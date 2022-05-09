<section id="filterBar" class="pB20 pT20">
    <div class="content flxR ordC itmE flxNoWrap">
      <div class="inputHolder pR10 wFull">
        <label for="titleInput" class="txtS5">Title:</label>
        <select name="title" id="titleInput">
           <option selected value="asc">Alphabetical</option>
           <option value="desc">Reverse Alphabetical</option>
        </select>
      </div>
      <div class="inputHolder pR10 wFull">
        <label for="yearInput" class="txtS5">Year:</label>
        <select name="year" id="yearInput">
          
        </select>
      </div>
      <div class="inputHolder pR10 wFull">
        <label for="genresInput" class="txtS5">Genres:</label>
        <select name="genres" id="genresInput">
          
        </select>
      </div>

      <input hidden name="wished" value="<?php if(isset($args['wish'])){echo $args['wish'];}else{echo -1;} ?>">
      
      <button onclick="filterMovies(<?php echo $args['batch']; ?>,'#movies'); 
                       renderMovies(<?php echo $args['batch']; ?>.movies.length,'#movies',<?php echo $args['batch']; ?>.movies)" 
              class="mL20 button">Filter</button>
    </div>
</section>

