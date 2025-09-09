import PySimpleGUI as psg
import numpy as np
import cv2
import csv
import os
from sklearn.linear_model import LinearRegression

# Imports para os gráficos Matplotlib
import matplotlib

matplotlib.use('TkAgg')  # Define o backend para integração com a UI
from matplotlib.figure import Figure
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg

# --- Variáveis Globais de Estado ---
app_state = {
    "calib_image_original": None, "calib_image_resized": None,
    "sample_roi": None, "background_roi": None, "calibration_data": [],
    "regression_model": None, "model_context": {}
}

# --- Variáveis de Interação com Mouse ---
mouse_interaction = {
    "start_point": None, "end_point": None, "drawing_mode": None,
    "dragging": False, "prior_rect": None
}

# --- Constantes e Configurações ---
COLOR_MODELS = [
    'RGB - Canal Azul', 'HSV - Matiz (H)', 'HSV - Saturação (S)', 'L*a*b* - Canal b*',
    'RGB - Todos os Canais', 'HSV - Todos os Canais', 'L*a*b* - Todos os Canais'
]
IMG_MAX_WIDTH, IMG_MAX_HEIGHT = 800, 600


# --- Funções de Lógica (separadas da UI) ---

def scale_rect_to_original(rect, original_shape, resized_shape):
    if not all([rect, original_shape, resized_shape]): return None
    x1_g, y1_g = rect[0];
    x2_g, y2_g = rect[1]
    h_orig, w_orig = original_shape[:2];
    h_res, w_res = resized_shape[:2]
    w_ratio, h_ratio = w_orig / w_res, h_orig / h_res
    x = min(int(x1_g * w_ratio), int(x2_g * w_ratio))
    y = min(int(y1_g * h_ratio), int(y2_g * h_ratio))
    w = abs(int(x1_g * w_ratio) - int(x2_g * w_ratio))
    h = abs(int(y1_g * h_ratio) - int(y2_g * h_ratio))
    return x, y, w, h


def extract_color_feature(roi_image, method):
    if roi_image is None or roi_image.size == 0: return None
    if 'HSV' in method:
        hsv = cv2.cvtColor(roi_image, cv2.COLOR_BGR2HSV)
        h, s, v, _ = cv2.mean(hsv)
        if method == 'HSV - Matiz (H)': return [h]
        if method == 'HSV - Saturação (S)': return [s]
        if method == 'HSV - Todos os Canais': return [h, s, v]
    elif 'L*a*b*' in method:
        lab = cv2.cvtColor(roi_image, cv2.COLOR_BGR2Lab)
        l, a, b, _ = cv2.mean(lab)
        if method == 'L*a*b* - Canal b*': return [b]
        if method == 'L*a*b* - Todos os Canais': return [l, a, b]
    elif 'RGB' in method:
        b, g, r, _ = cv2.mean(roi_image)
        if method == 'RGB - Canal Azul': return [b]
        if method == 'RGB - Todos os Canais': return [b, g, r]
    return None


def analyze_concentration(image, roi_sample, roi_background, model, context):
    if model is None: return None
    x, y, w, h = roi_sample
    sample_roi_img = image[y:y + h, x:x + w]
    avg_color_vec = np.array(extract_color_feature(sample_roi_img, context["color_model"]))
    if roi_background:
        x_bg, y_bg, w_bg, h_bg = roi_background
        bg_roi_img = image[y_bg:y_bg + h_bg, x_bg:x_bg + w_bg]
        avg_bg_color_vec = np.array(extract_color_feature(bg_roi_img, context["color_model"]))
        corrected_color_vec = np.abs(avg_color_vec - avg_bg_color_vec)
    else:
        corrected_color_vec = avg_color_vec
    return model.predict([corrected_color_vec])[0]


def rgb_to_wavelength(r, g, b):
    r, g, b = r / 255.0, g / 255.0, b / 255.0
    max_val = max(r, g, b);
    min_val = min(r, g, b)
    chroma = max_val - min_val
    if chroma == 0: return None
    if r == max_val:
        hue = (g - b) / chroma
    elif g == max_val:
        hue = 2.0 + (b - r) / chroma
    else:
        hue = 4.0 + (r - g) / chroma
    hue *= 60.0
    if hue < 0: hue += 360.0
    return max(380, min(780, 620 - 170 * (hue / 300)))


