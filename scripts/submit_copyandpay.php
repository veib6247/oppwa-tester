<?php
# start the session to reuse the API endpoint used
# we only need the host anyways
session_start();

function request($url, $data, $authorization)
{

  # initialize cURL request
  $curl = curl_init();

  /**
   * setopts in array
   */
  curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => array("Authorization:Bearer $authorization"),
    CURLOPT_POST => 1, # true
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_SSL_VERIFYPEER => false, # this should be set to true in production
    CURLOPT_RETURNTRANSFER => true
  ]);

  /**
   * execute cURL and store the response
   */
  $responseData = curl_exec($curl);

  /**
   * error handling: 
   * 
   * if the request fails, there will be an error number
   * after that, you can return the error string
   */
  if (curl_errno($curl)) {
    # return error string
    return curl_error($curl);
  }

  # close connection
  curl_close($curl);

  /**
   * if all goes well, return the response
   */
  return $responseData;
}

# save the posted url to extract the host later
$_SESSION['cnp_url'] = $_POST['url'];
$_SESSION['cnp_data'] = $_POST['data'];
$_SESSION['cnp_authorization'] = $_POST['authorization'];

# call the function then echo the response
$responseData = request($_POST['url'], $_POST['data'], $_POST['authorization']);

echo $responseData;
