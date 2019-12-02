
/*M.recit = M.recit || {};
M.recit.course = M.recit.course || {};
M.recit.course.theme = M.recit.course.theme || {};
M.recit.course.theme.ThemeRecit = class{
    constructor(){
        this.navbar = null;

        this.getBtnBar = this.getBtnBar.bind(this);

        this.init();
    }

    init(){
        this.navbar = document.getElementById("recitNavbarTop");
       // this.setBtnModeEdition();
    }

    getBtnBar(pos){
        let result = this.navbar.querySelectorAll(`[data-pos='${pos}']`);

        return (result.length >= 1 ? result[0] : null);
    }

    createNavItem(faIcon, desc, url){
        let li = document.createElement("li");
        let link = document.createElement("a");
        let i = document.createElement("i");

        li.appendChild(link);
        li.classList.add("nav-item");
        
        
        link.classList.add("nav-link");
        link.setAttribute("href", url);
        link.setAttribute("title", desc);
        link.setAttribute("innerHTML", desc);
        link.appendChild(i);

        i.classList.add("fa");
        i.classList.add(faIcon);

        return li;
    }

    setBtnModeEdition(){
        let urlParams = new URLSearchParams(window.location.search);
        let url = `${window.location.origin}${window.location.pathname}${window.location.search}`;
        url += `&sesskey=${M.cfg.sesskey}`;
        url += `&edit=on`;
        urlParams.get('id');

        let btnBar = this.getBtnBar('r');

        btnBar.appendChild(this.createNavItem("fa-pencil-alt", "bbb", url));
    }
}

M.recit.course.theme.ThemeRecit.instance = new M.recit.course.theme.ThemeRecit(); 
*/