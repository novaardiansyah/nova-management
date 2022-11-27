<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auditlog extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function auditlogList($data = [])
  {
    $result = $this->db->query("SELECT a.id, a.idUser, a.idRole, a.idType, a.ipAddress, a.description, a.isActive, a.created_at, b.username, b.email, c.name AS name_role, d.name AS name_auditlogs_types FROM auditlogs AS a 
      INNER JOIN users AS b ON a.idUser = b.id
      INNER JOIN role AS c ON a.idRole = c.id
      INNER JOIN auditlogs_types AS d ON a.idType = d.id
    WHERE a.isActive = 1 AND a.idUser IS NOT NULL ORDER BY a.id DESC")->result(); 

    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'GJHDW');

    foreach ($result as $key => $value) {
      $result[$key]->f1_created_at = format_date($value->created_at, 'F d, Y H:i');
    }

    return responseModelTrue('Data berhasil ditemukan.', ['list' => $result]);
  }
}