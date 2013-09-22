<?php

if ( !class_exists('SOS_Class') )                { require( 'class/sos_class.inc.php'); }

if (version_compare(PHP_VERSION,'5','>=') && !class_exists('php4DOMDocument'))  { include('util/domxml-php4-to-php5.php'); }

/**
* SOS Scheduler
*
* Kontrollfunktionen und Anzeige des Dienststatus
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/10/23
* @since        1.0-2002/12/01
* @since        1.1-2003/09/30
* @since        1.1-2004/02/14
*
* @access       public
* @package      SCHEDULER
*/

class SOS_Scheduler extends SOS_Class {

  /** access public */

  /* Seite, die ein Objekt der Klasse instantiiert */
  var $site                 = 'scheduler.php';
  
  /** Rechner, auf dem der Scheduler läuft */
  var $host                 = 'localhost';
  
  /** Port, auf dem der Scheduler läuft */
  var $port                 = 4444;

  /** Timeout für Socket-Verbindungen */
  var $timeout              = 15;

  /** Standard-Titel für Header-Methode */
  var $title                = 'JOB SCHEDULER';

  /** Untertitel für Header-Methode */
  var $subtitle             = '';

  /** Task, auf die die Anzeige eingeschränkt wird */
  var $task                 = '';

  /** Aktion, die ausgeführt werden soll */
  var $action               = 'spooler_status';
  
  /** Job-ID der History */
  var $item                 = 0;

  /** Anzahl von Einträgen, z.B. der History */
  var $range                = 0;

  /** Fensterhöhe des Task-Fensters */
  var $task_window_height   = '500';                        

  /** Fensterbreite des Task-Fensters */
  var $task_window_width    = '900';                        

  /** Timer zum Wiederholen der Anzeige bei laufenden Tasks */
  var $task_timer           = '8000';                       

  /** XML-Parser aus Extension wird benötigt */
  var $extension_domxml     = 'domxml';

  /** Query-Parameter für Session-ID */
  var $session_var          = '';

  /** Query-Parameterwert für Session-ID */
  var $session_id           = '';

  /** Session-ID wird von PHP automatisch übergeben: aus php.ini */
  var $session_use_trans_sid= 0;

  /** Anzahl History-Einträge in Darstellung */
  var $history_interval     = 10;


  /** Anzeige der Scheduler-Kontrolle zulassen */
  var $enable_spooler       = 1;

  /** Alle Jobs des Schedulers anzeigen, sonst nur die mit select_task() ausgewählten */
  var $enable_all_tasks     = 1;

  /** Job-Beschreibung mit Job anzeigen */
  var $enable_description   = 0;

  /** Auftragswarteschlangen anzeigen */
  var $enable_job_chains    = 0;

  /** Job-Beschreibung mit Aufträgen anzeigen */
  var $enable_job_orders    = 0;

  /** Dynamische Startzeit zulassen */
  var $enable_start_time    = 0;

  /** Dynamischen Job-Start aus Web-Seite zulassen */
  var $enable_add_jobs      = 0;                            

  /** Timer für Aktualisierung der Job-Anzeige aktivieren */
  var $enable_timer         = 0;

  /** Fenstergröße für einzelne Job-Darstellung reduzieren */
  var $enable_resize        = 1;

  /** Recherche der Historiendatenbank zulassen (erfordert hostPHP) */
  var $enable_history       = 0;

  /** Entfernen von Einträgen der Historiendatenbank zulassen */
  var $enable_history_delete= 0;

  /** Überwachung entfernter Scheduler zulassen */
  var $enable_monitoring    = 0;


  /** Thread für ausführbare Datei in temporärem Job-Start */
  var $process_thread       = '';

  /** Ausführbare Datei für temporären Job-Start */
  var $process_file         = '';
  
  /** Parameter für temporären Job-Start */
  var $process_param        = '';
  
  /** Separate Log-Datei für temporären Job-Start */
  var $process_log          = '';

  /** Parameter-Name für Job-Start */
  var $job_param_names      = array();

  /** Parameter-Wert für Job-Start */
  var $job_param_values     = array();
  
  /** Verzeichnis für Job Parameterdateien */
  var $job_dir              = '../config/';
  

  /** CSS-Datei mit Style-Definitionen */
  var $style_sheet          = 'scheduler.css';

  /** CSS-Klasse für Titel-Majuskeln */
  var $style_font_majus     = 'spoolerFontMajus';

  /** CSS-Klasse für Titel-Minuskeln */
  var $style_font_minus     = 'spoolerFontMinus';

  /** CSS-Klasse für dynamische Daten */
  var $style_font_entry     = 'spoolerFontEntry';

  /** CSS-Klasse für Fehler in dynamischen Daten */
  var $style_font_err       = 'spoolerFontErr';

  /** CSS-Klasse für Meldungen in dynamischen Daten */
  var $style_font_msg       = 'spoolerFontMsg';

  /** CSS-Klasse für Titel in dynamischen Daten */
  var $style_font_title     = 'spoolerFontTitle';
  
  /** CSS-Klasse für Tabellen-Überschriften */
  var $style_th             = 'spoolerTableHeader';

  /** CSS-Klasse für Tabellen-Überschriften */
  var $style_th_sub         = 'spoolerTableHeaderSub';

  /** CSS-Klasse für Tabellen-Zeilen mit Werten */
  var $style_td             = 'spoolerTableValue';

  /** CSS-Klasse für Tabellen-Spalte mit Labeln */
  var $style_td_label       = 'spoolerTableLabel';

  /** CSS-Klasse für Tabellen-Hintergrund */
  var $style_td_background  = 'spoolerTableBackground';

  /** CSS-Klasse für Tabellen-Zeilen mit Aktionen */
  var $style_td_action      = 'spoolerTableAction';

  /** CSS-Klasse für Tabellen-Zeilen mit Text */
  var $style_td_text        = 'spoolerTableText';

  /** CSS-Klasse für Tabellen-Spalte mit Fehlermeldung */
  var $style_td_error       = 'spoolerTableError';
  
  /** CSS-Klasse für Tabellen-Spalte mit Job-Meldung */
  var $style_td_msg         = 'spoolerTableMsg';

  /** CSS-Klasse für Tabellen-Überschriften */
  var $style_th_task        = 'spoolerTableHeaderTask';

  /** CSS-Klasse für Link Scheduler-Aktionen */
  var $style_scheduler_action = 'spoolerLinkAction';
  
  /** CSS-Klasse für Link Job-Aktionen */
  var $style_job_action     = 'spoolerLinkJobaction';
  

  /** Verzeichnis der Graphiken */
  var $img_dir              = 'images/scheduler/';

  /** Graphik für Scheduler-Auswahl */
  var $img_select           = 'btn_scheduler.gif';
  
  /** Graphik für Scheduler-Aktionen */
  var $img_scheduler        = 'arr_rightlr.gif';
  
  /** Graphik für Job-Aktionen */
  var $img_job              = 'arr_rightb.gif';
  
  /** Graphik für Seitenanfang */
  var $img_top              = 'arr_upb.gif';
  
  /** Graphik für Seitenende */
  var $img_bottom           = 'arr_downb.gif';

  /** Graphik für Hilfe-Symbol */
  var $img_help             = 'help.gif';
  
  /** Scheduler-Beschreibungen */
  var $url_doc              = 'doc/spooler_doku.htm';

  /** Virtuelles Verzeichnis mit Log-Datei */
  var $log_dir              = 'logs/';
  
  /** Verzeichnis der Sparch-Dateien */
  var $lang_dir             = 'languages/scheduler/';
  
  /** Sprachsteuerung: Name der Sprachdatei */
  var $lang_file_name       = 'sos_scheduler_lang.inc.php';
  
  /** Aufbereitete Fehlernachricht */
  var $errmsg               = '';


  /** Scriptdatei mit Prüfroutinen */
  var $file_javascript      = 'scheduler.js';
  
  /** Script mit Bestätigungsabfrage bei Scheduler-Aktionen */
  var $action_javascript    = 'onClick="return confirm(\'Diese Aktion beeinflusst den Betrieb des Schedulers. \nSind Sie mit der Aktion einverstanden?\');"';

  /** Script mit Bestätigungsabfrage bei Job-Aktionen */
  var $jobaction_javascript = 'onClick="return confirm(\'Diese Aktion beeinflusst den Ablauf des Jobs. \nSind Sie mit der Aktion einverstanden?\');"';

  /** Datumsformat in Darstellung dd.mm.yyyy */
  var $date_format          = 'dd.mm.yy';

  /** Datum/Zeitformat in Darstellung dd.mm.yyyy hh:mm:ss */
  var $datetime_format      = 'dd.mm.yy HH:MM:SS';

  /** Leeres Datum zurückliefern oder Format 00.00.00 */
  var $date_as_null         = 1;

  /** Name einer Funktion zur DeQuotierung von Strings aus Requests von Formulareingaben */
  var $normalize_value;

  /** Name einer Funktion zur DeQuotierung von Strings für Eingabefelder in Formularen */
  var $normalize_input;
  

  /** @access private */

  
  /** XML-Attribute des Schedulers */
  var $scheduler_attributes           = array();
  
  /** XML-Elemente der Scheduler-Prozessklassen */
  var $scheduler_process_classes      = array();
  
  /** Default-Prozessklassen für Job-Starts */
  var $scheduler_process_class        = '';

  /** Index für Scheduler-Prozessklassen */
  var $scheduler_process_class_count  = 0;
  
  /** XML-Elemente der Scheduler-Threads */
  var $scheduler_threads              = array();
  
  /** Default-Thread für Job-Starts */
  var $scheduler_thread               = '';

  /** Index für Scheduler-Threads */
  var $scheduler_thread_count         = 0;
  
  /** XML-Elemente der Scheduler-Jobs */
  var $scheduler_jobs                 = array();
  
  /** Default-Job für Job-Starts */
  var $scheduler_job                  = '';

  /** Index für Scheduler-Jobs */
  var $scheduler_job_count            = 0;
  
  /** XML-Elemente der Scheduler-Tasks */
  var $scheduler_tasks                = array();

  /** Warteschlange des Schedulers */
  var $scheduler_queued_tasks         = array();
  
  /** XML-Elemente der Scheduler-Tasks */
  var $scheduler_task_children        = array();
  
  /** Zähler für Anker-Referenz */
  var $scheduler_task_count           = 0;

  /** XML-Attribute der Task-Historien */
  var $scheduler_history_attributes   = array();

  /** XML-Attribute der Parameter in Task-Historien */
  var $scheduler_history_variables    = array();
  
  /** XML-Elemente der Scheduler-Tasks */
  var $scheduler_job_chains           = array();

  /** Liste der selektierten Scheduler-Tasks */
  var $selected_jobs                  = array();

  /** Index auf aktuell selektierten Task */
  var $selected_job_index             = '';
  
  /** Index auf aktuell selektierten Task-Parameter */
  var $selected_param_index           = '';
  
  /** Sortierte Liste der selektierten Taks */
  var $selected_jobs_order            = array();
  
  /** Index auf sortierte Liste */
  var $selected_jobs_order_index      = -1;

  /** Concatenator für Query-Strings */
  var $site_con             = '?';

  /** TCP Connection-Handle */
  var $sh                   = 0;

  /** Antwort des Schedulers im XML-Format */
  var $answer               = '';

  /** Status-Antwort des Schedulers verfügbar */
  var $state                = 0;
  
  /** Connection Fehler-Nr. */
  var $errno                = 0;

  /** Connection Fehler-Text */
  var $errstr               = '';

  /** Graphiken für die Aktions-Links */
  var $img_scheduler_action = '';
  var $img_job_action       = '';
  var $img_top_action       = '';
  var $img_bottom_action    = '';
  var $img_help_action      = '';

  /** Arrays für Zustände und Antworten */
  var $task_states;
  var $task_causes;
  var $scheduler_states;
  var $scheduler_cmds;
  var $remote_schedulers    = array();
  
                                                    
  /**                                                                                      
  * Konstruktor                                                                            
  *                                                                                        
  * @access   public                                                                       
  * @author   Andreas Püschel <ap@sos-berlin.com>                                          
  * @version  1.0-2002/07/07                                                               
  */

  function SOS_Scheduler() {

    if (!defined('SOS_LANG')) { define('SOS_LANG', 'de');}

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
    
    $this->translations['action_javascript']                    = 'Diese Aktion beeinflusst den Betrieb des Schedulers. \nSind Sie mit der Aktion einverstanden?';
    $this->translations['jobaction_javascript']                 = 'Diese Aktion beeinflusst den Ablauf des Jobs. \nSind Sie mit der Aktion einverstanden?';
    $this->translations['job_params_delete_javascript']         = 'Alle gespeicherten Job-Parameter werden entfernt. \nSind Sie mit der Aktion einverstanden?';
    $this->translations['empty_process_file_alert']             = 'Eine Datei muss zum Job-Start angegeben werden';
    $this->translations['scheduler_state_starting']             = 'startet gerade';
    $this->translations['scheduler_state_running']              = 'in Verarbeitung';
    $this->translations['scheduler_state_paused']               = 'angehalten';
    $this->translations['scheduler_state_stopping']             = 'beendet sich gerade';
    $this->translations['scheduler_state_stopping_let_run']     = 'wird sich nach letzter Task beenden';
    $this->translations['scheduler_state_stopped']              = 'beendet';
    $this->translations['task_state_none']                      = 'ohne Startzeit';
    $this->translations['task_state_ending']                    = 'beendet sich gerade (mit Wiederanlauf)';
    $this->translations['task_state_ended']                     = 'beendet (mit Wiederanlauf)';
    $this->translations['task_state_stopped']                   = 'gestoppt (ohne Wiederanlauf)';
    $this->translations['task_state_pending']                   = 'wartet auf nächsten Start';
    $this->translations['task_state_running']                   = 'in Verarbeitung';
    $this->translations['task_state_suspended']                 = 'angehalten';
    $this->translations['task_state_task_created']              = 'im Startvorgang';
    $this->translations['task_state_starting']                  = 'gestartet';
    $this->translations['task_state_start_task']                = 'Job startet';
    $this->translations['task_state_loading']                   = 'Job wird geladen';
    $this->translations['task_state_loaded']                    = 'Job ist geladen';
    $this->translations['task_state_running_process']           = 'Job in Verarbeitung';
    $this->translations['task_state_running_delayed']           = 'Job wird verzögert';
    $this->translations['task_state_running_waiting_for_order'] = 'Task erwartet Auftrag';
    $this->translations['task_state_read_error']                = 'Skriptfehler in Datei (include)';
    $this->translations['task_state_release']                   = 'Task beendet sich';
    $this->translations['task_state_closed']                    = 'Task ist geschlossen';
    $this->translations['task_state_exit']                      = 'Task wird beendet';
    $this->translations['task_cause_none']                      = 'manuell';
    $this->translations['task_cause_period_once']               = 'einmalig';
    $this->translations['task_cause_period_single']             = 'periodisch zum Zeitpunkt';
    $this->translations['task_cause_period_repeat']             = 'periodisch durch Intervall';
    $this->translations['task_cause_job_repeat']                = 'Job-Wiederholung';
    $this->translations['task_cause_order']                     = 'auftragsgesteuert';
    $this->translations['task_cause_queue']                     = 'per Job';
    $this->translations['task_cause_queue_at']                  = 'manueller Job-Start';
    $this->translations['task_cause_directory']                 = 'Verzeichnisüberwachung';
    $this->translations['task_cause_signal']                    = 'per Signalisierung';
    $this->translations['task_cause_delay_after_error']         = 'verzögert nach Fehler';
    $this->translations['job_start']                            = 'Starten';      
    $this->translations['job_end']                              = 'Beenden';      
    $this->translations['job_unstop']                           = 'Wiederanlaufen';
    $this->translations['invalid_port']                         = 'Nicht numerische Angabe des Ports: $port';
    $this->translations['connection_failed']                    = 'Fehler beim Verbindungsaufbau zum Scheduler auf Host $host, Port $port. Ist der Dienst gestartet? [$errno]: $errstr';
    $this->translations['host']                                 = 'Host';
    $this->translations['ip']                                   = 'IP-Adresse';
    $this->translations['port']                                 = 'Port';
    $this->translations['connected']                            = 'verbunden';
    $this->translations['version']                              = 'Version';
    $this->translations['at_host']                              = 'an Host';
    $this->translations['documentation']                        = 'Dokumentation';
    $this->translations['job']                                  = 'Job';
    $this->translations['parameter']                            = 'Parameter';
    $this->translations['name']                                 = 'Name';
    $this->translations['value']                                = 'Wert';
    $this->translations['history']                              = 'Historie';
    $this->translations['is_history']                           = 'Keine Historie für diesen Job verfügbar'; 
    $this->translations['job_id']                               = 'Job-ID';
    $this->translations['duration']                             = 'Dauer';
    $this->translations['steps']                                = 'Schritte';
    $this->translations['protocol']                             = 'Protokoll';
    $this->translations['start']                                = 'Start';
    $this->translations['end']                                  = 'Ende';
    $this->translations['type_of_start']                        = 'Startart';
    $this->translations['error']                                = 'Fehler';
    $this->translations['newer_jobs']                           = 'Neuere Jobs';
    $this->translations['older_jobs']                           = 'Ältere Jobs';
    $this->translations['investigation']                        = 'Recherche';
    $this->translations['remote_schedulers']                    = 'Registrierte Scheduler';
    $this->translations['scheduler']                            = 'Scheduler';
    $this->translations['scheduler_id']                         = 'Scheduler-ID';
    $this->translations['time']                                 = 'Zeit';
    $this->translations['scheduler_running_since']              = 'Scheduler-Start';
    $this->translations['cpu_time']                             = 'CPU-Verbrauch';
    $this->translations['orders']                               = 'Aufträge';
    $this->translations['queue']                                = 'Warteschlange';
    $this->translations['from_queued_tasks']                    = 'aus&nbsp;Warteschlange';
    $this->translations['queued_tasks_per_job']                 = 'in&nbsp;Warteschlange $queued_tasks für Job $job';
    $this->translations['efficiency']                           = 'Auslastung';
    $this->translations['kill_task']                            = 'Entfernen';
    $this->translations['job_queue_task']                       = 'Verwalten';
    $this->translations['enqueued']                             = 'eingereiht am';
    $this->translations['start_at']                             = 'Startzeit';
    $this->translations['next_start']                           = 'Nächste Startzeit';
    $this->translations['action']                               = 'Aktion';
    $this->translations['order']                                = 'Auftrag';
    $this->translations['job_chain']                            = 'Job-Kette';
    $this->translations['job_chains']                           = 'Job-Ketten';
    $this->translations['order_queue_length']                   = 'Offene&nbsp;Aufträge';
    $this->translations['entry_state']                          = 'Eingangsstatus';
    $this->translations['exit_state']                           = 'Ausgangsstatus';
    $this->translations['error_state']                          = 'Fehlerstatus';
    $this->translations['thread']                               = 'Thread'; 
    $this->translations['sleeping_until']                       = 'Nächster Start';
    $this->translations['task']                                 = 'Task';
    $this->translations['running_tasks']                        = 'Laufende Tasks';
    $this->translations['started_tasks']                        = 'Gestartete Tasks';
    $this->translations['calls']                                = 'Aufrufe';
    $this->translations['info']                                 = 'Info'; 
    $this->translations['settled']                              = 'erledigt'; 
    $this->translations['since']                                = 'seit'; 
    $this->translations['inactive']                             = 'inaktiv';
    $this->translations['file']                                 = 'Datei'; 
    $this->translations['line']                                 = 'Zeile'; 
    $this->translations['column']                               = 'Spalte'; 
    $this->translations['error_time']                           = 'Fehler aufgetreten am';
    $this->translations['clock']                                = 'Uhr';
    $this->translations['program']                              = 'Programm';
    $this->translations['btn_store']                            = 'Speichern';
    $this->translations['btn_delete']                           = 'Entfernen';
    $this->translations['btn_cancel']                           = 'Abbrechen';
    $this->translations['btn_reset']                            = 'Verwerfen';
    $this->translations['back_to_startpage']                    = 'Zurück zur Anfangsseite';
    $this->translations['top_of_page']                          = 'Seitenanfang';
    $this->translations['bottom_of_page']                       = 'Seitenende';
    $this->translations['spooler_status']                       = 'Status';           
    $this->translations['spooler_status2']                      = 'Status erneut prüfen';           
    $this->translations['spooler_pause']                        = 'Anhalten';         
    $this->translations['spooler_continue']                     = 'Fortsetzen';       
    $this->translations['spooler_stop']                         = 'Stoppen';          
    $this->translations['spooler_restart']                      = 'Neustart';         
    $this->translations['spooler_init']                         = 'Initialisieren';   
    $this->translations['spooler_reread']                       = 'Neu laden';        
    $this->translations['spooler_terminate']                    = 'Beenden';          
    $this->translations['spooler_terminate_restart']            = 'Beenden/Neustart'; 
    $this->translations['spooler_abort']                        = 'Abbrechen';        
    $this->translations['spooler_abort_restart']                = 'Abbrechen/Neustart';
    $this->translations['spooler_abort_restart2']               = 'Abbrechen mit Neustart';
    $this->translations['scheduler_answer_error1']              = 'Der Job Scheduler ist aktiv, liefert aber keine Antwort<br>Falls der Job Scheduler nicht mehr reagiert, können Sie';
    $this->translations['scheduler_answer_error2']              = 'Die PHP-Erweiterung $extension_domxml wird zur Anzeige der Informationen benötigt<br>Die Einstellung erfolgt in der Konfigurationsdatei php.ini';
    $this->translations['scheduler_answer_error3']              = 'XML-Fehler beim Parsieren der Antwort des Job Schedulers';     
    
    if( defined('SOS_LANG') ) { $this->language = SOS_LANG; }
    if( $include_lang_file )  { $this->get_lang_file( $this->lang_file_name ); }  
  }
  
  
   
