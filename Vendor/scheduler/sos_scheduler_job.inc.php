<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_job_elements.inc.php'); 
require_once( 'sos_scheduler_runtime.inc.php'); 
require_once( 'sos_scheduler_job_commands.inc.php');
require_once( 'sos_scheduler_order_commands.inc.php');


/**
* Instance of a Job XML-Defintion in the Job Scheduler.
* <p>It is used e.g. in the add-jobs command. </p>
* <p>An Instance is created with  $job = new SOS_Scheduler_Job();</p>
*
* <p> you can create a job e.g. for use in the add_jobs($job) method
*
* Example:
* <pre>
*   $job = new SOS_Scheduler_Job();
*
*  //Setting some properties
*  $job->name           = "myJob"; 
*  $job->title          = "my first job ";
* 
*   //Set the implentation
*   $job->script('shell')->script_source='dir c:\temp';
* 
*   //The job has a runtime 
*   $job->run_time()->period()->single_start = "18:00";
* 
*   /Adding the job
*   $job_command = new SOS_Scheduler_JobCommand_Launcher();
*
*   if (! $job_command->add_jobs($job))  { 
*   echo 'error occurred adding job: ' . $job_command->get_error(); exit; }  
*
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package Job_Scheduler
*/

class SOS_Scheduler_Job extends SOS_Scheduler_Object {

  
  /** name="jobname"   
  *
  * Every job has a unique name.
  * Should a job with the same name be defined in one of the basic configurations, then this parameter can be used to change or supplement the settings made in that job.
  * @access public
  */
  var $name                   = '';

  /** title="text"   
  *
  * A description of the job (max. 1 line). 
  * @access public
  */
  var $title                  = '';

  /** priority="process_priority"   
  *
  * Sets the priority of a task.
  * This attribute can be given the following values: idle, below_normal, normal, above_normal and high or the numerical values allowed for the operating system being used.
  * An error does not occur when the priority of a job is not set.
  * A task with a higher priority can block the computer.  
  * @access public
  */
  var $priority               = 0;


  /** process_class="process_class"   
  *
  * Defines the name of the process class in which the job should run. Note that process classes are defined with <process_classes>.
  * @access public
  */
  var $process_class          = '';

  /** tasks="number"    (Initial value: 1)     The maximum number of tasks
  *
  * A number of tasks can run in parallel from one job. This attribute specifies the maximum number of tasks for a job.
  * @access public
  */
  var $tasks                  = 1;

  /** timeout="duration"    The time allowed for an operation
  *
  * Limits the duration of a task operation (spooler_open, spooler_process etc.). An error does not occur when the priority of a job is not set. Should a task exceed the time allowed, then the Job Scheduler aborts the task.
  * duration can be specified in seconds or in the HH:MM or HH:MM:SS formats.  
  * @access public
  */
  var $timeout                = 0;

  /** idle_timeout="duration"    Limit for the waiting_for_order State
  *
  * Limits the idle time of an order controlled job (order="yes"). When a task is waiting on the next order and this idle time is exceeded, then the Job Scheduler ends the task.
  * The duration can be specified in seconds or in the HH:MM or HH:MM:SS formats.
  * @access public
  */
  var $idle_timeout           = 0;
 
