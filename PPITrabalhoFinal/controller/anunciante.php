<?php

class Anunciante
{

  static function Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone)
  {

    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO anunciante (nome, cpf, email, senhaHash, telefone)
      VALUES (?, ?, ?, ?, ?)
      SQL
    );

    $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);

    return $pdo->lastInsertId();
  }


  static function Get($pdo, $id)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT  nome, cpf, email, telefone
      FROM anunciante
      WHERE id = ?
      SQL
    );

    $stmt->execute([$id]);
    if ($stmt->rowCount() == 0)
      throw new Exception("anunciante não localizado");

    $anunciante = $stmt->fetch(PDO::FETCH_OBJ);
    return $anunciante;
  }

  static function GetFirst20($pdo)
  {
    $stmt = $pdo->query(
      <<<SQL
      SELECT id, nome, cpf, email, telefone
      FROM anunciante
      LIMIT 20
      SQL
    );

    $arrayAnunciantes = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $arrayAnunciantes;
  }

  public static function Remove($pdo, $id)
  {
    $sql = <<<SQL
    DELETE 
    FROM anunciante
    WHERE id = ?
    LIMIT 1
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }

  public static function buscarPorEmail($pdo, $email)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, cpf, email, senhaHash, telefone
      FROM anunciante
      WHERE email = ?
      SQL
    );

    $stmt->execute([$email]);
    if ($stmt->rowCount() == 0)
      throw new Exception("anunciante não localizado");

    $anunciante = $stmt->fetch(PDO::FETCH_OBJ);
    return $anunciante;
  }
}
