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
  if (getKey($typeKey) !== $key) return ['status' => false, 'message' => 'Authorization Key Is Invalid.', 'data' => ['error' => 'TF7N']];

  return ['status' => true, 'message' => 'Authorization Key Is Valid.', 'data' => []];
}