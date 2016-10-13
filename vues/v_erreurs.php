<?php 
foreach($_REQUEST['erreurs'] as $erreur)
	{
      echo "<script language=\"javascript\">";
	  echo "alert ('$erreur')";
	  echo "</script>";;
	}
?>
