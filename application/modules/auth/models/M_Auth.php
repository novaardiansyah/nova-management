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

  public function validateLogin($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    $username     = trim(isset($data['username']) ? $data['username'] : '');
    $isRemember   = trim(isset($data['isRemember']) ? $data['isRemember'] : '');
    $_password    = trim(isset($data['_password']) ? $data['_password'] : '');
    $password     = encryptKey($_password);

    $user = $this->db->query("SELECT a.id, a.idRole, a.username, a.email, a.isActive, a.isDeleted FROM users AS a WHERE a.username = '$username' AND a.password = '$password'")->row();
    if (empty($user)) return ['status' => false, 'message' => 'Username atau password tidak valid.', 'data' => ['error' => '64B7L', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    if ((int) $user->isDeleted == 1) return ['status' => false, 'message' => 'Akun sudah dihapus, silahkan hubungi customer support kami.', 'data' => ['error' => 'CHYS0', 'csrf_renewed' => $csrf_renewed, 'query' => '']];

    $this->db->update('users', ['last_on' => getTimes('now')], ['id' => $user->id]);
    
    $user = [
      'id'       => custom_encode($user->id),
      'idRole'   => custom_encode($user->idRole),
      'username' => $user->username,
      'email'    => $user->email
    ];

    return ['status' => true, 'message' => 'Login berhasil, harap tunggu proses masuk.', 'data' => ['user' => $user, 'csrf_renewed' => $csrf_renewed]];
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

    return ['status' => true, 'message' => 'Registrasi berhasil, harap tunggu proses masuk.', 'data' => ['user' => $user, 'csrf_renewed' => $csrf_renewed]];
  }
} 