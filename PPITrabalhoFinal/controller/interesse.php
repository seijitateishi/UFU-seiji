<?php

class Interesse
{
  static function Create($pdo, $nome, $telefone, $mensagem, $dataHora, $idAnuncio)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO interesse (nome, telefone, mensagem, dataHora, id_anuncio)
      VALUES (?, ?, ?, ?, ?)
      SQL
    );

    $stmt->execute([$nome, $telefone, $mensagem, $dataHora, $idAnuncio]);

    return $pdo->lastInsertId();
  }

  static function Remove($pdo, $idInteresse)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      DELETE FROM interesse WHERE id = ?
      SQL
    );

    $stmt->execute([$idInteresse]);
  }

  static function Get($pdo, $idInteresse)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT i.id, i.nome, i.telefone, i.mensagem, i.dataHora, 
             i.id_anuncio,
             a.marca, a.modelo, a.ano
      FROM interesse i
      JOIN anuncio a ON i.id_anuncio = a.id
      WHERE i.id = ?
      SQL
    );

    $stmt->execute([$idInteresse]);
    
    if ($stmt->rowCount() == 0) {
      return null;
    }

    return $stmt->fetch(PDO::FETCH_OBJ);
  }

  static function GetByAnuncio($pdo, $idAnuncio)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT i.id, i.nome, i.telefone, i.mensagem, i.dataHora
      FROM interesse i
      WHERE i.id_anuncio = ?
      ORDER BY i.dataHora DESC
      SQL
    );

    $stmt->execute([$idAnuncio]);
    
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  static function GetForMyAds($pdo, $idAnunciante)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT i.id, i.nome, i.telefone, i.mensagem, i.dataHora, i.id_anuncio,
             a.marca, a.modelo, a.ano
      FROM interesse i
      JOIN anuncio a ON i.id_anuncio = a.id
      WHERE a.id_anunciante = ?
      ORDER BY i.dataHora DESC
      SQL
    );

    $stmt->execute([$idAnunciante]);
    
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  static function CountByAnuncio($pdo, $idAnuncio)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT COUNT(*) as total
      FROM interesse
      WHERE id_anuncio = ?
      SQL
    );

    $stmt->execute([$idAnuncio]);
    
    $result = $stmt->fetch(PDO::FETCH_OBJ);
    return $result->total;
  }
}