<div class="col-md-6">
	<div class="content-box-large">
		<div class="panel-heading">
			<legend>Elément hors forfait</legend>
		</div>
		<div class="panel-body">
			<table class="table">
			  <thead>
				<tr>
					<th class="date">Date</th>
					<th class="libelle">Libellé</th>  
					<th class="montant">Montant</th>  
					<th class="action">&nbsp;</th>              
				 </tr>
				 <?php    

					foreach( $lesFraisHorsForfait as $unFraisHorsForfait) 
					{
						$libelle = $unFraisHorsForfait['libelle'];
						$date = $unFraisHorsForfait['date'];
						$montant=$unFraisHorsForfait['montant'];
						$id = $unFraisHorsForfait['id']
						
				?>		
						<tr>
							<td> <?php echo $date ?></td>
							<td><?php echo $libelle ?></td>
							<td><?php echo $montant ?></td>
							<td> <?php if ($lesInfosFicheFrais['idEtat']!='CR') { echo '<a></a>';}
								else {
									echo '<a href="index.php?uc=gererFrais&action=supprimerFrais&idFrais='.$id.'"
								onclick="return confirm(\'Voulez-vous vraiment supprimer ce frais?\');">Supprimer ce frais</a></td>';
								}
						?></tr><?php		 }  ?>	  
			 </table>
		</div> 
	</div>
</div>


    
                            
<div class="col-md-6">
	<div class="content-box-large">
		<div class="panel-heading">
			<legend>Nouvel élément hors forfait</legend>			
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" action="index.php?uc=gererFrais&action=validerCreationFrais" method="post">
				<div class="form-group">
					<div class="form-group">
					<label for="txtDateHF"> Date (jj/mm/aaaa): </label>
					</br>
					<input class="form-control" type="text" id="txtDateHF" name="dateFrais" <?php if ($lesInfosFicheFrais['idEtat']!='CR') { 		echo 'disabled';};   ?>/>
					</br></br>
					<label for="txtLibelleHF">Libellé</label>
					</br>
					<input class="form-control" type="text" id="txtLibelleHF" name="libelle"  <?php if ($lesInfosFicheFrais['idEtat']!='CR') { 		echo 'disabled';};   ?>/>
					</br></br>
					<label for="txtMontantHF">Montant : </label>
					</br>
					<input class="form-control" type="text" id="txtMontantHF" name="montant"  <?php if ($lesInfosFicheFrais['idEtat']!='CR') { 		echo 'disabled';};   ?>/>
					</br></br>
					</div>
				</div>
			<div class="horizontal-form">
				<input class="btn btn-primary" id="ajouter" type="submit" value="Ajouter" <?php if ($lesInfosFicheFrais['idEtat']!='CR') { echo 'disabled';} ?> />
				<input class="btn btn-primary" id="effacer" type="reset" value="Effacer" />
      
			</div>
        
			</form>
							
		</div>
	</div>
</div>



  

