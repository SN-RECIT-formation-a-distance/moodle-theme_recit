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
        this.onSectionNav = onSectionNav;

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
        this.ctrlResponsive2 = this.ctrlResponsive2.bind(this); 

        window.addEventListener('resize', this.ctrlResponsive2);

        this.placeholder = placeholder;

        this.btnMenuResponsive = null;

        this.init();
    }

    init(){
        this.btnMenuResponsive = this.placeholder.querySelector('.btn-menu-responsive');

        this.ctrlResponsive2();
    }

    ctrl(event){
        let menuItem, menuItemDesc;

        menuItemDesc = this.placeholder.querySelector(`[href='${event.target.getAttribute("href")}']`);

        if(menuItemDesc === null){ return; }
        
        menuItem = menuItemDesc.parentElement.parentElement;
        
        // Reset menu level 1 selection.
        this.resetSelection();

        menuItem.setAttribute("data-selected", "1");

        // If the menu level1 item has a branch then it also select it.
        let branch = this.placeholder.querySelector(`[data-parent-section='${event.target.hash}']`);
        if(branch !== null){            
            branch.setAttribute("data-selected", "1");
        }
         
        // Select menu level2 item.
        let containMenuLevel2 = menuItem.parentElement.classList.contains("menu-level2");
        if(containMenuLevel2){
            menuItem.parentElement.setAttribute("data-selected", "1");
            menuItem.parentElement.parentElement.setAttribute("data-selected", "1");
        }

        if(!this.placeholder.classList.contains('responsive') && ((branch !== null) || (containMenuLevel2))){
            this.placeholder.style.marginBottom = "60px";
        }

        this.ctrlResponsive(menuItemDesc, branch);
    }

    ctrlResponsive2(){
        if((window.innerWidth > 1024) && (this.placeholder.offsetWidth <= M.recit.theme.recit2.Options.maxWidth)){
            this.placeholder.classList.remove('responsive');
        }
        else{
            this.placeholder.classList.add('responsive');
        }
    }

    ctrlResponsive(menuItemDesc, branch){
        if(this.btnMenuResponsive === null){ return;}

        let sectionTitle = this.btnMenuResponsive.children[1];
        let sectionSubtitle = this.btnMenuResponsive.children[2];

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
        this.ctrlOpeningResponsive(null);
    }

    //Open and close the menu responsive
    ctrlOpeningResponsive(event){
        event = event || null;
        if(this.placeholder === null){ return; }

        let status = (event ? event.currentTarget.getAttribute('data-btn') : 'close');
        
        this.placeholder.setAttribute('data-status', status);
    }

    //Open and close the submenu responsive
    ctrlOpeningSubMenuResponsive(event, sectionId){
        if(this.placeholder === null){ return; }

        let branch = this.placeholder.querySelector(`[data-parent-section='${sectionId}']`);
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

    resetSelection(){
        if(this.placeholder === null){ return;}

        this.placeholder.style.marginBottom = "0";

        // Reset menu level 1 selection.
        let elems = this.placeholder.getElementsByClassName('menu-item');
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
        elems = this.placeholder.querySelectorAll('[data-parent-section]');
        for(let el of elems){
            el.setAttribute("data-selected", "0");
        }
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

            if (this.isVertical() && el.classList.contains('dropdown')){
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