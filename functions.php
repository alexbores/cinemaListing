<?php
@ini_set( 'upload_max_size' , '500M' );
@ini_set( 'post_max_size', '500M');
@ini_set( 'max_execution_time', '500' );

require_once 'helpers/helperFunctions.php';

//configs
require_once 'config/generalConfig.php';
require_once 'config/usersConfig.php';

if (is_admin() ) {
  require_once 'config/adminConfig.php';
}