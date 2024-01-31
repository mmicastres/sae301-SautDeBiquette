	<?php

	use function PHPSTORM_META\type;

	include "Modules/projet.php";
	include "Models/projetManager.php";

	include "Modules/source.php";
	include "Models/sourceManager.php";

	include "Modules/tag.php";
	include "Models/tagManager.php";

	include "Modules/ressource.php";
	include "Models/ressourceManager.php";

	include "Modules/sae.php";
	include "Models/saeManager.php";

	include "Modules/categorie.php";
	include "Models/categorieManager.php";

	include "Modules/participe.php";
	include "Models/participeManager.php";

	include "Modules/est.php";
	include "Models/estManager.php";
	/**
	 * Définition d'une classe permettant de gérer les projets 
	 *   en relation avec la base de données	
	 */
	class ProjetController
	{

		private $projetManager; // instance du manager de projet
		private $sourceManager; // instance du manager des sources		
		private $evaluationManager; // instance du manager de projet
		private $tagManager; // instance du manager des tags
		private $ressourceManager; // instance du manager des ressources
		private $saeManager; // instance du manager de sae
		private $categorieManager; // instance du manager des catégories		
		private $utilisateurManager; // instance du manager des utilisateurs	
		private $participeManager; // instance du manager de la table participe
		private $estManager; // instance du manager de la table est
		private $twig;

		/**
		 * Constructeur = initialisation de la connexion vers le SGBD
		 */
		public function __construct($db, $twig)
		{
			$this->projetManager = new ProjetManager($db);
			$this->sourceManager = new SourceManager($db);
			$this->evaluationManager = new EvaluationManager($db);
			$this->tagManager = new TagManager($db);
			$this->ressourceManager = new RessourceManager($db);
			$this->saeManager = new SaeManager($db);
			$this->categorieManager = new CategorieManager($db);
			$this->utilisateurManager = new UtilisateurManager($db);
			$this->participeManager = new ParticipeManager($db);
			$this->estManager = new estManager($db);
			$this->twig = $twig;
		}

		/**
		 * liste de tous les projets
		 * @param aucun
		 * @return rien
		 */
		public function listeProjets()
		{
			$projets = $this->projetManager->getListProjet();
			echo $this->twig->render('projet_liste.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
		}

		/**
		 * liste de MES projets
		 * @param aucun
		 * @return rien
		 */
		public function listeMesProjets($idUtilisateur)
		{
			$projets = $this->projetManager->getListProjetsUtilisateur($idUtilisateur);
			echo $this->twig->render('projet_liste.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
		}
		/**
		 * formulaire ajout
		 * @param aucun
		 * @return rien
		 */
		public function formAjoutProjet()
		{
			$tags = $this->tagManager->getListTags();
			$ressources = $this->ressourceManager->getListRessource();
			$categories = $this->categorieManager->getListCategorie();
			$utis = $this->utilisateurManager->getListUtilisateur();
			$utilisateur = $this->utilisateurManager->getUtilisateur($_SESSION['idUtilisateur']);
			echo $this->twig->render('projet_ajout.html.twig', array('acces' => $_SESSION['acces'], 'utis' => $utis, 'utilisateur' => $utilisateur, 'tags' => $tags, 'ressources' => $ressources, 'categories' => $categories));
		}

		/**	
		 * ajout dans la BD d'un proj à partir du form
		 * @param aucun
		 * @return rien
		 */
		public function ajoutProjet()
		{
			$proj = new Projet($_POST);

			if (isset($_FILES["img_projet"])) {
				$uploaddir = "./img/pdd/";
				// on trace le chemin dans lequel le fichier va être stocké : dans l'odre -le dossier img, on le renomme avec -le titre -l'année et enfin -l'extension du fichier
				$uploadfile = $uploaddir .  str_replace(" ", "_", $proj->getTitre()) . $proj->getAnnee() . "." . pathinfo($_FILES["img_projet"]["name"], PATHINFO_EXTENSION);
				if ($_FILES["img_projet"]["error"] == UPLOAD_ERR_OK) {

					if (!move_uploaded_file($_FILES["img_projet"]["tmp_name"], $uploadfile)) {
						echo "pb lors du telechargement de la photo de profil";
					}
					$proj->setImgProjet($uploadfile);



					$ok = $this->projetManager->addProjet($proj);

					// Je créer une nouvelle source
					foreach ($_POST['nom_source'] as $nomSource) {
						if (!empty($nomSource)) {
							$source = new Source(array('nom_source' => $nomSource));
							$this->sourceManager->addSourceProjet($source, $ok);
						}
					}
					// J'établis les liens entre les utilisateurs et mon projet
					foreach ($_POST['id_utilisateur'] as $idUtilisateur) {
						if (!empty($idUtilisateur)) {
							$participe = new Participe(array('id_utilisateur' => $idUtilisateur));
							$this->participeManager->addParticipe($participe, $ok);
						}
					}
					// J'établis les liens entre les tags et mon projet
					foreach ($_POST['id_tag'] as $idTag) {
						if (!empty($idTag)) {
							$tag = new Est(array('id_tag' => $idTag));
							$this->estManager->addEst($tag, $ok);
						}
					}


					$notif = $ok ? "Projet  ajouté" : "probleme lors de l'ajout du projet";
					echo $this->twig->render('notification.html.twig', array('notif' => $notif, 'acces' => $_SESSION['acces']));
				}
			}
		}

		/**
		 * suppression dans la BD d'un proj à partir de l'id choisi dans le form précédent
		 * @param aucun
		 * @return bool
		 */
		public function suppProjetSolo($idProjet) : bool
		{
			if (
				$this->estManager->deleteEstProjet($idProjet) &&
				$this->evaluationManager->deleteEvaluationProjet($idProjet) &&
				$this->participeManager->deleteParticipeProjet($idProjet) &&
				$this->sourceManager->deleteSourceProjet($idProjet) &&
				$this->projetManager->delete($idProjet)
			) {
				$ok =  true;
			} else {
				$ok  = false ;
			}
			return $ok;	
		}
		// Je sépare en deux fonction pour ne pas avoir le render de lié et pour pouvoir appeler ma fonction dans la suppression de ressource
		/**
		 * suppression dans la BD d'un proj à partir de l'id choisi dans le form précédent
		 * @param aucun
		 * @return rien
		 */
		public function suppProjet($idProjet)
		{
			if ($this->suppProjetSolo($idProjet)) {
				$notif =  "projet supprimé";
			} else {
				$notif = "probleme lors de la supression";
			}
			echo $this->twig->render('notification.html.twig', array('notif' => $notif, 'acces' => $_SESSION['acces']));
		}
		/**
		 * suppression dans la BD d'une liste de proj de la liste d'objets renvoyés 
		 * @param Tableau de Projet
		 * @return bool
		 */

		

		/**
		 * form de saisi des nouvelles valeurs du proj à modifier
		 * @param aucun
		 * @return rien
		 */
		public function saisieModProjet()
		{
			// Je récupère les éléments propre à mon projet
			$projet = $this->projetManager->getProjet($_POST['id_projet']);
			$sources = $this->sourceManager->getListSourceProjet($_POST['id_projet']);
			$tagProjet = $this->tagManager->getListTagsProjet($_POST['id_projet']);
			$tagProjet = $this->tagManager->getListTagsProjet($_POST['id_projet']);
			$utilisateursProjet = $this->utilisateurManager->getListUtilisateurProjet($_POST['id_projet']);
			// Je récupère les mêmes éléments mais globaux pour pouvoir les comparer
			$tags = $this->tagManager->getListTags();
			$ressources = $this->ressourceManager->getListRessource();
			$categories = $this->categorieManager->getListCategorie();
			$utis = $this->utilisateurManager->getListUtilisateur();
			$utilisateur = $this->utilisateurManager->getUtilisateur($_SESSION['idUtilisateur']);
			echo $this->twig->render('projet_modification.html.twig', array('projet' => $projet, 'sources' => $sources, 'tags' => $tags, 'ressources' => $ressources, 'categories' => $categories, 'tagProjet' => $tagProjet, 'utis' => $utis, 'utilisateur' => $utilisateur, 'utilisateursProjet' => $utilisateursProjet, 'acces' => $_SESSION['acces']));
		}

		/**
		 * modification dans la BD d'un proj à partir des données du form précédent
		 * @param aucun
		 * @return rien
		 */
		public function modProjet()
		{
			$proj =  new Projet($_POST);
			if (isset($_FILES["img_projet"]["name"])) {
				$uploaddir = "./img/pdd/";
				// on trace le chemin dans lequel le fichier va être stocké : dans l'odre -le dossier img, on le renomme avec -le titre -l'année et enfin -l'extension du fichier
				// On remplace aussi tous les espaces par des underscores, comme ça l'image est nommée correctement (risque coupure du nom de l'image sinon)
				$uploadfile = $uploaddir . str_replace(" ", "_", $proj->getTitre()) . $proj->getAnnee() . "." . pathinfo($_FILES["img_projet"]["name"], PATHINFO_EXTENSION);
				if ($_FILES["img_projet"]["error"] == UPLOAD_ERR_OK) {
					if (!move_uploaded_file($_FILES["img_projet"]["tmp_name"], $uploadfile)) {
						echo "pb lors du telechargement de la photo de profil";
					}
					$proj->setImgProjet($uploadfile);
				}
			}
			// Je supprimes les liens entre les utilisateurs du projet puis je les réétablies 
			// Ici array_unique me permet de supprimer les doublons, et donc, de ne pas avoir de conflits ! que ce soit doublons vide ou doublons par erreur d'utilisateurs
			$participe = $this->participeManager->deleteParticipeProjet($proj->getIdProjet());
			foreach (array_unique($_POST['id_utilisateur']) as $idUtilisateur) {
				if (!empty($idUtilisateur)) {
					$participe = new Participe(array('id_utilisateur' => $idUtilisateur));
					$this->participeManager->addParticipe($participe, $proj);
				}
			}

			// Je supprimes les sources du projet puis je les réétablies 
			$this->sourceManager->deleteSourceProjet($proj->getIdProjet());
			foreach ($_POST['nom_source'] as $nomSource) {
				if (!empty($nomSource)) {
					$source = new Source(array('nom_source' => $nomSource));
					$this->sourceManager->addSourceProjet($source, $proj);
				}
			}



			// Je supprimes les liens entre les tags du projet puis je les réétablies 
			$this->estManager->deleteEstProjet($proj->getIdProjet());
			foreach ($_POST['id_tag'] as $idTag) {
				if (!empty($idTag)) {
					$tag = new Est(array('id_tag' => $idTag));
					$this->estManager->addEst($tag, $proj);
				}
			}
			$ok = null !== $this->projetManager->update($proj);
			$notif = $ok ? "projet modifié" : $notif = "probleme lors de la modification";
			echo $this->twig->render('notification.html.twig', array('notif' => $notif, 'acces' => $_SESSION['acces']));
		}



		/**
		 * recherche dans la BD d'proj à partir des données du form précédent
		 * @param aucun
		 * @return rien
		 */
		public function rechercheProjet()
		{
			$projets = $this->projetManager->searchProjet(filter_var($_POST["recherche"], FILTER_SANITIZE_SPECIAL_CHARS));
			echo $this->twig->render('projet_liste.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
		}

		// Pour la recherche avancé dans le profil
		// public function rechercheProjetUtilisateur($idUtilisateur) {
		// 	$utilisateur = $this->utilisateurManager->getUtilisateur($idUtilisateur);
		// 	$projets = $this->projetManager->searchProjetUtilisateur($_POST["recherche"],$idUtilisateur);
		// 	$infosUtilisateur = true;
		// 	$this->twig->render('projet_liste.html.twig', array('acces' => $_SESSION['acces'],'projets'=>$projets, 'utilisateur' => $utilisateur, 'infosUtilisateur'=>$infosUtilisateur));
		// }

		public function detailProjet()
		{
			$projet = $this->projetManager->getProjet($_POST['id_projet']);
			$sources = $this->sourceManager->getListSourceProjet($_POST['id_projet']);
			$tags = $this->tagManager->getListTagsProjet($_POST['id_projet']);
			$sae = $this->saeManager->getSaeProjet($_POST['id_projet']);
			$ressource = $this->ressourceManager->getRessourceProjet($_POST['id_projet']);
			$categorie = $this->categorieManager->getCategorieProjet($_POST['id_projet']);
			$evaluations = $this->evaluationManager->getListEvaluations($_POST['id_projet']);
			$utilisateurs = $this->utilisateurManager->getListUtilisateurProjet($_POST['id_projet']);

			echo $this->twig->render('projet_details.html.twig', array('acces' => $_SESSION['acces'], 'idUtilisateur' => $_SESSION['idUtilisateur'], 'projet' => $projet, 'sources' => $sources, 'tags' => $tags, 'ressource' => $ressource, 'sae' => $sae, 'utilisateurs' => $utilisateurs, 'categorie' => $categorie, 'evaluations' => $evaluations));
		}

		public function getListCategorie()
		{
			$nom = "Catégories";
			$categories = $this->categorieManager->getListCategorie();
			echo $this->twig->render('gestion_liste.html.twig', array('acces' => $_SESSION['acces'], 'elements' => $categories, 'nom' => $nom));
		}

		public function suppCategorie()
		{
			$this->categorieManager->deleteCategorie($_POST['id_categorie']);
			$this->getListCategorie();
		}

		public function addCategorie()
		{
			$categorie = new Categorie($_POST);
			$this->categorieManager->addCategorie($categorie);
			$this->getListCategorie();
		}

		public function getListRessource()
		{
			$nom = "Ressources";
			$ressources = $this->ressourceManager->getListRessource();
			$saes = $this->saeManager->getListSae();
			echo $this->twig->render('gestion_liste.html.twig', array('acces' => $_SESSION['acces'], 'elements' => $ressources, 'saes' => $saes, 'nom' => $nom));
		}

		public function suppRessource()
		{
			// Je supprime d'abord tous les projet lié à la ressource avant de supprimer la ressource
			$projets = $this->projetManager->getListProjetsRessource($_POST['id_ressource']);
			foreach ($projets as $proj){
				$this->suppProjetSolo($proj->getIdProjet());
			}

			$this->ressourceManager->deleteRessource($_POST['id_ressource']);
			$this->getListRessource();
		}

		public function addRessource()
		{
			$ressource = new Ressource($_POST);
			$this->ressourceManager->addRessource($ressource);
			$this->getListRessource();
		}

		public function suppSae()
		{
			$this->saeManager->deleteSae($_POST['id_sae']);
			$this->getListRessource();
		}

		public function addSae()
		{
			$sae = new Sae($_POST);
			$this->saeManager->addSae($sae);
			$this->getListRessource();
		}
	}
