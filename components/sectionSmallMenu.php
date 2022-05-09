<!-- containerMenu -->


<div id="smallMenuShadow" class="fixed posT opacity7 wFullvw hFullvh z15 colorBBlack hide"
     onClick="Mnr.e('#smallMenu').class('open',false); Mnr.e('#smallMenuShadow').class('show',false)"></div>
<section id="smallMenu" class=" fixed z20 posT colorBBlack anim5 hFullvh" >
  
    

    <div class="scroll hFull wFull pT20 pB40">
     
     <div class="flxCol itemsE hAuto wFull pR pL20">
        
        <div class="wFull flxR ordE">
           
           <div class="flxCol ordC closeBtn size30 z2 cursor iconHover" 
                onClick="Mnr.e('#smallMenu').class('open',false); Mnr.e('#smallMenuShadow').class('show',false)" >
                <img svg src="<?=MnrRoot()?>/assets/close.svg" class="svg s40 colorSvgWhite anim5">
           </div>

        </div>


        <div class="wFull lineH mT20 mB20"></div>

        

        <?php
          $menu = mnrGetMenu('mobile');
          foreach ($menu as $key => $element) {
            ?>
              <a <?php echo $element['options'][0][1]; ?> >
                <p class="anim5 txtR txtUpper colorWhite txtS2 mT20 option">
                 <?php echo $element['options'][0][0]; ?>
                </p>
              </a>
            <?php
          }

        ?>
        
        
        
       
        

        <div class="wFull lineH mT20 mB5"></div>

        
          <a href="/account" >
            <p class="anim5 txtR txtUpper colorWhite txtS3 mT20 option">
             <?php if(is_user_logged_in() ) {
              echo 'wishlist'; 
              }else{
                echo 'log in';
              } ?>
            </p>
          </a>

          <?php
            if (get_option('users_can_register') && !is_user_logged_in()) {
          ?>
               <a href="/account?action=register" >
                 <p class="anim5 txtR txtUpper colorWhite txtS3 mT20 option">register</p>
               </a>
          <?php
            }
          ?>

          <?php
          if (  is_user_logged_in() ) {
            ?>
              <a href="<?php echo wp_logout_url('/'); ?>" >
                <p class="anim5 txtR txtUpper colorWhite txtS4 mT20 option">
                  Log out
                </p>
              </a>
            <?php
          } 
        ?>
       
        
     </div>

    </div>


</section>
