<?php

//regresa un arreglo del menu correspondiente a al seccion indicada 
function mnrGetMenu( $location = null ){
  if($location == null){
    return array();
  }

  $locations = get_nav_menu_locations();
  if ( isset($menu) == false && $location && $locations && isset( $locations[ $location ] ) ) {
      $menu = wp_get_nav_menu_object( $locations[ $location ] );
  }
  if ( $menu && ! is_wp_error( $menu ) && ! isset( $menu_items ) ) {
      $menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );
  }

  if(isset($menu_items) == false){
    return array();
  }
  
  $menu = array();
  $numMenu = 0;
  
  foreach ($menu_items as $i => $value) {
    

    $hasParent = false;
    for ($j=0; $j < count($menu); $j++) { 
       if(isset($menu[$j]['ID'])){
         if((int)$menu[$j]['ID'] == (int)$value->menu_item_parent && (int)$value->menu_item_parent != 0){
              array_push(
                $menu[$j]['options'], 
                array($value->title,'href="'.$value->url.'"', 'target="'.$value->target.'"') 
              );
              $hasParent = true;
              break;
         }
       }
    }
    if($hasParent == false){
       $numMenu ++;
       $temp = array(
            'ID' => $value->ID,
            'options' => array(
               array($value->title, 'href="'.$value->url.'"'),
            ),
            'num' => $numMenu,
            'target' => $value->target,
       );
       array_push($menu, $temp);
    }
  
  }
  
  
  // var_dump($menu);
  // var_dump($menu_items);
  return $menu;
}


//imprime el formato de estilo para imagen de fondo
//usado para remplazar "sytle='backgroun-image...'"
//recibe el link de la imagen
function mnrBackImg( $link ){
  if(isset($link) == false){
    echo '';
    return;
  }

  echo 'style="background-image: url('.$link.');"';
}

//imprime la dirección del tema
function mnrRoot($link = null){
  $path = '';
  if($link != null){
    $path = '/assets/';
  }
  else{
    $link = '';
  }
  echo get_bloginfo('template_directory').$path.$link;
}
//regresa la dirección del tema
function mnrGetRoot($link = null){
  $path = '';
  if($link != null){
    $path = '/assets/';
  }
  else{
    $link = '';
  }
  return get_bloginfo('template_directory').$path.$link;
}


/////////////////////////////////////////////////////////////posts

//pide un post id y regresa sus meta datos en un objeto
function mnrGetPost($id = null){
  $postInfo = array();

  if($id != null && $id > 0){
    $metas = get_post_meta($id, '', true);
    $post = get_post($id);
    foreach ($metas as $key => $value) {
      $postInfo[$key] = $value[0];
    }
    foreach ($post as $key => $value) {
      $postInfo[$key] = $value; 
    }
    $postInfo['permalink'] = get_post_permalink($id);


  }
  return $postInfo;
}
function mnrGetPostJson($id = null){
  $jsonTemp = mnrGetPost($id);
  return json_encode($jsonTemp);
}
function mnrGetPosts($args = null){
  $query = array();
  if($args != null){
    $args['fields'] = 'ids';
    $query =  get_posts($args);
  }
  return $query;
}
function mnrPostUpdate($data = null){
  if($data == null){
    return;
  }
  else if(!isset($data['postId'])){
    return;
  }
  else{
    $values = array(
       'ID' => $data['postId'],
       'meta_input' => array()
    );
    // $metas = array_keys(get_post_meta($data['postId'], '', true));
    
    foreach ($data as $key => $value) {
      if(strpos($key, 'mnr-') !== false){
        $values['meta_input'][$key] = $value;
      }
      else if($key == 'title'){
        $values['post_title'] = $value;
      }
      else if($key == 'content'){
        $values['post_content'] = $value;
      }
    }
    wp_update_post( $values);
    return $values;
  }
}
function mnrPostCreate(){
}
function mnrPostDelete(){
}





