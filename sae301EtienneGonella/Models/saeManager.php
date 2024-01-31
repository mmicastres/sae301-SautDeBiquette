<?php

/**
* Définition d'une classe permettant de gérer les saés
*   en relation avec la base de données	
*/
class SaeManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'une sae dans la BD
	* @param Sae à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function addSae(Sae $sae) { 
        
        // calcul d'un nouveau code sae non déja utilisé = Maximum + 1

		$stmt = $this->_db->prepare("SELECT max(id_sae) AS maximum FROM sae");
		$stmt->execute();
		$sae->setIdSae($stmt->fetchColumn()+1);
        
		// requete d'ajout dans la BD
		$req = "INSERT INTO sae (id_sae, nom_sae) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($sae->getIdSae(),$sae->getNomSae()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}
        
    /**
	* suppression d'une sae dans la base de données
	* @param Sae 
	* @return boolean true si suppression, false sinon
	*/
	public function deleteSae(int $idSae) : bool {
		$req = "DELETE FROM sae WHERE id_sae = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idSae));	
	}

    

	/**
	* retourne l'ensemble des saes présents dans la bdd
	* @return Sae[]
	*/
	public function getListSae() {
		$saes = array();
		$req = 'SELECT id_sae,nom_sae FROM sae';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array());
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$saes[] = new Sae($donnees);
		}
		return $saes;
	}


		/**
	* retourne l'ensemble des saes présents dans la bdd
	* @return Sae[]
	*/
	public function getSaeProjet(int $idProjet) {
		$req = 'SELECT id_sae,nom_sae FROM sae';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		$donnees = $stmt->fetch();
		
		$sae = new Sae($donnees);
		
		return $sae;
	}

}

 ?>