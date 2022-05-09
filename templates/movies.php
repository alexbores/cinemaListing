<?php
  if ( ! defined( 'ABSPATH' ) ) {
      exit; // Exit if accessed directly
  }

  /* 
  Template Name: movies
  */
  $id = $_GET['id'];
  

  get_header();
  
?>

<input id="movieId" hidden value="<?php echo $id; ?>">
<?php 
  if(!isset($id)){
    mnrPart('sectionMoviesList');
  }
  else{
    mnrPart('sectionMovie');
  }

?>



<?php 
  get_footer();
?>




