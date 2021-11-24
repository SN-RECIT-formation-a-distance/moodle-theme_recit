
define(['jquery'], function($) {
    'use strict';
    M.recit.theme.ThemeRecit2.createInstance();
});

M.recit = M.recit || {};
M.recit.theme = M.recit.theme || {};

M.recit.theme.ThemeRecit2 = class{
    constructor(){
        this.ctrlShortcuts = this.ctrlShortcuts.bind(this);
        this.ctrlFullScreen = this.ctrlFullScreen.bind(this);

        this.navSections = new M.recit.theme.NavSections();
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
M.recit.theme.ThemeRecit2.instance = null;

M.recit.theme.ThemeRecit2.createInstance = function(){
    if(M.recit.theme.ThemeRecit2.instance === null){
        M.recit.theme.ThemeRecit2.instance = new M.recit.theme.ThemeRecit2();
    }
}

M.recit.theme.NavSections = class{
    constructor(){
        window.onscroll = this.onScroll.bind(this);

        this.ctrlMenu = this.ctrlMenu.bind(this);

        this.menu = null;

        this.init();
    }

    init(){       
        this.menu = document.getElementById("nav-sections");

        if(this.menu){
            let sections = this.menu.querySelectorAll('a[data-section]');

            for(let section of sections){
                section.addEventListener('click', this.ctrlMenu);
            }
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

        let sectionId = event.target.getAttribute('data-section');
        menuItemDesc = this.menu.querySelector(`[data-section=${sectionId}]`);

        if(menuItemDesc === null){ return; }
        
        menuItem = menuItemDesc.parentElement.parentElement;

        // Reset menu level 1 selection.
        this.resetMenuSelection();

        menuItem.setAttribute("data-selected", "1");

        // If the menu level1 item has a branch then it also select it.
        let branch = this.menu.querySelector(`[data-parent-section=${sectionId}]`);
        if(branch !== null){
            branch.setAttribute("data-selected", "1");
        }
         
        // Select menu level2 item.
        if((menuItem.parentElement.getAttribute("id") === "level2")){
            menuItem.parentElement.setAttribute("data-selected", "1");
            menuItem.parentElement.parentElement.setAttribute("data-selected", "1");
        }

        this.ctrlMenuResponsive(this.menu, menuItem, menuItemDesc, branch);
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
}