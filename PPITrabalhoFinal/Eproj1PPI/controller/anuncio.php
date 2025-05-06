<?php

class Anuncio
{

    // Método estático para criar um novo cliente por meio
    // da inserção na tabela 'cliente' do BD.
    // Métodos estáticos estão associados à classe em si, e não a uma instância.
    // No PHP devem ser chamados com a sintaxe: NomeDaClasse::NomeDoMétodoEstático
    static function Create($pdo, $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $dataHora, $estado, $cidade, $id_anunciante)
    {
        // Neste caso é necessário utilizar prepared statements para prevenir
        // inj. de S Q L, pois temos parâmetros (dados do cliente) fornecidos pelo usuário.
        // Repare que a coluna Id foi omitida por ser do tipo auto_increment.
        $stmt = $pdo->prepare(
            <<<SQL
      INSERT INTO anuncio (marca, modelo, ano, cor, quilometragem, descricao, valor, dataHora, estado, cidade, id_anunciante)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
      SQL
        );

        // Executa a declaração preparada fornecendo valores aos parâmetros (pontos-de-interrogação)
        $stmt->execute([$marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $dataHora, $estado, $cidade, $id_anunciante]);

        // retorna o Id do novo anunciante criado
        return $pdo->lastInsertId();
    }

    // Busca um anuncio na tabela a partir do Id e retorna
    // os dados na forma de um objeto PHP.
    static function Get($pdo, $id)
    {
        //seleciona todos os dados do anuncio
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT *
            FROM anuncio
            WHERE id = ?
            SQL
        );

        $stmt->execute([$id]);
        if ($stmt->rowCount() == 0)
            throw new Exception("Anuncio não localizado");

        $anuncio = $stmt->fetch(PDO::FETCH_OBJ);
        return $anuncio;
    }

    // Retorna os 20 primeiros anuncios da tabela
    static function GetFirst20($pdo)
    {
        // Neste exemplo não é necessário utilizar prepared statements
        // porque não há a possibilidade de inj. de S Q L, 
        // pois nenhum parâmetro do usuário é utilizado na query SQL. 
        $stmt = $pdo->query(
            <<<SQL
            SELECT *
            FROM anuncio
            LIMIT 20
            SQL
        );

        // Resgata os dados dos anuncios como um array de objetos
        $arrayAnuncios = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $arrayAnuncios;
    }

    // Método estático para excluir um anuncio dado o seu Id
    public static function Remove($pdo, $id)
    {
        $sql = <<<SQL
        DELETE 
        FROM anuncio
        WHERE id = ?
        LIMIT 1
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }
}
