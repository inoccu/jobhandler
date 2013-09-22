<?php
require_once( 'sos_scheduler_object.inc.php'); 

//--------------------------------------------------------------------------------------------------------------------------------
//  SOS_Scheduler_Job_Chain_Node
//--------------------------------------------------------------------------------------------------------------------------------

/**
* Instance of a Jobhcain_node XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $node = new SOS_Scheduler_Job_Chain_Node();</p>
*
* <p> you can create a job_chain_node e.g. for use in a job_chain
*
* Example:
* <pre>
*   $job_chain_node = new SOS_Scheduler_Job_Chain_Node();
*   $job_chain      = new SOS_Scheduler_Job_Chain(
*   $job_chain.name = "myJob_Chain";
*
*  //Setting some properties
*     $job_chain_node->state  	      = "100" 	
*     $job_chain_node->error_state  	= "1100" 	
*     $job_chain_node->next_state  	= "200" 	
*     $job_chain_node->on_error    	= "suspend" 	
*     $job_chain_node->job  	        = "myJob"
*   //Adding the job_chain_node to the job_chain
*   $job_chain.add_node($job_chain_node);
*
*   //Adding the job_chain  to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($job_chain,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_Job_Chain_Node extends SOS_Scheduler_Object {
 
  
  /** delay="0"   
  *
  * delays an order before handing it over to a job.  
  * @access public
  */
  var $delay                   = '';
 
 /** error_state="string"   
  *
  * @access public
  * When return false is returned by a job's spooler_process() method, 
  * then the order state is changed to error  
  var $error_state = "";

  /** job="job_name"   
  *
  * @access public
  * The name of the job to be called when an order reaches the state specified.
  *
  * This attribute should not be specified for the end state.  *
  */
  var $job              = "";

  /** next_state="string"   
  *
  * @access public
  * An order is given the next state when the spooler_process() returns return true for the order.
  *
  * The default setting is the state= attribute of the following <job_chain_node>.  *
  */
  var $next_state              = "";

  /** on_error=6"   
  *
  * @access public
  * An element having this attribute is only active when the attribute is either:
  *
  *    * empty
  *    * set to the -id= Job Scheduler start parameter
  *    * or when the Job Scheduler -id option is not specified when starting the Job Scheduler.
  *
  */
  var $on_error              = "";
 
  /** state="string"   
  *
  * @access public
  * The state valid for a job chain node.
  */
  var $state              = "";

 
 function SOS_Scheduler_Job_Chain_Node($state="") {
 	$this->state = $state;
   }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <ur@sos-berlin.com>
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
   $s .= addAttribute('job',$this->job);
   $s .= addAttribute('state',$this->state);
   $s .= addAttribute('next_state',$this->next_state);
   $s .= addAttribute('error_state',$this->error_state);
   $s .= addAttribute('delay',$this->delay);
   $s .= addAttribute('on_error',$this->on_error);
  
   return $s;
  }

/**
* Returns a xml representation for the job_chain_node element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<job_chain_node' . $this->attributes(). '/>';
    return $s;
 
  }
    
} // end of class SOS_Scheduler_Job_Chain_Node

//--------------------------------------------------------------------------------------------------------------------------------
//  SOS_Scheduler_Job_Chain_Node
//--------------------------------------------------------------------------------------------------------------------------------

/**
* Instance of a Jobhcain_node XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $node = new SOS_Scheduler_Job_Chain_Node();</p>
*
* <p> you can create a job_chain_node e.g. for use in a job_chain
*
* Example:
* <pre>
*   $job_chain_node = new SOS_Scheduler_Job_Chain_Node();
*   $job_chain      = new SOS_Scheduler_Job_Chain(
*   $job_chain.name = "myJob_Chain";
*
*  //Setting some properties
*     $job_chain_node->state  	      = "100" 	
*     $job_chain_node->error_state  	= "1100" 	
*     $job_chain_node->next_state  	= "200" 	
*     $job_chain_node->on_error    	= "suspend" 	
*     $job_chain_node->job  	        = "myJob"
*   //Adding the job_chain_node to the job_chain
*   $job_chain.add_node($job_chain_node);

*   //Adding the job_chain  to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($job_chain,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_Job_Chain_End_Node extends SOS_Scheduler_Object {
   
  /** state="string"   
  *
  * @access public
  * The state valid for a job chain node.
  */
  var $state              = "";

 
 function SOS_Scheduler_Job_Chain_End_Node($state="") {
 	$this->state = $state;
   }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <ur@sos-berlin.com>
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
   $s .= addAttribute('state',$this->state);
  
   return $s;
  }

