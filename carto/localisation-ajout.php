<!-- Fichier ins�r� dans le formulaire d'ajout d'une intervention. Positionne un marqueur au centre du Parc. 
Ce marqueur est d�placable et ses coordonn�es sont affich�es dans un champs � chaque d�placement. Les valeurs x et y en WGS84
seront ensuite stock�es dans la BD PostgreSQL -->
	<div id="map_1" style="margin: 0 auto; border: 1px solid rgb(69, 69, 85); padding: 10px; width: 770px; height: 400px; background-color: rgb(229, 227, 223); color: rgb(32, 183, 255); font-size: 16px; font-weight: bold; vertical-align: middle; position: relative;">
	</div>
	<div style="margin: 0 auto; width: 800px">
		<p class="commentaire">
			1 - Placez approximativement le marqueur &agrave; l'aide de la souris. <br>
			2 - D&eacute;placez vous et zoomez (&agrave; l'aide des outils en haut &agrave; gauche ou grace &agrave; la molette de la souris) pour vous localiser plus precis&eacute;ment. <br>
			3 - Ajustez la position du marqueur.
		</p>
		<hr color="#dcdcdc" > 
		<p>
			Coordonn&eacute;es du marqueur : <br>
			<span class="commentaire">
				X : <input id="longEnd" value="" maxlength="30" type="text" name="fx" readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur<br>
				Y : <input id="latEnd" value="" maxlength="30" type="text" name="fy"  readonly="true" class="commentaire" style="border:0px"> - d&eacute;termin&eacute; selon la position du marqueur
			</span/>
		</p>
		<p>
			<span class="colbord">Commune : </span>
			<span class="colcom">
				<input id="fcomm" name="fcomm" type="hidden">
				<select id="fcomm1" name="fcomm1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire li�e pour renseigner la liste d�roulante
							$sql_infr = "SELECT id_commune , commune
							FROM layers.l_communes
							ORDER BY commune";
							$result = pg_query($sql_infr) or die ("Erreur requ�te") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant � la valeur selectionn�e.   -->
					<option value="<?=$val['id_commune'];?>"><?=$val['commune']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute;e selon la position du marqueur</span>
			</span>
		</p>
        <p>
			<span class="colbord">secteur : </span>
			<span class="colcom">
				<input id="fsect" name="fsect" type="hidden">
				<select id="fsect1" name="fsect1" disabled ="disabled">
					<option value="">...</option>
						<?
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire li�e pour renseigner la liste d�roulante
							$sql_infr = "SELECT id_sect , secteur
							FROM layers.l_secteurs
							ORDER BY id_sect";
							$result = pg_query($sql_infr) or die ("Erreur requ�te") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant � la valeur selectionn�e.   -->
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
							//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire li�e pour renseigner la liste d�roulante
							$sql_infr = "SELECT id_statutzone , statutzone
							FROM interventions.bib_statutszone";
							$result = pg_query($sql_infr) or die ("Erreur requ�te") ;
							while ($val = pg_fetch_assoc($result)){
						?>
						<!--  Stocker l'id correspondant � la valeur selectionn�e.  -->
					<option value="<?=$val['id_statutzone'];?>"><?=$val['statutzone']?></option>
						<? } ?>
				</select> <span class="commentaire">- d&eacute;termin&eacute; selon la position du marqueur</span/>
			</span>
		</p>
	</div>