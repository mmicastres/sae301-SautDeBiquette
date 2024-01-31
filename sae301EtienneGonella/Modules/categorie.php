<?php
/** 
* définition de la classe Categorie
*/
class Categorie {
        private int $_idCategorie;
        private string $_nomCategorie;

	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_categorie'])) { $this->_idCategorie = $donnees['id_categorie']; }
			if (isset($donnees['nom_categorie'])) { $this->_nomCategorie = $donnees['nom_categorie']; }

        }     
        
        
        // GETTERS //
		public function getIdCategorie() { return $this->_idCategorie;}
		public function getNomCategorie() { return $this->_nomCategorie;}

	
		// SETTERS //
		public function setIdCategorie(int $idCategorie) { $this->_idCategorie = $idCategorie; }
        public function setNomCategorie(string $nomCategorie) { $this->_nomCategorie= $nomCategorie; }
    }

?>