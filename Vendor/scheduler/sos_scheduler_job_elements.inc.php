<?php

 	
/**
* Instance of a script definition in a job element.
* 
* Objects of this class are created, when using the method script() in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->script('javascript')->include='filename';
*
* or you create the script and then set the properties
* 
*    $script = $job->script('javascript');
*    $script->include='include_file';
*    $script->com_class="com_class";
*
* 
* You can also create the script via the constructor
*
*    $script = new SOS_Scheduler_Job_Script();
*    $script->filename='filename';
*    $script->language='javascript';
*
* and than assign this script to the job
*
*    $job->script = script;*
*
* The major task of script-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_Script {

  
  /** com_class="com_class_name"   
  *
  * The name of a COM-Class (Windows only). The COM class can implement the spooler_open(), spooler_process() etc. methods.The name of a COM-Class (Windows only). The COM class can implement the spooler_open(), spooler_process() etc. methods. 
  * @access public
  */
  var $com_class               = '';

  /** filename="file_name"   
  *
  * Should the name of the dll which implements the COM class not be registered, then its name can be given here, in conjunction with the com_class attribute.
  * @access public
  */
  var $filename                  = '';


  /** java_class="java_class_name"   
  *
  * Should a job be implemented as a Java class, then the class name must be defined using this attribute.
  * A name specified in the basic configuration can be overwritten here. The next task (running in a separate process) uses a new class.
 * @access public
  */
  var $java_class               = '';


  /** language="language"   
  *
  * The language of the program code. Is not used in conjunction with the com_class. Case is not important here.
  * language="java"
  *   The class name is defined with the java_class attribute. The program code for the java class can be entered as text in <script> - the Job Scheduler then compiles the code using javac. 
  * language="JavaScript"
  *  JavaScript is available via the SpiderMonkey scripting engine in Windows and Unix. 
  * language="JScript" and "VBScript"
  *  JScript and VBScript are present in Windows with their own scripting engines. 
  * language="PerlScript"
  *  PerlScript is used in Windows when it is installed. Perl is used directly in Unix. 
  * language="shell"
  *  On Windows systems the Job Scheduler forwards the script to cmd.exe. (the script is saved as a temporary .cmd file). 
  *  The script is called in the same way as an executable file on Unix Systems. 
  *  Behaviour is the same as <process>.
  * @access public
  */
  var $language               = '';
  
  /** Only use_engine="task" is allowed. 
   *
   * @access public
   */
  var $use_engine               = '';

  /** Includes Text from the file $include 
   *
   * @access public
  */
  var $include          = '';
  
  /** If not including a file, you can specify inline source 
   *
   * @access public
  */
  var $script_source=  '';


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
   $s .= addAttribute('com_class',$this->com_class);
   $s .= addAttribute('filename',$this->filename);
   $s .= addAttribute('java_class',$this->java_class);
   $s .= addAttribute('language',$this->language);
   $s .= addAttribute('use_engine',$this->use_engine);
   return $s;
  }
  
 /**
* Returns a xml representation for the script element.
*
*
* @access public
* @return String Script in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function asString(){
 	  $s  =	'<script' . $this->attributes() . '>';
  	if ($this->include != ''){
     	$s .= '<include file="'.$this->include .'"/>';
    }
  	if ($this->script_source != ''){
     	$s .= '<![CDATA[' . $this->script_source . ']]>';
    }
  	$s .= '</script>';
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_Script



//============================================================================================================

/**
* Instance of a process definition in a job element.
* 
* Objects of this class are created, when using the method process() in the class SOS_Scheduler_Job().
* Sample:
*   
*     $job->process('filename');
*
* 
* You can also create the process via the constructor
*
*    $process = new SOS_Scheduler_Job_Process();
*    $process->filename='filename';
*
* and than assign this script to the job
*
*    $job->process = process;
*
* The major task of process-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_Process {

  
  /** file="filename"   
  *
  * The name of the file containing the program or script with which the process is to be started. The file must be capable of being executed by the operating system.
  * @access public
  */
  var $file               = '';

  /**  ignore_error="yes|no"    (Initial value: no)    
  * An exit code ? 0 causes a job error when ignore_error="no" (the default setting). The job is then stopped.
  * An exit code ? 0 does not cause a job error when ignore_error="yes".
  * @access public
  */
  var $ignore_error                  = '';

  /**ignore_signal="yes|no"    (Initial value: no)    
  *
  * Functions on Unix systems. (An interrupted process returns a Exit code on Windows. This code is, however, recognized by ignore_error="".)
  * A signal (i.e. a process termination, such as kill) leads to a job error when the (default) setting ignore_signal="no" is used. The job is then stopped. 
  * However the setting ignore_signal="yes" causes a signal not to result in a job error.
  * ignore_signal="yes" has the same effect as <job ignore_signals="all">.
  */
  var $ignore_signal               = '';

  /** log_file="file_name"   
  *
  * The Job Scheduler includes the content of this file in its protocol after the process has ended.
  * @access public
  */
  var $log_file               = '';
  
  /**  param="text"   
  *
  * Defines the parameter string which will be assigned to the process. Task parameters can be called using $name or ${name} in addition to environment variables.
  * Variables specified in <environment> have no influence on the substitution.
  * Environment variables (e.g. $HOME) will be replaced.
  * @access public
  */
  var $param               = '';
  
  /** <environment>     – Environment Variables
  *
  * The environment variables listed here are carried over to the new process together with the environment variables for the Job Scheduler processes. The environment variables listed here overwrite the Scheduler process environment variables having the same name.
  * Messages 
  * @access public
  */
  var $environment         = array();

 /**
* set an environment-variable.
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
   function addEnvironment($n,$v){
   	 $this->environment[$n] = $v;
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
   $s .= addAttribute('file',$this->file);
   $s .= addAttribute('ignore_error',$this->ignore_error);
   $s .= addAttribute('ignore_signal',$this->ignore_signal);
   $s .= addAttribute('log_file',$this->log_file);
   $s .= addAttribute('param',$this->param);
   return $s;
  }
  
 /**
* Returns a xml representation for the process element.
*
*
* @access public
* @return String Process in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function asString(){
  	$s  =	'<process' . $this->attributes() . '>';
    
    if (count($this->environment) > 0){
      $s .= '<environment>';  
      foreach ($this->environment as $name=>$value){
      	$s .= '<variable name="'.$name.'" value="'.$value.'"/>';
	    }
      $s .= '</environment>';  
    }

  	$s .= '</process>';
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_Process

//=====================================================================================================================================

/**
* Instance of a start_when_directory_change definition in a job element.
* 
* Objects of this class are created, when using the method start_when_directory_change() in the class SOS_Scheduler_Job().
* Sample:
*   
*     $job->start_when_directory_change('directory');
*
* 
* You can also create the start_when_directory_change via the constructor
*
*    $start_when_directory_change = new SOS_Scheduler_Job_Start_when_directory_change();
*    $start_when_directory_change->directory='directory';
*
* and than assign this start_when_directory_change to the job
*
*    $job->start_when_directory_change = $start_when_directory_change;
*
* The major task of start_when_directory_change-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_Start_when_directory_changed {


  
  /** directory="path"   
  *
  * A change inthe directory (the addition or deletion of a file in the directory) leads to the start of a task. This also occurs when the directory being monitored itself is deleted.
  * Environment variables (e.g. $HOME) will be replaced 
  * @access public
  */
  var $directory               = '';

  /** regex="regex"   
  *
  * Only file names which correspond with this regular expression are noted.
  * @access public
  */
  var $regex                  = '';


  
  
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
   $s .= addAttribute('directory',$this->directory);
   $s .= addAttribute('regex',$this->regex);
 
   return $s;
  }
  
 /**
* Returns a xml representation for the start_when_directory_changed element.
*
*
* @access public
* @return String start_when_directory_changed in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  function asString(){
  	$s  =	'<start_when_directory_changed' . $this->attributes() . '/>';
  	
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_Start_when_directory_changed

//=====================================================================================================================================

/**
* Instance of a delay_after_error definition in a job element.
* 
* Objects of this class are created, when using the method addDelayAfterError() in the class SOS_Scheduler_Job().
* Sample:
*   
*     $job->addDelayAfterError(5,1);
*
* 
* You can also create the delay_after_error via the constructor
*<pre>
*    $delay_after_error = new SOS_Scheduler_Job_Delay_after_error();
*    $delay_after_error->delay='5';
*    $delay_after_error->error_count='3';
*</pre>
* and than assign this delay_after_error to the job
*<pre>
*    $job->delay_after_error = new SOS_Scheduler_Job_list_delay_after_error();
*    array_push($job->delay_after_error,$delay_after_error);
*</pre>
* The major task of delay_after_error-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_delay_after_error {

  
  /** delay="seconds|HH:MM|HH:MM:SS|stop"   
  *
  * Delay before the job will be rerun.
  * delay="stop" or delay="STOP" stops a job after the specified number of consecutive errors.
  * @access public
  */
  var $delay               = '';

  /** error_count="integer"   
  *
  * The number of consecutively occurring errors before which a job will be delayed.
  */
  var $error_count         = '';


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
   $s .= addAttribute('delay',$this->delay);
   $s .= addAttribute('error_count',$this->error_count);
 
   return $s;
  }
  
 /**
* Returns a xml representation for the delay_after_error element.
*
*
* @access public
* @return String delay_after_error in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   
  function asString(){
  	$s  =	'<delay_after_error' . $this->attributes() . '/>';
  	
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_delay_after_error

//=====================================================================================================================================

/**
* Instance of a delay_order_after_setback definition in a job element.
* 
* Objects of this class are created, when using the method addDelayOrderAfterSetback() in the class SOS_Scheduler_Job().
* Sample:
*   
*     $job->addDelayOrderAfterSetback(5,'no',1);
*
* 
* You can also create the delay_order_after_setback via the constructor
*<pre>
*    $delay_order_after_setback = new SOS_Scheduler_Job_Delay_order_after_setback();
*    $delay_order_after_setback->delay='5';
*    $delay_order_after_setback->setback_count='3';
*    $delay_order_after_setback->is_maximum='no';
*</pre>
* and than assign this delay_order_after_setback to the job
*<pre>
*    $job->delay_order_after_setback = new SOS_Scheduler_Job_list_delay_order_after_setback();
*    array_push($job->delay_order_after,$delay_order_after_setback);
*</pre>
* The major task of delay_order_after_setback-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_delay_order_after_setback {

   
  /**delay="seconds|HH:MM|HH:MM:SS"   
  *
  * The period an order waits after a setback before being restarted in a job. The period an order waits after a setback before being restarted in a job.
  * @access public
  */
  var $delay               = '';

  /**is_maximum="yes|no"    (Initial value: no)    
  *
  * setback_count= specifies the maximum number of sequential setbacks allowed. A further setback occurring after this number of setbacks has been reached (Order.setback()) causes the Job Scheduler to give the order the error state Job_chain_node.error_state.
  */

  var $is_maximum         = '';

  /**setback_count="integer"   
  *
  * The number of successive setbacks occurring for an order. Different delays can be set for each setback - e.g. 1st setback, 1 second; 2nd setback, 10 seconds; etc.
  * @access public
  */

  var $setback_count         = '';

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
   $s .= addAttribute('delay',$this->delay);
   $s .= addAttribute('is_maximum',$this->is_maximum);
   $s .= addAttribute('setback_count',$this->setback_count);
 
   return $s;
  }
  
 /**
* Returns a xml representation for the delay_order_after_setback element.
*
*
* @access public
* @return String delay_order_after_setback in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   

  function asString(){
  	$s  =	'<delay_order_after_setback' . $this->attributes() . '/>';
  	
  	return $s;
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



} // end of class SOS_Scheduler_Job_delay_order_after_setback
  

//=====================================================================================================================================

/**
* A list of all command-elements for a job
* 
* An Object of this class is created, when using the method addCommands($exit,$command) in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->addCommands('success',$command)
*
*$command can be on of 
*
*<ul>
*<li>class SOS_Scheduler_Command_Add_Order</li>
*<li>class SOS_Scheduler_Command_Remove_Order</li>
*<li>class SOS_Scheduler_Command_Modify_Order</li>
*<li>class SOS_Scheduler_Command_Add_Jobs </li>
*<li>class SOS_Scheduler_Command_Modify_Job </li>
*<li>class SOS_Scheduler_Command_Start_Job </li>
*</ul>* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/


class SOS_Scheduler_Job_list_commands {

  
   var $list_commands               = array();

 /**
* adds a command-element to the list of commands. Use $job->addCommands('success',$command) to insert a command in the list
*$command can be on of 
*
*<ul>
*<li>class SOS_Scheduler_Command_Add_Order</li>
*<li>class SOS_Scheduler_Command_Remove_Order</li>
*<li>class SOS_Scheduler_Command_Modify_Order</li>
*<li>class SOS_Scheduler_Command_Add_Jobs </li>
*<li>class SOS_Scheduler_Command_Modify_Job </li>
*<li>class SOS_Scheduler_Command_Start_Job </li>
*</ul>
*
* Sample:
* Creating a job
*
* <pre>
*   $job = new SOS_Scheduler_Job();
* 
*   //Setting some properties
*   $job->force_idle_timeout   = "yes";
*   $job->idle_timeout         = "1000";
*   $job->ignore_signals       = "all";
*
* Starting another job on succes. Note: Job my_job must already exist;
*    $c = new SOS_Scheduler_Command_Start_Job('my_job');
*    $c->at = "now";
*    $job->addCommands('success',$c)
* </pre>
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function addCommand($c){
   	 array_push($this->list_commands,$c);
   }
  

 /**
* Returns a xml representation for the commands element.
*
*
* @access public
* @return String Commands in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   
  function asString(){
  	$s  =	'';
  
    if (count($this->list_commands) > 0){
      foreach ($this->list_commands as $name=>$obj){
      	$s .= $obj->asString();
	    }
    }
  	
  	return $s;
  }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  */
  
  function destruct() {
    return 1;    
  }
  

} // end of class SOS_Scheduler_Job_list_commands

