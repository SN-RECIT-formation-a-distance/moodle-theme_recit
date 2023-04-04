M.recit = M.recit || {};
M.recit.theme = M.recit.theme || {};
M.recit.theme.recit2 = M.recit.theme.recit2 || {};

M.recit.theme.recit2.SectionsNav = class{
    constructor(){        
        this.onSectionNav = this.onSectionNav.bind(this);
        this.curSection = null;
        this.observers = [];
        this.menu = null;
        this.pagination = null;
        
        this.init();
    }

    init(){
        let placeholder = document.getElementById("nav-sections");

        if(placeholder === null){
            return;
        }
        
        let sectionList = placeholder.querySelectorAll('a');

        this.pagination = new M.recit.theme.recit2.SectionPagination(sectionList, this.onSectionNav);

        if(placeholder.classList.contains('menuM1')){ 
            this.menu = new M.recit.theme.recit2.MenuM1(placeholder);
        }
        else if(placeholder.classList.contains('menuM5')){
            this.menu = new M.recit.theme.recit2.MenuM5(placeholder);
        }

        let sectionId = window.location.hash || M.recit.theme.recit2.Utils.getCookieCurSection() || '#section-0';
        for(let section of sectionList){
            section.addEventListener('click', this.onSectionNav);

            // if the user is the course page then it load automatically the section content. More than one listener could exist.
            if(section.hash === sectionId){
                this.curSection = section;

                // If the user is on the course page then it dispatch the link click (with all listeners)
                /*if(M.course){
                    section.click(); 
                }*/

                // we don't want to call click() to avoid unnecessary redirection to href link, we just want to call onSectionNav
                // if the user is on a module course page then we can't dispatch the link click (otherwise it will return to the course page)
                let tmp = document.createElement('a');
                tmp.setAttribute('href', section.getAttribute('href'));
                this.onSectionNav({target: tmp});
            }
        }
    }

    addOnSectionNavListener(callback){

        for(let o of this.observers){
            if(o === callback){
                return false;
            }
        }

        this.observers.push(callback);
        
        if(this.curSection){
            let tmp = document.createElement('a');
            tmp.setAttribute('href', this.curSection.getAttribute('href'));
            callback.call(this, {target: tmp, preventDefault: ()=>{}});
        }

        return true;
    }    

    onSectionNav(event){
        this.curSection = event.target;

        M.recit.theme.recit2.Utils.setCookieCurSection(this.curSection.hash);
       
        if(this.menu){
            this.menu.ctrl(event);
        }

        for(let o of this.observers){
            o.call(this, event);
        }
        
        this.pagination.ctrl(this.curSection.hash);
    }
}

M.recit.theme.recit2.SectionPagination = class{
    constructor(sectionList, onSectionNav){
        this.placeholder = null;
        this.sectionList = sectionList;
        this.btnPrevious = null;
        this.btnNext = null;
        this.onSectionNav = function(e){
            if (M.course){//If we're in an activity we do not want to load the section ajax. In an activity, M.course is null
                onSectionNav(e);
            }
        }

        this.init();
    }

    init(){
        this.placeholder = document.getElementById('sectionPagination');

        if(this.placeholder){
            let buttons = this.placeholder.querySelectorAll('a');
            this.btnPrevious = buttons[0];
            this.btnNext = buttons[1];
            this.btnPrevious.addEventListener('click', this.onSectionNav);
            this.btnNext.addEventListener('click', this.onSectionNav);
        }
    }

    ctrl(sectionId){
        if(this.placeholder === null){ return; }
      
        let iSection = 0;
        for(iSection = 0; iSection < this.sectionList.length; iSection++){
            if(this.sectionList[iSection].hash === sectionId){
                break;
            }
        }

        if(!this.sectionList[iSection]){ return; }
        
        if(iSection <= 0){
            this.btnPrevious.classList.add("disabled");
            this.btnPrevious.setAttribute('href', '#');
        }
        else{
            this.btnPrevious.classList.remove("disabled");
            this.btnPrevious.setAttribute('href', this.sectionList[iSection-1].getAttribute('href'));
        }
        if(iSection >= this.sectionList.length - 1){
            this.btnNext.classList.add("disabled");
            this.btnNext.setAttribute('href', '#');
        }
        else{
            this.btnNext.classList.remove("disabled");
            this.btnNext.setAttribute('href', this.sectionList[iSection+1].getAttribute('href'));
        }
    }
}

