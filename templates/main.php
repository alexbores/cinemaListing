<?php
  if ( ! defined( 'ABSPATH' ) ) {
      exit; // Exit if accessed directly
  }

  /* 
  Template Name: landing
  */

  get_header();
  
?>


<section >
  <div class="content pB60">
    <h2 class="txtC pB20">Upcoming Movies</h2>
    <div class="cardsHolder flxR" id="upcoming">
      
    </div>

    <a href="/movies" class="button mLAuto mRAuto mT40">View all movies</a>
  </div>
  <div class="content pB60">
    <h2 class="txtC pB20">Top 10 popular actors</h2>
    <div class="cardsHolder flxR" id="topActors">
      
    </div>
    <a href="actors" class="button mLAuto mRAuto mT40">View all actors</a>
  </div>
</section>


<?php 
  get_footer();
?>

<script>
  Mnr.onLoad(null,()=>{
    getInitialData(()=>{
     getUpcomingMovies(10,'#upcoming');
   });

   getTopActors(10,'#topActors');

  });
</script>



