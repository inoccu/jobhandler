<?php

if ( !class_exists('SOS_Class') )       					{ require('class/sos_class.inc.php'); }

/**
* Remote-Kommando für Job Scheduler
*
* Erzeugen eines Kommandos an den Scheduler.
* Die Kommandos sind in scheduler.xsd beschrieben.
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/10/26
* @since        1.1-2003/09/30
*
* @access       public
* @package      SCHEDULER
*/

class SOS_Scheduler_Command extends SOS_Class {

  /** @access public */

  /** Rechner, auf dem der Scheduler läuft */
  var $host                 = 'localhost';

  /** Port, auf dem der Scheduler läuft */
  var $port                 = 4444;

  /**  Timeout für Socket-Verbindungen */
  var $timeout              = 5;

  /** Aufbereitete Fehlernachricht */
  var $errmsg               = '';
  
  /** Verzeichnis der Sparch-Dateien */
  var $lang_dir             = 'languages/scheduler/';
  
  /** Sprachsteuerung: Name der Sprachdatei */
  var $lang_file_name       = 'sos_scheduler_lang.inc.php';
  
  
  /** @access private */

  /** Connection-Handle */
  var $sh                   = 0;

  /** Antwort des Schedulers im XML-Format */
  var $answer               = '';

  /** Status-Antwort des Schedulers verfügbar */
  var $state                = 0;

  /** Connection Fehler-Nr. */
  var $errno                = 0;

  /** Connection Fehler-Text */
  var $errstr               = '';
  
                                   

  /**
  * Konstruktor
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function SOS_Scheduler_Command($host=null, $port=null, $timeout=null) {
    
    if ($host != null)    { $this->host     = $host; }
    if ($port != null)    { $this->port     = $port; }
    if ($timeout != null) { $this->timeout  = $timeout; }

    $this->translations_init();
    $this->init();
  }
  
  
  /**
  * Deutsche Vorbesetzung des Mehrsprachigkeitsarrays 'translations'
  *
  * @access   private
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2004/11/04
  */
  
  function translations_init($include_lang_file = true) {
    
    $this->translations['invalid_port']                       = 'Nicht numerische Angabe des Ports: $port';
    $this->translations['connection_failed']                  = 'Fehler beim Verbindungsaufbau zum Scheduler auf Host $host, Port $port. Ist der Dienst gestartet? [$errno]: $errstr';
  
    if ( defined('SOS_LANG')) { $this->language = SOS_LANG; } 
    if( $include_lang_file )  { $this->get_lang_file( $this->lang_file_name ); } 
  }
    
    
  /**
  * Initialisierung
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function init() {

  }


  /**
  * Verbindung zum Scheduler aufbauen
  *
  * Als Protokoll kann TCP oder UDP eingesetzt werden. Bei UDP erfolgt keine Quittierung der Kommunikation.
  *
  * @param    string   $sp_host Host-Name des Schedulers, mit der Syntax udp:host wird UDP anstelle von TCP als Protokoll verwendet
  * @param    integer  $sp_port Port-Nr. des Schedulers
  * @param    integer  $sp_timeout max. Anzahl Sekunden für Verbindungsaufbau
  * @return   boolean  Fehlerzustand
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function connect( $host=null, $port=null, $timeout=null ) {

    if ($host != null)    { $this->host     = $host; }
    if ($port != null)    { $this->port     = $port; }
    if ($timeout != null) { $this->timeout  = $timeout; }

    $this->port = intval($this->port);
    if (!is_integer($this->port) || $this->port == 0 ) {
      $this->set_error( $this->get_translation( 'invalid_port', 'port='.$this->port ) );
      return 0;
    }

    $this->sh = @fsockopen( $this->host, $this->port, $this->errno, $this->errstr, $this->timeout );
    if ( !$this->sh ) {
      $this->set_error ( $this->get_translation( 'connection_failed', 'host='.$this->host.'&port='.$this->port.'&errno='.$this->errno.'&errstr='.$this->errstr ) );
      return 0;
    } else {
      return 1;
    }
  }


  /**
  * Verbindung zum Scheduler abbauen
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function disconnect() {

    fclose( $this->sh );
  }


  /**
  * Scheduler-Antwort via TCP lesen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer() {
    $this->answer = ''; $s = '';
    while ( !ereg("</spooler>", $s) && !ereg("<ok[/]?>", $s) ) {
      $s = fgets($this->sh, 1000);
      if (strlen($s) == 0) { break; }
      $this->answer .= $s;
      $s = substr($this->answer, strlen($this->answer)-20);

      if (substr($this->answer, -1) == chr(0)) {
        $this->answer = substr($this->answer, 0, -1);
        break;
      }
    }
    $this->answer = trim($this->answer);

    return $this->answer;
  }


  /**
  * Fehlermeldung aus Scheduler-Antwort via TCP lesen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2006/10/16
  */

  function get_answer_error() {
    /*
    if (version_compare(PHP_VERSION,'5','>=') && !class_exists('php4DOMDocument')) { include('util/domxml-php4-to-php5.php'); }
    
    $dom   = domxml_open_mem($this->answer); 
    $xpath = &$dom->xpath_new_context();

    $result_code = xpath_eval_expression($xpath, '//ERROR/@code');
    $result_text = xpath_eval_expression($xpath, '//ERROR/@text');

    if (!isset($result_code->nodeset[0]) && !isset($result_text->nodeset[0])) return;
    
   #$this->set_error( (isset($result_code->nodeset[0]) ? $result_code->nodeset[0]->value . ': ' : '') . (isset($result_text->nodeset[0]) ? $result_text->nodeset[0]->value : '') );  
    $this->set_error( (isset($result_text->nodeset[0]) ? $this->to_html($result_text->nodeset[0]->value) : '') );  
    return $this->get_error();
    */
    if( preg_match( '/<ERROR(?:[\s]+[^=]+=[\s]*"[^"]*")*[\s]+text[\s]*=[\s]*"([^"]*)"(?:[\s]+[^=]+=[\s]*"[^"]*")*[\/]?>/i', $this->answer, $matches ) ) {
      $this->set_error( $this->to_html($matches[1]),__FILE__,__LINE__ );  
    } else {
  #    $this->reset_error();
    }
    return $this->get_error();
  }


  /**
  * Scheduler-Kommando absenden
  *
  * Bei Verwendung von UDP anstelle von TCP wird keine Scheduler-Antwort abgeholt
  *
  * @param    string $cmd Kommando wie beschrieben in scheduler.dtd
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function command( $cmd ) {

    if ( substr($cmd, 0, 5) != '<?xml' ) {
      $cmd = '<?xml version="1.0" encoding="iso-8859-1"?>' . $cmd;
    }

     fputs( $this->sh, $cmd );
    if ( strpos(strtolower($this->host), 'udp://') === false) { $this->get_answer(); }
 
    return 1;
  }


  /**
  * Konvertieren von 2-Byte Zeichensatz (UTF) in HTML
  *
  * @param    string   $htmlstr Zeichenfolge in UTF
  * @return   string   Zeichenfolge für HTML
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_html( $htmlstr ) {
  
    if ( $htmlstr != '' ) {
      return utf8_decode($htmlstr);     
    }
  }
  

} // end of class SOS_Scheduler_Command

?>