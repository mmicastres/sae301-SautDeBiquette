<?php
include "Modules/utilisateur.php";
include "Models/utilisateurManager.php";


/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateurManager; // instance du manager
	private $projetManager; // instance du manager des projets
	private $participeManager; // instance du manager des projets
	private $evaluationManager; // instance du manager de projet
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->projetManager = new ProjetManager($db);
		$this->participeManager = new ParticipeManager($db);
		$this->evaluationManager = new EvaluationManager($db);
		$this->twig = $twig;
	}

	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurConnexion()
	{
		// verif du login et mot de passe
		// if ($_POST['login']=="user" && $_POST['passwd']=="pass")
		$utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($utilisateur != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idUtilisateur'] = $utilisateur->getIdUtilisateur();
			$notif = "Bonjour <br/>" . $utilisateur->getPrenom() . " " . $utilisateur->getNom() . "!";

			if ($utilisateur->getAdministrateur()) {
				$_SESSION['acces'] = 'admin'; // acces autorisé
				$notif = "Bonjour administrateur <br/>" . $utilisateur->getPrenom() . " " . $utilisateur->getNom() . "!";
			}
			echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif));
		} else { // acces non autorisé : variable de session acces = non
			$notif = "identification incorrecte";
			$_SESSION['acces'] = "non";
			echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif));
		}
	}

	/**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurDeconnexion()
	{
		$_SESSION['acces'] = "non"; // acces non autorisé
		$_SESSION['idUtilisateur'] = "non"; // On reprécise l'accès idUtilisateur sur "non" puisqu'on est plus connecté
		$notif = "vous êtes déconnecté";
		echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif));
	}
	function utilisateurAdd(){
		$utilisateur = new Utilisateur($_POST);

		if (isset($_FILES["pdp"])) {
			$uploaddir = "./img/pdp/";
			// on trace le chemin dans lequel le fichier va être stocké : dans l'odre -le dossier img, on le renomme avec -l'id de l'iut qui est unique et enfin -l'extension du fichier
			$uploadfile = $uploaddir . $utilisateur->getIdIut() . "." . pathinfo($_FILES["pdp"]["name"], PATHINFO_EXTENSION);
			if ($_FILES["pdp"]["error"] == UPLOAD_ERR_OK) {

				if (!move_uploaded_file($_FILES["pdp"]["tmp_name"], $uploadfile)) {
					echo "pb lors du telechargement de la photo de profil";
				}
				$utilisateur->setPdp($uploadfile);

				$ok = $this->utilisateurManager->addUtilisateur($utilisateur);
				return $ok;
	}}}
	// Permet de gérer l'inscription d'un utilisateur
	function utilisateurInscription()
	{			$ok = $this->utilisateurAdd();

				if ($ok) {
					$notif = "Inscription validée ! <br/> Veuillez maintenant vous connecter  <br/> <a class=-nav-link- href='?action=login'><button class='bg-purple-300 rounded text-white border-0 p-2 m-3 align-self-center'>Connexion</button></a>";
				} else {
					$notif = "Erreur lors de l'inscription ! <br/> Veuillez réessayer";
				}
				echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif));
			}
		
	function utilisateurAddAdmin()
	{	
		$this->utilisateurAdd();
		$this->getListUtilisateur();
	}

	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurFormulaire()
	{
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}


	// Permet de renvoyer les infos d'un utilisateur
	public function utilisateurInfos($idUtilisateur)
	{
		$utilisateur = $this->utilisateurManager->getUtilisateur($idUtilisateur);
		$projets = $this->projetManager->getListProjetsUtilisateur($idUtilisateur);
		$infosUtilisateur = true;
		echo $this->twig->render('projet_liste.html.twig', array('acces' => $_SESSION['acces'], 'projets' => $projets, 'utilisateur' => $utilisateur, 'infosUtilisateur' => $infosUtilisateur));
	}

	function utilisateurGestion()
	{

		if ($_SESSION['acces'] == 'admin') {
			echo $this->twig->render('gestion.html.twig', array('acces' => $_SESSION['acces']));
		} else {
			$notif = "Erreur lors de l'accès à l'espace adminisatreur";
			echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif));
		}
	}

	function getListUtilisateur()
	{
		$utilisateurs = $this->utilisateurManager->getListUtilisateur();
		$nom = 'Utilisateurs';
		echo $this->twig->render('gestion_liste.html.twig', array('acces' => $_SESSION['acces'], 'nom' => $nom, 'elements' => $utilisateurs));
	}


	function utilisateurSuppUtilisateurAdmin()
	{

		$this->participeManager->deleteParticipeUtilisateur($_POST['id_utilisateur']);
		$this->evaluationManager->deleteEvaluationUtilisateur($_POST['id_utilisateur']);
		$this->utilisateurManager->deleteUtilisateur($_POST['id_utilisateur']);

		$this->getListUtilisateur();
	}
}
