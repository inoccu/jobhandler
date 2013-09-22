<?php

require_once( 'sos_scheduler_object.inc.php'); 
require_once( 'sos_scheduler_command.inc.php'); 
require_once( 'sos_scheduler_runtime_elements.inc.php'); 


/**
* Instance of a runtime definition in a job or in an order.
* 
* Objects of this class are created, when using the method runtime() in the class SOS_Scheduler_Job().
* Sample:
*   
*    $runtime = $job->run_time()
* 
* You can also create the runtime via the constructor
*
*    $runtime = new SOS_Scheduler_Runtime();
*
* The major task of runtime-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <add_jobs>-command.
* The classes SOS_Scheduler_Job and SOS_Scheduler_Order have a run_time propertie which are implicit created when 
* using the method run_time().
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
* @package Job_Scheduler
*/
class SOS_Scheduler_Runtime extends SOS_Scheduler_Object {

  /** @access public */

  /** Begin of the period
  *   
  * begin="hh:mm[:ss]"    (Initial value: 00:00)    
  * Should the <run_time> element be empty (i.e. it does not contain a <period>), then the Job Scheduler will generate an operating period using this setting. This setting is also the default setting for the <run_time> child elements.
  * @access public
  */
  var $begin               = '';

  /** End of the period  
  * end="hh:mm[:ss]"    (Initial value: 24:00)    
  * Should the <run_time> element be empty (i.e. it does not contain a <period>), then the Job Scheduler will generate an operating period using this setting. This setting is also the default setting for the <run_time> child elements.
  * @access public
  */  var $end                  = '';

  /** Job will continue, when period-end is reached
  * let_run="yes_no"   
  *This attribute may only be specified for jobs and not for orders.
  *Should the <run_time> element be empty (i.e. it does not contain a <period>), then the Job Scheduler will generate an operating period using this setting. This setting is also the default setting for the <run_time> child elements 
  * @access public
  */
  var $let_run               = 0;

  
  /** start job at scheduler-start 
  * once="yes_no"    (Initial value: no)    
  * When once="yes" the Scheduler starts a job once after the start of the Scheduler, in so far as this is allowed by the <run_time.
  * @access public
  */
  var $once               = '';

  /** starts the job every x seconds 
  *repeat="hh:mm[:ss] or seconds"   
  *Should the <run_time> element be empty (i.e. it does not contain a <period>), then the Job Scheduler will generate an operating period using this setting. This setting is also the default setting for the <run_time> child elements
  * @access public
  */
  var $repeat          = '';

  /** single point of start 
  *single_start="hh:mm[:ss]"   
  *Should the <run_time> element be empty (i.e. it does not contain a <period>), then the Job Scheduler will generate an operating period using this setting. This setting is also the default setting for the <run_time> child elements
  * @access public
  */
  var $single_start                  = '';

  /** The list of periods (Instance of SOS_Scheduler_Runtime_Periods). Periods are represented by objects of the SOS_Scheduler_Runtime_Period
  *   you can add a period with
  *   $job->runtime()->period();
  * or with
  *   $job->runtime()->addPeriod($period); 
  * where $period is an instance of SOS_Scheduler_Runtime_Period
  * You get a xml-representation of all periods with $periods->asString();
  * @access public
  */
  var $periods = null;

  /** The list of dates (Instance of SOS_Scheduler_Runtime_Dates). Dates are represented by objects of the SOS_Scheduler_Runtime_Date
  *   you can add a date with
  *   $job->runtime()->date('YYYY-MM-DD')    
  *   You get a xml-representation of all dates with $dates->asString();
  * @access public
  */
  var $dates = null;



  /** The list of weekdays (Instance of SOS_Scheduler_Runtime_Days). Weekdays are represented by objects of the SOS_Scheduler_Runtime_Day
  *   you can add a weekdays with
  *   $job->runtime()->weekdays('YYYY-MM-DD')    
  *   You get a xml-representation of all weekdays with $weekdays->asString();
  * @access public
  */
  var $weekdays = null;
  
  /** The list of monthdays (Instance of SOS_Scheduler_Runtime_Days). Weekdays are represented by objects of the SOS_Scheduler_Runtime_Day
  *   you can add a monthdays with
  *   $job->runtime()->monthdays($day) where $d is a daynumber
  *   You get a xml-representation of all monthdays with $monthdays->asString();
  * @access public
  */
  
  var $monthdays = null;

  /** The list of ultimos (Instance of SOS_Scheduler_Runtime_Days). Weekdays are represented by objects of the SOS_Scheduler_Runtime_Day
  *   you can add a ultimos with
  *   $job->runtime()->ultimos($day) where $d is a daynumber
  *   You get a xml-representation of all ultimos with $ultimos->asString();
  * @access public
  */
  
  var $ultimos  = null;

  /** The list of holidays (Instance of SOS_Scheduler_Runtime_Holidays). Holidays are represented by objects of the SOS_Scheduler_Runtime_Holiday
  *   you can add a holidays with
  *   $job->runtime()->holidays($d) where $d is a date in the form 'yyyy-mm-dd'
  *   You get a xml-representation of all holidays with $holidays->asString();
  * @access public
  */
  
  var $holidays = null;

