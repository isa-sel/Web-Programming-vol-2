<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ParticipantsDao.php';

class ParticipantsService extends BaseService {
    public function __construct() {
        $dao = new ParticipantsDao();
        parent::__construct($dao);
    }

    public function listByPhase(int $league_phase_id): array {
        return $this->dao->listByPhase($league_phase_id);
    }

    public function insertParticipant(array $data) {
        return $this->dao->insert($data);
    }

    public function updateParticipant(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteParticipant(int $id) {
        return $this->dao->delete($id);
    }
}
