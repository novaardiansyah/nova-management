<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MX_Controller 
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'M_Account';
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Daftar Akun',
      'subtitle' => 'Keuangan - Daftar Akun',
      'breadcrumb' => [
        ['title' => 'Keuangan', 'link' => base_url('keuangan/account')],
        ['title' => 'Daftar Akun', 'link' => base_url('keuangan/account')]
      ],
      'currency'    => requestModel($this->_modelPath, 'getCurrency', []),
      'typeAccount' => requestModel($this->_modelPath, 'getTypeAccount', []),

      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css?v=' . versionAsset())
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js?v=' . versionAsset()),
        base_url('assets/js/keuangan/index.js?v=' . versionAsset()),
        base_url('assets/js/keuangan/account.js?v=' . versionAsset()),
        // base_url('assets/js/keuangan/typeAccount.js?v=' . getTimes('now', 'YmdH')),
        // base_url('assets/js/keuangan/typeCurrency.js?v=' . getTimes('now', 'YmdH'))
      ]
    ];

    backend_layout('account/index', $data);
  }

  public function accountList()
  {
    $result = requestModel($this->_modelPath, 'accountList', []);
    echo json_encode($result);
  }

  public function storeAccount()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_storeAccount();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $logo = upload_file(['field' => 'logo', 'path' => 'assets/images/financeLogo', 'type' => 'image']);
    
    if ($logo['status'] == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => ['logo' => $logo['error']]]]); exit;
    }

    $idCurrency = trim(isset($_POST['idCurrency']) ? $_POST['idCurrency'] : '');
    $idCurrency = create_array($idCurrency, ';')[0];

    $send = [
      'name'       => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'idCurrency' => $idCurrency,
      'idType'     => trim(isset($_POST['idType']) ? $_POST['idType'] : ''),
      'amount'     => trim(isset($_POST['amount']) ? $_POST['amount'] : ''),
      'logo'       => $logo['file_name'],
      'isActive'   => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = requestModel($this->_modelPath, 'storeAccount', $send);
    echo json_encode($result);
  }

  private function _r_storeAccount()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'idCurrency', 'label' => 'Currency', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'idType', 'label' => 'Type', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'amount', 'label' => 'Amount', 'rules' => 'required|trim|numeric|min_length[1]|max_length[10]|greater_than[-1]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function editAccount()
  {
    $_id    = trim(isset($_POST['_id']) ? $_POST['_id'] : '');
    $result = requestModel($this->_modelPath, 'editAccount', ['_id' => $_id], 'object');
    echo json_encode($result);
  }

  public function updateAccount()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_updateAccount();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }


    $isUploadLogo = $_FILES['logo']['name'] !== '' ? true : false;

    if ($isUploadLogo == true)
    {
      $logo = upload_file(['field' => 'logo', 'path' => 'assets/images/financeLogo', 'type' => 'image']);
      
      if ($logo['status'] == false)
      {
        echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => ['logo' => $logo['error']]]]); exit;
      }
    }


    $idCurrency = trim(isset($_POST['idCurrency']) ? $_POST['idCurrency'] : '');
    $idCurrency = create_array($idCurrency, ';')[0];

    $send = [
      '_id'        => trim(isset($_POST['_id']) ? $_POST['_id'] : ''),
      'name'       => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'idCurrency' => $idCurrency,
      'idType'     => trim(isset($_POST['idType']) ? $_POST['idType'] : ''),
      'amount'     => trim(isset($_POST['amount']) ? $_POST['amount'] : ''),
      'logo'       => $isUploadLogo ? $logo['file_name'] : '',
      'isActive'   => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = requestModel($this->_modelPath, 'updateAccount', $send, 'object');

    if ($result->status == TRUE)
    {
      $data = $result->data;

      if (file_exists(FCPATH . 'assets/images/financeLogo/' . $data->oldLogo))
      {
        if ($data->oldLogo !== 'default-logo-finance.png')
        {
          unlink(FCPATH . 'assets/images/financeLogo/' . $data->oldLogo);
        }
      }
    }

    echo json_encode($result);
  }

  private function _r_updateAccount()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'idCurrency', 'label' => 'Currency', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'idType', 'label' => 'Type', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'amount', 'label' => 'Amount', 'rules' => 'required|trim|numeric|min_length[1]|max_length[10]|greater_than[-1]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function deleteAccount()
  {
    $_id = trim(isset($_POST['_id']) ? $_POST['_id'] : '');
    $result = requestModel($this->_modelPath, 'deleteAccount', ['_id' => $_id], 'object');

    if ($result->status == TRUE)
    {
      $data = $result->data->list;

      if (file_exists(FCPATH . 'assets/images/financeLogo/' . $data->logo))
      {
        if ($data->logo !== 'default-logo-finance.png')
        {
          unlink(FCPATH . 'assets/images/financeLogo/' . $data->logo);
        }
      }
    }

    echo json_encode($result);
  }
}