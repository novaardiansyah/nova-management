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
    $result = $this->db->query("SELECT a.id, a.idCurrency, a.idType, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at FROM finance_account AS a")->result();
    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'KFTVH');
    return responseModelTrue('Data tersedia/ditemukan.', ['list' => $result]);
  }
}