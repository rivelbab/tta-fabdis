/* ================== main window behavior ==================*/
$(function() {
    var $elem = $('.page-container');
    $('#nav_up').fadeIn('slow');
    $('#nav_down').fadeIn('slow');
    $(window).bind('scrollstart', function(){
        $('#nav_up,#nav_down').stop().animate({'opacity':'0.2'});
    });
    $(window).bind('scrollstop', function(){
        $('#nav_up,#nav_down').stop().animate({'opacity':'1'});
    });    
});
