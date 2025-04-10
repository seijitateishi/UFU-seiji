<html>

<body>
    <p>
        <?php
        function mysqlConnect()
        {
            $host = "sql201.infinityfree.com"; // veja dados de conexão no slide 6
            $username = "if0_38295654"; // veja dados de conexão no slide 6
            $password = "ttUM8HXMRWa"; // veja dados de conexão no slide 6
            $dbname = "if0_38295654_ppi";
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false, // desativa a execução emulada de prepared statements
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // altera o modo de busca padrão para FETCH_ASSOC
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // para que exceções sejam lançadas (padrão no PHP > 8.0)
            ];
            try {
                // Efetua a conexão com o MySQL. O objeto $pdo será utilizado posteriormente nas operações.
                $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $username, $password, $options);
                $sql =
                    <<<SQL
    SELECT nome, telefone as tel
    FROM aluno
    SQL;
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch()) {
                    echo $row['nome'];
                    echo $row['tel'];
                }

                return $pdo;
            } catch (Exception $e) {
                exit('Falha na conexão com o MySQL: ' . $e->getMessage());
            }
        }
        mysqlConnect();
        ?>
    </p>
</body>

</html>