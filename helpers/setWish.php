<?php
require_once('../../../../wp-load.php');
require_once( ABSPATH . 'wp-config.php' );
date_default_timezone_set("America/Mexico_City");

header('Content-Type: multipart/form-data');


if ( ! is_user_logged_in() ) {
  return;
}

$movieId = $_POST['movieId'];

$userId = get_current_user_id();


$wishlist = get_userdata($userId)->wishlist;

$wishArr = explode(" ",$wishlist);
if(in_array($movieId, $wishArr) == false){
   $wishlist .= " ".$movieId;
}
else{
  $wishlist = '';
  foreach ($wishArr as $key => $value) {
    if($value != $movieId && $value != ''){
      $wishlist .= " ".$value;
    }
  }
}

update_user_meta($userId, 'wishlist', $wishlist);

$status = "ok";
$response = new stdClass();
$response->status = $status;
$response->wishlist = $wishlist;

echo json_encode($response);


