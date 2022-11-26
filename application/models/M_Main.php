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
    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.isActive = 1 AND a.isDeleted = 0")->result();

    if (empty($result)) return arrayToObject(['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'I2W3']]);

    $menu = [];

    foreach ($result as $key => $value) 
    {
      $result[$key]->isSingle = 1;

      $submenu = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.isActive FROM submenu AS a WHERE a.isActive = 1 AND a.idMenu = '$value->id'")->result();

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

    $response = arrayToObject(['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => []]);
    $response->data = $menu;
    
    return $response;
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
    if (format_date($result->expired_at) < getTimes('now')) return ['status' => false, 'message' => 'Sesi anda telah habis, silahkan login kembali.', 'data' => ['error' => 'F0ZAX', 'expired_at'   => format_date($result->expired_at), 'csrf_renewed' => $csrf_renewed]];

    $data = [
      'idUser'       => $_idUser,
      'idType'       => $_idType,
      'token'        => $token,
      'csrf_renewed' => $csrf_renewed
    ];

    return ['status' => true, 'message' => 'Validasi berhasil, sesi anda aman.', 'data' => $data];
  }
}