<?php
require_once __DIR__ . '/../config.php';

class BaseDao {
  protected $table;
  protected $connection;

  public function __construct($table)
    {
        $this->table = $table;
        try {
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                Config::DB_USER(),
                Config::DB_PASSWORD(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw $e;
        }
    }

  protected function query($query, $params)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }

  public function getAll(): array {
    $stmt = $this->connection->prepare("SELECT * FROM {$this->table}");
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getById(int $id): ?array {
    $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public function insert(array $data): int {
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($data);
    return (int)$this->connection->lastInsertId();
  }

  public function update(int $id, array $data): bool {
    $fields = "";
    foreach ($data as $key => $value) {
      $fields .= "$key = :$key, ";
    }
    $fields = rtrim($fields, ", ");
    $sql = "UPDATE {$this->table} SET $fields WHERE id = :id";
    $stmt = $this->connection->prepare($sql);
    $data['id'] = $id;
    return $stmt->execute($data);
  }

  public function delete(int $id): bool {
    $stmt = $this->connection->prepare("DELETE FROM {$this->table} WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }
}
