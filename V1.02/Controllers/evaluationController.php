<?php
include "Modules/evaluation.php";
include "Models/evaluationManager.php";

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class EvaluationController{

	private $evaluationManager; // instance du manager
    private $twig;

	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/

	public function __construct($db, $twig){

		$this->evaluationManager = new EvaluationManager($db);
		$this->twig = $twig;
	}

	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */
	


    function newEvaluation($idUtilisateur){
		$evaluation = new Evaluation($_POST);
		$ok = $this->evaluationManager->addEvaluation($evaluation,$idUtilisateur);
		if ($ok){
			$notif = "Commentaire enregistré ! <br/>";
		}
		else {
			$notif = "Erreur ! <br/> Veuillez réessayer";
		}
		echo $this->twig->render('notification.html.twig', array('acces' => $_SESSION['acces'], 'notif' => $notif)); 	
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

}
