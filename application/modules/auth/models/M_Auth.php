<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function attempt_login($data = [])
  {
    return request_api('v1/Auth/login', 'POST', $data);
  }
}