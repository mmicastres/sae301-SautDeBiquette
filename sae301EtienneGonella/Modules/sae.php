<?php
/** 
* définition de la classe Sae
*/
class Sae {
        private int $_idSae;
        private string $_nomSae;
	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_sae'])) { $this->_idSae = $donnees['id_sae']; }
			if (isset($donnees['nom_sae'])) { $this->_nomSae = $donnees['nom_sae']; }
        }     
        
        
        // GETTERS //
		public function getIdSae() { return $this->_idSae;}
		public function getNomSae() { return $this->_nomSae;}
	
        
		// SETTERS //
		public function setIdSae(int $idSae) { $this->_idSae = $idSae; }
        public function setNomSae(string $nomSae) { $this->_nomSae= $nomSae; }
    }

?>