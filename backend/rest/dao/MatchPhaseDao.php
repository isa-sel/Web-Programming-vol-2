<?php
require_once __DIR__ . '/BaseDao.php';

class MatchPhaseDao extends BaseDao {
  public function __construct() { parent::__construct("match_phase"); }
}