///////////////////////////////////////////////////////products
function mnrGetProduct($id = null){
  $postInfo = array();

  if($id != null){
   
    $postInfo = mnrGetPost($id);
    $postInfo['cats'] = get_the_terms($id,'product_cat');
    if($postInfo['cats'][0]){
      $postInfo['primaryCat'] = $postInfo['cats'][0]->slug;
    }

    $product = wc_get_product($id);
    $postInfo['shortDescription'] = $product->short_description;
    $postInfo['description'] = $product->description;
    $postInfo['productType'] = $product->get_type();
    
    $postInfo['rating'] = $product->average_rating;
    $postInfo['ratingCount'] = $product->review_count;
    
    $postInfo['attributes'] = array();
    $postInfo['variants'] = array();
    

    $postInfo['currency'] = get_option('woocommerce_currency');

    if($postInfo['productType'] != 'variable'){
      $postInfo['regularPrice'] =  $product->get_regular_price();
      $postInfo['salePrice'] = $product->get_sale_price();
      $postInfo['price'] =  $product->get_price();
    }
    else{
      $postInfo['price'] =  $product->get_price();
      $postInfo['children'] = $product->get_children(); 

      //////////////////////////////////////////// obtener todas las variantes

      // var_dump($product->get_available_variations());
      foreach ($postInfo['children'] as $key=>$value) {
        
          $variantObject = array();
          $variantObject['attributes'] = array();
        
          $_product = new WC_Product_Variation($value);



        if($_product->get_price() != '' && $_product->status == 'publish'){
          $variation_data = $_product->get_variation_attributes(); // variation data in array
          // var_dump(woocommerce_get_formatted_variation($variation_data, true));
          // var_dump($variation_data);
          $variantObject['title'] = $value;

          $variantObject['stockStatus'] = $_product->stock_status;
          $variantObject['stock'] = $_product->stock_quantity;
          
          
          foreach ($variation_data as $key => $value2) {
            array_push($variantObject['attributes'] , $value2);
          }
          


          $variantObject['thumb_id'] = get_post_thumbnail_id($value);
          $variantObject['galleryPos'] = -1;
          $variantObject['regularPrice'] =  $_product->get_regular_price();
          $variantObject['salePrice'] = $_product->get_sale_price();
          $variantObject['price'] =  $_product->get_price();
          $variantObject['downloadable'] = mnrParseBool($_product->downloadable);
          $variantObject['virtual'] = mnrParseBool($_product->virtual);
          $variantObject['description'] = $_product->get_description();
        
          // var_dump($_product->get_description());
          array_push($postInfo['variants'], $variantObject);

        }
      }


      ////////////////////////////////////////////////set attributes
      $attributes = $product->get_attributes();
      // var_dump($attributes);
      // wc_get_product_terms( get_the_ID(), $key )
      foreach ( $attributes as $attribute ) {

          $attributeObject = array();

          $tempName = preg_replace( '/pa_/', '', $attribute['name'] );
          $tempName = preg_replace( '/-/', ' ', $tempName );
          $attributeObject['title'] = $tempName;
          $attributeObject['slug'] = $attribute['name'];

          $attributeObject['cleanTitle'] = preg_replace("/[^a-zA-Z]+/", "", $attributeObject['title']);

          $attributeObject['attributes'] = array();

          $product_attributes = array();
          // $product_attributes = explode(' | ',$attribute['value']);          
          if($attribute['is_taxonomy']){
             foreach ($attribute['options'] as $opt) {
               // array_push($product_attributes, wc_get_product_terms( $opt));
              $termObj = get_term_by('id', $opt, $attribute['name']);
              array_push($product_attributes, $termObj->name);
             }
             $attributeObject['slug'] = 'attribute_'.$attribute['name'];
          }
          else{
            $product_attributes = explode(' | ',$attribute['value']);
          }
          
          
          foreach ( $product_attributes as $pa ) {
              array_push($attributeObject['attributes'], $pa);
          }
          
          // var_dump($attributeObject);
          array_push($postInfo['attributes'], $attributeObject);
      }

     
      
    }

    /////////////////////////////////////////////////////images
    $postInfo['gallery'] = array((int)$postInfo['_thumbnail_id']);

    $tempGall = $product->get_gallery_image_ids();
    foreach ($tempGall as $key => $value) {
      $pos = array_search( $value , $postInfo['gallery'] );
      if($pos === false){
        array_push($postInfo['gallery'] , $value);
      }
    }
    
    if($postInfo['variants']){
      $gallCount = count($postInfo['variants']);
      for ($i=0; $i < $gallCount; $i++) { 
         if($postInfo['variants'][$i]['thumb_id'] > 0){
           $pos = array_search( $postInfo['variants'][$i]['thumb_id'] , $postInfo['gallery'] );
           if($pos === false){
             array_push($postInfo['gallery'] , $postInfo['variants'][$i]['thumb_id']);
             $postInfo['variants'][$i]['galleryPos'] = count($postInfo['gallery']);
           }
           else{
             $postInfo['variants'][$i]['galleryPos'] = $pos+1;
           }
         }
      }
    }



    //////////////////////////////////////////////max price
    $maxPrice = 0;
    foreach ($postInfo['variants'] as $key => $value) {
      if($value['price'] > $maxPrice){
        $maxPrice = $value['price'];
      }
    }
    $postInfo['maxPrice'] = $maxPrice;
  }
  
  return $postInfo;
}   




//////////////////////////////////////////////////////////helpers

