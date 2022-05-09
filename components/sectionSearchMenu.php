<!-- containerMenu -->

<div id="searchMenuShadow" class="fixed posT opacity7 wFullvw hFullvh z15 colorBBlack hide"
     onClick="Mnr.e('#searchMenu').class('open',false); Mnr.e('#searchMenuShadow').class('show',false)',action='remove');"></div>

<section id="searchMenu" class="fixed extraScreen z20 posT  anim5 pT40 pB60 flxC ordC " >
    
    
    <div class="content scroll hFull innerContent colorBWhite">
      <div class="hAuto wFull flxC ordC  pT40 pB40 relative">

        <div class="mT20 mR20 closeBtn s30 z2 cursor abs posR posT iconHover" 
           onClick="Mnr.e('#searchMenu').class('open',false); Mnr.e('#searchMenuShadow').class('show',false)" >
           <img src="<?=MnrRoot();?>/assets/close.svg" svg class="svg s30 anim5">
        </div>

        <h3 class="mT20 txtS1 txtC">Search:</h3>
        
        <div class="mT40 wFull">  
        <?php
           mnrPart( 'snipSearchBar');
        ?>
 
        </div>

        <div class=" mT60 wFull flxR ordC flxNoWrap"> 
          <a class="button m10" href="/movies">movies</a>
          <a class="button m10" href="/actors">actors</a>
        </div>

      </div>
    </div>

</section>

<style>
    
</style>
