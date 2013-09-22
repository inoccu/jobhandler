<?php

if ( !class_exists('SOS_Class') )                             { require( 'class/sos_class.inc.php'); }
if ( !class_exists('SOS_Scheduler_Command') )                 { require( 'sos_scheduler_command.inc.php'); }

/**
* base class of the Job Scheduler API
*
* abstract class for implementations of jobs, job chains, orders
*
* @copyright    SOS GmbH
* @author       Andreas Püschel <andreas.pueschel@sos-berlin.com>
* @since        1.0-2005/08/17
*
* @access       public
* @package      SCHEDULER
*/

class SOS_Scheduler_Object extends SOS_Class {

  /** @access public */
  
  /** implicit object for scheduler commands and return codes */
  var $interface                  = null;
  
  /** host for which the job scheduler is operated */
  var $host                       = 'localhost';
  
  /** port on which the job scheduler listens */
  var $port                       = '4444';
  
  /** timeout for connections to the job scheduler */
  var $timeout                    = 15;
  
  
  /** @access private */


  /**
  * constructor
  *
  * @param    string $host host (name or IP) for which the job scheduler is operated
  * @param    integer $port host (name or IP) for which the job scheduler is operated
  * @param    string $host host (name or IP) for which the job scheduler is operated
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function SOS_Scheduler_Object( $host=null, $port=null, $timeout=null ) {

    if (!defined('SOS_LANG')) { define('SOS_LANG', 'de'); }    
    
    if ( $host != null )                  { $this->host = $host; }
    if ( $port != null )                  { $this->port = $port; }
    if ( $timeout != null )               { $this->timeout = $timeout; }
  }


  /**
  * destructor
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function destruct() {

    return 1;    
  }
  

  /**
  * submit command to job scheduler
  *
  * @param    string  $command one of the commands: pause, continue, stop, reload, terminate_and_restart, let_run_terminate_and_restart, abort_immediately, abort_immediately_and_restart
  * @return   boolean error_status
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function scheduler_command( $command ) {

    $this->interface = new SOS_Scheduler_Command($this->host, $this->port, $this->timeout);
    $this->interface->log_level = $this->log_level;
    $this->interface->debug_level = $this->debug_level;
    if (!$this->interface->connect()) { $this->set_error( $this->interface->get_error(), __FILE__, __LINE__ ); return 0; }

    $this->interface->command('<modify_spooler cmd="' . $command . '""/>');
    if ($this->interface->get_answer_error()) { $this->set_error( $this->interface->get_error(), __FILE__, __LINE__); }

    $this->interface->disconnect();
    
    return !$this->error();
  }


  /**
  * create instance of an job scheduler API object (job, job chain, order) 
  *
  * @param    string   $class class for object (SOS_Scheduler_Job, SOS_Scheduler_Job_Chain, SOS_Scheduler_Order)
  * @param    string   $include_path path to include the class
  * @param    string   $extension file name extension for API classes
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
  
  function &get_instance($class, $include_path='scheduler/', $extension='.inc.php') {

    if ( !class_exists($class) ) { include( $include_path . strtolower($class) . $extension ); }

    $object = new $class;
    
    $object->log_level                  = $this->log_level;
    $object->debug_level                = $this->debug_level;
    
    $object->interface                  = $this->interface;
    
    $object->host                       = $this->host;
    $object->port                       = $this->port;
    $object->timeout                    = $this->timeout;
    
    return $object;    
  }



  /**
  * add parameter
  *
  * @param    string  $name parameter name
  * @param    string  $value parameter value
  * @return   boolean error status
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/08/28
  */
  
  function add_parameter($name, $value) {

    if ($this->params != null) $this->params->addParam($name,$value);
    return !$this->error();
  }
  



  /**
  * remove parameter
  *
  * @param    string  $name parameter name
  * @return   boolean error status
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/08/28
  */
  
  function remove_parameter($name) {

    if ($this->params != null) $this->params->params[$name] = null;
    return !$this->error();
  }
  
  

} // end of class SOS_Scheduler_Object

  function addAttribute($an,$av){
  $s='';	
   if ($av != '') {
   	$s .= ' ' . $an . '="' . $av . '"';
   }
   return $s;
 	}
 	
?>