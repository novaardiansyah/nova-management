<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function isLogin()
{
  $ci = get_instance();
  $ci->load->model('M_Main', 'main');

  $csrf_renewed = $ci->security->get_csrf_hash();
  $tokens       = getCustomCookie('token-login');

  if (!isset($tokens)) return invalidLogin();
  
  // * Tokens : token;idUser;idType;expired_at
  $tokens = create_array($tokens, ';');

  $s_token = [
    'token'        => isset($tokens[0]) ? $tokens[0] : '',
    'idUser'       => isset($tokens[1]) ? $tokens[1] : '',
    'idType'       => isset($tokens[2]) ? $tokens[2] : '',
    'csrf_renewed' => $csrf_renewed
  ];

  $validateTokenLogin = $ci->main->validateTokenLogin($s_token);
  $validateTokenLogin = arrayToObject($validateTokenLogin);

  if (!$validateTokenLogin->status) return invalidLogin();

  return $validateTokenLogin;
}

function isAlreadyLogin()
{
  $ci = get_instance();

  if (getSession('isLogin')) return redirect('main');

  return true;
}

function invalidLogin()
{
  destroySession(['user', 'isLogin']);
  setCustomCookie('token-login', '', null);
  return redirect('auth');
}