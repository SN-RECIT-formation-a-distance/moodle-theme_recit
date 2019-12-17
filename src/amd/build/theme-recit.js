define(['jquery'], function($) {
    'use strict';
    M.recit.course.theme.ThemeRecit.createInstance();
    M.recit.course.theme.EditorHTML.createInstance();
});

M.recit = M.recit || {};
M.recit.course = M.recit.course || {};
M.recit.course.theme = M.recit.course.theme || {};
M.recit.course.theme.ThemeRecit = class{
    static instance = null;

    static createInstance(){
        if(M.recit.course.theme.ThemeRecit.instance === null){
            M.recit.course.theme.ThemeRecit.instance = new M.recit.course.theme.ThemeRecit(); 
        }
    }

    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);

        this.init();
    }

    init(){
        document.onkeyup = this.ctrlShortcuts;
    }

    ctrlShortcuts(e){
        /*if (e.which == 77) {
            alert("M key was pressed");
        } else if (e.ctrlKey && e.which == 66) {
            alert("Ctrl + B shortcut combination was pressed");
        } else if (e.ctrlKey && e.altKey && e.which == 89) {
            alert("Ctrl + Alt + Y shortcut combination was pressed");
        } else if (e.ctrlKey && e.altKey && e.shiftKey && e.which == 85) {
            alert("Ctrl + Alt + Shift + U shortcut combination was pressed");
        }*/
        // 69 = e
        if (e.ctrlKey && e.altKey && e.which == 69){
            this.ctrlModeEdition(); 
        }
    }

    ctrlModeEdition(){
        let btn = document.getElementById("btnEditionMode");
        if(btn !== null){
            btn.click();
        }
    }

    ctrlFullScreen(){
        if (!document.fullscreenEnabled) { return; }

        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            document.documentElement.requestFullscreen();
        }
    }
}

M.recit.course.theme.EditorHTML = class{
    static instance = null;

    static createInstance(){
        if(M.recit.course.theme.EditorHTML.instance === null){
            M.recit.course.theme.EditorHTML.instance = new M.recit.course.theme.EditorHTML(); 
        }
    }

    constructor(){
        this.init = this.init.bind(this);

        this.init();
    }

    init(){
         // Gets the video src from the data-src on each button
        let videoSrc;
        $('.video-btn').click(function() {
            videoSrc = $(this).data( "src" );
        });

    // console.log($videoSrc);
        // when the modal is opened autoplay it
        $('#myModal').on('shown.bs.modal', function (e) {
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
            $("#video").attr('src',videoSrc + "?autoplay=0&amp;modestbranding=1&amp;showinfo=0" );
        })
        // stop playing the youtube video when I close the modal
        $('#myModal').on('hide.bs.modal', function (e) {
            // a poor man's stop video
            $("#video").attr('src',videoSrc);
        })

        // Youtube video background
        $(".player").mb_YTPlayer({
            showControls : false,
            showYTLogo: false
        });
        
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
        
        $('.ubeo_btn_expand').click(function() {
                $(this).parents('.math_content_expand').toggleClass('ubeo_zoom');
                $(this).parents('.container').toggleClass('ubeo_zoom');
                $(this).toggleClass('ubeo_zoom');
                $('html, body').toggleClass('ubeo_zoom');
        });
    }
}


