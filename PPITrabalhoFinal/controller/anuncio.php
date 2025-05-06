<?php

class Anuncio
{
    static function Create($pdo, $marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $dataHora, $estado, $cidade, $id_anunciante)
    {
        try {
            $stmt = $pdo->prepare(
                <<<SQL
            INSERT INTO anuncio (marca, modelo, ano, cor, quilometragem, descricao, valor, estado, cidade, id_anunciante)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            SQL
            );

            $stmt->execute([$marca, $modelo, $ano, $cor, $quilometragem, $descricao, $valor, $estado, $cidade, $id_anunciante]);

            return $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erro ao criar anúncio: " . $e->getMessage());
        }
    }

    static function Get($pdo, $id)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, dataHora, estado, cidade, id_anunciante
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


    static function GetFirst20($pdo)
    {
        $stmt = $pdo->query(
            <<<SQL
            SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, dataHora, estado, cidade, id_anunciante
            FROM anuncio
            LIMIT 20
            SQL
        );

        $arrayAnuncios = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $arrayAnuncios;
    }

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

    static function GetAnunciosPorUsuario($pdo, $idUsuario)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, dataHora, estado, cidade
            FROM anuncio
            WHERE id_anunciante = ?
            SQL
        );

        $stmt->execute([$idUsuario]);
        if ($stmt->rowCount() == 0) {
            throw new Exception("Nenhum anúncio encontrado para o usuário com ID: " . $idUsuario);
        }

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
