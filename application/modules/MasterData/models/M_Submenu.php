<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Submenu extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function dropdownMenu($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.isDeleted = 0 ORDER BY a.name ASC")->result();
    
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => '9D61R']];

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_encode($value->id);
    }

    $response = [
      'menu'         => $result,
      'csrf_renewed' => $csrf_renewed
    ];
    
    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $response];
  }

  public function getSubmenu($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $result = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.sortOrder, a.isActive, b.name AS nameMenu FROM submenu AS a 
      INNER JOIN menu AS b ON a.idMenu = b.id
    WHERE a.isDeleted = 0 AND b.isDeleted = 0 ORDER BY a.created_at DESC")->result();
    
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'UWR34']];

    foreach ($result as $key => $value) 
    {
      $result[$key]->id     = custom_encode($value->id);
      $result[$key]->idMenu = custom_encode($value->idMenu);
    }

    $response = [
      'submenu'      => $result,
      'csrf_renewed' => $csrf_renewed
    ];
    
    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $response];
  }

  public function addData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $_idMenu = trim(isset($data['_idMenu']) ? $data['_idMenu'] : '');
    $idMenu  = $_idMenu ? custom_decode($_idMenu) : 0;

    $menu = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.id = '$idMenu' AND a.isDeleted = 0")->row();
    if (empty($menu)) return ['status' => false, 'message' => 'Data menu tidak ditemukan.', 'data' => ['error' => 'NNB7U']];

    $send = [
      'name'       => trim(isset($data['name']) ? $data['name'] : ''),
      'idMenu'     => $idMenu,
      'link'       => trim(isset($data['link']) ? $data['link'] : ''),
      'sortOrder'  => trim(isset($data['sortOrder']) ? $data['sortOrder'] : ''),
      'isActive'   => trim(isset($data['isActive']) ? $data['isActive'] : 0),
      'created_by' => 1
    ];

    $this->db->insert('submenu', $send);
    $send['csrf_renewed'] = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil menambah data baru.', 'data' => $send];
  }

  public function deleteData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = $_id ? custom_decode($_id) : '';

    $result = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.isActive FROM submenu AS a WHERE a.isDeleted = 0 AND a.id = '$id'")->row();
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'UWR34']];

    $result = custom_encode($result->id);

    $send = [
      'isDeleted'  => 1,
      'isActive'   => 0,
      'deleted_by' => 1,
      'deleted_at' => getTimes('now')
    ];

    $this->db->update('submenu', $send, ['id' => $id]);

    $send['id'] = $_id;
    $send['csrf_renewed'] = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil menghapus data.', 'data' => $send];
  }

  public function editData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = $_id ? custom_decode($_id) : '';

    $result = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.sortOrder, a.isActive, b.name AS nameMenu FROM submenu AS a 
      INNER JOIN menu AS b ON a.idMenu = b.id
    WHERE a.isDeleted = 0 AND b.isDeleted = 0 AND a.id = '$id'")->row();
    
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => '7YP7W']];

    $result->id           = custom_encode($result->id);
    $result->idMenu       = custom_encode($result->idMenu);
    $result->csrf_renewed = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil menemukan data.', 'data' => $result];
  }

  public function updateData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    
    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = $_id ? custom_decode($_id) : '';

    $_idMenu = trim(isset($data['_idMenu']) ? $data['_idMenu'] : '');
    $idMenu  = $_idMenu ? custom_decode($_idMenu) : '';

    $result = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.sortOrder, a.isActive FROM submenu AS a WHERE a.isDeleted = 0 AND a.id = '$id'")->row();
    if (empty($result)) return ['status' => false, 'message' => 'Data submenu tidak ditemukan.', 'data' => ['error' => 'PAA8T', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    $send = [
      'idMenu'     => $idMenu,
      'name'       => trim(isset($data['name']) ? $data['name'] : ''),
      'link'       => trim(isset($data['link']) ? $data['link'] : ''),
      'sortOrder'  => trim(isset($data['sortOrder']) ? $data['sortOrder'] : ''),
      'isActive'   => trim(isset($data['isActive']) ? $data['isActive'] : 0),
      'updated_by' => 1,
      'updated_at' => getTimes('now')
    ];

    $this->db->update('submenu', $send, ['id' => $id]);

    $send['id']           = custom_encode($id);
    $send['idMenu']       = $_idMenu;
    $send['csrf_renewed'] = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil memperbarui data.', 'data' => $send];
  }
}