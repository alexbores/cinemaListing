<?php
//////////////////////////////////////////////////////role permisions



$roles = array('administrator');
// Loop through each role and assign capabilities
foreach($roles as $the_role) { 

     $role = get_role($the_role);

     $role->add_cap( 'read' );
     $role->add_cap( 'read_admin');
     $role->add_cap( 'read_private_admins' );
     $role->add_cap( 'edit_admin' );
     $role->add_cap( 'edit_admins' );
     $role->add_cap( 'edit_others_admins' );
     $role->add_cap( 'edit_published_admins' );
     $role->add_cap( 'publish_admins' );
     $role->add_cap( 'delete_others_admins' );
     $role->add_cap( 'delete_private_admins' );
     $role->add_cap( 'delete_published_admins' );     
     $role->add_cap( 'delete_published_admin' );  
     $role->add_cap( 'delete_admin');    
     $role->add_cap( 'delete_admins');                   
}



//////////////////////////////////////////////////////custom user meta
add_action( 'edit_user_profile', 'custom_user_profile_fields' );
 
function custom_user_profile_fields( $user ){
    echo '<h3 class="heading">Información</h3>';
    $wishlist = get_user_meta( $user->ID, 'wishlist', true);
    if ( $wishlist  == '') {
        $wishlist = '';
    }
?>
    
    <table class="form-table">
      

      <tr>
        <th><label>wishlist</label></th>
        <td><input type="text" class="input-text form-control" name="wishlist" value="<?php echo $wishlist ?>"/></td>
      </tr>


    </table>
    
<?php
}

function save_custom_user_profile_fields( $user_id ){
    update_user_meta( $user_id, 'wishlist', $_POST['wishlist'] );
}

add_action( 'show_user_profile', 'custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_custom_user_profile_fields' );
add_action( 'personal_options_update','save_custom_user_profile_fields');
?>