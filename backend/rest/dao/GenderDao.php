<?php
require_once __DIR__ . '/BaseDao.php';

class GenderDao extends BaseDao {
  public function __construct() { parent::__construct("gender"); }
}
