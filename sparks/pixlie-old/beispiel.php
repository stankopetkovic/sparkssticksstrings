<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pixlie 2 - Minimaldemo</title>
</head>
<body>
  <h1>Pixlie 2</h1>
  <?php
    $response_type = 'php';   //Ausgabe auf php umstellen
    include('pixlie.php'); 	  //Einbinden von Pixlie
  ?>


  <h2>Beispielausgabe</h2>
  <?php 
  /*Baue Link zu Bildtyp "s" (small): Pfad zu Pixlie / Pfad zum Bild / _s + Dateiendung*/
  foreach ($pixlie_table_file as $picture){ ?>
    <img
      src="<?php echo $pixlie_table_env['path_pixlie'].$picture['link_get']; ?>_s<?php echo $picture['extension']; ?>"
      alt="<?php echo $picture['name']; ?>" />
   <?php } ?>


  <h2>Erhaltene Arrays</h2>

  <h3>Bilder im aktuellen Ordner</h3>
  <pre><?php print_r($pixlie_table_file); ?></pre>

  <h3>Unterordner im aktuellen Ordner</h3>
  <pre><?php print_r($pixlie_table_dir); ?></pre>

  <h3>Umgebungsdaten</h3>
  <pre><?php print_r($pixlie_table_env); ?></pre>


  <a href="http://www.pixlie.de" title="Bildergalerie, Script, PHP, Foto, Photo, Bilder, Galerie">Pixlie - Das kostenlose PHP Bildergalerie Script</a>
</body>
</html>
