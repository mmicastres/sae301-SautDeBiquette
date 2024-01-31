<?php
/** 
* définition de la classe Ressource
*/

class Ressource {
        private int $_idRessource;
        private int $_semestre;
        private string $_intitule;
        private int $_numeroRessource;
        private int $_idSae;
        private string $_nomSae;

        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_ressource'])) { $this->_idRessource = $donnees['id_ressource']; }
			if (isset($donnees['semestre'])) { $this->_semestre = $donnees['semestre']; }
			if (isset($donnees['intitule'])) { $this->_intitule = $donnees['intitule']; }
			if (isset($donnees['numero_ressource'])) { $this->_numeroRessource = $donnees['numero_ressource']; }
			if (isset($donnees['id_sae'])) { $this->_idSae = $donnees['id_sae']; }
			if (isset($donnees['nom_sae'])) { $this->_nomSae = $donnees['nom_sae']; }

            
        }   
        
        
        // GETTERS //
		public function getIdRessource() { return $this->_idRessource;}
		public function getSemestre() { return $this->_semestre;}
        public function getIntitule() {return $this->_intitule;}
        public function getNumeroRessource() {return $this->_numeroRessource;}
        public function getIdSae() {return $this->_idSae;}
		public function getNomSae() { return $this->_nomSae;}


	
		// SETTERS //
		public function setIdRessource(int $idRessource) { $this->_idRessource = $idRessource; }
		public function setSemestre(int $semestre) { $this->_semestre = $semestre; }
        public function setNom(string $intitule) { $this->_intitule= $intitule; }
		public function setNumeroRessource(int $numeroRessource) { $this->_numeroRessource = $numeroRessource; }
        public function setIdSae(int $idSae) { $this->_idSae = $idSae;}
        public function setNomSae(string $nomSae) { $this->_nomSae= $nomSae; }



    }

?>