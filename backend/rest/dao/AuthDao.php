<?php
require_once __DIR__ . '/UsersDao.php';


class AuthDao extends UsersDao{
   protected $table_name;


   public function __construct() {
       $this->table_name = "users";
       parent::__construct($this->table_name);
   }


   public function get_user_by_email($email) {
       $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
       return $this->query_unique($query, ['email' => $email]);
   }
}
