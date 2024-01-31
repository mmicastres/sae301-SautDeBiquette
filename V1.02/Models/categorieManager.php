<?php

/**
* Définition d'une classe permettant de gérer les catégories
*   en relation avec la base de données	
*/
class CategorieManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une categorie dans la BD
	* @param Categorie à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addCategorie(Categorie $categorie) { 
        
        // calcul d'un nouveau code categorie non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_categorie) AS maximum FROM categorie");
		$stmt->execute();
		$categorie->setIdCategorie($stmt->fetchColumn()+1);
        
		// requete d'ajout dans la BD
		$req = "INSERT INTO categorie (id_categorie, nom_categorie) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($categorie->getIdCategorie(),$categorie->getNomCategorie()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une categorie dans la base de données
	* @param Categorie 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteCategorie($idCategorie) : bool {
		$req = "DELETE FROM categorie WHERE id_categorie = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idCategorie));
	}

    
	/**
	* retourne la catégorie pour un projet en particulier
	* @return Categorie[]
	*/
	public function getCategorieProjet(int $idProjet) {
		$req = 'SELECT categorie.id_categorie, nom_categorie  FROM categorie JOIN projet ON categorie.id_categorie = projet.id_categorie WHERE id_projet = ?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		$donnees = $stmt->fetch();
		
		$categorie = new Categorie($donnees);
		
		return $categorie;
	}


	/**
	* retourne l'ensemble des categories présents dans la bdd
	* @return Categorie[]
	*/
	public function getListCategorie() {
		$categories = array();
		$req = 'SELECT id_categorie,nom_categorie FROM categorie';
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$categories[] = new Categorie($donnees);
		}
		return $categories;
	}

}

 ?>