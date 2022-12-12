<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ErrorPage extends MX_Controller
{
  public function __construct()
  {
    parent::__construct();
    // $this->load->model('M_Error', 'error');
  }

  public function forbidden()
  {
    $this->output->set_status_header('403');
    $this->load->view('ErrorPage/error_403');
  }
}
