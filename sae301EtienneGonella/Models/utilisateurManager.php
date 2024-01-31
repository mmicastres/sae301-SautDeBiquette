<?php

/**
 * Définition d'une classe permettant de gérer les Utilisateurs 
 * en relation avec la base de données
 *
 */

class UtilisateurManager
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
	 * ajout d'un utilisateur dans la BD
	 * @param utilisateur à ajouter
	 * @return int true si l'ajout a bien eu lieu, false sinon
	 */
	public function addUtilisateur(Utilisateur $utilisateur)
	{
		// Vérification de l'adresse mail
		// Ici on sépare l'adresse entrée grâce au @ 
		$mail = explode("@", $utilisateur->getMail());
		// On cherche ensuite les deux adresses acceptées dans les mails IUT : 
		$mail1 = array_search('iut-tlse3.fr', $mail);
		$mail2 = array_search('etu.iut-tlse3.fr', $mail);
		// Puis on met une condition : si un des deux types d'adresses et trouvé, alors on excecute l'inscription, sinon on revoit un message d'erreur
		if ($mail1 != false or $mail2 != false) {

			// calcul d'un nouveau code d'utilisateur non déja utilisé = Maximum + 1
			$stmt = $this->_db->prepare("SELECT max(id_utilisateur) AS maximum FROM utilisateur");
			$stmt->execute();
			$utilisateur->setIdUtilisateur($stmt->fetchColumn() + 1);
			// requete d'ajout dans la BD
			$req = "INSERT INTO utilisateur (id_utilisateur,nom,prenom,id_iut,mail,mdp,administrateur,pdp) 	VALUES (?,?,?,?,?,?,?,?)";
			$stmt = $this->_db->prepare($req);
			$res  = $stmt->execute(array($utilisateur->getIdUtilisateur(), $utilisateur->getNom(), $utilisateur->getPrenom(), $utilisateur->getIdIut(), $utilisateur->getMail(), $utilisateur->getMdp(), 0, $utilisateur->getPdp()));

			//On remarquera que l'initialisation d'admin est sur 0 par défault. 
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}

			return $res;
		}
	}

	/**
	 * verification de l'identité d'un membre (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return utilisateur si authentification ok, false sinon
	 */
	public function verif_identification($login, $password)
	{

		$req = "SELECT id_utilisateur, nom, prenom,administrateur FROM utilisateur WHERE mail=:login and mdp=:password ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));
		if ($data = $stmt->fetch()) {
			$utilisateur = new Utilisateur($data);
			return $utilisateur;
		} else return false;
	}
	// Getters 
	public function getUtilisateur($idUtilisateur): Utilisateur
	{
		$req = 'SELECT id_utilisateur, nom,prenom,id_iut,mail,mdp,administrateur,pdp FROM utilisateur WHERE id_utilisateur=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idUtilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$utilisateur = new Utilisateur($stmt->fetch());
		return $utilisateur;
	}


	/**
	 * renvoie la liste des utilisateurs 
	 * @param int $idProjet
	 * @return utilisateur 
	 */
	public function getListUtilisateur()
	{
		$utilisateurs = array();
		$req = 'SELECT id_utilisateur, nom, prenom, id_iut,mail,mdp,administrateur,pdp FROM utilisateur';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array());
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$utilisateurs[] = new Utilisateur($donnees);
		}
		return $utilisateurs;
	}

	/**
	 * renvoie la liste des utilisateurs liés à un projet
	 * @param int $idProjet
	 * @return utilisateur 
	 */
	public function getListUtilisateurProjet(int $idProjet)
	{

		$utilisateurs = array();
		$req = 'SELECT utilisateur.id_utilisateur, nom, prenom, mail FROM utilisateur JOIN participe ON utilisateur.id_utilisateur = participe.id_utilisateur WHERE id_projet = ?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$utilisateurs[] = new Utilisateur($donnees);
		}
		return $utilisateurs;
	}

	/**
	 * suppression d'un projet dans la base de données
	 * @param int $idUtilisateur 
	 * @return boolean true si suppression, false sinon
	 */
	public function deleteUtilisateur($idUtilisateur): bool
	{
		$req = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($idUtilisateur));
	}
}
