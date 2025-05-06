<?php

class Interesse
{

  // Método estático para criar um novo anunciante por meio
  // da inserção na tabela 'anunciante' do BD.
  // Métodos estáticos estão associados à classe em si, e não a uma instância.
  // No PHP devem ser chamados com a sintaxe: NomeDaClasse::NomeDoMétodoEstático
  static function Create($pdo, $nome, $telefone, $mensagem, $dataHora, $idAnuncio)
  {
    // Neste caso é necessário utilizar prepared statements para prevenir
    // inj. de S Q L, pois temos parâmetros (dados do anunciante) fornecidos pelo usuário.
    // Repare que a coluna Id foi omitida por ser do tipo auto_increment.
    $stmt = $pdo->prepare(
      <<<SQL
      INSERT INTO interesse (nome, telefone, mensagem, dataHora, idAnuncio)
      VALUES (?, ?, ?, ?, ?)
      SQL
    );

    // Executa a declaração preparada fornecendo valores aos parâmetros (pontos-de-interrogação)
    $stmt->execute([$nome, $telefone, $mensagem, $dataHora, $idAnuncio]);

    // retorna o Id do novo anunciante criado
    return $pdo->lastInsertId();
  }
}