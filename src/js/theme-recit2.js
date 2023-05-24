M.recit = M.recit || {};
M.recit.theme = M.recit.theme || {};
M.recit.theme.recit2 = M.recit.theme.recit2 || {};

M.recit.theme.recit2.Options = {
    maxWidth: 1500
}

M.recit.theme.recit2.Ctrl = class{
    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);
        this.ctrlFullScreen = this.ctrlFullScreen.bind(this);

        this.mainTopNav = new M.recit.theme.recit2.MainTopNav();
        this.sectionsNav = new M.recit.theme.recit2.SectionsNav();
        this.webApi = new M.recit.theme.recit2.WebApi();

        this.switchEditingMode = null;

        this.init();
        this.initTimeoutModal();
        this.initEkkoLightbox();
        this.initGoToTopBtn();
        setTimeout(() => this.initDrawer(), 500);//Timer needed because drawer needs to be initiated by Bootstrap
    }

    init(){
        this.switchEditingMode = document.getElementById("switch-editing-mode");

        if(this.switchEditingMode){
            this.switchEditingMode.onclick = function(event){
                window.location.href = event.target.value;
            }
        }

        window.addEventListener("keydown", this.ctrlShortcuts);
    };

    initDrawer(){
        if (window.innerWidth < 550){//if mobile hide drawer
            if (document.body.classList.contains('drawer-open-right')){
                let btn = document.getElementById('sidepreopen-control');
                if (btn){
                    btn.click();
                }
            }
        }

        let toggler = document.getElementById('sidepreopen-control');
        if (toggler){
            toggler.addEventListener('click', () => setTimeout(() => {
                if (document.body.classList.contains('drawer-open-right')){
                    M.util.set_user_preference('drawer-open-block', true);
                }else{
                    M.util.set_user_preference('drawer-open-block', false);
                }
            }, 100))
        }
    }

    initGoToTopBtn(){
        let btn = document.getElementById('goto-top-link');
        if (btn){
            document.addEventListener('scroll', (e) => {
                if (document.documentElement.scrollTop > 500){
                    btn.style.display = 'block';
                }else{
                    btn.style.display = 'none';
                }
            })
        }
    }

    initEkkoLightbox(){
        require(["jquery"],function($){
            $(document).on('click','[data-toggle="lightbox"]',function(event){
                event.preventDefault();
                $(this).ekkoLightbox();
            });
        });
    }

    initTimeoutModal(){
        let modal = document.getElementById('recit-modal-timeout');
        if (modal){
            setInterval(() => this.checkForTimeout(), 60000 * 5);//Check every 5 mins
        }
    }

    checkForTimeout(){
        this.webApi.ping(null, () => {
            $('#recit-modal-timeout').modal('show');
        });
    }

    ctrlShortcuts(e){
        // 69 = e
        if (e.ctrlKey && e.altKey && e.which == 69){
            this.switchEditingMode.onclick({target: this.switchEditingMode});
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

M.recit.theme.recit2.floatingSection = class {
    constructor(section, floatingSection){
        
        window.onscroll = this.onScroll.bind(this);
        window.onresize = this.onScroll.bind(this);
        this.navsections = section;
        this.menu = floatingSection;
        this.navbarHeight = document.querySelector('#mainTopNav').offsetHeight;
        this.maincontent = document.querySelector('#page-content');

    }

    isMobile(){
        return window.innerWidth < 992;
    }

    onScroll(event){
        if (this.isMobile() || this.maincontent.offsetHeight < (this.menu.offsetHeight + 50)){ //If maincontent is empty (smaller than menu), no need to go floating
            this.menu.style.top = '';
            this.menu.style.height = '';
            this.menu.style.maxHeight = '';
            return;
        }

        var scrolled = this.navsections.getBoundingClientRect();
        var top = 0-scrolled.top + this.navbarHeight;
        var heightToRemove = (this.navsections.offsetHeight - top - 10);
        if (heightToRemove > window.innerHeight){
            heightToRemove = '';
        }else{
            heightToRemove = heightToRemove + 'px';
        }
        
        if (top > 0){
            this.menu.style.top = top + 'px';
            this.menu.style.height = heightToRemove;
            this.menu.style.maxHeight = 'calc(100vh - '+this.navbarHeight+'px)';
        }else{
            this.menu.style.top = '';
            this.menu.style.height = '';
            this.menu.style.maxHeight = '';
        }
    }
}

// definition static attributes and methods to work with Firefox
if (typeof M.recit.theme.recit2.Ctrl.instance == 'undefined') M.recit.theme.recit2.Ctrl.instance = null;

M.recit.theme.recit2.Ctrl.createInstance = function(){
    if(M.recit.theme.recit2.Ctrl.instance === null){
        M.recit.theme.recit2.Ctrl.instance = new M.recit.theme.recit2.Ctrl();
    }
}

M.recit.theme.recit2.MainTopNav = class{
    constructor(){
        this.mainTopNav = document.getElementById('mainTopNav');
        if (this.mainTopNav){
            this.ctrlResponsive = this.ctrlResponsive.bind(this);   

            window.addEventListener('resize', this.ctrlResponsive);

            this.menuMobile = mainTopNav.querySelector('[id=menu-mobile]');
            this.menuCourse = mainTopNav.querySelector('[id=menu-course]');
            this.menuPlatform = mainTopNav.querySelector('[id=menu-platform]');

            this.elems = {
                btnCourseHome: mainTopNav.querySelector('[data-button="coursehome"]'),
                btnNotifications: mainTopNav.querySelector('[data-button="notifications"]'),
                btnMessages: mainTopNav.querySelector('[data-button="messages"]'),
                titles: mainTopNav.querySelectorAll('[data-title]')
            }

            this.ctrlResponsive();
        }
    }

    ctrlResponsive(){
        if(window.innerWidth > 992){
            this.menuMobile.style.display = 'none';

            if(this.elems.btnCourseHome){
                this.menuCourse.insertBefore(this.elems.btnCourseHome, this.menuCourse.firstElementChild);
            }
            
            if(this.menuPlatform){
                if(this.elems.btnMessages){
                    this.menuPlatform.insertBefore(this.elems.btnMessages, this.menuPlatform.firstElementChild);
                }
                
                if(this.elems.btnNotifications){
                    this.menuPlatform.insertBefore(this.elems.btnNotifications, this.menuPlatform.firstElementChild);
                }
            }

            for(let item of this.elems.titles){
                item.style.display = "none";
            }
        }
        else{
            this.menuMobile.style.display = 'inline-flex';

            if(this.elems.btnCourseHome){
                this.menuMobile.appendChild(this.elems.btnCourseHome);
            }
            
            if(this.menuMobile){
                if(this.elems.btnNotifications){
                    this.menuMobile.appendChild(this.elems.btnNotifications);
                }
                
                if(this.elems.btnMessages){
                    this.menuMobile.appendChild(this.elems.btnMessages);
                }
            }
            
            
            for(let item of this.elems.titles){
                item.style.display = "inline-flex";
            }
        }
    }
}

M.recit.theme.recit2.Utils = class{
    static getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return false;
    }

    static setCookie(id, value, minutesExpire, path) {
        minutesExpire = minutesExpire || 1440;
        path = path || null;

        let d = new Date();
        d.setTime(d.getTime() + (minutesExpire * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        
        let cookie = `${id}=${value};SameSite=Lax;${expires};`;

        if(path){
            cookie += `path=${path};`;
        }
        
        document.cookie = cookie;
    };

    static getCookieCurSection(){
        let courseId = window.document.body.className.match(/course-\d+/);
        let result = false;

        if(courseId){
            let cookie = M.recit.theme.recit2.Utils.getCookie(`${courseId}-cursection`);
            if (!cookie) return false;
            result = "#section-"+cookie;
        }

        return result;
    }

    static setCookieCurSection(sectionId){
        let courseId = window.document.body.className.match(/course-\d+/);

        if(courseId){
            let section = sectionId.replace( /^\D+/g, '');
            if (section == '') section = sectionId;
            M.recit.theme.recit2.Utils.setCookie(`${courseId}-cursection`, section, 1440, `/`);
        }
    }

    static getUrlVars(){
        let uri;

        uri = decodeURI(window.location.href);
        let queryParams = {}
        //create an anchor tag to use the property called search
        let anchor = document.createElement("a");
        //assigning url to href of anchor tag
        anchor.href = uri;

        //search property returns the query string of url
        let queryStrings = anchor.search.substring(1);
        let params = queryStrings.split("&");
        for (var i = 0; i < params.length; i++) {
          var pair = params[i].split("=");
          queryParams[pair[0]] = decodeURIComponent(pair[1]);
        }

        return queryParams;
    }
}

M.recit.theme.recit2.WebApi = class{
    constructor(){
        this.gateway = this.getGateway();

        this.post = this.post.bind(this);
    }

    getGateway(){
        return `${M.cfg.wwwroot}/theme/recit2/classes/local/WebApi.php`;
    }

    post(url, data, callbackSuccess, callbackError){
        data = JSON.stringify(data);

        let xhr = new XMLHttpRequest();
        let that = this;

        xhr.open("post", url, true);
        // Header sent to the server, specifying a particular format (the content of message body).
        xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
        xhr.setRequestHeader('Accept', 'json'); // What kind of response to expect.

        xhr.onload = function(event){
            if(this.clientOnLoad !== null){
                let result = null;

                try{
                    result = JSON.parse(event.target.response);
                }
                catch(error){
                    console.log(error, this);
                }
                
                if (callbackSuccess){
                    callbackSuccess.call(this, result);
                }
            }
        }

        if (callbackError){
            xhr.onerror = callbackError;
        }
        
        xhr.send(data);
    }

    ping(onSuccess, onError){
        let options = {};
        options.service = "ping";
        this.post(this.gateway, options, onSuccess, onError);
    }
}

//Init
M.recit.theme.recit2.Ctrl.createInstance();