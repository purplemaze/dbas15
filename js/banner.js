$(window).scroll(function(){
    if ($(window).scrollTop() >= 181) {
       $('nav').addClass('fixed-header');
    }
    else {
       $('nav').removeClass('fixed-header');
    }
});

/* scrollTop() >= 240
   Should be equal the the height of the header
 */