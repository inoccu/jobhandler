<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_job_chain_elements.inc.php'); 


/**
* Instance of a Job_Chain XML-Defintion in the Job Scheduler.
 * <p>An Instance is created with  $job_chain = new SOS_Scheduler_Job_Chain();</p>
*
* <p> you can create a job_chain e.g. for use in the add_job_chain($job_chain) method
*
* Example:
* <pre>
*   $job_chain = new SOS_Scheduler_Job_Chain();
*
*  //Setting some properties
*  $job_chain->name           = "myJob_Chain"; 
* 
* 
*   //The job has a file_order_sources 
*   $job_chain->file_order_source("/myDir","1");
*   $job_chain->file_order_source("/myOtherDir","2");
*
*   //The job has a file_order_sinks
*   $job_chain->file_order_sink("6")->remove="yes";
*   $job_chain->file_order_sink("99")->remove="yes";
*   $job_chain->file_order_sink("999")->remove="yes";
* 
*   //Adding some Job_chain_nodes
*    $job_chain->add_job("my_job1","1","2","99");
*    $job_chain->add_job("my_job2","2","3","99");
*    $job_chain->add_job("my_job3","3","4","99");
*    $job_chain->add_job("my_job4","4","5","999");
*    $job_chain->add_job("my_job5","5","6","999");
*
* //Adding the job_chain to the hotfolder
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

class SOS_Scheduler_Job_Chain extends SOS_Scheduler_Object {
 
  /** distributet=""yes|no""   
  *
  * Only works when specified in conjunction with -distributed-orders  
  * and causes orders to be distributed over more than one Job Scheduler.
  *
  * distributed="no" prevents a job chain from being processed by more than one Job Scheduler. 
  * Instead, allows the job chain to be processed on the one Job Scheduler, as if it were in 
  * a non-distributed environment. Note that in this situation, the name of the job chain muss be 
  * unique in the cluster (Note that this is not checked by the Job SCheduler).* @access public
  */
  var $distributet                   = '';

  /** name="string"   
  *
  *  The name of the job chain. Note that a job chain can only be defined once.
  * @access public
  */
  var $name                  = '';

  /** orders_recoverable="yes|no"   
  *
  * orders_recoverable="yes"
  *
  * When the Job Scheduler has been configured to store orders in the database, 
  * as soon as an order is added to a job's order queue it will be also be stored in the database. 
  * After the order has completed the job chain, it will be deleted from the database
  * 
  * The Job Scheduler loads orders from the database on starting and setting up the job chains. 
  *
  *
  * This attribute does not function when the Job Scheduler has been configured to work without a database - 
  *
  * orders_recoverable="no"
  *
  * The Job Scheduler does not store orders in or load orders from a database.  * @access public
  */
  var $orders_recoverable               = "";


  /** visible=""yes|no|never"   
  *
  *  visible="no" and visible="never" make a job chain invisible in the results of <show_job_chains> and <show_state>.
  * The Job Scheduler makes a job chain visible as soon as an order has been added to the chain.
  *
  * @access public
  */
  var $visible          = '';
  
  /** file_order_source=array()  
  *
  * @access public
  */
  var $file_order_source                  = null;

  /** file_order_sink=array()    The time allowed for an operation
  *
  * @access public
  */
  var $file_order_sink                = null;

  /** job_chain_nodes=array()   
  *
  * List of the nodes of the job chain
  * @access public
  */
  var $job_chain_nodes           = null;
 
   

 
  
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
* Creates this->job_chain_nodes if does not exist.
* Adds a new SOS_Scheduler_Job_Chain_Node in the list of job_chain_nodes.
* 
*     $job->add_node($node);
*
* @access public
* @param String SOS_Scheduler_Job_Chain_Node node
* @return SOS_Scheduler_Job_Chain_Node the new added SOS_Scheduler_Job_Chain_Node
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function add_node($node){
 	  if ($this->job_chain_nodes==null){
  		$this->job_chain_nodes=new SOS_Scheduler_Job_list_job_chain_nodes();
  	} 	
  	$this->job_chain_nodes->addNode($node);
  	return $node;
  }
  
   
