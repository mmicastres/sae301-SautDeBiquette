<?php
/**
* définition de la classe Projet
*/
class Projet {
	private int $_idProjet;   
	private string $_titre;
	private string $_description;
	private string $_imgProjet;
	private string $_lienDemo;
	private int $_annee;
	private int $_idRessource;
	private int $_idCategorie;

		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['id_projet']))    	   { $this->_idProjet     = $donnees['id_projet']; }
		if (isset($donnees['titre']))   		   { $this->_titre 		  = $donnees['titre']; }
		if (isset($donnees['description'])) 	   { $this->_description  = $donnees['description']; }
		if (isset($donnees['img_projet'])) 		   { $this->_imgProjet   = $donnees['img_projet']; }
		if (isset($donnees['lien_demo'])) 		   { $this->_lienDemo    = $donnees['lien_demo']; }
		if (isset($donnees['annee']))     		   { $this->_annee        = $donnees['annee'];}		
		if (isset($donnees['id_ressource']))       { $this->_idRessource = $donnees['id_ressource']; }
		if (isset($donnees['id_categorie']))       { $this->_idCategorie = $donnees['id_categorie']; }

	}   
	
	
	// GETTERS //
	public function getIdProjet()       { return $this->_idProjet;}
	public function getTitre()    		{ return $this->_titre;}
	public function getDescription() 	{ return $this->_description;}
	public function getImgProjet() 		{ return $this->_imgProjet;}
	public function getLienDemo()       { return $this->_lienDemo;}
	public function getAnnee()  		{ return $this->_annee;}
	public function getIdRessource()    { return $this->_idRessource;}
	public function getIdCategorie()    { return $this->_idCategorie;}

		
	// SETTERS //
	public function setIdProjet(int $idProjet)            { $this->_idProjet     = $idProjet; }
	public function setTitre(string $titre)       		  { $this->_titre        = $titre; }
	public function setDescription(string $description)   { $this->_description  = $description; }
	public function SetImgProjet(string $imgProjet) 	  { $this->_imgProjet   = $imgProjet; }
	public function setLienDemo(string $lienDemo) 		  { $this->_lienDemo    = $lienDemo; }
	public function setAnnee(int $annee)  				  { $this->_annee        = $annee; }
	public function setIdRessource(int $idRessource)      { $this->_idRessource = $idRessource; }
	public function setNbPlaces(int $idCategorie)         { $this->_idCategorie = $idCategorie; }


}

