<?php
/**
* Instance of a period definition in a runtime, weekdays, ultimos, monthdays or dates element.
* 
* Objects of this class are created and added to a list, when using the method period() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $period = $job->run_time()->period()
*    $job->run_time()->monthdays('28')->period()->single_start='00:01';
*
* or you create the period and then set the properties
* 
*   $period = $job->run_time()->weekdays(2)->period();
*   $period->begin='12:00';
*   $period->end='13:00';
*
* 
* You can also create the period via the constructor
*
*    $period = new SOS_Scheduler_Runtime_Period();
*    $period->begin='12:00';
*    $period->end='13:00';
*
* and than add this period to e.g. a date.
*
*    $job->run_time()->date('2006-30-11')->addPeriod($period);
*
* or a day
*
*    $job->run_time()->monthdays('22')->addPeriod($period);
*
* The major task of period-objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/

class SOS_Scheduler_Runtime_Period {

  
  /** begin="hh:mm[:ss]"    (Initial value: 00:00)    
  *
  * The start of the operating period for the job.
  * @access public
  */
  var $begin               = '';

  /** end="hh:mm[:ss]"    (Initial value: 24:00)    
  *
  * The end of the operating period. Should let_run="no" is set and no further operating period is designated, then the Job Scheduler ends all tasks which are running (using spooler_close()).
  * @access public
  */
  var $end                  = '';



  /** let_run="yes_no"   
  *
  * This attribute can only be used for jobs and not for orders.
  * let_run="yes" allows the Job Scheduler to let a task continue running, even though this is not allowed by the <run_time attribute.
  * let_run="no" causes the Job Scheduler to end a task (spooler_close is evoked instead of spooler_process), as soon as the <run_time is no longer valid. 
  * @access public
  */
  var $let_run               = '';


  /** repeat="hh:mm[:ss] or seconds"   
  *
  * Should a job not already be running, then it will be started at the start of the operating period. After the job has been ended, then it will be restarted after the defined time, as far as allowed by the <run_time> attribute. This repeat delay can be specified in hh:mm, in hh:mm:ss or in seconds.
  * Cannot be combined with the single_start= attribute.
  * The job will not be repeated, if repeat="0" (the default value) is set.
 * @access public
*/
  var $repeat               = '';
  
  /** single_start="hh:mm[:ss]"   
  *
  * The job should start at the time given.
  * Cannot be used in combination with the begin=, end= or repeat= attributes. 
  * @access public
  */
  var $single_start               = '';

  
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
   $s .= addAttribute('repeat',$this->repeat);
   $s .= addAttribute('single_start',$this->single_start);
   return $s;
  }
 
 
 /**
* Returns a xml representation for the period element.
*
*
* @access public
* @return String Period in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s  =	'<period' . $this->attributes() . '/>';
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
  

} // end of class SOS_Scheduler_Runtime_Period



//============================================================================================================

/**
* Instance of a list of period definitions in a runtime, weekdays, ultimos, monthdays or dates element.
* 
* Objects of this class are created, when using the method period() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $period = $job->run_time()->period()

*
* The major task of periods-objects is to gather all periods e.g. of a weekdays element and the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
* The classes SOS_Scheduler_Runtime, SOS_Scheduler_Runtime_Date and SOS_Scheduler_Runtime_Day has periods propertie which is implicit created when 
* using the method period().
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/

class SOS_Scheduler_Runtime_Periods {

  /** List of periods.
  *
  *  periods are added when using the method period() in SOS_Scheduler_Runtime
  * @access public
  */  
  var $list_periods               = array();

/**
* Adds an instance of SOS_Scheduler_Runtime_Period in the list of periods and returns it.
* 
*
* @access public
* @param SOS_Scheduler_Runtime_Period period
* @return SOS_Scheduler_Runtime_Period the new added period
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function addPeriod(){
   $obj = new Sos_Scheduler_Runtime_Period();

   array_push($this->list_periods,$obj);
   return $obj;
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
  	$s  =	'';
  	if (count($this->list_periods) > 0){
     	foreach ($this->list_periods as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Runtime_Period



//============================================================================================================

/**
* Instance of a date definition in a runtime element.
* 
* Objects of this class are created and added to a list, when using the method date() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $job->run_time()->date('2006-30-11')->addPeriod($period);
*
* The major task of date objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/

class SOS_Scheduler_Runtime_Date {

  /** The date yyyy-mm-dd
  *
  /* @access public
  */
  var $date               = '';

  /** Instance of SOS_Scheduler_Runtime_Periods
  *
  *  periods are added when using the method period() in SOS_Scheduler_Runtime_Date
  * @access public
  */

  var $periods            = null;


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
   $s .= addAttribute('date',$this->date);
   return $s;
  }
  
  
