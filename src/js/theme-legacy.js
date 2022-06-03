//RECIT Editor JS from before Moodle Approval



document.body.addEventListener('click',function(e){
    if(e.target && e.target.classList.contains('videobtn')){
        let url = e.target.getAttribute('data-videourl');
        if (url){
            let iframe = document.createElement('iframe');
            iframe.src = url;
            new M.recit.reciteditor.Popup(iframe);
        }
        e.preventDefault();
    }else if(e.target && e.target.classList.contains('r_img-popup')){
        let url = e.target.src;
        if (url){
            let img = document.createElement('img');
            img.src = url;
            new M.recit.reciteditor.Popup(img);
        }
        e.preventDefault();
    }else if(e.target && e.target.matches('.flipcard2 *')){ //Check if user clicked on a flipcard or its children
        let el = e.target;
        while (el = el.parentElement){
            if (el.classList.contains('flipcard2')){
                break;
            }
        }
        if (!el) return;
        if(el.classList.contains("hover2")){
            el.classList.remove('hover2');
        }else{
            el.classList.add('hover2');
        }
        e.preventDefault();
    }
});