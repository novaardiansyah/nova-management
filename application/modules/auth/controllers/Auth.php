<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model('M_Auth');
  }

  public function index()
  {
    already_login();
    $data = [];
    $this->load->view('auth/Login', $data);
  }

  public function generate_captcha()
  {
    $captcha = generate_captcha();
    $captcha['captcha_word'] = encrypt($captcha['captcha_word']);
    $captcha['captcha_time'] = encrypt($captcha['captcha_time']);

    $data = [
      'captcha' => $captcha,
      'csrf'    => $this->security->get_csrf_hash(),
    ];

    echo json_encode($data); exit;
  }

  public function attempt_login()
  {
    $captcha      = request($_POST, 'captcha', []);
    $captcha_word = request($captcha, 'captcha_word', encrypt(random_token('alnum', 7, 'upper')));
    $captcha_time = request($captcha, 'captcha_time', encrypt(time() - 3600));

    $username_or_email = request($_POST, 'username_or_email', '');
    $password          = request($_POST, 'password', '');
    $user_captcha      = request($_POST, 'user_captcha', '');
    $remember          = request($_POST, 'remember', 'off');

    if ($user_captcha !== decrypt($captcha_word)) {
      $res = [
        'status' => false,
        'errors' => ['user_captcha' => 'Captcha is not valid.'],
        'csrf'   => $this->security->get_csrf_hash(),
      ];
      echo json_encode($res); exit;
    }

    if (time() - decrypt($captcha_time) > 3600) {
      $res = [
        'status' => false,
        'errors' => ['user_captcha' => 'Captcha is expired.'],
        'csrf'   => $this->security->get_csrf_hash(),
      ];
      echo json_encode($res); exit;
    }

    $this->form_validation->set_rules('username_or_email', 'Username or Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if ($this->form_validation->run() === false) {
      $res = [
        'status' => false,
        'errors' => $this->form_validation->error_array(),
        'csrf'   => $this->security->get_csrf_hash(),
      ];
      echo json_encode($res); exit;
    }
    
    $req = $this->M_Auth->attempt_login([
      'username_or_email' => $username_or_email,
      'password'          => $password,
      'remember'          => $remember
    ]);
    
    if ($req->status) {
      $user = $req->data;

      if (isset($user->token->token) && $user->token->token) {
        $token = $user->token->token;
        $exp   = strtotime($user->token->expired_at);

        set_cookie('token_login', $token, $exp);
      }
      unset($user->token);
      
      $user->api_key = $user->api_key->token;
      set_session(['user' => $req->data]);

      $req->redirect = base_url('dashboard');
    }

    echo json_encode($req); exit;
  }

  public function register()
  {
    already_login();
    $data = [];
    $this->load->view('auth/Register', $data);
  }

  public function logout()
  {
    $this->session->sess_destroy();
    delete_cookie('token_login');
    return redirect('auth/login');
  }
}