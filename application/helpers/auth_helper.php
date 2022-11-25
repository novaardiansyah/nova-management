<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function isLogin()
{
  $isLogin = getSession('isLogin');
  if ($isLogin) return true;

  destroySession(['user', 'isLogin']);
  return redirect('auth');
}