<?php

if ( !class_exists('SOS_Class') )       { require('sos_class.inc.php'); }

/**
* SOS_History: Operationen einer SOS-Klasse in Session-Historie speichern
*
* Die Klasse fängt alle Operationen eines Objekts ab und speichert sie zur späteren Ausführung
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @author       Ghassan Beydoun <ghassan.beydoun@sos-berlin.com>
* @since        1.0-2003/02/04
*
* @access       public
* @package      CLASS
*/

class SOS_History extends SOS_Class {

  /** @access public */

  /** auto-quote: automatische Handhabung von quotes beim Lesen/Schreiben */
  var $auto_quote     = false;


  /** @access private */

  /** Verarbeitung: 0=verzögern, 1=ausführen */
  var $commit_mode    = 0;

  /**
  * Konstruktor
  * 
  * @access   public  
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/10/20
  */   

  function SOS_History() {
    
    if (!isset($_SESSION['sos_history']) )       { $_SESSION['sos_history']       = array(); }
    if (!isset($_SESSION['sos_history_index']) ) { $_SESSION['sos_history_index'] = 0; }

    /** Bei ausgeschalteten magic quotes quotieren die Connection-Methoden nicht automatisch */
    $this->auto_quote = ( get_magic_quotes_runtime() ) ? true : false;
  }  
    

  /**
  * Statement in Historie aufnehmen
  * 
  * @return   string   $class Klasse des statements
  * @return   string   $function Funktion des statements
  * @param    string   $statement statement zur verzögerten Ausführung
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/12/01
  */   
  
  function add( $class, $function, $statement=null ) {

    $this->debug( 3, 'add: class=' . $class . ' function=' . $function . ' statement=' . $statement );

    if (!isset($_SESSION['sos_history_index']) ) { $_SESSION['sos_history_index'] = 0; }
    $_SESSION['sos_history'][$_SESSION['sos_history_index']++] = array( 'class' => $class, 'function' => $function, 'statement' => $statement, 'disable' => 0 );

    return ($_SESSION['sos_history_index']-1);
  }


  /**
  * Statement in Historie aktivieren ( nach history_disable() )
  * 
  * @param    integer  $index ID des Historieneintrags
  * @return   integer  Nr. des Historieneintrags
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/12/01
  */   
  
  function enable( $index ) {

    if ( !isset($_SESSION['sos_history'][$index]) ) { $this->set_error( 'Historieneintrag nicht vorhanden: ' . $index, __FILE__, __LINE__ ); return 0; }

    $_SESSION['sos_history'][$index]['disable'] = 0;
    return $index;
  }


  /**
  * Statement in Historie desaktivieren
  * 
  * @param    integer  $index ID des Historieneintrags
  * @return   integer  Nr. des Historieneintrags
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/12/01
  */   
  
  function disable( $index ) {

    if ( !isset($_SESSION['sos_history'][$index]) ) { $this->set_error( 'Historieneintrag nicht vorhanden: ' . $index, __FILE__, __LINE__ ); return 0; }

    $_SESSION['sos_history'][$index]['disable'] = 1;
    return $index;
  }


  /**
  * Verzögerte Ausführung der statements in der Historie
  * 
  * @param    array    $objects Array der Objekte, deren Funktionen ausgeführt werden
  * @return   boolean  Fehlerzustand
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/02/05
  */   
  
  function commit( $objects ) {

    $this->commit_mode = 1;
    $classes = array();

    $this->debug( 3, 'commit: ' );
    
    if (!$objects) { $this->set_error( 'Keine Objekte zur Ausführung der Historie vorhanden', __FILE__, __LINE__ ); return 0; }

    for($i=0; $i<count($objects); $i++) {
      $classes[get_class($objects[$i])] = &$objects[$i];
    }

    for($i=0; $i<count($_SESSION['sos_history']); $i++) {
      if ( $_SESSION['sos_history'][$i]['disable'] ) { continue; }
      if ( isset($classes[$_SESSION['sos_history'][$i]['class']]) ) {
        if ( !$classes[$_SESSION['sos_history'][$i]['class']]->{$_SESSION['sos_history'][$i]['function']}( $_SESSION['sos_history'][$i]['statement'] ) ) {
          $this->set_error( $classes[$_SESSION['sos_history'][$i]['class']]->get_error(), __FILE__, __LINE__ );
          break;
        }
      }
    }

    $this->commit_mode = 0;
    return !$this->error();
  }

} // end of class SOS_History

?>