<?php

/**
* SOS_Class: Basisklasse der SOS
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/07/22
* @since        1.0-2003/02/04
*
* @access       public
* @package      CLASS
*/

class SOS_Class {

  /** @access public */

  /** Objekt einer Historienklasse zum verzögerten Ausführen von Operationen, z.B. SOS_Connection */
  var $history;

  /** assoz. Array der assoz. Objekte */
  var $registered_objects   = array();

  /** assoz. Array der Optionen */
  var $options              = array();

  /** Verzeichnis für Graphiken: Pakete bilden Unterverzeichnisse und überschreiben die Eigenschaft */
  var $img_dir              = '';

  /** Fehler-Flag */
  var $err                  = 0;

  /** Fehler-Code */
  var $err_code             = '';

  /** Fehler-Meldung */
  var $err_msg              = '';
  
  /** Exception-Name */
  var $err_exception        = '';

  /** Name der Protokolldatei */
  var $log_file             = 'sos.log';

  /** Log-Level für Protokollausgaben (in Datei) */
  var $log_level            = 0;

  /** Log-Modus: (a)ppend | (w)rite && (b)inary */
  var $log_mode             = 'ab';
  
  /** Flag für geöffnete Protokollldatei */
  var $log_opened           = 0;
  
  /** Debug-Level für Protokollausgaben (in Browser) */
  var $debug_level          = 0;

  /** Objekt der Klasse SOS_Profiler */
  var $profiler;

  /** Objekt der Klasse SOS_ACL: Access Control List */
  var $acl;
  
  /** Sprachsteuerung: Verzeichnis der Sprachdateien */
  var $lang_dir             = 'languages/class/';
  
  /** Sprachsteuerung: Sprache (bestimmt Name des Unterverzeichnisses unter 'lang_dir')*/
  var $language             = 'de';
  
  /** Sprachsteuerung: Übersetzungsarray */
  var $translations         = array();


  /** @access private */

  /** Name der Datei, in der der Fehler auftritt */
  var $err_file    = '';

  /** Zeile in der Datei, in der der Fehler auftritt */
  var $err_line    = '';

  /** Backtrace-Info */
  var $backtrace   = array();


  /**
  * Konstruktor
  * 
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */   
  
  function SOS_Class () {
    
    /** Debug-Level lokal zur Instanz der Klasse oder global für alle Instanzen */
    # $this->declare_option( 'debug_scope', 'local', array('local', 'global') );

    if ( isset($GLOBALS['sos_debug_level']) ) { $this->debug_level = $GLOBALS['sos_debug_level']; }
    if ( isset($GLOBALS['sos_log_level']) )   { $this->log_level   = $GLOBALS['sos_log_level']; }
    
    if( defined('SOS_LANG') )                 { $this->language    = SOS_LANG; }
  }  
    

  /**
  * Objekt instantiieren
  * 
  * Der Funktion wird der Name einer Klasse als String übergeben.
  * Ein Objekt der Klasse wird zurückgegeben
  *
  * @param    string  $class Name der Klasse
  * @param    string  $include_path Pfad, aus dem die Klassendefinition gelesen wird
  * @param    string  $extension Namenserweiterung der Datei mit Klassendefinition
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */   

  function &create_object( $class, $include_path='./', $extension='.inc.php' ) {

    if ( !class_exists( $class) ) { require( $include_path . strtolower($class) . $extension ); }
    return new $class;
  }


  /**
  * Objekt registrieren
  * 
  * Der Funktion werden Name und Instanz eines Objekts übergeben.
  * Ein Objekt kann dadurch in der Session gespeichert und automatisch restauriert werden
  *
  * @param    string  $name Name der Instanzvariablen
  * @param    object  SOS_Class Objekt der Klasse SOS_Class
  * @param    string  $include_file Dateiname incl. Pfad, aus dem die Klassendefinition gelesen wird
  * @param    string  $auto_restore automatische Restaurierung des Objekts
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/08/13
  */   

  function register_object( $name, $object, $auto_restore=1, $include_file='' ) {

    $this->registered_objects[$name] = array( 'object' => $object, 'class' => get_class($object), 'auto_restore' => $auto_restore, 'include_file' => $include_file );

    return !$this->error();
  }


  /**
  * Objekt aus Registrierung entfernen
  * 
  * Der Funktion wird der Name eines Objekts übergeben, das aus dem Array registrierter Objekte entfernt wird.
  *
  * @param    string  $name Name der Instanzvariablen
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/08/13
  */   

  function release_object( $name ) {

    $this->registered_objects[$name] = null;

    return !$this->error();
  }


