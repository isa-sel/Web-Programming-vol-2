<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/GenderDao.php';

class GenderService extends BaseService {
    public function __construct() {
        $dao = new GenderDao();
        parent::__construct($dao);
    }
}
?>