M.recit.theme.recit2.MenuM1 = class{
    constructor(placeholder){
        this.ctrl = this.ctrl.bind(this);
        this.init = this.init.bind(this);
        this.onSelect = this.onSelect.bind(this);
        this.onSelectNormal = this.onSelectNormal.bind(this);
        this.onSelectResponsive = this.onSelectResponsive.bind(this);
        this.onMenuCollapase = this.onMenuCollapase.bind(this);
        this.onSubMenuCollapase = this.onSubMenuCollapase.bind(this);
        window.onresize = this.onWindowResize.bind(this);

        this.placeholder = placeholder;
        this.menuSections = null;
        this.init();
    }

    init(){
        this.menuSections = document.getElementById('menu-sections');

        let btn = document.getElementById(`btn-menu-collapse`);
        btn.onclick = this.onMenuCollapase;

        let items = this.menuSections.querySelectorAll('[data-btn-submenu-collapse]');
        for(let item of items){
            item.onclick = this.onSubMenuCollapase;
        }

        this.onWindowResize();
    }

    onWindowResize(){
        if((window.innerWidth > 1024) && (!this.menuWidth || this.menuWidth <= M.recit.theme.recit2.Options.maxWidth)){
            this.placeholder.classList.remove('responsive');
            this.placeholder.classList.add('normal');
            this.menuSections.style.display = 'flex';

            let items = this.menuSections.querySelectorAll('[data-btn-submenu-collapse]');
            for(let item of items){
                item.nextElementSibling.classList.remove("d-flex");
            }

            if (!this.menuWidth){
                this.menuWidth = this.menuSections.offsetWidth; //Keep width in memory because if we add responsive, width will return 0 as it's display none
                this.onWindowResize(); //we added all classes so now we can see if menu is too large
            }
        }
        else{
            this.placeholder.classList.add('responsive');
            this.placeholder.classList.remove('normal');
            this.menuSections.style.display = 'none';
        }
    }

    ctrl(event){
        let sectionId = event.target.getAttribute("href");
        
        let href = this.placeholder.querySelector(`[href='${sectionId}']`);
        if(href !== null){
            this.onSelect(href);
            if(this.placeholder.classList.contains('responsive')){
                this.onSelectResponsive(href);
            }
            else{
                this.onSelectNormal(href);
            }
        }
    }

    onMenuCollapase(){
        this.menuSections.style.display = (this.menuSections.style.display === 'flex' ? 'none' : 'flex');

        let sectionId = M.recit.theme.recit2.Utils.getCookieCurSection();
        let tmp = document.createElement('a');
        tmp.setAttribute('href', sectionId);
        this.ctrl({target: tmp});
    }

    onSubMenuCollapase(event, forceOpen){
        let icon = event.currentTarget.firstElementChild;
        let submenu = event.currentTarget.nextElementSibling;
        
        if(forceOpen || icon.classList.contains("fa-plus")){
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
            submenu.classList.add("d-flex");
        }
        else{
            icon.classList.add('fa-plus');
            icon.classList.remove('fa-minus');
            submenu.classList.remove("d-flex");
        }
    }

    onSelect(href){
        let menuItem = href.parentElement;

        let items = this.placeholder.querySelectorAll(`li`);

        // restart menu
        for(let item of items){
            // unselect all items
            item.setAttribute('data-selected', '0');            
        }

        for(let item of items){
            // select menu itemn (level 1 or 2)
            if(item.isSameNode(menuItem)){
                menuItem.setAttribute('data-selected', '1');
            }

            // in case menuItem is level2 then we need to select its parent
            if(item.contains(menuItem)){
                item.setAttribute('data-selected', '1');
            }
        }
    }

    onSelectNormal(href){
        let menuItem = href.parentElement;

        // restart menu
        this.placeholder.style.marginBottom = "0px";

        let items = this.placeholder.querySelectorAll(`li`);

        for(let item of items){
            if(!menuItem.isSameNode(item) && (menuItem.contains(item) || item.contains(menuItem))){
                this.placeholder.style.marginBottom = "60px";
                break;
            }
        }
    }

    onSelectResponsive(href){
        let item = this.placeholder.querySelector(`.active-item`);

        item.innerHTML = href.innerHTML;

        if(href.parentElement.parentElement.previousElementSibling.hasAttribute('data-btn-submenu-collapse')){
            this.onSubMenuCollapase({currentTarget: href.parentElement.parentElement.previousElementSibling}, true);
        }

        this.menuSections.style.display = 'none';
    }
}

M.recit.theme.recit2.MenuM5 = class{
    constructor(placeholder){
        //window.onscroll = this.onScroll.bind(this);

        this.placeholder = placeholder;
        
        if (this.isVertical()){//If menu is vertical, prevent dropdowns from closing
            let els = placeholder.querySelectorAll('.dropdown-menu');
            for (let el of els){
                el.addEventListener("click", function(e){
                    e.stopPropagation();
                });
            }
        }
    }

    isVertical(){
        return this.placeholder.parentElement.classList.contains('vertical');
    }

    isMobile(){
        return window.innerWidth < 990;
    }

    /*onScroll(event){
         let verticalMenu = this.placeholder.querySelector("[id='navbarTogglerCourse']");
         
         if((verticalMenu) && (this.menu.parentElement.classList.contains("vertical")) && (window.scrollY < 0)){
             verticalMenu.style.marginTop = `${window.scrollY}px`;
         }
     }*/

    ctrl(event){
        let elems = this.placeholder.querySelectorAll('.menu-item');
        for(let el of elems){
            if(event.target.hash === el.firstElementChild.hash){
                el.setAttribute('data-selected', '1');
            }
            else{
                el.setAttribute("data-selected", "0");
            }

            if ((this.isVertical() || this.isMobile()) && el.classList.contains('dropdown')){
                let subelems = el.querySelectorAll('.dropdown-item');
                for(let subel of subelems){
                    if(event.target.hash === subel.hash){
                        subel.setAttribute('data-selected', '1');
                        if (!el.classList.contains('show')){
                            el.classList.add('show')
                            let menu = el.querySelector('.dropdown-menu');
                            if (menu){
                                menu.classList.add('show');
                            }
                        }
                    }
                    else{
                        subel.setAttribute("data-selected", "0");
                    }
                }
            }
        }
    }
}