/**
* Returns a xml representation for the job_chain_node element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<job_chain_node.end' . $this->attributes(). '/>';
    return $s;
 
  }
    
} // end of class SOS_Scheduler_Job_Chain_End_Node



//--------------------------------------------------------------------------------------------------------------------------------
//  SOS_Scheduler_Job_Chain_Node_Job_Chain
//--------------------------------------------------------------------------------------------------------------------------------

/**
* Instance of a Jobhcain_node_Job_Chain in a nested Job_chain  XML-Defintion in the Job Scheduler.
*
* <p>An Instance is created with  $node = new SOS_Scheduler_Job_Chain_Node_Job_Chain();</p>
*
* <p> you can create a job_chain_node_job_chain e.g. for use in a nested job_chain
*
* Example:
* <pre>
*   $job_chain_node = new SOS_Scheduler_Job_Chain_Node_Job_Chain("100");
*   $job_chain      = new SOS_Scheduler_Job_Chain(
*   $job_chain.name = "myJob_Chain";
*
*  //Setting some properties
*     $job_chain_node->state  	      = "100" 	
*     $job_chain_node->error_state  	= "1100" 	
*     $job_chain_node->next_state   	= "200" 	
*     $job_chain_node->job_chain      = "myJobNextChain"
*   //Adding the job_chain_node to the job_chain
*   $job_chain.add_node($job_chain_node);
*
*   //Adding a second job_chain with -> add_job_chain();
*   $job_chain.add_job_chain("mySecondJobChain","200","300","1200");
*
*   //Adding the job_chain  to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($job_chain,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_Job_Chain_Node_Job_Chain extends SOS_Scheduler_Object {
 
  
 /** error_state="string"   
  *
  * @access public
  * When return false is returned by a job's spooler_process() method, 
  * then the order state is changed to error  
  */
    var $error_state = "";

  /** job_chain="job_chain"   
  *
  * @access public
  *  The job chain to which the order is to be handed over when it reaches this state.
  */
  var $job_chain              = "";

  /** next_state="string"   
  *
  * @access public
  * The default value is the value of the state= attribute of the next job chain node.
  */
  var $next_state              = "";

   
 
  /** state="string"   
  *
  * @access public
  * The state valid for a job chain node.
  */
  var $state              = "";

 
 function SOS_Scheduler_Job_Chain_Node_Job_Chain($state="") {
 	$this->state = $state;
   }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <ur@sos-berlin.com>
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
   $s .= addAttribute('job_chain',$this->job_chain);
   $s .= addAttribute('state',$this->state);
   $s .= addAttribute('next_state',$this->next_state);
   $s .= addAttribute('error_state',$this->error_state);
  
   return $s;
  }

