<?php
// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";

include "Controllers/projetController.php";
include "Controllers/utilisateurController.php";
include "Controllers/evaluationController.php";

$projController = new ProjetController($bdd, $twig);
$utiController = new UtilisateurController($bdd, $twig);
$evalController = new EvaluationController($bdd, $twig);


// texte du message
$message = "";

// ============================== connexion / deconnexion - sessions ==================

// si la variable de session n'existe pas, on la crée
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
  $_SESSION['idUtilisateur'] = "non";
}

// formulaire de connexion
if (isset($_GET["action"])  && $_GET["action"] == "login") {
  $utiController->utilisateurFormulaire();
}

// click sur le bouton connexion
if (isset($_POST["connexion"])) {
  $message = $utiController->utilisateurConnexion($_POST);
}


// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $message = $utiController->utilisateurDeconnexion();
}


// click sur le bouton Inscription
if (isset($_POST["inscription"])) {
  $utiController->utilisateurInscription($_POST);
}



// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  $projController->listeProjets();
}

// ============================== gestion des projets ==================

// liste des projets dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"] == "accueil") {
  $projController->listeProjets();
}

// liste de mes projets dans un écran "mon espace" et de mes infos personnelles
if (isset($_GET["action"]) && $_GET["action"] == "monespace") {
  $utiController->utilisateurInfos($_SESSION['idUtilisateur']);
}



// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
//  https://.../index/php?action=ajout
// version 0 : le projet est rattaché automatiquement à un utilisateur déjà présent dans la BD
//              l'idUtilisateur est en champ caché dans le formulaire
if (isset($_GET["action"]) && $_GET["action"] == "ajoutProjet") {
  $projController->formAjoutProjet();
}

// ajout du projet dans la base
// --> au clic sur le bouton "valider_ajout" du form précédent
if (isset($_POST["valider_ajout"])) {
  $projController->ajoutProjet();
}



// supression d'un projet dans la base
// --> au clic sur le bouton "valider_supp" du form précédent
if (isset($_POST["supprimerProjet"])) {
  $projController->suppProjet($_POST['id_projet']);
}

// supression d'un projet dans la base
// --> au clic sur le bouton "valider_supp" du form précédent
if (isset($_POST["modifierProjet"])) {
  $projController->saisieModProjet();
}

//modification d'un projet : enregistrement dans la bd
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $projController->modProjet();
}





// affichage des détails d'un projet
if (isset($_POST["detailsProjet"])) {
  $projController->detailProjet();
}

// Envoie d'un commentaire
if (isset($_POST["commenter"])) {
  $evalController->newEvaluation($_SESSION['idUtilisateur']);
}


// recherche des projets : construction de la requete SQL en fonction des critères 
// --> au clic sur le bouton "recherche" 

if (isset($_POST["rechercheProjets"])) {
  $projController->rechercheProjet();
}


// ============================== gestion Administrateur ==================

// Accès à l'espace de gestion administrateur
if (isset($_GET["action"]) && $_GET["action"] == "gestion") {
  $utiController->utilisateurGestion();
}

// Affichage de la liste pour la modif des Catégories
if (isset($_POST["modifCategories"])) {
  $projController->getListCategorie();
}
// Suppression d'une catégorie
if (isset($_POST["supprimerCategorie"])) {
  $projController->suppCategorie($_POST);
}
// Ajout d'une catégorie
if (isset($_POST["ajouterCategorie"])) {
  $projController->addCategorie($_POST);
}

// Affichage de la liste pour la modif des Ressources et des Saé
if (isset($_POST["modifRessources"])){
  $projController->getListRessource();
}

// Suppression d'une Ressource
if (isset($_POST["supprimerRessource"])) {
  $projController->suppRessource($_POST);
}

// Suppression d'une Sae
if (isset($_POST["supprimerSae"])) {
  $projController->suppSae($_POST);
}

// Ajout d'une Ressource
if (isset($_POST["ajouterRessource"])) {
  $projController->addRessource($_POST);
}

// Ajout d'une Sae
if (isset($_POST["ajouterSae"])) {
  $projController->addSae($_POST);
}

// Affichage de la liste pour la modif des Utilisateurs
if (isset($_POST["modifUtilisateurs"])) {
  $utiController->getListUtilisateur();
}

// Ajout d'un Utilisateur
if (isset($_POST["ajouterUtilisateur"])) {
  $utiController->utilisateurAddAdmin($_POST);
}

// Suppression d'un utilisateur
if (isset($_POST["supprimerUtilisateur"])) {
  $utiController->utilisateurSuppUtilisateurAdmin($_POST);
}
