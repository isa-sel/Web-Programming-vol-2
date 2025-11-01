<?php
require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao {
  public function __construct() { parent::__construct("users"); }


  public function getByUsername(string $username): ?array {
    $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u' => $username]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public function publicById(int $id): ?array {
    $stmt = $this->connection->prepare(
      "SELECT id, username, email, role, created_at FROM users WHERE id=:id"
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public function updatePassword(int $id, string $password_hash): bool {
    $stmt = $this->connection->prepare("UPDATE users SET password_hash=:h WHERE id=:id");
    return $stmt->execute([':h' => $password_hash, ':id' => $id]);
  }

  public function insertUser(array $data): int {
    return $this->insert($data);
  }
  public function updateUser(int $id, array $data): bool {
    return $this->update($id, $data);
  }
  public function deleteUser(int $id): bool {
    return $this->delete($id);
  }


}