/**
* Returns a xml representation for the job_chain_node element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<job_chain_node.job_chain' . $this->attributes(). '/>';
    return $s;
 
  }
    
} // end of class SOS_Scheduler_Job_Chain_Node_Job_Chain


//--------------------------------------------------------------------------------------------------------------------------------
//    SOS_Scheduler_File_Order_Source
//--------------------------------------------------------------------------------------------------------------------------------

/**
* Instance of a file_order_source XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $file_order_source = new SOS_Scheduler_File_Order_Source();</p>
*
* <p> you can create a file_order_source e.g. for use in a job_chain
*
* Example:
* <pre>
*   $file_order_source = new SOS_Scheduler_File_Order_Source("/anydir","100");
*   $job_chain      = new SOS_Scheduler_Job_Chain(
*   $job_chain.name = "myJob_Chain";

*  //Setting some properties
*     $file_order_source->repeat  	      = "11" 	

*   //Adding the job_chain_node to the job_chain
*   $job_chain.file_order_source=$file_order_source;

*   //Adding the job_chain  to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($job_chain,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_File_Order_Source extends SOS_Scheduler_Object {
 
 
 
  
  /** delay_after_error="seconds"   
  *
  * The default setting is the repeat="…" attribute value.
  *
  * Should the directory not be readable, then the Job Scheduler will first send an e-mail and 
  * then repeatedly try to read the directory until it is successful. The Scheduler will then send a further mail.
  * @access public
  */
  var $delay_after_error                   = '';
 
  
  /** directory="directory_path"   
  *
  * the path to the directory containing the files.
  * @access public
  */
  var $directory                   = '';
 
 /** max="integer"   
  *
  * The maximum number of files to be taken as orders. Should more files be present, 
  * then these extra files are taken on as soon as the first job in the job chain can take on a new order.
  * @access public
 */
   var $max = 0;

  /** next_state="string"   
  *
  * Should it not be possible to start the orders in the first job of the job chain, 
  * then the initial state of the orders can be specified using this attribute.
  * @access public
  *
  */
  var $next_state              = "";

  /** regex="regex"   
  *
  * A regular expression used to select files according to their names.
  * @access public
  */
  var $regex              = "";

  /** repeat="no|seconds"   
  *
  * The Job Scheduler checks for changes in the directory on a regular basis.  
  * The length of time between these checks can be set here.
  *
  * The default setting on Windows systems is repeat="60". 
  * Further, the Job Scheduler also uses the Windows system directory monitoring, 
  * in order to be able to react immediately to a change in a directory. This is renewed regularly after the repeat interval has elapsed.
  *
  * On Unix systems the default value is repeat="10". This means that the directory is checked every 10 seconds for changes.
  * @access public
  *
  *
  */
  var $repeat              = "";
  
 
 function SOS_Scheduler_File_Order_Source($directory,$state) {
 	$this->directory = $directory;
 	$this->state = $state;
   }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <ur@sos-berlin.com>
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
   $s .= addAttribute('directory',$this->directory);
   $s .= addAttribute('next_state',$this->next_state);
   $s .= addAttribute('delay_after_error',$this->delay_after_error);
   $s .= addAttribute('max',$this->max);
   $s .= addAttribute('regex',$this->regex);
   $s .= addAttribute('repeat',$this->repeat);
  
   return $s;
  }