  /**
  * Initialisierung
  *
  * @return   boolean Fehlerzustand
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function init() {
  	
    $this->session_use_trans_sid = ini_get( 'session.use_trans_sid' );

    $this->normalize_value       = ( get_magic_quotes_gpc() ) ? 'unquote' : 'nop';
    $this->normalize_input       = ( get_magic_quotes_gpc() ) ? 'quote'   : 'nop';

    $this->scheduler_attributes['time']                   = ''; 
    $this->scheduler_attributes['spooler_running_since']  = ''; 
    $this->scheduler_attributes['cpu_time']               = ''; 
    $this->scheduler_attributes['log_file']               = '';
    $this->scheduler_attributes['db']                     = '';
    $this->scheduler_attributes['id']                     = '';
    $this->scheduler_attributes['state']                  = '';

    $this->scheduler_cmds['spooler_status']               = '<show_state/>';
    $this->scheduler_cmds['spooler_pause']                = '<modify_spooler cmd="pause"/>';
    $this->scheduler_cmds['spooler_continue']             = '<modify_spooler cmd="continue"/>';
    $this->scheduler_cmds['spooler_stop']                 = '<modify_spooler cmd="stop"/>';
    $this->scheduler_cmds['spooler_reload']               = '<modify_spooler cmd="reload"/>';
    $this->scheduler_cmds['spooler_terminate']            = '<modify_spooler cmd="terminate"/>';
    $this->scheduler_cmds['spooler_terminate_restart']    = '<modify_spooler cmd="terminate_and_restart"/>';
    $this->scheduler_cmds['spooler_restart']              = '<modify_spooler cmd="let_run_terminate_and_restart"/>';
    $this->scheduler_cmds['spooler_abort']                = '<modify_spooler cmd="abort_immediately"/>';
    $this->scheduler_cmds['spooler_abort_restart']        = '<modify_spooler cmd="abort_immediately_and_restart"/>';

    $this->scheduler_cmds['job_suspend']                  = '<modify_job cmd="suspend"/>';
    $this->scheduler_cmds['job_continue']                 = '<modify_job cmd="continue"/>';
    $this->scheduler_cmds['job_stop']                     = '<modify_job cmd="stop"/>';
    $this->scheduler_cmds['job_unstop']                   = '<modify_job cmd="unstop"/>';
    $this->scheduler_cmds['job_start']                    = '<modify_job cmd="start"/>';
    $this->scheduler_cmds['job_end']                      = '<modify_job cmd="end"/>';
    $this->scheduler_cmds['job_reread']                   = '<modify_job cmd="reread"/>';

    $this->scheduler_states['starting']                   = $this->get_translation('scheduler_state_starting');
    $this->scheduler_states['running']                    = $this->get_translation('scheduler_state_running');
    $this->scheduler_states['paused']                     = $this->get_translation('scheduler_state_paused');
    $this->scheduler_states['stopping']                   = $this->get_translation('scheduler_state_stopping');
    $this->scheduler_states['stopping_let_run']           = $this->get_translation('scheduler_state_stopping_let_run');
    $this->scheduler_states['stopped']                    = $this->get_translation('scheduler_state_stopped');

    $this->task_states['0']                             = array( '', 0 );
    $this->task_states['none']                          = array( $this->get_translation('task_state_none'), 0 );
    $this->task_states['ending']                        = array( $this->get_translation('task_state_ending'), 1 );
    $this->task_states['ended']                         = array( $this->get_translation('task_state_ended'), 1 );
    $this->task_states['stopped']                       = array( $this->get_translation('task_state_stopped'), 0 );
    $this->task_states['pending']                       = array( $this->get_translation('task_state_pending'), 0 );
    $this->task_states['running']                       = array( $this->get_translation('task_state_running'), 1 );
    $this->task_states['suspended']                     = array( $this->get_translation('task_state_suspended'), 0 );
    $this->task_states['task_created']                  = array( $this->get_translation('task_state_task_created'), 1 );
    $this->task_states['starting']                      = array( $this->get_translation('task_state_starting'), 1 );
    $this->task_states['start_task']                    = array( $this->get_translation('task_state_start_task'), 1 );
    $this->task_states['loading']                       = array( $this->get_translation('task_state_loading'), 1 );
    $this->task_states['loaded']                        = array( $this->get_translation('task_state_loaded'), 1 );
    $this->task_states['running_process']               = array( $this->get_translation('task_state_running_process'), 1 );
    $this->task_states['running_delayed']               = array( $this->get_translation('task_state_running_delayed'), 1 );
    $this->task_states['running_waiting_for_order']     = array( $this->get_translation('task_state_running_waiting_for_order'), 0 );
    $this->task_states['read_error']                    = array( $this->get_translation('task_state_read_error'), 0 );
    $this->task_states['release']                       = array( $this->get_translation('task_state_release'), 1 );
    $this->task_states['closed']                        = array( $this->get_translation('task_state_closed'), 1 );
    $this->task_states['exit']                          = array( $this->get_translation('task_state_exit'), 1 );

    $this->task_causes['none']                          = array( $this->get_translation('task_cause_none'), '' );
    $this->task_causes['period_once']                   = array( $this->get_translation('task_cause_period_once'), '&lt;run_time once="yes"&gt;' );
    $this->task_causes['period_single']                 = array( $this->get_translation('task_cause_period_single'), '&lt;run_time single_start="..."&gt;' );
    $this->task_causes['period_repeat']                 = array( $this->get_translation('task_cause_period_repeat'), '&gt;run_time repeat="..."&gt;' );
    $this->task_causes['job_repeat']                    = array( $this->get_translation('task_cause_job_repeat'), 'spooler_job.repeat="..."' );
    $this->task_causes['order']                         = array( $this->get_translation('task_cause_order'), 'spooler.job_chain.add_order' );
    $this->task_causes['queue']                         = array( $this->get_translation('task_cause_queue'), 'spooler_job.start oder &lt;start_job&gt;' );
    $this->task_causes['queue_at']                      = array( $this->get_translation('task_cause_queue_at'), '&lt;start_job at="..."&gt;' );
    $this->task_causes['directory']                     = array( $this->get_translation('task_cause_directory'), 'spooler_job.start_when_directory_changed' );
    $this->task_causes['signal']                        = array( $this->get_translation('task_cause_signal'), '&lt;signal_object&gt;' );
    $this->task_causes['delay_after_error']             = array( $this->get_translation('task_cause_delay_after_error'), 'delay_after_error' );
    
    $this->action_javascript                            = 'onClick="return confirm(\''.$this->get_translation('action_javascript').'\');"';
    $this->jobaction_javascript                         = 'onClick="return confirm(\''.$this->get_translation('jobaction_javascript').'\');"';
    
    if ( isset($_REQUEST['process_thread']) )  { $this->process_thread = $this->{$this->normalize_value}($_REQUEST['process_thread']); }
    if ( isset($_REQUEST['process_file']) )    { $this->process_file   = $this->{$this->normalize_value}($_REQUEST['process_file']); }
    if ( isset($_REQUEST['process_param']) )   { $this->process_param  = $this->{$this->normalize_value}($_REQUEST['process_param']); }
    if ( isset($_REQUEST['process_log']) )     { $this->process_log    = $this->{$this->normalize_value}($_REQUEST['process_log']); }
    if ( isset($_REQUEST['action']) )          { $this->action         = $_REQUEST['action']; }
  
    $this->extension_domxml = (version_compare(PHP_VERSION,'5','>=')) ? null : 'domxml';

    return true;
  }


  /**
  * Zurücksetzen
  *
  * @return   boolean Fehlerzustand
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function reset() {

    $this->item  = '';
    $this->range = '';
    $this->task  = '';


  /** XML-Elemente des Schedulers */
    $this->scheduler_attributes['time']                   = ''; 
    $this->scheduler_attributes['spooler_running_since']  = ''; 
    $this->scheduler_attributes['cpu_time']               = ''; 
    $this->scheduler_attributes['log_file']               = '';
    $this->scheduler_attributes['db']                     = '';
    $this->scheduler_attributes['id']                     = '';
    $this->scheduler_attributes['state']                  = '';
  
  /** XML-Elemente der Scheduler-Prozessklassen */
    $this->scheduler_process_classes      = array();
  
  /** Default-Prozessklassen für Job-Starts */
    $this->scheduler_process_class        = '';

  /** Index für Scheduler-Prozessklassen */
    $this->scheduler_process_class_count  = 0;
  
  /** XML-Elemente der Scheduler-Threads */
    $this->scheduler_threads              = array();
  
  /** Default-Thread für Job-Starts */
    $this->scheduler_thread               = '';

  /** Index für Scheduler-Threads */
    $this->scheduler_thread_count         = 0;
  
  /** XML-Elemente der Scheduler-Jobs */
    $this->scheduler_jobs                 = array();
  
  /** Default-Job für Job-Starts */
    $this->scheduler_job                  = '';

  /** Index für Scheduler-Jobs */
    $this->scheduler_job_count            = 0;
  
  /** XML-Elemente der Scheduler-Tasks */
    $this->scheduler_tasks                = array();

  /** Warteschlange des Schedulers */
    $this->scheduler_queued_tasks         = array();
  
  /** XML-Elemente der Scheduler-Tasks */
    $this->scheduler_task_children        = array();
  
  /** Zähler für Anker-Referenz */
   $this->scheduler_task_count            = 0;

  /** XML-Attribute der Task-Historien */
    $this->scheduler_history_attributes   = array();

  /** XML-Attribute der Parameter in Task-Historien */
    $this->scheduler_history_variables    = array();
  
  /** XML-Elemente der Scheduler-Tasks */
    $this->scheduler_job_chains           = array();

  /** XML-Elemente der registrierten Scheduler */
    $this->remote_schedulers              = array();

  /** Liste der selektierten Scheduler-Tasks */
    $this->selected_jobs                  = array();

  /** Index auf aktuell selektierten Task */
    $this->selected_job_index             = '';
  
  /** Index auf aktuell selektierten Task-Parameter */
    $this->selected_param_index           = '';
  
  /** Sortierte Liste der selektierten Taks */
    $this->selected_jobs_order            = array();
  
