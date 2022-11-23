<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Auth', 'auth');
  }

  public function index()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $mainLogo     = $this->auth->getMainLogo(['csrf_renewed' => $csrf_renewed]);

    $data = [
      'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],

      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js')
      ]
    ];

    $this->_loadLayout('auth/login', $data);
  }

  public function register()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $mainLogo     = $this->auth->getMainLogo(['csrf_renewed' => $csrf_renewed]);

    $data = [
      'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],

      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js')
      ]
    ];

    $this->_loadLayout('auth/register', $data);
  }

  public function forgotPassword()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $mainLogo     = $this->auth->getMainLogo(['csrf_renewed' => $csrf_renewed]);

    $data = [
      'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],

      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js')
      ]
    ];

    $this->_loadLayout('auth/forgotPassword', $data);
  }

  private function _loadLayout($path, $data)
  {
    $this->load->view('auth/layout/header');
    $this->load->view($path, $data);
    $this->load->view('auth/layout/footer');
  }
}