  /**
  * Objekt restaurieren
  * 
  * Der Funktion werden Name und Instanz eines Objekts übergeben.
  * Ein Objekt kann dadurch in der Session gespeichert und automatisch restauriert werden
  *
  * @param    string  $name Name der Instanzvariablen
  * @param    string  $include_file Dateiname incl. Pfad, aus dem die Klassendefinition gelesen wird
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/08/13
  */   

  function restore_object( $name, $registered_objects=null ) {

    if ( $registered_objects == null ) { $registered_objects = $this->registered_objects; }
    
    if ( !isset($registered_objects[$name]) ) { $this->set_error( 'Objekt ist nicht registriert: ' . $name ); return 0; }

    if ( !class_exists($registered_objects[$name]['class']) ) { 
      if ( $registered_objects[$name]['include_file'] ) {
        // include from registered path
        include_once( $registered_objects[$name]['include_file'] );
      } else {
        // include local class file from name
        include_once( $registered_objects[$name]['class'] . '.inc.php' );
      }
    }

    return $registered_objects[$name]['object'];
  }


  /**
  * Optionen parsieren
  * 
  * Der Methode werden eine Reihe von Optionen in der Form -db=oracle -user=sos -pass=sos übergeben.
  * Zurückgeliefert wird ein assoz. Array der Optionen (ohne -) und ihrer Werte
  *
  * @param    string  $option Optionen in der Form -option=wert
  * @param    string  $sep Separator für Optionen
  * @return   array   assoz. Array der Optionen in der Form $array[option] = wert
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */   
  
  function getopt( $option, $sep=' -' ) {

    $this->debug( 3, 'getopt: ' . $option );

    $option = ' ' . $option;
    $options = array();
    $option_objs = explode( $sep, $option );
    for($i=0; $i<count($option_objs); $i++) {
      $pos = strpos($option_objs[$i], '=');
      if ( $pos > 0 ) {
        $options[substr($option_objs[$i], 0, $pos)] = trim(substr($option_objs[$i], $pos+1));
        $this->debug( 6, 'options[' . substr($option_objs[$i], 0, $pos) . '] = ' . trim(substr($option_objs[$i], $pos+1)) );
      }
    }
    return $options;
  }


  /**
  * Option definieren
  * 
  * Optionen müssen von einer Klasse zunächst definiert werden, dabei wir ein Bereich der zulässigen Werte angegeben
  *
  * @param    string  $class Klasse der Option (Schreibweise bleibt unberücksichtigt)
  * @param    string  $name Name der Option (Schreibweise bleibt unberücksichtigt)
  * @param    string  value Wert der Option
  * @param    array   zugelassene Werte der Option
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/10
  */   
  
  function declare_option( $class, $name, $value, $range ) {
    
    $class = strtolower($class);
    $name  = strtolower($name);
    $this->options[$class][$name]['value'] = $value;
    $this->options[$class][$name]['range'] = $range;
  }


  /**
  * Option setzen
  * 
  * Eine Option kann im Rahmen ihres Wertebereichs verändert werden.
  *
  * @param    string  $class Klasse der Option (Schreibweise bleibt unberücksichtigt)
  * @param    string  $name Name der Option (Schreibweise bleibt unberücksichtigt)
  * @param    string  value Wert der Option
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/10
  */   
  
  function set_option( $class, $name, $value ) {
    
    $class = strtolower($class);
    $name  = strtolower($name);
    
    if ( isset($this->options[$class][$name]) ) {
      for($i=0; $i<count($this->options[$class][$name]['range']); $i++) {
        if ( $value == $this->options[$class][$name]['range'][$i] ) { $this->options[$class][$name]['value'] = $value; return true; }
      }
    }
    $this->set_error( 'Unzulässige Option [' . $class . '][' . $name. ']: ' . $this->options[$class][$name]['range'] );
    return false;
  }


  /**
  * Option zurückliefern
  * 
  * Optionen, die von einer Klasse bereitgestellt werden, werden zurückgeliefert.
  * Die Klasse implementiert die Option.
  *
  * @param    string  $class Klasse der Option (Schreibweise bleibt unberücksichtigt)
  * @param    string  $name Name der Option (Schreibweise bleibt unberücksichtigt)
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/10
  */   
  
  function get_option( $class, $name ) {

    $class = strtolower($class);
    $name  = strtolower($name);
    
    if ( isset($this->options[$class][$name]) ) {
      return $this->options[$class][$name]['value'];
    } else {
      return;
    }
  }


  /**
  * Fehlerzustand feststellen
  * 
  * @return   boolean Fehlerzustand
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */
  
  function error () {
    
    return $this->err;
  }
  

