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
        searchBar.toggleClass("active");
        if(searchBar.hasClass("active")){
            searchBar.slideDown("slow");
            searchBar.css("display","flex");
            searchWrapper.delay(200).fadeIn("slow");
        }else {
            searchWrapper.fadeOut("slow");
            searchBar.delay(200).slideUp("slow");
        }
    });

    /* SWITCHER TOGGLE */
    $(".offer-tab-switcher").click(function (){
        $(".offer-tab-switcher").removeClass("active");
        $(this).toggleClass("active");
    });

});
