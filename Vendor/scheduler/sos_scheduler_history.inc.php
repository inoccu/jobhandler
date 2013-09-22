<?php

if ( !class_exists('SOS_Scheduler') )                { require( 'sos_scheduler.inc.php'); }

/**
* Job-Historie des Job Schedulers
*
* Anzeige von Job-Läufen und Recherchefunktionen
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/10/26
* @since        1.1-2003/04/13
* @since        1.2-2003/09/30
*
* @access       public
* @package      SCHEDULER
*/

class SOS_Scheduler_History extends SOS_Scheduler {

  /** @access public */
  
  /** Objekt für Datenbankverbindung der Klasse SOS_Connection */
  var $db;

  /** Klasse für Datenbankverbindung (SOS_Connection) */
  var $connection_class       = 'sos_odbc_connection';
  
  /** Verbindungszeichenfolge -db= -user= -password= */
  var $connection_auth        = '';

  /** Tabelle der Job-Historie */
  var $table_history          = 'SCHEDULER_HISTORY';

  /** Tabelle der Zähler */
  var $table_variables        = 'SCHEDULER_VARIABLES';

  /** Tabelle der Aufträge */
  var $table_orders           = 'SCHEDULER_ORDERS';

  /** Tabelle der Auftragshistorie */
  var $table_order_history    = 'SCHEDULER_ORDER_HISTORY';

  /** Tabelle der eingereihten Jobs */
  var $table_tasks            = 'SCHEDULER_TASKS';


  /** Datum ab dem in Abfragen gesucht wird */
  var $date_from              = '';

  /** Datum bis zu dem in Abfragen gesucht wird */
  var $date_to                = '';

  /** Job, nach dem gesucht wird */
  var $job                    = '';

  /** Job-ID, nach der gesucht wird */
  var $job_id                 = '';

  /** Job-Ergebnis, nach dem gesucht wird */
  var $job_error              = -1;

  /** Fehlertext des Jobs, nach dem gesucht wird */
  var $job_error_text         = '';

  /** Script mit Bestätigungsabfrage bei Job-Aktionen */
  var $joblog_javascript      = 'onClick="return confirm(\'Sie entfernen diesen Eintrag aus der Job-Historie. \nSind Sie mit der Aktion einverstanden?\');"';


  /** Max. Anzahl Sätze im Result Set */
  var $result_limit           = 200;

  /** Array mit Werten des Result Sets */
  var $results                = array();

  /** Index auf Datensatz aus Result Set */
  var $result_index           = 0;

  /** Überschriftentitel der Felder des Result Sets */
  var $result_titles          = array( 'Scheduler',
                                       'Job',
                                       'Start',
                                       'Ende',
                                       'Schritte',
                                       'Fehler' );

  /** Array der Feld-Namen des Result Sets */
  var $result_fields          = array( 'id',
                                       'spooler_id',
                                       'job_name',
                                       'start_time', 
                                       'end_time',
                                       'steps',
                                       'error',
                                       'error_code',
                                       'error_text',
                                       'parameters' );       


  /** @access private */

  /** Flag für geöffnete Datenbank */
  var $db_opened              = 0;

  /** Recherche in der Historiendatenbank zulassen */
  var $enable_history         = 1;

  /** Entfernen von Einträgen der Historiendatenbank zulassen */
  var $enable_history_delete  = 1;
  
  

  /**
  * Konstruktor
  *
  * @param    string $connection_auth Verbindungszeichenfolge für Datenbank
  * @param    string $connection_class Connection-Klasse für Datenbank
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function SOS_Scheduler_History( $connection_auth=null, $connection_class=null ) {

    if (!defined('SOS_LANG')) { define('SOS_LANG', 'de'); }    
    
    if ( $connection_auth != null )     { $this->connection_auth  = $connection_auth; }
    if ( $connection_class != null )    { $this->connection_class = $connection_class; }

    $this->translations_init();
    $ok = $this->init();
    if ($ok) { $ok = $this->history_init( $this->connection_auth, $this->connection_class ); }
    return $ok;
  }
  
  
  /**
  * Destruktor
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function destruct() {

    return 1;    
  }
  
  
  /**
  * Deutsche Vorbesetzung des Mehrsprachigkeitsarrays 'translations'
  *
  * @access   private
  * @author   Oliver Haufe <oh@sos-berlin.com>
  * @version  1.0-2004/11/04
  */
  
