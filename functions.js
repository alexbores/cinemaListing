Mnr.init({
  binds:{
    root: '',
    scrolled: false,
    wishlist: '',
    userId: '',
    searchWord:'',
  },
});

Mnr.onLoad(null,function(){
  this.e('#pageLoader')
      .wait(500)
      .class('fade')
      .wait(500)
      .class('load');

      SVGInject(document.querySelectorAll("[svg]"));

});
Mnr.onScroll(function(){
     if(window.pageYOffset > 50 && this.b.scrolled == false){
       this.b.scrolled = true;
       this.e('html').class('scrolled');
     }
     else if(window.pageYOffset <= 50){
       this.b.scrolled = false;
       this.e('html').class('scrolled',0);
     }
});

const apiKey = '199a8db04bfd593e813f3ad1120d2995';
const apiUrl = 'https://api.themoviedb.org/';
const imgUrl = 'https://image.tmdb.org/t/p/original/';
const steps = 10;



// //////////////////////////// movies
var allMovies = {
  currentPage: 0,
  maxPages: 1,
  movies:[],
  loading: false,
  pageSteps: steps
};
var upcomingMovies = {
  currentPage: 0,
  maxPages: 1,
  movies:[],
  loading: false,
}
var wishMovies = {
  currentPage: 0,
  maxPages: 1,
  movies:[],
  currentWish: 0,
  list: [],
  loading: false
};




// //////////////////////////all movies
function getMoreMovies(target, call){
  if(allMovies.loading && allMovies.pageSteps <= 0) return;
  allMovies.loading = true;
  allMovies.pageSteps -= (allMovies.pageSteps > 0)? 1 : 0;
  renderLoaderText((100-Math.floor((100/steps)*allMovies.pageSteps))+'%');

  let url = apiUrl+'3/discover/movie?api_key='+apiKey+'&sort_by=original_title.asc&page='+(allMovies.currentPage+1);
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setMoviesBatch(response);
     if(allMovies.pageSteps == 0){
       filterMovies(allMovies,target);  
       renderMovies(allMovies.movies.length,target,allMovies.movies);
       call.call();
     }
     else{
       getMoreMovies(target,call);
     }
  })
  .catch(error =>{
     renderMovies(allMovies.movies.length,target,allMovies.movies);
     call.call();
     console.warn(error);
  })
}
function setMoviesBatch(batch){
  if(batch.results){
    saveMoviesBatch(allMovies,batch);
  }
  else{
    allMovies.pageSteps = 0;
  }

  allMovies.loading = false;
}
function getMoviesBatch(target,call){

  let page = Mnr.e('#currentPage');
  if(page.e.length > 0){

     page = (parseInt(page.e[0].value)-1);
     if(page <= 0) page = 0;
     if(page > 0) page += steps+1; 
  }

  allMovies.currentPage = page;
  getMoreMovies('#movies',()=>{
     call
  });
}


// /////////////////////// upcoming movies
function getUpcomingMovies(max=10, target){
  if(allGenres.genres.length <= 0){
     return;
  }
  if(upcomingMovies.currentPage >= upcomingMovies.maxPages ){
    renderMovies(max,target,upcomingMovies.movies);
    return;
  }
  if(upcomingMovies.loading == false){
    renderLoader(target);
    upcomingMovies.loading = true;
  }

  let url = apiUrl+'3/movie/upcoming?api_key='+apiKey+'&page='+(upcomingMovies.currentPage+1)
  fetch(url)
  .then((response) => response.json())
  .then(response => {
     setUpcomingMoviesBatch(response);
     getUpcomingMovies(max,target);
  })
  .catch(error =>{
     console.warn(error);
  })
}
function setUpcomingMoviesBatch(batch){
  saveMoviesBatch(upcomingMovies,batch);
  

  upcomingMovies.movies.sort(function(a,b){
     return new Date(b.release_date) - new Date(a.release_date);
  });
}



// /////////////////////// wishlist movies
function getWishMovies(target){
  if(allGenres.genres.length <= 0){
     return;
  }
  if(wishMovies.loading == false){
    renderLoader(target);
    wishMovies.loading = true;
    wishMovies.list = Mnr.b.wishlist.split(' ').filter(id=>id != '');
  }
  if(wishMovies.currentWish >= wishMovies.list.length){
    renderMovies(wishMovies.movies.length,target,wishMovies.movies);
    renderYearOptions(wishMovies);
    return;
  }

  let wishId = wishMovies.list[wishMovies.currentWish];
  

  let url = apiUrl+'3/movie/'+wishId+'?api_key='+apiKey;
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setWishMoviesBatch(response);
     getWishMovies(target);
  })
  .catch(error =>{
     renderMovies(wishMovies.movies.length,target,wishMovies.movies);
     console.warn(error);
  })
}
function setWishMoviesBatch(batch){
  let results = {
    results:[],
    page: 0,
    total_pages: 1,
  };
  batch.genre_ids = batch.genres.map(g=>{return g.id});
  results.results.push(batch);
  wishMovies.currentWish++;

  saveMoviesBatch(wishMovies,results);
}



