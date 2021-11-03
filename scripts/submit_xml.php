<?php


/**
 * payload must be a field named "load" with the XML request as the value
 */

# build data string
$data = http_build_query(["load" => $_POST['data']]);

// execute function
// use http_build_query to format array into an http data parameter thingy
$responseData = request($_POST['url'], $data);

// echo the data to display on browser
echo $responseData;


/**
 * executes a CURL script to submit to ACI's endpoint
 */
function request($url, $data) {

  $ch = curl_init();

  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded;charset=UTF-8"]
  ]);

  // execute curl script
  $responseData = curl_exec($ch); 

  // container for curl script errors
  $err = curl_error($ch);

  // if there are errors, return the error number
  if($err) {
    return "cURL Error #:" . $err;
  }else{
    return $responseData;
  }  
}