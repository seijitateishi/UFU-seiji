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
      // Collect filter parameters
      $filters = [];
      
      if (!empty($_GET['marca'])) {
        $filters['marca'] = $_GET['marca'];
      }
      
      if (!empty($_GET['modelo'])) {
        $filters['modelo'] = $_GET['modelo'];
      }
      
      if (!empty($_GET['cidade'])) {
        $filters['cidade'] = $_GET['cidade'];
      }
      
      // Use the new GetWithFilters method
      $arrayClientes = Anuncio::GetWithFilters($pdo, $filters);
      
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($arrayClientes);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

  case "listarMarcas":
    //--------------------------------------------------------------------------------------
    try {
      $marcas = Anuncio::GetUniqueBrands($pdo);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($marcas);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;
    
  case "listarModelos":
    //--------------------------------------------------------------------------------------
    $marca = $_GET['marca'] ?? '';
    try {
      $modelos = Anuncio::GetModelosByMarca($pdo, $marca);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($modelos);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;
    
  case "listarCidades":
    //--------------------------------------------------------------------------------------
    $marca = $_GET['marca'] ?? '';
    $modelo = $_GET['modelo'] ?? '';
    try {
      $cidades = Anuncio::GetCidadesByMarcaModelo($pdo, $marca, $modelo);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($cidades);
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    break;

    case "login":
      require_once "utils/sessao.php";
  
      $email = $_POST["email"] ?? "";
      $senha = $_POST["senha"] ?? "";
  
      try {
          $usuario = Anunciante::buscarPorEmail($pdo, $email);
  
          if ($usuario && password_verify($senha, $usuario->senhaHash)) {
              // Usa a nova função de login para gerenciar a sessão
              fazerLogin($usuario);
  
              // Check if request is AJAX or direct browser request
              $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
              
              if ($isAjax) {
                  // For AJAX requests, return JSON
                  header('Content-Type: application/json; charset=utf-8');
                  echo json_encode(["sucesso" => true, "mensagem" => "Login realizado com sucesso"]);
              } else {
                  // For direct browser requests, redirect
                  header('Location: views/restricted/principal_interna.php');
                  exit;
              }
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
