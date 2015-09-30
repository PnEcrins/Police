<!-- Fichier inséré dans le formulaire d'ajout d'une intervention. Positionne un marqueur au centre du Parc. 
Ce marqueur est déplacable et ses coordonnées sont affichées dans un champs à chaque déplacement. Les valeurs x et y en WGS84
seront ensuite stockées dans la BD PostgreSQL -->
    <div id="map_1" style="margin: 0 auto; border: 1px solid rgb(69, 69, 85); padding: 10px; width: 770px; height: 400px; background-color: rgb(229, 227, 223); color: rgb(32, 183, 255); font-size: 16px; font-weight: bold; vertical-align: middle; position: relative;">
	</div>
	<div style="margin: 0 auto; width: 800px">
		<p class="commentaire">
			Cliquez sur la carte pour positionner l'intervention ou saisissez les coordonnées ci-dessous.<br/>
			Recliquez sur la carte pour modifier la localisation de l'intervention.
		</p>
		<hr color="#dcdcdc" > 
		<p>
			Coordonnées du marqueur (WGS84) : <br>
			<!--<span class="commentaire">
				X : <input id="longEnd" value="" maxlength="30" type="text" name="fx" readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur<br>
				Y : <input id="latEnd" value="" maxlength="30" type="text" name="fy"  readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur<br>
			</span/>-->
            X : <input id="longEnd" type="text" name="fx" onChange="create_ol.putPoint('<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>');">
                <span id="commentairex" class="commentaire"> - Saisir vos coordonnées en degrés décimaux (exemple : 6.29571)</span/><br>
            Y : <input id="latEnd" type="text" name="fy" onChange="create_ol.putPoint('<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>');">
                <span id="commentairey" class="commentaire"> - Saisir vos coordonnées en degrés décimaux</span/>
		</p>
		<p>
			<span class="colbord">Commune : </span>
			<span class="colcom">
				<input id="fcomm" name="fcomm" type="hidden">
				<select id="fcomm1" name="fcomm1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_commune , commune
							FROM layers.l_communes
							ORDER BY commune";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.   -->
					<option value="<?=$val['id_commune'];?>"><?=$val['commune']?></option>
						<? } ?>
				</select> <span class="commentaire">- déterminée selon la position du marqueur</span>
			</span>
		</p>
        <p>
			<span class="colbord">Secteur : </span>
			<span class="colcom">
				<input id="fsect" name="fsect" type="hidden">
				<select id="fsect1" name="fsect1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_sect , secteur
							FROM layers.l_secteurs
							ORDER BY id_sect";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.   -->
					<option value="<?=$val['id_sect'];?>"><?=$val['secteur']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute;e selon la position du marqueur</span>
			</span>
		</p>
		<p>
			<span class="colbord">Statut de la zone : </span>
			<span class="colzone">
				<input id="fstatut" name="fstatut" type="hidden">
				<select id="fstatut1" name="fstatut1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
							$sql_infr = "SELECT id_statutzone , statutzone
							FROM interventions.bib_statutszone";
							$result = pg_query($sql_infr) or die ("Erreur requête") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant à la valeur selectionnée.  -->
					<option value="<?=$val['id_statutzone'];?>"><?=$val['statutzone']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute; selon la position du marqueur</span/>
			</span>
		</p>
	</div>