<?php


// remover mensaje de bienvenida
add_filter('gettext', 'change_howdy', 10, 3);
function change_howdy($translated, $text, $domain) {

    if (false !== strpos($translated, 'Howdy'))
        return str_replace('Howdy', 'Bienvenido', $translated);

    return $translated;
}



// bloquear acceso a usuario y redirigir
if ( is_admin() && is_user_logged_in() ) {
  $role = mnrGetCurrentRole();
  if ($role != 'administrator' && $role != 'editor' && $role != 'author'){
      if ( wp_safe_redirect( '/account' ) ) {
        exit;
      }
  }
}

///////////////////////////////////////////////menus
function custom_menus() {
  register_nav_menus(
    array(
      'mobile' => __( 'Mobile' ),
    )
  );
}
add_action( 'init', 'custom_menus' ); 




?>