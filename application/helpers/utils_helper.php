<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function getTimes($data = 'now', $format = 'Y-m-d H:i:s')
{
  date_default_timezone_set('Asia/Jakarta');
  return date($format, strtotime($data));
}

function format_date($data, $format = 'Y-m-d H:i:s')
{
  date_default_timezone_set('Asia/Jakarta');

  if (strpos($data, '/') !== false) {
    $data = str_replace('/', '-', $data);
  }

  return date_format(date_create($data), $format);
}


function getKey($typeKey = 'encoded_key')
{
  $ci = get_instance();
  
  $encoded_key       = $ci->config->item('encoded_key');
  $secret_key        = $ci->config->item('secret_key');
  $encrypt_key       = $ci->config->item('encrypt_key');
  $authorization_key = $ci->config->item('authorization_key');

  switch ($typeKey) {
    case 'encoded_key':
      return $encoded_key;
      break;
    case 'secret_key':
      return $secret_key;
    case 'encrypt_key':
      return $encrypt_key;
    case 'authorization_key':
      return $authorization_key;
    default:
      return $encoded_key;
      break;
  }
}

function validateKey($key = null, $typeKey = 'authorization_key')
{
  if (getKey($typeKey) !== $key) return arrayToObject(['status' => false, 'message' => 'Authorization Key Is Invalid.', 'data' => ['error' => 'TF7N']]);

  return arrayToObject(['status' => true, 'message' => 'Authorization Key Is Valid.', 'data' => []]);
}

function custom_encode($value)
{
  if (!$value) return false;

  $ci = get_instance();

  $key       = sha1(getKey('encoded_key'));
  $strLen    = strlen($value);
  $keyLen    = strlen($key);
  $j         = 0;
  $crypttext = '';

  for ($i = 0; $i < $strLen; $i++) {
    $ordStr = ord(substr($value, $i, 1));
    if ($j == $keyLen) {
      $j = 0;
    }
    $ordKey = ord(substr($key, $j, 1));
    $j++;
    $crypttext .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
  }

  return base64_encode($crypttext);
}

function custom_decode($value)
{
  if (!$value) return false;

  $ci = get_instance();

  $value       = base64_decode($value);
  $key         = sha1(getKey('encoded_key'));
  $strLen      = strlen($value);
  $keyLen      = strlen($key);
  $j           = 0;
  $decrypttext = '';

  for ($i = 0; $i < $strLen; $i += 2) {
    $ordStr = hexdec(base_convert(strrev(substr($value, $i, 2)), 36, 16));
    if ($j == $keyLen) {
      $j = 0;
    }
    $ordKey = ord(substr($key, $j, 1));
    $j++;
    $decrypttext .= chr($ordStr - $ordKey);
  }

  return $decrypttext;
}

function create_string($data = [], $separator = ';')
{
  $string = '';

  if (end($data) == '') {
    array_pop($data);
  }

  foreach ($data as $key => $value) {
    if ($key == count($data) - 1) {
      $string .= trim($value);
    } else {
      $string .= trim($value) . $separator;
    }
  }

  return $string;
}

function create_array($string = '', $separator = ';')
{
  $array = explode($separator, $string);

  if (end($array) == '') {
    array_pop($array);
  }

  return $array;
}

function textCapitalize($text)
{
  $text = trim($text);
  return ucwords(strtolower($text));
}

function textUpper($text)
{
  $text = trim($text);
  return strtoupper($text);
}

function textLower($text)
{
  $text = trim($text);
  return strtolower($text);
}

function getSession($session = '', $prefix = true)
{
  $ci = get_instance();

  if ($prefix) {
    return $session ? $ci->session->userdata($ci->config->item('secret_prefix') . '_' . $session) : $ci->session->userdata();
  }

  return $session ? $ci->session->userdata($session) : $ci->session->userdata();
}

function destroySession($session = [], $prefix = true)
{
  $ci = get_instance();

  if ($prefix) {
    foreach ($session as $value) {
      $ci->session->unset_userdata($ci->config->item('secret_prefix') . '_' . $value);
    }

    return true;
  }

  return $ci->session->unset_userdata($session);
}

