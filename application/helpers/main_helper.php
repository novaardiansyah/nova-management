<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function backend_layout($content, $data = [])
{
  $ci = get_instance();
  $ci->load->model('M_Main', 'main');

  $csrf_renewed = $ci->security->get_csrf_hash();

  $menu     = $ci->main->getMenu();
  $mainLogo = $ci->main->getMainLogo(['csrf_renewed' => $csrf_renewed]);

  $send = [
    'menu'     => $menu['status'] ? $menu['data'] : [],
    'mainLogo' => $mainLogo['status'] ? $mainLogo['data'] : [],
  ];
  
  $data = array_merge($data, $send);

  $ci->load->view('layout/header', $data);
  $ci->load->view('layout/sidebar');
  $ci->load->view('layout/breadcrumb');
  $ci->load->view($content);
  $ci->load->view('layout/footer');
  
  return true;
}

function insertAuditlog($data = [])
{
  $ci = get_instance();
  $ci->load->library('user_agent');

  $data = !(empty($data)) ? arrayToObject($data) : [];

  $send = [
    'idUser'      => isset($data->idUser) ? $data->idUser : 0,
    'idRole'      => isset($data->idRole) ? $data->idRole : 0,
    'idType'      => isset($data->idType) ? $data->idType : 0,
    'description' => isset($data->description) ? $data->description : null,
    'browser'     => $ci->agent->browser() . ' ' . $ci->agent->version(),
    'platform'    => $ci->agent->platform(),
    'ipAddress'   => $ci->input->ip_address(),
    'userAgent'   => $ci->agent->agent_string(),
    'isActive'    => 1,
    'created_by'  => isset($data->idUser) ? $data->idUser : 0,
    'created_at'  => getTimes('now'),
  ];

  $ci->db->insert('auditlogs', $send);
  return ['status' => true, 'message' => 'Berhasil merekam data ke auditlog.', 'data' => $send];
}

function requestModel($modelPath, $function, $data = [], $responseType = 'normal')
{
  $ci = get_instance();
  $ci->load->model($modelPath, 'model');

  $user = getSession('user');
  $user = $user ? arrayToObject($user) : [];

  $send = [
    'csrf_renewed' => $ci->security->get_csrf_hash(),
    'idUser'       => isset($user->id) ? $user->id : 0,
    'idRole'       => isset($user->idRole) ? $user->idRole : 0,
    'username'     => isset($user->username) ? $user->username : '',
    'email'        => isset($user->email) ? $user->email : '',
    'responseType' => $responseType
  ];

  $send    = array_merge($send, $data);
  $request = $ci->model->$function($send);

  return $request;
}

function responseModelFalse($message, $error, $data = [], $responseType = 'normal')
{
  $ci = get_instance();

  $data = array_merge($data, ['error' => $error, 'csrf_renewed' => $ci->security->get_csrf_hash()]);

  $response = [
    'status'  => false,
    'message' => $message,
    'data'    => $data
  ];

  if ($responseType == 'object') {
    return arrayToObject($response);
  }

  return $response;
}

function responseModelTrue($message, $data = [], $responseType = 'normal')
{
  $ci = get_instance();

  $data = array_merge((array) $data, ['csrf_renewed' => $ci->security->get_csrf_hash()]);

  $response = [
    'status'  => true,
    'message' => $message,
    'data'    => $data
  ];

  if ($responseType == 'object') {
    return arrayToObject($response);
  }

  return $response;
}