/**
* Creates this->job_chain_nodes if does not exist.
* Creates a new SOS_Scheduler_Job_Chain_Node in the list of job_chain_nodes.
* 
*     $job->add_job("myJob","1","2","99");
*
* @access public
* @param String delay
* @param String error_count
* @return SOS_Scheduler_Job_list_delay_after_error the new added delay_after_error
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function add_job($jobname,$state,$next_state,$error_state){
 	  if ($this->job_chain_nodes==null){
  		$this->job_chain_nodes=new SOS_Scheduler_Job_list_job_chain_nodes();
  	} 	
  	
    $job_chain_node = new SOS_Scheduler_Job_Chain_Node();
    $job_chain_node->job            = $jobname;
    $job_chain_node->state  	      = $state; 	
    $job_chain_node->next_state  	  = $next_state; 	
    $job_chain_node->error_state  	= $error_state; 	
  	$this->job_chain_nodes->addNode($job_chain_node);
  	return $d;
  }

/**
* Creates this->job_chain_nodes if does not exist.
* Creates a new SOS_Scheduler_Job_Chain_Node in the list of job_chain_nodes.
* 
*     $job->add_job("myJob","1","2","99");
*
* @access public
* @param String delay
* @param String error_count
* @return SOS_Scheduler_Job_list_delay_after_error the new added delay_after_error
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function add_job_chain($job_chain_name,$state,$next_state,$error_state){
 	  if ($this->job_chain_nodes==null){
  		$this->job_chain_nodes=new SOS_Scheduler_Job_list_job_chain_nodes();
  	} 	
  	
    $job_chain_node = new SOS_Scheduler_Job_Chain_Node_Job_Chain();
    $job_chain_node->job_chain      = $job_chain_name;
    $job_chain_node->state  	      = $state; 	
    $job_chain_node->next_state  	  = $next_state; 	
    $job_chain_node->error_state  	= $error_state; 	
  	$this->job_chain_nodes->addNode($job_chain_node);
  	return $d;
  }

/**
* Creates this->job_chain_nodes if does not exist.
* Creates a new SOS_Scheduler_Job_Chain_Node in the list of job_chain_nodes.
* 
*     $job->add_job("myJob","1","2","99");
*
* @access public
* @param String delay
* @param String error_count
* @return SOS_Scheduler_Job_list_delay_after_error the new added delay_after_error
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
 
  function add_end_node($state){
 	  if ($this->job_chain_nodes==null){
  		$this->job_chain_nodes=new SOS_Scheduler_Job_list_job_chain_nodes();
  	} 	
  	
    $job_chain_node = new SOS_Scheduler_Job_Chain_End_Node($state);
  	$this->job_chain_nodes->addNode($job_chain_node);
  	return $d;
  }



 
/**
* Creates this->file_order_source if does not exist.
 
* 
*     $job->file_order_source('javascript')->include='include_file';
*
* @access public
* @param String language
* @return SOS_Scheduler_Job_Script the new script
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  
  function file_order_source($directory,$state){
  	if ($this->file_order_source == null){
  		$this->file_order_source = new SOS_Scheduler_Job_list_file_order_source();
  	}
  	$f = new SOS_Scheduler_File_Order_Source($directory,$state);
  	$this->file_order_source -> addFileOrderSource($f);
  	return $f;
  }

/**
* Creates this->file_order_sink if does not exist.
 
* 
*     $job->file_order_sink($state)->remove='yes';
*
* @access public
* @param String language
* @return SOS_Scheduler_Job_Script the new script
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  
  function file_order_sink($state){
   	if ($this->file_order_sink == null){
  		$this->file_order_sink = new SOS_Scheduler_Job_list_file_order_sink();
  	}
  	$f = new SOS_Scheduler_File_Order_Sink($state);
  	$this->file_order_sink -> addFileOrderSink($f);
  	return $f;  #
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
   $s .= addAttribute('distributed',$this->distributed);
   $s .= addAttribute('name',$this->name);
   $s .= addAttribute('orders_recoverable',$this->orders_recoverable);
   $s .= addAttribute('visible',$this->visible);
 
   return $s;
  }

/**
* Returns a xml representation for the job_chain element.
*
*
* @access public
* @return String Job in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s = '<job_chain' . $this->attributes(). '>';
  	if ($this->file_order_source != null)	$s .= $this->file_order_source->asString();
  	if ($this->file_order_sink != null)	$s .= $this->file_order_sink->asString();
  	if ($this->job_chain_nodes != null)$s .= $this->job_chain_nodes->asString();
    $s .= '</job_chain>';

    return $s;
 
  }
    
} // end of class SOS_Scheduler_Job_Chain

?>