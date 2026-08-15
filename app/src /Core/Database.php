<?php

class Database{

    private static ?Database $instance = null;
    public PDO $pdo;
    private function __construct(){
        try {
            // Tentative de connexion PostgreSQL
            $this->pdo = new PDO(
                "pgsql:host=localhost;dbname=probase;port=5432",
                "postgres",
                "config295"
            );
            $this->pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $ex) {
            // Fallback automatique vers SQLite
            $sqlitePath = dirname(__DIR__, 2) . '/erp.db';
            $this->pdo = new PDO(
                "sqlite:" . $sqlitePath
            );
            $this->pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }
    }
     //Retourne l'unique instance de Database.
public static function getInstance(): Database{
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
public function query(string $sql, bool $single = true): array{
        $query = $this->pdo->query($sql);
        return $single
            ? ($query->fetch() ?: [])
            : $query->fetchAll();
    }
public function prepare(string $sql, array $datas): PDOStatement{
        $prepare = $this->pdo->prepare($sql);
        $prepare->execute($datas);
        return $prepare;
    }
public function executeQuery(string $sql, array $datas, bool $single = true): array {
        $statement = $this->prepare($sql, $datas);
        return $single
            ? ($statement->fetch() ?: [])
            : $statement->fetchAll();
    }
public function executeUpdate(string $sql, array $datas): int {
        $statement = $this->prepare($sql, $datas);
        return $statement->rowCount();
    }
public function getAllTable(string $table): array{
        $sql = "SELECT * FROM $table";
        return $this->query($sql, false);
    } 
     //Empêche le clonage de l'instance.
     
private function __clone(){
    }
     //Empêche la désérialisation.
public function __wakeup(){
        throw new Exception("Cannot unserialize singleton");
    }
}