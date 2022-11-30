<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Account extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function accountList()
  {
    $result = $this->db->query("SELECT a.id, a.idCurrency, a.idType, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at, b.name AS name_finance_types 
      FROM finance_account AS a
    INNER JOIN finance_types AS b ON a.idType = b.id")->result();
    
    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'KFTVH');
    
    return responseModelTrue('Data tersedia/ditemukan.', ['list' => $result]);
  }
}