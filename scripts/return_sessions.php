<?php
# check if sessions has started already
if (session_id() == '') {
  session_start();
}


# create associative array to return the session values to the front-end
$session_variables = array(
  "url" => "",
  "data" => "",
  "authorization" => ""
);


# check if the session variables exists
if(isset($_SESSION['cnp_url'])){

  # check if it's not empty
  if ($_SESSION['cnp_url'] != "" && $_SESSION['cnp_data'] != "" && $_SESSION['cnp_authorization'] != "") {
    
    # get the values from the session
    $session_variables['url'] = $_SESSION['cnp_url'];
    $session_variables['data'] = $_SESSION['cnp_data'];
    $session_variables['authorization'] = $_SESSION['cnp_authorization'];
  } 
}


/**
 * returns a blank array if none of the variables are set
 */
echo json_encode($session_variables);
