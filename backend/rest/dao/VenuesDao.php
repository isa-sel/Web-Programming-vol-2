<?php
require_once __DIR__ . '/BaseDao.php';

class VenuesDao extends BaseDao {
  public function __construct() { parent::__construct("venues"); }

}