  function translations_init($include_lang_file = true) {
    
    parent::translations_init( false );
    $this->translations['no_hit']                             = 'Keine Treffer für diese Kriterien gefunden';
    $this->translations['sizeof']                             = 'Anzahl';
    $this->translations['nr']                                 = 'Nr';
    $this->translations['service_without_history1']           = 'Der Dienst arbeitet zur Zeit ohne Historiendatenbank.';
    $this->translations['service_without_history2']           = 'Diese Datenbank wird mit dem Eintrag "db" in der Sektion "[spooler]" der Konfigurationsdatei festgelegt.';
    $this->translations['service_without_history3']           = 'Fehler beim Zugriff auf die Historiendatenbank stehen im Protokoll: $protocol';
    $this->translations['select_all']                         = '(alle)';
    $this->translations['select_scheduler']                   = '(Scheduler)';
    $this->translations['from']                               = 'ab';
    $this->translations['to']                                 = 'bis';
    $this->translations['calendar']                           = 'Kalender zur Datumsauswahl'; 
    $this->translations['coverage']                           = 'Umfang';
    $this->translations['error_text']                         = 'Fehlertext';    
    $this->translations['joblog_javascript']                  = 'Sie entfernen diesen Eintrag aus der Job-Historie. \nSind Sie mit der Aktion einverstanden?';    
    if( defined('SOS_LANG') ) { $this->language = SOS_LANG; } 
    if( $include_lang_file )  { $this->get_lang_file( $this->lang_file_name ); }  
  }


  /**
  * Initialisierung
  *
  * @param    string $connection_auth Verbindungszeichenfolge für Datenbank
  * @param    string $connection_class Connection-Klasse für Datenbank
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function history_init( $connection_auth=null, $connection_class=null ) {

    if ( $connection_auth != null )     { $this->connection_auth  = $connection_auth; }
    if ( $connection_class != null )    { $this->connection_class = $connection_class; }

    $this->debug(3, 'history_init: connection_auth=' . $this->connection_auth . ' connection_class=' . $this->connection_class);
    $this->joblog_javascript                            = 'onClick="return confirm(\''.$this->get_translation('joblog_javascript').'\');"';
    
    if ( isset($_REQUEST['scheduler_job']) )             { $this->job            = trim($_REQUEST['scheduler_job']); }
    if ( isset($_REQUEST['scheduler_job_id']) )          { $this->job_id         = trim($_REQUEST['scheduler_job_id']); }
    if ( isset($_REQUEST['scheduler_job_error']) )       { $this->job_error      = trim($_REQUEST['scheduler_job_error']); }
    if ( isset($_REQUEST['scheduler_job_error_text']) )  { $this->job_error_text = trim($_REQUEST['scheduler_job_error_text']); }
    if ( isset($_REQUEST['scheduler_date_from']) )       { $this->date_from      = trim($_REQUEST['scheduler_date_from']); }
    if ( isset($_REQUEST['scheduler_date_to']) )         { $this->date_to        = trim($_REQUEST['scheduler_date_to']); }
    return 1;
  }


  /**
  * Datenbank öffnen
  *
  * Wird separat das Objekt $db instantiiert, dann erfolgt dort das connect() zur Datenbank
  *
  * @param    string $connection_auth Verbindungszeichenfolge für Datenbank
  * @param    string $connection_class Connection-Klasse für Datenbank
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/08/02
  */

