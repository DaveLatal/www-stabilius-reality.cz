//import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import './styles/app.css';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
const {waitFor} = require("@babel/core/lib/gensync-utils/async");
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');


$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    /* NAV SEARCHBAR TOGGLE */
    $(".nav-search-butt").click(function (){
        let searchBar = $(".desktop-search-bar");
        let searchWrapper = $(".search-wrapper");
        let thisIcon = $("#nav-search-butt-icon");
        searchBar.toggleClass("active");
        if(searchBar.hasClass("active")){
            searchBar.slideDown("slow");
            searchBar.css("display","flex");
            searchWrapper.delay(200).fadeIn("slow");
            thisIcon.removeClass("icon-search");
            thisIcon.addClass("icon-cross");
        }else {
            searchWrapper.fadeOut("slow");
            searchBar.delay(200).slideUp("slow");
            thisIcon.removeClass("icon-cross");
            thisIcon.addClass("icon-search");
        }
    });

    /* SWITCHER TOGGLE */
    $(".offer-tab-switcher").click(function (){
        $(".offer-tab-switcher").removeClass("active");
        $(this).toggleClass("active");
    });

    $(".navigation-toggler").click(function (){

        let mobNavbar = $(".mobile-nav-bar");
        let thisIcon = $("#navbar-toggler-icon");
        mobNavbar.toggleClass("active");
        if(mobNavbar.hasClass("active")){
            thisIcon.removeClass("icon-menu-mobile");
            thisIcon.addClass("icon-cross");
        }else{
            thisIcon.removeClass("icon-cross");
            thisIcon.addClass("icon-menu-mobile");
        }
    });

    $(".offer-tab").click(function (){
        let slug = $(this).attr("data-slug");
        window.location.href = "/nemovitost/" + slug;
    });
    $('.image-link').viewbox();



    $('#send-mail').click(function (){

    });
    // if($("#reality-map-render")){
    //     initMap();
    // }

});

let apiKey = "";
if(document.querySelector('#reality-map-render') !==null){

function loadGoogleMaps(apiKey) {
    return new Promise((resolve, reject) => {
        window.initMap = function() {
            resolve(google.maps);
        };

        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap`;
        script.async = true;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}
    apiKey = document.querySelector('#reality-map-render').dataset.apiKey;


loadGoogleMaps(apiKey)
    .then((maps) => {

        let _lat = parseFloat(document.getElementById('map-render-lat').value);
        let _lng = parseFloat(document.getElementById('map-render-lng').value);
        const options = {
            zoom: 14,
            center: { lat: _lat, lng: _lng }
        };

        const map = new maps.Map(document.getElementById('reality-map-render'), options);

        new maps.Marker({
            position: { lat: _lat, lng: _lng },
            map: map,
            title: 'Hello New York!'
        });
    })
    .catch((err) => console.error('Google Maps failed to load', err));
}
