<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/VenuesDao.php';

class VenuesService extends BaseService
{
    public function __construct() {
        $dao = new VenuesDao();
        parent::__construct($dao);
    }

    /*public function getAll() {
        return $this->getAll();
    }

    public function getById($id) {
        return $this->getById($id);
    }
*/
    public function insertVenue($data) {
        if (empty($data['name'])) {
            throw new Exception("Venue name is required.", 400);
        }
        if (strlen($data['name']) > 100) {
            throw new Exception("Venue name cannot exceed 100 characters.", 400);
        }
        $data['name'] = trim($data['name']);
        return $this->insert($data);
    }

    public function updateVenue(int $id, array $data) {
        if (empty($data['name'])) {
            throw new Exception("Venue name is required.", 400);
        }
        if (strlen($data['name']) > 100) {
            throw new Exception("Venue name cannot exceed 100 characters.", 400);
        }
        $data['name'] = trim($data['name']);
        return $this->update($data, $id);
    }

    public function deleteVenue(int $id) {
        return $this->delete($id);
    }
}