  function open_db( $connection_auth=null, $connection_class=null ) {

    if ( defined('APP_CONNECTION_AUTH') )   { $this->connection_auth  = APP_CONNECTION_AUTH; }
    if ( defined('APP_CONNECTION_CLASS') )  { $this->connection_class = APP_CONNECTION_CLASS; }

    if ( $connection_auth != null )     { $this->connection_auth  = $connection_auth; }
    if ( $connection_class != null )    { $this->connection_class = $connection_class; }
    $this->debug(3, 'open_db: connection_auth=' . $this->connection_auth . ' connection_class=' . $this->connection_class);

    if ( $this->db_opened )     { return 1; }
    if ( $this->connection_auth == '' ) {
      preg_match("/ +jdbc:([a-zA-Z\_\-]+):/", $this->scheduler_attributes['db'], $matches);
      if ( $matches ) {
         switch (strtolower($matches[1])) {
          case 'mysql'  : $this->connection_class = 'sos_mysql_connection';
                          // Beispiel: jdbc -id=scheduler -class=com.mysql.jdbc.Driver jdbc:mysql://wilma/factory?user=factory&password=factory
                          preg_match("/mysql:\/\/(.*)\/(.*)\?(user=.*)&(password=.*)/", $this->scheduler_attributes['db'], $matches);
                          if ( count($matches) > 4 ) { $this->connection_auth = '-db=' . $matches[2] . ' -' . $matches[3] . ' -' . $matches[4] . ' -host=' . $matches[1]; }
                          break;
          case 'oracle' : $this->connection_class = 'sos_oracle_connection';
                          // Beispiel: jdbc -id=scheduler -class=oracle.jdbc.driver.OracleDriver jdbc:oracle:thin:@sag.sos:1521:sag -user=sos -password=sos
                          preg_match("/ +jdbc:oracle:.*:\@.*:.*:(.*) +(-user=.*) +(-password=.*)/", $this->scheduler_attributes['db'], $matches);
                          if ( count($matches) > 3 ) { $this->connection_auth = '-db=' . $matches[1] . ' ' . $matches[2] . ' ' . $matches[3]; }
                          break;
        }
      }
    }
    if ( $this->connection_auth == '' ) { $this->connection_auth = $this->scheduler_attributes['db']; }

    if ( !isset($this->db) || !is_object($this->db) ) { 
      $this->db = $this->create_object( $this->connection_class, 'connection/' );
      $this->debug(6, 'new db object: connection_auth=' . $this->connection_auth );
      if ( !$this->db->connect( $this->connection_auth ) ) {
        $this->set_error( $this->db->get_error( __FILE__, __LINE__ ) );
        return 0;
      } else { 
        $this->db_opened = 1;
      }
    }

    return !$this->db->error();
  }


