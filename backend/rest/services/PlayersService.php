<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PlayersDao.php';

class PlayersService extends BaseService
{
    public function __construct() {
        $dao = new PlayersDao();
        parent::__construct($dao);
    }

    public function getAll() {
        return $this->get_all();
    }

    public function getById(int $id) {
        return $this->get_by_id($id);
    }

    public function insertPlayer(array $data) {
        return $this->insert($data);
    }

    public function updatePlayer(int $id, array $data) {
        return $this->update($data, $id);
    }

    public function deletePlayer(int $id) {
        return $this->delete($id);
    }
}
