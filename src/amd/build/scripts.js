jQuery(function($){
    // Background parallax effect
    $(window).scroll(function () {
        $(".parallax-pale-row, .parallax-dark-row").css("background-position","50% " + ($(this).scrollTop() / -5) + "px");
    });


    // Gets the video src from the data-src on each button
    var $videoSrc;
    $('.video-btn').click(function() {
        $videoSrc = $(this).data( "src" );
    });
    console.log($videoSrc);
    // when the modal is opened autoplay it
    $('#myModal').on('shown.bs.modal', function (e) {
    // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" );
    })
    // stop playing the youtube video when I close the modal
    
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
   


    // Youtube video background
    $(".player").mb_YTPlayer({
        showControls : false,
        showYTLogo: false
    });


});