function setSession($session = [], $prefix = true)
{
  $ci = get_instance();

  if ($prefix) {
    foreach ($session as $key => $value) {
      $ci->session->set_userdata($ci->config->item('secret_prefix') . '_' . $key, $value);
    }

    return true;
  }

  return $ci->session->set_userdata($session);
}

function arrayToObject($array)
{
  if (!is_array($array)) {
    return $array;
  }

  $object = new stdClass();
  if (is_array($array) && count($array) > 0) {
    foreach ($array as $name => $value) {
      $name = trim($name);
      if (!empty($name)) {
        $object->$name = arrayToObject($value);
      }
    }
    return $object;
  } else {
    return FALSE;
  }
}

function encryptKey($string, $type = 'encrypt_key', $algo = 'sha256')
{
  $value  = $string . getKey($type);
  $result = hash($algo, $value);
  return $result;
}

function random_tokens($length, $type = null, $seconds = false)
{
  $token = "";

  $lower_case = 'abcdefghijklmnopqrstuvwxyz';
  $upper_case = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $numbers    = '0123456789';

  if ($type == null) {
    $type = ['lowercase', 'uppercase', 'numeric'];
  }

  $final_string = '';

  if (in_array('lowercase', $type)) {
    $final_string .= $lower_case;
  }

  if (in_array('uppercase', $type)) {
    $final_string .= $upper_case;
  }

  if (in_array('numeric', $type)) {
    $final_string .= $numbers;
  }

  $max = strlen($final_string);

  for ($i = 0; $i < $length; $i++) {
    $token .= $final_string[crypto_rand_secure(0, $max - 1)];
  }

  if ($seconds) {
    $token .= getTimes('now', 's');
  }

  return $token;
}

function crypto_rand_secure($min, $max)
{
  $range = $max - $min;
  if ($range < 1) return $min; // not so random...

  $log    = ceil(log($range, 2));
  $bytes  = (int) ($log / 8) + 1;    // length in bytes
  $bits   = (int) $log + 1;          // length in bits
  $filter = (int) (1 << $bits) - 1;  // set all lower bits to 1

  do {
    $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    $rnd = $rnd & $filter; // discard irrelevant bits
  } while ($rnd > $range);

  return $min + $rnd;
}

function getCustomCookie($name, $prefix = true)
{
  $ci = get_instance();

  if ($prefix)
  {
    $name = $ci->config->item('cookie_prefix') . $name;
  }

  return $ci->input->cookie($name, true);
}

function setCustomCookie($name, $value, $expire = null)
{
  $ci = get_instance();

  if (!$expire) 
  {
    $expire = getTimes('-1 hours');
  }

  $expire = format_date($expire, 'Y-m-d') . ' 23:59:59';

  $ci->input->set_cookie([
    'name'   => $name,
    'value'  => $value,
    'expire' => strtotime($expire)
  ]);

  return getCustomCookie($name);
}

function versionAsset()
{
  $ci = get_instance();
  $status_app = $ci->config->item('status_app');

  if ($status_app == 'development') {
    return getTimes('now', 'YmdHis');
  }

  return getTimes('now', 'Ymd');
}

function upload_file($params = [])
{
  $ci = get_instance();

  $field = trim(isset($params['field']) ? $params['field'] : '');
  $path  = trim(isset($params['path']) ? $params['path'] : '');
  $type  = trim(isset($params['type']) ? $params['type'] : '');

  $file_name = $_FILES[$field]['name'];

  if ($file_name !== null) {
    $config_video = [
      'allowed_types'    => 'mp4|mkv',
      'max_size'         => '5120',
      'upload_path'      => $path,
      'file_name'        => getTimes('now', 'YmdHis') . '_' . $file_name
    ];

    $config_image = [
      'allowed_types'    => 'jpg|jpeg|png',
      'max_size'         => '1024',
      'upload_path'      => $path,
      'file_name'        => getTimes('now', 'YmdHis') . '_' . $file_name
    ];

    $ci->load->library('upload');

    if ($type == 'video') {
      $ci->upload->initialize($config_video);
    } else {
      $ci->upload->initialize($config_image);
    }

    if ($ci->upload->do_upload($field)) return ['status' => true, 'message' => 'Successfully uploaded the file', 'file_name' => trim($ci->upload->data('file_name'))];

    return ['status' => false, 'message' => 'File upload failed, please try again.', 'error' => $ci->upload->display_errors()];
  }
}