//=====================================================================================================================================

/**
* A list of all delay_after_error-elements for a job
* 
* An Object of this class is created, when using the method addDelayAfterError($delay,$error_count) in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->addDelayAfterError($delay,$error_count);
*
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_list_delay_after_error {

  
   var $list_delay_after_error               = array();


/**
* adds a delay_after_error-element to the list of delay_after_errors. Use $job->addDelayAfterError($delay,$error_count)
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function addDelay_after_error($d){
   	 array_push($this->list_delay_after_error,$d);
   }
  

/**
* Returns a xml representation for all delay_after_error elements.
*
*
* @access public
* @return String Delay_after_error in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   
  
  function asString(){
  	$s  =	'';
  
    if (count($this->list_delay_after_error) > 0){
      foreach ($this->list_delay_after_error as $name=>$obj){
      	$s .= $obj->asString();
	    }
    }
  	
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_list_delay_after_error

//=====================================================================================================================================

/**
* A list of all delay_order_after_setback-elements for a job
* 
* An Object of this class is created, when using the method addDelayOrderAfterSetback($delay,$is_max,$setback_count) in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->addDelayOrderAfterSetback($delay,$is_max,$setback_count)
*
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_list_delay_order_after_setback {

  
   var $list_delay_order_after_setback               = array();

/**
* adds a delay_order_after_setback-element to the list of delay_order_after_setbacks. 
* Use $job->addDelayOrderAfterSetback($delay,$is_max,$setback_count)
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/ 
 
  function addDelay_order_after_setback($d){
   	 array_push($this->list_delay_order_after_setback,$d);
   }
  
/**
* Returns a xml representation for all delay_order_after_setback elements.
*
*
* @access public
* @return String Delay_Order_after_setback in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   

  
  function asString(){
  	$s  =	'';
  
    if (count($this->list_delay_order_after_setback) > 0){
      foreach ($this->list_delay_order_after_setback as $name=>$obj){
      	$s .= $obj->asString();
	    }
    }
  	
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_list_delay_order_after_setback

//=====================================================================================================================================

/**
 		
* A SOS_Scheduler_Job_commands contains a list of commands (start_job etc.) for one exitcode 
* 
* An Object of this class is created, when using the method 
* addCommand($command) in the class SOS_Scheduler_Job_commands().
*
*$command can be on of 
*
*<ul>
*<li>class SOS_Scheduler_Command_Add_Order</li>
*<li>class SOS_Scheduler_Command_Remove_Order</li>
*<li>class SOS_Scheduler_Command_Modify_Order</li>
*<li>class SOS_Scheduler_Command_Add_Jobs </li>
*<li>class SOS_Scheduler_Command_Modify_Job </li>
*<li>class SOS_Scheduler_Command_Start_Job </li>
*</ul>
* 

* Sample:
*   
* <pre>
*    $c =new SOS_Scheduler_Job_commands();		
*    $c->on_exit_code = 5;
*    $command = new SOS_Scheduler_Command_Start_Job('my_job');
*    $command->at = "now";
*    $c->addCommand($command);
* </pre>
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_commands {


  
  /** on_exit_code="exitcodes"   
   *
  * This attribute is mandatory within <job> - it cannot be used anywhere else.
  * @access public
  */
  var $on_exit_code               = '';

  /** List of commands.
  *
  * @access public
  */
  var $commands         = array();


 
