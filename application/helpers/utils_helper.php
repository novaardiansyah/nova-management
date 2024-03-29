<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ramsey\Uuid\Uuid;

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
  
  if ($result !== $default && $action === 'hash_pass') $result = hash_pass($result);
  if ($result !== $default && $action === 'textUppercase') $result = textUppercase($result);

  return $result;
}

function request_api($url = '', $method = 'GET', $data = [], $headers = [])
{
  $ci = get_instance();
  $ci->load->library('user_agent');
  
  $url  = $ci->config->item('api_url') . '/' . $url;
  $data = ['data' => $data];
  $user = get_session('user') ? (Object) get_session('user') : (Object) [];

  $curl = curl_init();

  if ($method === 'GET') {
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    $url = $url . '?' . http_build_query($data);
  }

  if ($method === 'POST') {
    if ($user) {
      $data = array_merge($data['data'], [
        'user_id'    => $user->id ?? null,
        'role_id'    => $user->role_id ?? null,
        'ip_address' => $ci->input->ip_address() ?? null,
        'user_agent' => $ci->agent->agent_string() ?? null,
        'browser'    => $ci->agent->browser() ?? null,
        'version'    => $ci->agent->version() ?? null,
        'platform'   => $ci->agent->platform() ?? null,
        'mobile'     => $ci->agent->mobile() ?? null,
        'robot'      => $ci->agent->robot() ?? null,
        'referrer'   => $ci->agent->referrer() ?? null,
        'csrf'       => $ci->security->get_csrf_hash() ?? null
      ]);

      $data = ['data' => $data];
    }

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

  if ($user) {
    $headers = array_merge($headers, [
      'x-api-key: ' . $user->api_key ?? null,
      'x-debug-key: ' . $user->debug_key ?? null,
      'x-refresh-token: ' . get_cookie('token_login') ?? null
    ]);
  }

  curl_setopt_array($curl, array(
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER     => $headers
  ));

  $response = curl_exec($curl);
  $error    = curl_error($curl);
  curl_close($curl);

  if ($error) return $error;
  return $response ? json_decode($response) : null;
}

function response_api($status = 400, $message, $data = [], $params = [])
{
  $ci = get_instance();

  $debug_key = $ci->input->get_request_header('x-debug-key', true);
  $is_debug = false;

  $debug_mode = $ci->db->query("SELECT a.id, a.uid, a.token, a.is_active, a.type_id, a.user_id, a.expired_at FROM users_tokens AS a WHERE a.is_deleted = 0 AND a.is_active = 1 AND a.type_id = 3 AND a.expired_at >= CURRENT_TIMESTAMP AND a.token = '$debug_key'")->row();
  if (!empty($debug_mode)) $is_debug = true;

  $etag = $ci->input->get_request_header('etag', true);

  $new_etag = md5($status . $message . json_encode($data));
  if ($etag === $new_etag) $status = 304;
  $etag = $new_etag;

  $status_code = $status;
  $status      = false;

  if ($status_code == 200 || $status_code == 201 || $status_code == 204) $status = true;
 
  $response = [
    'status'      => $status,
    'status_code' => $status_code,
    'etag'        => $etag,
    'csrf'        => request($_POST['data'] ?? [], 'csrf') ?? null,
    'message'     => $status_code == 304 ? $message . ' (Not Modified)' : $message,
    'data'        => $status_code == 304 ? [] : $data
  ];
  
  if ($is_debug == false) unset($params['lastq']);
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

function access_restric()
{
  $ci = get_instance();
  
  $now     = getTimestamp('now', 'Y-m-d H:i:s');
  $user_id = request($_POST['data'] ?? [], 'user_id', null);

  if ($user_id == null) return response_api(401, 'Unauthorized, user has not been logged in');
  
  $api_key = $ci->input->get_request_header('x-api-key', true);
  if ($api_key == null) return response_api(401, 'Unauthorized, token not defined');

  $user = $ci->db->query("SELECT a.id, a.uid, a.username, a.email, a.fullname, a.phone, a.role_id FROM users AS a WHERE a.is_active = 1 AND a.is_deleted = 0 AND a.uid = '$user_id'")->row();

  if (empty($user)) return response_api(401, 'Unauthorized, user has not valid');

  $token = $ci->db->query("SELECT a.id, a.uid, a.token, a.is_active, a.type_id, a.user_id, a.expired_at FROM users_tokens AS a WHERE a.is_deleted = 0 AND a.is_active = 1 AND a.type_id = 2 AND a.expired_at >= CURRENT_TIMESTAMP AND a.user_id = '$user->id' AND a.token = '$api_key'")->row();

  if (empty($token)) {
    $refresh_token = $ci->input->get_request_header('x-refresh-token', true);
    $new_api_key = null;

    if ($refresh_token != null) {
      $check_refresh_token = $ci->db->query("SELECT a.id, a.uid, a.token, a.is_active, a.type_id, a.user_id, a.expired_at FROM users_tokens AS a WHERE a.is_deleted = 0 AND a.is_active = 1 AND a.type_id = 1 AND a.expired_at >= CURRENT_TIMESTAMP AND a.user_id = '$user->id' AND a.token = '$refresh_token'")->row();

      if (!empty($check_refresh_token)) {
        $check_prev_api_key = $ci->db->query("SELECT a.id, a.uid, a.token, a.is_active, a.type_id, a.user_id, a.expired_at FROM users_tokens AS a WHERE a.is_deleted = 0 AND a.is_active = 1 AND a.type_id = 2 AND a.user_id = '$user->id'")->row();

        $new_api_key = random_token('alnum', 128, 'lower');

        if (!empty($check_prev_api_key)) {
          $ci->db->update('users_tokens', [
            'token'      => $new_api_key,
            'expired_at' => getTimestamp('+1 hours', 'Y-m-d H:i:s'),
            'updated_at' => $now,
            'updated_by' => $user->id
          ], ['id' => $check_prev_api_key->id]);
        } else {
          $ci->db->insert('users_tokens', [
            'uid'        => uid(),
            'user_id'    => $user->id,
            'type_id'    => 2,
            'token'      => $new_api_key,
            'created_by' => $user->id,
            'expired_at' => getTimestamp('+1 hours', 'Y-m-d H:i:s')
          ]);
        }
      }
    }

    if ($new_api_key != null) return response_api(401, 'Unauthorized, token is not valid or has expired', ['new_api_key' => $new_api_key], ['error' => 'token_expired', 'action' => 'refresh_token']);

    return response_api(401, 'Unauthorized, token is not valid or has expired');
  }

  $token->id = $token->uid;
  unset($token->uid);

  return ['status' => true, 'message' => 'Authorized', 'data' => [
    'token' => $token,
    'user'  => $user
  ]];
}

function uid()
{
  return Uuid::uuid4();
}