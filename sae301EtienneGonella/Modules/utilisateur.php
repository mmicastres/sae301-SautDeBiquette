<?php
/** 
* définition de la classe Utilisateur
*/
class Utilisateur {
        private int $_idUtilisateur;
        private string $_nom;
        private string $_prenom;
		private string $_idIut;
		private string $_mail;
		private string $_mdp;
		private bool $_administrateur;
		private string $_pdp;
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_utilisateur'])) { $this->_idUtilisateur = $donnees['id_utilisateur']; }
			if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->_prenom = $donnees['prenom']; }
			if (isset($donnees['id_iut'])) { $this->_idIut = $donnees['id_iut']; }
			if (isset($donnees['mail'])) { $this->_mail = $donnees['mail']; }
			if (isset($donnees['mdp'])) { $this->_mdp = $donnees['mdp']; }
			if (isset($donnees['administrateur'])) { $this->_administrateur = $donnees['administrateur']; }
			if (isset($donnees['pdp'])) { $this->_pdp = $donnees['pdp']; }
        }   
		
		
        // GETTERS //
		public function getIdUtilisateur() { return $this->_idUtilisateur;}
		public function getNom() { return $this->_nom;}
		public function getPrenom() { return $this->_prenom;}
		public function getIdIut() { return $this->_idIut;}
		public function getMail() { return $this->_mail;}
		public function getMdp() { return $this->_mdp;}
		public function getAdministrateur() { return $this->_administrateur;}
		public function getPdp() { return $this->_pdp ; }
	
		
		// SETTERS //
		public function setIdUtilisateur(int $idUtilisateur) { $this->_idUtilisateur = $idUtilisateur; }
        public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
		public function setIdIut(int $idIut) { $this->_idIut = $idIut; }
		public function setMail(string $mail) { $this->_mail = $mail; }
		public function setMdp(string $mdp) { $this->_mdp = $mdp; }
		public function setAdministrateur(bool $administrateur) { $this->_administrateur = $administrateur; }		
		public function setPdp(string $pdp) { $this->_pdp = $pdp; }	
    }

?>