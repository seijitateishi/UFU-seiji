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
        
        // Adicionar fotos ao anúncio
        $anuncio->fotos = self::GetFotos($pdo, $anuncio->id);
        
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
        try {
            // Iniciar transação
            $pdo->beginTransaction();
            
            // Remover todas as fotos associadas
            require_once __DIR__ . "/foto.php";
            Foto::RemoveAllForAnuncio($pdo, $id);
            
            // Remover o anúncio
            $sql = <<<SQL
            DELETE FROM anuncio
            WHERE id = ?
            LIMIT 1
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            // Confirmar transação
            $pdo->commit();
        } catch (Exception $e) {
            // Em caso de erro, desfazer
            $pdo->rollBack();
            throw $e;
        }
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
        
        $anuncios = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Adicionar fotos a cada anúncio
        foreach ($anuncios as $anuncio) {
            $anuncio->fotos = self::GetFotos($pdo, $anuncio->id);
        }
        
        return $anuncios;
    }
    
    /**
     * Get all photo URLs for an ad
     * @param PDO $pdo Database connection
     * @param int $idAnuncio ID of the ad
     * @return array List of photo URLs
     */
    private static function GetFotos($pdo, $idAnuncio)
    {
        require_once __DIR__ . "/foto.php";
        
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT id, nome_arquivo
            FROM foto
            WHERE id_anuncio = ?
            SQL
        );

        $stmt->execute([$idAnuncio]);
        $fotos = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $fotoUrls = [];
        foreach ($fotos as $foto) {
            $fotoUrls[] = Foto::GetUrl($foto->nome_arquivo);
        }
        
        return $fotoUrls;
    }

    /**
     * Get all unique brands from active listings
     * @param PDO $pdo Database connection
     * @return array List of unique brands
     */
    static function GetUniqueBrands($pdo)
    {
        $stmt = $pdo->query(
            <<<SQL
            SELECT DISTINCT marca 
            FROM anuncio 
            ORDER BY marca
            SQL
        );
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * Get all unique models for a specific brand
     * @param PDO $pdo Database connection
     * @param string $marca Brand name
     * @return array List of unique models
     */
    static function GetModelosByMarca($pdo, $marca)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT DISTINCT modelo 
            FROM anuncio 
            WHERE marca = ? 
            ORDER BY modelo
            SQL
        );
        
        $stmt->execute([$marca]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * Get all unique cities for a specific brand and model
     * @param PDO $pdo Database connection
     * @param string $marca Brand name
     * @param string $modelo Model name
     * @return array List of unique cities
     */
    static function GetCidadesByMarcaModelo($pdo, $marca, $modelo)
    {
        $sql = "SELECT DISTINCT cidade FROM anuncio WHERE 1=1";
        $params = [];
        
        if (!empty($marca)) {
            $sql .= " AND marca = ?";
            $params[] = $marca;
        }
        
        if (!empty($modelo)) {
            $sql .= " AND modelo = ?";
            $params[] = $modelo;
        }
        
        $sql .= " ORDER BY cidade";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * Get anuncios with optional filters
     * @param PDO $pdo Database connection
     * @param array $filters Array with filter criteria
     * @return array Array of anuncios
     */
    static function GetWithFilters($pdo, $filters = [])
    {
        $where = [];
        $params = [];
        
        // Build WHERE clause based on provided filters
        if (!empty($filters['marca'])) {
            $where[] = "marca = ?";
            $params[] = $filters['marca'];
        }
        
        if (!empty($filters['modelo'])) {
            $where[] = "modelo = ?";
            $params[] = $filters['modelo'];
        }
        
        if (!empty($filters['cidade'])) {
            $where[] = "cidade = ?";
            $params[] = $filters['cidade'];
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT id, marca, modelo, ano, cor, quilometragem, descricao, valor, dataHora, estado, cidade, id_anunciante 
                FROM anuncio 
                $whereClause 
                ORDER BY dataHora DESC
                LIMIT 20";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $arrayAnuncios = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Adicionar fotos a cada anúncio
        foreach ($arrayAnuncios as $anuncio) {
            $anuncio->fotos = self::GetFotos($pdo, $anuncio->id);
        }
        
        return $arrayAnuncios;
    }
}
