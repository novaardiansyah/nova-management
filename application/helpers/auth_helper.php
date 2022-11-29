<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function isLogin($data = [])
{
  $ci = get_instance();
  $ci->load->model('M_Main', 'main');
  $redirect = 'auth';

  if (isset($data['redirect'])) $redirect = $data['redirect'];

  $csrf_renewed = $ci->security->get_csrf_hash();
  $tokens       = getCustomCookie('token-login');

  if (!isset($tokens)) 
  {
    if (isset($data['checkAlreadyLogin']) && $data['checkAlreadyLogin'] == true) return true;
    return invalidLogin($redirect);
  }
  
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

  if (!$validateTokenLogin->status) return invalidLogin($redirect);

  setSession(['user' => $validateTokenLogin->data->user, 'isLogin' => true]);

  if (isset($data['checkAlreadyLogin']) && $data['checkAlreadyLogin'] == true) return redirect($redirect);

  return $validateTokenLogin;
}

function isAlreadyLogin()
{
  return isLogin(['redirect' => 'main', 'checkAlreadyLogin' => true]);
}

function invalidLogin($redirect = 'auth')
{
  destroySession(['user', 'isLogin']);
  setCustomCookie('token-login', '', null);
  return redirect($redirect);
}