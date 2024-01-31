<?php
/** 
* définition de la classe Tag
*/
class Tag {
        private int $_idTag;
        private string $_nomTag;

	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_tag'])) { $this->_idTag = $donnees['id_tag']; }
			if (isset($donnees['nom_tag'])) { $this->_nomTag = $donnees['nom_tag']; }

        }    
        
    
        // GETTERS //
		public function getIdTag() { return $this->_idTag;}
		public function getNomTag() { return $this->_nomTag;}

	
		// SETTERS //
		public function setIdTag(int $idTag) { $this->_idTag = $idTag; }
        public function setNomTag(string $nomTag) { $this->_nomTag= $nomTag; }
    }

?>