<?php 

echo request("json", "ca5e9ca7000ea88d0e59bce27911dad6", $_POST["bin"]);

function request($format, $api_key, $bin)
{
  // main API endpoint
  $host = "https://api.bincodes.com/bin/?";

  // build the URL query
  $query = http_build_query([
    "format" => $format,
    "api_key" => $api_key,
    "bin" => $bin
  ]);

  //append
  $url = $host . $query;

  // init cURL instance
  $ch = curl_init();

    // set cURL opt
    curl_setopt_array($ch, [
      CURLOPT_URL => $url,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_SSL_VERIFYPEER => true, # this should be set to true in production
      CURLOPT_RETURNTRANSFER => true
    ]);

  // Execute cURL
  $responseData = curl_exec($ch);
  
  // basic error checking
  if (curl_errno($ch)) {
    return curl_error($ch);
  }

  // close connection
  curl_close($ch);

  // return data
  return $responseData;
}