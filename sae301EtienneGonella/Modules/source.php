<?php
/** 
* définition de la classe Source
*/
class Source {
        private int $_idSource;
        private string $_nomSource;
        private int $_idProjet;

	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_source'])) { $this->_idSource = $donnees['id_source']; }
			if (isset($donnees['nom_source'])) { $this->_nomSource = $donnees['nom_source']; }
			if (isset($donnees['id_projet'])) { $this->_idProjet = $donnees['id_projet']; }

        }  
		
		
        // GETTERS //
		public function getIdSource() { return $this->_idSource;}
		public function getNomSource() { return $this->_nomSource;}
		public function getIdSae() { return $this->_idProjet;}

	
		// SETTERS //qs
		public function setIdSource(int $idSource) { $this->_idSource = $idSource; }
        public function setNomSource(string $nomSource) { $this->_nomSource= $nomSource; }
		public function setIdSae(int $idProjet) { $this->_idProjet = $idProjet; }
    }

?>