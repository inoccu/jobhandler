<?php   
require_once( 'sos_scheduler_job_elements.inc.php');

/**
* Instance of a add_order-command
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_OrderCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_OrderCommand_Launcher.add_order()
* Objects of the class SOS_Scheduler_OrderCommand_Launcher create an object of this class in the method add_order().</p>
* <p>It supports the reprentation of <add_order> element. </p>
*
* <pre>
*    Creating &lt;add_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $c = new SOS_Scheduler_Command_Add_Order($job_chain);
*     $c->state = '100';
*     $xml_command = $c->asString();
* </pre>
*<p> The same result has:</p>
* <pre>
*    //Creating &lt;add_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $launcher = new SOS_Scheduler_OrderCommand_Launcher();
*     $c = $launcher->add_order('my_chain','100');
*     $xml_command = $c->asString();
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/
class SOS_Scheduler_Command_Add_Order extends SOS_Scheduler_Object {

  /** id="id"   
  *
  * The alphanumerical identification of the order. (Note that this parameter may not be set to id - which is an XML reserved word
  */
  var $id                     = 0;

  /** job_chain="name"   
  *
  * The name of the job chain in which the order is being processed. 
  */
  var $job_chain              = '';

  /** processing priority 
  */
  var $priority               = '';

  /** title="text"     
  * 
  * The title of the order. 
  */
  
  var $title                  = '';


  /** Instance of SOS_Scheduler_Job_params */
  var $params                 = null;

  /** Instance of SOS_Scheduler_Runtime
  */
  var $run_time               = null;

  /** max. processing time of this order */
  var $timeout                = 0;

  /** at="timestamp"    (Initial value: now)    
  *
  *  Order Starting Time "now", "yyyy-mm-dd HH:MM[:SS]", "now + HH:MM[:SS]" and "now + SECONDS" are possible. 
  */
  var $at   = '';
  
  /** replaces an order with the same id */
  var $replace    = 'no'; 
  
  /** state="text"   
  *
  * state of the job. Defines the startjob in the jobchain 
  */
  var $state    = '';

  var $job = '';

  /** web_service="name"   
   *
   * When an order has been completed and the end of the job chain reached, it is then transformed with a style sheet and forwarded to a Web Service.
   */  
  var $web_service = '';


/**
  * constructor
  *
  * @param  String job_chain, the name of the job_chain
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function SOS_Scheduler_Command_Add_Order( $job_chain ) {
    $this->job_chain = $job_chain;

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
  
  /**
  * adds a param to the list of params. 
  *
  * create an instance of the class SOS_Scheduler_Job_params class implicite.
  *
  * @access private
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
  
/** Create this->run_time if does not exist.
* 
*        $add_order->run_time()->at('12:20"');
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
 

/** builds a list of the given attributes.
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

   $s .= addAttribute('at',$this->at);
   $s .= addAttribute('id',$this->id);
   $s .= addAttribute('job',$this->job);
   $s .= addAttribute('job_chain',$this->job_chain);
   $s .= addAttribute('priority',$this->priority);
   $s .= addAttribute('replace',$this->replace);
   $s .= addAttribute('state',$this->state);
   $s .= addAttribute('title',$this->title);
   $s .= addAttribute('web_service',$this->web_service);
    
   return $s;
  }
  

  
 
/**
* Returns a xml representation for the add_order element.
*
*
* @access public
* @return String add_order in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      

  function asString(){
    $s = '<add_order ' . $this->attributes() . '>';
  
    if ($this->params != null)    $s .= $this->params->asString();
    if ($this->run_time != null)  $s .= $this->run_time->asString();
  
    $s .= '</add_order>';
    return $s;
  }
      
} // end of class SOS_Scheduler_Command_Add_Order


//============================================================================================================
// 

  class SOS_Scheduler_Command_Order extends SOS_Scheduler_Command_Add_Order {
  	/**
* Returns a xml representation for the order element.
* for storing to a hot_folder
*
* @access public
* @return String order in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      

  function asString(){
    $s = '<order ' . $this->attributes() . '>';
  
    if ($this->params != null)    $s .= $this->params->asString();
    if ($this->run_time != null)  $s .= $this->run_time->asString();
  
    $s .= '</order>';
    return $s;
  }
} // end of class SOS_Scheduler_Command_Add_Order


//============================================================================================================

/**
* Instance of a remove_order-command
*
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_OrderCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_OrderCommand_Launcher.remove_order()
* Objects of the class SOS_Scheduler_OrderCommand_Launcher create an object of this class in the method remove_order($job_chain,$id).</p>
* <p>It supports the reprentation of <remove_order> element. </p>
*
* <pre>
*    //Removing order '100' from job_chain 'my_chain ($xml_command is to be executed)
*    //Creating &lt;remove_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $c = new SOS_Scheduler_Command_Remove_Order();
*     $c->job_chain = 'my_chain';
*     $c->id = '100';     
*     $xml_command = $c->asString();
* </pre>
*<p> The same result has:</p>
* <pre>
*    //Removing order '100' from job_chain 'my_chain ($xml_command is to be executed)
*    //Creating &lt;remove_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $launcher = new SOS_Scheduler_OrderCommand_Launcher();
*     $c = $launcher->remove_order('my_chain','100');
*     $xml_command = $c->asString();
* </pre>

*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/

class SOS_Scheduler_Command_Remove_Order extends SOS_Scheduler_Object {

  

  /** order_id */
  var $id                     = 0;

  /** name of the job chain */
  var $job_chain              = '';


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

   $s .= addAttribute('order',$this->id);
   $s .= addAttribute('job_chain',$this->job_chain);
    
   return $s;
  }
  
