
define(['jquery'], function($) {
    'use strict';
    M.recit.theme.recit2.Ctrl.createInstance();
});

M.recit = M.recit || {};
M.recit.theme = M.recit.theme || {};
M.recit.theme.recit2 = M.recit.theme.recit2 || {};

M.recit.theme.recit2.Ctrl = class{
    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);
        this.ctrlFullScreen = this.ctrlFullScreen.bind(this);

        this.navSections = new M.recit.theme.recit2.NavSections();
    }

    ctrlShortcuts(e){
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
M.recit.theme.recit2.Ctrl.instance = null;

M.recit.theme.recit2.Ctrl.createInstance = function(){
    if(M.recit.theme.recit2.Ctrl.instance === null){
        M.recit.theme.recit2.Ctrl.instance = new M.recit.theme.recit2.Ctrl();
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
        return "";
    }

    static setCookie(id, value, minutesExpire) {
        minutesExpire = minutesExpire || 1440;
        let d = new Date();
        d.setTime(d.getTime() + (minutesExpire * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = id + "=" + value + ";SameSite=Lax;" + expires;
    };

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

M.recit.theme.recit2.NavSections = class{
    constructor(){
        window.onscroll = this.onScroll.bind(this);

        this.ctrlMenu = this.ctrlMenu.bind(this);
        this.ctrlPagination = this.ctrlPagination.bind(this);

        this.sectionList = [];
        this.menu = null;
        this.pagination = {
            placeholder: null,
            btnPrevious: null,
            btnNext: null
        };

        this.init();
    }

    init(){       
        let params = M.recit.theme.recit2.Utils.getUrlVars();
        let sectionId = params.sectionId || window.location.hash || M.recit.theme.recit2.Utils.getCookie('section') || '#section-0';

        this.menu = document.getElementById("nav-sections");

        if(this.menu){
            this.sectionList = this.menu.querySelectorAll('a');

            for(let section of this.sectionList){
                section.addEventListener('click', this.ctrlMenu);

                if(section.getAttribute('href') === sectionId){
                    section.click();
                }
            }
        }

        this.pagination.placeholder = document.getElementById('sectionPagination');

        if(this.pagination.placeholder){
            let buttons = this.pagination.placeholder.querySelectorAll('a');
            this.pagination.btnPrevious = buttons[0];
            this.pagination.btnNext = buttons[1];
            this.pagination.btnPrevious.addEventListener('click', this.ctrlMenu);
            this.pagination.btnNext.addEventListener('click', this.ctrlMenu);
            this.ctrlPagination();
        }
    }

    onScroll(event){
        if(this.menu === null){ return; }

        let verticalMenu = this.menu.querySelector("[id='navbarTogglerCourse']");
        
        if((verticalMenu) && (this.menu.parentElement.classList.contains("vertical")) && (window.scrollY < 0)){
            verticalMenu.style.marginTop = `${window.scrollY}px`;
        }
    }

    ctrlMenu(event){
        let menuItem, menuItemDesc;

        if(this.menu === null){ return;}

        if(!this.menu.classList.contains('menuM1') && !this.menu.classList.contains('menuM3')){ return;}

        let sectionId = event.target.getAttribute('href');
        
        M.recit.theme.recit2.Utils.setCookie('section', sectionId);

        menuItemDesc = this.menu.querySelector(`[href='${sectionId}']`);

        if(menuItemDesc === null){ return; }
        
        menuItem = menuItemDesc.parentElement.parentElement;

        // Reset menu level 1 selection.
        this.resetMenuSelection();

        menuItem.setAttribute("data-selected", "1");

        // If the menu level1 item has a branch then it also select it.
        let branch = this.menu.querySelector(`[data-parent-section='${sectionId}']`);
        if(branch !== null){
            branch.setAttribute("data-selected", "1");
        }
         
        // Select menu level2 item.
        if((menuItem.parentElement.getAttribute("id") === "level2")){
            menuItem.parentElement.setAttribute("data-selected", "1");
            menuItem.parentElement.parentElement.setAttribute("data-selected", "1");
        }

        this.ctrlMenuResponsive(this.menu, menuItem, menuItemDesc, branch);

        this.ctrlPagination();
    }

    ctrlMenuResponsive(menu, menuItem, menuItemDesc, branch){
        let itemMenuResponsive = menu.querySelector('.btn-menu-responsive');
        let sectionTitle = itemMenuResponsive.children[1];
        let sectionSubtitle = itemMenuResponsive.children[2];

        if (sectionTitle){
            //Make appear the title of the section in the responsive menu
            sectionTitle.innerHTML = menuItemDesc.textContent;

            if(branch !== null){
                //Make appear the title of the sous section in the responsive menu
                let sections = branch.getElementsByClassName('menu-item');
                for(let sec of sections){
                    if(sec.getAttribute('data-selected') === "1"){
                        let subsection = sec.getElementsByClassName('menu-item-desc');
                        sectionSubtitle.innerHTML = subsection.textContent;
                        break;
                    }
                }
            }
        }
        this.ctrlOpeningMenuResponsive(null);
    }

    //Open and close the menu responsive
    ctrlOpeningMenuResponsive(event){
        event = event || null;
        if(this.menu === null){ return; }

        let status = (event ? event.currentTarget.getAttribute('data-btn') : 'close');
        
        this.menu.setAttribute('data-status', status);
    }

    //Open and close the submenu responsive
    ctrlOpeningSubMenuResponsive(event, sectionId){
        if(this.menu === null){ return; }

        let branch = this.menu.querySelector(`[data-parent-section=${sectionId}]`);
        if(branch !== null){
            if(branch.getAttribute("data-status") === "open"){
                branch.setAttribute("data-status", "close");
                event.currentTarget.firstChild.classList.add("fa-plus");
                event.currentTarget.firstChild.classList.remove("fa-minus");
            }
            else{
                branch.setAttribute("data-status", "open");
                event.currentTarget.firstChild.classList.add("fa-minus");
                event.currentTarget.firstChild.classList.remove("fa-plus");
            }
        }
    }

    resetMenuSelection(){
        if(this.menu === null){ return;}

        let menu = this.menu;

        // Reset menu level 1 selection.
        let elems = menu.getElementsByClassName('menu-item');
        for(let el of elems){
            el.setAttribute("data-selected", "0");

            //set the negative(-) sign to plus(+) sign
            let levelSection = el.getElementsByClassName('menu-item-desc level-section active');
            if(levelSection.length >= 1){
                for(let item of levelSection){
                    let sectionIcon = el.getElementsByClassName('fas fa-minus');
                    for(let sec of sectionIcon){
                        item.classList.toggle("active");
                        sec.className = 'fas fa-plus';
                    }
                }
            }
        }

        // Reset menu level 2 selection.
        elems = menu.querySelectorAll('[data-parent-section]');
        for(let el of elems){
            el.setAttribute("data-selected", "0");
        }
    }

    ctrlPagination(){
        if(this.pagination.placeholder === null){ return; }
      
        let currentSection = M.recit.theme.recit2.Utils.getCookie('section');
        
        let iSection = 0;
        for(iSection = 0; iSection < this.sectionList.length; iSection++){
            if(this.sectionList[iSection].getAttribute('href') === currentSection){
                break;
            }
        }

        if(!this.sectionList[iSection]){ return; }
        
        if(iSection <= 0){
            this.pagination.btnPrevious.classList.add("disabled");
        }
        else{
            this.pagination.btnPrevious.classList.remove("disabled");
            this.pagination.btnPrevious.setAttribute('href', this.sectionList[iSection-1].getAttribute('href'));
        }
        if(iSection >= this.sectionList.length - 1){
            this.pagination.btnNext.classList.add("disabled");
        }
        else{
            this.pagination.btnNext.classList.remove("disabled");
            this.pagination.btnNext.setAttribute('href', this.sectionList[iSection+1].getAttribute('href'));
        }
    }
}