def draw_figure(canvas, figure):
    """Função auxiliar para desenhar um gráfico Matplotlib em um Canvas PySimpleGUI."""
    figure_canvas_agg = FigureCanvasTkAgg(figure, canvas)
    figure_canvas_agg.draw()
    figure_canvas_agg.get_tk_widget().pack(side='top', fill='both', expand=1)
    return figure_canvas_agg


# --- Definição do Layout da Interface ---
image_column = [[psg.Graph(
    canvas_size=(IMG_MAX_WIDTH, IMG_MAX_HEIGHT),
    graph_bottom_left=(0, IMG_MAX_HEIGHT), graph_top_right=(IMG_MAX_WIDTH, 0),
    key='-GRAPH-', drag_submits=True, change_submits=True, background_color='darkgray'
)]]

controls_column = [
    [psg.Frame("1. Carregar Imagem de Calibração", [
        [psg.Input(key="-FILE_PATH-", size=(30, 1), readonly=True),
         psg.FileBrowse("Procurar", file_types=(
             ("Imagens", "*.jpg *.JPG *.jpeg *.JPEG *.png *.PNG"), ("Todos os arquivos", "*.*"))),
         psg.Button("Carregar Imagem", key='-LOAD_IMAGE-')]
    ])],
    [psg.Frame("2. Definir Regiões de Interesse (ROI)", [
        [psg.Button("Definir ROI de Amostra", key='-DRAW_SAMPLE_ROI-', disabled=True),
         psg.Text("Status: N/A", key='-SAMPLE_ROI_STATUS-')],
        [psg.Button("Definir ROI de Fundo", key='-DRAW_BG_ROI-', disabled=True),
         psg.Text("Status: N/A", key='-BG_ROI_STATUS-')]
    ])],
    [psg.Frame("3. Adicionar Ponto de Calibração", [
        [psg.Text("Concentração:"), psg.Input(key='-CONCENTRATION-', size=(10, 1))],
        [psg.Text("Modelo de Cor:"),
         psg.Combo(COLOR_MODELS, default_value=COLOR_MODELS[0], key='-COLOR_MODEL-', readonly=True)],
        [psg.Button("Adicionar Ponto", key='-ADD_POINT-', disabled=True)]
    ])],
    [psg.Frame("4. Calibração", [
        [psg.Table(values=[], headings=['Características de Cor', 'Concentração'], auto_size_columns=True,
                   justification='right', key='-CALIB_TABLE-', num_rows=6, expand_x=True)],
        [psg.Button("Calibrar Modelo", key='-CALIBRATE-', disabled=True), psg.Text("", key='-MODEL_STATUS-')]
    ])],
    [psg.Frame("5. Análise de Amostras", [
        [psg.Input(key='-ANALYSIS_FILE_PATH-', size=(30, 1), readonly=True),
         psg.FileBrowse("Procurar", file_types=(
             ("Imagens", "*.jpg *.JPG *.jpeg *.JPEG *.png *.PNG"), ("Todos os arquivos", "*.*"))),
         psg.Button("Analisar Imagem", key='-ANALYZE_IMAGE-', disabled=True)],
        [psg.Text("Concentração Prevista:", size=(20, 1)),
         psg.Text("", size=(20, 1), key='-ANALYSIS_RESULT-', font=("Helvetica", 12, "bold"), text_color="yellow")],
        [psg.T("_" * 35)],
        [psg.Button("Analisar Diretório (em Lote)", key='-ANALYZE_DIRECTORY-', disabled=True, expand_x=True)]
    ])],
    [psg.Frame("6. Ferramentas e Relatórios", [
        [psg.Button("Detectar Círculos na ROI", key='-DETECT_CIRCLES-', disabled=True)],
        [psg.Text("Qualidade JPEG:"), psg.Input("95", key="-JPEG_QUALITY-", size=(5, 1)),
         psg.Button("Comprimir Imagem", key="-COMPRESS_IMAGE-", disabled=True)],
        [psg.T("_" * 35)],
        # --- NOVO BOTÃO ADICIONADO ---
        [psg.Button("Gerar Imagens Comprimidas (Lote)", key='-BATCH_COMPRESS-', expand_x=True)],
        [psg.Button("Analisar Espectro (nm)", key='-ANALYZE_SPECTRUM-', disabled=True, expand_x=True)],
        [psg.Button("Gerar Gráfico Compressão vs. Erro", key='-COMPRESSION_REPORT-', disabled=True, expand_x=True)]
    ])]
]

