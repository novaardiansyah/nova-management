<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function assets($path = '')
{
  return base_url('assets/' . $path);
}

function adminlte($path = '')
{
  return base_url('assets/plugins/template-admin/' . $path);
}

function backend($view = '', $data = [])
{
  $ci = get_instance();
  $ci->load->model('main/M_Main');

  $data['menu'] = [];
  $data['view'] = $view;
  $ci->load->view('layouts/main/Main', $data);
}

function is_login()
{
  $ci = get_instance();

  date_default_timezone_set('Asia/Jakarta');
  $user = (Object) get_session('user');
  $ci->load->model('main/M_Main');
  
  if (!isset($user->id)) {
    $token_login = get_cookie('token_login');
    
    if ($token_login) {
      $request = $ci->M_Main->get_session_by_token(['token' => $token_login]);
      
      if ($request->status) {
        $user = $request->data;
        $user->api_key = $user->api_key->token;
        set_session(['user' => $user]);
        
        return true;
      }
    }
    
    return redirect('auth');
  }

  return true;
}

function already_login()
{
  $ci = get_instance();
  $user = (Object) get_session('user');
  $ci->load->model('main/M_Main');
  
  if (!isset($user->id)) {
    $token_login = get_cookie('token_login');
      
    if ($token_login) {
      $request = $ci->M_Main->get_session_by_token(['token' => $token_login]);
      
      if ($request->status) {
        $user = $request->data;
        $user->api_key = $user->api_key->token;
        set_session(['user' => $user]);
        
        return redirect('dashboard');
      }
    }

    return true;
  }
  
  return redirect('dashboard');
}