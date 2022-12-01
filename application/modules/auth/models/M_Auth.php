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
    $_password    = trim(isset($data['_password']) ? $data['_password'] : '');
    $password     = encryptKey($_password);

    $user = $this->db->query("SELECT a.id, a.idRole, a.username, a.email, a.isActive, a.isDeleted, a.activate_at, b.name AS nameRole
      FROM users AS a
    INNER JOIN role AS b ON a.idRole = b.id WHERE a.username = '$username' AND a.password = '$password'")->row();

    if (empty($user)) return ['status' => false, 'message' => 'Username atau password tidak valid.', 'data' => ['error' => '64B7L', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    if ((int) $user->isDeleted == 1) return ['status' => false, 'message' => 'Akun sudah dihapus, silahkan hubungi customer support kami.', 'data' => ['error' => 'CHYS0', 'csrf_renewed' => $csrf_renewed, 'query' => '']];

    $token = $this->db->query("SELECT a.id, a.idUser, a.idType, a.token, a.isActive, a.expired_at FROM tokens AS a WHERE a.idUser = '$user->id' AND a.idType = 2 AND a.isActive = 1")->row();

    if (!empty($token)) {
      $this->db->delete('tokens', ['id' => $token->id]);
    }

    $s_tokens = [
      'idUser'     => $user->id,
      'idType'     => 2,
      'token'      => random_tokens(63, null, true),
      'isActive'   => 1,
      'created_by' => 1,
      'created_at' => getTimes('now'),
      'expired_at' => getTimes('+7 day', 'Y-m-d') . ' 23:59:59'
    ];

    $this->db->insert('tokens', $s_tokens);

    $token = $this->db->query("SELECT a.id, a.idUser, a.idType, a.token, a.isActive, a.expired_at FROM tokens AS a WHERE a.idUser = '$user->id' AND a.idType = 2 AND a.isActive = 1")->row();

    $this->db->update('users', ['last_on' => getTimes('now')], ['id' => $user->id]);

    $rp_user = [
      'id'          => custom_encode($user->id),
      'idRole'      => custom_encode($user->idRole),
      'nameRole'    => $user->nameRole,
      'username'    => $user->username,
      'email'       => $user->email,
      'isActive'    => $user->isActive,
      'activate_at' => $user->activate_at
    ];

    $rp_token = [
      'name'       => 'token-login',
      'value'      => $token->token . ';' . custom_encode($user->id) . ';' . custom_encode($token->idType),
      'expired_at' => $token->expired_at
    ];

    $data = [
      'user'         => $rp_user, 
      'token'        => $rp_token,
      'csrf_renewed' => $csrf_renewed
    ];

    insertAuditlog(['idUser' => $user->id, 'idRole' => $user->idRole, 'idType' => 1, 'description' => "$user->username, berhasil login kedalam sistem."]);
    return ['status' => true, 'message' => 'Login berhasil, harap tunggu proses masuk.', 'data' => $data];
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