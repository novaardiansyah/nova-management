<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function backend_layout($content, $data = [])
{
  $ci = get_instance();
  $ci->load->model('M_Main', 'main');

  $menu = $ci->main->getMenu();

  $send = [
    'menu' => $menu->status ? $menu->data : []
  ];

  $data = array_merge($data, $send);

  $ci->load->view('layout/header', $data);
  $ci->load->view('layout/sidebar');
  $ci->load->view('layout/breadcrumb');
  $ci->load->view($content);
  $ci->load->view('layout/footer');
  
  return true;
}