  /** ignore_signals="all|signalnames"    (Initial value: no)    
  *
  * Is only relevant for Unix systems.
  * A job whose task process ends with a signal, causes the job to be stopped. Signals are sent when the task ends either by way of the kill system command or by way of a program being aborted.
  * If ignore_signals has not been specified, then a task ending with a signal stops the job (with the message SCHEDULER-279).
  * ignore_signals="all" means that a job will not be stopped by a signal.
  * A list of signal names (seperated by blanks) can be specified instead of "all". The folowing signal names are recognised, depending on the operating system: SIGHUP, SIGINT, SIGQUIT, SIGILL, SIGTRAP, SIGABRT, SIGIOT, SIGBUS, SIGFPE, SIGKILL, SIGUSR1, SIGSEGV, SIGUSR2, SIGPIPE, SIGALRM, SIGTERM, SIGSTKFLT, SIGCHLD, SIGCONT, SIGSTOP, SIGTSTP, SIGTTIN, SIGTTOU, SIGURG, SIGXCPU, SIGXFSZ, SIGVTALRM, SIGPROF, SIGWINCH, SIGPOLL, SIGIO, SIGPWR und SIGSYS. Signal names which are not recogniosed by an operating system are ignored and a warning given.
  * Note that because a task ending with a signal which may be ignored can cause a TCP connection error (ECONNRESET), the Job Scheduler is so configured that TCP connection errors only lead to a job being stopped when ignore_signals="…" does not apply. The Job Scheduler reacts to this situation with the SCHEDULER-974 message.
  * @access public
  */
  var $ignore_signals          = 0;

  /** log file for command 
  *
  * @access public
  */
  var $log_file               = '';

  /** force_idle_timeout="yes_no"    (Initial value: no)     
  *
   *  Task ended by idle_timeout despite min_task
   *  Note that this is only effective with <job min_tasks = "0"> and <job idle_timeout="…">.
   *  force_idle_timeout="yes" ends a task after idle_timeout has expired, even when fewer tasks than specified in min_tasks are running. min_tasks only starts new tasks after the task termination.
   *  In this way tasks can be ended which may not take up a resource such as a database too long when idle.
   * @access public
   */
  var $force_idle_timeout  	= "";
  
  /**  java_options="string"   
  *
  * Is only effective when a job runs as its own process (see <process_classes>) and either the job or monitor is implemented in Java. The options are handed over together with the <config java_options="…"> Java options. The interpretation of these options depends on Java.
  * @access public
  */
  var $java_options  	      = "";	
  
  /** min_tasks="number"    (Initial value: 0)     The minimum number of tasks kept running
  *
  * The Job Scheduler keeps this minimium number of tasks running. This allows order controlled tasks, which require a long time to initialise, to held in waiting.
  * @access public
  */
  var $min_tasks          	= "";
  
  /**order="yes_no"    Order Controlled Job 
  *
  * order="yes" defines a job as being order controlled. The Job Scheduler will only start an order controlled job when an order for the job exists.
  * @access public
  */
  var $order  	            = "";

  /**spooler_id="spooler_id"   
  *
  * This element is only effective when its attribute is identical to the -id= parameter which was set as the Job Scheduler was started, or when the -id= parameter was not set as the Job Scheduler was started.
  * @access public
  */
  var $spooler_id  	        = "";	
  
  var $stop_on_error  	    = "";	
  
  /** visible="yes|no"    (Initial value: yes)    
  *
  * visible="no" makes a job invisible in the results of <show_jobs> and <show_state>.
  * The Job Scheduler makes a job visible as soon as a task has been loaded. 
  * @access public
  */
  var $visible              = "";
  

  /** temporary="yes_no"   
  *
  * @access public
  * temporary="yes" defines a job as being temporary. This setting is only for <add_jobs>. A job will then be deleted after being carried out and will no longer be recognized.
  */
  var $temporary              = 'no';

  /** Instance of SOS_Scheduler_Job_Param
  *
  * Is automatically created when using the method  this->addParam($n,$v)
  * @access public
  */
  var $params = null;
  
  /** Instance of  SOS_Scheduler_Job_Script
  *
  * Is automatically created when using the method  this->script($language='')
  * @access public
  */
  var $script = null;
  
  /** Instance of  SOS_Scheduler_Job_Process
  *
  * Is automatically created when using the method  this->process($file)
  * @access public
  */
  var $process = null;
  
  /** Instance of  SOS_Scheduler_Job_Monitor
  *
  * Is automatically created when using the method  this-> monitor()
  * @access public
  */
  var $monitor = null;
  
  /** Instance of SOS_Scheduler_Job_Start_when_directory_changed
  *
  * Is automatically created when using the method  this->start_when_directory_change($directory)
  * @access public
  */
  var $start_when_directory_changed  = null;
  