function saveMoviesBatch(oldBatch, batch){
  if(batch){
    oldBatch.movies = [...oldBatch.movies,...batch.results];
    oldBatch.currentPage = batch.page;
    oldBatch.maxPages = batch.total_pages;
  }


  oldBatch.movies.map(movie=>{
     movie.genres = [];
     for(let gen of movie.genre_ids){
      let genre = allGenres.genres.find(g=>{
        return (g.id == gen);
      });
      if(genre) movie.genres.push(genre);   
     }
     movie.wished = false;
  });


  updateWishlist();
}
function renderNoMovies(target){
  let html = `<div class="message">
             <h3 class="txtC pT40 pB40">No movies found <br/>please try other filter parameters or add more movies to wishlist</h3>
             <a href="/movies" class="mLAuto mRAuto button">All Movies</a>
            </div>`;

  Mnr.e(target).html(html);
}
function renderMovies(max,target,batch){
  

  let showEmpty = true;
  let html = '';
  for (let i = 0; i < max; i++) {
    let movie = batch[i];
    if(movie == undefined) break;

    if(movie.poster_path == null &&
       movie.original_title == '"..."' &&
       movie.overview == ''){
      continue;
    };

    if(movie?.show || movie?.show == true || movie.show == undefined){
     showEmpty = false;
    }


    let img = (movie.poster_path) ? 
        `<img class="anim2 img" alt="${movie.original_title}" src="${imgUrl+movie.poster_path}" />` : 
        `<div class="anim2 img noPoster rltv">
           <div class="absS flxC ordC ">
             <p class="txtC txtS5 txtUpper p20">${movie.original_title}</p>
           </div>
         </div>` 
    html += `
    <div class="card movieCard rltv" 
         genres="${movie?.genre_ids.map(g=>{
           return g;
         })}"
         movie-date="${movie?.release_date}"
         movie-id="${movie.id}"
         wished="${movie?.wished}"
         show="${movie?.show}">

      <a href="/movies?id=${movie.id}" 
         aria-label="more info about ${movie.original_title}"
         class="z1 absS"></a>
      ${img}
      <h3 class="txtC txtS4"><strong>${movie.original_title}</strong></h3>
      ${(movie?.extraText)? movie.extraText : ''}
      <p class="txtC date">${movie.release_date}</p>
      <p class="txtC genres">${movie?.genres?.map(g=>{
        return g?.name;
      })}</p>
      
      <div class="wishBtn flxC ordC p10">
        <div class="s20 rltv z2 wish cursor" onclick="saveWish(${movie.id})">
          <img svg src="${Mnr.b.root+'/assets/corazon.svg'}" class="toWish colorSvgError abs posC anim2 s20">
          <img svg src="${Mnr.b.root+'/assets/corazonFill.svg'}" class="wished colorSvgError abs posC anim2 s20">
        </div>
      </div>
      
    </div>`;
  }

  if(showEmpty == true){
    html = `<div class="message">
             <h3 class="txtC pT40 pB40">No movies found <br/>please try other filter parameters or other page</h3>
            </div>`;
  }

  Mnr.e(target).html(html);
  SVGInject(document.querySelectorAll("[svg]"));
}


// wishlist
function updateWishlist(strList){
  if(strList != null){
    Mnr.b.wishlist = strList;
  }

  addWishAttr(allMovies);
  addWishAttr(wishMovies);
  addWishAttr(upcomingMovies);
}
function addWishAttr(batch){
  batch?.movies?.map(movie=>{
    movie.wished = (Mnr.b.wishlist.split(' ').includes(movie.id.toString()));
  });
}
function saveWish(id,target = null){
   if(Mnr.u.parseBool(Mnr.b.userId) == false){
     openModalMessage(`<p class="txtC txtS2 pT40 pB40">You must be log in to save movies</p>
      <a href="/account" class="button mLAuto mRAuto">Log In</a>`);
     return;
   }
   
   if(target == null){
     target = Mnr.e(`.movieCard[movie-id="${id}"]`);
   }
   else{
     target = Mnr.e(target);
   }

   target.attr('wished',!Mnr.u.parseBool(target.attr('wished')));

   var data = new FormData();
   
   data.append('movieId', id);
   
   var url = Mnr.b.root+"/helpers/setWish.php";
   var response = fetch(url, {
     method: 'POST',
     body: data,
   })
   .then((response) => response.json())
   .then(response => {
     console.log(response);
     if(response.status === 'ok') {
       updateWishlist(response.wishlist);
       return response.json;
     }
     return Promise.reject(response);
   })
   .catch((error) =>{
     console.error('Something went wrong.', error);
   });
}


