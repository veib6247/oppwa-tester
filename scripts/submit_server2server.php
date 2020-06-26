<?php
# start the session to reuse the API endpoint used
# we only need the host anyways
session_start();

function request($url, $data, $auth)
{

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:Bearer $auth"));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $responseData = curl_exec($ch);

  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  curl_close($ch);

  return $responseData;
}

# save the posted url to extract the host later
$_SESSION['url'] = $_POST['url'];
$_SESSION['data'] = $_POST['data'];
$_SESSION['authorization'] = $_POST['authorization'];

# call the function then echo the response
$responseData = request($_POST['url'], $_POST['data'], $_POST['authorization']);

echo $responseData;
