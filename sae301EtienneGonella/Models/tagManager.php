<?php

/**
* Définition d'une classe permettant de gérer les tags
*   en relation avec la base de données	
*/
class TagManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}

	/**
	* retourne l'ensemble des tags présents dans la bdd
	* @return Tag[]
	*/
	public function getListTagsProjet($idProjet) {
		$tags = array();
		$req = 'SELECT tag.id_tag,nom_tag FROM tag JOIN est ON tag.id_tag = est.id_tag JOIN projet ON projet.id_projet = est.id_projet WHERE est.id_projet = ?';
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
			$tags[] = new Tag($donnees);
		}
		return $tags;
	}

	public function getListTags() {
		$req = 'SELECT id_tag,nom_tag FROM tag';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(	);
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		$tags = array();
		while ($donnees = $stmt->fetch())
		{
			$tags[] = new Tag($donnees);
		}
		return $tags;
	}

}

 ?>