/**
* Adds an Instance of SOS_Scheduler_Runtime_Period to the list of periods of this date.
*
*
* @access public
* @param SOS_Scheduler_Runtime_Period period
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/     
   
  
  function addPeriod($p){
  	if ($this->periods == null){
  		$this->periods = new SOS_Scheduler_Runtime_Periods();
  	}
   	array_push($this->periods->list_periods,$p);
  }


/**
* Adds a new instance of a period in the date.
* Periods is created, when using the method period()
* Sample:
*   
*    $period = $job->run_time()->date('2006-12-12')->period();
* 
*
* The major task of periods is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the date element).
* The class SOS_Scheduler_Runtime_Date has a periods propertie which is implicit created when 
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
* Returns a xml representation for the date element.
*
*
* @access public
* @return String Date in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  

  function asString(){
  	$s  =	'<date' . $this->attributes();
  	if ($this->periods != null){
  		$s .= '>';
     	$s .= $this->periods->asString();
     	$s .= '</date>';
    }else{
    	$s .= '/>';
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
  

} // end of class SOS_Scheduler_Runtime_Date



//============================================================================================================

/**
* Instance of a list of date definitions in a runtime element.
* 
* Objects of this class are created, when using the method date() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*     $date = $job->run_time()->date('2006-12-12');

*
* The major task of dates-objects is to gather all dates  of a runtime element and the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
* The classes SOS_Scheduler_Runtime has a dates propertie which is implicit created when 
* using the method date().
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/

class SOS_Scheduler_Runtime_Dates {


  /** A List of dates
  *
  *  dates are added when using the method date() in SOS_Scheduler_Runtime
  *  Direct access is not recommeded.
  * @access public
  */
  var $list_dates               = array();
 
 
 /**
* Adds a new instance of a date in the list of dates if this date does not exist.
* Returns the new date or the existing date.
* Sample:
*       
*    $date = $job->run_time()->dates->addDate('2006-12-12');
*    Attention: You must create the object dates. It is recommended to use the method date() to create dates automatically
*    $date = $job->run_time()->date('2006-12-12');
*
* The major task of dates is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the run-time element).
* The class SOS_Scheduler_Runtime has a dates propertie which is implicit created when 
* using the method date().
*
*
* @access public
* @return SOS_Scheduler_Runtime_Period date
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function addDate($d){
  	
  	if ( !isset($this->list_dates[$d]) ) {
  		$obj = new Sos_Scheduler_Runtime_Date();
  		$obj->date = $d;
  	
      $this->list_dates[$d] = $obj;
  	}else{
  		$obj = $this->list_dates[$d];
  	}
  	return $obj;
  }
  
  /**
* Returns a xml representation for the dates element.
*
*
* @access public
* @return String Dates in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function asString(){
  	$s  =	'';
  	if (count($this->list_dates) > 0){
     	foreach ($this->list_dates as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Runtime_Dates



//============================================================================================================

/**
* Instance of a holiday definition in a runtime element.
* 
* Objects of this class are created and added to a list, when using the method holiday() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $job->run_time()->holiday('2006-30-11');
*
* The major task of holiday objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/
class SOS_Scheduler_Runtime_Holiday {

  

  /** A date in Stringformat yyyy-mm-dd
  *
  * @access public
  */ 
  var $date               = '';

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
   $s .= addAttribute('date',$this->date);
   return $s;
  }
  
  /**
* Returns a xml representation for the holiday element.
*
*
* @access public
* @return String Holiday in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/   
  
  function asString(){
  	$s  =	'<holiday' . $this->attributes() . '/>';
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
  

} // end of class SOS_Scheduler_Runtime_Holiday



//============================================================================================================

/**
* Instance of a list of holiday definitions in a runtime element.
* 
* Objects of this class are created, when using the method holidays() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*     $holiday = $job->run_time()->holidays('2006-12-12');

*
* The major task of holiday-objects is to gather all holidays of a runtime element and the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
* The class SOS_Scheduler_Runtime has a holidays propertie which is implicit created when 
* using the method holidays().
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/
class SOS_Scheduler_Runtime_Holidays {


  /** A List of holidays
  *
  *  holidays are added when using the method holidays() in SOS_Scheduler_Runtime
  *  Direct access is not recommeded.
  * @access public
  */
  var $list_holidays               = array();
 
 /**
* Adds a new instance of a holiday in the list of holidays if this holiday does not exist.
* Returns the new holiday or the existing holiday.
* Sample:
*       
*    $holiday = $job->run_time()->holidays->addHoliday('2006-12-12');
*    Attention: You must create the object holidays. It is recommended to use the method holidays() to create holidays automatically
*    $holiday = $job->run_time()->holidays('2006-12-12');
*
* The major task of holidays is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. in the run-time element).
* The class SOS_Scheduler_Runtime has a holidays propertie which is implicit created when 
* using the method holidays().
*
*
* @access public
* @return SOS_Scheduler_Runtime_Period date
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function addHoliday($d){
  	if ( !isset($this->list_holidays[$d]) ){
  		$obj = new Sos_Scheduler_Runtime_Holiday();
  		$obj->date = $d;
  	
      $this->list_holidays[$d] = $obj;
  	}else{
  		$obj = $this->list_holidays[$d];
  	}
  	return $obj;
  }
  
 /**
* Returns a xml representation for the holiday element.
*
*
* @access public
* @return String Holidays in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function asString(){
  	$s  =	'';
  	if (count($this->list_holidays) > 0){
  		$s = '<holidays>';
     	foreach ($this->list_holidays as $name=>$obj){
      	$s .= $obj->asString();
	    }
 		$s .= '</holidays>';
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
  

} // end of class SOS_Scheduler_Runtime_Holidays


//============================================================================================================

/**
* Instance of a day definition in a runtime element.
* 
* Objects of this class are created and added to a list, when using one of the methods weekdays(), monthdays(), utltimos() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $job->run_time()->weekday('1');
*    $job->run_time()->ultimos('22');
*    $job->run_time()->monthdays('11');

* The major task of day objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <weekdays> element.
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/

class SOS_Scheduler_Runtime_Day {
 
  /** A day in Stringformat. Must be 1-7 when using with weekdays, 1-31 when using with monthdays or ultimos.
  *
  * @access public
  */
  var $day            = '';
  

  /** A SOS_Scheduler_Runtime_Periods to gather all periods.
  *
  *  Direct access is not recommeded. Use the method period() to create this object implicit.
  *  e.g. $p = $job->run_time()->weekdays('2')->period();
  * @access public
  */
  var $periods        = null;

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
   $s .= addAttribute('day',$this->day);
   return $s;
  }
  
