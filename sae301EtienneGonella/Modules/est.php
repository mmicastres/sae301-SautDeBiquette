<?php
/** 
* définition de la classe relationnelle Est
*/
class Est {
        private int $_idProjet;
        private string $_idTag;
	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_projet'])) { $this->_idProjet = $donnees['id_projet']; }
			if (isset($donnees['id_tag'])) { $this->_idTag = $donnees['id_tag']; }
        }         

        
        // GETTERS //
		public function getIdProjet() { return $this->_idProjet;}
		public function getIdTag() { return $this->_idTag;}

	
		// SETTERS //
        public function setNomProjet(string $idProjet) { $this->_idProjet= $idProjet; }
		public function setIdTag(int $idTag) { $this->_idTag = $idTag; }

    }

?>