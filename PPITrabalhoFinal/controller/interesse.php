<?php

class Interesse
{
  static function Create($pdo, $nome, $telefone, $mensagem, $dataHora, $idAnuncio)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO interesse (nome, telefone, mensagem, dataHora, idAnuncio)
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
      DELETE FROM interesse WHERE idInteresse = ?
      SQL
    );

    $stmt->execute([$idInteresse]);
  }
}