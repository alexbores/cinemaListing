<!-- containerMenu -->

<div id="modalMessageShadow" class="fixed posT opacity7 wFullvw hFullvh z15 colorBBlack hide"
     onClick="Mnr.e('#modalMessage').class('open',false); Mnr.e('#modalMessageShadow').class('show',false)',action='remove');"></div>

<section id="modalMessage" class="fixed extraScreen z20 posT  anim5 pT40 pB60 flxC ordC " >
    
    
    <div class="content scroll scrollBlack hFull innerContent colorBWhite">
      <div class="hAuto wFull flxC ordC  pT40 pB40 relative">

        <div class="mT20 mR20 closeBtn s30 z2 cursor abs posR posT iconHover" 
           onClick="Mnr.e('#modalMessage').class('open',false); Mnr.e('#modalMessageShadow').class('show',false)" >
           <img src="<?=MnrRoot();?>/assets/close.svg" svg class="svg s30 anim5">
        </div>

        
        
        <div class="mT40 wFull messageHolder" >  
          
 
        </div>


      </div>
    </div>

</section>

<style>
    .scroll::-webkit-scrollbar {
        width: 5px;
    }
</style>
