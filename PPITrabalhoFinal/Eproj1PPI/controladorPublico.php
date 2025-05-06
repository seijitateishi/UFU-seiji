<?php
require_once "config/conexao.php";
require "controller/anunciante.php";
require "controller/anuncio.php";

// resgata a ação a ser executada
$acao = $_GET['acao'];

// conecta ao servidor do MySQL
$pdo = mysqlConnect();

switch ($acao) {

  case "criarAnunciante":
    //--------------------------------------------------------------------------------------    
    $nome = $_POST["nome"] ?? "";
    $cpf = $_POST["cpf"] ?? "";
    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";
    $telefone = $_POST["telefone"] ?? "";

    // gera o hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
      $novoAnunciante = Anunciante::Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($novoAnunciante);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;


  case "excluirAnunciante":
    //--------------------------------------------------------------------------------------
    $idCliente = $_GET["idCliente"] ?? "";
    try {
      Anunciante::Remove($pdo, $idCliente);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(["status" => "ok"]);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

  case "listarAnuncio":
    //--------------------------------------------------------------------------------------
    try {
      $anuncio = Anuncio::Get($pdo, $_GET["idAnuncio"]);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($anuncio);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

  case "listarAnuncios":
    //--------------------------------------------------------------------------------------
    try {
      $arrayClientes = Anuncio::GetFirst20($pdo);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($arrayClientes);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

  case "login":
    session_start(); // sempre antes de qualquer saída

    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";

    try {
      $usuario = Anunciante::buscarPorEmail($pdo, $email);

      if ($usuario && password_verify($senha, $usuario["senhaHash"])) {
        // Login válido, salva dados na sessão
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_email"] = $usuario["email"];
        $_SESSION["usuario_nome"] = $usuario["nome"];

        echo json_encode(["sucesso" => true]);
      } else {
        echo json_encode(["sucesso" => false, "erro" => "Email ou senha inválidos"]);
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(["sucesso" => false, "erro" => $e->getMessage()]);
    }
    break;

  //-----------------------------------------------------------------
  default:
    echo json_encode(["sucesso" => false, "erro" => "Ação não disponível"]);
}
