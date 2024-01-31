<?php
/** 
* définition de la classe relationnelle Participe
*/
class Participe {
        private int $_idProjet;
        private int $_idUtilisateur;
	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_projet'])) { $this->_idProjet = $donnees['id_projet']; }
			if (isset($donnees['id_utilisateur'])) { $this->_idUtilisateur = $donnees['id_utilisateur']; }
        }          

        
        // GETTERS //
		public function getIdProjet() { return $this->_idProjet;}
		public function getIdUtilisateur() { return $this->_idUtilisateur;}
	
		// SETTERS //
        public function setNomProjet(string $idProjet) { $this->_idProjet= $idProjet; }
		public function setIdUtilisateur(int $idUtilisateur) { $this->_idUtilisateur = $idUtilisateur; }

    }

?>