<?php
  if ( ! defined( 'ABSPATH' ) ) {
      exit; // Exit if accessed directly
  }

  /* 
  Template Name: account
  */

  get_header();
  
?>


<?php 
  if (  is_user_logged_in() ) {
    mnrPart('sectionWishList');
  } 
  else{
    mnrPart('sectionLogin');
  }
?>

<?php 
  get_footer();
?>





