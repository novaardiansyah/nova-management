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
    $result = $this->db->query("SELECT a.id, a.idCurrency, a.idType, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at, b.name AS name_finance_types, c.name AS name_finance_currency, c.short AS short_finance_currency, c.country, c.kdCountry FROM finance_account AS a
    INNER JOIN finance_types AS b ON a.idType = b.id
    INNER JOIN finance_currency AS c ON a.idCurrency = c.id")->result();
    
    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'KFTVH');

    foreach ($result as $key => $value) 
    {
      $result[$key]->f1_amount = $value->short_finance_currency . '. ' . number_format($value->amount, 2, ',', '.');
    }
    
    return responseModelTrue('Data tersedia/ditemukan.', ['list' => $result]);
  }
}