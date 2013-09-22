<?php

/**
* SOS_Globals: Basisklasse der SOS
*
* @copyright   SOS GmbH
* @author      Oliver Haufe <oliver.haufe@sos-berlin.com>
* @since       1.0-2005/10/21
* @access      public
* @package     CLASS
*/

class SOS_Globals {
  
  var $var_register  = array();
  var $error_msg     = '';
  var $error_codes   = array( 'is_not_registered',
                              'unknown_data_type',
                              'is_not_string',
                              'is_not_number',
                              'data_too_long',
                              'data_must_be_given',
                              'invalid_date_format',
                              'invalid_datetime_format',
                              'invalid_number_format', 
                            );
  
  /**
  * Konstruktor
  * 
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */   
  
  function SOS_Globals() {}  
    

  /**
  * Wert aus Request/Post/Get/Cookie-Array liefern
  * 
  * Der Funktion wird der Schl�ssel des Request/Post/Get/Cookie-Arrays und optional ein Default-R�ckgabewert �bergeben.
  * Als dritter Parameter kann mittels (p|g|c) bestimmt werden, ob nur in Post-, Get- oder Cookie-Variablen gesucht wird.
  * Wert aus Request/Post/Get/Cookie-Array wird zur�ckgegeben.
  * Wenn Schl�ssel im Request/Post/Get/Cookie-Array nicht existiert, wird NULL bzw. gesetzer Default zur�ckgegeben.
  *
  * @param    string  $param_name Schl�ssel des Request/Post/Get/Cookie-Arrays
  * @param    string  $default_value Default-R�ckgabewert, wenn Schl�ssel nicht existiert
  * @param    string  $resource Schr�nkt Suche in Posts,Gets bzw. Cookies ein. M�gliche Werte sind 'p','g' und 'c'
  * @return   mixed   Wert aus Request/Post/Get/Cookie-Array
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */   

  function get_value( $param_name, $default_value=null, $resource='' ) {

    switch( strtolower($resource) ) {
      case 'p' : return $this->get_post_value  ( $param_name, $default_value );
      case 'g' : return $this->get_get_value   ( $param_name, $default_value );
      case 'c' : return $this->get_cookie_value( $param_name, $default_value );
      default  : return ( isset( $_REQUEST[$param_name] ) ) ? $_REQUEST[$param_name] : $default_value;
    }
  }
  
  
  /**
  * Wert aus Post-Array liefern
  * 
  * Der Funktion wird der Schl�ssel des Post-Arrays und optional ein Default-R�ckgabewert �bergeben.
  * Wert aus Post-Array wird zur�ckgegeben.
  * Wenn Schl�ssel im Post-Array nicht existiert, wird NULL bzw. gesetzer Default zur�ckgegeben.
  *
  * @param    string  $param_name Schl�ssel des Post-Arrays
  * @param    string  $default_value Default-R�ckgabewert, wenn Schl�ssel nicht existiert
  * @return   mixed   Wert aus Post-Array
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */
  
  function get_post_value( $param_name, $default_value=null ) {
    
    return ( isset( $_POST[$param_name] ) ) ? $_POST[$param_name] : $default_value;
  }
  
  
  /**
  * Wert aus Get-Array liefern
  * 
  * Der Funktion wird der Schl�ssel des Get-Arrays und optional ein Default-R�ckgabewert �bergeben.
  * Wert aus Get-Array wird zur�ckgegeben.
  * Wenn Schl�ssel im Get-Array nicht existiert, wird NULL bzw. gesetzer Default zur�ckgegeben.
  *
  * @param    string  $param_name Schl�ssel des Get-Arrays
  * @param    string  $default_value Default-R�ckgabewert, wenn Schl�ssel nicht existiert
  * @return   mixed   Wert aus Get-Array
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */
  
  function get_get_value( $param_name, $default_value=null ) {
    
    return ( isset( $_GET[$param_name] ) ) ? $_GET[$param_name] : $default_value;
  }
  
  
  /**
  * Wert aus Cookie-Array liefern
  * 
  * Der Funktion wird der Schl�ssel des Cookie-Arrays und optional ein Default-R�ckgabewert �bergeben.
  * Wert aus Cookie-Array wird zur�ckgegeben.
  * Wenn Schl�ssel im Cookie-Array nicht existiert, wird NULL bzw. gesetzer Default zur�ckgegeben.
  *
  * @param    string  $param_name Schl�ssel des Cookie-Arrays
  * @param    string  $default_value Default-R�ckgabewert, wenn Schl�ssel nicht existiert
  * @return   mixed   Wert aus Cookie-Array
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */
  
