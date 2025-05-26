<?php

class Foto
{
    /**
     * Create a new photo entry in the database and save the file
     * @param PDO $pdo Database connection
     * @param int $idAnuncio ID of the ad this photo belongs to
     * @param array $file File data from $_FILES
     * @return string The ID of the newly created photo
     */
    static function Create($pdo, $idAnuncio, $file)
    {
        // Create upload directory if it doesn't exist
        $uploadDir = __DIR__ . '/../uploads/';
        
        // Make sure upload directory exists and is writable
        if (!file_exists($uploadDir)) {
            if (!@mkdir($uploadDir, 0777, true)) {
                throw new Exception("Não foi possível criar o diretório de upload: " . $uploadDir);
            }
        }
        
        if (!is_writable($uploadDir)) {
            @chmod($uploadDir, 0777);
            if (!is_writable($uploadDir)) {
                throw new Exception("O diretório de upload não é gravável: " . $uploadDir);
            }
        }

        // Check if file is valid
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("Arquivo de imagem inválido");
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Move uploaded file to destination
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception("Erro ao salvar imagem. Detalhes: " . print_r(error_get_last(), true));
        }

        // Store in database
        $stmt = $pdo->prepare(
            <<<SQL
            INSERT INTO foto (id_anuncio, nome_arquivo)
            VALUES (?, ?)
            SQL
        );

        $stmt->execute([$idAnuncio, $filename]);

        return $pdo->lastInsertId();
    }

    /**
     * Get all photos for an ad
     * @param PDO $pdo Database connection
     * @param int $idAnuncio ID of the ad
     * @return array List of photo objects
     */
    static function GetByAnuncio($pdo, $idAnuncio)
    {
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT id, nome_arquivo
            FROM foto
            WHERE id_anuncio = ?
            SQL
        );

        $stmt->execute([$idAnuncio]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get photo URL path
     * @param string $filename Filename of the photo
     * @return string Full URL to the photo
     */
    static function GetUrl($filename)
    {
        // Build absolute URL for website
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $pathToFile = '/uploads/' . $filename;
        
        if (strpos($host, 'infinityfreeapp.com') !== false) {
            // If on InfinityFree hosting
            return $pathToFile;
        } else {
            // Local development
            return $pathToFile;
        }
    }

    /**
     * Delete a photo
     * @param PDO $pdo Database connection
     * @param int $idFoto ID of the photo to delete
     * @return void
     */
    static function Remove($pdo, $idFoto)
    {
        // First get the filename
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT nome_arquivo FROM foto WHERE id = ?
            SQL
        );
        $stmt->execute([$idFoto]);
        $foto = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$foto) {
            throw new Exception("Foto não encontrada");
        }

        // Delete file from filesystem
        $filepath = __DIR__ . '/../uploads/' . $foto->nome_arquivo;
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        // Delete from database
        $stmt = $pdo->prepare(
            <<<SQL
            DELETE FROM foto WHERE id = ?
            SQL
        );
        $stmt->execute([$idFoto]);
    }

    /**
     * Delete all photos for an ad
     * @param PDO $pdo Database connection
     * @param int $idAnuncio ID of the ad
     * @return void
     */
    static function RemoveAllForAnuncio($pdo, $idAnuncio)
    {
        // First get all filenames
        $stmt = $pdo->prepare(
            <<<SQL
            SELECT nome_arquivo FROM foto WHERE id_anuncio = ?
            SQL
        );
        $stmt->execute([$idAnuncio]);
        $fotos = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Delete files from filesystem
        foreach ($fotos as $foto) {
            $filepath = __DIR__ . '/../uploads/' . $foto->nome_arquivo;
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }

        // Delete from database
        $stmt = $pdo->prepare(
            <<<SQL
            DELETE FROM foto WHERE id_anuncio = ?
            SQL
        );
        $stmt->execute([$idAnuncio]);
    }
} 