  /** Index auf sortierte Liste */
    $this->selected_jobs_order_index      = -1;

  }


  /**
  * TCP-Verbindung herstellen
  *
  * @param    string   $sp_host Host-Name des Schedulers
  * @param    integer  $sp_port Port-Nr. des Schedulers
  * @param    integer  $sp_timeout max. Anzahl Sekunden für Verbindungsaufbau
  * @return   boolean Fehlerzustand
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function connect( $host='localhost', $port=4444, $timeout=null ) {

    $this->img_scheduler_action = '<img src="' . $this->img_dir . $this->img_scheduler . '" border="0" hspace="4" vspace="2">';
    if ( $host != 'localhost' ) { $this->host = $host; }
    if ( $port != 4444 ) { $this->port = intval($port); }
    if ( $timeout != null ) { $this->timeout  = $timeout; }

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
  * TCP-Verbindung abbauen
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function disconnect() {

    if ( $this->sh > 0 ) { @fclose( $this->sh ); }
  }


  /**
  * Fehlernachricht anzeigen
  *
  * @param    string   $msg Fehlernachricht
  * @param    string   $class CSS-Klasse für Fehlerdarstellung
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_error( $msg, $class='spoolerErr' ) {

    $this->show_html('<table width="100%"><tr><td width="5">&nbsp;</td><td><font class="' . $class . '">' . $msg . '</font></td></tr></table>');
  }


  /**
  * Seitenkopf anzeigen
  *
  * @param    string   $title Titel
  * @param    string   $subtitle Untertitel
  * @param    string   $javascript Dateiname für eine JavaScript-Datei
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_header( $title='', $subtitle='', $javascript='' ) {

    if ( $title == '' )     { $title    = $this->title; }
    if ( $subtitle == '' )  { $subtitle = $this->subtitle; }
    $this->show_html( '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">' );
    $this->show_html( '<html>' );
    $this->show_html( '<head>' );
    $this->show_html( '  <title>' . $title . '&nbsp;&nbsp;-&nbsp;&nbsp;'.$this->get_translation('host').'&nbsp;' . $this->host . '&nbsp;'.$this->get_translation('port').'&nbsp;' . $this->port . '</title>' );
    $this->show_html( '  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">' );
    $this->show_html( '  <link rel="stylesheet" type="text/css" href="' . $this->style_sheet . '">' );
    if ( $javascript != "" ) {
      $this->show_html( '  <script language="JavaScript" type="text/javascript" src="' . $javascript . '"></script>' );
    }
    if ( $this->file_javascript != '' ) {
      $this->show_html( '  <script language="JavaScript" type="text/javascript" src="' . $this->file_javascript . '"></script>');
    }
    $this->show_html( '  <script language="JavaScript" type="text/javascript" src="'.$this->lang_dir.$this->language.'/sos_scheduler_lang.js"></script>' );
    $this->show_html( '</head>' );
    $this->show_html( '<body>' );
    $this->show_html( '<table width="100%">' );
    $this->show_html( '  <tr>');
    $this->show_html( '    <td width="1%">&nbsp;</td>' );
    $this->show_html( '    <td width="98%"><h3>' . $this->style_capitalize_title( $title ) . '&nbsp;&nbsp;&nbsp;' . $this->style_string( $subtitle ) . '</h3><hr></td>' );
    $this->show_html( '    <td width="1%">&nbsp;<a name="#header"/></td>' );
    $this->show_html( '  </tr>' );
    $this->show_html( '</table><br>' );
  }


  /**
  * Seitenfuß anzeigen
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_footer() {
  
    $this->show_html( '</body></html>' );
  }


  /**
  * Selektions-Formular für Scheduler anzeigen
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_select() {

    if ( $this->action == 'job_description' || $this->action == 'job_history' || $this->action == 'job_history_log' || $this->action == 'job_history_remove' || $this->action == 'job_params_show' ) { return; }

    $this->img_help_action    = '<img src="' . $this->img_dir . $this->img_help    . '" border="0" hspace="4" vspace="2">';
    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    $this->show_html('<form name="sos_spooler_select_form" action="' . $this->site . $this->site_con . 'action=spooler_status' . $query_session . '" method="post">');
    $this->show_html('<table width="100%" border="0">');
    $this->show_html('  <tr valign="bottom">');
    $this->show_html('    <td width="20" bgColor="#FFFFFF" valign="bottom">&nbsp;</td>');
    $this->show_html('    <td class="' . $this->style_td_background . '" valign="bottom">');
    $this->show_html('      <table border="0" cellPadding="5" cellSpacing="1" width="100%">');
    $this->show_html('        <tr valign="middle">');
    $this->show_html('          <td class="' .  $this->style_th . '" valign="bottom">');
    $this->show_html('            <input type="image" name="btn_spooler" src="' . $this->img_dir . SOS_LANG . '/' . $this->img_select . '">');
    $this->show_html('            <a name="select">'.$this->get_translation('at_host').'</a> <input type="text" name="spooler_host" size="20" value="' . $this->host . '">&nbsp;');
    $this->show_html('            '.$this->get_translation('port').' <input type="text" name="spooler_port" size="10" value="' . $this->port . '">&nbsp;&nbsp;&nbsp;&nbsp;');
    $this->show_html('            <a class="' . $this->style_job_action . '" href="' . $this->url_doc . '" target="_blank">' . $this->img_help_action . $this->get_translation('documentation').'</a>');
    $this->show_html('          </td>');
    $this->show_html('        </tr>');
    $this->show_html('      </table>');
    $this->show_html('    </td>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('  </tr>');
    $this->show_html('</table>');  
    $this->show_html('</form>');
  }



	/**
  * Queue-Job-Parameter aus Web-Oberfläche bzw. Parameterdatei lesen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_queue_params( $job ) {
  
    global $HTTP_POST_VARS;
		
		if(isset($HTTP_POST_VARS['job_queue_param_name']) && is_array($HTTP_POST_VARS['job_queue_param_name'])){
			foreach($HTTP_POST_VARS['job_queue_param_name'] as $cnt=>$param_name){
				$this->job_param_names[$cnt] = $param_name;
				if(isset($HTTP_POST_VARS['job_queue_param_value'][$cnt])){
					$this->job_param_values[$cnt] = $HTTP_POST_VARS['job_queue_param_value'][$cnt];
				}
				else if(isset($HTTP_POST_VARS['job_queue_param_value_'.$cnt])){
					$this->job_param_values[$cnt] = $HTTP_POST_VARS['job_queue_param_value_'.$cnt];
				}
				else{
					$this->job_param_values[$cnt] = '';
				}
			}	
		}
  }

  /**
  * Job-Parameter aus Web-Oberfläche bzw. Parameterdatei lesen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_params( $job ) {
  
    global $HTTP_POST_VARS;

    $cnt = 0;
    for($i=0; $i<20; $i++) {
      $param_name = 'job_param_name_' . $i;
      if ( isset($HTTP_POST_VARS[$param_name]) ) {
        if ( $HTTP_POST_VARS[$param_name] != '' ) {
          $this->job_param_names[$cnt] = $HTTP_POST_VARS[$param_name];
          if ( isset($HTTP_POST_VARS['job_param_value_' . $i] )) { 
            $this->job_param_values[$cnt] = $HTTP_POST_VARS['job_param_value_' . $i];
          } else {
            $this->job_param_values[$cnt] = '';
          }
          $cnt++;
        }
      }
    }
    
    // Vorbesetzung aus Parameter-Datei, wenn noch kein Formular gezeigt wurde
    if ( !isset($HTTP_POST_VARS['job_param_name_0']) ) {
      if ( !ini_get('safe_mode') && file_exists( $this->job_dir . $job . '.job' ) ) {
        $fd = fopen( $this->job_dir . $job . '.job', 'r' );
        $this->job_param_names  = fgetcsv ( $fd, 4000, ';' );
        $this->job_param_values = fgetcsv ( $fd, 4000, ';' );
        fclose( $fd );
        $cnt = count($this->job_param_names);
        for($i=0;$i<$cnt;$i++) {
          if (! isset($this->job_param_values[$i]) ) { $this->job_param_values[$i] = ''; }
        }
      }
    }
    
    for($i=$cnt; $i<($cnt+5); $i++) {
      $this->job_param_names[$i] = '';
      $this->job_param_values[$i] = '';
    }
    return $cnt;
  }
  

  /**
  * Job-Parameter aus Web-Oberfläche in Parameterdatei speichern
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function store_params( $job ) {
  
    $job_names  = array();
    $job_values = array();
    $cnt        = 0;
    
    if ( $this->get_params( $job ) ) {
      $fd = fopen( $this->job_dir . $job . '.job', 'w' );
      if ( ! $fd ) { return 0; }

      for($i=0;$i<count($this->job_param_names); $i++) {
        if ( $this->job_param_names[$i] != '' ) {
          $job_names[$cnt]    = $this->job_param_names[$i];
          $job_values[$cnt++] = $this->job_param_values[$i];
        }
      } 

      fwrite( $fd, implode(';', $job_names )  . "\n" );
      fwrite( $fd, implode(';', $job_values ) );
      fclose( $fd );
    } 
    else {
      $this->delete_params( $job );
    }
  }


  /**
  * Job-Parameter in Parameterdatei löschen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function delete_params( $job ) {

    $file = $this->job_dir . $job . '.job';
    if ( file_exists( $file ) ) { unlink( $file ); }
  }
  

  /**
  * Job-Parameter in Web-Oberfläche anzeigen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_params( $job ) {

    $this->get_params( $job );
    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    $this->img_help_action    = '<img src="' . $this->img_dir . $this->img_help    . '" border="0" hspace="4" vspace="2">';
    if ( !$this->session_use_trans_sid && $this->session_var != "" ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    $this->show_html('<form name="sos_spooler_param_form" action="' . $this->site . $this->site_con . 'action=job_params_show' . $query_session . '" method="post">');
    $this->show_html('<table width="100%" border="0">');
    $this->show_html('  <tr>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('    <td class="' . $this->style_td_background . '">');
    $this->show_html('      <table border="0" cellpadding="5" cellspacing="1" width="100%">');
    $this->show_html('        <tr valign="middle">');
    $this->show_html('          <td class="' .  $this->style_th . '" colspan="2">');
    $this->show_html('            <font class="spoolerFontMinus">'.$this->get_translation('job').'&nbsp;'.$this->get_translation('parameter').':&nbsp;' . $job . '</font>&nbsp;&nbsp;');
    $this->show_html('          </td>');
    $this->show_html('        </tr>');
    for($i=0; $i<count($this->job_param_names); $i++) {
      $this->show_html('        <tr valign="middle">');
      $this->show_html('          <td class="' .  $this->style_td . '">'.$this->get_translation('name').'&nbsp;&nbsp;<input type="text" size="30" name="job_param_name_'  . $i . '" value="' . $this->job_param_names[$i]  . '">&nbsp;&nbsp;</td>');
      $this->show_html('          <td class="' .  $this->style_td . '">'.$this->get_translation('value').'&nbsp;&nbsp;<input type="text" size="50" name="job_param_value_' . $i . '" value="' . $this->job_param_values[$i] . '">&nbsp;&nbsp;</td>');
      $this->show_html('          </td>');
      $this->show_html('        </tr>');
      if ( $i == 10 ) { break; }
    }
    $this->show_html('          <td class="' .  $this->style_td . '" colspan="2" align="center">');
    $this->show_html('            <input type="image" name="btn_store"  src="'.$this->img_dir.$this->language.'/btn_store.gif" onClick="document.sos_spooler_param_form.action=\'' . $this->site . $this->site_con . 'action=job_params_store&job='  . $job . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . '\'"/>&nbsp;&nbsp;');
    $this->show_html('            <input type="image" name="btn_delete" src="'.$this->img_dir.$this->language.'/btn_remove.gif" onClick="document.sos_spooler_param_form.action=\'' . $this->site . $this->site_con . 'action=job_params_delete&job=' . $job . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . '\'; return confirm(\''.$this->get_translation('job_params_delete_javascript').'\');"/>&nbsp;&nbsp;');
    $this->show_html('            <input type="image" name="btn_cancel" src="'.$this->img_dir.$this->language.'/btn_cancel.gif" onClick="document.sos_spooler_param_form.action=\'' . $this->site . $this->site_con . 'action=spooler_status&job='    . $job . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . '\'"/>&nbsp;&nbsp;');
    $this->show_html('            <image border="0" src="'.$this->img_dir.$this->language.'/btn_reset.gif" onclick="window.document.forms[\'sos_spooler_param_form\'].reset();"/>');
    $this->show_html('          </td>');
    $this->show_html('        </tr>');
    $this->show_html('      </table>');
    $this->show_html('    </td>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('  </tr>');
    $this->show_html('</table>');  
    $this->show_html('</form>');
  }


  /**
  * Job-Beschreibung anzeigen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_description( $job ) {
  
    $title = ( isset($this->scheduler_tasks[$job]['title']) ) ? '<br>' . $this->to_html($this->scheduler_tasks[$job]['title']) : '';
   #$content_desc = ( isset($this->scheduler_task_children[$job][0]->tagname) ) ? $this->scheduler_task_children[$job][0]->get_content() : $this->scheduler_task_children[$job][0]->content;
    $content_desc = ( $this->scheduler_task_children[$job][0]->tagname() ) ? $this->scheduler_task_children[$job][0]->get_content() : $this->scheduler_task_children[$job][0]->content;

    $this->show_html( '<table width="100%">' );
    $this->show_html( '  <tr>' );
    $this->show_html( '    <td width="1%">&nbsp;</td>' );
    $this->show_html( '    <td width="98%"><font class="spoolerFontMajus">'.$this->get_translation('job').': ' . $job . '</font><br><font class="spoolerFontHeader">' . $title . '</font><p>&nbsp;</p></td>' );
    $this->show_html( '    <td width="1%">&nbsp;</td>' );
    $this->show_html( '  </tr>' );
    $this->show_html( '  <tr>' );
    $this->show_html( '    <td width="1%">&nbsp;</td>' );
    $this->show_html( '    <td width="98%" class="' . $this->style_td_text . '">' . $this->to_html( $content_desc ) . '</td>' );
    $this->show_html( '    <td width="1%">&nbsp;</td>' );
    $this->show_html( '  </tr>' );
    $this->show_html( '</table>' );
  }
  

  /**
  * Job-Historie anzeigen
  *
  * @param    string   $job Job-Name
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_history( $job ) {

    $title = ( isset($this->scheduler_tasks[$job]['title']) ) ? '' . $this->to_html($this->scheduler_tasks[$job]['title']) : '';

    $is_history = isset($this->scheduler_history_attributes[$job]);
    
    if ( $is_history ) {
      $histories = $this->scheduler_history_attributes[$job];
      $is_history = (count($histories) > 0);
    }

    $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
    $this->img_top_action     = '<img src="' . $this->img_dir . $this->img_top     . '" border="0" hspace="4" vspace="2">';
    $this->img_bottom_action  = '<img src="' . $this->img_dir . $this->img_bottom  . '" border="0" hspace="4" vspace="2">';
    
    $this->show_answer_table_begin();
    $this->show_html( '<tr>' );
    $this->show_html( '  <td colspan="7" class="' . $this->style_td_background . '"><font class="spoolerFontMajus">'.$this->get_translation('job').' '.$this->get_translation('history').': ' . $title . '</font><font class="spoolerFontHeader"> (' . $job . ')</font></td>' );
    $this->show_html( '</tr>' );

    if ( !$is_history ) {
      $this->show_html( '  <tr><td colspan="7" class="' . $this->style_td_error . '">' . $this->print_jobaction( "job_history", $this->get_translation('back') . ' ', $job, "", "", "", "", "&item=" . 0 . "&range=-" . $this->history_interval . "&task=" . $job ) . '&nbsp;&nbsp;'.$this->get_translation('is_history').'</td></tr>' );
    }
    
    $this->show_answer_table_end();

    if ( $is_history ) {
      $this->show_answer_table_begin();
      $this->show_html( '  <tr>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('job_id').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('protocol').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('start').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('end').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('duration').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('steps').'</font>&nbsp;</th>' );
      $this->show_html( '    <th class="' . $this->style_th . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('type_of_start').'</font>&nbsp;</th>' );
      $this->show_html( '  </tr>' );

      for($i=0; $i<count($histories); $i++) {
        $elapsed = ( $histories[$i]['end_time'] != '') ? ( strtotime($histories[$i]['end_time'])-strtotime($histories[$i]['start_time']) ) : 0;
        $this->show_html( '  <tr>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '">' . $histories[$i]['id'] . '<a name="history' . $i . '"/>&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '">' );
        if ( $this->scheduler_attributes['db'] != '' ) {
          $this->show_html( $this->print_jobaction( 'job_history_log', $this->get_translation('protocol'), $job, '', '#history' . $this->item, '', '', '&item=' . $histories[$i]['id'] . '&task=' . $job.'&scope=simple' ) );
        }
        $this->show_html( '&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '">' . $this->to_datetime($histories[$i]['start_time']) . '&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '">' . $this->to_datetime($histories[$i]['end_time']) . '&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '" align="right">' . $elapsed . 's&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '" align="right">' . $histories[$i]['steps'] . '&nbsp;</td>' );
        $this->show_html( '    <td valign="top" class="' . $this->style_td . '">' . $this->get_task_cause($histories[$i]['cause']) . '&nbsp;</td>' );
        $this->show_html( '  </tr>' );

        if ( isset($this->selected_jobs[$job]) ) {
          $label_done = 0;
          foreach( $this->selected_jobs[$job]->history_entries as $name => $value) {
            if ( !$label_done ) { $label = $this->get_translation('history'); $label_done = 1; } else { $label = ''; }
            if ( !isset($histories[$i][$name]) ) { $histories[$i][$name] = ''; }
            $this->show_html( '  <tr>' );
            $this->show_html( '    <td valign="top" class="' . $this->style_td . '">&nbsp;</td>' );
            $this->show_html( '    <td valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">' . $label . '</font>&nbsp;</td>' );
            $this->show_html( '    <td valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">' . $value->prompt . '</font>&nbsp;</td>' );
            $this->show_html( '    <td valign="top" class="' . $this->style_td . '" colspan="4">' . $histories[$i][$name] . '&nbsp;</td>' );
            $this->show_html( '  </tr>' );
          }
        }

        $is_variables = isset($this->scheduler_history_variables[$job][$i]);
        if ( $is_variables ) {
          $variables = $this->scheduler_history_variables[$job][$i];
          $is_variables = (count($variables) > 0);
        }

        if ( $is_variables ) {
          $label_done = 0;
          for($j=0; $j<count($variables); $j++) {
            foreach( $variables[$j] as $name => $value) {
              if ( $value != '' ) {
                $this->show_html( '  <tr>' );
                if ( $name == 'history_log' ) {
                  $this->show_html( '    <td valign="top" class="' . $this->style_td . '">&nbsp;</td>' );
                  $colspan = 6;
                } else {
                  if ( !$label_done ) { $label = $this->get_translation('parameter'); $label_done = 1; } else { $label = ''; }
                  $this->show_html( '    <td valign="top" class="' . $this->style_td . '">&nbsp;</td>' );
                  $this->show_html( '    <td valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">' . $label . '&nbsp;</font></td>' );
                  if ( isset($this->selected_jobs[$job]) ) {
                    if ( isset($this->selected_jobs[$job]->params[$name]) ) { $name = $this->selected_jobs[$job]->params[$name]->prompt; }
                  }
                  $this->show_html( '    <td valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">' . $name . '</font>&nbsp;</td>' );
                  $colspan = 4;
                }
                $this->show_html( '    <td valign="top" class="' . $this->style_td . '" colspan="' . $colspan . '">' . nl2br($this->to_html($value)) . '&nbsp;</td>' );
                $this->show_html( '  </tr>' );
              }
            }
          }
        }

        if ( $histories[$i]['error_text'] != '' ) {
          $this->show_html( '  <tr>' );
          $this->show_html( '    <td valign="top" class="' . $this->style_td . '" colspan="2">&nbsp;</td>' );
          $this->show_html( '    <td valign="top" class="' . $this->style_td_error . '">'.$this->get_translation('error').':&nbsp;' . $histories[$i]['error_code'] . '&nbsp;</td>' );
          $this->show_html( '    <td valign="top" class="' . $this->style_td_error . '" colspan="4">' . $this->to_html($histories[$i]['error_text']) . '&nbsp;</td>' );
          $this->show_html( '  </tr>' );
        }
      }

      $this->show_html( '  <tr>' );
      $this->show_html( '    <td valign="top" class="' . $this->style_td . '" colspan="7">' );
      if ( $this->scheduler_attributes['db'] != '' ) {
        $this->show_html( '    ' . $this->print_jobaction( 'job_history', $this->get_translation('newer_jobs').' ', $job, '', '', '', '', '&item=' . $histories[0]['id'] . '&range=' . $this->history_interval . '&task=' . $job ) . '&nbsp;&nbsp;' );
        $this->show_html( '    ' . $this->print_jobaction( 'job_history', $this->get_translation('older_jobs').' ', $job, '', '', '', '', '&item=' . $histories[$i-1]['id'] . '&range=-' . $this->history_interval . '&task=' . $job ) . '&nbsp;&nbsp;' );
        if ( $this->enable_history ) {        $this->show_html( '    ' . $this->print_jobaction( 'job_query', $this->get_translation('investigation').' ', $job, '', '', '', '', '&task=' . $job . '&scheduler_job=' . $job . '&scheduler_date_to=' . urlencode($this->to_datetime($histories[0]['start_time'], 'dd.mm.yy HH:MM:SS')) . '&scheduler_date_from=' . urlencode($this->to_datetime($histories[$i-1]['start_time'], 'dd.mm.yyyy HH:MM:SS') ) ) . '&nbsp;&nbsp;' ); }
      } else {
#       $this->show_html( '    ' . $this->print_jobaction( 'job_history', $this->get_translation('newer_jobs').' ', $job, '', '', '', '', '&range=' . $this->history_interval . '&task=' . $job ) . '&nbsp;&nbsp;' );
        $ok = isset($_REQUEST['range']);
        if ($ok) { $ok = ( $_REQUEST['range'] < 0 ); }
        if ($ok) {
          $history_range = ( ($_REQUEST['range']*-1)+$this->history_interval );
        } else {
          $history_range = $this->history_interval*2;
        }
        $this->show_html( '    ' . $this->print_jobaction( 'job_history', $this->get_translation('older_jobs').' ', $job, '', '', '', '', '&range=-' . $history_range . '&task=' . $job ) . '&nbsp;&nbsp;' );
      }
      $this->show_html( '  </tr>' );
      $this->show_answer_table_end();
    }
  }
  

  /**
  * Hilfedatei anzeigen
  *
  * @param    string   $help_file Hilfedatei im HTML-Format anzeigen
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_help( $help_file ) {

    $this->show_html('<table border="0" frame="box" rules="none" width="100%" cellspacing="0">' );
    $this->show_html('  <tr>' );
    $this->show_html('    <td width="1%"  align="left">&nbsp;</td>' );
    $this->show_html('    <td width="98%" align="right">' );
    $this->show_html('      <a class="spoolerLinkJobaction" href="javascript:{reset_history();}">'.$this->get_translation('back_to_startpage').'</a>&nbsp;&nbsp;&nbsp;<a class="spoolerLinkJobaction" href="#bottom" onClick="{inc_history();}">'.$this->get_translation('bottom_of_page').'</a>' );
    $this->show_html('    </td>' );
    $this->show_html('    <td width="1%">&nbsp;</td>' );
    $this->show_html('  </tr>' );
    $this->show_html('  <tr>' );
    $this->show_html('    <td width="1%" align="left">&nbsp;</td>' );
    $this->show_html('    <td width="98%" align="left">' );

    require( $help_file );

    $this->show_html('    </td>' );
    $this->show_html('    <td width="1%">&nbsp;</td>' );
    $this->show_html('  </tr>' );
    $this->show_html('  <tr>' );
    $this->show_html('    <td width="1%"  align="left">&nbsp;</td>' );
    $this->show_html('    <td width="98%" align="right">' );
    $this->show_html('      <a class="spoolerLinkJobaction" href="javascript:{reset_history();}">'.$this->get_translation('back_to_startpage').'</a>&nbsp;&nbsp;&nbsp;<a class="spoolerLinkJobaction" href="#top" onClick="{inc_history();}">'.$this->get_translation('top_of_page').'</a>' );
    $this->show_html('    <td width="1%" align="left">&nbsp;</td>' );
    $this->show_html('  </tr>' );
    $this->show_html('</table>' );
  }
    

  /**
  * Spooler-Antwort via TCP lesen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer() {

    $this->answer = ''; $s = ''; $i = 0;
    while ( !ereg("</spooler>", $s) && !ereg("<ok[/]?>", $s) ) {
      $s = fgets($this->sh, 1000);
      # echo $s;
      if (strlen($s) == 0) { break; }
      $this->answer .= $s;
      $s = substr($this->answer, strlen($this->answer)-20);

      if (substr($this->answer, -1) == chr(0)) {
        $this->answer = substr($this->answer, 0, -1);
        break;
      }
    }
    $this->answer = trim($this->answer);
    # echo $this->answer;
    #$this->get_answer_error();
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
    $dom   = domxml_open_mem($this->answer); 
    $xpath = $dom->xpath_new_context();

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
      $this->set_error( '' );
    }
    return $this->get_error();
  }


  /**
  * Scheduler-Antwort im XML-Format auslesen
  *
  * @param    String ignore_state
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_elements($ignore_state=0) {

    if ( !$ignore_state && !$this->state ) { return 0; }
  
    $this->img_scheduler_action = '<img src="' . $this->img_dir . $this->img_scheduler . '" border="0" hspace="4" vspace="2">';

    if ( !$ignore_state && $this->answer == '' ) {
      $this->state = 0;
      $msg  = $this->get_translation( 'scheduler_answer_error1' );
      $msg .= '<p><p>' . $this->print_action( 'spooler_abort', $this->get_translation( 'spooler_abort' ), $this->action_javascript ) . '<br>';
      $msg .= $this->print_action( 'spooler_abort_restart', $this->get_translation( 'spooler_abort_restart2' ), $this->action_javascript ) . '<br>';
      $msg .= $this->print_action( 'spooler_status', $this->get_translation( 'spooler_status2' ), '' );
      $this->show_error( $msg );
      return 0;
    }

    if ( $this->extension_domxml && !extension_loaded($this->extension_domxml) ) {
      $msg  = $this->get_translation( 'scheduler_answer_error2', 'extension_domxml='.$this->extension_domxml );
      $msg .= '<p><p><p>' . $this->print_action( 'spooler_status', $this->get_translation( 'spooler_status2' ), '' ) . '<p>';
      $this->show_error( $msg );
      return 0;
    }

    // Returns a DomDocument object 
    // $doc = xmldoc( $this->answer );
    if( $this->answer == "" ) { $this->set_error( $this->get_translation( 'scheduler_answer_error3' ) . ' XML-Content: ' . $this->answer); return 0; }   
    $doc = domxml_open_mem( $this->answer ); 
    if (!is_object($doc)) { $this->set_error( $this->get_translation( 'scheduler_answer_error3' ) . ' XML-Content: ' . $this->answer); return 0; }

    // Returns a DomNode object containing 
    // the root of the DOM document 
    $root       = $doc->root();  
    $answers    = $root->children();
    $states     = $answers[0]->children();

    for( $i=0; $i<count($states); $i++ ) {
      $state      = &$states[$i];

      $tagname = $state->tagname();
      /*
      if ( isset( $state->tagname ) ) {
        $tagname = $state->tagname;
      } else {
        $tagname = $state->name;
      }
      */

      $return = false;
      switch( $tagname ) {
        case 'history'    : $ok = $this->get_answer_history_elements( $state->children() );
                            $return = true;
                            break;
        case 'job_chains' : $ok = $this->get_answer_job_chains_elements( $state->children() );
                            $return = true;
                            break;
        #case 'task'       : $ok = $this->get_answer_task_elements( $state );
        #                    $return = true;
        #                    break;
      }
      if ( $return ) { return $ok; }

      $this->scheduler_attributes['state']                  = ''; 
      $this->scheduler_attributes['time']                   = ''; 
      $this->scheduler_attributes['spooler_running_since']  = ''; 
      $this->scheduler_attributes['cpu_time']               = ''; 
      $this->scheduler_attributes['log_file']               = '';
      $this->scheduler_attributes['db']                     = '';
      $this->scheduler_attributes['id']                     = '';
      $attributes = $state->attributes();

      for($j=0; $j<count($attributes); $j++) {
        if ( $attributes[$j]->name != '' ) { $this->scheduler_attributes[$attributes[$j]->name] = $state->get_attribute($attributes[$j]->name); }
      }

      $state_nodes      = $state->children();
      $jobs             = array();
      $threads          = array();
      $process_classes  = array();
      $this->scheduler_job_count                = 0;
      $this->scheduler_thread_count             = 0;
      $this->spooler_processes_classes_count  = 0;

      for( $j=0; $j<count($state_nodes); $j++ ) {
        $state_node      = &$state_nodes[$j];

        $tagname = $state_node->tagname();
        /*
        if ( isset( $state_node->tagname ) ) {
          $tagname = $state_node->tagname;
        } else {
          $tagname = $state_node->name;
        }
        */

        switch( $tagname ) {
          case 'jobs'             : $ok = $this->get_answer_jobs_elements( $state_node->children() );
                                    break;
          case 'threads'          : $ok = $this->get_answer_threads_elements( $state_node->children() );
                                    break;
          case 'process_classes'  : $ok = $this->get_answer_process_classes_elements( $state_node->children() );
                                    break;
          case 'remote_schedulers': $ok = $this->get_answer_remote_schedulers_elements( $state_node->children() );
                                    break;
          case 'job_chains'       : $ok = $this->get_answer_job_chains_elements( $state_node->children() );
                                    break;
        }

      }
      
    }
  }
  

  /**
  * ProcessClasses-Elemente aus Spooler-Antwort lesen
  *
  * @param    array $process_class Array der Threads mit Elementen im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_process_classes_elements( $process_classes ) {

    // echo "... count process_classes: " . count($process_classes) . "<br>";
    for($i=0; $i<count($process_classes); $i++) {
      $this->get_answer_process_class_elements( $process_classes[$i] );
    }
  }    


  /**
  * ProcessClass-Eigenschaften aus Spooler-Antwort lesen
  *
  * @param    array $process_class Array der ProcessClass-Elemente im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_process_class_elements( $process_class ) {

    $item_attributes = array();
    $item_attributes['name']            = '';
    $item_attributes['processes']       = '';
    $item_attributes['max_processes']   = '';

    $attributes = $process_class->attributes();
    // echo "... ... count process_class: " . count($attributes) . "<br>";

    for($i=0; $i<count($attributes); $i++) {
      if ( $attributes[$i]->name != '' ) { 
        $item_attributes[$attributes[$i]->name] = $process_class->get_attribute($attributes[$i]->name);
        // echo "... ... ... " . $attributes[$i]->name . " = " . $process_class->get_attribute($attributes[$i]->name) . "<br>";
      }
    }

    if ( $item_attributes['name'] != '' ) { 
      $this->scheduler_process_classes[$item_attributes['name']] = $item_attributes; 
      if ( $this->scheduler_process_class == '' ) { $this->scheduler_process_class = $item_attributes['name']; }
    }

    $this->scheduler_process_class_count = $i;
    return $this->scheduler_process_class_count;
  }


  /**
  * Thread-Elemente aus Scheduler-Antwort lesen
  *
  * @param    array $threads Array der Threads mit Elementen im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_threads_elements( $threads ) {

    for($i=0; $i<count($threads); $i++) {
      $this->get_answer_thread_elements( $threads[$i] );
    }
  }    


  /**
  * Thread-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @param    array $thread Array der Thread-Elemente im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_thread_elements( $thread ) {

    $item_attributes = array();
    $item_attributes['name']            = '';
    $item_attributes['sleeping_until']  = '';
    $item_attributes['running_tasks']   = '';
    $item_attributes['steps']           = '';
    $item_attributes['started_tasks']   = '';
    $item_attributes['priority']        = '';

    $attributes = $thread->attributes();
    // echo "... ... count thread: " . count($attributes) . "<br>";

    for($i=0; $i<count($attributes); $i++) {
      if ( $attributes[$i]->name != '' ) {
        $item_attributes[$attributes[$i]->name] = $thread->get_attribute($attributes[$i]->name);
        // echo "... ... ... " . $attributes[$i]->name . " = " . $thread->get_attribute($attributes[$i]->name) . "<br>";
      }
    }

    if ( $item_attributes['name'] != '' ) { 
      $this->scheduler_threads[$item_attributes['name']] = $item_attributes; 
      if ( $this->scheduler_thread == '' ) { $this->scheduler_thread = $item_attributes['name']; }
    }

    $this->scheduler_thread_count = $i;
    return $this->scheduler_thread_count;
  }


  /**
  * Job-Elemente aus Scheduler-Antwort lesen
  *
  * @param    array $jobs Array der Jobs mit Elementen im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_jobs_elements( $jobs ) {

    for($i=0; $i<count($jobs); $i++) {
      $this->get_answer_job_elements( $jobs[$i] );
    }
  }    


  /**
  * Job-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @param    array $job Array der Job-Elemente im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2003/09/06
  */

  function get_answer_job_elements( $job ) {

    $job_name = '';
    $item_attributes = array();
    $item_attributes['job']                 = '';
    $item_attributes['state']               = '';
    $item_attributes['title']               = '';
    $item_attributes['all_steps']           = 0;
    $item_attributes['state_text']          = '';
    $item_attributes['log_file']            = '';
    $item_attributes['order']               = '';
    $item_attributes['next_start_time']     = '';
    $item_attributes['order_queue_length']  = 0;
    $item_attributes['order_queue_orders']  = array();

    $attributes = $job->attributes();
    // echo "... count job: " . count($attributes) . "<br>";

    for($i=0; $i<count($attributes); $i++) {
      if ( $attributes[$i]->name != '' ) {
        $item_attributes[$attributes[$i]->name] = $job->get_attribute($attributes[$i]->name);
        // echo "... ... " . $attributes[$i]->name . " = " . $job->get_attribute($attributes[$i]->name) . "<br>";
      }
    }

    $job_children = $job->children();

    for($j=0; $j<count($job_children); $j++) {
      $job_node  = &$job_children[$j];
      
      $tagname = $job_node->tagname();
      /*
      if ( isset( $job_node->tagname ) ) {
        $tagname = $job_node->tagname;
      } else {
        $tagname = $job_node->name;
      }
      */

      switch( $tagname ) {
        case 'tasks'        : $this->get_answer_tasks_elements( $job_node->children(), $item_attributes['job'] );
                              break;

        case 'queued_tasks' : $this->get_answer_queued_tasks_elements( $job_node->children(), $item_attributes['job'] );
                              break;

        case 'order_queue'  : // echo "... " . "order_queue" . "<br>";
                              $attributes = $job_node->attributes();
                              for($k=0; $k<count($attributes); $k++) {
                                if ( $attributes[$k]->name != '' ) {
                                  $item_attributes['order_queue_' . $attributes[$k]->name] = $job_node->get_attribute($attributes[$k]->name);
                                  // echo "... ... " . 'order_queue_' . $attributes[$k]->name . " = " . $job_node->get_attribute($attributes[$k]->name) . "<br>";
                                }
                              }
                              break;
      }
    }

    if ( $item_attributes['job'] != '' ) {
      $this->scheduler_jobs[$item_attributes['job']] = $item_attributes;
      if ( !isset($this->scheduler_job_children[$item_attributes['job']]) ) { $this->scheduler_job_children[$item_attributes['job']] = $job->children(); }
    }

    $this->scheduler_jobs_count = $i;
    return $this->scheduler_jobs_count;
  }


  /**
  * Task-Elemente aus Scheduler-Antwort lesen
  *
  * @param    array  $tasks Array der Tasks mit Elementen im XML-Format
  * @param    string $job Name des Jobs
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_tasks_elements( $tasks, $job=null ) {

    for($i=0; $i<count($tasks); $i++) {
      $this->get_answer_task_elements( $tasks[$i], $job );
    }
  }    


  /**
  * Task-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @param    array  $task Node der Task-Attribute im XML-Format
  * @param    string $job Name des Jobs
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_task_elements( $task, $job ) {

    $item_attributes = array();
    $item_attributes['name']                = '';
    $item_attributes['id']                  = '';
    $item_attributes['state']               = '';
    $item_attributes['cause']               = '';
    $item_attributes['thread']              = '';
    $item_attributes['steps']               = 0;
    $item_attributes['log_file']            = '';
    $item_attributes['running_since']       = '';
    $item_attributes['idle_since']          = '';
    $item_attributes['in_process_since']    = '';

    $attributes = $task->attributes();

    for($i=0; $i<count($attributes); $i++) {
      if ( $attributes[$i]->name != '' )    {
        $item_attributes[$attributes[$i]->name] = $task->get_attribute($attributes[$i]->name);
        // echo "... ... ... " . $attributes[$i]->name . " = " . $task->get_attribute($attributes[$i]->name) . "<br>";
      }
    }

    if ( $job != '' ) {
      $this->scheduler_tasks[$job]['tasks'][] = $item_attributes;
      if ( !isset($this->scheduler_task_children[$job]) ) { $this->scheduler_task_children[$job] = $task->children(); }
    }
  }


  /**
  * Task-Elemente eingereihter Jobs aus Scheduler-Antwort lesen
  *
  * @param    array  $tasks Array der eingereihten Tasks mit Elementen im XML-Format
  * @param    string $job Name des Jobs
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_queued_tasks_elements( $tasks, $job=null ) {

    for($i=0; $i<count($tasks); $i++) {
      $this->get_answer_queued_task_elements( $tasks[$i], $job );
    }
  }    


  /**
  * Task-Eigenschaften eingereihter Jobs aus Scheduler-Antwort lesen
  *
  * @param    array  $task Node der Task-Attribute im XML-Format
  * @param    string $job Name des Jobs
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_queued_task_elements( $task, $job ) {

    $queued_tasks_index = 0;
    $item_attributes = array();
    $item_attributes['name']                = '';
    $item_attributes['id']                  = '';
    $item_attributes['enqueued']            = '';
    $item_attributes['start_at']            = '';
    $item_attributes['job']                 = '';

    $attributes = $task->attributes();

    for($i=0; $i<count($attributes); $i++) {
      if ( $attributes[$i]->name != '' )    {
        $item_attributes[$attributes[$i]->name] = $task->get_attribute($attributes[$i]->name);
        // echo "... ... ... " . $attributes[$i]->name . " = " . $task->get_attribute($attributes[$i]->name) . "<br>";
      }
    }

    if ( $job != '' ) {
      $this->scheduler_queued_tasks[$job]['tasks'][]['attributes'] = $item_attributes;
      $queued_tasks_index = count($this->scheduler_queued_tasks[$job]['tasks'])-1;
    }

    $task_children = $task->children();

    for($i=0; $i<count($task_children); $i++) {
      $task_node  = &$task_children[$i];
      
      $tagname = $task_node->tagname();
      /*
      if ( isset( $task_node->tagname ) ) {
        $tagname = $task_node->tagname;
      } else {
        $tagname = $task_node->name;
      }
      */

      if ( $tagname == 'params' ) {
        $param_children = $task_node->children();

        for($j=0; $j<count($param_children); $j++) {
          $param_node  = &$param_children[$j];
      
          $tagname = $param_node->tagname();
          /*
          if ( isset( $param_node->tagname ) ) {
            $tagname = $param_node->tagname;
          } else {
            $tagname = $param_node->name;
          }
          */

          if ( $tagname == 'param' ) {
            $item_attributes = array();
            $item_attributes['name']  = '';
            $item_attributes['value'] = '';

            $attributes = $param_node->attributes();

            for($k=0; $k<count($attributes); $k++) {
              if ( $attributes[$k]->name != '' )    {
                $item_attributes[$attributes[$k]->name] = $param_node->get_attribute($attributes[$k]->name);
                // echo "... ... ... " . $attributes[$k]->name . " = " . $param_node->get_attribute($attributes[$k]->name) . "<br>";
              }
            }

            if ( $job != '' ) {
            	if ( $item_attributes['name'] && isset($item_attributes['value']) ) {
                $this->scheduler_queued_tasks[$job]['tasks'][$queued_tasks_index]['parameters'][$item_attributes['name']] = $item_attributes['value'];
                // echo "... ... ... ... " . $item_attributes['name'] . " = " . $item_attributes['value'] . "<br>";
              }
            }

          }

        }
      }
    }

  }


  /**
  * Historien-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @param    array $histories Array der Historienelemente im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_history_elements( $histories ) {

    $job_cnts = array();

    for($i=0; $i<count($histories); $i++) {

      $job_name = '';
      $history_attributes = array();
      $history_attributes['id']         = 0;
      $history_attributes['job_name']   = '';
      $history_attributes['start_time'] = '';
      $history_attributes['end_time']   = '';
      $history_attributes['cause']      = '';
      $history_attributes['steps']      = 0;
      $history_attributes['log']        = '';
      $history_attributes['error']      = 0;
      $history_attributes['error_code'] = '';
      $history_attributes['error_text'] = '';

      $attributes = $histories[$i]->attributes();
      for($j=0; $j<count($attributes); $j++) {
        if ( $attributes[$j]->name != '' ) {
          if ( $attributes[$j]->name == 'job_name' ) { $job_name = $histories[$i]->get_attribute($attributes[$j]->name); }
          $history_attributes[$attributes[$j]->name] = $histories[$i]->get_attribute($attributes[$j]->name);
          // echo "... ... " . $history_attributes[$attributes[$j]->name] . " = " . $histories[$i]->get_attribute($attributes[$i]->name) . "<br>";
        }
      }

      $history_variable_attributes = array();
      $history_children = $histories[$i]->children();
      $var_cnt = 0;
      if ( count($history_children) > 0 ) {
        if ( is_object($history_children[0]) ) {
          $history_variables  = $history_children[0]->children(); 
          $history_variable_attributes = array();
          for($j=0; $j<count($history_variables); $j++) {
            if ( is_object($history_variables[$j]) ) {
              $variable_attributes = $history_variables[$j]->attributes();
              for($k=0; $k<count($variable_attributes); $k++) {
                if ( $variable_attributes[$k]->name == 'name' ) {
                  $history_variable_attributes[$var_cnt++][$history_variables[$j]->get_attribute('name')] = $history_variables[$j]->get_attribute('value');
                }
              }
            }
          }        
        }
      
        for( $j=0; $j<count($history_children); $j++ ) {
          if ( $history_children[$j]->tagname() == 'log' ) { $history_variable_attributes[$var_cnt++]['history_log'] = $history_children[$j]->get_content(); }
          /*
          if ( isset( $history_children[$j]->tagname ) ) {
          if ( $history_children[$j]->name ) {
            if ( $history_children[$j]->name == 'log' ) { $history_variable_attributes[$var_cnt++]['history_log'] = $history_children[$j]->get_content(); }
          } else {
            if ( $history_children[$j]->name == 'log' ) { $history_variable_attributes[$var_cnt++]['history_log'] = $history_children[$j]->content; }
          }
          */
        }
      }

      if ( $job_name != '' ) { 
        if ( !isset($job_cnts[$job_name]) ) { $job_cnts[$job_name] = 0; }
        $this->scheduler_history_variables[$job_name][$job_cnts[$job_name]]    = $history_variable_attributes;
        $this->scheduler_history_attributes[$job_name][$job_cnts[$job_name]++] = $history_attributes;
      }
    }

    return 1;
  }


  /**
  * Job-Ketten-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @param    array $job_chains Array der Job-Kettenelemente im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_answer_job_chains_elements( $job_chains ) {

    // echo "job_chains count: " . count($job_chains) . "<br>";
    for($i=0; $i<count($job_chains); $i++) {

      $job_chain_name           = '';
      $item_attributes          = array();
      $item_attributes['name']  = '';
      $attributes               = $job_chains[$i]->attributes();

      for($j=0; $j<count($attributes); $j++) {
        if ( $attributes[$j]->name != '' ) {
          $item_attributes[$attributes[$j]->name] = $job_chains[$i]->get_attribute($attributes[$j]->name);
          // echo "... ... " . $attributes[$j]->name . " = " . $job_chains[$i]->get_attribute($attributes[$j]->name) . "<br>";
        }
      }

      if ( $item_attributes['name'] != '' ) {
        $job_chain_name = $item_attributes['name'];
        // echo "... job_chain: " . $job_chain_name . "<br>"; 
      }

      $job_chain_nodes = $job_chains[$i]->children();
      // echo "... ... job_chain_nodes count: " . count($job_chain_nodes) . "<br>";
      for($j=0; $j<count($job_chain_nodes); $j++) {
      
        $job_chain_state = '';
        $item_attributes = array();
        $item_attributes['state']               = '';
        $item_attributes['next_state']          = '';
        $item_attributes['error_state']         = '';
        $item_attributes['job']                 = '';
        $item_attributes['order_queue_length']  = 0;
        $attributes = $job_chain_nodes[$j]->attributes();

        for($k=0; $k<count($attributes); $k++) {
          if ( $attributes[$k]->name != '' ) {
            $item_attributes[$attributes[$k]->name] = $job_chain_nodes[$j]->get_attribute($attributes[$k]->name);
            // echo "... ... " . $attributes[$k]->name . " = " . $job_chain_nodes[$j]->get_attribute($attributes[$k]->name) . "<br>";
          }
        }

        $job_chain_node_elements = $job_chain_nodes[$j]->children();
        for($l=0; $l<count($job_chain_node_elements); $l++) {
        
          $tagname = $job_chain_node_elements[$l]->tagname();
          /*
          if ( isset( $job_chain_node_elements[$l]->tagname ) ) {
            $tagname = $job_chain_node_elements[$l]->tagname;
          } else {
            $tagname = $job_chain_node_elements[$l]->name;
          }
          */

          switch( $tagname ) {
            case 'task' :
                          $this->get_answer_task_elements( $job_chain_node_elements[$l] );
                          break;
          }
        }

        if ( isset($this->scheduler_tasks[$item_attributes['job']]['order_queue_length']) ) { $item_attributes['order_queue_length'] = $this->scheduler_tasks[$item_attributes['job']]['order_queue_length']; }
        if ( $job_chain_name != '' && $item_attributes['state'] != '' ) {
          $job_chain_state = $item_attributes['state'];
          $this->scheduler_job_chains[$job_chain_name][$job_chain_state] = $item_attributes;
        }

      }
    }

    return 1;
  }




  /**
  * Job-Ketten-Eigenschaften aus Scheduler-Antwort lesen
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/06/27
  */

  function get_job_chains() {

    if (!$this->scheduler_job_chains) {
      fputs( $this->sh, '<?xml version="1.0" encoding="iso-8859-1"?><show_state what="job_chains"/>');
      $this->get_answer();
      if ($this->error()) return 0;
      $this->get_answer_elements(1);
    }
    return $this->scheduler_job_chains;
  }


  /**
  * Eigenschaften registrierter Scheduler aus Scheduler-Antwort lesen
  *
  * @param    array $remote_schedulers Array der Elemente registrierter Scheduler im XML-Format
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/04/20
  */

  function get_answer_remote_schedulers_elements( $remote_schedulers ) {

    // echo "remote_scheduler count: " . count($remote_schedulers) . "<br>";
    for($i=0; $i<count($remote_schedulers); $i++) {

      $remote_scheduler_name              = '';
      $item_attributes                    = array();
      $item_attributes['ip']              = '';
      $item_attributes['hostname']        = '';
      $item_attributes['tcp_port']        = '';
      $item_attributes['scheduler_id']    = '';
      $item_attributes['version']         = '';
      $item_attributes['connected']       = '';
      $item_attributes['connected_at']    = '';
      $item_attributes['disconnected_at'] = '';
      $attributes = $remote_schedulers[$i]->attributes();

      for($j=0; $j<count($attributes); $j++) {
        if ( $attributes[$j]->name != '' ) {
          $item_attributes[$attributes[$j]->name] = $remote_schedulers[$i]->get_attribute($attributes[$j]->name);
          // echo "... ... " . $attributes[$j]->name . " = " . $remote_schedulers[$i]->get_attribute($attributes[$j]->name) . "<br>";
        }
      }

      if ( $item_attributes['ip'] != '' ) {
        $remote_scheduler_name = $item_attributes['ip'] . ':' . $item_attributes['tcp_port'];
        // echo "... remote_scheduler: " . $remote_scheduler_name . "<br>"; 
        $this->remote_schedulers[$remote_scheduler_name] = $item_attributes;
      }
      
    }

    return 1;
  }


  /**
  * Eigenschaften registrierter Scheduler als Info-Array liefern
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/04/20
  */

  function get_info_remote_schedulers() {
    
    return $this->remote_schedulers;
  }


  /**
  * Scheduler-Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_spooler( $make_table=1 ) {

    if ( !$this->state ) { return 0; }

    $this->img_scheduler_action = '<img src="' . $this->img_dir . $this->img_scheduler . '" border="0" hspace="4" vspace="2">';
    $this->img_top_action       = '<img src="' . $this->img_dir . $this->img_top     . '" border="0" hspace="4" vspace="2">';
    $this->img_bottom_action    = '<img src="' . $this->img_dir . $this->img_bottom  . '" border="0" hspace="4" vspace="2">';
    /*
    $found = ( $this->log_dir != '' );
    if ( $found ) {
      if ( substr(strrev($this->log_dir), 0, 1) != '/' ) { $this->log_dir .= ''; }
      $found = strrpos( $this->scheduler_attributes['log_file'], '/' );
      if ( $found > 0 ) {
        $log_file = '<a href="' . $this->log_dir . substr($this->scheduler_attributes['log_file'], $found+1) . '" class="spoolerLinkAction" target="_blank">' . $this->img_scheduler_action . $this->scheduler_attributes['log_file'] . '</a>'; 
      }
    }
    if ( !$found ) { $log_file = $this->scheduler_attributes['log_file']; }
    */
    $log_file = '<a href="javascript:show_log(\'http://' . $this->host . ':' . $this->port . '/show_log?\');" class="spoolerLinkAction">' . $this->img_scheduler_action . $this->scheduler_attributes['log_file'] . '</a>';
    
    if ( $make_table ) { $this->show_answer_table_begin(); }

    $this->show_html('        <tr valign="middle">');
    $this->show_html('          <th width="2%"  class="' . $this->style_th . '" align="left"><a href="#select">' . $this->img_top_action . '</a></th>');
    $this->show_html('          <th colspan="2" class="' . $this->style_th . '" align="center"><a name="spooler">'.$this->get_translation( 'scheduler' ).'</a></th>');
    $this->show_html('          <th width="2%"  class="' . $this->style_th . '" align="right"><a href="#thread0">' . $this->img_bottom_action . '</a></th>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr valign="top">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'scheduler_id' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->scheduler_attributes['id'] . '&nbsp;</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'time' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->to_datetime($this->scheduler_attributes['time']) . '&nbsp;</td>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'host' ).' / '.$this->get_translation( 'port' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->host . ' / ' . $this->port . '&nbsp;</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'scheduler_running_since' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->to_datetime($this->scheduler_attributes['spooler_running_since']) . '&nbsp;</td>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'spooler_status' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->get_state($this->scheduler_attributes['state']) . '&nbsp;</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'cpu_time' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_msg    . '">' . $this->scheduler_attributes['cpu_time'] . '&nbsp;</td>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'protocol' ).'</td>');
    $this->show_html('          <td colspan="3" class="' . $this->style_td        . '">' . $log_file . '</td>');
    $this->show_html('        </tr>');
    
    if ( $this->enable_history && $this->scheduler_attributes['db'] != '' ) {
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'history' ).'</td>');
      $this->show_html('          <td colspan="3" class="' . $this->style_td        . '">' . $this->print_action( 'job_query', $this->to_save_display($this->scheduler_attributes['db']), '', '', '', '_blank', '&scheduler_job=(Scheduler)&scheduler_date_from=' . urlencode($this->to_datetime($this->scheduler_attributes['spooler_running_since']) ) ) . '</td>');
      $this->show_html('        </tr>');
    }
    
    if ( $this->enable_job_chains ) {
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'job_chains' ).'</td>');
      $this->show_html('          <td colspan="3" class="' . $this->style_td        . '">' );
      $this->show_html( $this->print_action( 'job_chains', $this->get_translation( 'efficiency' ) ) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' );
      $this->show_html( '         </td>');
      $this->show_html('        </tr>');
    }

    if ( $this->scheduler_queued_tasks ) {
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'queue' ).'</td>');
      $this->show_html('          <td colspan="3" class="' . $this->style_td        . '"><table>' );
      foreach( $this->scheduler_queued_tasks as $job => $queued_tasks) {
        for($i=0; $i<count($queued_tasks['tasks']); $i++) {
          if ( $queued_tasks['tasks'][$i]['attributes']['id'] != '' ) {
            $this->show_html('        <tr><td class="' . $this->style_td_action . '">' );
            //$this->show_html('            ' . $this->print_action( 'kill_task', $this->get_translation( 'kill_task' ), $this->jobaction_javascript, '', '', '', '&job=' . $job . '&item=' . $queued_tasks['tasks'][$i]['attributes']['id'] ) . '&nbsp;&nbsp;');
            $this->show_html('            ' . $this->print_action( 'job_queue_task', $this->get_translation( 'job_queue_task' ), '', '', '', '', '&job=' . $job . '&item=' . $queued_tasks['tasks'][$i]['attributes']['id'].'&item_index='.$i.'&job_location='.$this->scheduler_job_count) . '&nbsp;&nbsp;');
            $this->show_html( $this->get_translation( 'queued_tasks_per_job', 'queued_tasks='.$queued_tasks['tasks'][$i]['attributes']['name'].'&job='.$job ).'&nbsp;&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation( 'enqueued' ).' ' . $this->to_datetime($queued_tasks['tasks'][$i]['attributes']['enqueued']) . '&nbsp;&nbsp;</td>');
            if ( $queued_tasks['tasks'][$i]['attributes']['start_at'] != '' ) {
              $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation( 'start_at' ).' ' .  $this->to_datetime($queued_tasks['tasks'][$i]['attributes']['start_at']) . '&nbsp;&nbsp;</td>');
            } else {
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;&nbsp;</td>');
            }
            $this->show_html('        </td></tr>');
          }
        }
      }
      $this->show_html( '         </table></td>');
      $this->show_html('        </tr>');
    }
    
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'action' ).'</td>');
    $this->show_html('          <td colspan="3" class="' . $this->style_td_action . '">');
    $this->show_html('            <table width="100%"><tr>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_status',            $this->get_translation( 'spooler_status' ),                                  '') . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_pause',             $this->get_translation( 'spooler_pause' ),            $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_continue',          $this->get_translation( 'spooler_continue' ),         $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_stop',              $this->get_translation( 'spooler_stop' ),             $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_restart',           $this->get_translation( 'spooler_restart' ),          $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('            </tr><tr>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_init',              $this->get_translation( 'spooler_init' ),             $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_terminate',         $this->get_translation( 'spooler_terminate' ),        $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_terminate_restart', $this->get_translation( 'spooler_terminate_restart' ),$this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_abort',             $this->get_translation( 'spooler_abort' ),            $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('              <td>' . $this->print_action( 'spooler_abort_restart',     $this->get_translation( 'spooler_abort_restart' ),    $this->action_javascript ) . '&nbsp;</td>');
    $this->show_html('            </tr></table>');
    $this->show_html('          </td>');
    $this->show_html('        </tr>');

    if ( $make_table ) { $this->show_answer_table_end(); }
    return 1;
  }


  /**
  * Alle Job-Chains mit Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    string  $task Name des Jobs, falls nur einer angezeigt werden soll
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/10/13
  */

  function show_job_chains( $chain='' ) {
  
    $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
    $this->img_top_action     = '<img src="' . $this->img_dir . $this->img_top     . '" border="0" hspace="4" vspace="2">';
    $this->img_bottom_action  = '<img src="' . $this->img_dir . $this->img_bottom  . '" border="0" hspace="4" vspace="2">';
    
    foreach( $this->scheduler_job_chains as $name => $states ) {

      $this->show_answer_table_begin();
      $this->show_html( '<tr>' );
      $this->show_html( '  <th colspan="5" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'job_chain' ).': ' . $name . '</th>' );
      $this->show_html( '</tr>' );

      if ( $chain == '' || $chain == $name ) { $this->show_answer_job_chain( 0, $name ); }
      $this->show_answer_table_end();
    }

  }
  

  /**
  * Scheduler-Eigenschaften registrierter Scheduler in Web-Oberfläche anzeigen
  *
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/04/19
  */

  function show_answer_remote_schedulers( $make_table=1 ) {

    if ( !$this->state ) { return 0; }

    $this->img_scheduler_action = '<img src="' . $this->img_dir . $this->img_scheduler . '" border="0" hspace="4" vspace="2">';
    $this->img_top_action       = '<img src="' . $this->img_dir . $this->img_top     . '" border="0" hspace="4" vspace="2">';
    $this->img_bottom_action    = '<img src="' . $this->img_dir . $this->img_bottom  . '" border="0" hspace="4" vspace="2">';


    if ( $make_table ) { $this->show_html('<br>'); $this->show_answer_table_begin(); }

    $this->show_html('        <tr valign="middle">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label . '" align="left"><a name="remoteschedulers">'.$this->get_translation( 'remote_schedulers' ).'</a></th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'host' ).'</th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'ip' ).'</th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'port' ).'</th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left" nowrap>'.$this->get_translation( 'scheduler_id' ).'</th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'connected' ).'</th>');
    $this->show_html('          <td width="10%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'since' ).'</th>');
    $this->show_html('          <td width="15%" class="' . $this->style_td_label . '" align="left">'.$this->get_translation( 'version' ).'</th>');
    $this->show_html('        </tr>');

    foreach($this->remote_schedulers as $key => $value) {
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
      $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '"><a href="http://' . $value['hostname'] . ':' . $value['tcp_port'] . '" target="_blank" class="' . $this->style_scheduler_action . '">' . $this->img_scheduler_action . $value['hostname'] . '</a>&nbsp;</td>');
      $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $value['ip'] . '&nbsp;</td>');
      $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $value['tcp_port'] . '&nbsp;</td>');
      $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $value['scheduler_id'] . '&nbsp;</td>');
      $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $value['connected'] . '&nbsp;</td>');
      if ( $value['connected'] == 'yes' ) {
        $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $this->to_datetime($value['connected_at']) . '&nbsp;</td>');
      } else {
        $this->show_html('          <td width="10%" class="' . $this->style_td_msg  . '">' . $this->to_datetime($value['disconnected_at']) . '&nbsp;</td>');
      }
      $this->show_html('          <td width="15%" class="' . $this->style_td_msg  . '">' . $value['version'] . '&nbsp;</td>');
      $this->show_html('        </tr>');
    }

    if ( $make_table ) { $this->show_answer_table_end(); }
    return 1;
  }


  /**
  * Job-Chain-Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    integer $make_table Tabellenkopf erzeugen
  * @param    integer $thread_count Lfd. Nr. des Threads
  * @param    array   $thread_attributes XML-Objekt der Thread-Attribute
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_job_chain( $make_table=1, $job_chain, $job_chain_attributes=array() ) {

    if ( $make_table ) { $this->show_answer_table_begin(); }

    $this->show_html('  <tr>' );
    $this->show_html('    <th width="30%" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'job' ).'</th>');
    $this->show_html('    <th width="10%" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'order_queue_length' ).'</th>');
    $this->show_html('    <th width="20%" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'entry_state' ).'</th>');
    $this->show_html('    <th width="20%" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'exit_state' ).'</th>');
    $this->show_html('    <th width="20%" class="' . $this->style_th_sub . '" align="left">'.$this->get_translation( 'error_state' ).'</th>');
    $this->show_html('  </tr>' );

    ksort($this->scheduler_job_chains[$job_chain]);
    reset($this->scheduler_job_chains[$job_chain]);
    
    foreach( $this->scheduler_job_chains[$job_chain] as $name => $attributes ) {
      $this->show_html('  <tr>' );
      if ( $attributes['job'] != '' ) {
        $this->show_html('    <td width="30%" class="' . $this->style_td        . '">' . $this->print_jobaction( 'job_orders&task=' . $attributes['job'], $attributes['job'], $attributes['job'], $this->jobaction_javascript, '', '', '' ) . '&nbsp;</td>' );
        #$item = ( isset($this->scheduler_tasks[$attributes['job']]['order_queue_length']) ) ? $this->scheduler_tasks[$attributes['job']]['order_queue_length'] : 0 ;
        $this->show_html('    <td width="10%" class="' . $this->style_td        . '">' . $attributes['order_queue_length'] . '&nbsp;</td>');
        $this->show_html('    <td width="20%" class="' . $this->style_td        . '">' . $attributes['state'] . '&nbsp;</td>');
        $this->show_html('    <td width="20%" class="' . $this->style_td        . '">' . $attributes['next_state'] . '&nbsp;</td>');
        $this->show_html('    <td width="20%" class="' . $this->style_td        . '">' . $attributes['error_state'] . '&nbsp;</td>');
        $this->show_html('  </tr>' );
      }
    }
    
    if ( $make_table ) { $this->show_answer_table_end(); }

  }    


  /**
  * Thread-Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    integer $make_table Tabellenkopf erzeugen
  * @param    integer $thread_count Lfd. Nr. des Threads
  * @param    array   $thread_attributes XML-Objekt der Thread-Attribute
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_thread( $make_table=1, $thread_count=0, $thread_attributes=array() ) {

    if ( $make_table ) { $this->show_answer_table_begin(); }

    if ( $thread_count == 0 ) {
      $tmp_href = '#spooler';
    } else {
      $tmp_href = '#thread' . ($thread_count-1);
    }
    
    $this->show_html('          <th width="2%"  class="' . $this->style_th . '" align="left"><a href="' . $tmp_href . '">' . $this->img_top_action . '</a></th>');
    $this->show_html('          <th colspan="2" class="' . $this->style_th . '" align="center"><a name="thread' . $thread_count . '">'.$this->get_translation( 'thread' ).'</a> ' . $thread_attributes['name'] . '</th>');
    $this->show_html('          <th width="2%"  class="' . $this->style_th . '" align="right"><a href="#thread' . ($thread_count+1) . '">' . $this->img_bottom_action . '</a></th>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr valign="top">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'sleeping_until' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">' . $this->to_datetime($thread_attributes['sleeping_until']) . '&nbsp;</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'running_tasks' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">' . $thread_attributes['running_tasks'] . '&nbsp;</td>');
    $this->show_html('        </tr>');
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'calls' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">' . $thread_attributes['steps'] . '&nbsp;</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">'.$this->get_translation( 'started_tasks' ).'</td>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">' . $thread_attributes['started_tasks'] . '&nbsp;</td>');
    $this->show_html('        </tr>');

    $this->show_html('        <tr valign="middle">');
    $this->show_html('          <th width="25%" class="' . $this->style_th_task . '">'.$this->get_translation( 'task' ).'</th>');
    $this->show_html('          <th width="25%" class="' . $this->style_th_task . '">'.$this->get_translation( 'spooler_status' ).'</th>');
    $this->show_html('          <th width="25%" class="' . $this->style_th_task . '">'.$this->get_translation( 'steps' ).'</th>');
    $this->show_html('          <th width="25%" class="' . $this->style_th_task . '">'.$this->get_translation( 'start_at' ).'</th>');
    $this->show_html('        </tr>');

    if ( $make_table ) { $this->show_answer_table_end(); }
     
  }


  /**
  * Job-Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    string  $job Name des Jobs
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_job_info( $job, $make_table=1 ) {

    $job_attributes = $this->scheduler_jobs[$job];
    $children       = ( isset($this->scheduler_job_children[$job]) ) ? $this->scheduler_job_children[$job] : array();
    $child_cnt      = 0;  
    $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
    $job_checks     = array();
    $job_check_cnt  = 0;

    $this->show_html('<a name="job' . ++$this->scheduler_job_count . '"/><form name="sos_spooler_form_' . $job . '" action="' . $this->site . $this->site_con . 'action=spooler_process&spooler_host=' . $this->host . '&spooler_port=' . $this->port . '" method="post" onSubmit="return sos_spooler_form_' . $job . '_onSubmit();">');
    if ( $make_table ) { $this->show_answer_table_begin(); }

    if ( strtolower($job_attributes['state']) == 'pending' && $job_attributes['next_start_time'] == '' ) { $job_attributes['state'] = 'none'; }
    $this->show_html('        <tr valign="top">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">' . $job_attributes['job'] . '&nbsp;</td>');
    if ( $job_attributes['title'] != '' ) {
      $this->show_html('          <td width="75%" colspan="3" class="' . $this->style_td_label . '">' );

      $ok = ( $children[$child_cnt]->tagname() == 'description' && !$this->enable_description );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'description' && !$this->enable_description );
      } else {
        $ok = ( $children[$child_cnt]->name == 'description' && !$this->enable_description );
      }
      */
      if ($ok) {
        $this->show_html( $this->print_jobaction( 'job_description', $this->get_translation('info'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '', '&task=' . $job ) . '&nbsp;&nbsp;');
      }
      $this->show_html( $this->to_html($job_attributes['title']) . '&nbsp;' );
      $this->show_html('          </td>');
      $this->show_html('        </tr><tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
    }  

    $ok      = ( $children[$child_cnt]->tagname() == 'description' );
    if ($ok) { $content_desc = $children[$child_cnt]->get_content(); }
    /*
    if ( isset($children[$child_cnt]->tagname) ) {
      $ok      = ( $children[$child_cnt]->tagname == 'description' );
      if ($ok) { $content_desc = $children[$child_cnt]->get_content(); }
    } else {
      $ok      = ( $children[$child_cnt]->name == 'description' );
      if ($ok) { $content_desc = $children[$child_cnt]->content; }
    }
    */
    if ($ok) {
      $ok_desc = ( $this->enable_description && $content_desc != '' );
      if ( $ok_desc ) {      
        $this->show_html('          <td width="75%" colspan="3" class="' . $this->style_td_label . '">' . $this->to_html($content_desc) . '&nbsp;' );
        $this->show_html(            '&nbsp;</td>');
        $this->show_html('      </tr><tr>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
      }
      if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
    }

    $this->show_html('          <td width="25%" class="' . $this->style_td_action . '"><table border="0" cellPadding="0" cellSpacing="0" bgcolor="#FFFFFF" width="100%"><tr><td valign="top" class="' . $this->style_td_action . '">');
#    $ok = ( !isset($_REQUEST['task']) );
#   if (!$ok) { $ok = ( $_REQUEST['task'] != $job ); }
    $ok = true;
    if ($ok) {
      $this->show_html( $this->print_jobaction( 'spooler_status', $this->get_translation('job'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '_blank', '&task=' . $job ), '&nbsp;&nbsp;' );
      $job_label = '&nbsp;';
    } else {
      $job_label = $this->get_translation('job').'&nbsp;';
    }

    $this->show_html($this->get_translation('spooler_status').':&nbsp;</td><td valign="top"><font class="'    . $this->style_font_msg . '">' . $this->get_task_state($job_attributes['state']) . '</font></td></tr></table></td>');
    if ( $job_attributes['order_queue_length'] > 0 ) {
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('orders').':&nbsp;<font class="'  . $this->style_font_msg . '">'  . $job_attributes['order_queue_length'] . '</font>&nbsp;&nbsp;&nbsp;'.$this->get_translation('settled').':&nbsp;<font class="' . $this->style_font_msg . '">' . $job_attributes['all_steps'] . '</font>&nbsp;</td>');
    } else {
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('steps').':&nbsp;'.$this->get_translation('settled').':&nbsp;<font class="' . $this->style_font_msg . '">' . $job_attributes['all_steps'] . '</font>&nbsp;</td>');
    }
    $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('start_at').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_attributes['next_start_time']) . '</font>&nbsp;</td>');
    $this->show_html('        </tr>');


    if (isset($this->scheduler_tasks[$job]['tasks'])) {
      foreach( $this->scheduler_tasks[$job]['tasks'] as $job_task_index => $job_task ) {
        /*
        $found = ( $this->log_dir != '' );
        if ( $found ) {
          if ( substr(strrev($this->log_dir), 0, 1) != '/' ) { $this->log_dir .= ''; }
          $found = strrpos( $job_task['log_file'], '/' );
          if ( $found > 0 ) {
            $log_link = '<a href="' . $this->log_dir . substr($job_task['log_file'], $found+1) . '" class="spoolerLinkJobaction" target="_blank">' . $this->img_job_action . $this->get_translation('task') . '</a>'; 
          }
        }
        if ( !$found ) { $log_link = $this->get_translation('task'); }
        */
        $log_link = '<a href="javascript:show_log(\'http://' . $this->host . ':' . $this->port . '/show_log?task='. $job_task['id'] . '\');" class="spoolerLinkJobaction">' . $this->img_job_action . $this->get_translation('task') . '</a>'; 
    
        $this->show_html('        <tr>');
        $this->show_html('          <td width="25%" class="' . $this->style_td . '">&nbsp;</td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '"><table border="0" cellPadding="2" cellSpacing="0" bgcolor="#FFFFFF" width="100%"><tr><td valign="top" class="' . $this->style_td_action . '">');
        $this->show_html(             $log_link . '&nbsp;' . $job_task['id'] . ':&nbsp;</td><td valign="top"><font class="' . $this->style_font_msg . '">' . $this->get_task_state($job_task['state']) . '</font></td></tr>');
        $this->show_html(             '<tr><td valign="top">' . $this->print_jobaction( 'kill_task', $this->get_translation('spooler_terminate'), $job, '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $job_task['id'] ) . '</td><td>' . $this->print_jobaction( 'kill_task_immediately', $this->get_translation('spooler_abort'), $job, '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $job_task['id'] ) . '</td></tr>' );
        $this->show_html('          </table></td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '" valign="top">'.$this->get_translation('steps').':&nbsp;<font class="'  . $this->style_font_msg . '">'  . $job_task['steps'] . '</font>&nbsp;&nbsp;&nbsp;'.$this->get_translation('since').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_task['running_since']) . '</font>&nbsp;</td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '" valign="top">'.$this->get_translation('inactive').' '.$this->get_translation('since').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_task['idle_since']) . '</font>&nbsp;</td>');
        $this->show_html('        </tr>');
      }
    }

    /*
    $found = ( $this->log_dir != '' );
    if ( $found ) {
      if ( substr(strrev($this->log_dir), 0, 1) != '/' ) { $this->log_dir .= ''; }
      $found = strrpos( $job_attributes['log_file'], '/' );
      if ( $found > 0 ) {
        $log_link = '<a href="' . $this->log_dir . str_replace($this->get_translation('job').'.', $this->get_translation('task').'.', substr($job_attributes['log_file'], $found+1)) . '" class="spoolerLinkJobaction" target="_blank">' . $this->img_job_action . $this->get_translation('protocol') . '</a>'; 
      }
    }
    if ( !$found ) { $log_link = ''; }
    if ( !$found ) { $found    = ( $log_link != '' ); }
    */
    $log_link = '<a href="javascript:show_log(\'http://' . $this->host . ':' . $this->port . '/show_log?job=' . $job . '\');" class="spoolerLinkJobaction">' . $this->img_job_action . $this->get_translation('protocol') . '</a>'; 
    
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">&nbsp;</td>');
    $this->show_html('          <td class="' . $this->style_td_label . '">' );
    $this->show_html( $this->print_jobaction( 'job_info', $this->get_translation('spooler_status'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '', '&task=' . $job ), '&nbsp;&nbsp;' );
    $this->show_html( $this->print_jobaction( 'job_history', $this->get_translation('history'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '_blank', '&task=' . $job ) . '&nbsp;&nbsp;' .  $log_link . '&nbsp;&nbsp;</td>');
    $this->show_html('          <td colspan="2" class="' . $this->style_td_msg . '">' . $this->to_html($job_attributes['state_text']) . '&nbsp;</td>');
    $this->show_html('        </tr>');


    for($child_cnt=0; $child_cnt<count($children); $child_cnt++) {

      $ok = ( $children[$child_cnt]->tagname() == 'queued_tasks' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'queued_tasks' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'queued_tasks' );
      }
      */

      if ($ok) {
        $queue_attributes['enqueued'] = '';
        $queue_attributes['name']     = $this->get_translation('job');
        $queue_attributes['start_at'] = '';
        $queue_attributes['id']       = '';
          
        $queues = $children[$child_cnt]->children();
        for($i=0; $i<count($queues);$i++) {
          $attributes = $queues[$i]->attributes();
          $found = 0;
          for($j=0; $j<count($attributes); $j++) {
            if ( $attributes[$j]->name != '' )    {
              $found = 1;
              $queue_attributes[$attributes[$j]->name] = $queues[$i]->get_attribute($attributes[$j]->name);
            }
          }        
          if ( $found ) {
            $this->show_html('        <tr valign="top">');
            $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">' );
            if ( $queue_attributes['id'] != '' ) {
              //$this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'kill_task',        $this->get_translation('kill_task'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count, '', '', '&item=' . $queue_attributes['id'] ) . '&nbsp;&nbsp;');
              $this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_queue_task',   $this->get_translation( 'job_queue_task' ),        $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $queue_attributes['id'].'&item_index='.$i.'&job_location='.$this->scheduler_job_count) . '&nbsp;&nbsp;');
            }
            $this->show_html($this->get_translation('from_queued_tasks') . $queue_attributes['name'] . '&nbsp;&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation('enqueued').' '. $this->to_datetime($queue_attributes['enqueued']) . '&nbsp;&nbsp;</td>');
            if ( $queue_attributes['start_at'] != '' ) {
              $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation('start_at').' ' .  $this->to_datetime($queue_attributes['start_at']) . '&nbsp;&nbsp;</td>');
            } else {
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;&nbsp;</td>');
            }
            $this->show_html('        </tr>');
          }
        }
        # if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
      }

      $ok = ( $children[$child_cnt]->tagname() == 'ERROR' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'ERROR' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'ERROR' );
      }
      */

      if ($ok) {
        $error_attributes['time']   = '';
        $error_attributes['class']  = '';
        $error_attributes['code']   = '';
        $error_attributes['text']   = '';
        $error_attributes['source'] = '';
        $error_attributes['line']   = '';
        $error_attributes['col']    = '';
        $attributes = $children[$child_cnt]->attributes();

        for($err_cnt=0; $err_cnt<count($attributes); $err_cnt++) {
          if ( $attributes[$err_cnt]->name != '' )    {
            $error_attributes[$attributes[$err_cnt]->name] = $children[$child_cnt]->get_attribute($attributes[$err_cnt]->name); 
          }
        }
        if ( $error_attributes['source'] != '' ) {
          $error_source = ' ['.$this->get_translation('file').' ' . $error_attributes['source'] . ', '.$this->get_translation('line').' ' . $error_attributes['line'] . ', '.$this->get_translation('column').' ' . $error_attributes['col'] . ']';
        } else {
          $error_source = '';
        }

        $this->show_html('        <tr valign="top">');
        $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
        $this->show_html('          <td colspan="3" class="' . $this->style_td_error . '">');
        $this->show_html('            '.$this->get_translation('error_time').' ' . $this->to_datetime($error_attributes['time']) . ' '.$this->get_translation('clock').' [' . $error_attributes['code'] . ' ' . $error_attributes['class'] . ']: ' . $this->to_html($error_attributes['text']) . $error_source );
        $this->show_html('          </td>');
        $this->show_html('        </tr>');
      }          

      $ok = ( $children[$child_cnt]->tagname() == 'order_queue' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'order_queue' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'order_queue' );
      }
      */

      if ($ok && $this->enable_job_orders) {
        $orders = $children[$child_cnt]->children();
        for($i=1; $i<count($orders); $i++) {
          $order_id = '';
          $order_attributes = array();
          $order_attributes['id']               = 0;
          $order_attributes['title']            = '';
          $order_attributes['job_chain']        = '';
          $order_attributes['job']              = '';
          $order_attributes['state']            = '';
          $order_attributes['state_text']       = '';
          $order_attributes['priority']         = '';
          $order_attributes['created']          = '';
          $order_attributes['in_process_since'] = '';

          $found = 0;
          $attributes = $orders[$i]->attributes();
          for($j=0; $j<count($attributes); $j++) {
            if ( $attributes[$j]->name != '' ) {
              $found = 1;
              $order_attributes[$attributes[$j]->name] = $orders[$i]->get_attribute($attributes[$j]->name);
            }
          }
          if ($found) {
            $this->show_html('        <tr valign="top">');
            $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">' );
            if ( $order_attributes['id'] != "" ) {
              #$this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'order_priority',        $this->get_translation('order'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count, '', '', '&item=' . $order_attributes['id'] ) . '&nbsp;');
              $this->show_html(' '.$this->get_translation('order').'&nbsp;' );
            }
            $this->show_html( $order_attributes['id'] . '&nbsp;&nbsp;&nbsp;'.$this->get_translation('spooler_status').' ' . $order_attributes['state'] . '&nbsp;&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '"><nobr>'.$this->get_translation('enqueued').' ' . $this->to_datetime($order_attributes['created']) . '&nbsp;&nbsp;</nobr></td>');
            if ( $order_attributes['in_process_since'] != '' ) {
              $this->show_html('          <td class="' . $this->style_td_action . '"><nobr>'.$this->get_translation('start_at').' ' .  $this->to_datetime($order_attributes['in_process_since']) . '</nobr>&nbsp;&nbsp;</td>');
            } else {
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;&nbsp;</td>');
            }
            $this->show_html('        </tr>');
            if ( $order_attributes['title'] . $order_attributes['state_text'] != "" ) {
              $this->show_html('        <tr valign="top">');
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
              $this->show_html('          <td class="' . $this->style_td_action . '">' . $order_attributes['state_text'] . '</td>' );
              $this->show_html('          <td class="' . $this->style_td_action . '" colspan="2">' . $order_attributes['title'] . '</td>' );
              $this->show_html('        </tr>');
            }
          }
        }
        # if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
      }

    }

    if ( $make_table )         { $this->show_answer_table_end(); }
    $this->show_html('</form>');
  }


  /**
  * Job-Funktionen in Web-Oberfläche anzeigen
  *
  * @param    string  $job Name des Jobs
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_job( $job, $make_table=1 ) {

    if (isset($this->selected_jobs[$job]) && $this->selected_jobs[$job]->type == 'applet') {
      return $this->show_answer_job_applet( $job, $make_table);
    } else {
      return $this->show_answer_job_html( $job, $make_table);
    }
  }


  /**
  * Job-Funktionen in Web-Oberfläche anzeigen
  *
  * @param    string  $job Name des Jobs
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_job_applet( $job, $make_table=1 ) {
		
    $job_attributes = $this->scheduler_jobs[$job];
    
    $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
    
   	if ( $make_table ) { $this->show_answer_table_begin(); }

    if ( strtolower($job_attributes['state']) == 'pending' && $job_attributes['next_start_time'] == '' ) { $job_attributes['state'] = 'none'; }
		
		//$this->debug_level = 9;
		# mit JS Starten
		$row_height = 100+count($this->selected_jobs[$job]->params)*30;
		# mit applet Button starten
		#$row_height = 140+count($this->selected_jobs[$job]->params)*30;
		
		if($this->debug_level){
			// für die Anzeige von Applet TextArea
			$row_height += 400;
		}

    $this->show_html('        <tr valign="top" height="'.$row_height.'">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">' . $job_attributes['job'] . '&nbsp;</td>');
    $this->show_html('          <td width="75%" class="' . $this->style_td_label  . '">');

    $this->show_html('<applet name="sos_scheduler_applet" codebase="applet" code="sos.subito.applet.SOSSubitoJobsApplet.class" archive="sos.subito.applet.jar,sos.util.jar,sos.scheduler.jar,sos.xml.jar,xalan.jar,xml-apis.jar" width="100%" height="100%">');
    $this->show_html('Fehler! Applet kann nicht starten! Der Browser kann kein Java, oder es ist abgeschaltet!');

		$this->show_html('<!-- Sprache : default de -->');
    $this->show_html('<param name="language" value="' . SOS_LANG . '">');
     
    $this->show_html('<!-- Debug-Level : default 0 -->');
    $this->show_html('<param name="debug_level" value="' . $this->debug_level . '">');
    #$this->show_html('<param name="debug_level" value="9">');
      
          
    $this->show_html('<!-- Host : default localhost-->');
    $this->show_html('<param name="host" value="' . $this->host . '">');
     
    $this->show_html('<!-- Port : default 4444 -->');
    $this->show_html('<param name="port" value="' . $this->port . '">');
     
    $this->show_html('<!-- Timeout : default 5 -->');
    $this->show_html('<param name="timeout" value="' . $this->timeout . '">');
          
    $this->show_html('<!-- Anzeigefeld: Job -->');
    $this->show_html('<param name="job.name" value="' . $job . '">');
    $this->show_html('<param name="job.title" value="' . $this->to_html($job_attributes['title']) . '">');
      
    $this->show_html('<!-- ... Soll ein Eingabefeld für den Job-Startzeitpunkt erzeugt werde oder nicht? Default: 0 -->');
    $this->show_html('<param name="job.start_time" value="' . $this->enable_start_time . '">');
      
    $this->show_html('<!-- es folgen die Parameterdefinitionen des Jobs -->');

    if ( isset($this->selected_jobs[$job]) ) {
      $param_counter = 1;
      $job_object = $this->selected_jobs[$job];
      foreach( $job_object->params as $name => $param) {
        $this->show_html('<!-- ... Name des Parameters, muss geliefert werden, kein Default -->');
        $this->show_html('<param name="param.' . $param_counter . '.name" value="' . $name . '">');
        $this->show_html('<!-- ... Label links neben Eingabefeld, Default: "Parameter" -->');
        $this->show_html('<param name="param.' . $param_counter . '.label" value="' . $param->prompt . '">');
        $this->show_html('<!-- ... Default-Wert, Default: -->');
        
        $default_value = (is_array($param->default)) ? implode(";", $param->default) : $param->default;
        
        $this->show_html('<param name="param.' . $param_counter . '.default" value="' . $default_value . '">');
        $this->show_html('<!-- ... vorbesetzter Wert, Default: -->');
        $this->show_html('<param name="param.' . $param_counter . '.value" value="' . $param->value . '">');
        $this->show_html('<!-- ... Eingabe-Datentyp: "char" => 0, "number" => 1, "date" => 2, "password" => 3, "token" => 4 Default: 0 -->');
        $this->show_html('<param name="param.' . $param_counter . '.input_type" value="' . $param->input_type . '">');
        $this->show_html('<!-- ... Darstellungstyp: "text" => 0, "listbox" => 1, "checkbox" => 2, "hidden" => 3, Default: 0 -->');
        $this->show_html('<param name="param.' . $param_counter . '.display_type" value="' . $param->type . '">');
        $this->show_html('<!-- ... Anzeigebreite des Eingabefelds, Default: 20px -->');
        $this->show_html('<param name="param.' . $param_counter . '.size" value="' . $param->size . '">');
        $this->show_html('<!-- ... Eingabe ist erforderlich (=1) oder nicht (=0), Default: 0 -->');
        $this->show_html('<param name="param.' . $param_counter . '.force" value="' . $param->force . '">');
        $param_counter++;
      }
    }
    $this->show_html('</applet>');
		$this->show_html('&nbsp;</td>');
    $this->show_html('</tr>');
		
		$this->show_html('<tr>');
		$this->show_html('	<td class="' . $this->style_td_label  . '">&nbsp;</td>');
    $this->show_html('	<td class="' . $this->style_td_label  . '">');
    $this->show_html('<a href="#" onclick="javascript:document.sos_scheduler_applet.startJob()" class="spoolerLinkJobaction">'.$this->img_job_action.'Starten</a>');
    $this->show_html('	</td>');
    $this->show_html('</tr>');
    
    if ( $make_table )         { $this->show_answer_table_end(); }
    
    return true;
  }


  /**
  * Job-Funktionen in Web-Oberfläche anzeigen
  *
  * @param    string  $job Name des Jobs
  * @param    integer $make_table Tabellenkopf erzeugen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_job_html( $job, $make_table=1 ) {
 
    $job_attributes = $this->scheduler_jobs[$job];
    $children       = ( isset($this->scheduler_job_children[$job]) ) ? $this->scheduler_job_children[$job] : array();
    $child_cnt      = 0;  
    $this->img_job_action     = '<img src="' . $this->img_dir . $this->img_job     . '" border="0" hspace="4" vspace="2">';
    $job_checks     = array();
    $job_check_cnt  = 0;

    $this->show_html('<a name="job' . ++$this->scheduler_job_count . '"/><form name="sos_spooler_form_' . $job . '" action="' . $this->site . $this->site_con . 'action=spooler_process&spooler_host=' . $this->host . '&spooler_port=' . $this->port . '" method="post" onSubmit="return sos_spooler_form_' . $job . '_onSubmit();">');
    if ( $make_table ) { $this->show_answer_table_begin(); }

    if ( strtolower($job_attributes['state']) == 'pending' && $job_attributes['next_start_time'] == '' ) { $job_attributes['state'] = 'none'; }
    $this->show_html('        <tr valign="top">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">' . $job_attributes['job'] . '&nbsp;</td>');
    if ( $job_attributes['title'] != '' ) {
      $this->show_html('          <td width="75%" colspan="3" class="' . $this->style_td_label . '">' );

      $ok = ( $children[$child_cnt]->tagname() == 'description' && !$this->enable_description );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'description' && !$this->enable_description );
      } else {
        $ok = ( $children[$child_cnt]->name == 'description' && !$this->enable_description );
      }
      */
      if ($ok) {
        $this->show_html( $this->print_jobaction( 'job_description', $this->get_translation('info'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '_blank', '&task=' . $job ) . '&nbsp;&nbsp;');
      }
      $this->show_html( $this->to_html($job_attributes['title']) . '&nbsp;' );
      $this->show_html('          </td>');
      $this->show_html('        </tr><tr>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
    }  

    $ok      = ( $children[$child_cnt]->tagname() == 'description' );
    if ($ok) { $content_desc = $children[$child_cnt]->get_content(); }
    /*
    if ( isset($children[$child_cnt]->tagname) ) {
      $ok      = ( $children[$child_cnt]->tagname == 'description' );
      if ($ok) { $content_desc = $children[$child_cnt]->get_content(); }
    } else {
      $ok      = ( $children[$child_cnt]->name == 'description' );
      if ($ok) { $content_desc = $children[$child_cnt]->content; }
    }
    */
    if ($ok) {
      $ok_desc = ( $this->enable_description && $content_desc != '' );
      if ( $ok_desc ) {      
        $this->show_html('          <td width="75%" colspan="3" class="' . $this->style_td_label . '">' . $this->to_html($content_desc) . '&nbsp;' );
        $this->show_html(            '&nbsp;</td>');
        $this->show_html('      </tr><tr>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
      }
      if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
    }

    $this->show_html('          <td width="25%" class="' . $this->style_td_action . '"><table border="0" cellPadding="0" cellSpacing="0" bgcolor="#FFFFFF" width="100%"><tr><td valign="top" class="' . $this->style_td_action . '">');
    $ok = ( !isset($_REQUEST['task']) );
    if (!$ok) { $ok = ( $_REQUEST['task'] != $job ); }
    if ($ok) {
      $this->show_html( $this->print_jobaction( 'spooler_status', $this->get_translation('job'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '_blank', '&task=' . $job ), '&nbsp;&nbsp;' );
      $job_label = '&nbsp;';
    } else {
      $job_label = $this->get_translation('job').'&nbsp;';
    }

    $this->show_html($this->get_translation('spooler_status').':&nbsp;</td><td valign="top"><font class="'    . $this->style_font_msg . '">' . $this->get_task_state($job_attributes['state']) . '</font></td></tr></table></td>');
    if ( $job_attributes['order_queue_length'] > 0 ) {
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('orders').':&nbsp;<font class="'  . $this->style_font_msg . '">'  . $job_attributes['order_queue_length'] . '</font>&nbsp;&nbsp;&nbsp;'.$this->get_translation('settled').':&nbsp;<font class="' . $this->style_font_msg . '">' . $job_attributes['all_steps'] . '</font>&nbsp;</td>');
    } else {
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('steps').':&nbsp;'.$this->get_translation('settled').':&nbsp;<font class="' . $this->style_font_msg . '">' . $job_attributes['all_steps'] . '</font>&nbsp;</td>');
    }
    $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('start_at').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_attributes['next_start_time']) . '</font>&nbsp;</td>');
    $this->show_html('        </tr>');


    if (isset($this->scheduler_tasks[$job]['tasks'])) {
      foreach( $this->scheduler_tasks[$job]['tasks'] as $job_task_index => $job_task ) {
        /*
        $found = ( $this->log_dir != '' );
        if ( $found ) {
          if ( substr(strrev($this->log_dir), 0, 1) != '/' ) { $this->log_dir .= ''; }
          $found = strrpos( $job_task['log_file'], '/' );
          if ( $found > 0 ) {
            $log_link = '<a href="' . $this->log_dir . substr($job_task['log_file'], $found+1) . '" class="spoolerLinkJobaction" target="_blank">' . $this->img_job_action . $this->get_translation('task') . '</a>'; 
          }
        }
        if ( !$found ) { $log_link = $this->get_translation('task'); }
        */
        $log_link = '<a href="javascript:show_log(\'http://' . $this->host . ':' . $this->port . '/show_log?task='. $job_task['id'] . '\');" class="spoolerLinkJobaction">' . $this->img_job_action . $this->get_translation('task') . '</a>'; 
    
        $this->show_html('        <tr>');
        $this->show_html('          <td width="25%" class="' . $this->style_td . '">&nbsp;</td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '"><table border="0" cellPadding="2" cellSpacing="0" bgcolor="#FFFFFF" width="100%"><tr><td valign="top" class="' . $this->style_td_action . '">');
        $this->show_html(             $log_link . '&nbsp;' . $job_task['id'] . ':&nbsp;</td><td valign="top"><font class="' . $this->style_font_msg . '">' . $this->get_task_state($job_task['state']) . '</font></td></tr>');
        $this->show_html(             '<tr><td valign="top">' . $this->print_jobaction( 'kill_task', $this->get_translation('spooler_terminate'), $job, '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $job_task['id'] ) . '</td><td>' . $this->print_jobaction( 'kill_task_immediately', $this->get_translation('spooler_abort'), $job, '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $job_task['id'] ) . '</td></tr>' );
        $this->show_html('          </table></td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '" valign="top">'.$this->get_translation('steps').':&nbsp;<font class="'  . $this->style_font_msg . '">'  . $job_task['steps'] . '</font>&nbsp;&nbsp;&nbsp;'.$this->get_translation('since').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_task['running_since']) . '</font>&nbsp;</td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '" valign="top">'.$this->get_translation('inactive').' '.$this->get_translation('since').':&nbsp;<font class="' . $this->style_font_msg . '">' . $this->to_datetime($job_task['idle_since']) . '</font>&nbsp;</td>');
        $this->show_html('        </tr>');
      }
    }
    /*
    $found = ( $this->log_dir != '' );
    if ( $found ) {
      if ( substr(strrev($this->log_dir), 0, 1) != '/' ) { $this->log_dir .= ''; }
      $found = strrpos( $job_attributes['log_file'], '/' );
      if ( $found > 0 ) {
        $log_link = '<a href="' . $this->log_dir . str_replace($this->get_translation('job').'.', $this->get_translation('task').'.', substr($job_attributes['log_file'], $found+1)) . '" class="spoolerLinkJobaction" target="_blank">' . $this->img_job_action . $this->get_translation('protocol') . '</a>';
      }
    }
    if ( !$found ) { $log_link = ''; }
    if ( !$found ) { $found    = ( $log_link != '' ); }
    */
    $log_link = '<a href="javascript:show_log(\'http://' . $this->host . ':' . $this->port . '/show_log?job='. $job . '\');" class="spoolerLinkJobaction">' . $this->img_job_action . $this->get_translation('protocol') . '</a>'; 
                         
    $this->show_html('        <tr>');
    $this->show_html('          <td width="25%" class="' . $this->style_td        . '">&nbsp;</td>');
    $this->show_html('          <td class="' . $this->style_td_label . '">' . $this->print_jobaction( 'job_history', $this->get_translation('history'), $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '_blank', '&task=' . $job ) . '&nbsp;&nbsp;' .  $log_link . '&nbsp;&nbsp;' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_params_show',$this->get_translation('parameter'), $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>');
    $this->show_html('          <td colspan="2" class="' . $this->style_td_msg . '">' . $this->to_html($job_attributes['state_text']) . '&nbsp;</td>');
    $this->show_html('        </tr>');


    if ( isset($this->selected_jobs[$job]) ) {
      $job_object = $this->selected_jobs[$job];
      foreach( $job_object->params as $name => $param) {
        if ( $param->type == 3 ) {
          $this->show_html('<input name="' . $name . '" value="' . $param->value . '" type="hidden">');
          continue;
        }
        $this->show_html('        <tr valign="top">');
        $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
        $this->show_html('          <td class="' . $this->style_td_action . '">' . $param->prompt . '&nbsp;&nbsp;</td>');
        $this->show_html('          <td colspan="2" class="' . $this->style_td_action . '">' );
        switch( $param->type ) {
          case 1  : $this->show_html('            <select class="spoolerInput" name="' . $name . '">');
                    foreach( $param->default as $key => $value) {
                      if ( $param->value == $key ) {
                        $selected = ' selected';
                      } else {
                        $selected = '';
                      }
                      $this->show_html('              <option value="' . $key . '"' . $selected . '>' . $value );
                    }
                    $this->show_html('            </select>&nbsp;');
                    break;
          case 2  : $checked = ($param->value != '') ? ' checked' : '';
                    $this->show_html('            <input class="spoolerInput" name="' . $name . '" type="checkbox"' . $checked . '>&nbsp;');
                    break;
          case 3  : $this->show_html('            <input name="' . $name . '" value="' . $param->value . '" type="hidden">&nbsp;');
                    break;
          default : $this->show_html('            <input class="spoolerInput" name="' . $name . '" value="' . $param->value . '" type="text" size="' . $param->size . '">&nbsp;');
                    switch ( $param->input_type ) {
                      case 1 : $job_checks[$job_check_cnt][0]   = $name;
                               $job_checks[$job_check_cnt++][1] = 'isValidNumber(document.sos_spooler_form_' . $job . '.' . $param->name . ', ' . $param->force . ')';
                               break;
                      case 2 : $job_checks[$job_check_cnt][0]   = $name;
                               $job_checks[$job_check_cnt++][1] = 'isValidDate(document.sos_spooler_form_' . $job . '.' . $name . ', ' . ($param->force ? 1 : 0) . ', 0)';
                               break;
                    }                    
        }
        $this->show_html('          </td>' );
        $this->show_html('        </tr>');
      }
    }

    if ( $this->enable_start_time || ( isset($this->selected_jobs[$job]) && $this->selected_jobs[$job]->enable_start_time ) ) {
      $start_at = (isset($_REQUEST['start_at'])) ? $_REQUEST['start_at'] : '';
      $this->show_html('        <tr valign="top">');
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">&nbsp;</td>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('next_start').'&nbsp;</td>');
      $this->show_html('          <td class="' . $this->style_td_action . '" colspan="2"><input class="spoolerInput" type="text" name="start_at" value="' . $start_at . '" size="20"> &nbsp;</td>');
      $this->show_html('        </tr>');
      $job_checks[$job_check_cnt][0]   = 'start_at';
      $job_checks[$job_check_cnt++][1] = 'isValidDate(document.sos_spooler_form_' . $job . '.start_at, 0, 1)';
    }

    for($child_cnt=0; $child_cnt<count($children); $child_cnt++) {

      $ok = ( $children[$child_cnt]->tagname() == 'queued_tasks' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'queued_tasks' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'queued_tasks' );
      }
      */

      if ($ok) {
        $queue_attributes['enqueued'] = '';
        $queue_attributes['name']     = $this->get_translation('job');
        $queue_attributes['start_at'] = '';
        $queue_attributes['id']       = '';
          
        $queues = $children[$child_cnt]->children();
        for($i=0; $i<count($queues);$i++) {
          $attributes = $queues[$i]->attributes();
          $found = 0;
          for($j=0; $j<count($attributes); $j++) {
            if ( $attributes[$j]->name != '' )    {
              $found = 1;
              $queue_attributes[$attributes[$j]->name] = $queues[$i]->get_attribute($attributes[$j]->name);
            }
          }        
          if ( $found ) {
            $this->show_html('        <tr valign="top">');
            $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">' );
            if ( $queue_attributes['id'] != '' ) {
              //$this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'kill_task',        $this->get_translation('kill_task'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count, '', '', '&item=' . $queue_attributes['id'] ) . '&nbsp;&nbsp;');
              $this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_queue_task',   $this->get_translation( 'job_queue_task' ),        $job_attributes['job'], '', '#job' . $this->scheduler_job_count, '', '', '&item=' . $queue_attributes['id'].'&item_index='.$i.'&job_location='.$this->scheduler_job_count) . '&nbsp;&nbsp;');
            }
            $this->show_html($this->get_translation('from_queued_tasks') . $queue_attributes['name'] . '&nbsp;&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation('enqueued').' ' . $this->to_datetime($queue_attributes['enqueued']) . '&nbsp;&nbsp;</td>');
            if ( $queue_attributes['start_at'] != '' ) {
              $this->show_html('          <td class="' . $this->style_td_action . '">'.$this->get_translation('start_at').' ' .  $this->to_datetime($queue_attributes['start_at']) . '&nbsp;&nbsp;</td>');
            } else {
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;&nbsp;</td>');
            }
            $this->show_html('        </tr>');
          }
        }
        # if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
      }

      $ok = ( $children[$child_cnt]->tagname() == 'ERROR' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'ERROR' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'ERROR' );
      }
      */

      if ($ok) {
        $error_attributes['time']   = '';
        $error_attributes['class']  = '';
        $error_attributes['code']   = '';
        $error_attributes['text']   = '';
        $error_attributes['source'] = '';
        $error_attributes['line']   = '';
        $error_attributes['col']    = '';
        $attributes = $children[$child_cnt]->attributes();

        for($err_cnt=0; $err_cnt<count($attributes); $err_cnt++) {
          if ( $attributes[$err_cnt]->name != '' )    {
            $error_attributes[$attributes[$err_cnt]->name] = $children[$child_cnt]->get_attribute($attributes[$err_cnt]->name); 
          }
        }
        if ( $error_attributes['source'] != '' ) {
          $error_source = ' ['.$this->get_translation('file').' ' . $error_attributes['source'] . ', '.$this->get_translation('line').' ' . $error_attributes['line'] . ', '.$this->get_translation('column').' ' . $error_attributes['col'] . ']';
        } else {
          $error_source = '';
        }

        $this->show_html('        <tr valign="top">');
        $this->show_html('          <td width="25%" class="' . $this->style_td_label  . '">&nbsp;</td>');
        $this->show_html('          <td colspan="3" class="' . $this->style_td_error . '">');
        $this->show_html('            '.$this->get_translation('error_time').' ' . $this->to_datetime($error_attributes['time']) . ' '.$this->get_translation('clock').' [' . $error_attributes['code'] . ' ' . $error_attributes['class'] . ']: ' . $this->to_html($error_attributes['text']) . $error_source );
        $this->show_html('          </td>');
        $this->show_html('        </tr>');
      }          

      $ok = ( $children[$child_cnt]->tagname() == 'order_queue' );
      /*
      if ( isset($children[$child_cnt]->tagname) ) {
        $ok = ( $children[$child_cnt]->tagname == 'order_queue' );
      } else {
        $ok = ( $children[$child_cnt]->name == 'order_queue' );
      }
      */

      if ($ok && $this->enable_job_orders) {
        $orders = $children[$child_cnt]->children();
        for($i=1; $i<count($orders); $i++) {
          $order_id = '';
          $order_attributes = array();
          $order_attributes['id']               = 0;
          $order_attributes['title']            = '';
          $order_attributes['job_chain']        = '';
          $order_attributes['job']              = '';
          $order_attributes['state']            = '';
          $order_attributes['state_text']       = '';
          $order_attributes['priority']         = '';
          $order_attributes['created']          = '';
          $order_attributes['in_process_since'] = '';

          $found = 0;
          $attributes = $orders[$i]->attributes();
          for($j=0; $j<count($attributes); $j++) {
            if ( $attributes[$j]->name != '' ) {
              $found = 1;
              $order_attributes[$attributes[$j]->name] = $orders[$i]->get_attribute($attributes[$j]->name);
            }
          }
          if ($found) {
            $this->show_html('        <tr valign="top">');
            $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '">' );
            if ( $order_attributes['id'] != "" ) {
              #$this->show_html('            ' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'order_priority',        $this->get_translation('order'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count, '', '', '&item=' . $order_attributes['id'] ) . '&nbsp;');
              $this->show_html(' '.$this->get_translation('order').'&nbsp;' );
            }
            $this->show_html( $order_attributes['id'] . '&nbsp;&nbsp;&nbsp;'.$this->get_translation('spooler_status').' ' . $order_attributes['state'] . '&nbsp;&nbsp;</td>');
            $this->show_html('          <td class="' . $this->style_td_action . '"><nobr>'.$this->get_translation('enqueued').' ' . $this->to_datetime($order_attributes['created']) . '&nbsp;&nbsp;</nobr></td>');
            if ( $order_attributes['in_process_since'] != '' ) {
              $this->show_html('          <td class="' . $this->style_td_action . '"><nobr>'.$this->get_translation('start_at').' ' .  $this->to_datetime($order_attributes['in_process_since']) . '</nobr>&nbsp;&nbsp;</td>');
            } else {
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;&nbsp;</td>');
            }
            $this->show_html('        </tr>');
            if ( $order_attributes['title'] . $order_attributes['state_text'] != "" ) {
              $this->show_html('        <tr valign="top">');
              $this->show_html('          <td class="' . $this->style_td_action . '">&nbsp;</td>');
              $this->show_html('          <td class="' . $this->style_td_action . '">' . $order_attributes['state_text'] . '</td>' );
              $this->show_html('          <td class="' . $this->style_td_action . '" colspan="2">' . $order_attributes['title'] . '</td>' );
              $this->show_html('        </tr>');
            }
          }
        }
        # if ( $child_cnt < count($children)-1 ) { $child_cnt += 1; }
      }

    }

    $this->show_html('        <tr valign="top">');
    $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">&nbsp;</td>');
    $this->show_html('          <td colspan="3" class="' . $this->style_td_action . '">');
    $this->show_html('            <table width="100%"><tr>');
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'spooler_status', $this->get_translation('spooler_status'),      '',                      '',                          '#job' . $this->scheduler_job_count, $this->style_scheduler_action, '', '', $this->img_scheduler_action ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_suspend',    $this->get_translation('spooler_pause'),       $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_continue',   $this->get_translation('spooler_continue'),    $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_stop',       $this->get_translation('spooler_stop'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_reread',     $this->get_translation('spooler_reread'),      $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('            </tr><tr>');

    if ( $this->enable_job_chains ) {
      $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_orders',   $this->get_translation('orders'),       $job_attributes['job'], '',                          '#job1', $this->style_scheduler_action, '', '', $this->img_scheduler_action ) . '&nbsp;&nbsp;</td>' );
    } else {
      $this->show_html('              <td width="20%">&nbsp;&nbsp;</td>' );
    }

    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_start',      $this->get_translation('job_start'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>');
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_end',        $this->get_translation('job_end'),        $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_unstop',     $this->get_translation('job_unstop'), $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
   #$this->show_html('              <td width="20%">' . $this->print_jobscriptaction( 'sos_spooler_form_' . $job_attributes['job'], 'job_queue',      $this->get_translation('queue'),  $job_attributes['job'], $this->jobaction_javascript, '#job' . $this->scheduler_job_count ) . '&nbsp;&nbsp;</td>' );
    $this->show_html('              <td width="20%">&nbsp;</td>');
    $this->show_html('            </tr></table>');
    $this->show_html('          </td>');
    $this->show_html('        </tr>');

    if ( $make_table )         { $this->show_answer_table_end(); }
    if ( $this->enable_timer ) {
      $ok = true;
      if ( isset($_REQUEST['task']) ) { $ok = ( $_REQUEST['task'] != '' ); }
      if ($ok) { $this->get_task_timer($job, $job_attributes['state'], '#job' . $this->scheduler_job_count); }
    }
    $this->show_html('<script language="JavaScript" type="text/javascript">');
    $this->show_html('  function sos_spooler_form_' . $job . '_onSubmit(action) {');
    $this->show_html('    var ok = true;' );
    $this->show_html('    if ( action == null || typeof(action) == "undefined" ) { return ok; }');
    $this->show_html('    if ( action.indexOf("job_start") > -1 ) {');
    for($i=0; $i<$job_check_cnt; $i++) {
      $this->show_html('      if (ok) { ok = ' . $job_checks[$i][1] . '; }' );
    }
    $this->show_html('    }');
    $this->show_html('    return ok;');
    $this->show_html('  }');
    $this->show_html('</script>');    
    $this->show_html('</form>');
    return true;
  }


  /**
  * Alle Jobs mit Eigenschaften in Web-Oberfläche anzeigen
  *
  * @param    string  $task Name des Jobs, falls nur einer angezeigt werden soll
  * @param    integer $history Anzahl Historieneinträge die dargestellt werden
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_tasks( $job='', $history=0, $make_table=1 ) {

    $ok         = true;
    $task_count = 0;
		
    switch( $this->action ) {
    
      case 'job_params_show': break;
      case 'spooler_query':
      case 'job_query':
                if ( class_exists('SOS_Scheduler_History') ) { 
                  $this->show_query();
                  if ( $this->action == 'job_query' && $this->process_query() ) { 
                  	$this->show_result(); $this->destruct(); $task_count++; }
                }
                break;

      case 'job_description': 
                $this->show_description( $job );
                break;

      case 'job_queue':
                break;
                
      case 'job_queue_task': 
      					$this->show_job_queue_task();
      					break;
                
      case 'job_history_remove':
                if ( class_exists('SOS_Scheduler_History') ) { $this->remove_job_history(); $this->show_query(); $task_count++; }
                break;
                
      case 'job_history_log':
      					if(isset($_REQUEST['scope']) && $_REQUEST['scope'] == 'simple'){
      						$this->show_history( $job );
      					}
      					else{// sos_scheduler_history method for quicklist
      						$this->get_history( $job ); 
                }
                $task_count++;
                break;
      case 'job_history': 
                $this->show_history( $job ); 
                $task_count++;
                break;
                
      case 'job_chains' :
                $this->show_job_chains(); $task_count++;
                break;

      case 'job_info' :
                if ( !$this->enable_all_tasks && count($this->selected_jobs) > 0 ) {

                  for($i=0; $i<count($this->selected_jobs_order); $i++) {
                    $ok = isset($this->scheduler_jobs[$this->selected_jobs_order[$i]]);
                    if ($ok) { $ok = ( $job == '' || $job == $this->selected_jobs_order[$i] ); }
                    if ($ok && $job != '') { $this->get_task_window_size($this->selected_jobs_order[$i]); }
                    if ($ok) { 
                      $ok = ( in_array($this->scheduler_jobs[$this->selected_jobs_order[$i]]['state'], array('starting', 'running', 'running_process', 'suspended', 'ending') ) );
                      if (!$ok) { $ok = isset($this->scheduler_queued_tasks[$this->selected_jobs_order[$i]]); }
                    }
                    if ($ok) { 
                      $ok = $this->show_answer_job_info( $this->selected_jobs_order[$i], $make_table ); 
                      $task_count++;
                    }
                  }

                } else {

                  foreach( $this->scheduler_jobs as $job_name => $job_object ) {
                    if ( $job == '' ) {
                      $ok = ( $this->enable_all_tasks || ( count($this->selected_jobs) == 0 || isset($this->selected_jobs[$job_name]) ) );
                    } else {
                      $ok = ( $job == $job_name && ( count($this->selected_jobs) == 0 || isset($this->selected_jobs[$job_name]) ) );
                      if ($ok) { $this->get_task_window_size($job_name); }
                    }  
                    if ($ok) {
                      $ok = ( in_array($job_object['state'], array('starting', 'running', 'running_process', 'suspended', 'ending') ) ); 
                      if (!$ok) { $ok = isset($this->scheduler_queued_tasks[$job_name]); }
                    }
                    if ($ok) {
                      if ( $job != '' && $history > 0 ) { $this->switch_action( 'job_history', $job_name, 0, $history, 1 ); }
                      $ok = $this->show_answer_job_info( $job_name, $make_table );
                      $task_count++;
                    }
                  }

                }
                break;
      case 'kill_queue_task'			:
			case 'job_queue_task_store' : $this->item = ''; # ohne break;
      default:  if ( $this->enable_spooler ) { 
                   $ok = $this->show_answer_spooler(); 
                   if ($ok && $this->enable_monitoring && count($this->remote_schedulers) > 0) $ok = $this->show_answer_remote_schedulers();
                }
                
                if ( !$this->enable_all_tasks && count($this->selected_jobs) > 0 ) {
                	for($i=0; $i<count($this->selected_jobs_order); $i++) {
                    $ok = isset($this->scheduler_jobs[$this->selected_jobs_order[$i]]);
                    if ($ok) { $ok = ( $job == '' || $job == $this->selected_jobs_order[$i] ); }
                    if ($ok && $job != '') { $this->get_task_window_size($this->selected_jobs_order[$i]); }
                    if ($ok) { 
                      if ( $job != '' && $history > 0 ) { $this->switch_action( 'job_history', $this->selected_jobs_order[$i], 0, $history, 1 ); }
                      $ok = $this->show_answer_job( $this->selected_jobs_order[$i], $make_table );
                      $task_count++;
                    }
                  }

                } 
                else {

                  foreach( $this->scheduler_jobs as $job_name => $job_object ) {
                    if ( $job == '' ) {
                      $ok = ( $this->enable_all_tasks || ( count($this->selected_jobs) == 0 || isset($this->selected_jobs[$job_name]) ) );
                    } else {
                      $ok = ( $job == $job_name && ( count($this->selected_jobs) == 0 || isset($this->selected_jobs[$job_name]) ) );
                      if ($ok) { $this->get_task_window_size($job_name); }
                    }
                    if ($ok) { 
                      if ( $job != '' && $history > 0 ) { $this->switch_action( 'job_history', $job_name, 0, $history, 1 ); }
                      $ok = $this->show_answer_job( $job_name, $make_table );
                      $task_count++;
                    }
                  }

                }

                if ($ok) { $this->show_add_jobs(); }
    }
    
    return $task_count;
  }


  /**
  * Eingabeformular für zusätzliche Jobs in Web-Oberfläche anzeigen
  *
  * @param    string  $thread Name des Threads für Formular
  * @param    integer $make_table Tabellenkopf anzeigen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_add_jobs( $thread='', $make_table=1 ) {
  
    if ( $this->enable_add_jobs ) {
      if ( $thread == '' ) { $thread = $this->scheduler_thread; }
      $javascript = ( $this->enable_start_time ) ? ' onSubmit="return isValidDate(document.sos_spooler_form_add_job_' . $thread . '.start_at, 0, 1)"' : '';
      
      $this->show_html('      <form name="sos_spooler_form_add_job_' . $thread . '" action="' . $this->site . $this->site_con . 'action=spooler_process&spooler_host=' . $this->host . '&spooler_port=' . $this->port . '" method="post"' . $javascript . '>');
      if ( $make_table ) { $this->show_answer_table_begin(); }
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" valign="top" class="' . $this->style_td_label  . '">');
      $this->show_html('            <input type="image" name="btn_jobstart_' .  $thread . '" src="' . $this->img_dir . SOS_LANG . '/btn_start.gif" onClick="{ if( document.sos_spooler_form_add_job_' . $thread . '.process_file.value == \'\' ) { alert(\''.$this->get_translation('empty_process_file_alert').'\'); document.sos_spooler_form_add_job_' . $thread . '.process_file.focus(); return false; } else { return true; } }"><input type="hidden" name="process_thread" value="' . $this->scheduler_thread . '">');
      $this->show_html('          </td>');
      $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('program').'&nbsp;</td>');
      $this->show_html('          <td colspan="2" valign="top" class="' . $this->style_td . '"><input type="text" class="spoolerInput" name="process_file" value="' . $this->{$this->normalize_input}($this->process_file) . '" size="35">&nbsp;&nbsp;');
      $this->show_html('        </tr>');
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" valign="top" class="' . $this->style_td_label . '">&nbsp;</td>');
      $this->show_html('          <td width="25%" valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('parameter').'</font>&nbsp;&nbsp;</font>&nbsp;</td>');
      $this->show_html('          <td colspan="2" valign="top" class="' . $this->style_td . '"><input type="text" class="spoolerInput" name="process_param" value="' . $this->{$this->normalize_input}($this->process_param) . '" size="35">&nbsp;</td>');
      $this->show_html('        </tr>');
      $this->show_html('        <tr>');
      $this->show_html('          <td width="25%" valign="top" class="' . $this->style_td_label . '">&nbsp;</td>');
      $this->show_html('          <td width="25%" valign="top" class="' . $this->style_td . '"><font class="' . $this->style_font_entry . '">'.$this->get_translation('protocol').'</font>&nbsp;&nbsp;</font>&nbsp;</td>');
      $this->show_html('          <td colspan="2" valign="top" class="' . $this->style_td . '"><input type="text" class="spoolerInput" name="process_log" value="' . $this->{$this->normalize_input}($this->process_log) . '" size="35">&nbsp;</td>');
      $this->show_html('        </tr>');
      
      if ( $this->enable_start_time ) {
        $start_at = (isset($_REQUEST['start_at'])) ? $_REQUEST['start_at'] : '';
        $this->show_html('        <tr valign="top">');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">&nbsp;</td>');
        $this->show_html('          <td width="25%" class="' . $this->style_td_action . '">'.$this->get_translation('next_start').'&nbsp;</td>');
        $this->show_html('          <td class="' . $this->style_td_action . '" colspan="2"><input class="spoolerInput" type="text" name="start_at" value="' . $start_at . '" size="20"> &nbsp;</td>');
        $this->show_html('        </tr>');
      }

      if ( $make_table ) { $this->show_answer_table_end(); }
      $this->show_html('          </form>' );
    }
  }
  

  /**
  * Tabellenkopf anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_table_begin() {

    $this->show_html('<table width="100%" border="0">');
    $this->show_html('  <tr>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('    <td class="' . $this->style_td_background . '">');
    $this->show_html('      <table border="0" cellPadding="5" cellSpacing="1" width="100%">');
  }
  

  /**
  * Tabellenfuß anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_answer_table_end() {

    $this->show_html('      </table>');
    $this->show_html('    </td>');
    $this->show_html('    <td width="20" bgColor="#FFFFFF">&nbsp;</td>');
    $this->show_html('  </tr>');
    $this->show_html('</table>');
  }
  
  /**
  * eingereihter Job Verwalten 
  *
  * @access   private
  * @author   Robert Ehrlich <re@sos-berlin.com>
  * @version  1.0-2004/08/12
  */

  function show_job_queue_task() {
 
 		$job				= $_REQUEST['job'];
  	$queue_task = array();
   	  	
  	if(!isset($this->scheduler_queued_tasks[$job]['tasks'][$_REQUEST['item_index']])){
  		return 0;	
  	}
  	else{
  		$queue_task = $this->scheduler_queued_tasks[$job]['tasks'][$_REQUEST['item_index']];
  	}
   	
   	$rowspan = 2;
   	if(isset($this->selected_jobs[$job])){
    	$rowspan += count($this->selected_jobs[$job]->params)+1;
   	}
   	else{
   		if(isset($queue_task['parameters'])){
   			$rowspan += count($queue_task['parameters'])+1;	
   		}
   	}
   	
   	$job_location = '';
   	$anker 				= '';
   	if(isset($_REQUEST['job_location'])){
   		$job_location = '#job'.$_REQUEST['job_location'];
   		$anker 				= '#job'.$_REQUEST['job_location'];
   	}
   	
   	$this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' && $this->session_id) {
    	 if($this->site_con == '?')	{	$sess_con = '';	  }
    	 else												{ $sess_con = '&';	}
       $query_session = $sess_con.$this->session_var . '=' . $this->session_id;
    } 
    else {
       $query_session  = '';
       $this->site_con = '';
    }
    
   	$this->show_answer_table_begin();
   	$this->show_html('<form name="sos_spooler_form" method="post" action="'.$this->site.$this->site_con.$query_session.$anker.'" onSubmit="return queueOnSubmit();">');
   	
   	$this->show_html('<tr>');
   	$this->show_html('	<td colspan="3" class="'.$this->style_td_label.'">'.$job.'</td>');   	
   	$this->show_html('</tr>');
   	
   	$this->show_html('<tr>');
   	$this->show_html('	<td rowspan="'.$rowspan.'" width="25%" class="'.$this->style_td.'">&nbsp;</td>');
   	$this->show_html('	<td class="'.$this->style_td_label.'">'.$this->get_translation('start_at').'</td>');   	
   	$this->show_html('	<td class="'.$this->style_td.'">');
   	$this->show_html('		<input class="spoolerInput" type="text" name="queue_start_at" size="20" value="'.$this->to_datetime($queue_task['attributes']['start_at']).'">');
   	$this->show_html('	</td>');
   	$this->show_html('</tr>');
   	
   	$job_checks 	= array();
   	$job_checks[] = 'isValidDate(document.sos_spooler_form.queue_start_at, 1, 1)';
   	
   	if(isset($queue_task['parameters'])){
   		$this->show_html('<tr>');
   		$this->show_html('	<td colspan="3" class="'.$this->style_td_label.'">'.$this->get_translation('parameter').'</td>');   	
   		$this->show_html('</tr>');
   		
   		$i	= 0;
   		
   		if ( isset($this->selected_jobs[$job]) ) {
    	  $job_object = $this->selected_jobs[$job];
    	  foreach( $job_object->params as $name => $param) {
    	    if(isset($queue_task['parameters'][$name])){
    	    	$param->value = $queue_task['parameters'][$name];
    	    }
    	    if ( $param->type == 3 ) {
    	      $this->show_html('<input name="job_queue_param_value['.$i.']" value="'.$param->value.'" type="hidden">');
    	      $this->show_html('<input name="job_queue_param_name['.$i.']" 	value="'.$name.'" 				type="hidden">');
    	    	$i++;
    	      continue;
    	    }
    	    $this->show_html('        <tr valign="top">');
    	    $this->show_html('          <td class="'.$this->style_td_action.'">'.$param->prompt.'</td>');
    	    $this->show_html('          <td class="'.$this->style_td_action.'">' );
    	    
    	    switch( $param->type ) {
    	      case 1  : 
    	      					$this->show_html('<select class="spoolerInput" name="job_queue_param_value['.$i.']">');
    	                foreach( $param->default as $key => $value) {
    	                  if($param->value == $key ){ $selected = ' selected';	} 
    	                  else 											{	$selected = '';           }
    	                  $this->show_html('	<option value="'.$key.'"'.$selected.'>'.$value);
    	                }
    	                $this->show_html('</select>');
    	                break;
    	      case 2  : 
    	      					$checked = ($param->value != '') ? ' checked' : '';
    	                $this->show_html('<input class="spoolerInput" name="job_queue_param_value['.$i.']" type="checkbox"'.$checked.'>');
    	                break;
    	                
    	      case 3  : 
    	      					$this->show_html('<input name="job_queue_param_value['.$i.']" value="'.$param->value.'" type="hidden">');
    	                break;
    	                
    	      default : 
    	      			    $this->show_html('<input class="spoolerInput" name="job_queue_param_value_'.$i.'" value="'.$param->value.'" type="text" size="'.$param->size.'">');
    	                
    	                switch ( $param->input_type ) {
    	                  case 1 : 
    	                  		     $job_checks[] = 'isValidNumber(document.sos_spooler_form.job_queue_param_value_'.$i.', '.$param->force.')';
    	                           break;
    	                           
    	                  case 2 : 
    	                  		     $job_checks[] = 'isValidDate(document.sos_spooler_form.job_queue_param_value_'.$i.', '.$param->force.', 1)';
    	                           break;
    	                }                    
    	    }
    	    $this->show_html('          </td>' );
    	    $this->show_html('        </tr>');
    	    
    	    $this->show_html('	<input type="hidden" name="job_queue_param_name['.$i.']" value="'.$name.'">');
    	    $i++;
    	  }
    	}
			else{
   			foreach($queue_task['parameters'] as $param=>$value){
   				$this->show_html('<tr>');
   				$this->show_html('	<td class="'.$this->style_td.'">'.$param.'</td>'); 
   				$this->show_html('	<input type="hidden" name="job_queue_param_name['.$i.']" value="'.$param.'">');
   				$this->show_html('	<td class="'.$this->style_td.'">');  	
   				$this->show_html('		<input class="spoolerInput" type="text" name="job_queue_param_value['.$i.']" size="20" value="'.$value.'">');
   				$this->show_html('	</td>');
   				$this->show_html('</tr>');
   				$i++;
   			}	
   		}
   	}
   	
   	$this->show_html('<tr>');
  	$this->show_html('	<td colspan="2" class="'.$this->style_td_action.'">');
  	$this->img_scheduler_action = '';
   	
   	$this->show_html('<input type="image" name="btn_job_queue_task_store" src="'.$this->img_dir.SOS_LANG.'/btn_store.gif">');
   	$this->show_html($this->print_action( 'kill_queue_task','<img src="'.$this->img_dir.SOS_LANG.'/btn_remove.gif" border="0">', $this->jobaction_javascript, $job_location, '', '', '&job='.$job.'&item='.$queue_task['attributes']['id'] ) . '&nbsp;&nbsp;');
   	$this->show_html( $this->print_jobaction( '','<img src="'.$this->img_dir.SOS_LANG.'/btn_cancel.gif" border="0">','', '',$job_location));
   	$this->show_html('	</td>');
    $this->show_html('</tr>');
   	        
   	$this->show_html('<input type="hidden" name="job" value="'.$job.'">');
   	$this->show_html('<input type="hidden" name="item" value="'.$_REQUEST['item'].'">');
   	$this->show_html('<input type="hidden" name="spooler_host" value="'.$_REQUEST['spooler_host'].'">');
   	$this->show_html('<input type="hidden" name="spooler_port" value="'.$_REQUEST['spooler_port'].'">');
   	   	
   	$this->show_html('<script language="JavaScript" type="text/javascript">');
    $this->show_html('  function queueOnSubmit() {');
    $this->show_html('    var ok = true;' );
    foreach($job_checks as $check){
      $this->show_html('  if (ok) { ok = '.$check.'; }');
    }
    $this->show_html('    return ok;');
    $this->show_html('  }');
    $this->show_html('</script>'); 
   	  	
   	
   	$this->show_html('</form>');
   	$this->show_answer_table_end();
  }

  /**
  * Link für Scheduler-Aktion anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_action( $action, $action_title, $javascript='', $anchor='', $css='', $target='', $query='' ) {

    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $css == '' )    { $css    = $this->style_scheduler_action; }
    if ( $target != '' ) { $target = ' target="' . $target . '" '; }

    return '<a class="' . $css . '" href="' . $this->site . $this->site_con . 'action=' . $action . $query . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . $anchor . '" ' . $target . $javascript . '>' . $this->img_scheduler_action . $action_title . '</a>';
  }


  /**
  * Link für Job-Aktion anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_jobaction( $action, $action_title, $job, $javascript='', $anchor='', $css='', $target='', $query='' ) {
  
    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $css == '' )    { $css    = $this->style_job_action; }
    if ( $target != '' ) { $target = ' target="' . $target . '" '; }

    return '<a class="' . $css . '" href="' . $this->site . $this->site_con . 'action=' . $action . '&job=' . $job . $query . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . $anchor . '" ' . $target . $javascript . '>' . $this->img_job_action . $action_title . '</a>';
  }


  /**
  * JavaScript-Link für Spooler-Aktion anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_jobscriptaction( $form, $action, $action_title, $job, $javascript='', $anchor='', $css='', $target='', $query='', $img='' ) {

    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $css == '' )         { $css    = $this->style_job_action; }
    if ( $target != '' )      { $target = ' target="' . $target . '" '; }
    if ( $img == '' )         { $img    = $this->img_job_action; }
    if ( $javascript == '' )  {
      $javascript = 'onClick="return ' . $form . '_onSubmit(\'' . $action . '\');"';
    } else {
      $pos = strpos(strtolower($javascript), 'onclick="');
      if (is_integer($pos)) { $javascript = 'onClick="if (!' . $form . '_onSubmit(\'' . $action . '\') ) { return false; } else { ' . substr($javascript, $pos+9, strlen($javascript)-$pos-10) . ' }"'; }
    }
    return '<a class="' . $css . '" href="javascript:document.' . $form . '.action=\'' . $this->site . $this->site_con . 'action=' . $action . '&job=' . $job . $query . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . $anchor . '\'; document.body.style.cursor = \'wait\'; document.' . $form . '.submit();"' . $target . ' ' . $javascript . '>' . $img . $action_title . '</a>';
  }


  /**
  * HTML-Button für Spooler-Aktion anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_jobscriptbutton( $form, $action, $action_title, $job, $javascript='', $anchor='', $query='' ) {
  
    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
       $query_session = '&' . $this->session_var . '=' . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $javascript == '' )  {
      $javascript = 'onClick="return ' . $form . '_onSubmit(\'' . $action . '\');"';
    } else {
      $pos = strpos(strtolower($javascript), 'onclick="');
      if (is_integer($pos)) { $javascript = 'onClick="if (!' . $form . '_onSubmit(\'' . $action . '\') ) { return false; }; ' . substr($javascript, $pos+9); }
    }

    return '<input name="button_' . $action . '" type="submit" value="' . $action_title . '" class="spoolerButton" onClick="document.' . $form . '.action=\'' . $this->site . $this->site_con . 'action=' . $action . '&job=' . $job . $query . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . $anchor . '\';"' . ' ' . $javascript . '>';
  }


  /**
  * Image-Button für Spooler-Aktion anzeigen
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_jobscriptimagebutton( $form, $action, $action_title, $job, $javascript="", $anchor="", $image, $query="", $disable=0 ) {
  
    $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
    if ( !$this->session_use_trans_sid && $this->session_var != "" ) {
       $query_session = "&" . $this->session_var . "=" . $this->session_id;
    } else {
       $query_session = '';
    }

    if ( $javascript == '' )  {
      $javascript = 'onClick="return ' . $form . '_onSubmit(\'' . $action . '\');"';
    } else {
      $pos = strpos(strtolower($javascript), 'onclick="');
      if (is_integer($pos)) { $javascript = 'onClick="if (!' . $form . '_onSubmit(\'' . $action . '\') ) { return false; }; ' . substr($javascript, $pos+9); }
    }
    $javascript = '" onClick="if (' . $form . '_onSubmit(\'' . $action . '\') ) { document.' . $form . '.action=\'' . $this->site . $this->site_con . 'action=' . $action . '&job=' . $job . $query . '&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $query_session . $anchor . '\'; } else { return false; }"';
    $disabled = ( $disable ) ? ' DISABLED ' : '';

    return '<input name="button_' . $action . '" type="image" src="' . $image . '" alt="' . $action_title . $javascript . $disabled . '>';
  }


  /**
  * XML-Knoten ausgeben
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function print_nodes( $nodes ) { 
  
    print '<hr>';
    while ($node = array_shift($nodes))
    {
      print 'name: ' . $node->name . '<br>';
    }
    print '<hr>';
  }


  /**
  * Aktionssteuerung für Spooler-Aktion
  *
  * @param    string   $action Aktion, z.B. spooler_status
  * @param    string   $job Name des Jobs
  * @param    integer  $item Lfd. Nr. des Jobs im Historieneintrag
  * @param    integer  $range Anzahl Historieneinträge für Historiensuchfunktion
  * @param    integer  $no_state Keine erneute Spooler-Statusabfrage durchführen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function switch_action( $action, $job='', $item=0, $range=0, $no_state=0 ) {

    $this->action  = $action;
    if ( $item > 0 ) { $this->item = $item; }
    $send_item  = ( $this->item > 0 ) ? ' id="' . $this->item . '" ' : '';

    if ( $range > 0 ) {
      $this->range = $range;
      $send_range  = ($send_item) ? ' next' : ' prev';
      $send_range .= '="' . $this->range . '" ';
    } 
    elseif ( $range < 0 ) {
      $this->range = $range;
      $send_range = ' prev="' . ($this->range*-1) . '" ';
    } 
    else {
      $send_range = '';
    }
    $send_command  = 1;
    if ($this->enable_job_orders) { // falls gewünscht alle order_queues mitlesen
      $state_command = '<show_state what="all,orders' .  (($this->enable_monitoring) ? ',remote_schedulers' : '') . '"/>';
    } else {
      $state_command = '<show_state what="all' .  (($this->enable_monitoring) ? ',remote_schedulers' : '') . '"/>';
    }
		
		if(isset($_POST['btn_job_queue_task_store_x']) && isset($_POST['btn_job_queue_task_store_y']) && !$action){
			$action 			= 'job_queue_task_store';
			$this->action = $action;
		}
		
    switch ( $action ) {
    	case  'kill_queue_task'								:
      case  'kill_task'                     : $cmd = '<kill_task job="' . $job . '" id="' . $item . '"/>';
                                              break;
      case  'kill_task_immediately'         : $cmd = '<kill_task job="' . $job . '" id="' . $item . '" immediately="yes"/>';
                                              break;
      case  'job_description'               : $cmd = $state_command;
                                              break;
     #case  'job_queue'                     : $cmd = '<show_state job="' . $job . '" what="all"/>';
      case  'job_queue'                     : $cmd = '<show_state what="all"/>';
                                              break;
     #case  'job_queue_task'                : $cmd = '<show_state job="' . $job . '" what="all"/>';
      case  'job_queue_task'                : $cmd = '<show_state what="all"/>';
                                              break;
			case  'job_queue_task_store'         	: 
																							// entfernen
																							if( $this->sh > 0 && $send_command )  {
      																					fputs( $this->sh, '<?xml version="1.0" encoding="iso-8859-1"?><kill_task job="' . $job . '" id="' . $item . '"/>');
      																					$this->get_answer();
      																					// und starten
																								$cmd = '<start_job job="' . $job . '"';
                                              	if (isset($_REQUEST['queue_start_at'])) { 
                                              		$cmd .= ' at="'.$this->date_as_string_convert($_REQUEST['queue_start_at'],'Y-m-d H:i:s').'"'; 
                                              	}
                                              	$cmd .= '>';
                                              	$this->get_queue_params($job);
                                              	$cmd .= '<params>';
                                              	for($i=0; $i<count($this->job_param_names); $i++) {
                                              	  if ( $this->job_param_names[$i] != '' ) {
                                              	    $cmd .= '<param name="' . $this->job_param_names[$i] . '" value="' . $this->to_value($this->job_param_values[$i]) . '"/>';
                                              	  }      
                                              	}
																				      	$cmd .= '</params></start_job>';
																				   	}
																							else{
																								$cmd = $state_command;
																							}
																							break;
                                              
      case  'job_task_log'                  : $cmd = '<show_task id="' . $item . '" what="log"/>';
                                              break;
      case  'job_history'                   : $cmd = '<show_history job="' . $job . '"' . $send_item . $send_range . '/>';
                                              break;
      case  'job_history_log'               : $cmd = '<show_history job="' . $job . '"' . $send_item . $send_range . ' what="all"/>';
                                              break;
      case  'job_history_remove'            : $cmd = $state_command;
                                              break;
      case  'job_params_show'               : $this->show_params( $job );
                                              $send_command  = 0;
                                              break;
      case  'job_params_store'              : $this->store_params( $job );
                                              $cmd = $state_command;
                                              break;
      case  'job_params_delete'             : $this->delete_params( $job );
                                              $cmd = $state_command;
                                              break;
      case  'job_chains'                    : $cmd = '<show_job_chains what="standard"/>';
                                              $state_command = ''; 
                                              $no_state = 1;
                                              break;
      case  'job_orders'                    : $cmd = '<show_job job="' . $job . '" what="all,orders"/>';
                                              $this->enable_job_orders = 1;
                                              break;
      case  'job_query'                     : 
                                              $cmd = $state_command;
                                              break;
      case  'spooler_query'                 : 
                                              $cmd = $state_command;
                                              break;
      case  'spooler_pause'                 : $cmd = '<modify_spooler cmd="pause"/>';
                                              break;
      case  'spooler_continue'              : $cmd = '<modify_spooler cmd="continue"/>';
                                              break;
      case  'spooler_stop'                  : $cmd = '<modify_spooler cmd="stop"/>';
                                              break;
      case  'spooler_init'                  : $cmd = '<modify_spooler cmd="reload"/>';
                                              break;
      case  'spooler_terminate'             : $cmd = '<modify_spooler cmd="terminate"/>';
                                              $no_state = 1;
                                              break;
      case  'spooler_terminate_restart'     : $cmd = '<modify_spooler cmd="terminate_and_restart"/>';
                                              $no_state = 1;
                                              break;
      case  'spooler_restart'               : $cmd = '<modify_spooler cmd="let_run_terminate_and_restart"/>';
                                              $no_state = 1;
                                              break;
      case  'spooler_abort'                 : $cmd = '<modify_spooler cmd="abort_immediately"/>';
                                              $no_state = 1;
                                              break;
      case  'spooler_abort_restart'         : $cmd = '<modify_spooler cmd="abort_immediately_and_restart"/>';
                                              $no_state = 1;
                                              break;
      case  'job_suspend'                   : $cmd = '<modify_job job="' . $job . '" cmd="suspend"/>';
                                              break;
      case  'job_continue'                  : $cmd = '<modify_job job="' . $job . '" cmd="continue"/>';
                                              break;
      case  'job_stop'                      : $cmd = '<modify_job job="' . $job . '" cmd="stop"/>';
                                              break;
      case  'job_unstop'                    : $cmd = '<modify_job job="' . $job . '" cmd="unstop"/>';
                                              break;
      case  'job_start'                     : $cmd = '<start_job job="' . $job . '"';
                                            # if (isset($_REQUEST['start_at'])) { $cmd .= ' at="' . $_REQUEST['start_at'] . '"'; $_REQUEST['start_at'] = ''; }
                                              if (isset($_REQUEST['start_at'])) { $cmd .= ' at="' . $this->date_as_string_convert($_REQUEST['start_at'], 'Y-m-d H:i:s') . '"'; $_REQUEST['start_at'] = ''; }
                                              $cmd .= '>';
                                              $this->get_params( $job );
                                              $cmd .= '<params>';
                                              for($i=0; $i<count($this->job_param_names); $i++) {
                                                if ( $this->job_param_names[$i] != '' ) {
                                                  $cmd .= '<param name="' . $this->job_param_names[$i] . '" value="' . $this->to_value($this->job_param_values[$i]) . '"/>';
                                                }      
                                              }
                                              if ( isset( $this->selected_jobs[$job] ) ) {
                                                foreach( $this->selected_jobs[$job]->params as $name => $param ) {
                                                  if ( $param->name != '' ) {
                                                    $cmd .= '<param name="' . $name . '" value="' . $this->to_value($param->value) . '"/>';
                                                  }
                                                }
                                              }
                                              $cmd .= '</params></start_job>';
                                              
                                              break;
      case  'job_end'                       : $cmd = '<modify_job job="' . $job . '" cmd="end"/>';
                                              break;
      case  'job_reread'                    : $cmd = '<modify_job job="' . $job . '" cmd="reread"/>';
                                              break;
      case  'spooler_process'               : $found = 0;
                                              $job_name = 'job_' . time();
                                              $cmd  = '<!DOCTYPE spooler SYSTEM "scheduler.dtd">';
                                              $cmd .= '<spooler>';
                                              $cmd .= '  <command>';
                                              if ( $this->process_thread != "" && $this->process_file != "" ) {
                                                $found = 1;
                                                $cmd .= '    <add_jobs thread="' . $this->process_thread . '">';
                                                $cmd .= '      <job name="' . $job_name . '" temporary="yes">';
                                                $cmd .= '        <process file     = "' . $this->process_file . '"';
                                                $cmd .= '                 param    = "' . $this->process_param . '"';
                                                $cmd .= '                 log_file = "' . $this->process_log . '"/>';
                                                $cmd .= '      </job>';
                                                $cmd .= '    </add_jobs>';
                                                $cmd .= '<start_job job="' . $job_name . '"';
                                                if (isset($_REQUEST['start_at'])) { $cmd .= ' at="' . $_REQUEST['start_at'] . '"'; $_REQUEST['start_at'] = ''; }
                                                $cmd .= '/>';
                                              }
                                              $cmd .= '  </command>';
                                              $cmd .= '</spooler>';
                                              if ( ! $found ) { return 0; }
                                              break;
      default                               : $cmd = $state_command;
    }

    if ( $this->sh > 0 && $send_command )  {
      $this->state = 1;
      fputs( $this->sh, '<?xml version="1.0" encoding="iso-8859-1"?>' . $cmd );
      $this->get_answer();
      if ($this->error()) return 0;

      $this->get_answer_elements();
      if ( !$no_state && $cmd != $state_command ) {
      	fputs( $this->sh, $state_command );
        $this->get_answer();
      if ($this->error()) return 0;
        $this->get_answer_elements();
      } 
    }

    return 1;
  }


  /**
  * Scheduler-Status erläutern
  *
  * @param    string   $state Status im Scheduler
  * @return   string   Statuserläuterung
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_state( $state ) {

    if ( isset($this->scheduler_states[$state]) ) {
      return $this->scheduler_states[$state];
    } else {
      return $state;
    }
  }
  

  /**
  * Job für Darstellung in Web-Oberfläche auswählen
  *
  * @param    string   $job Name des Jobs
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function select_task( $job, $type='html' ) {

    $this->selected_jobs_order[++$this->selected_jobs_order_index] = $job;
    $this->selected_job_index = $job;
    $this->selected_jobs[$this->selected_job_index] = new SOS_Scheduler_Task( $job, $type );
    $this->selected_jobs[$this->selected_job_index]->site  = $this->site;
    $this->selected_jobs[$this->selected_job_index]->set_window_size( $this->task_window_width, $this->task_window_height );
    $this->selected_jobs[$this->selected_job_index]->set_timer( $this->task_timer );
  }
  

  /**
  * Parameter eines selektierten Jobs vereinbaren
  *
  * @param    string   $prompt Eingabeaufforderung für Job-Parameter
  * @param    string   $name   Name des Parameters
  * @param    string   $value  Wert des Parameters
  * @param    integer  $type   Typ (0=Text, 1=Listbox, 2=CheckBox, 3=hidden)
  * @param    string   $default Voreinstellung des Parameters
  * @param    integer  $input_type Typ für Eingabeprüfung (0=Text, 1=Numerisch, 2=Datum)
  * @param    boolean  $force  Eingabe erforderlich, falls True
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_param( $prompt, $name, $value, $type='0', $default='', $input_type=0, $force=0 ) {
  
    $this->selected_param_index = $this->selected_jobs[$this->selected_job_index]->set_param( $prompt, $name, $value, $type, $default, $input_type, $force );
  }
  

  /**
  * Attribut eines Parameters für einen selektierten Job vereinbaren
  *
  * @param    string   $name   Name des Attributs: type, default, input_type, force
  * @param    string   $value  Wert des Attributs: type:listbox,checkbox default:beliebig input_type:number,date force:true,false
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_param_attribute( $name, $value ) {

    $this->selected_jobs[$this->selected_job_index]->set_param_attribute( $name, $value );
  }


  /**
  * Fenstergröße für einen selektierten Job vereinbaren
  *
  * @param    string   $width  Breite
  * @param    string   $height Höhe
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_window_size( $width='', $height='' ) {

    $this->selected_jobs[$this->selected_job_index]->set_window_size( $width, $height );
  }
  

  /**
  * Code für Dimensionierung der Fenstergröße eines selektierten Jobs erzeugen
  *
  * @param    string   $job Name des Jobs
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_task_window_size( $job ) {
  
    if ( $this->enable_resize && isset($this->selected_jobs[$job]) ) {
      if ( $this->selected_jobs[$job]->window_width > 0 && $this->selected_jobs[$job]->window_height > 0 ) {
        $this->show_html('<script type="text/javascript" language="JavaScript">');
        $this->show_html('  window.resizeTo( ' . $this->selected_jobs[$job]->window_width . ', ' . $this->selected_jobs[$job]->window_height . ');');
        $this->show_html('</script>');
      }
    }
  }


  /**
  * Timer für Aktualisierung eines Job-Fensters vereinbaren
  *
  * @param    string   $timer Zeitvorgabe für Aktualisierung
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_timer( $timer='' ) {

    $this->selected_jobs[$this->selected_job_index]->set_timer( $timer );
  }


  /**
  * Code für Aktualisierung eines Job-Fensters erzeugen
  *
  * @param    string   $job Name des Jobs
  * @param    string   $state Job-Status
  * @param    string   $anchor Anker-Element für HTML-Link
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_task_timer( $job, $state, $anchor='' ) {

    if ( isset($this->selected_jobs[$job]) ) {
      if ( $this->selected_jobs[$job]->timer > 0 ) {
        if ( isset($this->task_states[$state]) ) {
          if ( $this->task_states[$state][1] > 0 ) {
            $this->site_con           = ( strpos($this->site, '?') > 0 ) ? '&' : '?';
            if ( !$this->session_use_trans_sid && $this->session_var != '' ) {
              $query_session = '&' . $this->session_var . '=' . $this->session_id;
            } else {
              $query_session = '';
            }
            $this->show_html('<script type="text/javascript" language="JavaScript">');
            $this->show_html('  var sos_timer_' . $job . ';');
            $this->show_html('  sos_timer_' . $job . ' = setTimeout( "document.sos_spooler_form_' . $job . '.action=\'' . $this->site . $this->site_con . 'action=spooler_status&spooler_host=' . $this->host . '&spooler_port=' . $this->port . $anchor . '\';document.sos_spooler_form_' . $job . '.submit()", ' . $this->selected_jobs[$job]->timer . ');');
            $this->show_html('</script>');
          }
        }
      }
    }
  }


  /**
  * Job-Status erläutern
  *
  * @param    string   $state Status im Spooler
  * @return   string   Statuserläuterung
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_task_state( $state ) {

    if ( isset($this->task_states[$state]) ) {
      return $this->task_states[$state][0];
    } else {
      return $state;
    }
  }
  

  /**
  * Startgrund eines Jobs erläutern
  *
  * @param    string   $cause Startgrund im Spooler
  * @return   string   Erläuterung des Startgrunds
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function get_task_cause( $cause ) {
  
    if ( isset($this->task_causes[$cause]) ) {
      return $this->task_causes[$cause][0];
    }
  }


  /**
  * Historienparameter vereinbaren
  *
  * @param    string   $prompt Label für Anzeige in Historie
  * @param    string   $name Feldname aus Historientabelle des Spoolers
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_history( $prompt, $name ) {
  
    $this->selected_jobs[$this->selected_job_index]->set_history( $prompt, $name );
  }

  
  /**
  * Historienparameter vereinbaren
  *
  * @param    string   $prompt Label für Anzeige in Historie
  * @param    string   $name Feldname aus Historientabelle des Spoolers
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function set_task_attribute( $prompt, $name ) {
  
    $this->selected_jobs[$this->selected_job_index]->set_attribute( $prompt, $name );
  }

  
  /**
  * HTML-Code ausgeben
  *
  * @param    string   $htmlstr beliebiger HTML-Code für Ausgabe
  * @param    string   $nl New Line Zeichenfolge 
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_html( $htmlstr, $nl="\n" ) {

    print $htmlstr . $nl;
  }


  /**
  * Redirektion in ein anderes Fenster
  *
  * @param    string   $location URL auf die eine Redirektion stattfindet, bei 'none' wird das aktuelle Fenster geschlossen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function show_location( $location ) {
    
    if ( $location == 'none' ) {
      $this->show_html('<script language="JavaScript" type="text/javascript">');
      $this->show_html('  daddy = window.self;');
      $this->show_html('  daddy.opener = window.self;');
      $this->show_html('  daddy.close();' );
      $this->show_html('</script>');
    } else {
      header( 'Location: ' . $location );
    }
  }


  /**
  * Zeichenfolge in Minuskeln ausgeben
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function style_string( $str ) {
    return '<font class="' . $this->style_font_minus . '">' . substr($str,0,1) . '</font><font class="' . $this->style_font_minus . '">' . substr($str,1) . '</font>';
  }


  /**
  * Zeichenfolge in Kapitälchen ausgeben
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function style_capitalize_string( $str ) {
    return '<font class="' . $this->style_font_majus . '">' . substr($str,0,1) . '</font><font class="' . $this->style_font_minus . '">' . substr($str,1) . '</font>';
  }


  /**
  * Wortfolge in Kapitälchen ausgeben
  *
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function style_capitalize_title( $title ) {
  
    $arr_str = explode(' ', $title );
    $title_str = '';
    for($i=0;$i<count($arr_str);$i++) {
       $title_str .= $this->style_capitalize_string($arr_str[$i]) . ' ';
    }
    return $title_str;
  }


  /**
  * Datum, Zeitstempel konvertieren
  *
  * @param    string   $datestr Zeichenfolge mit Datum oder Zeitstempel
  * @param    string   $format Formatstring, z.B. dd.mm.yyyy HH:MM:SS
  * @return   string   formatierter Zeitstempel
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function date_as_string_convert( $datestr, $format ) {

    if ( $datestr != '' ) {
      $search_formats  = array("dd.", ".mm", ".yyyy", ".yy", "hh:", "h:", ":mm", ":m", ":ss", ":s");
      $replace_formats = array("d.",  ".m",  ".Y",    ".y",  "H:",  "G:", ":i",  ":i", ":s",  ":s");
      if( strpos( $datestr, '.' ) > 0 ) {
        $date = strtok( $datestr, ' ' );
        $time = strtok( ' ' );
        if( substr( $date, -1 ) == '.' ) { $date = substr( $date, 0, -1 ); }
        $datearr = explode( '.', $date );
        $datestr = $datearr[1].'/'.$datearr[0];
        if( count( $datearr ) == 3 ) { $datestr .= '/'.$datearr[2]; }
        $datestr .= ' '.$time;
      }
      $datetime = strtotime( $datestr );

      if ( $datetime > 0 ) {
        return date( str_replace($search_formats, $replace_formats, $format), $datetime );
      } else {
        return $datestr;
      }
    } else {
      return $this->date_as_null ? null : '00.00.00';
    }
  }


  /**
  * Datum, Zeitstempel konvertieren
  *
  * @param    string   $datestr Zeichenfolge mit Datum oder Zeitstempel
  * @param    string   $format Formatstring, z.B. dd.mm.yyyy HH:MM:SS
  * @return   string   formatierter Zeitstempel
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function date_as_string( $datestr, $format ) {

    $search_formats  = array("dd.", ".mm", ".yyyy", ".yy", "hh:", "h:", ":mm", ":m", ":ss", ":s");
    $replace_formats = array("d.",  ".m",  ".Y",    ".y",  "H:",  "G:", ":i",  ":i", ":s",  ":s");
    $datetime = strtotime( str_replace('.', '/', $datestr) );
    if ( $datetime > 0 ) {
      return date( str_replace($search_formats, $replace_formats, strtolower($format)), $datetime );
    } else {
      return '00.00.00';
    }
  }
  

  /**
  * Datum formatieren
  *
  * @param    string   $datestr Zeichenfolge mit Datum
  * @param    string   $date_format Formatstring, z.B. dd.mm.yyyy
  * @return   string   formatiertes Datum
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_date( $datestr, $date_format='' ) {
    
    if ( $datestr != '' ) {
      if (strlen($datestr) >19 ) { $datestr = substr($datestr,0,18); }
      if ( $date_format == '' ) { $date_format = $this->date_format; }
      return $this->date_as_string( $datestr, $date_format );
    } else {
      return '00.00.00';
    }
  }


  /**
  * Zeitstempel formatieren
  *
  * @param    string   $datestr Zeichenfolge mit Datum
  * @param    string   $datetime_format Formatstring, z.B. HH:MM:SS
  * @return   string   formatierter Zeitstempel
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_datetime( $datestr, $datetime_format='' ) {

    if ( $datestr != '' ) {
      if (strlen($datestr) >19 ) { $datestr = substr($datestr,0,18); }
      if ( $datetime_format == '' ) { $datetime_format = $this->datetime_format; }
      return $this->date_as_string( $datestr, $datetime_format );
    } else {
      return '00.00.00';
    }
  }


  /**
  * Datum in Zeitstempel konvertieren
  *
  * @param    string   $datestr Zeichenfolge mit Datum
  * @return   integer  Zeitstempel
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_timestamp( $datestr ) {

    return strtotime($datestr);
  }
  
  
  /**
  * Zahl mit Nachkommastellen konvertieren
  *
  * @param    string   $numberstr Zeichenfolge mit Zahl
  * @param    integer  $decimals Anzahl Nachkommastellen
  * @return   double   Zahl mit Nachkommastellen
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_decimal( $numberstr, $decimals=2 ) {
  
    $decimal = bcadd( $numberstr, 0, $decimals );
    $decimal = str_replace(',', ':', $decimal);
    $decimal = str_replace('.', ',', $decimal);
    return     str_replace(':', '.', $decimal);
  }


  /**
  * Entfernt Kennwörter vor der Ausgabe in den Browser
  *
  * @param    string   $displaystr Zeichenfolge, die ein Kennwort in der Form -pass= enthält
  * @return   string   Zeichenfolge mit ? anstelle eines Kennworts
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_save_display( $displaystr ) {

    $str_return = $displaystr;
    
    $str_start = strpos( $str_return, '-pass' );
    if ( $str_start ) {
      $str_start  = strpos( $str_return, '=', $str_start+1 );
      $str_length = strpos( $str_return, ' ', $str_start+1 );
      if ( $str_length === false ) {
        $str_return = substr( $str_return, 0, $str_start+1 ) . '?';
      } else {
        $str_return = substr( $str_return, 0, $str_start+1 ) . '?' . substr( $str_return, $str_length);
      }
    }
    return $str_return;
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
  

  /**
  * Konvertieren von &lt; und &gt; in Ersatzdarstellung
  *
  * @param    string   $htmlstr Zeichenfolge
  * @return   string   Zeichenfolge für HTML
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function to_value( $htmlstr ) {
  
    if ( $htmlstr != '' ) {
      $valstr = str_replace('<', '&lt;', $htmlstr);     
      return str_replace('>', '&gt;', $valstr);     
    }
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

  function nop( $param ) {
    
    return $param;
  }


  /**
  * quote - Quotierungszeichen hinzufügen
  * 
  * @param    string $param beliebiger Parameter
  * @return   string eine Referenz auf denselben Parameter
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/10/20
  */

  function quote( $param ) {
    
    return addslashes($param);
  }
  
  /**
  * unquote - Quotierungszeichen entfernen
  * 
  * @param    string $param beliebiger Parameter
  * @return   string eine Referenz auf denselben Parameter
  * @access   private
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/10/20
  */

  function unquote( $param ) {
    
    return stripslashes($param);
  }

} // end of class SOS_Scheduler


