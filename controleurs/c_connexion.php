<?php


if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'ajouterVisiteur':{
		
			$nom = $_REQUEST['nom'];
			$prenom = $_REQUEST['prenom'];
			$login = $_REQUEST['login'];
			$mdp =  $_REQUEST['mdp'];
			$type = $_REQUEST['type'];
			$pdo->ajouterVisiteur($nom, $prenom, $login, $mdp, $type);
			include('vues/v_redirection.php'); 
		
			
	}
		break;
	
	case 'creerCompte':{
		include ("vues/v_inscription.php");
		break;
	}
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST ['mdp'];
		$visiteur = $pdo->getInfosVisiteur($login,$mdp);
		if(!is_array( $visiteur)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else { 
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			connecter($id,$nom,$prenom);
			include("vues/v_sommaire.php");
			}

			break;	
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>