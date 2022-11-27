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
    $this->load->helper('auth_helper');
    isAlreadyLogin();

    $csrf_renewed = $this->security->get_csrf_hash();
    $mainLogo     = $this->auth->getMainLogo(['csrf_renewed' => $csrf_renewed]);

    $data = [
      'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],

      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js'),
        base_url('assets/js/auth/login.js')
      ]
    ];

    $this->_loadLayout('auth/login', $data);
  }

  public function register()
  {
    $this->load->helper('auth_helper');
    isAlreadyLogin();

    $csrf_renewed = $this->security->get_csrf_hash();
    $mainLogo     = $this->auth->getMainLogo(['csrf_renewed' => $csrf_renewed]);

    $data = [
      'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],

      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js'),
        base_url('assets/js/auth/register.js')
      ]
    ];

    $this->_loadLayout('auth/register', $data);
  }

  public function forgotPassword()
  {
    $this->load->helper('auth_helper');
    isAlreadyLogin();
    
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

  public function validateLogin()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_validateLogin();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $send = [
      'csrf_renewed' => $csrf_renewed,
      'username'     => trim(isset($_POST['username']) ? $_POST['username'] : ''),
      '_password'    => trim(isset($_POST['password']) ? $_POST['password'] : '')
    ];

    $result = $this->auth->validateLogin($send);
    $result = arrayToObject($result);

    if ($result->status == true)
    {
      destroySession(['user', 'isLogin']);
      setSession(['user' => $result->data->user, 'isLogin' => true]);
      setCustomCookie($result->data->token->name, $result->data->token->value, $result->data->token->expired_at);
    }

    echo json_encode($result);
  }

  private function _r_validateLogin()
  {
    $rules = [
      ['field' => 'username', 'label' => 'Username', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'password', 'label' => 'Password', 'rules' => 'required|trim|min_length[6]|max_length[120]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function validateRegister()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_validateRegister();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $send = [
      'csrf_renewed'    => $csrf_renewed,
      'email'           => trim(isset($_POST['email']) ? $_POST['email'] : ''),
      'username'        => trim(isset($_POST['username']) ? $_POST['username'] : ''),
      '_password'       => trim(isset($_POST['password']) ? $_POST['password'] : ''),
      'confirmPassword' => trim(isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : ''),
    ];

    $result = $this->auth->validateRegister($send);
    $result = arrayToObject($result);

    if ($result->status == true)
    {
      destroySession(['user', 'isLogin']);
      setSession(['user' => $result->data->user, 'isLogin' => true]);
    }

    echo json_encode($result);
  }

  private function _r_validateRegister()
  {
    $rules = [
      ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'username', 'label' => 'Username', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'password', 'label' => 'Password', 'rules' => 'required|trim|min_length[6]|max_length[120]'],
      ['field' => 'confirmPassword', 'label' => 'Confirm Password', 'rules' => 'required|trim|min_length[6]|max_length[120]'],
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function logout()
  {
    $this->load->helper('auth_helper');
    return invalidLogin('auth');
  }

  private function _loadLayout($path, $data)
  {
    $this->load->view('auth/layout/header');
    $this->load->view($path, $data);
    $this->load->view('auth/layout/footer');
  }
}