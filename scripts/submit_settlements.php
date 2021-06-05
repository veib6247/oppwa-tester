<?php
function request($host, $data, $bearer)
{
  $url = $host . '?' . $data;

  $ch = curl_init();

  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array("Authorization:Bearer $bearer"),
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


echo request($_POST['host'], $_POST['data'], $_POST['bearer']);
