




<section class="singleMovie">
  <div class="content pB20 flxR ordC itmS" id="movie">
    
  </div>
  <div class="content pB60 flxR ordC itmS">
     <h2 class="pT40 txtC">Similar Movies:</h3>
     <div class="cardsHolder flxR" id="movies">
      
     </div>
  </div>
  
</section>

<script>
Mnr.onLoad(null,()=>{
   getInitialData(()=>{
     getMovie('#movie',Mnr.e('#movieId').e[0].value);
   });

});
  

// movie
var movie = {
  data: null
};
function getMovie(target,id){
  let url = apiUrl+'3/movie/'+id+'?api_key='+apiKey+'&append_to_response=reviews,videos,credits,similar_movies,alternative_titles';
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setMovie(response);
     renderMovie(target);
     renderSimilarMovies('#movies');
  })
  .catch(error =>{
     renderMovie(target);
     console.warn(error);
  })
}
function setMovie(batch){
  console.log(batch);
  movie.data = batch;
}
function renderMovie(target){
  
  let video = ``;
  if(movie.data?.videos?.results?.length > 0){
    for(let vid of movie.data.videos.results){
      if(vid.site == "YouTube"){  
        video = `<iframe class="wFull hFull abs posT posL" src="https://www.youtube.com/embed/${vid.key}" 
              title="${vid.name}" frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope;" 
              allowfullscreen></iframe>`;
        break;
      }
      
    }
  }

  let poster = '';
  if(movie.data.poster_path != ''){
    poster = `<img class="poster wFull" 
                   src="${imgUrl}${movie.data.poster_path}" 
                   alt="${movie.data.title}">`;
  }
  else{
    poster = `<div class="poster colorBWhite noPoster wFull"></div>`;
  }

  let companies = '';
  if(movie.data.production_companies.length > 0){
    for (var i = 0; i < movie.data.production_companies.length; i++) {
      let com = movie.data.production_companies[i];
      companies += `<div class="comLogo">`
      if(com.logo_path != null){
         companies += `<img src="${imgUrl}${com.logo_path}" alt="${com.name}" />`;
      }
      else{
         companies += `<p class="txtS5 txtC">${com.name}</p>`
      }
      companies += `</div>`;
      if(i >= 5) break;
    }
  }

  let otherTitles = '';
  if(movie.data.alternative_titles?.titles?.length > 0){
    otherTitles = ``
    let many = false;
    for (let i = 0; i < movie.data.alternative_titles.titles.length; i++) {
      let title = movie.data.alternative_titles.titles[i];
      if(i < 4){
        otherTitles += `<li class="pB5 txtS5">${title.title} <span class="txtS6">${title.type}</span></li>`;
      }
      else{
        otherTitles += `<li class="extra pB5 txtS5">${title.title} <span class="txtS6">${title.type}</span></li>`;
        many = true;
      }
       
    }
    if(many){
      otherTitles += `<button class="hide readMore button button2 " onclick="openModalMessage(Mnr.e(this.parentNode).html())">View All Titles</button>`;
    }
    
  }
  

  function renderCrew(crew){
    let tempCredits = '';
    if(crew){
      
      let img = '';
      if(crew.profile_path != ''){
         img = `<img alt="director" class="imgCover round s50" src="${imgUrl}${crew.profile_path}">`;
      }
      else{
         img=`<div class="round s50 colorBBlack"><p class="txtS6 colorWhite">no image</p></div>`
      }
      
      let as = '';
      if(crew?.character){
        as = `<br> as <strong>${crew.character}</strong>`;
      }
      tempCredits += `<div class="crewHolder flxR ordS flxNoWrap itmC mB10">
                       ${img}
                       <p class="mL10 txtS5">${crew.name}${as}</p>
                       
                   </div><div class="lineH"></div>`;
    }
    return tempCredits;
  }
  let credits = '';
  if(movie.data.credits?.crew?.length > 0){
    let crew = movie.data.credits.crew.find(c=>c.job == "Director");
    credits += `<h3 class="txtS3 mB20">Director:</h3>
                ${renderCrew(crew)}`;
  }
  if(movie.data.credits?.cast?.length > 0){
    movie.data.credits.cast.sort(function(a,b){
     return b.popularity - a.popularity;
    });
    credits += `<h3 class="txtS3 mB20 mT10">Cast:</h3>`;
    for (let i = 0; i < movie.data.credits.cast.length; i++) {
      let crew = movie.data.credits.cast[i];
      credits += `<a href="/actor?id=${crew.id}" >${renderCrew(crew)}</a>`;
      if(i >= 7) break;
    }
  }


  let reviews = '';
  if(movie.data?.reviews.results.length > 0){
    for(let rev of movie.data.reviews.results){
       let rate = '';
       if(rev.author_details?.rating){
        rate = `<div class="gradeHolder flxR ordS itmC mT10 mB10 flxNoWrap">
               <img src="${Mnr.b.root}/assets/estrella.svg" svg class="icon s25">
               <p class="grade mL10"><strong>${rev.author_details?.rating}</strong></p>
             </div>`;
       }
       reviews += `  
          <div class="review card mB40">
             <p class="author"><strong>${rev.author_details.username}</strong></p>
             ${rate}
             <p class="hide cutText">${Mnr.u.cutText(rev.content, 200, true)}</p>
             <p class="allText">${rev.content}</p>
             <button class="hide readMore button button2 mT20" onclick="openModalMessage(Mnr.e(this.parentNode).html())">Read All</button>
          </div>
          
       `;
    }
  }

  let html = `
     <div class="col1">
       ${poster}
       
     </div>
     <div class="col2" wished="${(Mnr.b.wishlist.split(' ').includes(movie.data.id.toString()))}">
       <div class="wishBtn flxR ordS itmC ">
        <p class="txtS5 mR10">Wishlist:</p>
        <div class="s20 rltv z2 wish cursor" onclick="saveWish(${movie.data.id},this.parentNode.parentNode)">
          <img svg src="${Mnr.b.root+'/assets/corazon.svg'}" class="toWish colorSvgError abs posC anim2 s20">
          <img svg src="${Mnr.b.root+'/assets/corazonFill.svg'}" class="wished colorSvgError abs posC anim2 s20">
        </div>
       </div>

       <h1 class="pB10 txtS2"><strong>${movie.data.title}</strong></h1>

       

       <p class="date txtS3">${movie.data.release_date}<p>
       <p class="genres txtS3 pB20">${movie.data.genres.map(g=>{return g.name})}</p>
       
       

       <p class="lang pB20">Original Language:<strong> ${movie.data.original_language}</strong></p>
       
       <div class="grades">

          <div class="gradeHolder flxR ordS itmC mB10 flxNoWrap">
            <img src="${Mnr.b.root}/assets/corazonFill.svg" svg 
                 class="icon s25">
            <p class="grade mL10"><strong>${movie.data.popularity}</strong></p>
          </div>
          <div class="gradeHolder flxR ordS itmC mB10 flxNoWrap">
            <img src="${Mnr.b.root}/assets/estrella.svg" svg 
                 class="icon s25">
            <p class="grade mL10">
               <strong>${movie.data.vote_average}</strong> <span class="txtS5">count(
               ${movie.data.vote_count})</span></p>
          </div>
       </div>



       
      
       <div class="otherTitles pT20 pB20">
         <h3 class="pB5 txtS3">Alternative Titles:</h3>
         ${otherTitles}
       </div>
       
       <div class="companies flxR ordS itmC">
         <h3 class="wFull txtS3">Companies:</h3>
         ${companies}
       </div>
       
     </div>
     

     <div class="col3">
       <div class="overview pT20 pB20">
         <h3 class=" txtS3">Overview:</h3>
         <p class="pB10">${movie.data.tagline}</p>
         <p >${movie.data.overview}</p>
       </div>

       <div class="videoHolder asp rltv wFull mT20 mB20 ${(video != '')? '':'hide'}">${video}</div>

       
     </div>

     <div class="col4">
       <div class="crew pT20 pB20">
         ${credits}
       </div>

       
     </div>

     <div class="col5 ">
        <h3 class="wFull txtS3">Reviews:</h3>
        <div class="reviews cardsHolder flxR pT20 pB40">
         
         ${reviews}
       </div>
     </div>

     
     
  `;
  
  Mnr.e(target).html(html);
  // SVGInject(document.querySelectorAll("[svg]"));
}

