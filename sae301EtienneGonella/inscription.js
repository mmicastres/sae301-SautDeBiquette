// let inputs = document.querySelectorAll("form input")
// for (let i = 0; i < inputs.length; i++) {
//     inputs[i].addEventListener("focus", () => {
//         ex =  inputs[i].value
//         inputs[i].value = '';
//     })
//     inputs[i].addEventListener("blur", () => {
//         if (inputs[i].value == ''){
//             inputs[i].value = ex;
//         }

//     })
// }    

// J'avais écrit un bout de code qui avait la même fonction qu'un placeholder mais en utilisant la "value=''"


// Script vérification mail et mdp lors de l'inscription : 


// UNE PREMIERE VERSION, QUI EN SOIT FONCTIONNE MAIS LA VARIABLE PHP DU FORM NEST JAMAIS DEFINI DONC JAI UNE PAGE BLANCHE 
// let inscri = document.getElementById("inscriptionForm");

// inscri.addEventListener('submit', (event)=>{
//     event.preventDefault();
//     let mail = document.getElementById('mail').value;
//     let sep = mail.split("@");
//     if (sep[1]=='etu.iut-tlse3.fr' || sep[1]=='iut-tlse3.fr'){
//         inscri.submit();
//     }
//     else (
//         alert("L'email n'est pas au format de l'iut")
//     )

//     console.log("coucou");

// })




// DEUXIEME VERSION : AVEC LE BLUR SUR LE MAIL 

// let mail = document.getElementById('mail');
// let inscri = document.getElementById("inscription").classList
// mail.addEventListener("blur", () => {
//     let sep = mail.value.split("@");
//     if (sep[1] == 'etu.iut-tlse3.fr' || sep[1] == 'iut-tlse3.fr') {
//         inscri.remove('d-none');
//     }
//     else {
//         alert("L'email n'est pas au format de l'iut");
//         if(inscri == 'd-none'){

//         }
//         else{
//             inscri.add('d-none');
//         }
//     }
// })

// // MAINTENANT LE SCRRIPT POUR LES MDPS

// let mdp = document.getElementById('mdp');
// let confirmMdp = document.getElementById('confirmMdp');

// confirmMdp.addEventListener("blur", () => {

//     if (confirmMdp.value==mdp.value) {
//         inscri.remove('d-none');
//     }
//     else {
//         alert("Les mots de passe ne correspondent pas");
//         if(inscri == 'd-none'){

//         }
//         else{
//             inscri.add('d-none');
//         }
//     }
// })



// je récupère le mail du form
let mail = document.getElementById('mail');
// je récupère les mdp du form
let mdp = document.getElementById('mdp');
let confirmMdp = document.getElementById('confirmMdp');
// je récupère les classes de mon btn connexion pour le faire apparaître/disparaître
let inscri = document.getElementById("inscription").classList




// Ma fonction qui vérifie que l'e-mail est valide
// ATTENTION, si l'utilisateur clique sur un autre champs quand il a finit de remplir son mail, il est bloqué dans les alerts à l'infini
// Pour éviter ce problème, je mets mes champs de mot de passes en disabled tant que l'e-mail ne correspond pas, ça me fait une protection supplémentaire 
// (même si le js est modifiable côté client).
function verifMail() {
    if (mail.value) {

        let sep = mail.value.split("@");
        if (sep[1] == 'etu.iut-tlse3.fr' || sep[1] == 'iut-tlse3.fr') { //je vérifie que c'est une bonne email
            mdp.disabled = false; //je permet de rentrer un mot de passe
            confirmMdp.disabled = false; //je permet de rentrer la confirmation du mot de passe
            return true;
        }

        else {
            alert("L'email n'est pas au format de l'iut"); 
            if(mdp.disabled){
            }
            else{
                mdp.disabled = true; //si l'email n'est pas au bon format, je vérifie que le champs soit bien disabled, sinon je le redéfini comme tel
            }

            if(confirmMdp.disabled){

            }
            else{
                confirmMdp  .disabled = true; //Idem 
            }
            return false
        }
    }
    else {
        return false
    }

}

// MAINTENANT LE SCRRIPT POUR LES MDPS


// Ma fonction qui vérifie que les mdps sont correspondants
function verifMdp() {
    if (mdp.value && confirmMdp.value) {
        if (mdp.value == confirmMdp.value) {
            return true;
        }

        else {
            alert("Les mots de passe ne correspondent pas");
            return false
        }
    }
    else {
        return false
    }

}

// Ma fonction qui vient faire apparaitre le bouton inscription
function apparitionBtn() {
    if (verifMail() == true && verifMdp() == true) {
        inscri.remove('d-none') //Je vérifie que le mail soit au bon format ET que les mdps sont correspondants, j'affiche le bouton inscription
    }

    else {
        if (inscri == 'd-none') { //je vérifie qu'il n'est pas affiché dans le cas échéant 
        }

        else {
            inscri.add('d-none'); //S'il est affiché je le re-cache
        }
    }
}

// on applique la vérification à chaque fous que les input seront, en théorie, finit d'être rempli : 
mail.addEventListener("blur", apparitionBtn);
mdp.addEventListener("blur", apparitionBtn);
confirmMdp.addEventListener("blur", apparitionBtn);