<?php
require_once( 'sos_scheduler_object.inc.php'); 


/**
* Instance of a Process_Class XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $process_class = new SOS_Scheduler_Process_Class("myProcess_class");</p>
*
* <p> you can create a process_class e.g. for use in the modify_hot_folder_command->store($process_class) method
*
* Example:
* <pre>
*   $process_class = new SOS_Scheduler_Process_Class("myProcess_class");
*
*  //Setting some properties
*     $process_class->max_processes  	= "6" 	
* 
*   /Adding the process_class to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($process_class,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
*
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
* @access       public
* @package Job_Scheduler
*/

class SOS_Scheduler_Process_Class extends SOS_Scheduler_Object {

  
  /** name="name"   
  *
  * Every process_class has a unique name.
  * @access public
  */
  var $name                   = '';
 
 /** max_processes=6"   
  *
  * @access public
  * Limits the number of processes.
  * Some operating systems limit the number of processes which the Job Scheduler can start. The number of processes configured here should not exceed the number allowed by the operating system. A value below 64 is usually safe.
  *
  *For Microsoft Windows systems the maximum number of processes, which are allowed to be executed in parallel is currently 30.  */
  var $max_processes              = 0;

  /** remote_scheduler=6"   
  *
  * @access public
  * Specifies the remote computer on which the tasks of this process class are to be executed. This computer is specified using its host name or IP number and TCP port (see <config tcp_port="…">).
  * The remote computer must allow access with <allowed_host level="all">.
  *
  *Tasks executed communicate with the controlling Job Scheduler via the API. However, the following points should be noted:
  *
  *    * &lt;include&gt; within &lt;script&gt; is executed by a task process. The file to be included is thereby read by the computer which carries out the task.
  *    * The Subprocess.timeout and spooler_task.add_pid() methods do not work. The Job Scheduler cannot terminate remote subprocesses whose time limits have been exceeded.
  *    * Log.log_file() is, as with almost all methods, carried out on the computer on which the Job Scheduler is running and thereby accesses the files of its local file system.
  *
  *Some settings are taken from the remote instead of from the controlling Job Scheduler:
  *
  *    * sos.ini (section [java], entry javac=…)
  *    * factory.ini (section [spooler], entry tmp=…)
  *    * <config java_options="…">
  *    * <config java_class_path="…">
  *    * <config include_path="…">
  */
  var $remote_scheduler              = "";

  /** replace=6"   
  *
  * @access public
  * replace="yes" replaces the existing process class.
  *
  *replace="no" only changes the attributes which are set by the process class.
  */
  var $replace              = "yes";

  /** spooler_id=6"   
  *
  * @access public
  * An element having this attribute is only active when the attribute is either:
  *
  *    * empty
  *    * set to the -id= Job Scheduler start parameter
  *    * or when the Job Scheduler -id option is not specified when starting the Job Scheduler.
  *
  */
  var $spooler_id              = "";

 
 function SOS_Scheduler_Process_Class( $name="" ) {
    $this->name = $name; 
  }
  
  
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
   $s .= addAttribute('name',$this->name);
   $s .= addAttribute('spooler',$this->spooler);
   $s .= addAttribute('max_processes',$this->max_processes);
   $s .= addAttribute('replace',$this->replace);
   $s .= addAttribute('remote_scheduler',$this->remote_scheduler);
  
   return $s;
  }

/**
* Returns a xml representation for the lock element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<process_class' . $this->attributes(). '>';
    $s .= '</process_class>';

    return $s;
 
  }
    
} // end of class SOS_Scheduler_Process_Class

?>