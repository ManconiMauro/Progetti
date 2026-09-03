const apiKey = "api_key=2461e2dc84a8e379ad6a8309c585a25d";
const lang = "language=it-IT";
const baseUrl = "https://api.themoviedb.org/3";

function getPage(){
    query = window.location.search;
    var page = new URLSearchParams(query).get('page');

    if(page == null || page < 1){
        document.getElementById('page-prev').innerHTML = 1
        document.getElementById('page-current').innerHTML = 2
        document.getElementById('page-next').innerHTML = 3

        return 1;
    }
    page =  parseInt(page);
    document.getElementById('page-prev').innerHTML = page - 1
    document.getElementById('page-current').innerHTML = page
    document.getElementById('page-next').innerHTML = page + 1
    return page;
}

function setPage(page){
    query = window.location.search;
    var search = new URLSearchParams(query);
    search.set('page', page);
    window.location.search = search.toString();
}


function getPopular(page = 1){

    url = baseUrl + "/movie/popular?" + apiKey + "&" + lang + "&page=" + page
    fetch(url)
    .then(response => response.json())
    .then(popular => cards(popular))

}

function cards(popolari){
    var card = document.getElementById('card-film');
    for (var i = popolari.results.length - 1; i >= 0;   i--) {
      // for (var i =0; i <  popolari.results.length;   i++) {
      
      var clone = card.cloneNode(true);
      clone.id = 'badge-' + i;
      clone.getElementsByClassName('card-title')[0].innerHTML 
      =popolari.results[i].title;
  
      clone.getElementsByClassName('card-text')[0].innerHTML 
      =popolari.results[i].overview.substring(0,200) + "...";
  
      clone.getElementsByClassName('card-img-top')[0].src = 
      "https://image.tmdb.org/t/p/w200" + popolari.results[i].poster_path;
      card.after(clone);
  
      clone.getElementsByClassName('btn-primary')[0].href = clone.getElementsByClassName('btn-primary')[0].href + popolari.results[i].id
  
      clone.classList.remove('d-none');
    }

    
}