/**
* Adds an instance of SOS_Scheduler_Runtime_Period in the list of periods of this day and returns it.
* 
*
* @access public
* @param SOS_Scheduler_Runtime_Period period
* @return SOS_Scheduler_Runtime_Period the new added period
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/     
  function addPeriod($p){
  	if ($this->periods == null){
  		$this->periods = new SOS_Scheduler_Runtime_Periods();
  	}
   	array_push($this->periods->list_periods,$p);
  }
  
/**
* Create an instance of SOS_Scheduler_Runtime_Period and adds it in the list of periods of this day and returns it.
* 
*
* @access public
* @return SOS_Scheduler_Runtime_Period the new added period
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
* Returns a xml representation for the holiday element.
*
*
* @access public
* @return String day in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function asString(){
  	$s  =	'<day' . $this->attributes();
  	if ($this->periods != null){
  	  $s .= '>';
     	$s .= $this->periods->asString();
     	$s .= '</day>';
    }else{
     	$s .= '/>';
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
  

} // end of class SOS_Scheduler_Runtime_Day



//============================================================================================================
/**
* Instance of a list of days definitions in a runtime element.
* 
* Objects of this class are created, when using when using one of the methods weekdays(), monthdays(), utltimos() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*     $say = $job->run_time()->weekdays('1');

*
* The major task of days-objects is to gather all days of a runtime element and the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
* The class SOS_Scheduler_Runtime has a weekdays, ultimos and a monthdays propertie which are implicit created when 
* using the method weekdays(), monthdays() or ultimos().
*
*
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/
class SOS_Scheduler_Runtime_Days {

  /** A List of days
  *
  *  days are added when using the method weekdays(), ultimos() or monthdays() in SOS_Scheduler_Runtime
  *  Direct access is not recommeded.
  * @access public
  */
  var $list_days               = array();
  
  
 /**To define the type of days. Must be one of 'ultimos', 'monthdays' or 'weekdays
 *
 * Direct access is not recommeded. The correct value is set when using the method weekdays(), ultimos() or monthdays() in SOS_Scheduler_Runtime
 * @access public
 */
  var $identifier              = '';

  /**
  * constructor
  *
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function SOS_Scheduler_Runtime_Days($identifier) {
     $this->identifier = $identifier;
  }
  
/**
* Adds an instance of SOS_Scheduler_Runtime_Day in the list of days and returns it.
* 
*
* @access public
* @param String date yyy-mm-dd
* @return SOS_Scheduler_Runtime_Day the new added day
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  
  function addDay($d){
  	if ( !isset($this->list_days[$d]) ){
  		$obj = new Sos_Scheduler_Runtime_Day();
  		$obj->day = $d;
  	
      $this->list_days[$d] = $obj;
  	}else{
  		$obj = $this->list_days[$d];
  	}
  	return $obj;
  }
  
 /**
* Returns a xml representation for all day elements.
*
*
* @access public
* @return String list of days in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  function asString(){
  	$s  =	'';
  	if (count($this->list_days) > 0){
  	  $s .= '<'.$this->identifier.'>';
     	foreach ($this->list_days as $name=>$obj){
      	$s .= $obj->asString();
	    }
	    $s .= '</'.$this->identifier.'>';
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
  

} // end of class SOS_Scheduler_Runtime_Days



//============================================================================================================

/**
* Instance of a at definition in a runtime element.
* 
* Objects of this class are created and added to a list, when using the method at() in the class SOS_Scheduler_Runtime().
* Sample:
*   
*    $job->run_time()->at('11:00');
*
* The major task of at objects is the ability to provide a xml representation which can be used in xml-commands
* for the Job Scheduler (e.g. the <runtime> element.
*
* @access public
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*  @package Job_Scheduler
*/
class SOS_Scheduler_Runtime_At {

  
  var $at            = '';

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
   $s .= addAttribute('at',$this->at);
   return $s;
  }
  
 /**
* Returns a xml representation for at element.
*
*
* @access public
* @return String At in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function asString(){
  	$s  =	'<at' . $this->attributes() . '/>';
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
  

} // end of class SOS_Scheduler_Runtime_At

//============================================================================================================


class SOS_Scheduler_Runtime_Ats {

  
 
  var $list_ats              = array();

  /**
  * constructor
  *
  * @author   Uwe Risse <uwe.risse@sos-berlin.com>
  * @version  1.0-2005/08/17
  */

  function SOS_Scheduler_Runtime_Ats() {
  }

/**
* Adds an instance of SOS_Scheduler_Runtime_At in the list of ats and returns it.
* 
*
* @access public
* @param String date yyy-mm-dd
* @return SOS_Scheduler_Runtime_At the new added at
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/  
  
  function addAt($d){
  	
  	if ( !isset($this->list_ats[$d] ) ) {
  		
  		$obj = new Sos_Scheduler_Runtime_At();
  		$obj->at = $d;
  	
      $this->list_ats[$d] = $obj;
  	}else{
  		$obj = $this->list_ats[$d];
  	}
  	return $obj;
  }
  
 /**
* Returns a xml representation for all at elements.
*
*
* @access public
* @return String list of ats in XML format
* @author Uwe Risse <uwe.risse@sos-berlin.com>
* @since  1.0-2006/10/20
* @version 1.0 
*/    
  function asString(){
  	$s  =	'';
  	if (count($this->list_ats) > 0){
     	foreach ($this->list_ats as $name=>$obj){
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
  

} // end of class SOS_Scheduler_Runtime_Ats




?>