layout = [[psg.Column(image_column), psg.Column(controls_column, element_justification='center')]]

window = psg.Window("Analisador de Imagens Bioquímicas (GBC213)", layout, finalize=True)
graph = window['-GRAPH-']


# --- Funções de UI movidas para FORA do loop ---
def update_image_display(img_to_show):
    graph.erase()
    img_bytes = cv2.imencode(".png", img_to_show)[1].tobytes()
    graph.draw_image(data=img_bytes, location=(0, 0))


def update_button_states():
    img_loaded = app_state["calib_image_original"] is not None
    roi_defined = app_state["sample_roi"] is not None
    model_trained = app_state["regression_model"] is not None
    window['-DRAW_SAMPLE_ROI-'].update(disabled=not img_loaded)
    window['-DRAW_BG_ROI-'].update(disabled=not img_loaded)
    window['-ADD_POINT-'].update(disabled=not roi_defined)
    window['-DETECT_CIRCLES-'].update(disabled=not roi_defined)
    window['-CALIBRATE-'].update(disabled=not app_state["calibration_data"])
    window['-ANALYZE_IMAGE-'].update(disabled=not model_trained)
    window['-ANALYZE_DIRECTORY-'].update(disabled=not model_trained)
    window['-COMPRESS_IMAGE-'].update(disabled=not img_loaded)
    window['-ANALYZE_SPECTRUM-'].update(disabled=not roi_defined)
    window['-COMPRESSION_REPORT-'].update(disabled=not model_trained)
    window['-COLOR_MODEL-'].update(disabled=model_trained)


