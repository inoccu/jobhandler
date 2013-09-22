<?php
require_once( 'sos_scheduler_object.inc.php'); 


/**
* Instance of a Lock XML-Defintion in the Job Scheduler.
* <p>An Instance is created with  $lock = new SOS_Scheduler_Lock("myLock");</p>
*
* <p> you can create a lock e.g. for use in the modify_hot_folder_command->store($lock) method
*
* Example:
* <pre>
*   $lock = new SOS_Scheduler_lock("myLock");
*
*  //Setting some properties
*  $lock->max_non_exclusive           = 1; 
* 
*   /Adding the job to the hotfolder
*    $modify_hot_folder_command = &get_instance('SOS_Scheduler_HotFolder_Launcher','scheduler/');
*    if (! $xml=$modify_hot_folder_command->store($lock,"./test"))  { echo 'error occurred adding lock: ' . $modify_hot_folder_command->get_error(); exit; } 
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

class SOS_Scheduler_Lock extends SOS_Scheduler_Object {

  
  /** name="lockname"   
  *
  * Every lock has a unique name.
  * @access public
  */
  var $name                   = '';
 

  /** max_non_exclusive=6"   
  *
  * @access public
  *  Begrenzung der nicht-exklusiven Belegungen
  * Die Voreinstellung ist unbegrenzt, es können also mit <lock.use exclusive="no"> beliebig viele nicht-exklusive Tasks gestartet werden (aber nur eine exklusive). 
  */
  var $max_non_exclusive              = 0;

 
 function SOS_Scheduler_Lock( $name="" ) {
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
   $s .= addAttribute('max_non_exclusive',$this->max_non_exclusive);
 
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
  	$s = '<lock' . $this->attributes(). '>';
    $s .= '</lock>';

    return $s;
 
  }
    
} // end of class SOS_Scheduler_Lock

?>