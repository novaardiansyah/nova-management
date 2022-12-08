<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ContactUs extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function contactUsList()
  {
    $result = $this->db->query("SELECT a.id, a.idUser, a.name, a.email, a.phone, a.message, a.isUser, a.isActive FROM contact_us AS a")->result();

    if (empty($result)) return responseModelFalse('Maaf, belum ada data yang tersedia.', 'KFTVH');

    foreach ($result as $key => $value) {
      $result[$key]->id = custom_encode($value->id);
    }

    return responseModelTrue('Data berhasil ditemukan.', ['list' => $result]);
  }

  public function storeContactUs()
  {
    return responseModelFalse('Terjadi Kesalahan', 'KFTVH', $_POST);
  }

  public function updateContactUs()
  {
    return responseModelFalse('Terjadi Kesalahan', 'KFTVH', $_POST);
  }

  public function deletedContactUs()
  {
    return responseModelFalse('Terjadi Kesalahan', 'KFTVH', $_POST);
  }
}
