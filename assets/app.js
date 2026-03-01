//import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import './styles/app.css';
import Swiper from 'swiper';
import $ from "jquery";
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
import bootstrap from "bootstrap";

import {waitFor} from "@babel/core/lib/gensync-utils/async";

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');


$(document).ready(function() {
    // alert("sada");
    let actualCategoryChoosed = null;
    let actualSubCategoryChoosed = null;
    let actualSortByChoosed = null;
    let actualSortDirectionChoosed = "asc";
    let actualOfferTypeChoosed = null;
    let actualKrajChoosed = null;
    let actualOkresChoosed = null;
    let actualLokalitaChoosed = "all";
    let actualPriceRangeChoosed = "all";


    function checkForButtonCOunting(){
        if($("#properties-selected-length")!=null){
            let propertiesSelectedLength = $("#properties-selected-length");
            let propertiesSelectedSuffix = $("#properties-selected-suffix");
            $.ajax({
                type: "POST",
                url: "/get-countings-for-button",
                data: {
                    search: null,
                    mainCategory: actualCategoryChoosed,
                    subCategory: actualSubCategoryChoosed,
                    sortBy: actualSortByChoosed,
                    sortDirection: actualSortDirectionChoosed
                },
                success: function (response) {
                    switch(response){
                        case 0:
                            propertiesSelectedSuffix.text("nemovitost");
                            break;
                        case 1:
                            propertiesSelectedSuffix.text("nemovitost");
                            break;
                        case 2:
                            propertiesSelectedSuffix.text("nemovitosti");
                            break;
                        case 3:
                            propertiesSelectedSuffix.text("nemovitosti");
                            break;
                        case 4:
                            propertiesSelectedSuffix.text("nemovitosti");
                            break;
                        case response>5:
                            propertiesSelectedSuffix.text("nemovitostí");
                            break;
                        default:
                            propertiesSelectedSuffix.text("nemovitostí");
                            break;
                    }
                    propertiesSelectedLength.text(response);
                    console.log(response);
                }
            });


        }
    }

    checkForButtonCOunting();

    function openMenu(_thisIcon){
        _thisIcon.removeClass("icon-menu-mobile");
        _thisIcon.addClass("icon-cross");
    }
    function closeMenu(_thisIcon){
        _thisIcon.removeClass("icon-cross");
        _thisIcon.addClass("icon-menu-mobile");
    }
    function openSearch(_searchBar,_searchWrapper,_thisIcon){
        _searchBar.slideDown("slow");
        _searchBar.css("display","flex");
        _searchWrapper.delay(200).fadeIn("slow");
        _thisIcon.removeClass("icon-search");
        _thisIcon.addClass("icon-cross");
    }
    function closeSearch(_searchBar,_searchWrapper,_thisIcon){
        _searchWrapper.fadeOut("slow");
        _searchBar.delay(200).slideUp("slow");
        _thisIcon.removeClass("icon-cross");
        _thisIcon.addClass("icon-search");
    }
    $('[data-toggle="popover"]').popover();

    /* NAV SEARCHBAR TOGGLE */
    $(".nav-search-butt").click(function (){
        let searchBar = $(".desktop-search-bar");
        let searchWrapper = $(".search-wrapper");
        let thisIcon = $("#nav-search-butt-icon");
        searchBar.toggleClass("active");
        if(searchBar.hasClass("active")){
            openSearch(searchBar,searchWrapper,thisIcon);

            if($(".navigation-toggler").hasClass("active")){
                closeMenu($("#navbar-toggler-icon"));
            }

        }else {
            closeSearch(searchBar,searchWrapper,thisIcon);
        }
    });

    $(".navigation-toggler").click(function (){

        let mobNavbar = $(".mobile-nav-bar");
        let thisIcon = $("#navbar-toggler-icon");
        mobNavbar.toggleClass("active");
        if(mobNavbar.hasClass("active")){
            openMenu(thisIcon);

            if($(".nav").hasClass("active")){
                closeMenu($("#nav-search-butt-icon"));
            }

        }else{
            closeMenu(thisIcon);
        }
    });
    /* SWITCHER TOGGLE */
    $(".tab-switcher").click(function (){
        $(".tab-switcher").removeClass("active");
        $(this).toggleClass("active");

        if($(this).hasAttribute("data-filter")){
            actualSubCategoryChoosed = $(this).attr("data-filter");
        }
    });

    /* CATEGORY TAB TOGGLE */
    $(".cat-tab").click(function (){
        $(".cat-tab").removeClass("active");
        $(this).toggleClass("active");

        let tabSubfilters = $(".tab-subfilter");
        actualCategoryChoosed = $(this).attr("data-filter");

        tabSubfilters.removeClass("d-none");
        tabSubfilters.addClass("d-none");


        switch ($(this).attr("data-filter")){
            case "domy":
                $("#dum_subfilter").removeClass("d-none");
                break;
            case "byty":
                $("#byt_subfilter").removeClass("d-none");
                break;
            case "pozemky":
                $("#pozemky_subfilter").removeClass("d-none");
                break;
            case "komercni":
                $("#komercni_subfilter").removeClass("d-none");
                break;
            case "ostatni":
                $("#ostatni_subfilter").removeClass("d-none");
                break;
            default:
                $("#dum_subfilter").removeClass("d-none");
                break;
        }


    });

    $(".subfilter-item-select").on('change',function (){


        switch ($(this).attr("id")){
            case "offer_type_select":
                actualOfferTypeChoosed = $(this).val();
                break;
            case "kraj_select":
                actualKrajChoosed = $(this).val();
                break;
            case "okres_select":
                actualOkresChoosed = $(this).val();
                break;
            case "lokalita_select":
                actualLokalitaChoosed = $(this).val();
                break;
            case "price_select":
                actualPriceRangeChoosed = $(this).val();
                break;
            case "sorting_selects":
                actualSortByChoosed = $(this).attr("data-sort-by");
                actualSortDirectionChoosed = $(this).attr("data-sort-directions");
                break;
        }
    });

    $(".offer-tab").click(function (){
        if(!$(this).hasClass("sold")){
            let slug = $(this).attr("data-slug");
            window.location.href = "/nemovitost/" + slug;
        }
    });

    $(".blog-item").click(function (){
        let url= $(this).attr("data-url");
        window.open(url, "_blank")
    });

    $('.image-link').viewbox();

    $('.search-reality-butt').click(function () {
        let searchText = $("#search-reality").val();
        $.ajax({
            type: "POST",
            url: "/nemovitosti/json-filter",
            data: {
                search: searchText
            },
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        });

    });
    $('.js-filter-realities').click(function () {

        $.ajax({
            type: "POST",
            url: "/nemovitosti/json-filter",
            data: {
                search: null,
                mainCategory: actualCategoryChoosed,
                subCategory: actualSubCategoryChoosed,
                sortBy: actualSortByChoosed,
                sortDirection: actualSortDirectionChoosed
            },
            success: function (response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        });

    });

    $('.send-mail').click(function (){
        let firstname = $(".mail_form_firstname").val();
        let lastname = $(".mail_form_lastname").val();
        let email = $(".mail_form_email").val();
        let phone = $(".mail_form_phone").val();
        let message = $(".mail_form_message-text").val();
        let agreed = $(".agree-send-mail").is(":checked");
        let spinner = $(".mail-spinner");
        let info = $(".form-send-result");
        if(agreed){
            spinner.addClass("active");
            $.ajax({
                type: "POST",
                url: "/send-contact-form-mail",
                data: {
                    firstname: firstname,
                    lastname: lastname,
                    email: email,
                    phone: phone,
                    message: message,
                },
                success: function (response) {
                    //service.php response
                    if(response.success===true){

                        info.addClass("active");
                        $(".seller-contact-form").trigger('reset');
                        spinner.removeClass("active");
                    }
                }
            });
        }else{
            alert("Musíte souhlasit!");
        }


    });
    // if($("#reality-map-render")){
    //     initMap();
    // }

    $('.swiper-button-next').click(function (){
        swiper.slideNext();
    });
    $('.swiper-button-prev').click(function (){
        swiper.slidePrev();
    });

    $('.hero-category-item').click(function (){
        let _url = $(this).attr("data-url");
        window.location.href = _url;
    });
});

const swiper = new Swiper('.swiper', {
    loop: true,
    navigation: {
        nextEl:'.swiper-button-next',
        prevEl:'.swiper-button-prev',

    }
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

const myModal = document.getElementById('myModal')
const myInput = document.getElementById('want-sell')

if(myModal){
    myModal.addEventListener('shown.bs.modal', () => {
        myInput.focus()
    });
}