// ////////////////////genres
var allGenres = {
  genres : [],
}
function getGenres(){

  let url = apiUrl+'3/genre/movie/list?api_key='+apiKey
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setGenresBatch(response);
  })
  .catch(error =>{
     console.warn(error);
  })
}
function setGenresBatch(batch){

  allGenres.genres = [...allGenres.genres,...batch.genres];
}



// ////////////actors
var topActors = {
  currentPage: 0,
  maxPages: 1,
  actors:[],
  loading: false,
}
var allActors = {
  currentPage: 0,
  pageSteps: steps,
  maxPages: 1,
  actors:[],
  loading: false,
}
function getTopActors(max=10, target){
  if(topActors.currentPage >= topActors.maxPages){
    renderActors(max,target,topActors.actors);
    topActors.loading = false;
    return;
  }
  if(topActors.currentPage%200 == 0 && topActors.maxPages > topActors.currentPage*2){
    renderActors(max,target,topActors.actors);
  }
  if(topActors.loading == false){
    renderLoader(target);
    topActors.loading = true;
  }

  let url = apiUrl+'3/person/popular?api_key='+apiKey+'&page='+(topActors.currentPage+1)
  fetch(url)
  .then((response) => response.json())
  .then(response => {
     saveActorsBatch(topActors,response);
     getTopActors(max,target);
  })
  .catch(error =>{
     renderActors(topActors.actors.length,target,topActors.actors);
     console.warn(error);
  })
}



function getMoreActors(target,call){
  if(allActors.loading && allActors.pageSteps <= 0) return;
  allActors.loading = true;
  allActors.pageSteps -= (allActors.pageSteps > 0)? 1 : 0;
  renderLoaderText((100-Math.floor((100/steps)*allActors.pageSteps))+'%');

  let url = apiUrl+'3/person/popular?api_key='+apiKey+'&page='+(allActors.currentPage+1);
  return fetch(url)
  .then((response) => response.json())
  .then(response => {
     setActorsBatch(response);
     if(allActors.pageSteps == 0){
       filterActors(allActors,target); 
       renderActors(allActors.actors.length,target,allActors.actors);
       call.call();
     }
     else{
       getMoreActors(target,call);
     }
  })
  .catch(error =>{
     renderActors(allActors.actors.length,target,allActors.actors);
     call.call();
     console.warn(error);
  });
}
function setActorsBatch(batch){
  if(batch.results){
    saveActorsBatch(allActors,batch);
  }
  else{
    allActors.pageSteps = 0;
  }

  allActors.loading = false;
}

function getActorsBatch(target,call){
  let page = Mnr.e('#currentPage');
  if(page.e.length > 0){

     page = (parseInt(page.e[0].value)-1);
     if(page <= 0) page = 0;
     if(page > 0) page += steps+1; 
  }

  allActors.currentPage = page;
  getMoreActors('#actors',()=>{
     call
     renderMoviesOptions();
  });
}


function saveActorsBatch(oldBatch,batch){
  batch.results = batch.results.filter(actor =>{
    if(Mnr.u.findPosByProp('id',actor.id,oldBatch.actors) == false){
      return true;
    }
    return false;
  });

  oldBatch.actors = [...oldBatch.actors,...batch.results];
  

  oldBatch.actors = oldBatch.actors.filter(actor =>{
    return (actor.known_for_department == 'Acting');
  });


  oldBatch.actors.sort(function(a,b){
     return b.popularity - a.popularity
  });

  

  

  oldBatch.currentPage = batch.page;
  oldBatch.maxPages = batch.total_pages;
}
function renderActors(max,target,batch){
  console.log();
  let html = '';
  let showEmpty = true;
  for (let i = 0; i < max; i++) {
    let actor = batch[i];
    if(actor == undefined) break;
    
    if(actor?.show || actor?.show == true || actor.show == undefined){
     showEmpty = false;
    }

    html += `
    <div class="card actorCard rltv" show="${actor?.show}">
      <a href="/actors?id=${actor.id}" 
         aria-label="more info about ${actor.name}"
         class="z1 absS"></a>
      <img class="anim2 img" alt="${actor.name}" src="${imgUrl+actor.profile_path}" />
      <h3 class="txtC txtS4"><strong>${actor.name}</strong></h3>
    </div>`;
  }


  if(showEmpty == true){
    html = `<div class="message">
             <h3 class="txtC pT40 pB40">No actors found <br/>please try other filter parameters or other page</h3>
            </div>`;
  }

  Mnr.e(target).html(html);
}


