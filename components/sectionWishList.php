

<section >
  <div class="content">
    <h2 class="txtC pB20">Wishlist</h2>
  </div>
</section>

<?php mnrPart('sectionFilterBar',array(
  'batch'=>'wishMovies',
  'wish'=>true,
)); ?>

<section>
  <div class="content pB60">
    
    <div class="cardsHolder flxR" id="movies">
      
      

    </div>

  </div>
  
</section>



<script>
  Mnr.onLoad(null,()=>{
     getInitialData(()=>{
      renderGenOptions();
      renderYearOptions(wishMovies);
      if(Mnr.b.wishlist != ''){
        getWishMovies('#movies');
      }
      else{
      	renderNoMovies('#movies');
      }
     }); 

     // Mnr.e('#loadMoreMovies').setViewTrigger(()=>{
     //    getMoreMovies('#movies','#loadMoreArea','#loadMoreMovies');
     // });
  });


</script>