function renderSimilarMovies(target){
  if(movie.data?.similar_movies){
    setMoviesBatch(movie.data?.similar_movies);
    renderMovies(10,target,allMovies.movies);
  }
}

</script>

<style>
  .singleMovie .col1,
  .singleMovie .col2,
  .singleMovie .col3{
    margin-bottom: 40px;
  }
  .singleMovie .col1{
    width: 35%;
  }
  .singleMovie .col2{
    margin-left: 80px;
    width: calc(65% - 80px);
  }
  .singleMovie .col3{
    width: calc(65% - 80px);
    margin-right: 80px;
  }
  .singleMovie .col4{
    width: 35%;
  }
  .singleMovie .col5{
    width: 100%;
  }
  .singleMovie .companies{
    max-width: 400px;
  }
  .singleMovie .comLogo{
    width: calc(33.33% - 20px);
    margin-right: 10px;
    margin-left: 10px;
    margin-top: 20px;
  }
  .singleMovie .comLogo img{
    width: 70%;
    height: auto;
  }
  .singleMovie .crew .lineH{
   border-top: 1px solid var(--mnr-colorBlack);
   opacity: 0.5;
   height: 1.5px;
   width: 100%;
   margin-top: 5px;
   margin-bottom: 10px;
  }
  .singleMovie .review .cutText{
    display: block!important;
  }
  .singleMovie .review .readMore,
  .singleMovie .otherTitles .readMore{
    display: flex!important;
  }
  .singleMovie .review .allText,
  .singleMovie .otherTitles .extra{
    display: none;
  }

  

  @media only screen and (max-width: 800px){
     .singleMovie .col1{
      width: 30%;
     }
     .singleMovie .col2{
       margin-left: 40px;
       width: calc(70% - 40px);
     }
     .singleMovie .col3{
       width: calc(65% - 80px);
       margin-right: 80px;
     }
     .singleMovie .col4{
       width: 35%;
     }
  }
  @media only screen and (max-width: 600px){
     .singleMovie .col1,
     .singleMovie .col2,
     .singleMovie .col3,
     .singleMovie .col4{
       margin-left: 0px;
       margin-right: 0px;
       width: 100%;
     }
  }
</style>