  /**
  * Abfrage ausführen
  *
  * @param    string $job Name eines Jobs für Abfrage
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function process_query( $job='' ) {

    $this->debug(3, 'process_query: job=' . $job);
    if ( !$this->open_db() ) { return 0; }
    
    $ok_group = isset($_REQUEST['range']);
    if ( $ok_group ) { $ok_group = ( $_REQUEST['range'] == 'group' ); }

    if ( $ok_group ) {       
      $sql_stmt  = ' select "SPOOLER_ID", "JOB_NAME", "CAUSE", count(*) as "SUM_STARTS", sum(("END_TIME"-"START_TIME")*100000) as "SUM_ELAPSED", avg(("END_TIME"-"START_TIME")*100000) as "AVG_ELAPSED", sum("STEPS") as "SUM_STEPS", avg("STEPS") as "AVG_STEPS", sum(abs("ERROR")) as "SUM_ERROR"';
    } else {
      $sql_stmt  = ' select "ID","SPOOLER_ID","JOB_NAME","START_TIME","END_TIME","CAUSE","STEPS","ERROR","ERROR_CODE","ERROR_TEXT"';
    }
    
    $sql_stmt .= ' from ' . $this->table_history;
    $sql_and   = ' where ';
    
    if ( $this->scheduler_attributes['id'] != '' ) { $sql_stmt .= $sql_and . ' "SPOOLER_ID" IN (\'' . $this->scheduler_attributes['id'] . "','-') "; $sql_and = ' and '; }

    $select_job = ( $job != '' ) ? $job : $this->job;
    $select_job = ( $select_job == '(Scheduler)' ) ? '(Spooler)' : $this->job;
    if ( $select_job != '' ) { $sql_stmt .= $sql_and . ' "JOB_NAME" = \'' . $select_job . '\''; $sql_and = ' and ';}

    if ( $this->date_from != '' ) { $sql_stmt .= $sql_and. ' "START_TIME" >= %timestamp(\'' . $this->date_from . '\')'; $sql_and = ' and '; }
    if ( $this->date_to   != '' ) { 
      if ( strlen($this->date_to) < 12 ) { $this->date_to .= ' 23:59:59'; }
      $sql_stmt .= $sql_and . ' "START_TIME" <= %timestamp(\'' . $this->date_to . '\')'; $sql_and = ' and '; 
    }

    if ( $this->job_id != '' )   { $sql_stmt .= $sql_and . ' "ID" = ' . $this->job_id; $sql_and = ' and '; }
    if ( $this->job_error == 0 ) { $sql_stmt .= $sql_and . ' "ERROR" = 0'; $sql_and = ' and '; }
    if ( $this->job_error == 1 ) { $sql_stmt .= $sql_and . ' "ERROR" = 1'; $sql_and = ' and '; }
    if ( $this->job_error_text != '' ) { $sql_stmt .= $sql_and . ' %lcase("ERROR_TEXT") like \'%' . strtolower($this->job_error_text) . '%\''; $sql_and = ' and '; }

    if ( $ok_group ) {
      $sql_stmt .= ' group by "SPOOLER_ID","JOB_NAME","CAUSE" order by "SPOOLER_ID","JOB_NAME"';
    } else {
      $sql_stmt .= ' order by "ID" desc';
    }

#   if ( !$ok_group && $sql_and == ' where ' ) { 
#     $this->show_error( 'Bitte mindestens ein Recherchekriterium angeben oder die gruppierte Recherchefunktion verwenden.' );
#     return 0; 
#   }

    $this->results = $this->db->get_array( $sql_stmt );
    return ( !$this->db->error() );
  }


  /**
  * Abfrageergebnis anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_result() {

    $this->show_html('<table width="95%" border="0">');
    $this->show_html('  <tr>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('    <td class="' . $this->style_td_background . '">');
    $this->show_html('      <table border="0" cellPadding="5" cellSpacing="1" width="100%">');
    
    $ok = $this->show_result_header();

    for( $i=0; $i<count($this->results); $i++) {
      $ok = $this->show_result_entry( $i );
      if (!$ok) { break; }
    }
    
    $this->show_html('      </table>');
    $this->show_html('    </td>');
    $this->show_html('  </tr>');
    $this->show_html('</table>');  

    if ( $i==0 ) { $this->show_html('<table><tr><td width="20">&nbsp;</td><td>'); $this->show_error( $this->get_translation('no_hit') ); $this->show_html('</td></tr></table>'); }
    $this->show_html('<p>');
    
    return $ok;
  }


  /**
  * Überschriftenzeile für Abfrageergebnis
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function show_result_header() {

    $ok_db = ( $this->scheduler_attributes['db'] != '' );
    $ok_group = isset($_REQUEST['range']);
    if ( $ok_group ) { $ok_group = ( $_REQUEST['range'] == 'group' ); }

    $this->show_html( '<tr>' );
    $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('job').'&nbsp;</th>' );
    if ( $ok_group ) {
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('sizeof').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('duration').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">...&nbsp;&oslash;&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('steps').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">...&nbsp;&oslash;&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('error').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">Startart&nbsp;</th>' );
    } else {
      if ( $ok_db ) { $this->show_html( ' <th class="' . $this->style_th . '">&nbsp;&nbsp;&nbsp;&nbsp;'.$this->get_translation('protocol').'&nbsp;&nbsp;&nbsp;&nbsp;</th>' ); }
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('start').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('end').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('duration').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('steps').'&nbsp;</th>' );
      $this->show_html( ' <th class="' . $this->style_th . '">'.$this->get_translation('type_of_start').'&nbsp;</th>' );
    }
    $this->show_html( '</tr>' );

    return true;  
  }


  /**
  * Zeile eines Abfrageergebnisses darstellen
  *
  * @param    integer $index Nr. des Satzes im Result Set
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_result_entry( $index ) {

    $ok = false;
    $ok_db = ( $this->scheduler_attributes['db'] != '' );
    $ok_group = isset($_REQUEST['range']);
    if ( $ok_group ) { $ok_group = ( $_REQUEST['range'] == 'group' ); }

    $this->show_html('<tr>');

    if ( $ok_group ) {
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">' . $this->results[$index]['job_name'] . '&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->results[$index]['sum_starts'] . '&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->to_decimal($this->results[$index]['sum_elapsed']) . 's&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->to_decimal($this->results[$index]['avg_elapsed']) . 's&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->results[$index]['sum_steps'] . '&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->to_decimal($this->results[$index]['avg_steps']) . '&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->results[$index]['sum_error'] . '&nbsp;</td>' );
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">' . $this->get_task_cause($this->results[$index]['cause']) . '&nbsp;</td>' );
    } else {
      $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
      $item = ( $this->item != null && $this->item != 0 ) ? $this->item : '';
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">');
      if ($this->results[$index]['job_name'] != '(Spooler)') {
        $this->show_html( $this->print_jobaction( 'job_history', '', $this->results[$index]['job_name'], '', '' . $item, '', '', '&item=' . $this->results[$index]['id'] . '&task=' . $this->results[$index]['job_name'] ) );
      }
      $this->show_html($this->results[$index]['job_name'] . '&nbsp;</td>' );

      if ( $ok_db && $this->results[$index]['job_name'] != '(Spooler)') {
        $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
        $this->show_html( '  <td valign="bottom" align="left"  class="' . $this->style_td_action . '">' . $this->print_jobaction( 'job_history_log', '', $this->results[$index]['job_name'], '', '#history' . $this->item, '', '', '&item=' . $this->results[$index]['id'] . '&task=' . $this->results[$index]['job_name'] ) .$this->get_translation('nr').'.&nbsp;' . $this->results[$index]['id'] . '&nbsp;');
        if ( $this->enable_history_delete ) {
          $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_bottom . '" border="0" hspace="4" vspace="1">';
          $this->show_html( $this->print_jobaction( 'job_history_remove', '', $this->results[$index]['job_name'], $this->joblog_javascript, '', '', '', '&item=' . $this->results[$index]['id'] . '&task=' . $this->results[$index]['job_name'] . '&scheduler_date_from=' . $this->date_from . '&scheduler_date_to=' . $this->date_to ) );
        }
        $this->show_html('&nbsp;</td>' );
      } else {
        $this->show_html('  <td valign="bottom" align="left"  class="' . $this->style_td_action . '">&nbsp;</td>' );
      }
      
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">' . str_replace(' ', '&nbsp;', $this->to_datetime($this->results[$index]['start_time'])) . '&nbsp;</td>' );
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">' . str_replace(' ', '&nbsp;', $this->to_datetime($this->results[$index]['end_time'])) . '&nbsp;</td>' );
      $elapsed = ( $this->results[$index]['end_time'] != '') ? ( $this->to_timestamp($this->results[$index]['end_time'])-$this->to_timestamp($this->results[$index]['start_time']) ) : 0;
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $elapsed . 's' . '&nbsp;</td>' );
      $this->show_html('  <td align="right" class="' . $this->style_td_action . '">' . $this->results[$index]['steps'] . '&nbsp;</td>' );
      $this->show_html('  <td align="left"  class="' . $this->style_td_action . '">' . $this->get_task_cause($this->results[$index]['cause']) . '&nbsp;</td>' );
      if ( "" . $this->results[$index]['error'] != '0' ) {
        $colspan = ( $ok_db ) ? 4 : 3;
        $this->show_html('</tr><tr><td align="left" class="' . $this->style_td_action . '" colspan="' . $colspan . '">&nbsp;</td>');
        $this->show_html('  <td align="left"  class="' . $this->style_td_error . '" colspan="3">' . $this->results[$index]['error_code'] . ': ' . $this->results[$index]['error_text'] . '&nbsp;</td>' );
      }
    }

    $ok = !$this->db->error();

    $this->show_html( '</tr>' );
    return $ok;
  }


  /**
  * Detailanzeige einer Zeile des Abfrageergebnisses
  *
  * @param    integer $index Nr. des Satzes im Result Set
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_result_detail( $index ) {

    $this->show_error( 'not yet implemented' );
    return false;
  }


  /**
  * Formular mit Abfragekriterien anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_query() {

    $this->debug(3, 'show_query:' );
    $this->img_help_action    = '<img src="' . $this->img_dir . $this->img_help    . '" border="0" hspace="4" vspace="2">';

    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $this->scheduler_attributes['db'] == '' ) { 
      $this->show_error( $this->get_translation('service_without_history1') );
      $this->show_error( $this->get_translation('service_without_history2') );
      if ( $this->scheduler_attributes['log_file'] != '' ) { $this->show_error( $this->get_translation('service_without_history3', 'protocol='.$this->scheduler_attributes['log_file'] ) ); }
      return 0; 
    }

    if ( !$this->open_db() ) { return 0; }

    $this->show_html('<script language="JavaScript" type="text/javascript">');
    $this->show_html('  function sos_scheduler_query_form_onSubmit(action) {');
    $this->show_html('    var ok = true;' );
    $this->show_html('    if ( action == null || typeof(action) == "undefined" ) { return ok; }');
    $this->show_html('    if ( action.indexOf("job_query") > -1 ) {');
    $this->show_html('      if (ok) { ok = isValidDate(document.sos_scheduler_query_form.scheduler_date_from, false); }');
    $this->show_html('      if (ok) { ok = isValidDate(document.sos_scheduler_query_form.scheduler_date_to, false); }');
    $this->show_html('    }');
    $this->show_html('    return ok;');
    $this->show_html('  }');
    $this->show_html('</script>');    

    $this->show_html('<form name="sos_scheduler_query_form" action="' . $this->site . $this->site_con . 'action=job_query' . $query_session . '" method="post" onSubmit="return sos_scheduler_query_form_onSubmit(\'job_query\')">');
    $this->show_answer_table_begin();

    $this->show_html('  <tr valign="middle">');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="hidden" name="spooler_host" value="' . $this->host . '"><input type="hidden" name="spooler_port" value="' . $this->port . '"><input type="image" name="btn_history" src="' . $this->img_dir . SOS_LANG . '/btn_history.gif"></td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '">'.$this->get_translation('job').'&nbsp;</td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><select name="scheduler_job" size="1">');
    $this->show_html('       <option value="">'.$this->get_translation('select_all') );
    if ( !$this->enable_all_tasks && count($this->selected_jobs) > 0 ) {
      $this->show_html('       <option value="(Scheduler)"' );
      $selected = ( $this->job == '(Scheduler)' ) ? ' selected' : '';
      $this->show_html( $selected . '>'.$this->get_translation('select_scheduler') );
      for($i=0; $i<count($this->selected_jobs_order); $i++) {
        $selected = ( $this->job == $this->selected_jobs_order[$i] ) ? ' selected' : '';
        $this->show_html('        <option value="' . $this->selected_jobs_order[$i] . '"' . $selected . '>' . $this->selected_jobs_order[$i] );
      }
    } else {
      $sql_stmt  = ' select distinct "JOB_NAME" from ' . $this->table_history;
      if ( $this->scheduler_attributes['id'] != '' ) { $sql_stmt .= ' where "SPOOLER_ID" = \'' . $this->scheduler_attributes['id'] . '\''; }
      $sql_stmt .= ' order by "JOB_NAME"';
      $select_tasks = $this->db->get_array( $sql_stmt );
      for($i=0; $i<count($select_tasks); $i++) {
        $selected = ( $this->job == $select_tasks[$i]['job_name'] ) ? ' selected' : '';
        $this->show_html('        <option value="' . $select_tasks[$i]['job_name'] . '"' . $selected . '>' . $select_tasks[$i]['job_name'] );
      }
    }
    $this->show_html('      </select>&nbsp;</td>');
    $this->show_html('    <td valign="middle" align="right" class="' .  $this->style_td_background . '">'.$this->get_translation('from').'&nbsp;&nbsp;</td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="text" name="scheduler_date_from" size="18" value="' . $this->date_from . '">&nbsp;&nbsp;<a href="javascript:show_calendar(\'sos_scheduler_query_form.scheduler_date_from\');"><img src="' . $this->img_dir . 'cal.gif" valign="bottom" border="0" alt="'.$this->get_translation('calendar').'"></a>&nbsp;</td>');
    $this->show_html('    <td valign="middle" align="right" class="' .  $this->style_td_background . '">'.$this->get_translation('to').'&nbsp;&nbsp;</td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="text" name="scheduler_date_to" size="18" value="' . $this->date_to . '">&nbsp;&nbsp;<a href="javascript:show_calendar(\'sos_scheduler_query_form.scheduler_date_to\');"><img src="' . $this->img_dir . 'cal.gif" valign="bottom" border="0" alt="'.$this->get_translation('calendar').'"></a>&nbsp;</td>');

    $this->show_html('  </tr>');
    $this->show_html('  <tr valign="middle">');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="image"  name="btn_history_group" src="' . $this->img_dir . SOS_LANG . '/btn_group.gif" onClick="document.sos_scheduler_query_form.action += \'&range=group\';"></td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '">'.$this->get_translation('coverage').'&nbsp;&nbsp;</td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><select name="scheduler_job_error" size="1">');
    $select_errors = Array( '(alle)' => -1, 'ohne Fehler' => 0, 'mit Fehlern' => 1 );
    foreach( $select_errors as $name => $value) {
      $selected = ( $this->job_error == $value ) ? ' selected' : '';
      $this->show_html('        <option value="' . $value . '"' . $selected . '>' . $name );
    }
    $this->show_html('      </select>&nbsp;</td>');
    $this->show_html('    <td valign="middle" align="right" class="' .  $this->style_td_background . '"><nobr>'.$this->get_translation('job').' '.$this->get_translation('nr').'.&nbsp;&nbsp;</nobr></td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="text" name="scheduler_job_id" size="10" value="' . $this->job_id . '">&nbsp;</td>');
    $this->show_html('    <td valign="middle" align="right" class="' .  $this->style_td_background . '">'.$this->get_translation('error_text').'&nbsp;&nbsp;</td>');
    $this->show_html('    <td valign="middle" class="' .  $this->style_td_background . '"><input type="text" name="scheduler_job_error_text" size="20" value="' . $this->job_error_text . '">&nbsp;</td>');

    $this->show_html('  </tr>');

    $this->show_answer_table_end();
    $this->show_html('</form>');

  }


  /**
  * Historieneintrag eines Jobs entfernen
  *
  * @param    integer $item Job-ID des zu entfernenden Satzes
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function remove_job_history( $item=0 ) {
    
    $this->debug(3, 'remove_job_history: item=' . $item);
    
    if ( $item > 0 ) { $this->item = $item; }
    if ( isset($_REQUEST['job']) ) { $this->job = $_REQUEST['job']; }

    if ( !$this->open_db() ) { return 0; }
    
    $this->db->execute( 'delete from ' . $this->table_history . ' where "ID" = ' . $this->item );
    if ( !$this->db->error() ) {
      $this->db->commit();
    }

    return !$this->db->error();
  }


  /**
  * Statusabfrage an den Scheduler senden
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_status_scheduler() {

    $cmd = '<show_state what="all"/>';
  
    $this->state = 1;
    fputs( $this->sh, $cmd );
    $this->get_answer();
    $this->get_answer_elements();

    return 1;
  }

} // end of class SOS_Scheduer_History

?>