<?php
  if ( ! defined( 'ABSPATH' ) ) {
      exit; // Exit if accessed directly
  }

  /* 
  Template Name: actors
  */
  $id = $_GET['id'];
  

  get_header();
  
?>

<input id="actorId" hidden value="<?php echo $id; ?>">
<?php 
  if(!isset($id)){
    mnrPart('sectionActorList');
  }
  else{
    mnrPart('sectionActor');
  }

?>



<?php 
  get_footer();
?>




