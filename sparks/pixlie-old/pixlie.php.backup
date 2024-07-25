<?php
  ob_start(); //Starte den Ausgabepuffer
  error_reporting(E_ALL);
  clearstatcache();
  /*
    Pixlie ist Donationware und wird unter der MIT Lizenz zur Verfuegung gestellt.

    Donationware:

    Bei der Donationware (von engl. "donation": Spende) handelt es sich um kostenlose 
    Software, wobei der Autor um eine Spende in beliebiger Hoehe bittet, um die durch 
    Weiterentwicklung oder Verbreitung der Software entstehenden Kosten zu 
    kompensieren (zum Beispiel Server-Kosten). 
    Wenn das eingesetzte Programm die Erwartungen erfuellt, und man es regelmaessig 
    im Einsatz hat, solle man eine dementsprechende Verguetung in Erwaegung ziehen.

    MIT Lizenz (Deutsche Uebersetzung)

    Copyright (c) 2008 Steffen Hagdorn

    Hiermit wird unentgeltlich, jeder Person, die eine Kopie der Software 
    und der zugehoerigen Dokumentationen (die "Software") erhaelt, die Erlaubnis 
    erteilt, uneingeschraenkt zu benutzen, inklusive und ohne Ausnahme, dem Recht, 
    sie zu verwenden, kopieren, aendern, fusionieren, verlegen, verbreiten, 
    unterlizenzieren und/oder zu verkaufen, und Personen, die diese Software 
    erhalten, diese Rechte zu geben, unter den folgenden Bedingungen:

    Der obige Urheberrechtsvermerk und dieser Erlaubnisvermerk sind in alle 
    Kopien oder Teilkopien der Software beizulegen.

    DIE SOFTWARE WIRD OHNE JEDE AUSDRUeCKLICHE ODER IMPLIZIERTE GARANTIE 
    BEREITGESTELLT, EINSCHIESSLICH DER GARANTIE ZUR BENUTZUNG FUeR DEN 
    VORGESEHENEN ODER EINEM BESTIMMTEN ZWECK SOWIE JEGLICHER 
    RECHTSVERLETZUNG, JEDOCH NICHT DARAUF BESCHRAeNKT. IN KEINEM 
    FALL SIND DIE AUTOREN ODER COPYRIGHTINHABER FUeR JEGLICHEN SCHADEN 
    ODER SONSTIGE ANSPRUCH HAFTBAR ZU MACHEN, OB INFOLGE DER ERFUeLLUNG 
    VON EINEM VERTRAG, EINEM DELIKT ODER ANDERS IM ZUSAMMENHANG MIT 
    DER BENUTZUNG ODER SONSTIGE VERWENDUNG DER SOFTWARE ENTSTANDEN.


    Inhalt / Aufbau:
    ------------------------------------------------------
      1. Konfiguration
      - 1.1 Bildgroessen 
      - 1.2 Sortierung
      - 1.3 Sonstige Einstellungen
      - 1.4 Systemkonfiguration
      2. Sprachausgaben
      3. Fehlerbehandlung
      4. Umgebungs- und Installationspruefung
      5. Entgegennehmen der Query-Variable
      6. Auf Hackerangriffe pruefen
      7. Umwandlung in UTF-8
      8. Verarbeitung eine Fotos
      - 8.1 Angeforderte Bildgroesse der Query Variable pruefen
      - 8.2 Cache Dateinamen und Dateipfade generieren
      - 8.3 Pruefen ob Datei schon im Cache liegt
        - 8.3.1 Berechne Bilder nach dem Typ "cut"
        - 8.3.2 Berechne Bilder nach dem Typ "uncut"
      - 8.4 Ausgabe des Bildes
      9. Verarbeitung eines Ordners
      - 9.1 Weiche für Ordner / Bild
      - 9.2 JPG Grunddaten lesen
      - 9.3 IPTC Metadaten lesen
      - 9.4 EXIF Metadaten lesen
      - 9.5 Ordner Grunddaten lesen
      - 9.6 Sortierung der Datentabellen
      - 9.7 Ausgabe via JSON


   *************************************************************************************************
   *************************************** 1. Konfiguration ****************************************
   *************************************************************************************************


   **************************************** 1.1 Bildgroessen *****************************************

     Vordefinierte Standardbildgroessen:
      s  = small     | cut    | 75/75
      t  = thumbnail | uncut  | max 100
      m  = medium    | uncut  | max 240
      d  = default   | uncut  | max 500 (default)
      b  = big       | uncut  | max 1024
      o  = original  | none   | original

     Unterschied "cut" und "uncut":
       Wird der Bildberechnungstyp "cut" verwendet, wird das Bild auf eine feste Hoehe und Breite
       aus dem Originalbild ohne verzerren ausgeschnitten. Hierfuer die Groesse in Form 
       von "hoehe/breite" angegeben. Beispiel: Einstellung 100/50 | Originalbild 400/800 = Ausgabe 100/50

       Beim Bildberechnungstyp "uncut" wird das Bild unter beibehaltung der Seitenlaengen auf die 
       gewuenschte Groesse angepasst. Es muss nur der Wert angegeben werden, wie lange eine Seitenlaenge
       maximal sein darf. Beispiel: Einstellung 100 | Originalbild 400/800 = Ausgabe 50/100

     Benutzung 'overwrite_cache':
       Zum Testen ist es sinnvoll 'overwrite_cache' auf 'on' zu stellen. Bei jedem Seitenaufruf wird der 
       Cache wieder mit neuen Daten ueberschrieben! Diese Option ist unter 1.3 (Sonstige Einstellungen) 
       zu finden.
       HINWEIS: DASS DER CACHE GENUTZT WIRD, MUSS DIESE EINSTELLUNG IM LIVE-BETRIEB WIEDER AUF 'off' STEHEN! 

     Frei definierbare Bildgroessen:
       Nachfolgend koennen beliebig viele eigene Bildgroessen definiert werden. Die Bildabkuerzung s,t,m,d,b und o 
       sind schon vorbelegt! Das Kuerzel des Bildtyps darf nur ein Zeichen sein! 

     LEERE DEN CACHE-ORDER NACH DEM AeNDERN DIESER WERTE ODER VERWENDE 'overwrite_cache'!*/

  $pixlie_imageconfig = array(
    'x' => array('type'=>'uncut','size'=>'160,160'),
    'l' => array('type'=>'uncut','size'=>'700')
  );


  /**************************************** 1.2 Sortierung ****************************************

    Beispiele fuer die Sortierung:

    1. Sortiere zuerst absteigend nach dem Hochladezeitpunkt, danach absteigend nach dem Speicherdatum 
       des Originalfotos. Behandle beide Datumsangaben als Zahlen.
       first:  ctime, SORT_DESC, SORT_NUMERIC
       second: mtime, SORT_DESC, SORT_NUMERIC

    2. Sortiere zuerst aufsteigend nach dem Dateinamen, danach absteigend nach dem IPTC Feld "Stadt".
       Behandle beide Werte als Zeichenkette. Eine Liste der unterstuetzten IPTC Werte ist weiter unten 
       bei "$pixlie_iptc_codes" zu finden.
       first:  name , SORT_ASC, SORT_STRING
       second: iptc_city, SORT_DESC, SORT_STRING 
  */

  $pixlie_sortconfig = array(
    'sort_file_first_row'=>'ctime',             //name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
    'sort_file_first_order'=>SORT_DESC,         //SORT_DESC, SORT_ASC 
    'sort_file_first_type'=>SORT_NUMERIC,       //SORT_REGULAR, SORT_NUMERIC, SORT_STRING
    'sort_file_second_row'=>'mtime',            //name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
    'sort_file_second_order'=>SORT_DESC,        //SORT_DESC, SORT_ASC 
    'sort_file_second_type'=>SORT_NUMERIC,      //SORT_REGULAR, SORT_NUMERIC, SORT_STRING
    'sort_dir_first_row'=>'name',               //name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
    'sort_dir_first_order'=>SORT_ASC,           //SORT_DESC, SORT_ASC 
    'sort_dir_first_type'=>SORT_STRING,         //SORT_REGULAR, SORT_NUMERIC, SORT_STRING
    'sort_dir_second_row'=>'ctime',             //name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
    'sort_dir_second_order'=>SORT_DESC,         //SORT_DESC, SORT_ASC  
    'sort_dir_second_type'=>SORT_NUMERIC        //SORT_REGULAR, SORT_NUMERIC, SORT_STRING
  );


  /********************************** 1.3 Sonstige Einstellungen **********************************

  WICHTIG: STELLEN SIE IM LIVE BETRIEB NACH DER ENTWICKLUNG 'overwrite_cache' AUF AUS!!!
  Hinweis zu 'http_method': Viele Javascript Bibliotheken koennen Pixlie PATHINFO nicht umsetzen. */
  
  $pixlie_userconfig = array(
    'overwrite_cache' => 'off',                 //Cache bei jedem Aufruf neu erstellen: on = an, off = aus 
    'pic_image_quality' => 90,                  //Bildqualitaet: 0 = schlechteste , 100 = beste
    'show_metadata_iptc' => 'on',               //IPTC-Ausgabe: on = an, off = aus
    'show_metadata_exif' => 'off',              //EXIF-Ausgabe: on = an, off = aus 
    'dir_cache' => dirname(__FILE__).'/cache',  //Pfad zum Cache Verzeichnis
    'dir_upload' => dirname(__FILE__).'/upload',//Pfad zum Upload Verzeichnis
    'http_method' => 'GET',                     //Uebermittlungsmethode der Query-Variable: PATHINFO oder GET
    'http_var_name' => 'q',                     //Name der Query-Variablen die per http_method uebergeben wird
    'response_type' => 'json'                   //Art der Antwort: direkt = json, include = php
  );

  //Namen der IPTC Felder in der Tabelle "$pixlie_table_file"
  $pixlie_iptc_codes = array(
    '2#005'=>'iptc_object_name',                //Name des Objektes
    '2#007'=>'iptc_edit_status',                //Der Bearbeitungsstatus
    '2#010'=>'iptc_priority',                   //Die Prioritaet
    '2#015'=>'iptc_category',                   //Die Kategorie
    '2#020'=>'iptc_supplemental_category',      //Zusaetzliche Kategorien wenn vorhanden
    '2#025'=>'iptc_keywords',                   //Keywoerter fuer die Suche
    '2#030'=>'iptc_release_date',               //Datum des Bildes
    '2#035'=>'iptc_release_time',               //Uhrzeit des Bildes
    '2#040'=>'iptc_special_instructions',       //Besondere Hinweise zu dem Bild
    '2#045'=>'iptc_reference_service',          //Referenzen auf den Bilderservice
    '2#047'=>'iptc_reference_date',             //Referenzen auf das Datum (Bildarchiv)
    '2#050'=>'iptc_reference_number',           //Referenznummer fuer die Identifikation
    '2#055'=>'iptc_created_date',               //Datum des Fotos
    '2#060'=>'iptc_created_time',               //Uhrzeit des Fotos
    '2#062'=>'iptc_digital_creation_date',      //Datum des Fotos
    '2#063'=>'iptc_digital_creation_time',      //Uhrzeit des Fotos
    '2#065'=>'iptc_originating_program',        //Programm mit dem das Foto erstellt wurde
    '2#070'=>'iptc_program_version',            //Version des Programms
    '2#080'=>'iptc_byline',                     //Name des Autors (Fotografen)
    '2#085'=>'iptc_byline_title',               //Titel des Fotografen
    '2#090'=>'iptc_city',                       //Stadt
    '2#092'=>'iptc_sublocation',                //Lokation oder Ort
    '2#095'=>'iptc_province_state',             //Bundesland
    '2#100'=>'iptc_country_code',               //Laendercode nach [ISO 3166-1]
    '2#101'=>'iptc_country',                    //Laendername
    '2#105'=>'iptc_headline',                   //Titel des Fotos
    '2#115'=>'iptc_source',                     //Quelle
    '2#116'=>'iptc_copyright',                  //Copyright Text
    '2#118'=>'iptc_contact',                    //Internetadresse
    '2#120'=>'iptc_caption',                    //Beschreibung
    '2#122'=>'iptc_caption_writer',             //Autor der Beschreibung
    '2#150'=>'iptc_content_preview',            //Vorschau
    '2#200'=>'iptc_custom_field_01',            //Frei verwendbare Textfelder
    '2#201'=>'iptc_custom_field_02',            //Frei verwendbare Textfelder
    '2#202'=>'iptc_custom_field_03',            //Frei verwendbare Textfelder
    '2#203'=>'iptc_custom_field_04',            //Frei verwendbare Textfelder
    '2#204'=>'iptc_custom_field_05',            //Frei verwendbare Textfelder
    '2#205'=>'iptc_custom_field_06',            //Frei verwendbare Textfelder
    '2#206'=>'iptc_custom_field_07',            //Frei verwendbare Textfelder
    '2#207'=>'iptc_custom_field_08',            //Frei verwendbare Textfelder
    '2#208'=>'iptc_custom_field_09',            //Frei verwendbare Textfelder
    '2#209'=>'iptc_custom_field_10',            //Frei verwendbare Textfelder
    '2#210'=>'iptc_custom_field_11',            //Frei verwendbare Textfelder
    '2#211'=>'iptc_custom_field_12',            //Frei verwendbare Textfelder
    '2#212'=>'iptc_custom_field_13',            //Frei verwendbare Textfelder
    '2#213'=>'iptc_custom_field_14',            //Frei verwendbare Textfelder
    '2#214'=>'iptc_custom_field_15',            //Frei verwendbare Textfelder
    '2#215'=>'iptc_custom_field_16',            //Frei verwendbare Textfelder
    '2#216'=>'iptc_custom_field_17',            //Frei verwendbare Textfelder
    '2#217'=>'iptc_custom_field_18',            //Frei verwendbare Textfelder
    '2#218'=>'iptc_custom_field_19',            //Frei verwendbare Textfelder
    '2#219'=>'iptc_custom_field_20',            //Frei verwendbare Textfelder
    '2#230'=>'iptc_document_notes',             //Hinweise zu dem Dokument
    '2#231'=>'iptc_document_history',           //Historie des Dokumentes
    '2#232'=>'iptc_exif_camera_info'            //Binaere EXIF Kameradaten (nicht editierbar)
  );


  /*********************************** 1.4 Systemkonfiguration*************************************/

  $pixlie_sysconfig = array(
    'dir_cache_testfile' => '/writetest.tmp',   //Dateiname zum Pruefen der Cache-Schreibrechte
    'php_req_vers' => '4.0.0'                   //erforderliche PHP Version
  );
   
  /* Bitte aender diese Standardtisierten Bildwerte nicht. Eigene koennen weiter oben unter dem
     Punkt 1.1 (Bildgroessen) erstellt werden. */
  $pixlie_sys_imageconfig = array(
    's' => array('type'=>'cut',  'size'=>'75,75'),
    't' => array('type'=>'uncut','size'=>'100'),
    'm' => array('type'=>'uncut','size'=>'240'),
    'd' => array('type'=>'uncut','size'=>'500'),
    'b' => array('type'=>'uncut','size'=>'1024'),
    'o' => array('type'=>'none', 'size'=>'original'),
  ); 

  $pixlie_image_sizes = array_merge($pixlie_sys_imageconfig,$pixlie_imageconfig);
  $pixlie_config = array_merge($pixlie_userconfig,$pixlie_sortconfig,$pixlie_sysconfig);

  // PHP Remote Config
  if(isset($response_type)){
    switch($response_type){
        case 'php': $pixlie_config['response_type'] = 'php';
                     break;
        case 'json': $pixlie_config['response_type'] = 'json';
                     break;}}


  /*************************************** 2. Sprachausgaben **************************************/

  $pixlie_lang = array(
    'conf_cache_dir' => 'Pixlie benoetigt schreibenden Zugriff auf das Cache-Verzeichnis.',
    'conf_php_vers' => 'Pixlie benoetigt mindestens PHP in der Version 4.0.0.',
    'conf_http_method' => 'Der Wert http_method in der Config hat einen falschen Wert.',
    'conf_xss_prot' => 'Der XSS Schutz hat eine nicht gueltige Zeichenfolge in der Query erkannt.',
    'pic_fileexists_false' => 'Das angeforderte Bild existiert nicht.',
    'pic_rendertype_false' => 'Der hinterlegte Rendertyp existiert nicht.',
    'dir_opendir_false' => 'Das angeforderte Verzeichnis kann nicht gelesen werden.'
  );


  /************************************** 3. Fehlerbehandlung *************************************/

  if(function_exists('pixlie_error')==false){
    function pixlie_error($msg){
      global $pixlie_config;
      if ($pixlie_config['response_type'] == 'json') {
      	$pixlie_table_env['status'] = false;
      	$pixlie_table_env['errormsg'] = $msg;
        die(json_encode(array('pixlie_table_env'=>$pixlie_table_env)));}
      else{
        die($msg);}}}
    

  /***************************** 4. Umgebungs- und Installationspruefung ***************************/

  //cache check
  if(!($testfile_handler = @fopen($pixlie_config['dir_cache'].$pixlie_config['dir_cache_testfile'], 'w'))){
    pixlie_error($pixlie_lang['conf_cache_dir']);}
  else{
    fclose($testfile_handler);
    unlink($pixlie_config['dir_cache'].$pixlie_config['dir_cache_testfile']);}
    
  //PHP version-check
  if( phpversion() < $pixlie_config['php_req_vers'] ){
    pixlie_error($pixlie_lang['conf_php_vers']);}    


  /****************************** 5. Entgegennehmen der Query-Variable ****************************/

  switch ($pixlie_config['http_method']){
    case 'GET':      if(isset($_GET[$pixlie_config['http_var_name']])){
                       $pixlie_query = $_GET[$pixlie_config['http_var_name']];}
                     else{
                       $pixlie_query = '';}
                     break;
    case 'PATHINFO': if(isset($_SERVER['PATH_INFO'])){
                       $pixlie_query = $_SERVER['PATH_INFO'];}
                     else{
                       $pixlie_query = '';}
                     break;
    default:         pixlie_error($pixlie_lang['conf_http_method']);
                     break;}
                     

  /********************************* 6. Auf Hackerangriffe pruefen *********************************/

  //cross-site scripting (XSS-Schutz)
  $pixlie_query = eregi_replace('\.\.','',$pixlie_query);
  $pixlie_query = eregi_replace('//','/',$pixlie_query);
  if ((eregi("<[^>]*script*\"?[^>]*>", $pixlie_query)) ||
      (eregi("<[^>]*object*\"?[^>]*>", $pixlie_query)) ||
      (eregi("<[^>]*iframe*\"?[^>]*>", $pixlie_query)) ||
      (eregi("<[^>]*applet*\"?[^>]*>", $pixlie_query)) ||
      (eregi("<[^>]*meta*\"?[^>]*>", $pixlie_query))   ||
      (eregi("<[^>]*style*\"?[^>]*>", $pixlie_query))  ||
      (eregi("<[^>]*form*\"?[^>]*>", $pixlie_query))   ||
      (eregi("\([^>]*\"?[^)]*\)", $pixlie_query))      ||
      (eregi("\"", $pixlie_query))) {  
        pixlie_error($pixlie_lang['conf_xss_prot']);die();}


  /************************************ 7. Umwandlung in UTF-8 ************************************/

  $pixlie_query = utf8_decode($pixlie_query);


  /*************************************************************************************************
   ********************************* 8. Verarbeitung eines Fotos ***********************************
   ************************************************************************************************/

  if(eregi('.jpg',$pixlie_query)){


  /********************** 8.1 Angeforderte Bildgroesse der Query Variable pruefen ********************/

    if(false !== ereg('.*_(.)(\....)',$pixlie_query,$pixlie_reg_picsize)){
      $pixlie_item_picsize = $pixlie_reg_picsize[1];
      $pixlie_item_extension = $pixlie_reg_picsize[2]; 
      $pixlie_query = ereg_replace('_'.$pixlie_item_picsize.$pixlie_item_extension ,
        $pixlie_item_extension,$pixlie_query);}
    else{
      $pixlie_item_picsize = 'd';}


  /************************ 8.2 Cache Dateinamen und Dateipfade generieren ************************/

    $pixlie_item_path  = $pixlie_config['dir_upload'].$pixlie_query;
    $pixlie_cache_name = md5($pixlie_query).'_'.$pixlie_item_picsize.'.jpg';
    $pixlie_cache_path = $pixlie_config['dir_cache'].'/'.$pixlie_cache_name;


  /*************************** 8.3 Pruefen ob Datei schon im Cache liegt ***************************/

    if(file_exists($pixlie_item_path)){
      if((file_exists($pixlie_cache_path)==false)||($pixlie_config['overwrite_cache']=='on')){
        switch ($pixlie_image_sizes[$pixlie_item_picsize]['type']){


  /*************************** 8.3.1 Berechne Bilder nach dem Typ "cut" ***************************/

          case 'cut':
            $pixlie_render_size = explode(',',$pixlie_image_sizes[$pixlie_item_picsize]['size']);
            @ini_set('memory_limit', '50M');
            $src_img = imagecreatefromjpeg($pixlie_item_path);
            if((imagesy($src_img) / imagesx($src_img) * $pixlie_render_size[0]) >$pixlie_render_size[1] ){
              $src_w = imagesx($src_img);
              $src_h = round((imagesx($src_img)/$pixlie_render_size[0])*$pixlie_render_size[1]);
              $src_x = (imagesy($src_img)-$src_h) / 4;
              $src_y = 0;}
            else{
              $src_h = imagesy($src_img);
              $src_w = round((imagesy($src_img)/$pixlie_render_size[1])*$pixlie_render_size[0]);
              $src_y = (imagesx($src_img)-$src_w) / 2;
              $src_x = 0;}
            $dst_img = imagecreatetruecolor($pixlie_render_size[0],$pixlie_render_size[1]);
            imagecopyresampled($dst_img,$src_img,0,0,$src_y,$src_x,$pixlie_render_size[0],
              $pixlie_render_size[1],$src_w,$src_h);
            imagejpeg($dst_img, $pixlie_cache_path, $pixlie_config['pic_image_quality']);
            break;


  /************************** 8.3.2 Berechne Bilder nach dem Typ "uncut" **************************/

          case 'uncut':
            $pixlie_render_size = $pixlie_image_sizes[$pixlie_item_picsize]['size'];
            @ini_set('memory_limit', '50M');
            $src_img = imagecreatefromjpeg($pixlie_item_path);
            if(imagesx($src_img)==imagesy($src_img)){
              $dst_w = $pixlie_render_size;
              $dst_h = $pixlie_render_size;}
            elseif (imagesx($src_img) > imagesy($src_img)){
              $dst_w = round( $pixlie_render_size / imagesx($src_img) * imagesy($src_img));
              $dst_h = $pixlie_render_size;}
            else{
              $dst_w = $pixlie_render_size;
              $dst_h = round($pixlie_render_size / imagesy($src_img) * imagesx($src_img));}
            $dst_img = imagecreatetruecolor($dst_h,$dst_w);
            imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_h,$dst_w,imagesx($src_img),imagesy($src_img));
            imagejpeg($dst_img, $pixlie_cache_path, $pixlie_config['pic_image_quality']);
            break;
          case 'none': $pixlie_cache_path = $pixlie_item_path;
            break;
          default: pixlie_error($pixlie_lang['pic_rendertype_false']);;
            break;}}


  /*********************************** 8.4 Ausgabe des Bildes *************************************/

        header('Content-Type: image/jpeg');
        readfile($pixlie_cache_path);
        //imagejpeg(imagecreatefromjpeg($pixlie_cache_path)); //alternativ
    }
    else{
      pixlie_error($pixlie_lang['pic_fileexists_false']);}}
  else{


  /*************************************************************************************************
   ******************************** 9. Verarbeitung eines Ordners **********************************
   ************************************************************************************************/

    $pixlie_table_file = array();
    $pixlie_table_dir = array();
    $pixlie_table_env = array();
    $pixlie_key_counter_file = 0;
    $pixlie_key_counter_dir = 0;
    if($pixlie_dir_handle = @opendir($pixlie_config['dir_upload'].$pixlie_query)){


  /********************************** 9.1 Weiche für Ordner / Bild *********************************/

      while(false !== ($pixlie_item_name = readdir($pixlie_dir_handle))){
        if ($pixlie_item_name != "." && $pixlie_item_name != "..") {
          $pixlie_item_path = $pixlie_config['dir_upload'].$pixlie_query.'/'.$pixlie_item_name;
          if((filetype($pixlie_item_path)=='file')&&(eregi('.jpg',$pixlie_item_name))){


  /************************************* 9.2 JPG Grunddaten lesen *********************************/
  
            $pixlie_item_key = $pixlie_key_counter_file;
            $pixlie_key_counter_file ++;

            $pixlie_table_file[$pixlie_item_key]['name'] = 
              utf8_encode(eregi_replace('\.jpg','',$pixlie_item_name));

            $pixlie_link_file = urlencode($pixlie_table_file[$pixlie_item_key]['name']);
            $pixlie_link_query = ereg_replace('%2F','/',urlencode(utf8_encode($pixlie_query)));

            if($pixlie_query==''){
              $pixlie_table_file[$pixlie_item_key]['link_get'] = 
              '?'.$pixlie_config['http_var_name'].'=/'.$pixlie_link_file;}
            else{
              $pixlie_table_file[$pixlie_item_key]['link_get'] = 
              '?'.$pixlie_config['http_var_name'].'='.$pixlie_link_query.'/'.$pixlie_link_file;}
            if($pixlie_query==''){
              $pixlie_table_file[$pixlie_item_key]['link_pathinfo'] = 
              '/'.$pixlie_link_file;}
            else{
              $pixlie_table_file[$pixlie_item_key]['link_pathinfo'] = 
              $pixlie_link_query.'/'.$pixlie_link_file;}

            $pixlie_table_file[$pixlie_item_key]['atime'] = fileatime($pixlie_item_path);
            $pixlie_table_file[$pixlie_item_key]['ctime'] = filectime($pixlie_item_path);
            $pixlie_table_file[$pixlie_item_key]['mtime'] = filemtime($pixlie_item_path);
            $pixlie_table_file[$pixlie_item_key]['size']  = filesize($pixlie_item_path);
            ereg('.*(\....)',$pixlie_item_name,$pixlie_reg_extension);
            $pixlie_table_file[$pixlie_item_key]['extension'] = $pixlie_reg_extension[1];


  /*********************************** 9.3 IPTC Metadaten lesen ***********************************/

            if($pixlie_config['show_metadata_iptc']=='on'){
              getimagesize($pixlie_item_path, &$iptc_info);
              if(isset($iptc_info["APP13"])){
                $iptc_data = iptcparse($iptc_info["APP13"]);
                if(is_array($iptc_data)) {  
                  foreach ($iptc_data as $iptc_key => $iptc_value){
                    if($iptc_key != '2#000'){
                      if(count($iptc_value)>1){
                        $pixlie_table_file[$pixlie_item_key][$pixlie_iptc_codes[$iptc_key]] = 
                        utf8_encode(implode(',',$iptc_value));}
                      else{
                        @$pixlie_table_file[$pixlie_item_key][$pixlie_iptc_codes[$iptc_key]] = 
                        utf8_encode($iptc_value[0]); }}}}}}


  /*********************************** 9.4 EXIF Metadaten lesen ***********************************/

            if($pixlie_config['show_metadata_exif']=='on'){
              $exif_data = exif_read_data($pixlie_item_path ,1, true);
              if($exif_data!=false){    
                foreach ($exif_data as $exif_key => $exif_section) { 
                  foreach ($exif_section as $exif_name => $exif_value) {
                    if((strtolower($exif_key)!='exif')&&(strtolower($exif_key)!='makernote')&&
                       (stristr($exif_name,'undefined')== false)){
                     $pixlie_table_file[$pixlie_item_key]['exif_'.strtolower($exif_key).'_'.
                       strtolower($exif_name)] = utf8_encode($exif_value); }}}}}}
          elseif(filetype($pixlie_item_path)=='dir'){


  /*********************************** 9.5 Ordner Grunddaten lesen ********************************/

            $pixlie_item_key = $pixlie_key_counter_dir;
            $pixlie_key_counter_dir ++;

            $pixlie_link_file = urlencode(utf8_encode($pixlie_item_name));
            $pixlie_link_query = ereg_replace('%2F','/',urlencode(utf8_encode($pixlie_query)));

            if($pixlie_query==''){
              $pixlie_table_dir[$pixlie_item_key]['link_get'] = 
              '?'.$pixlie_config['http_var_name'].'=/'.$pixlie_link_file;}
            else{
              $pixlie_table_dir[$pixlie_item_key]['link_get'] = 
              '?'.$pixlie_config['http_var_name'].'='.$pixlie_link_query.'/'.$pixlie_link_file;}
            if($pixlie_query==''){
              $pixlie_table_dir[$pixlie_item_key]['link_pathinfo'] = '/'.$pixlie_link_file;}
            else{
              $pixlie_table_dir[$pixlie_item_key]['link_pathinfo'] = 
                $pixlie_link_query.'/'.$pixlie_link_file;}
            $pixlie_table_dir[$pixlie_item_key]['name'] = utf8_encode($pixlie_item_name);
            $pixlie_table_dir[$pixlie_item_key]['atime'] = fileatime($pixlie_item_path);
            $pixlie_table_dir[$pixlie_item_key]['ctime'] = filectime($pixlie_item_path);
            $pixlie_table_dir[$pixlie_item_key]['mtime'] = filemtime($pixlie_item_path);}}}
      closedir($pixlie_dir_handle);
      $pixlie_table_env['status'] = true;
      $pixlie_table_env['numberof_file'] = count($pixlie_table_file);
      $pixlie_table_env['numberof_dir']  = count($pixlie_table_dir);
      $pixlie_table_env['query']  = utf8_encode($pixlie_query);
      $pixlie_table_env['path_pixlie'] =  'http://'.$_SERVER['HTTP_HOST'].
        eregi_replace($_SERVER['DOCUMENT_ROOT'],'',__FILE__);
    }
    else{
        pixlie_error($pixlie_lang['dir_opendir_false']);}


  /******************************* 9.6 Sortierung der Datentabellen *******************************/

   if(count($pixlie_table_file)>1){
     foreach ($pixlie_table_file as $pixlie_sort_key => $pixlie_sort_row){
       $pixlie_first_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_file_first_row']];
       $pixlie_second_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_file_second_row']];}
     array_multisort($pixlie_first_sort_row,  $pixlie_config['sort_file_first_order'], 
                     $pixlie_config['sort_file_first_type'], $pixlie_second_sort_row, 
                     $pixlie_config['sort_file_second_order'], $pixlie_config['sort_file_second_type'],
                     $pixlie_table_file);
     unset($pixlie_first_sort_row,$pixlie_second_sort_row);}
   if(count($pixlie_table_dir)>1){
     foreach ($pixlie_table_dir as $pixlie_sort_key => $pixlie_sort_row){
       $pixlie_first_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_dir_first_row']];
       $pixlie_second_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_dir_second_row']];}
     array_multisort($pixlie_first_sort_row,  $pixlie_config['sort_dir_first_order'], 
                     $pixlie_config['sort_dir_first_type'], $pixlie_second_sort_row, 
                     $pixlie_config['sort_dir_second_order'], $pixlie_config['sort_dir_second_type'],
                     $pixlie_table_dir);
     unset($pixlie_first_sort_row,$pixlie_second_sort_row);}


  /************************************* 9.7 Ausgabe via JSON *************************************/

   switch($pixlie_config['response_type']){
     case 'php': 
         //no content output. use the var $pixlie_table_file and $pixlie_table_dir in your php file
       break;
     case 'json':
       header("Content-Type: text/html; charset=utf-8");
       $json = array(
         'pixlie_table_env'=> $pixlie_table_env,
         'pixlie_table_dir'=> $pixlie_table_dir,
         'pixlie_table_file'=> $pixlie_table_file);
       echo json_encode($json);
       break;}

  //Diese Arrays koennen bei einem include verwendet werden.
  /* 
    //Testausgabe:
    echo '<pre>';
    print_r($pixlie_table_env);
    print_r($pixlie_table_file);
    print_r($pixlie_table_dir);
    echo '</pre>';
  */
  }
ob_flush(); //Ausgabe des Puffers
?>