  function get_cookie_value( $param_name, $default_value=null ) {
    
    return ( isset( $_COOKIE[$param_name] ) ) ? $_COOKIE[$param_name] : $default_value;
  }
  
  
  /**
  * F�llt Globals-Array mit Wert aus Request/Post/Get/Cookie-Array
  * 
  * Der Funktion wird ein Variablenname und korrespondierender Schl�ssel des Request/Post/Get/Cookie-Arrays �bergeben.
  * Als dritter Parameter kann mittels (p|g|c) bestimmt werden, ob nur in Post-, Get- oder Cookie-Variablen gesucht wird.
  *
  * @param    string  $var_name Eigener Variablenname
  * @param    string  $param_name Schl�ssel des Request/Post/Get/Cookie-Arrays
  * @param    string  $resource Schr�nkt Funktion auf Posts, Gets bzw. Cookies ein. M�gliche Werte sind 'p','g' und 'c'
  * @access   public  
  * @author   Oliver Haufe <oliver.haufe@sos-berlin.com>
  * @version  1.0-2005/10/21
  */   

  function set_value( $var_name, $param_name, $resource='' ) {
 
    switch($resource) {
      case 'p': if( isset( $_POST[$param_name]    ) ) { $GLOBALS[$var_name] = $_POST[$param_name];    } else { unset($GLOBALS[$var_name]); }
                break;
      case 'g': if( isset( $_GET[$param_name]     ) ) { $GLOBALS[$var_name] = $_GET[$param_name];     } else { unset($GLOBALS[$var_name]); }
                break;
      case 'c': if( isset( $_COOKIE[$param_name]  ) ) { $GLOBALS[$var_name] = $_COOKIE[$param_name];  } else { unset($GLOBALS[$var_name]); }
                break;
      default : if( isset( $_REQUEST[$param_name] ) ) { $GLOBALS[$var_name] = $_REQUEST[$param_name]; } else { unset($GLOBALS[$var_name]); }
                break;          
    }
  }
  
  
  /** 
  * Der Funktion wird ein Variablenname und eine Datendefinition �bergeben.
  * Die Datendefinition ist ein '|' getrennter String mit folgenden Werten
  * [Datentyp(string|number|date|boolean)]|[Feldl�nge]|[Mussfeld(M oder leer)]|[genauerer Datentyp]|[Wertebereich]
  * Werte des genauerer Datentyps sind abh�ngig von Datentyp
  * Bei number Anzahl der Nachkommastellen (wenn leer, dann Integer ohne Tausendertrenner), 
  * sowie Dezimal- und Tausendertrenner angegeben werden (Bsp. f�r deutsches W�hrungsformat = 2,.)
  * Bei date kann das Datumsformat angegeben werden, wenn leer dann ISO (yyyy-mm-dd HH:MM:SS)
  * Der Wertebereich wird als regul�rer Ausdruck ausgewertet
  */
  
  function register( $var_name, $data_definition='string' ) {
    
    $this->var_register[$var_name] = $data_definition;
  }
  
  
  function is_registered( $var_name ) {
    
    return ( isset($this->var_register[$var_name]) );
  }
  
  
  function is_valid( $var_name, $var_value, $data_definition='' ) {
    
    if( $data_definition ) { $this->register( $var_name, $data_definition ); }
    if( !$this->is_registered( $var_name ) ) { $this->error_msg = $this->error_codes[0]; return false; }
    
    $data_definitions    = explode( '|', $this->var_register[$var_name] );
    $data_type           = ( isset($data_definitions[0]) ) ? $data_definitions[0] : '';
    $data_length         = ( isset($data_definitions[1]) ) ? $data_definitions[1] : '';
    $data_duty           = ( isset($data_definitions[2]) ) ? $data_definitions[2] : '';
    $data_type_precision = ( isset($data_definitions[3]) ) ? $data_definitions[3] : '';
    $data_range          = ( isset($data_definitions[4]) ) ? $data_definitions[4] : '';
    for( $i = 5; $i < count($data_definitions); $i++ ) { $data_range .= $data_definitions[$i]; }
    
    
    switch( $data_type ) {
      case 'string'  : //Pr�fung, ob string, nach L�nge, ob Mussfeld
                       //je nach data_type_precision weitere Pr�fung oder Konvertierungen
                       
                       
                       break;
      case 'number'  : break; //Pr�fung, ob number, nach L�nge(ohne Tausendertrenner), ob Mussfeld
                              //je nach data_type_precision weitere Pr�fung oder Konvertierungen
      case 'date'    : break; //Pr�fung, ob date(time), ob Mussfeld, Konvertierung nach ISO
      case 'boolean' : break; //entf�llt wom�glich
      default        : $this->error_msg = $this->error_codes[1]; return false;      
    }
    
    $this->error_msg = '';
    return true;
  }
    
    



  
  
} // end of class SOS_Globals

?>