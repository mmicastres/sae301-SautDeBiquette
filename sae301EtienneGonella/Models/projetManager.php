<?php

/**
 * Définition d'une classe permettant de gérer les Projets 
 *   en relation avec la base de données	
 */
class ProjetManager
{

	private $_db; // Instance de PDO - objet de connexion au SGBD

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * ajout d'un projet dans la BD
	 * @param projet à ajouter
	 * @return obj qui est créer
	 */
	public function addProjet(Projet $projet): Projet
	{

		// calcul d'un nouvel id non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(id_projet) AS maximum FROM projet");
		$stmt->execute();
		$projet->setIdProjet($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO projet (id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie) VALUES (?,?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($projet->getIdProjet(), $projet->getTitre(), $projet->getDescription(), $projet->getImgProjet(), $projet->getLienDemo(), $projet->getAnnee(), $projet->getIdRessource(), $projet->getIdCategorie()));
		// pour debuguer les requêtes SQL	
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $projet;
	}


	/**
	 * suppression d'un projet dans la base de données
	 * @param int $idProjet 
	 * @return boolean true si suppression, false sinon
	 */
	public function delete(int $idProjet): bool
	{
		$req = "DELETE FROM projet WHERE id_projet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idProjet));
	}

	/**
	 * echerche dans la BD d'un projet à partir de son id
	 * @param int $idProjet 
	 * @return Projet 
	 */
	public function getProjet(int $idProjet): Projet
	{
		$req = 'SELECT id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie FROM projet WHERE id_projet=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$projet = new Projet($stmt->fetch());
		return $projet;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD 
	 * @return Projet[]
	 */
	public function getListProjet()
	{
		$projets = array();
		$req = 'SELECT id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie FROM projet';
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD pour un utilisateur
	 * @param int idUtilisateur
	 * @return Projet[]
	 */
	public function getListProjetsUtilisateur(int $idUtilisateur)
	{
		$projets = array();
		$req = 'SELECT projet.id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie FROM projet JOIN participe ON projet.id_projet = participe.id_projet WHERE id_utilisateur=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idUtilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}


	/**
	 * retourne l'ensemble des Projets présents dans la BD pour une ressource
	 * @param int idUtilisateur
	 * @return Projet[]
	 */
	public function getListProjetsRessource(int $idRessource)
	{
		$projets = array();
		$req = 'SELECT id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie FROM projet  WHERE id_ressource=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idRessource));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}



	/**
	 * méthode de recherche d'un prokjet dans la BD à partir des critères passés en paramètres
	 * @param string $titre
	 * @param string $description
	 * @return Projet[]
	 */
	public function searchProjet(string $recherche)
	{
		$req = "SELECT id_projet,titre,description,img_projet,lien_demo,annee,projet.id_ressource,projet.id_categorie FROM projet 
		JOIN ressource ON projet.id_ressource = ressource.id_ressource 
		JOIN sae ON ressource.id_sae = sae.id_sae 
		JOIN categorie ON projet.id_categorie = categorie.id_categorie 
		WHERE titre LIKE '%" . $recherche . "%' 
		OR description LIKE '%" . $recherche . "%'
		OR annee LIKE '%" . $recherche . "%' 
		OR intitule LIKE '%" . $recherche . "%' 
		OR nom_sae LIKE '%" . $recherche . "%' 
		OR nom_categorie LIKE '%" . $recherche . "%'";

		// execution de la requete				
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$projets = array();
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	// Une fonction que j'avais créer pour pouvoir effectuer une recherche dans les projets d'un utilisateur donné
	// public function searchProjetUtilisateur(string $recherche, int $idUtilisateur)
	// {
	// 	$req = "SELECT id_projet,titre,description,img_projet,lien_demo,annee,id_ressource,id_categorie FROM projet WHERE id_utilisateur = ? AND titre LIKE '%" . $recherche . "%'OR description LIKE '%" . $recherche . "%' ";

	// 	// execution de la requete				
	// 	$stmt = $this->_db->prepare($req);
	// 	$stmt->execute(array($idUtilisateur));
	// 	// pour debuguer les requêtes SQL
	// 	$errorInfo = $stmt->errorInfo();
	// 	if ($errorInfo[0] != 0) {
	// 		print_r($errorInfo);
	// 	}
	// 	$projets = array();
	// 	while ($donnees = $stmt->fetch()) {
	// 		$projets[] = new Projet($donnees);
	// 	}
	// 	return $projets;
	// }

	/**
	 * modification d'un projet dans la BD
	 * @param Projet
	 * @return boolean 
	 */
	public function update(Projet $projet): bool
	{
		$req = "UPDATE projet SET titre = ?,
					description = ?, 
					img_projet = ?, 
					lien_demo  = ?, 
					annee = ?, 
					id_ressource = ?, 
					id_categorie= ?
					WHERE id_projet = ?";

		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(
			$projet->getTitre(),
			$projet->getDescription(),
			$projet->getImgProjet(),
			$projet->getLienDemo(),
			$projet->getAnnee(),
			$projet->getIdRessource(),
			$projet->getIdCategorie(),
			$projet->getIdProjet()
		));
		return $stmt->rowCount();
	}
}