/**
* Job im Document Factory Scheduler
*
* Job-Parameter und Historienparameter
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/07/12
*
* @access       private
* @package      SCHEDULER
*/

class SOS_Scheduler_Task {

  var $name               = '';
  var $type               = 'html';
  var $params             = array();
  var $param_index        = -1;
  var $history_entries    = array();
  
  var $window_width       = 0;
  var $window_height      = 0;

  var $timer              = 10000;
  var $site               = '';

  var $enable_timer       = 0;
  var $enable_start_time  = 0;


  function SOS_Scheduler_Task( $name='', $type='html' ) {
  
    if ( $name != '' ) { $this->name = $name; }
    if ( $type != '' ) { $this->type = $type; }
  }


  function set_param( $prompt, $name, $value, $type='0', $default='', $input_type='0', $force='0' ) {
  
    $this->param_index = $name;
    $this->params[$this->param_index] = new SOS_Scheduler_Task_Param( $prompt, $name, $value, $type, $default, $input_type, $force );
  }


  function set_param_attribute( $name, $value ) {
  
   $this->params[$this->param_index]->set_attribute( $name, $value );
  }


  function set_window_size( $width='', $height='' ) {
  
    if ( $width  != '' ) { $this->window_width  = $width;  }
    if ( $height != '' ) { $this->window_height = $height; }
  }


