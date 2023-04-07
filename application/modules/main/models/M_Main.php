<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Main extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function menu($data = [])
  {
    return request_api('Menu', 'GET', $data);
  }

  public function get_session_by_token($data = [])
  {
    return request_api('Auth/session_by_token', 'GET', $data);
  }
}