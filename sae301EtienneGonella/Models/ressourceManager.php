	<?php

/**
* Définition d'une classe permettant de gérer les saés
*   en relation avec la base de données	
*/
class RessourceManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une ressource dans la BD
	* @param Ressource à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addRessource(Ressource $ressource) { 
        
        // calcul d'un nouveau code ressource non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_ressource) AS maximum FROM ressource");
		$stmt->execute();
		$ressource->setIdRessource($stmt->fetchColumn()+1);
        
		// requete d'ajout dans la BD
		$req = "INSERT INTO ressource (id_ressource, semestre, intitule, numero_ressource, id_sae) VALUES (?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($ressource->getIdRessource(),$ressource->getSemestre(),$ressource->getIntitule(),$ressource->getNumeroRessource(),$ressource->getIdSae()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une ressource dans la base de données
	* @param Ressource 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteRessource(int $idRessource) : bool {
		$req = "DELETE FROM ressource WHERE id_ressource = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idRessource));
	}

    

	/**
	* retourne l'ensemble des ressources présents dans la bdd
	* @return Ressource[]
	*/
	public function getListRessource() {
		$ressources = array();
		$req = 'SELECT id_ressource, semestre, intitule, numero_ressource, ressource.id_sae, nom_sae  FROM ressource JOIN sae ON ressource.id_sae = sae.id_sae';
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
			$ressources[] = new Ressource($donnees);
		}
		return $ressources;
	}

	/**
	* retourne les ressources pour un projet 
	* @return Ressource[]
	*/
	public function getRessourceProjet(int $idProjet){
		$req = 'SELECT ressource.id_ressource, semestre, intitule, numero_ressource, ressource.id_sae, nom_sae FROM ressource JOIN projet ON ressource.id_ressource = projet.id_ressource JOIN sae ON ressource.id_sae = sae.id_sae WHERE id_projet = ?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
	$donnees = $stmt->fetch();
		
		$ressource= new Ressource($donnees);
		
		return $ressource;
	}

	
}

 ?>