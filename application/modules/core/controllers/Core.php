<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Core extends MX_Controller
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'M_Core';
  }

  public function store()
  {
    $table = trim(isset($_POST['table']) ? $_POST['table'] : '');
    $data  = isset($_POST['data']) ? $_POST['data'] : '';

    $send = array_merge(['table' => $table], $data);

    $result = requestModel($this->_modelPath, 'store', $send);
    echo json_encode($result);
  }
}
