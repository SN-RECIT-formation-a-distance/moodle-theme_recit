(function($){
    // Background parallax effect

    function isInViewport(node) {
        var rect = node.getBoundingClientRect()
        return (
            (rect.height > 0 || rect.width > 0) &&
            rect.bottom >= 0 &&
            rect.right >= 0 &&
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.left <= (window.innerWidth || document.documentElement.clientWidth)
        )
    }
    $(window).scroll(function() {
        var scrolled = $(window).scrollTop()
        $('').each(function(index, element) {
            var initY = $(this).offset().top
            var height = $(this).height()
            var endY  = initY + $(this).height()

            // Check if the element is in the viewport.
            var visible = isInViewport(this)
            if(visible) {
                var diff = scrolled - initY
                var ratio = Math.round((diff / height) * 300)
                $(this).css('background-position','center ' + parseInt(-(ratio * -0.7)) + 'px')
            }
        })
    })


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
    $('#myModal').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src',$videoSrc);
    })


    // Youtube video background
    $(".player").mb_YTPlayer({
        showControls : false,
        showYTLogo: false
    });
	
	$(function () {
  $('[data-toggle="popover"]').popover({
        html : true,
        trigger: 'focus',
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        }
    });
});
})(jQuery);