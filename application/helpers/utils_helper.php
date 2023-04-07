<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function getTimestamp($date = 'now', $format = 'Y-m-d H:i:s')
{
  date_default_timezone_set('Asia/Jakarta');
  return date($format, strtotime($date));
}

function generate_captcha($length = 5)
{
  $ci = get_instance();
  $ci->load->helper('captcha');

  $vals = [
    'img_path'    => './assets/images/captcha/',
    'img_url'     => base_url('assets/images/captcha'),
    'img_width'   => '100',
    'img_height'  => 30,
    'expiration'  => 3600,
    'word_length' => $length,
    'font_size'   => 16,
    'pool'        => '0123456789',
    'colors'      => [
      'background' => [255, 255, 255],
      'border'     => [255, 255, 255],
      'text'       => [0, 0, 0],
      'grid'       => [255, 40, 40]
    ],
  ];

  $cap = create_captcha($vals);

  $data = [
    'captcha_time'     => $cap['time'],
    'ip_address'       => $ci->input->ip_address(),
    'captcha_word'     => $cap['word'],
    'captcha_filename' => $cap['filename']
  ];

  return $data;
}

function encrypt($string = '') {
  $key = md5('nova-ardiansyah');
  $iv  = openssl_random_pseudo_bytes(16);

  $encrypted = openssl_encrypt($string, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
  $result    = base64_encode($iv . $encrypted);
  return $result;
}

function decrypt($string = '') {
  $key       = md5('nova-ardiansyah');
  $string    = base64_decode($string);
  $iv        = substr($string, 0, 16);
  $encrypted = substr($string, 16);

  $result = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
  return $result;
}

function request($array = [], $key = '', $default = '', $action = '')
{
  $ci = get_instance();

  $array = (array) $array;
  $result = isset($array[$key]) ? $array[$key] : $default;

  if ($action === 'escape_str') $result = $ci->db->escape_str($result);
  if ($action === 'hash_pass') $result = hash_pass($result);
  if ($action === 'textUppercase') $result = textUppercase($result);

  return $result;
}

function request_api($url = '', $method = 'GET', $data = [], $headers = [])
{
  $ci = get_instance();
  
  $url  = $ci->config->item('api_url') . $url;
  $data = ['data' => $data];

  $curl = curl_init();

  if ($method === 'GET') {
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    $url = $url . '?' . http_build_query($data);
  }

  if ($method === 'POST') {
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  }

  if ($method === 'PUT') {
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  }

  if ($method === 'DELETE') {
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  }

  if ($headers) {
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  }

  curl_setopt_array($curl, array(
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  ));


  $response = curl_exec($curl);
  $error    = curl_error($curl);
  curl_close($curl);

  $result = json_decode($response);
  $result->csrf = $ci->security->get_csrf_hash();

  return $result;
}

function response_api($status = 400, $message, $data = [], $etag = null, $params = [])
{
  $new_etag = md5($status . $message . json_encode($data));
  if ($etag === $new_etag) {
    $status = 304;
  }
  $etag = $new_etag;

  $status_code = $status;

  if ($status_code == 400 || $status_code == 401 || $status_code == 403 || $status_code == 404 || $status_code == 405 || $status_code == 500) {
    $status = false;
  }
  
  if ($status_code == 200 || $status_code == 201 || $status_code == 204) {
    $status = true;
  }

  $response = [
    'status'      => $status,
    'status_code' => $status_code,
    'message'     => $message,
    'data'        => $status_code == 304 ? [] : $data,
    'etag'        => $etag
  ];

  $response = array_merge($response, $params);
  return $response;
}

function random_token($type, $length, $case = 'lower')
{
  $ci = get_instance();
  $ci->load->helper('string');

  $token = random_string($type, $length);

  if ($case === 'upper') {
    $token = strtoupper($token);
  }

  return $token;
}

function version($type = 'style-script')
{
  if ($type === 'app') return '1.0.0';
  if ($type === 'api') return '1.0.0';

  $for_domain = ['localhost', '127.0.0.1'];
  $domain = $_SERVER['HTTP_HOST'];

  if (in_array($domain, $for_domain)) return '?v=' . getTimestamp('now', 'YmdHis');

  return '?v=' . getTimestamp('now', 'YmdH');
}

function create_array($string = '', $delimiter = ';')
{
  $array = array_filter(explode($delimiter, $string));
  return $array;
}

function hash_pass($string = '')
{
  $ci = get_instance();
  $string = trim($string) ?? random_token('alnum', 10, 'upper');
  $string = substr($ci->config->item('APP_HASH_KEY'), 0, 18) . $string . substr($ci->config->item('APP_HASH_KEY'), -18);
  return md5(hash('sha256', $string));
}

function set_session($data = [])
{
  $ci = get_instance();
  
  $temp = [];
  foreach ($data as $key => $value) {
    $key = $key . $ci->config->item('APP_SESSION_SUFFIX');
    $ci->session->set_userdata($key, $value);
    $temp[$key] = $value;
  }

  return (Object) ['status' => true, 'message' => 'Session has been set', 'data' => $temp];
}

function get_session($key = '')
{
  $ci = get_instance();
  $key = $key . $ci->config->item('APP_SESSION_SUFFIX');
  return $ci->session->userdata($key);
}

function unset_session($keys = [])
{
  $ci = get_instance();
  
  $temp = [];
  foreach ($keys as $key) {
    $key = $key . $ci->config->item('APP_SESSION_SUFFIX');
    $ci->session->unset_userdata($key);
    $temp[] = $key;
  }

  return (Object) ['status' => true, 'message' => 'Session has been unset', 'data' => $temp];
}

function textCapitalize($string = '')
{
  if (is_string($string)) {
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);
  }

  $string = ucwords(strtolower($string));
  return $string;
}

function textUppercase($string = '')
{
  if (is_string($string)) {
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);
  }

  $string = strtoupper($string);
  return $string;
}

function textLowercase($string = '')
{
  if (is_string($string)) {
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);
  }

  $string = strtolower($string);
  return $string;
}