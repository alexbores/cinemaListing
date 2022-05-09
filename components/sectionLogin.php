<?php 
  $action = $_GET['action'];
?>

<section >
  <div class="content">
    <h2 class="txtC pB20">
    <?php if($action == 'register'){ 
      echo 'Register';
    }else{ 
      echo 'Log In';
    } ?>
    </h2>
  </div>
</section>

<section id="login" class="pB20 pT10">
    <div class="content innerContent <?php if($action == 'register'){echo 'hide';} ?>" id="loginCol">
    
    <?php 
        if($action == 'checkemail'){ 
          echo '<p class="txtC txtS3 pT10 pB10">Registration complete, check you email and log in!</p>';
        }

        $args = array(
            'echo' => true,
            'redirect' => '/account',
            'form_id' => 'loginform',
            'label_username' => __( 'Username' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in' => __( 'Log In' ),
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => NULL,
            'value_remember' => false );
            wp_login_form($args);
        
    
    ?>
    <p class="txtC txtS3 mT20">Don´t have and account? <a href="/account?action=register"><strong>Register now!</strong></a></p>
    
    </div>
    <div class="content innerContent <?php if($action != 'register'){echo 'hide';} ?>" id="registerCol">

    <form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
      <p class="login-username">
        <label for="user_login">Username</label>
        <input type="text" name="user_login" value="" id="user_login" class="input" />
      </p>
      <p class="login-username">
        <label for="user_login">E-Mail</label>
        <input type="text" name="user_email" value="" id="user_email" class="input"  />
      </p>


      <?php do_action('register_form'); ?>
      <p>A password will be e-mailed to you.</p>
      <input type="submit" value="Register" id="register" />
    </form>

    <p class="txtC txtS3 mT20">already have and account? <a href="/account"><strong>Log in</strong></a></p>

  </div>
</section>

<style>
  #login form input[type="submit"]{
    margin-left: auto;
    margin-right: auto;
    /*margin-top: 20px;*/
  }
  #login form > *{
    margin-top: 20px;
  }
</style>