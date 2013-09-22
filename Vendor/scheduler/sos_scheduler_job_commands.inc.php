<?php   
/**
* Instance of a modify_hot_folder-command
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_JobCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_JobCommand_Launcher.modify_hot_folder()
* Objects of the class SOS_Scheduler_JobCommand_Launcher create an object of this class in the method modify_hot_folder().</p>
* <p>It supports the reprentation of <modify_hot_folder> element. </p>
*
* <pre>
*   Creating a job in a hot_folder
*    $job = new 'SOS_Scheduler_Job';
*    $job->name         = "my_job";
*    $job->visible      = "yes";  
*
*    Creating &lt;add_job&gt; &lt;job....&gt; &lt;/add_job&gt;
*    $c = new SOS_Scheduler_Command_Modify_Hot_Folder("./", $job);
*    $xml_command = $c->asString();
* </pre>
*
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_Command_Modify_Hot_Folder{


  
  /** Instance of SOS_Scheduler_Job, SOS_Scheduler_Order */
  var $object               = null;
  var $folder               = "";

  /**
  * constructor
  * @param  SOS_Scheduler_Job job
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */

  function SOS_Scheduler_Command_Modify_Hot_Folder( $modify_hot_folder,$object ) {
    $this->object = $object;
    $this->folder = $folder;

  }

 /**
* Returns a xml representation for the add_jobs element.
*
*
* @access public
* @return String add_jobs in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
    
  function asString(){
    $s  = '<modify_hot_folder>' . $this->object->asString() . '</modify_hot_folder>';
    return $s;
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */
  
  function destruct() {
    return 1;    
  }
  

} // end of class SOS_Scheduler_Command_Add_Jobs


/**
* Instance of a add_jobs-command
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_JobCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_JobCommand_Launcher.add_job()
* Objects of the class SOS_Scheduler_JobCommand_Launcher create an object of this class in the method add_jobs().</p>
* <p>It supports the reprentation of <add_jobs> element. </p>
*
* <pre>
*   Creating a job
*    $job = new 'SOS_Scheduler_Job';
*    $job->name         = "my_job";
*    $job->visible      = "yes";  
*
*    Creating &lt;add_job&gt; &lt;job....&gt; &lt;/add_job&gt;
*    $c = new SOS_Scheduler_Command_Add_Jobs($job);
*    $xml_command = $c->asString();
* </pre>

*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_Command_Add_Jobs {


  
  /** Instance of SOS_Scheduler_Job */
  var $job               = null;

  /**
  * constructor
  * @param  SOS_Scheduler_Job job
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */

  function SOS_Scheduler_Command_Add_Jobs( $job ) {
    $this->job = $job;

  }

 /**
* Returns a xml representation for the add_jobs element.
*
*
* @access public
* @return String add_jobs in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
    
  function asString(){
    $s  = '<add_jobs>' . $this->job->asString() . '</add_jobs>';
    return $s;
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */
  
  function destruct() {
    return 1;    
  }
  

} // end of class SOS_Scheduler_Command_Add_Jobs




//============================================================================================================

/**
* Instance of a modify_job-command
*
* <p>Object of this class are used to be execute with an Instance of SOS_Scheduler_JobCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_JobCommand_Launcher.modify_job()
* Objects of the class SOS_Scheduler_JobCommand_Launcher create an object of this class in the method modify_job().</p>
* <p>It supports the reprentation of <modify_job> element. </p>
*
* <pre>
*    //Assuming that job "my_job" already exists in Job Scheduler
*    //Creating &lt;modify_job&gt; &lt;job....&gt; &lt;/&gt;
*    $c = new SOS_Scheduler_Command_Modify_Job("my_job");
*    $c->cmd="stop";
*    // or $c->stop();
*    $xml_command = $c->asString();
* </pre>


*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_Command_Modify_Job {

  /** @access public */
  
  /** The name of a job to be modifie
  */
  var $job               = '';

  /** The command to be executed.
  *
  * One of
  *  stop, unstop, reread, start, wake, end, suspend, continue, remove
  * cmd can be set by direct assignment or by invoking the corresponding message
  */

  var $cmd               = '';

  /**
  * constructor
  *
  * @param  String job, the name of the job which is to be modified
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */

  
  function SOS_Scheduler_Command_Modify_Job($job) {
    $this->job=$job;
  }
  
