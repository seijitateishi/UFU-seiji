<?php

class Anunciante
{

  // Método estático para criar um novo anunciante por meio
  // da inserção na tabela 'anunciante' do BD.
  // Métodos estáticos estão associados à classe em si, e não a uma instância.
  // No PHP devem ser chamados com a sintaxe: NomeDaClasse::NomeDoMétodoEstático
  static function Create($pdo, $nome, $cpf, $email, $senhaHash, $telefone)
  {
    // Neste caso é necessário utilizar prepared statements para prevenir
    // inj. de S Q L, pois temos parâmetros (dados do anunciante) fornecidos pelo usuário.
    // Repare que a coluna Id foi omitida por ser do tipo auto_increment.
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO anunciante (nome, cpf, email, senhaHash, telefone)
      VALUES (?, ?, ?, ?, ?)
      SQL
    );

    // Executa a declaração preparada fornecendo valores aos parâmetros (pontos-de-interrogação)
    $stmt->execute([$nome, $cpf, $email, $senhaHash, $telefone]);

    // retorna o Id do novo anunciante criado
    return $pdo->lastInsertId();
  }

  // Busca um anunciante na tabela a partir do Id e retorna
  // os dados na forma de um objeto PHP.
  static function Get($pdo, $id)
  {
    // $pdo, $nome, $cpf, $email, $senhaHash, $telefone
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

  // Retorna os 30 anunciantes iniciais da tabela na forma de um array de objetos.
  static function GetFirst20($pdo)
  {
    // Neste exemplo não é necessário utilizar prepared statements
    // porque não há a possibilidade de inj. de S Q L, 
    // pois nenhum parâmetro do usuário é utilizado na query SQL. 
    $stmt = $pdo->query(
      <<<SQL
      SELECT id, nome, cpf, email, telefone
      FROM anunciante
      LIMIT 20
      SQL
    );

    // Resgata os dados dos anunciantes como um array de objetos
    $arrayAnunciantes = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $arrayAnunciantes;
  }

  // Método estático para excluir um anunciante dado o seu Id
  public static function Remove($pdo, $id)
  {
    $sql = <<<SQL
    DELETE 
    FROM anunciante
    WHERE id = $id
    AND NOT EXISTS (SELECT * FROM anuncio WHERE idAnunciante = $id)
    LIMIT 1
    SQL;


    // Necessário utilizar prepared statements devido ao 
    // parâmetro informado pelo usuário
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
  }

  public static function buscarPorEmail($pdo, $email)
  {
    $stmt = $pdo->prepare(
      <<<SQL
      SELECT id, nome, cpf, email, senhaHash, telefone
      FROM anunciante
      WHERE email = $email
      SQL
    );

    $stmt->execute([$email]);
    if ($stmt->rowCount() == 0)
      throw new Exception("anunciante não localizado");

    $anunciante = $stmt->fetch(PDO::FETCH_OBJ);
    return $anunciante;
  }
}