/**
* Returns a xml representation for the remove_order element.
*
*
* @access public
* @return String add_order in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  function asString(){
    $s = '<remove_order ' . $this->attributes() . '/>';
     return $s;
  }
  
 
    
} // end of class SOS_Scheduler_Command_Add_Order

//============================================================================================================

/**
* Instance of a modify_order-command
*
* <p>Objects of this class are used to be execute with an Instance of SOS_Scheduler_OrderCommand_Launcher
* or as an entry in <commands> SOS_Scheduler_Job_list_commands</p>
* <p>This class is mostly used internally e.g. in SOS_Scheduler_OrderCommand_Launcher.modify_order()
* Objects of the class SOS_Scheduler_OrderCommand_Launcher create an object of this class in the method modify_order($job_chain,$id) {.</p>
* <p>It supports the reprentation of <modify_order> element. </p>
*

* <pre>
*    //Creating &lt;modify_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $c = new SOS_Scheduler_Command_Modify_Order();
*     $c->job_chain = 'my_chain';
*     $c->id = '100';     
*     $c->state=2;    
*     $xml_command = $c->asString();
* </pre>
*<p> The same result has:</p>
* <pre>
*    //Creating &lt;modify_order&gt; &lt;id=....&gt; &lt;/&gt;
*     $launcher = new SOS_Scheduler_OrderCommand_Launcher();
*     $c = $order_launcher->modify_order('my_chain','100');
*     $c->state=2;
*     $xml_command = $c->asString();
* </pre>
*
* @copyright    SOS GmbH
* @author       Uwe Risse <uwe.risse@sos-berlin.com>
* @since        1.0-2005/08/10
*
* @access       public
* @package      Job_Scheduler
*/


class SOS_Scheduler_Command_Modify_Order extends SOS_Scheduler_Object {

  

  /** object key */
  var $id                     = 0;

  /** name of the job chain */
  var $job_chain              = '';
  var $priority               = '';
  var $state                  = '';
  
  /** Instance of SOS_Scheduler_Runtime */
  var $runtime                = null;

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

   $s .= addAttribute('order',$this->id);
   $s .= addAttribute('job_chain',$this->job_chain);
   $s .= addAttribute('priority',$this->priority);
   $s .= addAttribute('state',$this->state);
    
   return $s;
  }
  
/**
* Create this->run_time if does not exist.
* 
*        $modify_command->run_time()->at('12:20"');
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
* Returns a xml representation for the modify_order element.
*
*
* @access public
* @return String add_order in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/      
  function asString(){
    $s = '<modify_order ' . $this->attributes() . '>';
    if ($this->runtime != null) $s .= $this->run_time->asString();
    $s .= '</modify_order>';
     return $s;
  }
  
 
    
} // end of class SOS_Scheduler_Command_Add_Order
?>