  /**
  * Fehlermeldung liefern
  * 
  * Liest einen Fehlerzustand und liefert einen Meldungsstring bestehend aus den Eigenschaften 
  * der Basisklasse SOS_Class mit Fehlercode, -meldung, exception.
  *
  * @param    string $file Name der Datei, in der der Fehler auftritt
  * @param    string $line Zeile in der Datei, in der der Fehler auftritt
  * @return   string Fehlermeldung oder False, falls kein Fehler
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */   
  
  function get_error ( $file='', $line='' ) {

    if ( $this->err ) { 
      if ( $file != '' ) { $this->err_file = $file; }
      if ( $line != '' ) { $this->err_line = $line; }

      $location = '';
      if ( $this->debug_level > 0 ) {
        if ( $this->err_file != '' ) { $location .= 'File: '  . $this->err_file; }
        if ( $this->err_line != '' ) { $location .= ' Line: ' . $this->err_line; }
        if ( $location != '' ) { $location = ' [' . $location . ']'; }
      }
      $msg = ( $this->err_code != '' ) ? '[' . $this->err_code . '] ' : '';
      return $msg . $this->err_msg . $location;
    } else {
      return null;
    }
  }


  /**
  * Fehlermeldung liefern
  * 
  * Liest einen Fehlerzustand und liefert einen Meldungsstring bestehend aus den Eigenschaften 
  * der Basisklasse SOS_Class mit Fehlercode, -meldung, exception.
  *
  * @return   string Fehlercode oder Null, falls kein Fehler
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */   
  
  function get_error_code () {

    if ( $this->err ) { return $this->err_code; }
    return null;
  }


  /**
  * Fehler auslösen
  * 
  * @param    string $msg Text der Fehlernachricht
  * @param    string  $file Datei, in der der Fehler auftritt, z.B. __FILE__
  * @param    string  $line Zeile, in der der Fehler auftritt, z.B. __LINE__
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function set_error ( $msg, $file='', $line='' ) {
  
    $this->err            = 1;
    if ( $msg != '' && $msg != null ) { $this->err_msg = $msg; }
    $this->err_code       = '';
    $this->err_exception  = '';
    
    if ( $file != '' ) { $this->err_file = $file; }
    if ( $line != '' ) { $this->err_line = $line; }
    
   #if ( $this->debug_level >= 9 ) { $this->backtrace = debug_backtrace(); }
    if ( $this->debug_level >= 9 && str_replace( '.','',phpversion() ) >= 430 ) { $this->backtrace = debug_backtrace(); }
  }
  
  
  /**
  * Fehler zurücksetzen
  * 
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function reset_error () {
  
    $this->err            = 0;
    $this->err_msg        = '';
    $this->err_code       = '';
    $this->err_exception  = '';
    $this->err_file       = '';
    $this->err_line       = '';
  }
  
  
  /**
  * Fehler auslösen
  * 
  * @param    string  $exception Name der Exception
  * @param    string  $code Fehler-Code
  * @param    string  $msg Text der Fehlernachricht
  * @param    string  $file Datei, in der der Fehler auftritt, z.B. __FILE__
  * @param    string  $line Zeile, in der der Fehler auftritt, z.B. __LINE__
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function set_exception ( $exception, $code='', $msg='', $file='', $line='' ) {
  
    $this->err            = 1;
    $this->err_exception  = $exception;
    $this->err_code       = $code;
    $this->err_msg        = $msg;
    
    if ( $file != '' ) { $this->err_file = $file; }
    if ( $line != '' ) { $this->err_line = $line; }
  }
  
  
  /**
  * Fehlertext lesen
  * 
  * @return   string Inhalt der Fehlernachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function get_err_msg () {
  
    return $this->err_msg;
  }
  
  
  /**
  * Fehler anzeigen
  * 
  * @param    string $msg Text der Fehlernachricht
  * @param    string $css_class CSS-Klasse zur Darstellung der Nachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function show_err ( $msg, $css_class='siteErr' ) {
  
    $this->show_error( $msg, $css_class );
  }
    

  /**
  * Fehler anzeigen
  * 
  * @param    string $msg Text der Fehlernachricht
  * @param    string $css_class CSS-Klasse zur Darstellung der Nachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function show_error ( $msg, $css_class='siteErr' ) {
  
    echo '<font class="' . $css_class . '">' . $msg . '</font><br>';
  }
    

  /**
  * Nachricht anzeigen
  * 
  * @param    string $msg Text der Nachricht
  * @param    string $class CSS-Klasse zur Darstellung der Nachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function show_msg ( $msg, $css_class='siteMsg' ) {

    echo '<font class="' . $css_class . '">' . $msg . '</font><br>';
  }
    

  /**
  * Backtrace anzeigen
  * 
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/06/14
  */