# --- Loop Principal de Eventos ---
while True:
    try:
        event, values = window.read()
        if event == "Exit" or event == psg.WIN_CLOSED:
            break

        # --- Eventos ---
        if event == '-LOAD_IMAGE-':
            filepath = values['-FILE_PATH-']
            if not (filepath and os.path.exists(filepath)): continue
            app_state["calib_image_original"] = cv2.imdecode(np.fromfile(filepath, dtype=np.uint8), cv2.IMREAD_COLOR)
            if app_state["calib_image_original"] is not None:
                h, w, _ = app_state["calib_image_original"].shape
                app_state["calib_image_resized"] = cv2.resize(app_state["calib_image_original"],
                                                              (IMG_MAX_WIDTH, int(h * IMG_MAX_WIDTH / w)))
                update_image_display(app_state["calib_image_resized"])
                update_button_states()
            else:
                psg.popup_error(f"OpenCV não conseguiu decodificar a imagem:\n{filepath}")

        elif event in ('-DRAW_SAMPLE_ROI-', '-DRAW_BG_ROI-'):
            mouse_interaction["drawing_mode"] = 'sample' if event == '-DRAW_SAMPLE_ROI-' else 'background'
            window['-SAMPLE_ROI_STATUS-' if event == '-DRAW_SAMPLE_ROI-' else '-BG_ROI_STATUS-'].update(
                "Status: Desenhando...", text_color="blue")

        elif event == '-GRAPH-':
            if mouse_interaction["drawing_mode"]:
                x, y = values['-GRAPH-']
                if not mouse_interaction["dragging"]:
                    mouse_interaction["start_point"] = (x, y);
                    mouse_interaction["dragging"] = True
                else:
                    mouse_interaction["end_point"] = (x, y)
                if mouse_interaction["prior_rect"]: graph.delete_figure(mouse_interaction["prior_rect"])
                if mouse_interaction["start_point"] and mouse_interaction["end_point"]:
                    mouse_interaction["prior_rect"] = graph.draw_rectangle(mouse_interaction["start_point"],
                                                                           mouse_interaction["end_point"],
                                                                           line_color="green")

        elif event.endswith("+UP"):
            if mouse_interaction["dragging"] and mouse_interaction["drawing_mode"]:
                roi_coords = scale_rect_to_original((mouse_interaction["start_point"], mouse_interaction["end_point"]),
                                                    app_state["calib_image_original"].shape,
                                                    app_state["calib_image_resized"].shape)
                if roi_coords:
                    if mouse_interaction["drawing_mode"] == "sample":
                        app_state["sample_roi"] = roi_coords
                        window['-SAMPLE_ROI_STATUS-'].update("Status: Definida!", text_color="green")
                    else:  # background
                        app_state["background_roi"] = roi_coords
                        window['-BG_ROI_STATUS-'].update("Status: Definida!", text_color="green")
                if mouse_interaction["prior_rect"]: graph.delete_figure(mouse_interaction["prior_rect"])
                mouse_interaction = {k: None for k in mouse_interaction};
                mouse_interaction["dragging"] = False
                update_button_states()

        elif event == '-ADD_POINT-':
            try:
                concentration = float(values['-CONCENTRATION-'])
                x, y, w, h = app_state["sample_roi"]
                roi_img = app_state["calib_image_original"][y:y + h, x:x + w]
                features = extract_color_feature(roi_img, values['-COLOR_MODEL-'])
                if app_state["background_roi"]:
                    x_bg, y_bg, w_bg, h_bg = app_state["background_roi"]
                    bg_roi_img = app_state["calib_image_original"][y_bg:y_bg + h_bg, x_bg:x_bg + w_bg]
                    bg_features = extract_color_feature(bg_roi_img, values['-COLOR_MODEL-'])
                    features = np.abs(np.array(features) - np.array(bg_features)).tolist()
                app_state["calibration_data"].append({"features": features, "concentration": concentration})
                table_data = [[[f"{feat:.2f}" for feat in d["features"]], d["concentration"]] for d in
                              app_state["calibration_data"]]
                window['-CALIB_TABLE-'].update(values=table_data)
                window['-CONCENTRATION-'].update('')  # MELHORIA: Limpa o campo após adicionar
                update_button_states()
            except (ValueError, TypeError):
                psg.popup_error("Verifique se a concentração é um número válido e se a ROI foi definida.")

        elif event == '-CALIBRATE-':
            if len(app_state["calibration_data"]) < 2:
                psg.popup_error("São necessários pelo menos 2 pontos para calibrar.")
            else:
                X = [d["features"] for d in app_state["calibration_data"]]
                y = [d["concentration"] for d in app_state["calibration_data"]]
                app_state["regression_model"] = LinearRegression().fit(X, y)
                score = app_state["regression_model"].score(X, y)
                app_state["model_context"] = {"color_model": values['-COLOR_MODEL-'], "r2_score": score}
                window['-MODEL_STATUS-'].update(f"Modelo Treinado (R²={score:.4f})", text_color="green")
                update_button_states()
                psg.popup("Calibração Concluída!", f"R² (qualidade): {score:.4f}")

        elif event == '-ANALYZE_IMAGE-':
            filepath = values['-ANALYSIS_FILE_PATH-']
            if not (filepath and os.path.exists(filepath)):
                psg.popup_error("Selecione um arquivo de imagem válido.")
            else:
                analysis_img = cv2.imdecode(np.fromfile(filepath, dtype=np.uint8), cv2.IMREAD_COLOR)
                if analysis_img is not None:
                    predicted_conc = analyze_concentration(analysis_img, app_state["sample_roi"],
                                                           app_state["background_roi"], app_state["regression_model"],
                                                           app_state["model_context"])
                    if predicted_conc is not None:
                        window['-ANALYSIS_RESULT-'].update(f"{predicted_conc:.4f}")
                else:
                    psg.popup_error("Não foi possível carregar a imagem de análise.")

        elif event == '-ANALYZE_DIRECTORY-':
            folder_path = psg.popup_get_folder("Selecione a pasta com as imagens para análise")
            if not folder_path: continue
            save_path = psg.popup_get_file("Salvar resultados como...", save_as=True,
                                           file_types=(("CSV Files", "*.csv"),))
            if not save_path: continue

            image_files = [f for f in os.listdir(folder_path) if f.lower().endswith(('.png', '.jpg', '.jpeg'))]
            if not image_files:
                psg.popup("Nenhuma imagem encontrada no diretório selecionado.")
                continue

            results = []
            for i, filename in enumerate(image_files):
                if not psg.one_line_progress_meter("Analisando Diretório", i + 1, len(image_files), filename):
                    break
                filepath = os.path.join(folder_path, filename)
                image = cv2.imdecode(np.fromfile(filepath, dtype=np.uint8), cv2.IMREAD_COLOR)
                if image is not None:
                    pred = analyze_concentration(image, app_state["sample_roi"], app_state["background_roi"],
                                                 app_state["regression_model"], app_state["model_context"])
                    if pred is not None:
                        results.append({"imagem": filename, "concentracao_prevista": pred})

            if results:
                with open(save_path, 'w', newline='', encoding='utf-8') as f:
                    writer = csv.DictWriter(f, fieldnames=results[0].keys())
                    writer.writeheader()
                    writer.writerows(results)
                psg.popup("Sucesso", f"Análise em lote concluída. Resultados salvos em:\n{save_path}")
            else:
                psg.popup("Análise em lote concluída, mas nenhum resultado foi gerado.")

        elif event == '-COMPRESS_IMAGE-':
            try:
                quality = int(values['-JPEG_QUALITY-'])
                if not (0 <= quality <= 100): raise ValueError("Qualidade deve ser entre 0 e 100")
                save_path = psg.popup_get_file("Salvar imagem comprimida como...", save_as=True,
                                               file_types=(("JPEG Image", "*.jpg"),))
                if save_path:
                    if not save_path.lower().endswith(".jpg"): save_path += ".jpg"
                    cv2.imwrite(save_path, app_state["calib_image_original"], [int(cv2.IMWRITE_JPEG_QUALITY), quality])
                    psg.popup(f"Imagem salva com qualidade {quality}")
            except ValueError as e:
                psg.popup_error(str(e))

        # --- NOVA FUNCIONALIDADE ADICIONADA ---
        elif event == '-BATCH_COMPRESS-':
            source_folder = psg.popup_get_folder("1. Selecione a pasta com as imagens originais")
            if not source_folder: continue

            output_folder = psg.popup_get_folder("2. Selecione a pasta onde os resultados serão salvos")
            if not output_folder: continue

            qualities = [95, 75, 50, 25]
            image_files = [f for f in os.listdir(source_folder) if f.lower().endswith(('.png', '.jpg', '.jpeg'))]

            if not image_files:
                psg.popup("Nenhuma imagem encontrada na pasta de origem.")
                continue

            for i, filename in enumerate(image_files):
                if not psg.one_line_progress_meter("Compressão em Lote", i + 1, len(image_files), filename):
                    break

                original_path = os.path.join(source_folder, filename)
                original_image = cv2.imdecode(np.fromfile(original_path, dtype=np.uint8), cv2.IMREAD_COLOR)
                if original_image is None: continue

                for q in qualities:
                    # Cria a pasta de destino para a qualidade específica
                    quality_folder_path = os.path.join(output_folder, f"jpeg_{q}")
                    os.makedirs(quality_folder_path, exist_ok=True)

                    # Salva a imagem comprimida
                    output_path = os.path.join(quality_folder_path, os.path.splitext(filename)[0] + '.jpg')
                    cv2.imwrite(output_path, original_image, [int(cv2.IMWRITE_JPEG_QUALITY), q])

            psg.popup("Compressão em lote concluída!",
                      f"As imagens comprimidas foram salvas em subpastas dentro de:\n{output_folder}")


        elif event == '-DETECT_CIRCLES-':
            x, y, w, h = app_state["sample_roi"]
            roi_img = app_state["calib_image_original"][y:y + h, x:x + w]
            gray_roi = cv2.cvtColor(roi_img, cv2.COLOR_BGR2GRAY);
            gray_roi = cv2.medianBlur(gray_roi, 5)
            circles = cv2.HoughCircles(gray_roi, cv2.HOUGH_GRADIENT, dp=1, minDist=20, param1=50, param2=30,
                                       minRadius=10, maxRadius=50)
            img_with_circles = app_state["calib_image_resized"].copy()
            num_circles = 0
            if circles is not None:
                circles = np.uint16(np.around(circles));
                num_circles = len(circles[0])
                scale_x = app_state["calib_image_resized"].shape[1] / app_state["calib_image_original"].shape[1]
                scale_y = app_state["calib_image_resized"].shape[0] / app_state["calib_image_original"].shape[0]
                for i in circles[0, :]:
                    center_orig = (x + i[0], y + i[1]);
                    radius_orig = i[2]
                    center_display = (int(center_orig[0] * scale_x), int(center_orig[1] * scale_y))
                    radius_display = int(radius_orig * ((scale_x + scale_y) / 2))
                    cv2.circle(img_with_circles, center_display, radius_display, (255, 0, 255), 2)
                update_image_display(img_with_circles)
            psg.popup(f"{num_circles} círculo(s) detectado(s).")

        elif event == '-ANALYZE_SPECTRUM-':
            x, y, w, h = app_state["sample_roi"]
            roi_img = app_state["calib_image_original"][y:y + h, x:x + w]
            b, g, r, _ = cv2.mean(roi_img)
            wavelength = rgb_to_wavelength(r, g, b)

            fig = Figure(figsize=(6, 4), dpi=100)
            ax = fig.add_subplot(111)
            if wavelength:
                ax.bar([wavelength], [1], color=(r / 255, g / 255, b / 255), width=20)
                ax.set_title(f"Espectro Aproximado: {wavelength:.1f} nm")
            else:
                ax.set_title("Cor Acromática (sem pico de espectro)")
            ax.set_xlabel("Comprimento de Onda (nm)");
            ax.set_ylabel("Intensidade Relativa")
            ax.set_xlim(380, 780);
            ax.set_ylim(0, 1.2);
            ax.grid(True)

            plot_layout = [[psg.Canvas(key='-PLOT_CANVAS-')]]
            plot_window = psg.Window("Análise Espectral", plot_layout, finalize=True, modal=True)
            draw_figure(plot_window['-PLOT_CANVAS-'].TKCanvas, fig)
            plot_window.read()
            plot_window.close()

        elif event == '-COMPRESSION_REPORT-':
            baseline_pred = analyze_concentration(app_state["calib_image_original"], app_state["sample_roi"],
                                                  app_state["background_roi"], app_state["regression_model"],
                                                  app_state["model_context"])
            if baseline_pred is None: continue
            qualities = [100, 95, 75, 50, 25, 10];
            results = []
            for i, q in enumerate(qualities):
                if not psg.one_line_progress_meter("Gerando Relatório", i + 1, len(qualities),
                                                   f"Analisando Qualidade {q}%"):
                    break
                _, buffer = cv2.imencode(".jpg", app_state["calib_image_original"], [int(cv2.IMWRITE_JPEG_QUALITY), q])
                compressed_img = cv2.imdecode(buffer, cv2.IMREAD_COLOR)
                pred = analyze_concentration(compressed_img, app_state["sample_roi"], app_state["background_roi"],
                                             app_state["regression_model"], app_state["model_context"])
                error = abs(pred - baseline_pred);
                size_kb = len(buffer) / 1024
                results.append({"quality": q, "size_kb": size_kb, "error": error})

            fig = Figure(figsize=(8, 6), dpi=100)
            ax = fig.add_subplot(111)
            ax.plot([r['size_kb'] for r in results], [r['error'] for r in results], marker='o', linestyle='--')
            ax.set_title("Impacto da Compressão JPEG no Erro de Medição")
            ax.set_xlabel("Tamanho do Arquivo (KB)");
            ax.set_ylabel("Erro Absoluto na Predição")
            ax.grid(True);
            ax.invert_xaxis()
            for r in results: ax.text(r['size_kb'], r['error'], f" Q={r['quality']}", fontsize=9)

            plot_layout = [[psg.Canvas(key='-PLOT_CANVAS-')]]
            plot_window = psg.Window("Relatório de Compressão", plot_layout, finalize=True, modal=True)
            draw_figure(plot_window['-PLOT_CANVAS-'].TKCanvas, fig)
            plot_window.read()
            plot_window.close()

    # --- Tratamento de Erros Inesperados ---
    except Exception as e:
        psg.popup_error(f"Ocorreu um erro inesperado:\n\n{type(e).__name__}: {e}")

window.close()
