<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MatchesDao.php';

class MatchesService extends BaseService {
    public function __construct() {
        $dao = new MatchesDao();
        parent::__construct($dao);
    }

    public function listByLeague(int $league_id): array {
        return $this->dao->listByLeague($league_id);
    }

    public function insertMatch(array $data) {
        return $this->dao->insert($data);
    }

    public function updateMatch(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteMatch(int $id) {
        return $this->dao->delete($id);
    }
}
