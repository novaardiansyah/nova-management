<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Core extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function store($data = [])
  {
    return responseModelTrue('Berhasil menyimpan data.', $data);
  }
}
