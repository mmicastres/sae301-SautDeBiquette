<?php

/**
* Définition d'une classe permettant de gérer les Evaluations 
*   en relation avec la base de données	
*/
class EvaluationManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une evaluation dans la BD
	* @param evaluation à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addEvaluation(Evaluation $evaluation,$idUtilisateur) { 
		// calcul d'un nouveau code d'itineraire non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(id_evaluation) AS maximum FROM evaluation");
		$stmt->execute();
		$evaluation->setidEvaluation($stmt->fetchColumn()+1);
		$evaluation->setidUtilisateur($idUtilisateur);
		// requete d'ajout dans la BD
		$req = "INSERT INTO evaluation (id_evaluation,texte,note,id_projet,id_utilisateur) VALUES (?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($evaluation->getIdevaluation(), $evaluation->getTexte(), $evaluation->getNote(), $evaluation->getIdProjet(), $evaluation->getIdUtilisateur()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        


		
	/**
	* suppression d'une evaluation dans la base de données depuis un idProjet
	* @param Int 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteEvaluationProjet(int $idProjet) : bool {
		$req = "DELETE FROM evaluation WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idProjet));
	}
		
	
			/**
	* suppression d'une evaluation dans la base de données depuis un idUtilisateur
	* @param Evaluation 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteEvaluationUtilisateur(int $idUtilisateur) : bool {
		$req = "DELETE FROM evaluation WHERE id_utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idUtilisateur));
	}
		
	
	/**
	* retourne l'ensemble des Evaluations présents dans la BD 
	* @return Evaluationj[]
	*/
	public function getListEvaluations(int $idProjet) {
		$evaluations = array();
		$req = 'SELECT id_evaluation,texte,note,id_projet,evaluation.id_utilisateur, nom, prenom FROM evaluation JOIN utilisateur ON evaluation.id_utilisateur = utilisateur.id_utilisateur WHERE id_projet = ?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$evaluations[] = new Evaluation($donnees);
		}
		return $evaluations;
	}


	// Peut être plus tard
// 	/**
// 	* modification d'un evaluation dans la BD
// 	* @param Evaluation
// 	* @return boolean 
// 	*/

// 	public function update(Evaluation $evaluation) : bool {
// 		$req = "UPDATE evaluation SET texte = :texte, "
// 					. "note = :note, "
// 					. " WHERE id_evaluation = :id_evaluation";


// 		$stmt = $this->_db->prepare($req);
// 		$stmt->execute(array(":texte" => $evaluation->getTexte(),
// 								":note" => $evaluation->getNote(),
// 								":id_evaluation" => $evaluation->getIdEvaluation() ));
// 		return $stmt->rowCount();
		
// 	}

}

 ?>