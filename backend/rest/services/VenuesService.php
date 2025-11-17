<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/VenuesDao.php';

class VenuesService extends BaseService
{
    public function __construct() {
        $dao = new VenuesDao();
        parent::__construct($dao);
    }

    public function getAll() {
        return $this->get_all();
    }

    public function getById(int $id) {
        return $this->get_by_id($id);
    }

    public function insertVenue(array $data) {
        return $this->insert($data);
    }

    public function updateVenue(int $id, array $data) {
        return $this->update($data, $id);
    }

    public function deleteVenue(int $id) {
        return $this->delete($id);
    }
}
