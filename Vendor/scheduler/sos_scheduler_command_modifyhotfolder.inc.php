<?php   
/**
* Instance of a modify_hot_folder-command
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_HotFolder_Launcher
* </p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_HotFolder_Launcher.execute()
* Objects of the class SOS_Scheduler_HotFolder_Launcher create an object of this class in the method execute().</p>
* <p>It supports the reprentation of <modify_hot_folder> element. </p>
*
* <pre>
*   Creating a job in a hot_folder
*    $job = new 'SOS_Scheduler_Job';
*    $job->name         = "my_job";
*    $job->visible      = "yes";  
*
*    Creating &lt;add_job&gt; &lt;job....&gt; &lt;/add_job&gt;
*    $c = new SOS_Scheduler_CommandModifyHotFolder($job,"./");
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

class SOS_Scheduler_Command_ModifyHotFolder{


  
  /** Instance of SOS_Scheduler_Job, SOS_Scheduler_Order */
  var $object               = null;
  var $folder               = "";

  /**
  * constructor
  * @param  SOS_Scheduler_Job job
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2006/11/1
  */

  function SOS_Scheduler_Command_ModifyHotFolder( $object,$folder ) {
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
    $s  = '<modify_hot_folder folder="'.$this->folder.'">' . $this->object->asString() . '</modify_hot_folder>';
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
  

} // end of class SOS_Scheduler_CommandModifyHotFolder


?>