  function set_timer( $timer='' ) {

    if ( $timer != '' ) { $this->timer = $timer; }
  }


  function set_history( $prompt, $name ) {

    $this->history_entries[$name] = new SOS_Scheduler_Task_History( $prompt, $name );
  }


  function set_attribute( $name, $value ) {

    switch( strtolower($name) ) {
      case 'enable_start_time'  : $this->enable_start_time = $value; break;
      case 'enable_timer'       : $this->enable_timer      = $value; break;
    }
  }

} // end of class SOS_Scheduler_Task


/**
* Historieneintrag eines Jobs
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/07/12
*
* @access       private
* @package      SCHEDULER
*/

class SOS_Scheduler_Task_History {
  
  var $prompt      = '';
  var $name        = '';

  
  function SOS_Scheduler_Task_History( $prompt='', $name='' ) {
    
    if ( $name   != '' ) { $this->name   = $name; }
    if ( $prompt != '' ) { $this->prompt = $prompt; }
  }

} // end of class SOS_Scheduler_Task_History

  
/**
* Task-Parameter im Document Factory Scheduler
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2002/07/12
*
* @access       private
* @package      SCHEDULER
*/

class SOS_Scheduler_Task_Param {

  var $prompt             = '';
  var $name               = '';
  var $value              = '';
  var $default            = '';
  var $size               = 20;
  var $type               = 0;  // 0=Text, 1=Listbox, 2=CheckBox, 3=hidden
  var $types              = Array ( 'text' => 0, 'listbox' => 1, 'checkbox' => 2, 'hidden' => 3 );
  var $input_type         = 0;  // 0=char, 1=number,  2=date
  var $input_types        = array( 'char' => 0, 'number' => 1, 'date' => 2 );
  var $force              = 0;  // Mussfeld
  
