
<section class="singleActor">
  <div class="content pB20 flxR ordC itmS" id="actor">
    
  </div>
  <div class="content pB60 flxR ordC itmS">
     <h2 class="pT40 txtC">Known For:</h3>
     <div class="cardsHolder flxR" id="movies">
      
     </div>
  </div>
  
</section>

<script>
Mnr.onLoad(null,()=>{
   renderLoader('#movies');
   getActor('#actor',Mnr.e('#actorId').e[0].value);
   getInitialData(()=>{
     getRelatedMovies('#movies',Mnr.e('#actorId').e[0].value);
   });

   
});
  

// movie
var actor = {
  data: null
};
var relatedMovies = {
  movies : [],
}
function getRelatedMovies(target,id){
  let url = apiUrl+'3/person/'+id+'/movie_credits?api_key='+apiKey;
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setRelatedMovies(response);
     renderActor(target);
     renderKnownMovies(target);
  })
  .catch(error =>{
     renderActor(target);
     console.warn(error);
  })
}
function setRelatedMovies(batch){

  batch.results = batch.cast;
  batch.results.map(m=>{
    m.extraText = `<p class="txtS5 pT10 pB10 txtC">Character: <strong>${m.character}</strong></p>`;
  });

  saveMoviesBatch(relatedMovies,batch);
}
function renderKnownMovies(target){
  console.log(relatedMovies);
  relatedMovies.movies.sort(function(a,b){
     if(b.release_date == '' || b.release_date == null)return -1;
     return new Date(b.release_date) - new Date(a.release_date);
    
  });
  renderMovies(relatedMovies.movies.length,target,relatedMovies.movies);
}

function getActor(target,id){
  let url = apiUrl+'3/person/'+id+'?api_key='+apiKey+'&append_to_response=images,website';
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setActor(response);
     renderActor(target);
  })
  .catch(error =>{
     renderActor(target);
     console.warn(error);
  })
}
function setActor(batch){

  actor.data = batch;
}
function renderActor(target){
  
  

  let poster = '';
  if(actor.data.profile_path != ''){
    poster = `<img class="poster wFull" 
                   src="${imgUrl}${actor.data.profile_path}" 
                   alt="${actor.data.name}">`;
  }
  else{
    poster = `<div class="poster colorBWhite noPoster wFull"></div>`;
  }


  let gallery = '';
  if(actor.data.images.profiles?.length > 0){
    for (var i = actor.data.images.profiles.length - 1; i >= 0; i--) {
      let img = actor.data.images.profiles[i];
      gallery += `<div class="imgHolder cursor " onclick="openModalMessage(Mnr.e(this).e[0].innerHTML)">
                    <img src="${imgUrl}${img.file_path}" class="anim3 wFull">
                  </div>`;
      
    }
  }

  

  let html = `
     <div class="col1">
       ${poster}
     </div>
     <div class="col2">

       <h1 class="pB10 txtS2"><strong>${actor.data.name}</strong></h1>
       
       <p class="txtS3 pB20">Department: <strong>${actor.data.known_for_department}</strong></p>

       <p class="pB10">Birthday: <strong>${actor.data.birthday}</strong> in <strong>${actor.data.place_of_birth}</strong></p>
       <p class="pB10">${(actor.data.deathday == null)? '': '<strong>Date of death: '+actor.data.deathday+'</strong>'}</p>
       
       <div class="grades">

          <div class="gradeHolder flxR ordS itmC mB10 flxNoWrap">
            <img src="${Mnr.b.root}/assets/corazonFill.svg" svg 
                 class="icon s25">
            <p class="grade mL10"><strong>${actor.data.popularity}</strong></p>
          </div>
       </div>

       <div class="overview pT20 pB20">
         <h3 class=" txtS3">Biography:</h3>
         <p >${actor.data.biography}</p>
       </div>

       <div class="pT20 pB20">
         <h3 class=" txtS3">Gallery:</h3>
         <div class="flxR ordS itmC gallery">
           ${gallery}
         </div>
       </div>
       
     </div>
     

     
     
  `;
  
  Mnr.e(target).html(html);
  // SVGInject(document.querySelectorAll("[svg]"));
}



</script>

<style>
  .singleActor .col1,
  .singleActor .col2{
    margin-bottom: 40px;
  }
  .singleActor .col1{
    width: 35%;
  }
  .singleActor .col2{
    margin-left: 80px;
    width: calc(65% - 80px);
  }
  .singleActor .gallery{
    max-width: 500px;
  }
  .singleActor .gallery .imgHolder{
    width: calc(20% - 10px);
    margin-right: 10px;
    /*margin-left: 20px;*/
    margin-top: 20px;
  }
  .singleActor .gallery .imgHolder:hover img{
    transform: scale(1.05);
  }
  .singleActor .gallery img{
    width: 70%;
    height: auto;
  }
  @media only screen and (max-width: 800px){
     .singleActor .col1{
       width: 30%;
     }
     .singleActor .col2{
       margin-left: 40px;
       width: calc(70% - 40px);
     }
  }
  @media only screen and (max-width: 600px){
     .singleActor .col1,
      .singleActor .col2{
       margin-left: 0px;
       margin-right: 0px;
       width: 100%;
     }
  }
  
</style>