/**
* builds a list of the given attributes (Name of Job and command (stop|unstop etc.)
*
*
* @access private
* @return string List of Attributes
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/     
 function attributes(){
   $s = '';
   $s .= addAttribute('job',$this->job);
   $s .= addAttribute('cmd',$this->cmd);
   return $s;
  }
  
/** sets the cmd for the modify_job-command to stop
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
    
  
  function stop (){
    $this->cmd = 'stop';
  }

/** sets the cmd for the modify_job-command to unstop
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function unstop (){
    $this->cmd = 'unstop';
  }

/** sets the cmd for the modify_job-command to reread
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function reread (){
    $this->cmd = 'reread';
  }

/** sets the cmd for the modify_job-command to start
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function start (){
    $this->cmd = 'start';
  }

/** sets the cmd for the modify_job-command to wake
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function wake (){
    $this->cmd = 'wake';
  }

/** sets the cmd for the modify_job-command to end
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function end (){
    $this->cmd = 'end';
  }

/** sets the cmd for the modify_job-command to suspend
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function suspend (){
    $this->cmd = 'suspend';
  }

/** sets the cmd for the modify_job-command to continue
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function continue_ (){
    $this->cmd = 'continue';
  }

/** sets the cmd for the modify_job-command to remove
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function remove (){
    $this->cmd = 'remove';
  }
  
/**
* Returns a xml representation for the modify_job element.
*
*
* @access public
* @return String modify_job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
    $s  = '<modify_job ' . $this->attributes() . '/>';
    return $s;  
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */
  
  function destruct() {
    return 1;    
  }
  

} // end of class SOS_Scheduler_Command_Modify_Job



//============================================================================================================

/**
* Instance of a start_job-command
*
* <p>Object of this class are used to be execute with an Instance of SOS_Scheduler_JobCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
*
* <p>This class is mostly used internally e.g. in SOS_Scheduler_JobCommand_Launcher.start()
* Objects of the class SOS_Scheduler_JobCommand_Launcher create an object of this class in the method start().</p>
* <p>It supports the reprentation of <start_job> element. </p>
*
* <pre>
*
*    Creating &lt;start_job&gt; &lt;job....&gt; &lt;/&gt;
*    $c = new SOS_Scheduler_Command_Start_Job($name);
*    $c->at = "now";
*    $xml_command = $c->asString();
* </pre>


* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_Command_Start_Job {


  
  /** The name of a job which should be startet 
  */
  var $job               = '';

  /** after="number"   
  *
  * A delay - the number of seconds after which a task should be started
  */
  var $after             = '';
  
  /** at="yyyy-mm-dd hh:mm:ss | now | period"    (Initial value: now)    
  *
  * The time at which a task is to be started. <run_time> is deactivated.
  * Relative times - "now", "now + HH:MM[:SS]" and "now + SECONDS" - are allowed.
  *
  * at="period" allows a job to start when allowed by <run_time> (that is in the current or next period).
  */
  var $at                = '';


  /** name="name"   
  * A task can be given a name here.
  */
  var $name              = '';

  /**  web_service="name"   
  *
  * After a task has been executed, it is transformed with a style sheet and handed over to a Web Service.
  */
  var $web_service       = '';
  
  /** An Instance of SOS_Scheduler_Job_Params
  */
  var $params            = null;

  /**
  * constructor
  *
  * @param  String job, the name of the job
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */

  function SOS_Scheduler_Command_Start_Job( $job ) {
    $this->job = $job;

  }
  
  /**
  * adds a param to the list of params. 
  * create an instance of the class SOS_Scheduler_Job_params class implicite.
  *
  *
  * @access private
  * @author Uwe Risse <uwe.risse@sos-berlin.com>
  * @since  1.0-2006/10/20
  * @version 1.0 
  */      
  function addParam($n,$v){
     if ($this->params==null){
      $this->params = new SOS_Scheduler_Job_params();
     }
     $this->params->addParam($n,$v);
  }
  
  
  
  function copyParamFrom($n){
    if ($this->params==null){
      $this->params = new SOS_Scheduler_Job_params();
     }
   $this->params->from=$n; 
  }
  
/**
* builds a list of the given attributes.
*
*
* @access private
* @return string List of Attributes
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/     
   function attributes(){
   $s = '';
   $s .= addAttribute('job',$this->job);
   $s .= addAttribute('after',$this->after);
   $s .= addAttribute('at',$this->at);
   $s .= addAttribute('name',$this->name);
   $s .= addAttribute('web_service',$this->web_service);
 
   return $s;
  }

/**
* Returns a xml representation for the start_job element.
*
*
* @access public
* @return String start_job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function asString(){
    $s  = '<start_job' . $this->attributes() .'>';
      if ($this->params!=null){
        $s .= $this->params->asString();  
      }
    $s .= '</start_job>';
    return $s;
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */
  
  function destruct() {
    return 1;    
  }
  

} // end of class SOS_Scheduler_Command_Start_Job
?>
