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
        if (empty($data['name'])) {
            throw new Exception("Team name is required.", 400);
        }
        if (strlen($data['name']) > 100) {
            throw new Exception("Team name cannot exceed 100 characters.", 400);
        }
        if (empty($data['age_category_id'])) {
            throw new Exception("Age category is required.", 400);
        }
        if (empty($data['gender_id'])) {
            throw new Exception("Gender is required.", 400);
        }
        if (!empty($data['founded_year']) && ($data['founded_year'] < 1800 || $data['founded_year'] > date('Y'))) {
            throw new Exception("Founded year must be between 1800 and current year.", 400);
        }
        $data['name'] = trim($data['name']);
        return $this->dao->insert($data);
    }

    public function updateTeam(int $id, array $data) {
        if (empty($data['name'])) {
            throw new Exception("Team name is required.", 400);
        }
        if (strlen($data['name']) > 100) {
            throw new Exception("Team name cannot exceed 100 characters.", 400);
        }
        if (empty($data['age_category_id'])) {
            throw new Exception("Age category is required.", 400);
        }
        if (empty($data['gender_id'])) {
            throw new Exception("Gender is required.", 400);
        }
        if (!empty($data['founded_year']) && ($data['founded_year'] < 1800 || $data['founded_year'] > date('Y'))) {
            throw new Exception("Founded year must be between 1800 and current year.", 400);
        }
        $data['name'] = trim($data['name']);
        return $this->dao->update($id, $data);
    }

    public function deleteTeam(int $id) {
        return $this->dao->delete($id);
    }
}
