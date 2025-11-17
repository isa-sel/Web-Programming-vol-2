<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UsersDao.php';

class UsersService extends BaseService {
    public function __construct() {
        $dao = new UsersDao();
        parent::__construct($dao);
    }

    public function getByUsername(string $username): ?array {
        return $this->dao->getByUsername($username);
    }

    public function publicById(int $id): ?array {
        return $this->dao->publicById($id);
    }

    public function updatePassword(int $id, string $password_hash): bool {
        return $this->dao->updatePassword($id, $password_hash);
    }

    public function insertUser(array $data) {
        return $this->dao->insert($data);
    }

    public function updateUser(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteUser(int $id) {
        return $this->dao->delete($id);
    }
}
