<?php


// elimina el wp bar del front
add_filter('show_admin_bar', '__return_false');



add_action('user_register','mnr_register_ok');
function mnr_register_ok($user_id){
  $referrer = $_SERVER['HTTP_REFERER'];  
  if ( !empty($referrer) && !strstr($referrer,'wp-admin')) {
       wp_redirect(home_url('/account?action=checkemail'));  
       exit;
  }
}

//cuando se cierre sessión redirige a home
if(isset($_GET['loggedout'])){
  $loggedout = $_GET['loggedout'];
  if($loggedout == true){
    $page  = home_url( '/' );
    wp_safe_redirect($page);
  }
}

// si el login falla redirige a account
add_action( 'wp_login_failed', 'mnr_login_fail' ); 
function mnr_login_fail( $username ) {
     $referrer = $_SERVER['HTTP_REFERER'];  
     if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin')) {
          wp_redirect(home_url('/account'));  
          exit;
     }
}

// agregar opcion de perder contraseña
add_action( 'login_form_middle', 'add_lost_password_link' );
function add_lost_password_link() {
    return '<p><a href="/wp-login.php?action=lostpassword">Forgot your password?</a></p>';
}


///////////////////////////////maintain head php para seo
function theme_slug_setup() {
   add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'theme_slug_setup' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
    function theme_slug_render_title() {
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
    }
    add_action( 'wp_head', 'theme_slug_render_title' );
endif;






