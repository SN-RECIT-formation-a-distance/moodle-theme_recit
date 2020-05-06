
define(['jquery'], function($) {
    'use strict';
    M.recit.course.theme.ThemeRecit.createInstance();
    M.recit.course.theme.EditorHTML.createInstance();
});

M.recit = M.recit || {};
M.recit.course = M.recit.course || {};
M.recit.course.theme = M.recit.course.theme || {};

M.recit.course.theme.ThemeRecit = class{
    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);

        this.init();
    }

    init(){
        document.onkeyup = this.ctrlShortcuts;
        initsvgrecit();
    }

    initsvgrecit() {
        var base, cache = {}, hash, i, onload, request = false, url, uses = document.getElementsByTagName('use'), xhr;
        if (XMLHttpRequest) {
            request = new XMLHttpRequest();
            if ('withCredentials'in request) {
                request = XMLHttpRequest;
            } else {
                request = XDomainRequest ? XDomainRequest : false;
            }
        }
        if (!request) {
            return;
        }
        onload = function() {
            var body = document.body
              , x = document.createElement('x');
            x.innerHTML = xhr.responseText;
            body.insertBefore(x.firstChild, body.firstChild);
        }
        ;
        for (i = 0; i < uses.length; i += 1) {
            url = uses[i].getAttribute('xlink:href').split('#');
            base = url[0];
            hash = url[1];
            if (!base.length && hash && !document.getElementById(hash)) {
                base = 'https:///recitfad.ca/cdn/iconsrecit/1.0.0/svgdefs.svg';
                console.log("cdn");
            }
            if (base.length) {
                cache[base] = cache[base] || new request();
                xhr = cache[base];
                if (!xhr.onload) {
                    xhr.onload = onload;
                    xhr.open('GET', base);
                    xhr.send();
                    console.log("pas cdn");
                }
            }
        }
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
M.recit.course.theme.ThemeRecit.instance = null;

M.recit.course.theme.ThemeRecit.createInstance = function(){
    if(M.recit.course.theme.ThemeRecit.instance === null){
        M.recit.course.theme.ThemeRecit.instance = new M.recit.course.theme.ThemeRecit(); 
    }
}

M.recit.course.theme.EditorHTML = class{
    constructor(){
        this.init = this.init.bind(this);

        this.init();
    }

    init(){
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
		
		this.initBtnVideo();
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

M.recit.course.theme.EditorHTML.instance = null;

M.recit.course.theme.EditorHTML.createInstance = function(){
    if(M.recit.course.theme.EditorHTML.instance === null){
        M.recit.course.theme.EditorHTML.instance = new M.recit.course.theme.EditorHTML(); 
    }
}
