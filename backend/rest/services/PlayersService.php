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
        if (empty($data['name'])) {
            throw new Exception("Player name is required.", 400);
        }
        if (strlen($data['name']) > 100) {
            throw new Exception("Player name cannot exceed 100 characters.", 400);
        }
        if (empty($data['birthday'])) {
            throw new Exception("Player birthday is required.", 400);
        }
        if (strtotime($data['birthday']) > time()) {
            throw new Exception("Birthday cannot be in the future.", 400);
        }
        if (empty($data['gender_id'])) {
            throw new Exception("Player gender is required.", 400);
        }
        if (!empty($data['hand']) && !in_array($data['hand'], ['left', 'right', 'both'])) {
            throw new Exception("Hand must be 'left', 'right', or 'both'.", 400);
        }
        $data['name'] = trim($data['name']);
        return $this->insert($data);
    }

    public function updatePlayer($id, $data) {
        return $this->update($data, $id);
    }

    public function deletePlayer($id) {
        return $this->delete($id);
    }
}