/**
* Returns a xml representation for the job_chain_node element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<file_order_source' . $this->attributes(). '/>';
    return $s;
   }
    
} // end of class SOS_Scheduler_File_Order_Source


//--------------------------------------------------------------------------------------------------------------------------------
//    SOS_Scheduler_File_Order_Sink
//--------------------------------------------------------------------------------------------------------------------------------

/**
* Instance of a file_order_sink XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $file_order_sink = new SOS_Scheduler_File_Order_Sink("100");</p>
*
* <p> you can create a file_order_sink e.g. for use in a job_chain
*
* Example:
* <pre>
*   $file_order_sink = new SOS_Scheduler_File_Order_Sink("100");
*   $job_chain      = new SOS_Scheduler_Job_Chain(
*   $job_chain.name = "myJob_Chain";

*  //Setting some properties
*     $file_order_sink->move_to  	      = "./anydir" 	

*   //Adding the job_chain_node to the job_chain
*   $job_chain.file_order_source=$file_order_sink;

*   //Adding the job_chain  to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($job_chain,"./test"))  { echo 'error occurred adding process_class: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_File_Order_Sink extends SOS_Scheduler_Object {
 

  /** move_to="directory_path"   
  *
  * The file will be moved to the directory specified. An already existing file of the same name will be overwritten.
  *
  * On Unix systems the file can only be moved within the same file system.  * @access public
  */
  var $move_to                   = '';
 
  
  /** remove="yes|no"   
  *
  *remove="yes" removes the file.
  * @access public
  */
  var $remove                   = '';
 
 /** state="string"   
  *
  *  The state valid for a job chain node. This state is an end state.
  * @access public
 */
   var $state = "";

   
  
 
 function SOS_Scheduler_File_Order_Sink($state) {
   $this->state=$state;
   }
  
  
  /**
  * destructor
  *
  * @access   public
  * @author   Uwe Risse <ur@sos-berlin.com>
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
   $s .= addAttribute('state',$this->state);
   $s .= addAttribute('move_to',$this->move_to);
   $s .= addAttribute('remove',$this->remove);
  
   return $s;
  }

/**
* Returns a xml representation for the file_order_sink element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<file_order_sink' . $this->attributes(). '/>';
    return $s;
 
  }
    
} // end of class SOS_Scheduler_File_Order_Sink


//-----------------------------------------------------------------------------------------------------------------------
//            SOS_Scheduler_Job_list_job_chain_nodes
//-----------------------------------------------------------------------------------------------------------------------


/**
* A list of all job_chain_nodes for a job_chain
* 
* An Object of this class is created, when using the method add_job($jobname,$state,$next_state,$error_state) or add_node($node)
* Sample:
*   
*    $job_chain->add_job("myJob","100","200","999");
*
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job_chain> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_list_job_chain_nodes {

  
   var $list_job_chain_nodes             = array();

/**
* adds a job_chain_node-element to the list of job_chain_nodes. 
* Use $job_chain->addNode($node)
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/ 
 
  function addNode($n){
   	 array_push($this->list_job_chain_nodes,$n);
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
  
    if (count($this->list_job_chain_nodes) > 0){
      foreach ($this->list_job_chain_nodes as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Job_list_job_chain_nodes

//-----------------------------------------------------------------------------------------------------------------------
//            SOS_Scheduler_Job_list_file_order_source
//-----------------------------------------------------------------------------------------------------------------------


/**
* A list of all file_order_sources for a job_chain
* 
* An Object of this class is created, when using the method file_order_source($dir,$state)
* Sample:
*   
*    $job_chain->file_order_source("/anyDir","100");
*
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job_chain> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_list_file_order_source {

  
   var $list_file_order_source             = array();

/**
* adds a file_order_source-element to the list of file_order_sources. 
* Use $job_chain->file_order_source($dir,$state)
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/ 
 
  function addFileOrderSource($f){
   	 $this->list_file_order_source[$f->directory] = $f;
   }
  
/**
* Returns a xml representation for all file_order_source elements.
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
  
    if (count($this->list_file_order_source) > 0){
      foreach ($this->list_file_order_source as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Job_list_file_order_source


//-----------------------------------------------------------------------------------------------------------------------
//            SOS_Scheduler_Job_list_file_order_sink
//-----------------------------------------------------------------------------------------------------------------------


/**
* A list of all file_order_sinks for a job_chain
* 
* An Object of this class is created, when using the method file_order_sink($state)
* Sample:
*   
*    $job_chain->file_order_sink("100");
*
* 
*
* The major task of this object is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <job_chain> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Job_list_file_order_sink {

  
   var $list_file_order_sink             = array();

/**
* adds a file_order_sink-element to the list of file_order_sinks. 
* Use $job_chain->file_order_sink($state)
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/ 
 
  function addFileOrderSink($f){
   	 $this->list_file_order_sink[$f->state] = $f;
   }
  
/**
* Returns a xml representation for all file_order_sink elements.
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
  
    if (count($this->list_file_order_sink) > 0){
      foreach ($this->list_file_order_sink as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Job_list_file_order_sink

?>