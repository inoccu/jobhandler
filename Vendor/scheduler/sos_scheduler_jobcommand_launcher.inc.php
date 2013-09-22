<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_job_commands.inc.php'); 


/**
* Instance of a Job Command-Launcher in the Job Scheduler
* An object of this class handles the connection to the Job Scheduler using given host and port
* You can create an object with  
*
*    $job_launcher = new SOS_Scheduler_JobCommand_Launcher('localhost', '4444');
*
* The launcher provides the methode execute() to execute a command which is a instance of one these classes
*
* - SOS_Scheduler_Command_Add_Jobs 
* - SOS_Scheduler_Command_Start_Job
* - SOS_Scheduler_Command_Modify_Job
* 
* The launcher also provides the methods to get a instance of these classes (see below in Method Summary)
* Example to remove a job
* 
* <pre>
*    $job_command = new SOS_Scheduler_JobCommand_Launcher('localhost','4444');
*    $job_command->remove($job->name);
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_JobCommand_Launcher extends SOS_Scheduler_Object {


  /** Instance of SOS_Scheduler_Job
  */
  var $job                   = null;

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
  
  /**
  * removes a job from the job scheduler
  *
  * @param    string  $name the name of the job
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function remove( $name ) {
 	  $this->modify_job($name,'remove');
    return !$this->error();
  }
  
  /**
  * Connects to scheduler and executes a command which is an instance of
  *
  * - SOS_Scheduler_Command_Add_Jobs 
  * - SOS_Scheduler_Command_Start_Job
  * - SOS_Scheduler_Command_Modify_Job
  * 
  * @param    SOS_Scheduler_Command_Add_Jobs  command
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
    if (!$this->command->connect()) { $this->set_error( $this->command->get_error(), __FILE__, __LINE__ ); return 0; }
    
 
    $this->command->command($c->asString());  
   
    if ($this->command->get_answer_error()) { $this->set_error( $this->command->get_error(), __FILE__, __LINE__ ); }
    $this->command->answer = "";
    $this->command->disconnect();
 
    return !$this->error();
  }

  /**
  * submits the job to the Job Scheduler
  * if name is empty, a temporary name will be created
  * if name is given, the job first will be removed
  *
  * @param    string  $name name of the job. Can be empty)
  * @param    string  $start_at start job at iso timestamp or constant 'now' for immediate start (optional)
  * @param    SOS_Scheduler_Job_Params $param Parameter for the job (optional)
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function submit($name='', $start_at=null, $params=null ) {
    if ($this->job == null)  {
    	  $scheduler = new SOS_Scheduler_Object(APP_SCHEDULER_HOST, APP_SCHEDULER_PORT,1000);
        $this->job = $scheduler->get_instance('SOS_Scheduler_Job','scheduler/');
      }

    if ($name==''){
      $this->job->name = uniqid(rand());
      $this->job->temporary = "yes";
    }else{
       $this->job->name = $name;
       $this->remove($name);      // ignore errors, job not have been submitted before
       $this->command->reset_error();
       $this->reset_error();
       $this->job->temporary = "no";
    }
     

   if ($start_at != null) $this->job->run_time()->at($start_at); 
   
   if ($params != null)     $this->job->params = $params;
   $this->add_jobs($this->job);

   return !$this->error();
  }


  /**
  * starts an existing job in the Job Scheduler
  *
  * @param    string  $name name of the job. Can be empty)
  * @param    string  $start_at start job at iso timestamp or constant 'now' for immediate start (optional)
  * @param    SOS_Scheduler_Job_Params $param Parameter for the job (optional)
  * @return   boolean error status
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function start( $name,$start_at='',$params=null ) {
  
   $command = new SOS_Scheduler_Command_Start_Job($name);
   $command->at = $start_at;
   $command->params = $params;
   $this->execute($command);
 
   return !$this->error();
  }
  
  /**
  * creates an instance of SOS_Scheduler_Command_Start_Job execute and returns it
  *
  * @param    string  $name name of the job.
  * @return   SOS_Scheduler_Command_Start_Job start_job
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
  
 function start_job($name) {
 	  $c = new SOS_Scheduler_Command_Start_Job($name);
    $this->execute($c);
    return $c;    
  }  

  /**
  * creates an instance of SOS_Scheduler_Command_Add_Jobs execute and returns it
  *
  * @param    SOS_Scheduler_Job the job.
  * @return   SOS_Scheduler_Command_Add_Jobs add_jobs
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

 function add_jobs($job) {
 	  $c = new SOS_Scheduler_Command_Add_Jobs($job);
    $this->execute($c);
    return $c;    
  }  
     
  /**
  * creates an instance of SOS_Scheduler_Command_Modify_Job and execute (if cmd<>empty) and returns it
  *
  * @param    SOS_Scheduler_Job the job.
  * @param    string  $name name of the job.
  * @param    string  $cmd commando (can be empty)
  * @return   SOS_Scheduler_Command_Modify_Job modify_job
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
 function modify_job($name,$cmd='') {
 	  $c = new SOS_Scheduler_Command_Modify_Job($name);
 	  $c->cmd=$cmd;
 	  if ($cmd != '') $this->execute($c);
     return $c;    
  }  
          

} // end of class SOS_Scheduler_JobCommand_Launcher

?>