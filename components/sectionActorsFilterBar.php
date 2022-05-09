<section id="filterBar" class="pB20 pT20">
    <div class="content flxR ordC itmE flxNoWrap">
      <div class="inputHolder pR10 wFull">
        <label for="nameInput" class="txtS5">Name:</label>
        <select name="name" id="titleInput">
           <option selected value="asc">Alphabetical</option>
           <option value="desc">Reverse Alphabetical</option>
        </select>
      </div>
      <div class="inputHolder pR10 wFull">
        <label for="moviesInput" class="txtS5">Movies:</label>
        <select name="movies" id="moviesInput">
          
        </select>
      </div>

      
      <button onclick="filterActors(allActors,'#actors'); 
                       renderActors(allActors.actors.length,'#actors',allActors.actors)" 
              class="mL20 button">Filter</button>


     
    
    </div>
</section>