  /** Instance of  SOS_Scheduler_Job_Delay_after_error
  *
  * Is automatically created when using the method  this->addDelayAfterError($delay,$error_count)
  * @access public
  */
  var $delay_after_error = null;
  
  /** Instance of  SOS_Scheduler_Job_Delay_order_after_setback
  *
  * Is automatically created when using the method  this->addParam($n,$v)
  * @access public
  */
  var $delay_order_after_setback = null;
  
  /** Instance of  SOS_Scheduler_Job_Runtime
  *
  * Is automatically created when using the method  this->run_time()
  * @access public
  */
  var $run_time = null;
  
  /** Instance of  SOS_Scheduler_Job_Commands
  *
  * Is automatically created when using the method  this->addCommands($exit_code,$command,$obj)
  * @access public
  */
  var $commands  = null;
 
  
  /**
  * destructor
  *
  * @access   public
  * @author   Andreas Püschel <ap@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
  
  function destruct() {

    return 1;    
  }
  
/**
* Creates this->params if does not exist.
* Adds a new Parameter in the list of parameters.
* 
* $job->addParam('var1','value1');
*
* @access public
* @param String name
* @param String value
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    

  function addParam($n,$v){
  	if ($this->params==null){
  		$this->params=new SOS_Scheduler_Job_params();
  	}
  	$this->params->addParam($n,$v);
  }


/**
* Creates this->delay_after_error if does not exist.
* Adds a new SOS_Scheduler_Job_Delay_after_error in the list of delay_after_errors.
* 
*     $job->addDelayAfterError(20,6);
*
* @access public
* @param String delay
* @param String error_count
* @return SOS_Scheduler_Job_list_delay_after_error the new added delay_after_error
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function addDelayAfterError($delay,$error_count){
 	  if ($this->delay_after_error==null){
  		$this->delay_after_error=new SOS_Scheduler_Job_list_delay_after_error();
  	} 	
  	
  	$d = new SOS_Scheduler_Job_Delay_after_error();
  	$d->delay=$delay;
  	$d->error_count=$error_count;
  	
  	$this->delay_after_error->addDelay_after_error($d);
  	return $d;
  }
  
/**
* Creates this->delay_order_after_setback if does not exist.
* Adds a new SOS_Scheduler_Job_Delay_order_after_setback in the list of delay_order_after_setbacks.
* 
*     $job->addDelayOrderAfterSetback(20,6);
*
* @access public
* @param String delay
* @param String is_maximum
* @param String setback_count
* @return SOS_Scheduler_Job_Delay_order_after_setback the new added delay_order_after_setback
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function addDelayOrderAfterSetback($delay,$is_maximum='no',$setback_count=1){
 	  if ($this->delay_order_after_setback==null){
  		$this->delay_order_after_setback=new SOS_Scheduler_Job_list_delay_order_after_setback();
  	} 	
  	
  	$d = new SOS_Scheduler_Job_Delay_order_after_setback();
  	$d->delay=$delay;
  	$d->setback_count=$setback_count;
  	$d->is_maximum = $is_maximum;
  	 
  	$this->delay_order_after_setback->addDelay_order_after_setback($d);
  	return $d;
  }
  
  
/**
* Creates this->script if does not exist.
* Defines the language of the script.
* 
*     $job->script('javascript')->include='include_file';
*
* @access public
* @param String language
* @return SOS_Scheduler_Job_Script the new script
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  
  function script($language=''){
  	if ($this->script == null){
  		$this->script = new SOS_Scheduler_Job_Script();
  	}
  	$this->script->language=$language;
  	return $this->script;
  }

/**
* Creates this->process if does not exist.
* Defines the filename of the process. 
* 
*     $job->process('filename');
*
* @access public
* @param String filename
* @return SOS_Scheduler_Job_Process the new process
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function process($file){
  	if ($this->process == null){
  		$this->process = new SOS_Scheduler_Job_Process();
  	}
  	$this->process->file=$file;
  	return $this->process;
  }

/**
* Creates this->monitor if does not exist.
* 
*        $job->monitor()->script('javascript')->include='monitor_include';
*
* @access public
* @param String filename
* @return SOS_Scheduler_Job_Monitor the new monitor
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
 function monitor(){
  	if ($this->monitor == null){
  		$this->monitor = new SOS_Scheduler_Job_monitor();
  	}
  	return $this->monitor;
  }
  
/**
* Creates this->start_when_directory_change if does not exist.
* 
*        $job->start_when_directory_change('myDirectory');
*
* @access public
* @param String directory
* @return SOS_Scheduler_Job_Start_when_directory_changed the new start_when_directory_change
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    

 function start_when_directory_change($directory){
  	if ($this->start_when_directory_change == null){
  		$this->start_when_directory_change = new SOS_Scheduler_Job_Start_when_directory_changed();
  	}
  	$this->start_when_directory_change->directory = $directory;
  	
  	return $this->start_when_directory_change;
  }
  
/**
* Creates this->run_time if does not exist.
* 
*        $job->run_time()->at('12:20"');
*
* @access public
* @param String directory
* @return SOS_Scheduler_Runtime the new run_time
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   
 
 function run_time(){
  	if ($this->run_time == null){
  		$this->run_time = new SOS_Scheduler_Runtime();
  	}
  	return $this->run_time;
  }
 
/**
* Creates this->commands if does not exist.
* Adds the command for the exitcode.
* 
*        $job->addCommands('success',$command);
*
* @access public
* @param String exit_code
* @param String command
* @param SOS_Scheduler_Job_or_SOS_Scheduler_Order job or order
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function addCommands($exit_code,$command){
 	  if ($this->commands==null){
  		$this->commands=new SOS_Scheduler_Job_list_commands();
  	} 	
  	
  	
  	if ($this->commands->list_commands[$exit_code] == null){
  		$this->commands->list_commands[$exit_code] =new SOS_Scheduler_Job_commands();
  	}
  	
  		
  	$this->commands->list_commands[$exit_code]->on_exit_code = $exit_code;
  	$this->commands->list_commands[$exit_code]->addCommand($command);
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
   $s .= addAttribute('force_idle_timeout',$this->force_idle_timeout);
   $s .= addAttribute('idle_timeout',$this->idle_timeout);
   $s .= addAttribute('ignore_signals',$this->ignore_signals);
   $s .= addAttribute('java_options',$this->java_options);
   $s .= addAttribute('min_tasks',$this->min_tasks);
   $s .= addAttribute('name',$this->name);
   $s .= addAttribute('order',$this->order);
   $s .= addAttribute('priority',$this->priority);
   $s .= addAttribute('process_class',$this->process_class);
   $s .= addAttribute('spooler_id',$this->spooler_id);
   $s .= addAttribute('stop_on_error',$this->stop_on_error);
   $s .= addAttribute('tasks',$this->tasks);
   $s .= addAttribute('temporary',$this->temporary);
   $s .= addAttribute('timeout',$this->timeout);
   $s .= addAttribute('title',$this->title);
   $s .= addAttribute('visible',$this->visible);
 
   return $s;
  }

/**
* Returns a xml representation for the job element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<job' . $this->attributes(). '>';
  	if ($this->params != null)	$s .= $this->params->asString();
  	if ($this->script != null)$s .= $this->script->asString();
  	if ($this->process != null)$s .= $this->process->asString();
  	if ($this->monitor != null)$s .= $this->monitor->asString();
  	if ($this->start_when_directory_changed != null)$s .= $this->start_when_directory_changed->asString();
  	if ($this->delay_after_error != null)$s .= $this->delay_after_error->asString();
  	if ($this->run_time != null)$s .= $this->run_time->asString();
  	if ($this->commands != null)$s .= $this->commands->asString();

    $s .= '</job>';

    return $s;
 
  }
    
} // end of class SOS_Scheduler_Job

?>