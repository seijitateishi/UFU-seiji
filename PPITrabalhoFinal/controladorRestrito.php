<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "controller/anunciante.php";
require "controller/anuncio.php";
require "config/conexao.php";

$acao = $_GET['acao'];

$pdo = mysqlConnect();

switch ($acao) {

    case "criarAnuncio":
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
        $id_anunciante = 5;

        try {
            $novoAnuncio = Anuncio::Create($pdo, $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $dataHora, $estado, $cidade, $id_anunciante);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["sucesso" => true, "mensagem" => "Anúncio criado com sucesso!", "anuncio" => $novoAnuncio]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
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
        $idInteresse = $_GET["idInteresse"] ?? "";
        try {
            Interesse::Remove($pdo, $idInteresse);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(["status" => "ok"]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        break;

    case "listarAnunciosUsuario":
        session_start();
        $idUsuario = $_SESSION["usuario_id"] ?? null;

        if (!$idUsuario) {
            http_response_code(401);
            echo json_encode(["sucesso" => false, "mensagem" => "Usuário não autenticado"]);
            exit;
        }

        try {
            $anuncios = Anuncio::GetAnunciosPorUsuario($pdo, $idUsuario);
            if (empty($anuncios)) {
                echo json_encode(["sucesso" => false, "mensagem" => "Nenhum anúncio encontrado"]);
                exit;
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($anuncios);
        } catch (Exception $e) {
            echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["sucesso" => false, "erro" => "Ação não disponível"]);
}
