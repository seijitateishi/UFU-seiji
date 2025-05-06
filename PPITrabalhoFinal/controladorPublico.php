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
      session_start();
  
      $email = $_POST["email"] ?? "";
      $senha = $_POST["senha"] ?? "";
  
      try {
          $usuario = Anunciante::buscarPorEmail($pdo, $email);
  
          if ($usuario && password_verify($senha, $usuario->senhaHash)) {
              $_SESSION["usuario_id"] = $usuario->id;
              $_SESSION["usuario_nome"] = $usuario->nome;
              $_SESSION["usuario_email"] = $usuario->email;
  
              header('Content-Type: application/json; charset=utf-8');
              echo json_encode(["sucesso" => true, "mensagem" => "Login realizado com sucesso"]);
          } else {
              http_response_code(401);
              echo json_encode(["sucesso" => false, "mensagem" => "Email ou senha inválidos"]);
          }
      } catch (Exception $e) {
          echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
      }
      break;
  default:
    echo json_encode(["sucesso" => false, "erro" => "Ação não disponível"]);
}
