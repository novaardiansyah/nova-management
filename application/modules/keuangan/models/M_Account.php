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

  public function getCurrency()
  {
    $result = $this->db->query("SELECT a.id, a.name, a.short, a.country, a.kdCountry, a.isActive FROM finance_currency AS a WHERE a.isDeleted = 0 ORDER BY a.country ASC")->result();

    if (empty($result)) return responseModelFalse('Data tidak tersedia.', 'Q9WDI');

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_decode($value->id);
      $result[$key]->f1_name = $value->country . ' (' . $value->short . ')';
    }

    return responseModelTrue('Data tersedia / ditemukan.', ['list' => $result]);
  }

  public function getTypeAccount()
  {
    $result = $this->db->query("SELECT a.id, a.name, a.isActive FROM finance_types AS a WHERE a.isDeleted = 0 ORDER BY a.name ASC")->result();

    if (empty($result)) return responseModelFalse('Data tidak tersedia.', 'MZ0JI');

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_decode($value->id);
    }

    return responseModelTrue('Data tersedia / ditemukan.', ['list' => $result]);
  }

  public function storeAccount()
  {
    if (empty($result)) return responseModelFalse('Data tidak tersedia.', 'MZ0JI', $_POST);
  }
}