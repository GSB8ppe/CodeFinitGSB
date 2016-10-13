<?php
/** 
 * Fonction getLesVisiteurs
 * R�cupere tout les visiteurs de la table visiteur et les retournes
 * @param Object de connection $pdo
 * @return tableau $lesLignes contenant les visiteurs
 */
function getLesVisiteurs($pdo)
{
		$req = "select * from Visiteur";
		$res = $pdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
}
/**
 * Fonction getLesFichesFrais
 * R�cup�re tout les fiches frais de la table ficheFrais et les retournes
 * @param Object de connection $pdo
 * @return tableau $lesLignes contenant les fiches frais
 */
function getLesFichesFrais($pdo)
{
		$req = "select * from FicheFrais";
		$res = $pdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
}
/**
 * Fonction getLesIdFraisForfait
 * R�cup�re id fraisforfait de la table fraisforfait et les tris par id
 * @param Object de connection $pdo
 * @return tableau $LesLignes avec les ids de la table fraisforfait tri�s par id
 */
function getLesIdFraisForfait($pdo)
{
		$req = "select FraisForfait.id as id from FraisForfait order by FraisForfait.id";
		$res = $pdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
}
/**
 * Fonction getDernierMois
 * Renvoie le dernier mois de la fiche de frais en fonction du visiteur
 * @param Object de connection $pdo
 * @param string $idVisiteur
 * @return string $LaLigne
 */
function getDernierMois($pdo, $idVisiteur)
{
		$req = "select max(mois) as dernierMois from FicheFrais where idVisiteur = '$idVisiteur'";
		$res = $pdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['dernierMois'];

}
/**
 * Fonction getMoisSuivant
 * retourne le mois suivant et son ann�e
 * @param string $mois
 * @return int $numAnnee.$numMois
 */
function getMoisSuivant($mois){
		$numAnnee =substr( $mois,0,4);
		$numMois =substr( $mois,4,2);
		if($numMois=="12"){
			$numMois = "01"; 
			$numAnnee++;
		}
		else{
			$numMois++;

		}
		if(strlen($numMois)==1)
			$numMois="0".$numMois;
		return $numAnnee.$numMois;
}
/**
 * Fonction getMoisPrecedent
 * Retroune le mois pr�c�dent et son ann�e
 * @param string $mois
 * @return string $numAnnee.$numMois
 */
function getMoisPrecedent($mois){
		$numAnnee =substr( $mois,0,4);
		$numMois =substr( $mois,4,2);
		if($numMois=="01"){
			$numMois = "12"; 
			$numAnnee--;
		}
		else{
			$numMois--;
		}
		if(strlen($numMois)==1)
			$numMois="0".$numMois;
		return $numAnnee.$numMois;
}
/**Proc�dure creationFichesFrais
 * Permet la creation d'une ou plusieures fiches de frais
 * @param Object de connection $pdo
 */
function creationFichesFrais($pdo)
{
	$lesVisiteurs = getLesVisiteurs($pdo);
	$moisActuel = getMois(date("d/m/Y"));
	$moisDebut = "201001";
	$moisFin = getMoisPrecedent($moisActuel);
	foreach($lesVisiteurs as $unVisiteur)
	{
		$moisCourant = $moisFin;
		$idVisiteur = $unVisiteur['id'];
		$n = 1;
		while($moisCourant >= $moisDebut)
		{
			if($n == 1)
			{
				$etat = "CR";
				$moisModif = $moisCourant;
			}
			else
			{
				if($n == 2)
				{
					$etat = "VA";
					$moisModif = getMoisSuivant($moisCourant);
				}
				else
				{
					$etat = "RB";
					$moisModif = getMoisSuivant(getMoisSuivant($moisCourant));
				}
			}
			$numAnnee =substr( $moisModif,0,4);
			$numMois =substr( $moisModif,4,2);
			$dateModif = $numAnnee."-".$numMois."-".rand(1,8);
			$nbJustificatifs = rand(0,12);
			$req = "insert into FicheFrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
			values ('$idVisiteur','$moisCourant',$nbJustificatifs,0,'$dateModif','$etat');";
			$pdo->exec($req);
			$moisCourant = getMoisPrecedent($moisCourant);
			$n++;
		}
	}
}
/**
 * Proc�dure creationFraisForfait
 * Cr�� des frais forfait
 * @param Object de connection $pdo
 */
function creationFraisForfait($pdo)
{
	$lesFichesFrais= getLesFichesFrais($pdo);
	$lesIdFraisForfait = getLesIdFraisForfait($pdo);
	foreach($lesFichesFrais as $uneFicheFrais)
	{
		$idVisiteur = $uneFicheFrais['idVisiteur'];
		$mois =  $uneFicheFrais['mois'];
		foreach($lesIdFraisForfait as $unIdFraisForfait)
		{
			$idFraisForfait = $unIdFraisForfait['id'];
			if(substr($idFraisForfait,0,1)=="K")
			{
				$quantite =rand(300,1000);
			}
			else
			{
				$quantite =rand(2,20);
			}
			$req = "insert into LigneFraisForfait(idvisiteur,mois,idfraisforfait,quantite)
			values('$idVisiteur','$mois','$idFraisForfait',$quantite);";
			$pdo->exec($req);	
		}
	}

}
/**
 * Fonction getDesFraisHorsForfait
 * Renvoie les frais hors forfaits
 * @return array $tab
 */