//regresa el rol actual del usuario
function mnrGetCurrentRole(){
  if ( is_user_logged_in()) {
    global $current_user;
    wp_get_current_user();
    $user_info = get_userdata($current_user->ID);
    $role = implode(',', $current_user->roles);
    $role .= '';
  }
  else{
    $role = 'visitor';
  }
  return $role;
}
function mnrRemoveAccents($str){
  $unwanted_array = array(    
     'Š'=>'S',
     'š'=>'s',
     'Ž'=>'Z',
     'ž'=>'z',
     'À'=>'A',
     'Á'=>'A',
     'Â'=>'A',
     'Ã'=>'A',
     'Ä'=>'A',
     'Å'=>'A',
     'Æ'=>'A',
     'Ç'=>'C',
     'È'=>'E',
     'É'=>'E',
     'Ê'=>'E',
     'Ë'=>'E',
     'Ì'=>'I',
     'Í'=>'I',
     'Î'=>'I',
     'Ï'=>'I',
     'Ñ'=>'N',
     'Ò'=>'O',
     'Ó'=>'O',
     'Ô'=>'O',
     'Õ'=>'O',
     'Ö'=>'O',
     'Ø'=>'O',
     'Ù'=>'U',
     'Ú'=>'U',
     'Û'=>'U',
     'Ü'=>'U',
     'Ý'=>'Y',
     'Þ'=>'B',
     'ß'=>'Ss',
     'à'=>'a',
     'á'=>'a',
     'â'=>'a',
     'ã'=>'a',
     'ä'=>'a',
     'å'=>'a',
     'æ'=>'a',
     'ç'=>'c',
     'è'=>'e',
     'é'=>'e',
     'ê'=>'e',
     'ë'=>'e',
     'ì'=>'i',
     'í'=>'i',
     'î'=>'i',
     'ï'=>'i',
     'ð'=>'o',
     'ñ'=>'n',
     'ò'=>'o',
     'ó'=>'o',
     'ô'=>'o',
     'õ'=>'o',
     'ö'=>'o',
     'ø'=>'o',
     'ù'=>'u',
     'ú'=>'u',
     'û'=>'u',
     'ý'=>'y',
     'þ'=>'b',
     'ÿ'=>'y' 
  );
  return strtr( $str, $unwanted_array );
}
function mnrRemoveStopWords($searchString = "", $stopwords){

    $words = preg_split('/[^-\w\']+/', mnrRemoveAccents($searchString), -1, PREG_SPLIT_NO_EMPTY);

    if (count($words) > 1) {
      $words = array_filter($words, function ($v) use (&$stopwords) {
        return !isset($stopwords[strtolower($v)]);
      }
      );
    }

    if (empty($words)) {
      return $searchString;
    }

    return implode(" ", $words);
}
//revisa si la palabra es similar a otra en base a un porcentaje
function mnrFindStrMatch($hay, $needle,$pers = 80){
   $needle = preg_split('/[^-\w\']+/', strtolower(mnrRemoveAccents($needle)), -1, PREG_SPLIT_NO_EMPTY);
   $hay = preg_split('/[^-\w\']+/', strtolower(mnrRemoveAccents($hay)), -1, PREG_SPLIT_NO_EMPTY);
   // var_dump(strtr( $hay, $needle ));
   foreach ($needle as $keyi => $valuei) {
     // if(strpos( $hay, $value ) !== false){
     //   return true;
     // }
     foreach ($hay as $keyj => $valuej) {
        similar_text($valuei, $valuej, $percent);
        if($percent >= $pers){
           return true;
        }
     }
   }
   return false;
}
function mnrParseBool($val){
      switch($val){
       case "si":
         return true;
       break;
       case "yes":
         return true;
       break;
       case "true":
         return true;
       break;
       case "1":
         return true;
       break;
       case 1:
         return true;
       break;
       case true:
         return true;
       break;
       case 'no':
         return false;
       break;
       case 'false':
         return false;
       break;
       case '0':
         return false;
       break;
       case 0:
         return false;
       break;
      }
      return false;   
}
// funcion corta para get_template_part
function mnrPart($template, $args = null){
  
  if(strpos( $template , 'snip') !== false ){
    $template = 'components/'.$template;
  }
  else if(strpos( $template , 'section') !== false ){
    $template = 'components/'.$template;
  }


  // $template = 'components/'.$template;

  // var_dump($template);
  
  get_template_part($template, null, $args); 
}


function mnrFeatureImg($src, $local = true){
  if ( has_post_thumbnail() ) { 
    $thumb_id = get_post_thumbnail_id();
    $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full', true);
    $src = $thumb_url_array[0];
  }
  else if($local == true){
    $src = mnrGetRoot($src);
  }

  return $src;
}


function mnrEcho($args,$par){
  if(isset($args[$par])){
    echo $args[$par];
  }
}


function mnrLangSelect($esp,$eng){
  if(mnrGetLang() == 'es'){
     if($esp == ''){
       echo $eng;
     }
     else{
       echo $esp;
     }
  }
  else{
     if($eng == ''){
       echo $esp;
     }
     else{
       echo $eng;
     }
  }
}
function mnrGetLang(){
  $path = explode('/',$_SERVER['REQUEST_URI']);
  $lang = 'en';
  for($i = 0; $i <= count($path); $i++){
    $temp = $path[$i];
    switch($temp){
      case 'es':
        $lang = 'es';
      break;
      case 'en':
        $lang = 'en';
      break;
    }
  }

  return $lang;
}






?>