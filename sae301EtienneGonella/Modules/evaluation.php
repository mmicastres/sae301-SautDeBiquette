<?php
/** 
* définition de la classe Evaluation
*/
class Evaluation {
        private int $_idEvaluation;
        private string $_texte;
        private int $_note;
	    private int $_idProjet;   
        private int $_idUtilisateur;
		private string $_nom;
		private string $_prenom;



	
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['id_evaluation'])) { $this->_idEvaluation = $donnees['id_evaluation']; }
			if (isset($donnees['texte'])) { $this->_texte = $donnees['texte']; }
			if (isset($donnees['note'])) { $this->_note = $donnees['note']; }
	    	if (isset($donnees['id_projet'])) { $this->_idProjet = $donnees['id_projet']; }
			if (isset($donnees['id_utilisateur'])) { $this->_idUtilisateur = $donnees['id_utilisateur']; }
			if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->_prenom = $donnees['prenom']; }



        }  
		
			
        // GETTERS //
		public function getidEvaluation() { return $this->_idEvaluation;}
		public function getTexte() { return $this->_texte;}
		public function getNote() { return $this->_note;}
	    public function getidProjet() { return $this->_idProjet;}
		public function getidUtilisateur() { return $this->_idUtilisateur;}
		public function getNom() { return $this->_nom;}
		public function getPrenom() { return $this->_prenom;}


	
		// SETTERS //
		public function setidEvaluation(int $idEvaluation) { $this->_idEvaluation = $idEvaluation; }
        public function setTexte(string $texte) { $this->_texte= $texte; }
		public function setNote() { return $this->_note;}
	    public function setidProjet(int $idProjet) { $this->_idProjet = $idProjet; }
		public function setidUtilisateur(int $idUtilisateur) { $this->_idUtilisateur = $idUtilisateur; }
		public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }

        

    }

?>