  function show_backtrace () {
  
    echo '<p><font class="siteDebug">Backtrace ' . get_class($this) . '</font></p>';
    for($i=0; $i<count($this->backtrace); $i++) {
      echo str_repeat('&nbsp;&nbsp;', $i+1) . 'file: ' . $this->backtrace[$i]['file'] . ', line: ' . $this->backtrace[$i]['line'] . '<br>';
      echo str_repeat('&nbsp;&nbsp;', $i+1) . 'function: ' . $this->backtrace[$i]['function'] . ', class: ' . $this->backtrace[$i]['class'] . '<br>';
      for($j=0; $j<count($this->backtrace[$i]['args']); $j++) {
        echo str_repeat('&nbsp;&nbsp;', $i+1) . 'argument ' . $j . ': ' . $this->backtrace[$i]['args'][$j] . '<br>';
      }
      echo '<br>';
    }
    
  }
    

  /**
  * Access Control List zum Schutz der Seite erzeugen
  * 
  * @param    string  $name Name der ACL
  * @param    array   $lock String oder Array der Rechte
  * @param    string  $identifier Name der Gruppe bzw. des Benutzers
  * @param    long    $scope Zugehörigkeit 0=Gruppe, 1=Benutzer
  * @param    long    $type Typ 0=einschließend, 1=ausschließend
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/04/21
  */   
  
  function acl ( $name=null, $lock=null, $identifier=null, $scope=null, $type=null ) {

    if ($name == null) { $name = get_class($this); }
    if ( !class_exists('SOS_ACL')  ) { include_once( 'acl/sos_acl.inc.php' ); }

    $this->acl = new SOS_ACL( $name, $lock, $identifier, $scope, $type );
  }


  /**
  * Dialog-Protokoll im Browser aktivieren
  * 
  * Das Protokoll wird am Bildschirm mit der CSS-Klasse siteDebug ausgegeben.
  * Parallel wird eine Dateiprotkoll mit log() geschrieben
  *
  * @param    long $level Protokollierungsstufe: 1-9
  * @param    string $msg Text der Fehlernachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function debug( $level, $msg ) {

    if ( $level <= $this->debug_level ) { 
      $this->show_msg( '[debug' . $level . '] [' . get_class($this) . '] ' . htmlentities($msg), 'siteDebug' );
    } 
    if ( $level <= $this->log_level ) {
      $this->log( $level, $msg );
    }
  }


  /**
  * Datei-Protokoll aktivieren
  * 
  * Das Protokoll wird in eine Datei geschrieben. 
  * Der Name der Protokolldatei wird über die Eigenschaft $log_file eingestellt.
  * Default ist die Datei 'sos.log' im Verzeichnis der Anwendung
  *
  * @param    long $level Protokollierungsstufe: 1-9
  * @param    string $msg Text der Fehlernachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function log( $level, $msg ) {
  
    if ( $level <= $this->log_level || $level <= $this->debug_level ) {
      $now = getdate();
      $timestamp = date("H:i:s");
      if ( !isset($GLOBALS['sos_log_handle']) ) {
        $GLOBALS['sos_log_handle'] = fopen( $this->log_file, $this->log_mode );
        if ( !$GLOBALS['sos_log_handle'] ) {  unset($GLOBALS['sos_log_handle']); return 0; }
        if ( fputs($GLOBALS['sos_log_handle'], $timestamp . "\t---------------------- " . date("l M d ") . $timestamp . date(" Y") . "\n") < 0 ) {  unset($GLOBALS['sos_log_handle']); return 0; }
      }
      $log_type = ($level) ? 'debug' . $level : 'info';
      fputs( $GLOBALS['sos_log_handle'], $timestamp . "\t[" . $log_type. "]\t[" . $_SERVER['REMOTE_ADDR'] . "]\t[" . $_SERVER['PHP_SELF'] . "]\t[" . get_class($this) . "]\t" . $msg . "\n" );
      fflush( $GLOBALS['sos_log_handle'] );
    }
  }


  /**
  * Fehler-Protokoll aktivieren
  * 
  * Das Protokoll wird in eine Datei geschrieben. 
  * Der Name der Protokolldatei wird über die Eigenschaft $log_file eingestellt.
  * Default ist die Datei 'sos.log' im Verzeichnis der Anwendung
  *
  * @param    long $level Protokollierungsstufe: 1-9
  * @param    string $msg Text der Fehlernachricht
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function log_error( $msg ) {

    $now = getdate();
    $timestamp = date("H:i:s");
    if ( !isset($GLOBALS['sos_log_handle']) ) {
      $GLOBALS['sos_log_handle'] = fopen( $this->log_file, $this->log_mode );
      if ( !$GLOBALS['sos_log_handle'] ) {  unset($GLOBALS['sos_log_handle']); return 0; }
      if ( fputs($GLOBALS['sos_log_handle'], $timestamp . "\t---------------------- " . date("l M d ") . $timestamp . date(" Y") . "\n") < 0 ) {  unset($GLOBALS['sos_log_handle']); return 0; }
    }
      
    fputs( $GLOBALS['sos_log_handle'], $timestamp . "\t[ERROR]\t[" . $_SERVER['REMOTE_ADDR'] . "]\t[" . $_SERVER['PHP_SELF'] . "]\t[" . get_class($this) . "]\t" . $msg . "\n" );
    fflush( $GLOBALS['sos_log_handle'] );
  }


  /**
  * No operation - Leerfunktion
  * 
  * @param    string $param beliebiger Parameter
  * @return   string eine Referenz auf denselben Parameter
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/09
  */

