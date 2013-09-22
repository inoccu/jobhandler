<?php

  /**
  * Job Scheduler PHP Interface HOWTO
  * Step 1. Creating Objects e.g. for a Job
  * Step 2. Setting properties
  * Step 3. Sending XML to Scheduler 
  * Samples for Job Scheduler PHP Interface usage
  *
  * @copyright    SOS GmbH
  * @author       Uwe Risse <uwe.risse@sos-berlin.com>
  * @since        1.0-2005/08/17
  *
  * @access       public
  * @package      SCHEDULER
  */


  /**
  * create instance of an class given in first parameter (e.g. job, job chain, order) 
  * All files of the Job Scheduler php interface have to be saved in one directory 
  *
  * @param    string   $class class for object (SOS_Scheduler_Job, SOS_Scheduler_Job_Chain, SOS_Scheduler_Order)
  * @param    string   $include_path path to include the class
  * @param    string   $extension file name extension for API classes
  * @access   public
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */
  function &get_instance($class, $include_path='scheduler/', $extension='.inc.php') {
    if ( !class_exists($class) ) { include( $include_path . strtolower($class) . $extension ); }
    $object = new $class;
    return $object;    
  }
 
  // All files of the Job Scheduler php interface have to be saved in one directory below the include_path
  // In this example, there exists an folder packages and all files are saved in packages/scheduler
 	ini_set( 'include_path', 'packages' );


 
  // Howto add an order to an existing jobchain
  
  $order_launcher = &get_instance('SOS_Scheduler_OrderCommand_Launcher','scheduler/');
 
  //Create an add_order object (SOS_Scheduler_Command_Add_Order).
  $order = $order_launcher->add_order('jobchain',100);
  
  //Setting some properties of the order object
  $order->id='sostest_12';
  $order->replace="yes";
  $order->priority="10";  
  $order->title="Testorder";  
  $order->web_service="";  
  $order->at="now+60";
  $order->addParam('test','any value');

  // Sending XML to the Job Scheduler
  if (!$order_launcher->execute($order)) { echo 'error occurred adding order: ' . $order_launcher->get_error(); exit; } 
  


  // Howto remove an order
  $order_launcher = $scheduler->get_instance('SOS_Scheduler_OrderCommand_Launcher','scheduler/');
  
  
  // First, we create an add_order (SOS_Scheduler_Command_Add_Order) for the jobchain
  $order = $order_launcher->add_order('jobchain',100);
  $order->replace='yes';
  $order->id='sostest_13';
  $order->run_time()->single_start="23:00";
  if (!$order_launcher->execute($order)) { echo 'error occurred adding order: ' . $order_launcher->get_error(); exit; } 

  // Just added order will be removed
  $order = $order_launcher->remove_order('jobchain', 'sostest_13');
  
  // Sending XML to the Job Scheduler
  if (!$order_launcher->execute ($order)) { echo 'error occurred removing order: ' . $order_launcher->get_error(); exit; } 
  
    
  
  //Howto change an existing order
  
  //starting with adding an order.
  $order_launcher = $scheduler->get_instance('SOS_Scheduler_OrderCommand_Launcher','scheduler/');
  $order = $order_launcher->add_order('jobchain',300);
  $order->replace='yes';
  $order->id='sostest_14';
  $order->run_time()->single_start="22:00";
  if (!$order_launcher->execute($order)) { echo 'error occurred adding order: ' . $order_launcher->get_error(); exit; } 
  
  //Now change the order.state
   $order = $order_launcher->modify_order('jobchain','sostest_14');
   $order->state=100;
   if (!$order_launcher->execute($order)) { echo 'error occurred modifying order: ' . $order_launcher->get_error(); exit; } 
 
  
 
  //Howto start an order with submit. This can be usefull, when you know orders jobchain, id, state and starttime.
 
  $order_launcher = $scheduler->get_instance('SOS_Scheduler_OrderCommand_Launcher','scheduler/');
  if (! $order_launcher->submit('jobchain','sostest_15',200,'now+30')) { echo 'error occurred submitting order: ' . $order_launcher->get_error(); exit; } 
	
    
      
 // Howto add a job

  $job = $scheduler->get_instance('SOS_Scheduler_Job','scheduler/');
  
  //Setting some properties
  $job->force_idle_timeout   	= "yes";
  $job->idle_timeout  	      = "1000";
  $job->ignore_signals  	    = "all";
  $job->java_options  	      = "java";
  $job->min_tasks  	          = "2";
  $job->name  	              = "test_jobname3";	
  $job->order  	              = "no";
  $job->priority   	          = "1"	;
  $job->stop_on_error  	      = "no";	
  $job->tasks  	              = "4";
  $job->temporary  	          = "no";	 
  $job->timeout             	= "10";
  $job->title  	              = "mein job";
  $job->visible  	            = "yes";	
  
  
  //Defining some parameters
  $job->addParam('var1','value1');
  $job->addParam('var2','value2');
 
 //Set the implentation
  $job->script('javascript')->script_source='a=1;';
 
 //The job has a runtime
   $job->run_time()->period()->single_start = "10:00";
   $job->run_time()->period()->single_start = "11:00";
  
   $job->run_time()->at('2006-12-24 12:20');
   $job->run_time()->at('2006-12-24 12:25');
   $job->run_time()->at('2006-12-24 12:35');
  
   /** A period forr day=1 */
   $p = $job->run_time()->weekdays('1')->period();
   $p->single_start = '07:30';
 
 
   $job_command = $scheduler->get_instance('SOS_Scheduler_JobCommand_Launcher','scheduler/');
   
   //First removing the job    
   $job_command->remove($job->name);
   if (! $job_command->add_jobs($job))  { echo 'error occurred adding job: ' . $job_command->get_error(); exit; } 

 
  
 // Howto to delete a job 
  // First we add the job to have one, which we can remove
  $job = $scheduler->get_instance('SOS_Scheduler_Job','scheduler/');
  
  $job->name  	              = "jobtoberemoved";	
  $job->title  	              = "my removed job";
  $job->visible  	            = "yes";	

  $job_command = $scheduler->get_instance('SOS_Scheduler_JobCommand_Launcher','scheduler/');
  if (! $job_command->add_jobs($job)) { echo 'error occurred adding job: ' . $job_command->get_error(); exit; } 
  
  //Now the job will be removed
  if (! $job_command->remove($job->name)) { echo 'error occurred removing job: ' . $job_command->get_error(); exit; } 
  
   
 // Starting a job
  // First we add the job to have one, which we can start

  $job = $scheduler->get_instance('SOS_Scheduler_Job','scheduler/');
  $job->name  	              = "test_jobname3";	
  $job->title  	              = "my job";
  $job->visible  	            = "yes";	
   
  $job->script('javascript')->script_source='a=1';
 
  $job_command = $scheduler->get_instance('SOS_Scheduler_JobCommand_Launcher','scheduler/');

  if (! $job_command->add_jobs($job))  { echo 'error occurred adding job: ' . $job_command->get_error(); exit; } 
  if (! $job_command->start($name='test_jobname3', $start_at="now" )) { echo 'error occurred submitting job: ' . $job_command->get_error(); exit; } 
 
  

  
  
  
?>