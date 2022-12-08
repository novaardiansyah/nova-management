<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactUs extends MX_Controller
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'M_ContactUs';
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Hubungi Kami',
      'subtitle' => 'Layanan Pelanggan - Hubungi Kami',
      'breadcrumb' => [
        ['title' => 'Layanan Pelanggan', 'link' => base_url('customerService/contactUs')],
        ['title' => 'Hubungi Kami', 'link' => base_url('customerService/contactUs')]
      ],

      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css?v=' . versionAsset())
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js?v=' . versionAsset()),
        base_url('assets/js/customerService/contactUs.js?v=' . versionAsset())
      ]
    ];

    backend_layout('contactUs/index', $data);
  }

  public function contactUsList()
  {
    $result = requestModel($this->_modelPath, 'contactUsList', []);
    echo json_encode($result);
  }

  public function storeContactUs()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_storeContactUs();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $send = [
      'name'     => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'email'    => trim(isset($_POST['email']) ? $_POST['email'] : ''),
      'phone'    => trim(isset($_POST['phone']) ? $_POST['phone'] : ''),
      'message'  => trim(isset($_POST['message']) ? $_POST['message'] : ''),
      'isActive' => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = requestModel($this->_modelPath, 'storeContactUs', $send);
    echo json_encode($result);
  }

  private function _r_storeContactUs()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'phone', 'label' => 'Phone', 'rules' => 'trim|numeric|min_length[9]|max_length[15]'],
      ['field' => 'message', 'label' => 'Message', 'rules' => 'required|trim|max_length[255]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  private function _r_updateContactUs()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'phone', 'label' => 'Phone', 'rules' => 'trim|numeric|min_length[9]|max_length[15]'],
      ['field' => 'message', 'label' => 'Message', 'rules' => 'required|trim|max_length[255]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }
}
