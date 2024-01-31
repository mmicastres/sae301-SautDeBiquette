<?php

/**
* Définition d'une classe permettant de gérer les Est
*   en relation avec la base de données	
*/
class EstManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une est dans la BD
	* @param Est à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addEst(Est $est, $projet) { 
           
		// requete d'ajout dans la BD
		$req = "INSERT INTO est (id_projet, id_tag) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($projet->getIdProjet(),$est->getIdTag()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'un est dans la base de données depuis un idProjet
	* @param int  
	* @return boolean true si suppression, false sinon
	*/
	public function deleteEstProjet(int $idProjet) : bool {
		$req = "DELETE FROM est WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idProjet));
	}

        /**
	* suppression d'un est dans la base de données (quand on supprime un tag)
	* @param Est 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteEstTag(Est $est) : bool {
		$req = "DELETE FROM est WHERE id_tag = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($projet->getIdTag()));
	}

    

	/**
	* retourne l'ensemble des est présents dans la bdd pour un tag
	* @return Est[]
	*/
	public function getListEstTag(in $idTag) {
		$ests = array();
		$req = 'SELECT id_projet, id_tag FROM est WHERE id_tag = ?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idTag));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$ests[] = new Est($donnees);
		}
		return $ests;
	}

    /**
	* retourne l'ensemble des est présents dans la bdd pour un projet
	* @return Est[]
	*/
    public function getEstProjet(int $idProjet){
		$ests = array();
		$req = 'SELECT id_projet, id_tag FROM est WHERE id_Projet = ?';
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
			$ests[] = new Est($donnees);
		}
		return $ests;
	}
}

 ?>