/**
* adds a command for exit-code
*
*
* @access private
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function addCommand($c){
  	$i=array_push($this->commands,$c);
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
   $s .= addAttribute('on_exit_code',$this->on_exit_code);
  
   return $s;
  }
  
/** Returns a xml representation for all commands for an exit-code.
*
*
* @access public
* @return String Commands in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  
  function asString(){
  	$s  =	'<commands' . $this->attributes() . '>';
  
    if (count($this->commands) > 0){
      foreach ($this->commands as $name=>$obj){
      	$s .= $obj->asString();
	    }
    }
  	
  	$s .= '</commands>';
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_command
//=====================================================================================================================================

/**
* A list of all params-elements for a job
* 
* An Object of this class is created, when using the method addParam($name,$value) in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->addParam('var1','value1');
*
* 
*
* The major task of process-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_params {

  /** List of params (name-value)
   *
   * @access public
   *
  */
   var $params               = array();
 
   /** task or order
   *
   * generate the copy_from directive
   * @access public
   */
   var $from                 = '';


/**
* adds a param to the list of params. You should use the addParam-method in SOS_Scheduler_Job to
* create an instance of this class implicite.
*
*
* @access private
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  function addParam($n,$v){
   	 $this->params[$n] = $v;
   }
  

/** Returns a xml representation for all params.
*
*
* @access public
* @return String Params in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  
  function asString(){
  	$s  =	'';
  
    if (count($this->params) > 0){
  	  $s  =	'<params>';
      foreach ($this->params as $name=>$value){
      	$s .= '<param name="' . $name . '" value="' . $value .'"/>';
	    }
	    if ($this->from != ''){
	    	$s .= '<copy_params from="'.$this->from.'"/>';
	    }
    	$s .= '</params>';
    }
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_params

//=====================================================================================================================================

/**
* A list of all params-elements for a job
* 
* An Object of this class is created, when using the method addParam($name,$value) in the class SOS_Scheduler_Job().
* Sample:
*   
*    $job->addParam('var1','value1');
*
* 
*
* The major task of process-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/

class SOS_Scheduler_Job_monitor {

   /**Instance of SOS_Scheduler_Job_Script  
    *
    * @access public
   */
   var $script               = null;

/**
* Create this->script if does not exist.
* Defines the language of the script.
* 
*     $job->monitor()->script('javascript')->include='include_file';
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
  

/** Returns a xml representation for all params.
*
*
* @access public
* @return String Params in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function asString(){
   	$s .= '<monitor>';
    if ($this->script != null){ 
   	   $s .= $this->script->asString();
   	}
    $s .= '</monitor>';
    
  	return $s;
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
  

} // end of class SOS_Scheduler_Job_monitor
?>
