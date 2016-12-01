<div class="col-md-6">
	<div class="content-box-large">
		<div class="panel-heading">
			<div class="panel-title"><h2></h2></div>
			</br></br>
		  <legend>Eléments forfaitisés</legend>			
		</div>
		<div class="panel-body">
			<form class="form-horizontal" role="form" method="POST"  action="index.php?uc=gererFrais&action=validerMajFraisForfait">
			  <div class="form-group">
				<?php
						foreach ($lesFraisForfait as $unFrais)
						{
							$idFrais = $unFrais['idfrais'];
							$libelle = $unFrais['libelle'];
							$quantite = $unFrais['quantite'];
					?>
							<div class="form-group">
								<label for="idFrais"><?php echo $libelle ?></label>
								<input class="form-control" placeholder="<?php echo $quantite?>" type="text" id="idFrais" name="lesFrais[<?php echo $idFrais?>]""<?php echo $quantite?>"<?php if ($lesInfosFicheFrais['idEtat']!='CR') { echo 'disable';} ?> >
							</div>

					<?php
						}
					?>
				</div>
				<input class="btn btn-primary" id="ok" type="submit" value="Valider" size="20" <?php if ($lesInfosFicheFrais['idEtat']!='CR') { echo 'disable';} ?>/>
			  </div>
			</form>
		</div>
	</div>






  