<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PlayersDao.php';

class PlayersService extends BaseService
{
    public function __construct() {
        $dao = new PlayersDao();
        parent::__construct($dao);
    }
/*
    public function getAll() {
        return $this->getAll();
    }

    public function getById($id) {
        return $this->getById($id);
    }
*/
    public function insertPlayer($data) {
        return $this->insert($data);
    }

    public function updatePlayer($id, $data) {
        return $this->update($data, $id);
    }

    public function deletePlayer($id) {
        return $this->delete($id);
    }
}