function getDesFraisHorsForfait()
{
	$tab = array(
				1 => array(
				      "lib" => "repas avec praticien",
					  "min" => 30,
					  "max" => 50 ),
				2 => array(
				      "lib" => "achat de matériel de papèterie",
					  "min" => 10,
					  "max" => 50 ),
				3	=> array(
				      "lib" => "taxi",
					  "min" => 20,
					  "max" => 80 ),
				4 => array(
				      "lib" => "achat d'espace publicitaire",
					  "min" => 20,
					  "max" => 150 ),
				5 => array(
				      "lib" => "location salle conférence",
					  "min" => 120,
					  "max" => 650 ),
				6 => array(
				      "lib" => "Voyage SNCF",
					  "min" => 30,
					  "max" => 150 ),
				7 => array(
					  "lib" => "traiteur, alimentation, boisson",
					  "min" => 25,
					  "max" => 450 ),
				8 => array(
					  "lib" => "rémunération intervenant/spécialiste",
					  "min" => 250,
					  "max" => 1200 ),
				9 => array(
					  "lib" => "location équipement vidéo/sonore",
					  "min" => 100,
					  "max" => 850 ),
				10 => array(
					  "lib" => "location véhicule",
					  "min" => 25,
					  "max" => 450 ),
				11 => array(
					  "lib" => "frais vestimentaire/représentation",
					  "min" => 25,
					  "max" => 450 ) 
		);
	return $tab;
}
/**
 * Proc�dure updateMdpVisiteur
 * Met a jour le mot de passe du visiteur
 * @param Object de connection $pdo
 */
function updateMdpVisiteur($pdo)
{
	$req = "select * from Visiteur";
		$res = $pdo->query($req);
		$lesLignes = $res->fetchAll();
		$lettres ="azertyuiopqsdfghjkmwxcvbn123456789";
		foreach($lesLignes as $unVisiteur)
		{
			$mdp = "";
			$id = $unVisiteur['id'];
			for($i =1;$i<=5;$i++)
			{
				$uneLettrehasard = substr( $lettres,rand(33,1),1);
				$mdp = $mdp.$uneLettrehasard;
			}
			
			$req = "update Visiteur set mdp ='$mdp' where Visiteur.id ='$id' ";
			$pdo->exec($req);
		}


}
/**
 * Proc�dure creationFraisHorsForfait
 * Cr�er des frais hors forfait
 * @param Object de connection $pdo
 */
function creationFraisHorsForfait($pdo)
{
	$desFrais = getDesFraisHorsForfait();
	$lesFichesFrais= getLesFichesFrais($pdo);
	
	foreach($lesFichesFrais as $uneFicheFrais)
	{
		$idVisiteur = $uneFicheFrais['idVisiteur'];
		$mois =  $uneFicheFrais['mois'];
		$nbFrais = rand(0,5);
		for($i=0;$i<=$nbFrais;$i++)
		{
			$hasardNumfrais = rand(1,count($desFrais)); 
			$frais = $desFrais[$hasardNumfrais];
			$lib = $frais['lib'];
			$min= $frais['min'];
			$max = $frais['max'];
			$hasardMontant = rand($min,$max);
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$hasardJour = rand(1,28);
			if(strlen($hasardJour)==1)
			{
				$hasardJour="0".$hasardJour;
			}
			$hasardMois = $numAnnee."-".$numMois."-".$hasardJour;
			$req = "insert into LigneFraisHorsForfait(idVisiteur,mois,libelle,date,montant)
			values('$idVisiteur','$mois','$lib','$hasardMois',$hasardMontant);";
			$pdo->exec($req);
		}
	}
}
/**
 * Fonction getMois
 * Retourne l'ann�e et le mois de la date
 * @param DateTime $date
 * @return string $annee.$mois
 */
function getMois($date){
		@list($jour,$mois,$annee) = explode('/',$date);
		if(strlen($mois) == 1){
			$mois = "0".$mois;
		}
		return $annee.$mois;
}
/**
 * Proc�dure majFicheFrais
 * Met � jour la fiche de frais
 * @param Object de connection $pdo
 */
function majFicheFrais($pdo)
{
	
	$lesFichesFrais= getLesFichesFrais($pdo);
	foreach($lesFichesFrais as $uneFicheFrais)
	{
		$idVisiteur = $uneFicheFrais['idVisiteur'];
		$mois =  $uneFicheFrais['mois'];
		$dernierMois = getDernierMois($pdo, $idVisiteur);
		$req = "select sum(montant) as cumul from LigneFraisHorsForfait where LigneFraisHorsForfait.idVisiteur = '$idVisiteur' 
				and LigneFraisHorsForfait.mois = '$mois' ";
		$res = $pdo->query($req);
		$ligne = $res->fetch();
		$cumulMontantHorsForfait = $ligne['cumul'];
		$req = "select sum(LigneFraisForfait.quantite * FraisForfait.montant) as cumul from LigneFraisForfait, FraisForfait where
		LigneFraisForfait.idFraisForfait = FraisForfait.id   and   LigneFraisForfait.idVisiteur = '$idVisiteur' 
				and LigneFraisForfait.mois = '$mois' ";
		$res = $pdo->query($req);
		$ligne = $res->fetch();
		$cumulMontantForfait = $ligne['cumul'];
		$montantEngage = $cumulMontantHorsForfait + $cumulMontantForfait;
		$etat = $uneFicheFrais['idEtat'];
		if($etat == "CR" )
			$montantValide = 0;
		else
			$montantValide = $montantEngage*rand(80,100)/100;
		$req = "update FicheFrais set montantValide =$montantValide where
		idVisiteur = '$idVisiteur' and mois='$mois'";
		$pdo->exec($req);
		
	}
}
?>




