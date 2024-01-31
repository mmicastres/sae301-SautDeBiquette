<?php

/**
* Définition d'une classe permettant de gérer les sources
*   en relation avec la base de données	
*/
class SourceManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une source dans la BD
	* @param Source à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addSource(Source $source) { 
        
        // calcul d'un nouveau code source non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_source) AS maximum FROM source");
		$stmt->execute();
		$source->setIdSource($stmt->fetchColumn()+1);
		// requete d'ajout dans la BD
		$req = "INSERT INTO source (id_source, nom_source,id_projet) VALUES (?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($source->getIdSource(),$source->getNomSource(),$source->getIdProjet()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	public function addSourceProjet(Source $source,$projet) { 
        
        // calcul d'un nouveau code source non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_source) AS maximum FROM source");
		$stmt->execute();
		$source->setIdSource($stmt->fetchColumn()+1);
		// requete d'ajout dans la BD
		$req = "INSERT INTO source (id_source, nom_source,id_projet) VALUES (?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($source->getIdSource(),$source->getNomSource(),$projet->getIdProjet()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une source dans la base de données depuis un idProjet
	* @param int 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteSourceProjet(int $idProjet) : bool {
		$req = "DELETE FROM source WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idProjet));
	}


	/**
	* retourne l'ensemble des sources présents dans un projet
	* @return Source[]
	*/
	public function getListSourceProjet(int $idProjet) {
		$sources = array();
		$req = 'SELECT id_source, nom_source FROM source WHERE id_projet = ?';
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
			$sources[] = new Source($donnees);
		}
		return $sources;
	}

}

 ?>