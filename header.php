<!doctype html>
<html lang="es">

<head>


  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  

  <meta name="author" content="alejandro bores rivera">
  <meta name="apple-mobile-web-app-capable" content="yes"/>
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
  
  <style mnr-styles=""></style>

  <?php wp_head(); ?>

  <link rel="stylesheet" href="<?php mnrRoot(); ?>/style.css" >
 

  <script src="<?php mnrRoot(); ?>/scripts/moonRise/moonRise.js" rel="preload" as="script"></script>
  <script src="<?php mnrRoot(); ?>/scripts/moonRise/moonRiseStyles.js" rel="preload" as="script"></script>
  
</head>


<body >
  
<noscript>
   It appears that JavaScript is deactivated, this web needs JavaScript to work
</noscript>



<input  hidden disabled id="mnr-mainRoot" mnr-bind="root" mnr-bind-set="true" value="<?php mnrRoot(); ?>">

<?php if(is_user_logged_in()){ ?>

  <input hidden disabled id="whishlist" mnr-bind="wishlist" mnr-bind-set="true" 
         value="<?php echo get_userdata(get_current_user_id())->wishlist; ?>">
  
<?php } ?>

<input hidden disabled mnr-bind="userId" mnr-bind-set="true" value="<?php echo get_current_user_id(); ?>">

<?php 
  mnrPart( 'sectionPageLoader'); 
  mnrPart('sectionMenu'); 
  mnrPart( 'sectionModalMessage');
?>

<main >



