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
    $result = requestApi(api_url('CustomerService/contactUsList'), 'POST');
    return $result;
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