// inital data
var gettingInitialData = false;
function getInitialData(calls){
   if(gettingInitialData) return;

   let prom = getGenres().then(()=>{
      calls.call();
      console.log('initial data');
   });
}



// filter
function renderYearOptions(){
   let html = `<option selected value="-1">All Years</option> 
               <option  value="-2">Unregistered</option>`;

   let year = new Date().getFullYear();
   for (let i = year; i >= 1888; i-=5) {
    html += `<option value="${i}">Until ${i}</option>`;
   }  
   Mnr.e('#filterBar [name="year"]').html(html);
}
function renderGenOptions(){
   let html = `<option selected value="-1">All</option>`;
   for(let gen of allGenres.genres){
    html += `<option value="${gen.id}">${gen.name}</option>`;
   }  
   Mnr.e('#filterBar [name="genres"]').html(html);
}

function filterMovies(batch, target){
   if(Mnr.e('#filterBar').e.length <= 0) return;
   

   let titleVal = Mnr.e('#filterBar [name="title"]')?.e[0]?.value;
   let yearVal = Mnr.e('#filterBar [name="year"]')?.e[0]?.value;
   let genresVal = Mnr.e('#filterBar [name="genres"]')?.e[0].value;
   let wishVal = Mnr.e('#filterBar [name="wished"]')?.e[0].value;
   

   if(wishVal != true && wishVal != false){
     wishVal = -1;
   }
   

   batch.movies.sort(function(a,b){
     return (titleVal == 'asc')? 
               a.original_title.localeCompare(b.original_title) :
               b.original_title.localeCompare(a.original_title);
   });
   
   
   batch.movies.map(movie=>{
     let check = [];
     if(genresVal == -1 && yearVal == -1 && wishVal == -1){
       check.push(true);
     }
     else{

       check.push((genresVal != -1)? movie.genre_ids.includes(parseInt(genresVal)) : true);
       
       let year = 9999;
       if(movie.release_date != null && movie.release_date != undefined ){
         year = movie.release_date.split('-');
         if(year.length > 0){
          year = year[0];
          if(year == '' || year == null){
            year = 9999;
          }
         }
       }

       if(yearVal != -2){
         check.push((yearVal != -1)? (yearVal >= year) : true);
       }
       else{
         check.push((year == 9999));

       }
       
       if(wishVal != -1){
         check.push((movie.wished == wishVal));
       }

     }

     movie.show = (check.includes(false) == false);

   });
}

function renderMoviesOptions(){
  let html = `<option 'selected' value="-1">All</option>`;
  let movies = [];
  for(let actor of allActors.actors){
      for(let knw of actor.known_for){
          if(Mnr.u.findPosByProp('id',knw.id,movies) == false  && 
             knw != null && knw.original_title != undefined){
             movies.push(knw);
          }
      }
  }
  movies.sort(function(a,b){return a.original_title.localeCompare(b.original_title)});
  for(let movie of movies){
   html += `<option value="${movie.id}">${movie.original_title}</option>`;
  }  
  Mnr.e('#filterBar [name="movies"]').html(html);
}
function filterActors(batch,target){
  
  if(Mnr.e('#filterBar').e.length <= 0) return;
   
   let titleVal = Mnr.e('#filterBar [name="name"]')?.e[0]?.value;
   let moviesVal = Mnr.e('#filterBar [name="movies"]')?.e[0].value;
   
   moviesVal = (moviesVal)? moviesVal : -1;
   
   
   batch.actors.sort(function(a,b){
     return (titleVal == 'asc')? 
               a.name.localeCompare(b.name) :
               b.name.localeCompare(a.name);
   });
   
   
   batch.actors.map(actor=>{
     let check = [];
     
     
     check.push((moviesVal != -1) ? (actor.known_for.filter(a=>a.id == moviesVal).length > 0) : true);
     // console.log(check);

     actor.show = (check.includes(false) == false);

   });
}

// loaders
function renderLoader(target){
  let html = ` 
    <div class="loaderHolder wFull hFull flxC ordC p20 rltv">
      <div class="abs posC spinner loader ok"></div>
      <div class="abs z2 absS posC mAuto flxC ordC"><p class="txt5 txtC colorOk" id="loaderText"></p></div>
    </div>

  `;
  Mnr.e(target).html(html);
}
function renderLoaderText(text){
  if(Mnr.e('#loaderText').e.length > 0){
    Mnr.e('#loaderText').text(text);
  }
}







// modal
function openModalMessage(msg){
  Mnr.e('#modalMessage .messageHolder').html(msg);
  Mnr.e('#modalMessage').class('open'); 
  Mnr.e('#modalMessageShadow').class('show');
}