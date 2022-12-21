<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    // $this->load->model('M_Auth', 'auth');
  }

  public function index()
  {
    $data = [
      'style' => [
        base_url('assets/css/main.css' . versionAssets())
      ],
      'script' => [
        base_url('assets/js/main.js' . versionAssets()),
        base_url('assets/js/auth/login.js' . versionAssets())
      ]
    ];

    $this->_loadLayout('auth/login', $data);
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
      'username' => trim(isset($_POST['username']) ? $_POST['username'] : ''),
      'password' => trim(isset($_POST['password']) ? $_POST['password'] : '')
    ];

    $result = requestApi('auth/login', 'POST', $send);
    
    if ($result->status == true)
    {
      destroySession(['user', 'isLogin']);
      setSession(['user' => $result->data->user, 'isLogin' => true]);
      setCustomCookie($result->data->token->name, $result->data->token->value, $result->data->token->expiredAt);
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

  public function register()
  {
    $this->load->helper('auth_helper');
    // isAlreadyLogin();

    $data = [
      'style' => [
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/js/main.js' . versionAssets()),
        base_url('assets/js/auth/register.js' . versionAssets())
      ]
    ];

    $this->_loadLayout('auth/register', $data);
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
      'email'    => trim(isset($_POST['email']) ? $_POST['email'] : ''),
      'username' => trim(isset($_POST['username']) ? $_POST['username'] : ''),
      'password' => trim(isset($_POST['password']) ? $_POST['password'] : '')
    ];

    $result = requestApi('auth/register', 'POST', $send);
    
    if ($result->status == true)
    {
      destroySession(['user', 'isLogin']);
      setSession(['user' => $result->data->user, 'isLogin' => true]);
      setCustomCookie($result->data->token->name, $result->data->token->value, $result->data->token->expiredAt);
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

  public function logout()
  {
    $this->load->helper('auth_helper');
    return invalidLogin('auth');
  }

  private function _loadLayout($path, $data)
  {
    $this->load->view('auth/layout/header', $data);
    $this->load->view($path);
    $this->load->view('auth/layout/footer');
  }
}