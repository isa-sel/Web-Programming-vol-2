<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TeamPlayerDao.php';

class TeamPlayerService extends BaseService
{
    public function __construct() {
        $dao = new TeamPlayerDao();
        parent::__construct($dao);
    }

    public function currentRoster(int $team_id, string $on_date): array {
        return $this->dao->currentRoster($team_id, $on_date);
    }

    public function insertTeamPlayer(array $data) {
        return $this->dao->insert($data);
    }

    public function updateTeamPlayer(int $id, array $data) {
        return $this->dao->update($id, $data);
    }

    public function deleteTeamPlayer(int $id) {
        return $this->dao->delete($id);
    }
}
