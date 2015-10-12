<div id="bas">
	<?
		$an = date("Y");
        $version_file = fopen('VERSION', 'r');
        $num_version = fgets($version_file);
        fclose($version_file);
	?>
	2009 - <? echo $an ?> / <? echo $etablissement ?> / D&eacute;veloppement : <a href="http://www.ecrins-parcnational.fr">Parc national des Ecrins</a> / Version <? echo $num_version;?>
</div>

