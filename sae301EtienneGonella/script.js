let i = 0
let formsSupp=document.getElementsByClassName("suppElement")
while (i<formsSupp.length){
    formsSupp[i].addEventListener("submit", function (event) {
        if (confirm("Voulez vous vraiment supprimer?")){
        }
        else{
        event.preventDefault();
        }
}       
    )
    i++
    }


let j = 0
let formsAjout=document.getElementsByClassName("ajoutElement")
 while (j<formsAjout.length){
        formsAjout[j].addEventListener("submit", function (event) {
            if (confirm("Voulez vous vraiment ajouter?")){
            }
            else{
            event.preventDefault();
            }
    }       
        )
        j++
        }
    
