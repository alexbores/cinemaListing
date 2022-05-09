<?php 
  $page = $_GET['source'];
  if(!isset($page)){
    $page = 1;
  }
?>

<input hidden id="currentPage" value="<?php echo $page; ?>">

<section >
  <div class="content">
    <h2 class="txtC pB10">All Movies</h2>
    <div class="wFull flxR ordC flxNoWrap mB20">
      <?php if($page -1 > 0){ ?>
        <a  class="button m20 buttonRound" href="movies?source=<?php echo $page-1; ?>">
          <img svg src="<?= mnrRoot(); ?>/assets/flecha-izquierda.svg" class="colorSvgWhite s20">
        </a>
      <?php } ?>
      <h3 class="txtC">Page: <?php echo $page; ?></h3>
      <a  class="button m20 buttonRound" href="/movies?source=<?php echo $page+1; ?>">
        <img svg src="<?= mnrRoot(); ?>/assets/flecha-derecha.svg" class="colorSvgWhite s20">
      </a>
    </div>
  </div>
</section>

<?php mnrPart('sectionFilterBar',array('batch'=>'allMovies')); ?>

<section >
  <div class="content pB60">
    
    <div class="cardsHolder flxR" id="movies">
      
    </div>
    
    <div class="wFull flxR ordC">
      <?php if($page -1 > 0){ ?>
        <a id="prev" class="button m20" href="/movies?source=<?php echo $page-1; ?>">prev page</a>
      <?php } ?>
      <a id="next" class="button m20" href="/movies?source=<?php echo $page+1; ?>">next page</a>
    </div>
    
  </div>
  
</section>


<script>
  Mnr.onLoad(null,()=>{
     getInitialData(()=>{
      renderLoader('#movies');
      renderGenOptions();
      renderYearOptions();
      getMoviesBatch('#movies');
      
     }); 

     // Mnr.e('#loadMoreMovies').setViewTrigger(()=>{
     //    getMoreMovies('#movies','#loadMoreArea','#loadMoreMovies');
     // });
  });


</script>
