<form autocomplete="off" class="innerContent anim3 searchBar wFull rltv flxR flxNoWrap" role="search" method="GET" 
      action="/">
  <input 
     mnr-bind="searchWord"
     placeholder="Find" 
     class="searchInput z2 wFull pR40 pL5 txtS3  mR20" 
     name="s" >
  <div class="">
    <button type="submit" class="z3 cursor submit buttonRound button iconHover">
      <img src="<?=mnrRoot();?>/assets/lupa.svg" svg class="svg colorSvgWhite s25 anim5">
    </button>        
  </div>
  <p mnr-class='{"displayInBlock":"searchWord.length >= 3", "hide":"searchWord.length < 3" }'
     class="btnBorrar cursor pT15 txtS5 colorOk wAuto "
     onClick="Mnr.b.searchWord = '';">borrar</p>   
</form>
