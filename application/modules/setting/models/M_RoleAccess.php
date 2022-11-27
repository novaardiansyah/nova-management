<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_RoleAccess extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function listRoleAccount($data = [])
  {
    $data = $data ? arrayToObject($data) : [];

    $result = $this->db->query("SELECT a.id, a.name, a.isActive FROM role AS a WHERE a.isActive = 1 AND a.isDeleted = 0 ORDER BY a.name ASC")->result();

    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'GJHDW');

    return responseModelTrue('Data berhasil ditemukan.', ['list' => $result]);
  }
}