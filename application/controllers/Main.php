<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Dashboard',
      'subtitle' => 'Dashboard',
      'breadcrumb' => [
        ['title' => 'Dashboard', 'link' => base_url()]
      ],
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js'),
      ]
    ];

    backend_layout('index', $data);
  }
}