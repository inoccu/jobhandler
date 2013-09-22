<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_runtime.inc.php'); 
require_once( 'sos_scheduler_order_commands.inc.php');

/**
* Instance of a Order Command-Launcher in the Job Scheduler
* An object of this class handles the connection to the Job Scheduler using given host and port
* You can create an object with  
*
*    $order_launcher = $scheduler->get_instance('SOS_Scheduler_OrderCommand_Launcher');
*
* The launcher provides the methode execute() to execute a command which is a instance of one these classes
*
* - SOS_Scheduler_Command_Add_Order
* - SOS_Scheduler_Command_Modify_Order
* - SOS_Scheduler_Command_Remove_Order
* 
* The launcher also provides the methods to get a instance of these classes (see below)
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_OrderCommand_Launcher extends SOS_Scheduler_Object {

 /** Instance of new SOS_Scheduler_Command
  *
  * will automatically be created when using the method execute()
  */
  var $command               = null;
  

  /**
  * constructor
  *
  * @param    string $host host (name or IP) for which the job scheduler is operated
  * @param    integer $port port for which the job scheduler is operated
  * @param    integer $timeout timeout for connection to the job scheduler
  * @access   public
  * @author   Andreas Püschel <uwe.risse@sos-berlin.com>
  * @version  1.0-2002/07/07
  */

  function SOS_Scheduler_OrderCommand_Launcher( $host=null, $port=null, $timeout=null ) {

    if (!defined('SOS_LANG')) { define('SOS_LANG', 'de'); }    
   
    if ( $host != null )                  { $this->host = $host; }
    if ( $port != null )                  { $this->port = $port; }
    if ( $timeout != null )               { $this->timeout = $timeout; }
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Andreas Püschel <uwe.risse@sos-berlin.com>
  * @version  1.0-2002/07/07
  */
  
  function destruct() {

    return 1;    
  }
  
 /**
  * Connects to scheduler and executes a command which is an instance of
  *
  * - SOS_Scheduler_Command_Add_Order
  * - SOS_Scheduler_Command_Remove_Order
  * - SOS_Scheduler_Command_Modify_Order
  * 
  * @param    SOS_Scheduler_Command_Add_Order  command
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  *  
  */  
  function execute($c){
  	if ($this->command == null){
       $this->command = new SOS_Scheduler_Command($this->host, $this->port, $this->timeout);
       $this->command->log_level = $this->log_level;
       $this->command->debug_level = $this->debug_level;
    }
    if (!$this->command->connect()) { $this->set_error($this->command->get_error(), __FILE__, __LINE__ ); return 0; }
    
    $this->command->command($c->asString());  
 
    if ($this->command->get_answer_error()) { $this->set_error( $this->command->get_error(), __FILE__, __LINE__ ); }
    $this->command->disconnect();
    return !$this->error();
  }
  

  
   /**
  * remove the order from the job scheduler
  *
  * @param    string  $job_chain name of job chain (
  * @param    string  $id order identifier
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function remove( $job_chain, $id) {

    $this->debug(3, 'remove: jobchain='.$job_chain.  '  order_id=' . $id);
    $order = $this->remove_order($job_chain,$id);
    $this->execute($order);
    $this->command->reset_error();
    $this->reset_error();
    
  }


  /**
  * submits the order to the Job Scheduler
  *
  * @param    string  $job_chain name of job chain 
  * @param    string  $id order identifier
  * @param    string  $status status of the order in the jobchain
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function submit( $job_chain, $id, $state,$at='now' ) {
    $this->debug(3, 'submit: id=' . $id . ' job_chain=' . $job_chain);
   
   $order = $this->add_order($job_chain,$state);
   $order->id=$id;
   $order->at=$at;
   $order->replace="yes";
  
   if (!$this->execute($order)) { echo 'error occurred adding order: ' . $this->get_error(); exit; } 
 
   echo $order -> asString();
  echo "<br>";
   return !$this->error();
  }

  /**
  * creates an instance of SOS_Scheduler_Command_Add_Order  returns it
  *
  * @param    string job_chain
  * @param    string state
  * @return   SOS_Scheduler_Command_Add_Order add_order
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
 function add_order($job_chain,$state) {
 	  $c = new SOS_Scheduler_Command_Add_Order($job_chain);
 	  $c->state = $state;
    return $c;      
  }
  
    /**
  * creates an instance of SOS_Scheduler_Command_Order  returns it
  *
  * @param    string job_chain
  * @param    string state
  * @return   SOS_Scheduler_Command_Order add_order
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
 function order($job_chain,$state) {
 	  $c = new SOS_Scheduler_Command_Order($job_chain);
 	  $c->state = $state;
    return $c;    
  }  
 
 
   /**
  * creates an instance of SOS_Scheduler_Command_Modify_Order and returns it
  *
  * @param    string job_chain
  * @param    string id of the order
  * @return   SOS_Scheduler_Command_modify_Order add_order
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
 function modify_order($job_chain,$id) {
 	  $c = new SOS_Scheduler_Command_Modify_Order();
 	  $c->job_chain = $job_chain;
 	  $c->id = $id;
 	  return $c;    
  }  
  
  /**
  * creates an instance of SOS_Scheduler_Command_Remove_Order  returns it
  *
  * @param    string job_chain
  * @param    string id of the order
  * @return   SOS_Scheduler_Command_Remove_Order add_order
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */  
  
  
 
 function remove_order($job_chain,$id) {
 	  $c = new SOS_Scheduler_Command_Remove_Order();
 	  $c->job_chain = $job_chain;
 	  $c->id = $id; 	  
 	  return $c;    
  }  
 
  
 
    
} // end of class SOS_Scheduler_OrderCommand_Launcher

?>