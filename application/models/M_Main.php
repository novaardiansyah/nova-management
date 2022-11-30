<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Main extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getMenu()
  {
    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.sortOrder, a.isActive FROM menu AS a WHERE a.isActive = 1 AND a.isDeleted = 0 ORDER BY a.sortOrder ASC")->result();

    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'SHW8Z']];

    $menu = [];

    foreach ($result as $key => $value) 
    {
      $result[$key]->isSingle = 1;

      $submenu = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.sortOrder, a.isActive FROM submenu AS a WHERE a.isActive = 1 AND a.idMenu = '$value->id' ORDER BY a.sortOrder ASC")->result();

      $temp = [
        'menu' => $result[$key]
      ];

      if (!empty($submenu)) 
      {
        $result[$key]->isSingle = 0;
        $temp['submenu'] = $submenu;
      }

      array_push($menu, $temp);
    }

    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $menu];
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

  public function validateTokenLogin($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $token   = trim(isset($data['token']) ? $data['token'] : '');
    $_idUser = trim(isset($data['idUser']) ? $data['idUser'] : '');
    $idUser  = $_idUser ? custom_decode($_idUser) : '';
    $_idType = trim(isset($data['idType']) ? $data['idType'] : '');
    $idType  = $_idType ? custom_decode($_idType) : '';

    $result = $this->db->query("SELECT a.id, a.idUser, a.idType, a.token, a.isActive, a.expired_at FROM tokens AS a WHERE a.idUser = '$idUser' AND a.idType = '$idType' AND a.isActive = 1")->row();

    if (empty($result)) return ['status' => false, 'message' => 'Sesi anda tidak valid, silahkan login kembali.', 'data' => ['error' => '3FBMO', 'csrf_renewed' => $csrf_renewed]];

    // * Token Invalid
    if ($token !== $result->token) return ['status' => false, 'message' => 'Sesi anda telah habis, silahkan login kembali.', 'data' => ['error' => 'TK4H4', 'csrf_renewed' => $csrf_renewed]];

    // * Token Expired
    if (format_date($result->expired_at) < getTimes('now')) return ['status' => false, 'message' => 'Sesi anda telah habis, silahkan login kembali.', 'data' => ['error' => 'F0ZAX', 'expired_at' => format_date($result->expired_at), 'csrf_renewed' => $csrf_renewed]];

    $user = $this->db->query("SELECT a.id, a.idRole, a.username, a.email, a.isActive, a.isDeleted, a.activate_at, b.name AS nameRole
      FROM users AS a
    INNER JOIN role AS b ON a.idRole = b.id WHERE a.id = '$idUser'")->row();

    if (empty($user)) return ['status' => false, 'message' => 'Data pengguna tidak ditemukan, silahkan coba lagi.', 'data' => ['error' => '64B7L', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    if ((int) $user->isDeleted == 1) return ['status' => false, 'message' => 'Data pengguna sudah dihapus, silahkan hubungi customer support kami.', 'data' => ['error' => 'CHYS0', 'csrf_renewed' => $csrf_renewed]];

    $rp_user = [
      'id'          => custom_encode($user->id),
      'idRole'      => custom_encode($user->idRole),
      'nameRole'    => $user->nameRole,
      'username'    => $user->username,
      'email'       => $user->email,
      'isActive'    => $user->isActive,
      'activate_at' => $user->activate_at
    ];

    $data = [
      'idUser'       => $_idUser,
      'idType'       => $_idType,
      'token'        => $result,
      'user'         => $rp_user,
      'csrf_renewed' => $csrf_renewed
    ];

    return ['status' => true, 'message' => 'Validasi berhasil, sesi anda aman.', 'data' => $data];
  }
}