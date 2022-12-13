<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function isLogin($data = [])
{
  $ci = get_instance();
  $ci->load->model('M_Main', 'main');

  if (isset($data['redirect'])) $redirect = $data['redirect'];

  $loginToken   = getCustomCookie('login-token');
  $accessToken  = getCustomCookie('access-token');

  if (!isset($loginToken) || !isset($accessToken)) 
  {
    if (isset($data['checkAlreadyLogin']) && $data['checkAlreadyLogin'] == true) return true;
    return invalidLogin();
  }
  
  // * Tokens : token;idUser;idType;expired_at
  $loginToken  = create_array($loginToken, ';');
  $accessToken = create_array($accessToken, ';');
  
  $send = [
    'loginToken'  => isset($loginToken[0]) ? $loginToken[0] : '',
    'accessToken' => isset($accessToken[0]) ? $accessToken[0] : ''
  ];
  
  $validateTokenLogin = requestApi(api_url(('auth/validateTokens')), 'POST', $send);
  
  if (!$validateTokenLogin->status) return invalidLogin();
  if (isset($data['checkAlreadyLogin']) && $data['checkAlreadyLogin'] == true) return redirect($redirect);

  return $validateTokenLogin;
}

function isAlreadyLogin()
{
  return isLogin(['redirect' => 'main', 'checkAlreadyLogin' => true]);
}

function invalidLogin($redirect = 403)
{
  destroySession(['user', 'isLogin']);
  setCustomCookie('login-token', '', null);
  setCustomCookie('access-token', '', null);
  
  if ($redirect == 403) return redirect('error/403-forbidden');

  return redirect($redirect);
}