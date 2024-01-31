<?php

/**
* Définition d'une classe permettant de gérer tout ceux qui participent
*   en relation avec la base de données	
*/
class ParticipeManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une participation dans la BD
	* @param participe à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addParticipe(Participe $participe, Projet $projet) { 
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO participe (id_projet,id_utilisateur) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($projet->getIdProjet(), $participe->getIdUtilisateur()));			
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une participation dans la base de données depuis un idProjet
	* @param Int 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteParticipeProjet(int $idProjet) : bool {
		$req = "DELETE FROM participe WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idProjet));
	}
		
/**
	* suppression d'une participation dans la base de données depuis un utilisateur
	* @param Participe 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteParticipeUtilisateur(int $idUtilisateur) : bool {
		$req = "DELETE FROM participe WHERE id_utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idUtilisateur));
	}
		
	/**
	* retourne l'ensemble des Participations présents dans la BD pour un projet en particulier-
	* @return Participe[]
	*/
	public function getListParticipe(int $idProjet) {
		$participes = array();
		$req = 'SELECT id_utilisateur FROM participe WHERE id_projet = ?';
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
			$participes[] = new Participe($donnees);
		}
		return $participes;
	}

}

 ?>