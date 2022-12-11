<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getMainLogo($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $result = $this->db->query("SELECT a.id, a.name, a.path, a.isActive, a.isDeleted FROM assets AS a WHERE a.id = 1 AND a.isActive = 1 AND a.isDeleted = 0")->row();
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'GO6RM', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    $response = [
      'logo' => $result,
      'csrf_renewed' => $csrf_renewed
    ];

    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $response];
  }

  public function validateRegister($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    $email        = trim(isset($data['email']) ? $data['email'] : '');
    $username     = trim(isset($data['username']) ? $data['username'] : '');
    $_password    = trim(isset($data['_password']) ? $data['_password'] : '');
    $password     = encryptKey($_password);

    $user = $this->db->query("SELECT a.id, a.idRole, a.username, a.email, a.isActive, a.isDeleted FROM users AS a WHERE a.username = '$username' AND a.email = '$email'")->row();
    if (!empty($user)) return ['status' => false, 'message' => 'Username atau email sudah terdaftar.', 'data' => ['error' => 'VA4US', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    $send = [
      'idRole'     => 5,
      'username'   => $username,
      'email'      => $email,
      'password'   => $password,
      'last_on'    => getTimes('now'),
      'isDeleted'  => 0,
      'isActive'   => 0,
      'created_by' => 1
    ];
    
    $this->db->insert('users', $send);
    $user = $this->db->query("SELECT a.id, a.idRole, a.username, a.email, a.isActive, a.isDeleted FROM users AS a WHERE a.username = '$username' AND a.email = '$email'")->row();

    if (empty($user)) return ['status' => false, 'message' => 'Registrasi tidak berhasil, silahkan coba lagi.', 'data' => ['error' => '83MWE', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    insertAuditlog(['idUser' => $user->id, 'idRole' => $user->idRole, 'idType' => 1, 'description' => "$user->username, berhasil registrasi akun pada sistem."]);

    return ['status' => true, 'message' => 'Registrasi berhasil, harap tunggu proses masuk.', 'data' => ['user' => $user, 'csrf_renewed' => $csrf_renewed]];
  }
} 