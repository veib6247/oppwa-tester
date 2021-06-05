<?php

function request($host, $id, $entity, $auth)
{
  $url = "$host/v1/query/$id?entityId=$entity";

  $ch = curl_init();

  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array("Authorization:Bearer $auth"),
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_SSL_VERIFYPEER => false, # this should be set to true in production
    CURLOPT_RETURNTRANSFER => true
  ]);

  $responseData = curl_exec($ch);

  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  curl_close($ch);

  return $responseData;
}

$responseData = request($_POST['host'], $_POST['id'], $_POST['entity'], $_POST['auth']);

echo $responseData;
