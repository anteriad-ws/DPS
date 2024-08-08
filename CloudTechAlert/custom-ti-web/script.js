var loadMore = 1;
var resourceType = 'no';

/*function hide_URLstring() {
    var uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
        var clean_uri = uri.substring(0, uri.indexOf("?"));
        window.history.replaceState({}, document.title, clean_uri);
    }
}
jQuery(document).ready(function () {
    load_posts();
    jQuery('.resource-type-link').on('click', function (eV) {
        resourceType = 'no';
        eV.preventDefault();
        jQuery('.resource-type-link').removeClass('active-filter');
        tag_id = jQuery(this).attr('data-type-id');
        jQuery(this).addClass('active-filter');
        loadMore = 1;
        pageNumber = 0;
        jQuery('.post_loop_').html('');
        load_posts();
    });
    // jQuery("#latestloadMore").on("click",function(e){ // When btn is pressed.
    if (jQuery(".post_loop_").length > 0) {
        jQuery(window).on('scroll', function (e) {
            e.preventDefault();
            if (jQuery('#latestloadMore').length > 0) {
                if (loadMore == 1) {
                    if (jQuery(window).scrollTop() >= jQuery('#latestloadMore').offset().top + jQuery('#latestloadMore').outerHeight() - window.innerHeight) {

                        load_posts();

                    }
                }
            }
        });
    }

    jQuery(".js-resource-type").on("change", function (e) {
        resourceType = 'no';
        loadMore = 1;
        pageNumber = 0;
        cat_id = jQuery(this).find("option:selected").val();
        jQuery('.post_loop_').html('');
        load_posts();
    });

});
var ppp = 9; // Post per page
var cat_id = '';
var tag_id = '';
var pageNumber = 0;
var postType = 'post';
var tax_name = '';

function load_posts() {
    if (resourceType == 'yes') {
        return;
    }
    hide_URLstring();
    jQuery("#extra-info").html('');
    pageNumber++;
    postType = jQuery('.post_loop_').attr('data-post-type');
    ppp = jQuery('.post_loop_').attr('data-post-per-page');
    jQuery.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        dataType: "html",
        async: true,
        data: {
            ppp: ppp,
            pageNumber: pageNumber,
            postType: postType,
            cat_id: cat_id,
            tag_id: tag_id,
            action: 'show_ti_post_ajax' //this is the name of the AJAX method called in WordPress
        },
        beforeSend: function () {
            jQuery('.ti_loader').show();
        },
        success: function (data) {
            var $data = jQuery(data);
            if ($data.length) {
                jQuery(".post_loop_").append($data);
                set_equal_height();
            } else {
                loadMore = 0;
                if (jQuery(".post_loop_").html().length == 0) {

                    jQuery("#extra-info").html(myAjax.noResource_text);

                }
            }
            jQuery('.ti_loader').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
        }

    });
    return false;
}
*/
function set_equal_height() {


    // Cache the highest
    var highestBox = 0;

    // Select and loop the elements you want to equalise
    jQuery('.post_loop_ .resource-card .content-box').each(function () {

        // If this box is higher than the cached highest then store it
        if (jQuery(this).height() > highestBox) {
            highestBox = jQuery(this).height();
            // console.log(highestBox);
        }

    });

    // Set the height of all those children to whichever was highest 
    jQuery('.post_loop_ .resource-card .content-box').height(highestBox);

}

function set_equal_height_boxes(mainClass) {


    // Cache the highest
    var highestBox = 0;

    // Select and loop the elements you want to equalise
    jQuery(mainClass + ' .body-container-resource-inner .vc_column_container .td-module-meta-info123').each(function () {

        // If this box is higher than the cached highest then store it
        if (jQuery(this).height() > highestBox) {
            highestBox = jQuery(this).height();
            // console.log(highestBox);
        }

    });

    // Set the height of all those children to whichever was highest 
    jQuery(mainClass + ' .body-container-resource-inner .vc_column_container .td-module-meta-info123').height(highestBox);

}

jQuery(document).ready(function () {
    // set_equal_height_boxes('.tax-news_categories');
    // set_equal_height_boxes('.tax-geo-location');
    addLogoutLink();
    //replace_searchBox();
});

function replace_searchBox() {
    var search_defaultTXT = 'Search here';
    var searchBar = jQuery('.header-search').html();
    jQuery('.menu-top-bar-menu-container').prepend('<li>' + searchBar + '</li>');
    jQuery('.header-search').remove();
    jQuery('.menu-top-bar-menu-container .search-input').attr('placeholder', search_defaultTXT);
    jQuery('.menu-top-bar-menu-container .search-input').val(search_defaultTXT);
    jQuery('.menu-top-bar-menu-container .search-input').attr('onblur', search_defaultTXT);
    jQuery('.menu-top-bar-menu-container .searchform-wrapper').append('<i class="fa fa-search"></i>');

    jQuery('.searchform-wrapper .fa-search').on('click', function () {
        var searchQuery = jQuery('.menu-top-bar-menu-container .search-input').val();
        window.location.href = '?s=' + searchQuery;
    });
}

function addLogoutLink() {
    jQuery.ajax({
        url: myAjax.ajaxurl,
        type: "POST",
        dataType: "json",
        data: {
            action: 'addLogoutLink_ajax' //this is the name of the AJAX method called in WordPress
        },
        success: function (data) {
            console.log(data);
            var logoutLink = jQuery('#menu-top-bar-menu .my-account .sub-menu li:last-child a').attr('href');
            console.log(logoutLink);
            jQuery('#menu-top-bar-menu .my-account .sub-menu li:last-child a').attr('href', logoutLink + data.url);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }

    });
}