 /** The list of ats (Instance of SOS_Scheduler_Runtime_Ats). Ats are represented by objects of the SOS_Scheduler_Runtime_At
  *   you can add an at with
  *   $job->runtime()->ats($d) where $d is a date in the form 'yyyy-mm-dd hh:mm:ss' e.g 12:00
  *   You get a xml-representation of all ats with $ats->asString();
  * @access public
  */

  var $ats = null;
   

 
  
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
* Adds an instance of a period in the runtime.
* Periods is created, when using the method period()
* Sample:
*   
*    $period = $job->run_time()->period();
* 
*
* The major task of periods is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime have a periods propertie which is implicit created when 
* using the method period().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Period period
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/
  function period(){
  	if ($this->periods == null){
  		$this->periods = new SOS_Scheduler_Runtime_Periods();
  	}
    
    $period=$this->periods->addPeriod();
  	return $period;
  }

/**
* Adds an instance of a date in the runtime.
* Dates is created, when using the method date()
* Sample:
*   
*    $date = $job->run_time()->date('yyyy-mm-dd');
* 
*
* The major task of dates is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a dates propertie which is implicit created when 
* using the method date().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Date date
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/
  function date($d){
  	if ($this->dates == null){
  		$this->dates = new SOS_Scheduler_Runtime_Dates();
  	}
    $date=$this->dates->addDate($d);
    
  	return $date;
  }
  
/**
* Adds an instance of a holiday in the runtime.
* Holidays is created, when using the method holiday()
* Sample:
*   
*    $holiday = $job->run_time()->holidays('yyyy-mm-dd');
* 
*
* The major task of holidays is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a holidays propertie which is implicit created when 
* using the method holiday().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Holiday holiday
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
    function holidays($d){
  	if ($this->holidays == null){
  		$this->holidays = new SOS_Scheduler_Runtime_Holidays();
  	}
    $holiday=$this->holidays->addHoliday($d);
    
  	return $holiday;
  }
  
  
/**
* Adds an instance of a weekday in the runtime.weekdays is created, when using the method weekdays()
* Sample:
*   
*    $weekday = $job->run_time()->weekdays('7');
* 
*
* The major task of weekdays is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a weekdays propertie which is implicit created as an object of SOS_Scheduler_Runtime_Day when 
* using the method weekdays().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Day day
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
     
  function weekdays($d){
  	if ($this->weekdays == null){
  		$this->weekdays = new SOS_Scheduler_Runtime_Days('weekdays');
  	}
    $day=$this->weekdays->addDay($d);
    
  	return $day;
  }
  
/**
* Adds an instance of a monthday in the runtime.
* monthdays is created, when using the method monthdays()
* Sample:
*   
*    $monthday = $job->run_time()->monthdays('17');
* 
*
* The major task of monthdays is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a monthdays propertie which is implicit created as an object of SOS_Scheduler_Runtime_Day when 
* using the method monthdays().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Day day
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
   

  function monthdays($d){
  	if ($this->monthdays == null){
  		$this->monthdays = new SOS_Scheduler_Runtime_Days('monthdays');
  	}
    $day=$this->monthdays->addDay($d);
    
  	return $day;
  }

/**
* Adds an instance of a ultimo in the runtime.
* ultimos is created, when using the method ultimos()
* Sample:
*   
*    $ultimo = $job->run_time()->ultimos('17');
* 
*
* The major task of ultimos is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a ultimos propertie which is implicit created as an object of SOS_Scheduler_Runtime_Day when 
* using the method ultimos().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_Day day
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
 
  function ultimos($d){
  	if ($this->ultimos == null){
  		$this->ultimos = new SOS_Scheduler_Runtime_Days('ultimos');
  	}
    $day=$this->ultimos->addDay($d);
    
  	return $day;
  }


/**
* Adds an instance of a aT in the runtime.
* ATS is created, when using the method ats()
* Sample:
*   
*    $at = $job->run_time()->at('11:12');
* 
*
* The major task of ats is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the runtime element).
* The class SOS_Scheduler_Runtime has a ats propertie which is implicit created as an object of SOS_Scheduler_Runtime_Day when 
* using the method ats().
*
*
*
* @access public
* @return SOS_Scheduler_Runtime_At at
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
   
    function at($d){
  	if ($this->ats == null){
  		$this->ats = new SOS_Scheduler_Runtime_Ats();
  	}
    $at=$this->ats->addAt($d);
    
  	return $at;
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
   $s .= addAttribute('begin',$this->begin);
   $s .= addAttribute('end',$this->end);
   $s .= addAttribute('let_run',$this->let_run);
   $s .= addAttribute('once',$this->once);
   $s .= addAttribute('repeat',$this->repeat);
   $s .= addAttribute('single_start',$this->single_start);

   return $s;
  }

/**
* Returns a xml representation for the runtime element.
*
*
* @access public
* @return String Runtime in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  

  function asString(){

  	$s = '<run_time' . $this->attributes(). '>';
  	if ($this->periods != null)	$s .= $this->periods->asString();
  	if ($this->ats != null)$s .= $this->ats->asString();
  	if ($this->dates != null)$s .= $this->dates->asString();
  	if ($this->weekdays != null)$s .= $this->weekdays->asString();
  	if ($this->monthdays != null)$s .= $this->monthdays->asString();
  	if ($this->ultimos != null)$s .= $this->ultimos->asString();
  	if ($this->holidays != null)$s .= $this->holidays->asString();

    $s .= '</run_time>';
    return $s;
 
  }
    
} // end of class SOS_Scheduler_Runtime

?>