<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "controller/anunciante.php";
require "controller/anuncio.php";
require "controller/interesse.php";
require "controller/foto.php";
require "config/conexao.php";

$acao = $_GET['acao'];

$pdo = mysqlConnect();

switch ($acao) {

    case "criarAnuncio":
        // Inclui o arquivo de sessão e verifica se o usuário está logado
        require_once "utils/sessao.php";
        
        if (!validarSessao()) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }
        
        // Obtém os dados do usuário logado
        $usuario = getUsuarioLogado();
        
        $marca = $_POST["marca"] ?? "";
        $modelo = $_POST["modelo"] ?? "";
        $ano = $_POST["ano"] ?? "";
        $cor = $_POST["cor"] ?? "";
        $quilometragem = $_POST["quilometragem"] ?? "";
        $descricao = $_POST["descricao"] ?? "";
        $valor = $_POST["valor"] ?? "";
        $dataHora = date("Y-m-d H:i:s");
        $estado = $_POST["estado"] ?? "";
        $cidade = $_POST["cidade"] ?? "";
        $id_anunciante = $usuario->id; // Usa o ID do usuário logado

        try {
            // Verificação de arquivos de imagem
            if (!isset($_FILES['fotos']) || $_FILES['fotos']['error'] != UPLOAD_ERR_OK) {
                $errorMessage = "Erro no upload da imagem: ";
                if (isset($_FILES['fotos'])) {
                    switch($_FILES['fotos']['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            $errorMessage .= "Tamanho do arquivo excede o limite definido no php.ini.";
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $errorMessage .= "Tamanho do arquivo excede o limite definido no formulário.";
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $errorMessage .= "O upload foi parcial.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $errorMessage .= "Nenhum arquivo foi enviado.";
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $errorMessage .= "Diretório temporário não encontrado.";
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $errorMessage .= "Falha ao escrever o arquivo no disco.";
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $errorMessage .= "O upload foi interrompido por uma extensão PHP.";
                            break;
                        default:
                            $errorMessage .= "Erro desconhecido.";
                    }
                } else {
                    $errorMessage .= "Nenhuma imagem enviada.";
                }
                
                // Se não for obrigatório, podemos continuar
                // Caso seja obrigatório, descomentar a linha abaixo:
                // throw new Exception($errorMessage);
            }
            
            // Iniciar transação para garantir que tudo seja salvo corretamente
            $pdo->beginTransaction();
            
            // Criar o anúncio
            $idAnuncio = Anuncio::Create(
                $pdo, 
                $marca, 
                $modelo, 
                $ano, 
                $cor, 
                $quilometragem, 
                $descricao, 
                $valor, 
                $dataHora, 
                $estado, 
                $cidade, 
                $id_anunciante
            );
            
            // Verificar se há foto para salvar
            $fotoId = null;
            $fotoUrl = null;
            
            if (isset($_FILES['fotos']) && $_FILES['fotos']['error'] === UPLOAD_ERR_OK) {
                $fotoId = Foto::Create($pdo, $idAnuncio, $_FILES['fotos']);
                
                if ($fotoId) {
                    $fotoQuery = $pdo->prepare("SELECT nome_arquivo FROM foto WHERE id = ?");
                    $fotoQuery->execute([$fotoId]);
                    $fotoInfo = $fotoQuery->fetch(PDO::FETCH_OBJ);
                    
                    if ($fotoInfo) {
                        $fotoUrl = Foto::GetUrl($fotoInfo->nome_arquivo);
                    }
                }
            }
            
            // Confirmar a transação
            $pdo->commit();
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                "sucesso" => true, 
                "mensagem" => "Anúncio criado com sucesso!", 
                "anuncio" => $idAnuncio,
                "foto" => $fotoUrl,
                "debug" => [
                    "fotoId" => $fotoId,
                    "fotoUrl" => $fotoUrl,
                    "uploadDir" => __DIR__ . '/uploads/',
                    "fileExists" => $fotoInfo ? file_exists(__DIR__ . '/uploads/' . $fotoInfo->nome_arquivo) : false
                ]
            ]);
        } catch (Exception $e) {
            // Em caso de erro, desfazer todas as alterações
            $pdo->rollBack();
            
            http_response_code(500);
            echo json_encode([
                "sucesso" => false, 
                "mensagem" => "Erro ao criar anúncio: " . $e->getMessage(),
                "debug" => [
                    "error" => $e->getMessage(),
                    "trace" => $e->getTraceAsString(),
                    "files" => isset($_FILES) ? print_r($_FILES, true) : "No files"
                ]
            ]);
        }
        break;

    case "excluirAnuncio":
        $idAnuncio = $_GET["idAnuncio"] ?? "";
        try {
            Anuncio::Remove($pdo, $idAnuncio);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["status" => "ok"]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        break;

    case "excluirInteresse":
        require_once "utils/sessao.php";
        
        if (!validarSessao()) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }
        
        $idInteresse = $_GET["idInteresse"] ?? "";
        
        try {
            // Verificar se o interesse existe e pertence a um anúncio do usuário logado
            $interesse = Interesse::Get($pdo, $idInteresse);
            if (!$interesse) {
                http_response_code(404);
                echo json_encode(["sucesso" => false, "mensagem" => "Interesse não encontrado"]);
                exit;
            }
            
            $usuario = getUsuarioLogado();
            
            // Obtém o anúncio para verificar se o usuário logado é o dono
            $anuncio = Anuncio::Get($pdo, $interesse->id_anuncio);
            
            if ($anuncio->id_anunciante != $usuario->id) {
                http_response_code(403);
                echo json_encode(["sucesso" => false, "mensagem" => "Você não tem permissão para excluir este interesse"]);
                exit;
            }
            
            Interesse::Remove($pdo, $idInteresse);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["sucesso" => true, "mensagem" => "Interesse excluído com sucesso"]);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
        }
        break;

    case "listarInteressesAnuncio":
        require_once "utils/sessao.php";

        if (!validarSessao()) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }
        
        $idAnuncio = $_GET["idAnuncio"] ?? "";
        
        try {
            // Verificar se o anúncio pertence ao usuário logado
            $usuario = getUsuarioLogado();
            $anuncio = Anuncio::Get($pdo, $idAnuncio);
            
            if ($anuncio->id_anunciante != $usuario->id) {
                http_response_code(403);
                echo json_encode(["sucesso" => false, "mensagem" => "Você não tem permissão para visualizar os interesses deste anúncio"]);
                exit;
            }
            
            $interesses = Interesse::GetByAnuncio($pdo, $idAnuncio);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["sucesso" => true, "interesses" => $interesses]);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
        }
        break;
        
    case "listarTodosInteresses":
        require_once "utils/sessao.php";
        
        if (!validarSessao()) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }
        
        try {
            $usuario = getUsuarioLogado();
            $interesses = Interesse::GetForMyAds($pdo, $usuario->id);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["sucesso" => true, "interesses" => $interesses]);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
        }
        break;
        
    case "criarInteresse":
        require_once "utils/sessao.php";
        
        // Get form data
        $idAnuncio = $_POST["idAnuncio"] ?? "";
        $nome = $_POST["nome"] ?? "";
        $telefone = $_POST["telefone"] ?? "";
        $mensagem = $_POST["mensagem"] ?? "";
        $dataHora = date("Y-m-d H:i:s");
        
        // Validate required fields
        $errors = [];
        if (empty($idAnuncio)) $errors[] = "ID do anúncio não informado";
        if (empty($nome)) $errors[] = "Nome não informado";
        if (empty($telefone)) $errors[] = "Telefone não informado";
        if (empty($mensagem)) $errors[] = "Mensagem não informada";
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                "sucesso" => false, 
                "mensagem" => "Dados inválidos: " . implode(", ", $errors)
            ]);
            exit;
        }
        
        try {
            // Verify anuncio exists
            $anuncio = Anuncio::Get($pdo, $idAnuncio);
            
            // Create interest record
            $novoInteresse = Interesse::Create(
                $pdo, 
                $nome, 
                $telefone, 
                $mensagem, 
                $dataHora, 
                $idAnuncio
            );
            
            if ($novoInteresse) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "sucesso" => true, 
                    "mensagem" => "Interesse registrado com sucesso! O anunciante entrará em contato em breve.", 
                    "interesse" => $novoInteresse
                ]);
            } else {
                throw new Exception("Erro ao registrar interesse");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "sucesso" => false, 
                "mensagem" => "Erro ao registrar interesse: " . $e->getMessage()
            ]);
        }
        break;

    case "listarAnunciosUsuario":
        require_once "utils/sessao.php";
        
        if (!validarSessao()) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }
        
        // Obtém os dados do usuário logado
        $usuario = getUsuarioLogado();
        $idUsuario = $usuario->id;

        try {
            $anuncios = Anuncio::GetAnunciosPorUsuario($pdo, $idUsuario);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["sucesso" => true, "anuncios" => $anuncios]);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["sucesso" => false, "erro" => "Ação não disponível"]);
}
