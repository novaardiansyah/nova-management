<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

function getKey($typeKey = 'encoded_key')
{
  $encoded_key = '649ce3fa-4ac5-4005-9830-53a2b7e0192a';
  $secret_key = 'cb13331d-829c-499f-8089-b301befe3e83';
  $encrypt_key = '85aaac43-bbf3-4dd4-9361-d165415b30d6';
  $authorization_key = '94c8f165-73ed-4940-990e-e645e2f4831b';

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
    return $ci->session->userdata($ci->config->item('secret_prefix') . '_' . $session);
  }

  return $ci->session->userdata($session);
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