  function &nop( $param ) {
    
    return $param;
  }


  /**
  * script_exit - Script beenden
  * 
  * @param    string $code optionaler Fehlercode
  * @return   string eine Referenz auf denselben Parameter
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/05/04
  */

  function script_exit( $code=null ) {

    if ( $code === null ) {
      $code = ($this->error()) ? 1 : 0;
    } elseif ( $code === true ) {
      $code = 0;
    } elseif ( $code === false ) {
      $code = 1;
    } elseif ( !is_numeric($code) ) { 
      $code = 1;
    } 
    exit($code);
  }
  
  
  /**
  * Gibt Wert aus Übersetzungsarray zurück
  * Die Zeichenkette im 2.Parameter muss in der Form eines Querystring (bsp.: eins=Wert1&zwei=Wert2)
  * oder eines assoziatives Array sein (bsp.: array( 'eins' => Wert1, 'zwei' => Wert2 )),
  * dann werden die Platzhalter '$eins' und '$zwei' im Übersetzungsstring durch Wert1 und Wert2 ersetzt
  * 
  * @param    string $name Schlüssel des Arrays '$this->translations'
  * @param    string $substitutes Zeichenkette in Form eines Querystrings oder assoziatives Array
  * @return   string Wert aus Übersetzungsarray, wenn existiert, sonst $name
  * @access   public
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2003/10/14
  */
  
  function get_translation( $translation_key='', $substitutes='' ) {
    
    if( !isset( $this->translations[$translation_key] ) ) { return $translation_key; }
    if( !$substitutes ) { return $this->translations[$translation_key]; }
    if( is_array( $substitutes ) ) {
      extract( $substitutes );
    } else {
      parse_str( $substitutes );
    }
    return @eval( 'return "'.str_replace( '\n','".\'\n\'."',$this->translations[$translation_key]).'";' );
  }
  
  
  /**
  * Includiert Sprachdatei, wenn existent
  * 
  * @param    string $filename Dateiname ohne Pfad (=klassenname_lang.inc.php, wenn leer)
  * @param    string $lang_dir Pfad ohne Sprachunterverzeichnis (=this->lang_dir, wenn leer)
  * @param    string $language Sprache (=this->language, wenn '*')
  * @return   boolean true, wenn Sprachdatei existiert
  * @access   public
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2003/10/21
  */
  
  function get_lang_file( $filename='', $lang_dir='', $language='*' ) {
  
    if( !$filename )        { $filename  = strtolower( get_class( $this ) ).'_lang.inc.php'; }
    if( !$lang_dir )        { $lang_dir  = $this->lang_dir; }
    if( $language == '*' )  { $language  = $this->language; }
    if( $language )         { $language .= '/'; }
    $filename = $lang_dir.$language.$filename; 
    
    $ok = file_exists( $filename );
    clearstatcache();
    if( !$ok ) { $this->debug( 3, 'Die Sprachdatei '.$filename.' existiert nicht' ); return false; }
    include_once( $filename );
    
    return true;
  }
  
  
  /**
  * Abwärtskompatible Clone-Funktion, um in PHP5 und PHP4 Objekte kopieren zu können
  * 
  * @param    object $object 
  * @return   object Kopie des übergebenen Objekts
  * @access   public
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2005/07/19
  */
  
  function clone_object($object) {  
    
    if( version_compare(phpversion(),'5.0') < 0 ) {   
      return $object;  
    } else {
      return clone($object);  
    } 
  }
  
} // end of class SOS_Class

?>