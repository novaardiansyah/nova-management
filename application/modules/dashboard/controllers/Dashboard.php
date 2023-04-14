<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Dashboard', 'dashboard');
  }

  public function index()
  {
    is_login();
    
    $data = [
      'bc' => [
        ['Dashboard' => 'dashboard'],
        ['Dashboard' => '']
      ],
      'title' => 'Dashboard',
    ];
    
    backend('List', $data);
  }
}