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
    INNER JOIN finance_currency AS c ON a.idCurrency = c.id WHERE a.isDeleted = 0")->result();
    
    if (empty($result)) return responseModelFalse('Data tidak tersedia/ditemukan.', 'KFTVH');

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_encode($value->id);
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
      $result[$key]->id = custom_encode($value->id);
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
      $result[$key]->id = custom_encode($value->id);
    }

    return responseModelTrue('Data tersedia / ditemukan.', ['list' => $result]);
  }

  public function storeAccount($data = [])
  {
    // * Additional Data (Start)
    $idUser = trim(isset($data['idUser']) ? custom_decode($data['idUser']) : '');
    $idRole = trim(isset($data['idRole']) ? custom_decode($data['idRole']) : '');
    // * Additional Data (End)

    $name     = trim(isset($data['name']) ? $data['name'] : '');
    $amount   = trim(isset($data['amount']) ? $data['amount'] : '');
    $logo     = trim(isset($data['logo']) ? $data['logo'] : '');
    $isActive = trim(isset($data['isActive']) ? $data['isActive'] : '');

    $_idCurrency = trim(isset($data['idCurrency']) ? $data['idCurrency'] : '');
    $idCurrency  = custom_decode($_idCurrency);

    $_idType = trim(isset($data['idType']) ? $data['idType'] : '');
    $idType  = custom_decode($_idType);

    $idQueue = random_tokens(15);

    $send = [
      'idQueue'    => $idQueue,
      'idCurrency' => $idCurrency,
      'idType'     => $idType,
      'name'       => $name,
      'amount'     => $amount,
      'logo'       => $logo,
      'isActive'   => $isActive,
      'created_by' => $idUser
    ];
    $this->db->insert('finance_account', $send);
    
    $result = $this->db->query("SELECT a.id, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at FROM finance_account AS a WHERE a.idQueue = '$idQueue'")->row();

    if (empty($result)) return responseModelFalse('Data gagal ditambahkan, silahkan coba lagi.', 'MZ0JI', $data);

    $this->db->update('finance_account', ['idQueue' => NULL, 'updated_at' => getTimes('now'), 'updated_by' => $idUser], ['id' => $result->id]);
    
    $result->id         = custom_encode($result->id);
    $result->idCurrency = $_idCurrency;
    $result->idType     = $_idType;

    insertAuditlog(['idUser' => $idUser, 'idRole' => $idRole, 'idType' => 5, 'description' => "Berhasil menambah data akun keuangan baru dengan nama '$name'. -- $result->id"]);
    
    return responseModelTrue('Data berhasil ditambahkan.', ['list' => $result]);
  }

  public function editAccount($data = [])
  {
    // * Additional Data (Start)
    $responseType = trim(isset($data['responseType']) ? $data['responseType'] : '');
    // * Additional Data (End)

    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = custom_decode($_id);

    $result = $this->db->query("SELECT a.id, a.idCurrency, a.idType, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at, b.name AS name_finance_types, c.name AS name_finance_currency, c.short AS short_finance_currency, c.country, c.kdCountry 
    FROM finance_account AS a
      INNER JOIN finance_types AS b ON a.idType = b.id
      INNER JOIN finance_currency AS c ON a.idCurrency = c.id 
    WHERE a.id = '$id' AND a.isDeleted = 0")->row();

    if (empty($result)) return responseModelFalse('Data tidak ditemukan, silahkan coba lagi.', '7TDBS', $data, $responseType);

    $result->id         = $_id;
    $result->idCurrency = custom_encode($result->idCurrency);
    $result->idType     = custom_encode($result->idType);
    $result->f1_name    = $result->country . ' (' . $result->short_finance_currency . ')';

    return responseModelTrue('Data berhasil ditemukan.', $result, $responseType);
  }

  public function updateAccount($data = [])
  {
    // * Additional Data (Start)
    $idUser       = trim(isset($data['idUser']) ? custom_decode($data['idUser']) : '');
    $idRole       = trim(isset($data['idRole']) ? custom_decode($data['idRole']) : '');
    $responseType = trim(isset($data['responseType']) ? $data['responseType'] : '');
    // * Additional Data (End)

    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = custom_decode($_id);

    $name     = trim(isset($data['name']) ? $data['name'] : '');
    $amount   = trim(isset($data['amount']) ? $data['amount'] : '');
    $logo     = trim(isset($data['logo']) ? $data['logo'] : '');
    $isActive = trim(isset($data['isActive']) ? $data['isActive'] : '');

    $_idCurrency = trim(isset($data['idCurrency']) ? $data['idCurrency'] : '');
    $idCurrency  = custom_decode($_idCurrency);

    $_idType = trim(isset($data['idType']) ? $data['idType'] : '');
    $idType  = custom_decode($_idType);

    $result = $this->db->query("SELECT a.id, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at FROM finance_account AS a WHERE a.id = '$id'")->row();
    if (empty($result)) return responseModelFalse('Data tidak ditemukan, silahkan coba lagi.', '8KMOK', $data, $responseType);

    $send = [
      'idQueue'    => NULL,
      'idCurrency' => $idCurrency,
      'idType'     => $idType,
      'name'       => $name,
      'amount'     => $amount,
      'isActive'   => $isActive,
      'updated_by' => $idUser,
      'updated_at' => getTimes('now')
    ];

    if (!empty($logo)) $send['logo'] = $logo;

    $this->db->update('finance_account', $send, ['id' => $id]);

    $result->id      = $_id;
    $result->oldLogo = !empty($logo) ? $result->logo : 'deleted.png';  
    $result->logo    = !empty($logo) ? $logo : $result->logo;

    insertAuditlog(['idUser' => $idUser, 'idRole' => $idRole, 'idType' => 6, 'description' => "Berhasil mengubah data akun keuangan dengan nama '$name'. -- $_id"]);

    return responseModelTrue('Data akun keuangan berhasil diperbarui.', $result, $responseType);
  }

  public function deleteAccount($data = [])
  {
    // * Additional Data (Start)
    $idUser       = trim(isset($data['idUser']) ? custom_decode($data['idUser']) : '');
    $idRole       = trim(isset($data['idRole']) ? custom_decode($data['idRole']) : '');
    $responseType = trim(isset($data['responseType']) ? $data['responseType'] : '');
    // * Additional Data (End)

    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = custom_decode($_id);

    $result = $this->db->query("SELECT a.id, a.name, a.amount, a.logo, a.isActive, a.isDeleted, a.created_at, a.updated_at FROM finance_account AS a WHERE a.id = '$id'")->row();
    if (empty($result)) return responseModelFalse('Data tidak ditemukan, silahkan coba lagi.', '4IIT2', $data, $responseType);

    $this->db->update('finance_account', ['isDeleted' => 1, 'deleted_at' => getTimes('now'), 'deleted_by' => $idUser], ['id' => $id]);
    insertAuditlog(['idUser' => $idUser, 'idRole' => $idRole, 'idType' => 7, 'description' => "Berhasil menghapus data akun keuangan dengan nama '$result->name'. -- $_id"]);

    $result->id = $_id;
    return responseModelTrue('Berhasil menghapus data keuangan..', ['list' => $result], $responseType);
  }
}