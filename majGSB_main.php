
<?php
/*Programme d'actualisation des lignes des tables,  
 cette mise à  jour peut prendre plusieurs minutes...*/
include("include/fct.inc.onlydev.php");

/* Modification des paramètres de connexion */

$serveur='mysql:host=localhost';
$bdd='dbname=gsbAppliFrais';   		
$user='root' ;    		
$mdp='mysql' ;	

/* fin paramètres*/

$pdo = new PDO($serveur.';'.$bdd, $user, $mdp);
$pdo->query("SET CHARACTER SET utf8"); 

set_time_limit(0);
creationFichesFrais($pdo);
creationFraisForfait($pdo);
creationFraisHorsForfait($pdo);
majFicheFrais($pdo);

?>