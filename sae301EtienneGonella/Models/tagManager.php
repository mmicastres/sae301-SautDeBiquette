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
	* ajout d'une tag dans la BD
	* @param Tag à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addTag(Tag $tag) { 
        
        // calcul d'un nouveau code tag non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_tag) AS maximum FROM tag");
		$stmt->execute();
		$tag->setIdTag($stmt->fetchColumn()+1);
        
		// requete d'ajout dans la BD
		$req = "INSERT INTO tag (id_tag, nom_tag) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($tag->getIdTag(),$tag->getNomTag()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une tag dans la base de données
	* @param Tag 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteTagProjet(Projet $projet) : bool {
		$req = "DELETE FROM tag WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($projet->getIdProjet()));
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