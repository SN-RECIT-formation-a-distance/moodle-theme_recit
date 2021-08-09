
define(['jquery'], function($) {
    'use strict';
    M.recit.course.theme.ThemeRecit2.createInstance();
    M.recit.course.theme.EditorHTML2.createInstance();
});

M.recit = M.recit || {};
M.recit.course = M.recit.course || {};
M.recit.course.theme = M.recit.course.theme || {};

M.recit.course.theme.ThemeRecit2 = class{
    HISTORY_LIMIT_COUNT_TO_SAVE = 50;
    HISTORY_LIMIT_COUNT_TO_SHOW = 7;
    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);
        this.ctrlFullScreen = this.ctrlFullScreen.bind(this);
        this.history = [];

        this.init();
    }

    init(){
        document.onkeyup = this.ctrlShortcuts;
        this.loadNavigationHistory();
    }

    loadNavigationHistory(){
        let hist = window.localStorage.getItem('recit_navigation_history');
        try {
            let decoded = JSON.parse(hist);
            if (decoded && decoded.length > 0){
                this.history = decoded;
                let count = 0;
                let exists = {};
                $('.navleft').append('<li class="nav-item-divider"></li><li class="nav-item dropdown">\
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'+window.M.str.theme_recit2.last_navigated+'">\
                <i class="fa fa-history"></i>\
                    <span class="show-onmobile"> '+window.M.str.theme_recit2.last_navigated+'</span>\
                </a>\
                <div class="dropdown-menu history-menu" aria-labelledby="navbarDropdown"></div>\
            </li>')
                $('.history-menu').append('<span style="font-weight:bold;text-align:center;width: 100%;display: block;">'+window.M.str.theme_recit2.last_navigated+'</span><div class="dropdown-divider"></div>');
                for (let el of this.history){
                    if (!exists[el.title]){
                        $('.history-menu').append('<a class="dropdown-item" href="'+el.url+'" title="'+el.title+'">'+el.title+'</a>');
                        count++;
                        exists[el.title] = true;
                    }
                    if (count == this.HISTORY_LIMIT_COUNT_TO_SHOW) break;
                }
            }
            this.saveNavigationHistory();
        }catch(e){
            this.saveNavigationHistory();
        }
    }
    saveNavigationHistory(){
        if (!this.history) this.history = [];
        let title = document.title;
        let url = document.location.href;
        if (this.history.length == 0 || this.history[this.history.length - 1].title != title){
            this.history.push({title:title,url:url});
        }
        if (this.history.length > this.HISTORY_LIMIT_COUNT_TO_SAVE){
            this.history.shift();
        }
        window.localStorage.setItem('recit_navigation_history', JSON.stringify(this.history));
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
// definition static attributes and methods to work with Firefox
M.recit.course.theme.ThemeRecit2.instance = null;

M.recit.course.theme.ThemeRecit2.createInstance = function(){
    if(M.recit.course.theme.ThemeRecit2.instance === null){
        M.recit.course.theme.ThemeRecit2.instance = new M.recit.course.theme.ThemeRecit2();
    }
}

M.recit.course.theme.EditorHTML2 = class{
    constructor(){
        this.init = this.init.bind(this);

        this.init();
    }

    init(){
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
		
		this.initBtnVideo();
        this.initFlipCard();
    }

    initFlipCard(){
        $(document).on('click', '.flipcard', function(event) {
            if ($(this.parentElement).hasClass('hover')){
                $(this.parentElement).removeClass('hover');
            }else{
                $(this.parentElement).addClass('hover')
            }
        });
        
        $(".flipcard").each(function(e) {
            $(this.parentElement).addClass('manual-flip');
        })
    }
	
	initBtnVideo(){
        let bsModalVideo = document.createElement('template');
        bsModalVideo.innerHTML = '\
        <div class="modal fade" id="bsModalVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\
            <div class="modal-dialog modal-dialog-centered" role="document">\
                <div class="modal-content">\
                    <!--<div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">\
                            <span aria-hidden="true">&times;</span>\
                        </button>\
                    </div>-->\
                    <div class="modal-body" style="padding: 0;">\
                        <div class="embed-responsive embed-responsive-16by9">\
                            <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay" allowfullscreen></iframe>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>';
        bsModalVideo = bsModalVideo.content.childNodes[1];

		 // Gets the video src from the data-src on each button
        $('.video-btn').click(function(event) {
            let idVideo = $(event.target).data( "src" );

            document.body.appendChild(bsModalVideo);
            // when the modal is opened autoplay it
            $(bsModalVideo).on('shown.bs.modal', function (e) {
                let iFrame = $('#video').get(0);
                
                // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
                $(iFrame).attr('src',`https://www.youtube.com/embed/${idVideo}?autoplay=1&amp;modestbranding=1&amp;showinfo=0`);
                // move to the end of the body to fix Bootstrap modal appearing under background
                $(bsModalVideo).modal('show');
                //$(iFrame).trigger('focus');
            })
            // stop playing the youtube video when I close the modal
            $(bsModalVideo).on('hide.bs.modal', function (e) {
                //document.body.removeChild(bsModalVideo);
                let iFrame = $('#video').get(0);
                // stop video
                $(iFrame).attr('src',"");
            })
        });
	}
}

M.recit.course.theme.EditorHTML2.instance = null;

M.recit.course.theme.EditorHTML2.createInstance = function(){
    if(M.recit.course.theme.EditorHTML2.instance === null){
        M.recit.course.theme.EditorHTML2.instance = new M.recit.course.theme.EditorHTML2();
    }
}
