<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Profile', 'profile');
  }

    public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Profile',
      'subtitle' => 'My Profile',
      'breadcrumb' => [
        ['title' => 'Account', 'link' => base_url('account')],
        ['title' => 'Profile', 'link' => base_url('account/profile')]
      ],
      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js'),
      ]
    ];

    backend_layout('profile/index', $data);
  }
}