<!-- containerMenu -->


<section id="menu" class="abs z15 posT posL anim5 fixed colorBWhite" >
  
  <div class="content fullContent flxR ordSB flxNoWrap hFull  anim5">
     
     <div class="col1 col flxR ordS itmC flxNoWrap">
       

       <div class="logoHolder rltv hFull flxC ordC anim5" >
         <a aria-label="inicio" href="/<?php echo $lang; ?>" class="absS marAuto z2"></a>
         <h1 class="anim3">Cinema Listing</h1>
       </div>
     </div>

     <div class="col2 col flxR itmC">

       <!-- <div class="bar searchHolder cursor flxC ordC mR30 iconHover"
         onClick="Mnr.e('#searchMenu').class('open'); Mnr.e('#searchMenuShadow').class('show')">
         <img svg src="<?=MnrRoot();?>/assets/lupa.svg" class="anim3 svg s40" />
       </div> -->

       <div class="bar hamHolder cursor flxR ordC iconHover" 
         onClick="Mnr.e('#smallMenu').class('open'); Mnr.e('#smallMenuShadow').class('show')">
         <img svg src="<?=MnrRoot();?>/assets/MENU.svg" class="anim3 svg s40" />
       </div>
     </div>

     

  </div>
</section>

<?php
  mnrPart('sectionSmallMenu');
  mnrPart('sectionSearchMenu');
?>


<style>
  /*menu*/
  #smallMenu,
  #menu{
    opacity: 0;
  }
  [mnr-page-loading="false"] #smallMenu,
  [mnr-page-loading="false"] #menu{
    opacity: 1;
  }

  #menu{
    overflow: visible;
    /*position: absolute;*/
    top: 0;
    left: 0;
    z-index: 5;

  }
  #menu .content{
    border-bottom: solid 2px black;
  }
  #menu .col{
    height: 80px;
  }
  
  .scrolled #menu .col{
    height: 60px;
  }
  .scrolled #menu .col h1{
    font-size: var(--mnr-fontS2);
  }
 
  #smallMenu{
    right: -200vw;
    width: 90vw;
    max-width: 500px!important;
    min-width: 300px!important;
  }
  #smallMenu.open{
    right: 0px;
  }
  
  #smallMenu .lineH{
   border-top: 1px solid white;
   opacity: 0.5;
   height: 1.5px;
  }



  @media only screen and (max-width: 600px){
     #menu .logoHolder h1{
       font-size: 25px;
     }
     .scrolled #menu .logoHolder h1{
      font-size: 20px;
     }
     #menu .col2 .bar .svg{
       width: 30px;
       height: 30px;
     }
  }



</style>