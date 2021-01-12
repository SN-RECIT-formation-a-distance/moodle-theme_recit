function theme_recit_init_vars(_, settings){
    
        // affiche un message à l'utilisateur pour l'avertir qu'il va laisser le site Moodle
        if (settings.showleavingsitewarning){
        window.onclick = function(event){
            if(event.target.nodeName.toLowerCase() === "a"){
                if((event.target.host.toString().length > 0) && (event.target.host !== window.location.host)){
                    if(!confirm(M.str.theme_recit.msgleavingmoodle)){
                        event.stopPropagation();
                        event.preventDefault();
                    }
                }
            }
        }
    }
}