  function SOS_Scheduler_Task_Param( $prompt='', $name='', $value='', $type='0', $default='', $input_type='0', $force='0' ) {

    if ( $prompt != '' )      { $this->prompt     = $prompt; }
    if ( $name != '' )        { $this->name       = $name; }
    if ( $value != '' )       { $this->value      = $value; }
    if ( $default != '' )     { $this->default    = $default; }
    if ( $type != '' )        { $this->type       = $type; }
    if ( $input_type != '' )  { $this->input_type = $input_type; }
    if ( $force != '' )       { $this->force      = $force; }
  }


  function set_attribute( $name, $value ) {

    switch( strtolower($name) ) {
      case 'prompt'     : $this->prompt     = $value; break;
      case 'name'       : $this->name       = $value; break;
      case 'value'      : $this->value      = $value; break;
      case 'default'    : $this->default    = $value; break;
      case 'size'       : $this->size       = $value; break;
      case 'type'       : if (!is_numeric($value)) {
                            if ( isset($this->types[$value]) ) { $value = $this->types[$value]; }
                          }
                          $this->type       = $value; break;
      case 'force'      : $this->force      = $value; break;
      case 'input_type' : if (!is_numeric($value)) {
                            if ( isset($this->input_types[$value]) ) { $value = $this->input_types[$value]; }
                          }
                          $this->input_type = $value; break;
    }
  }
  
} // end of class SOS_Scheduler_Task_Param

?>