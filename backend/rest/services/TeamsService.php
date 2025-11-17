<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TeamsDao.php';

class TeamsService extends BaseService {
    public function __construct() {
        $dao = new TeamsDao();
        parent::__construct($dao);
    }

    public function listWithLookups(): array {
        return $this->dao->listWithLookups();
    }

    public function insertTeam(array $data) {
        return $this->dao->insert($data);
    }

    public function updateTeam(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteTeam(int $id) {
        return $this->dao->delete($id);
    }
}
