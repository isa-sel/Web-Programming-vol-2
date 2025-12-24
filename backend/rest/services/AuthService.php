<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }


   public function register($entity) {  
       if (empty($entity['username']) || empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Username, email and password are required.'];
       }

       $username_exists = $this->auth_dao->getByUsername($entity['username']);
       if($username_exists){
           return ['success' => false, 'error' => 'Username already registered.'];
       }

       $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
       if($email_exists){
           return ['success' => false, 'error' => 'Email already registered.'];
       }

       $data = [
        'username'      => $entity['username'],
        'email'         => $entity['email'],
        'password_hash' => password_hash($entity['password'], PASSWORD_BCRYPT),
        'role'          => $entity['role'] ?? 'user'
    ];

       $entity = parent::insert($data);
       //unset($entity['password_hash']);


       return ['success' => true, 'data' => $entity];             
   }


   public function login($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }


       $user = $this->auth_dao->get_user_by_email($entity['email']);
       if(!$user){
           return ['success' => false, 'error' => 'Invalid username or password.'];
       }


       if(!$user || !password_verify($entity['password'], $user['password_hash']))
           return ['success' => false, 'error' => 'Invalid username or password.'];


       unset($user['password_hash']);
      
       $jwt_payload = [
           'user' => $user,
           'iat' => time(),
           'exp' => time() + (60 * 60 * 24)
       ];


       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );


       return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
   }
}
