<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_command_modifyhotfolder.inc.php'); 
 

/**
* Instance of a hotfolder Command-Launcher in the Job Scheduler
* An object of this class handles the connection to the Job Scheduler using given host and port
* You can create an object with  
*
*    $hotfolder_launcher = new SOS_Scheduler_HotFolder_Launcher('localhost', '4444');
*
* The launcher provides the methode store() to store an object (Job, Job_chain, Order)
*
*
* Example to remove a job
* 
* <pre>
*    $job_command = new SOS_Scheduler_HotFolder_Launcher('localhost','4444');
*    $job_command->store($job,"./");
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_HotFolder_Launcher extends SOS_Scheduler_Object {


  

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
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function SOS_Scheduler_JobCommand_Launcher( $host=null, $port=null, $timeout=null ) {

    if (!defined('SOS_LANG')) { define('SOS_LANG', 'de'); }    
    
    if ( $host != null )                  { $this->host = $host; }
    if ( $port != null )                  { $this->port = $port; }
    if ( $timeout != null )               { $this->timeout = $timeout; }
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
  
  function destruct() {

    return 1;    
  }
  
  
 
  function execute($c){
  	if ($this->command == null){
       $this->command = new SOS_Scheduler_Command($this->host, $this->port, $this->timeout);
       $this->command->log_level = $this->log_level;
       $this->command->debug_level = $this->debug_level;
    }
    if (!$this->command->connect()) { $this->set_error( $this->command->get_error(), __FILE__, __LINE__ ); return 0; }
    
    
    $this->command->command($c->asString());  
   
    if ($this->command->get_answer_error()) { $this->set_error( $this->command->get_error(), __FILE__, __LINE__ ); }
    $this->command->answer = "";
    $this->command->disconnect();
 
    return !$this->error();
  }

 /**
  * creates an instance of SOS_Scheduler_Command_ModifyHotFolder execute and returns it
  *
  * @param    SOS_Scheduler_Job the job.
  * @return   SOS_Scheduler_Command_ModifyHotFolder 
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

 function store($job,$folder) {
 	  $c = new SOS_Scheduler_Command_ModifyHotFolder($job,$folder);
    $this->execute($c);
    return $c;    
  }  
 
          

} // end of class SOS_Scheduler_HotFolder_Launcher

?>