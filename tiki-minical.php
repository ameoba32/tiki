<?php
require_once('tiki-setup.php');
include_once('lib/minical/minicallib.php');

if($feature_minical != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}

if(!$user) {
  $smarty->assign('msg',tra("Must be logged to use this feature"));
  $smarty->display("styles/$style_base/error.tpl");
  die;  
}


//if($tiki_p_usermenu != 'y') {
//  $smarty->assign('msg',tra("Permission denied to use this feature"));
//  $smarty->display("styles/$style_base/error.tpl");
//  die;  
//}


if(!isset($_REQUEST["eventId"])) $_REQUEST["eventId"]=0;

if(isset($_REQUEST['remove'])) {
//  foreach(array_keys($_REQUEST["menu"]) as $men) {      	
    $minicallib->minical_remove_event($user, $_REQUEST['remove']);
//  }
}

if(isset($_SESSION['thedate'])) {
  $pdate = mktime(0,0,0,date("m",$_SESSION['thedate']),date("d",$_SESSION['thedate']),date("Y",$_SESSION['thedate']));
} else {
  $pdate = date("U");
}
$yesterday = $pdate - 60*60*24;
$tomorrow = $pdate + 60*60*24;
$smarty->assign('yesterday',$yesterday);
$smarty->assign('tomorrow',$tomorrow);


$smarty->assign('day',date("d",$pdate));
$smarty->assign('mon',date("m",$pdate));
$smarty->assign('year',date("Y",$pdate));

$pdate_h = mktime(date("G"),date("i"),date("s"),date("m",$pdate),date("d",$pdate),date("Y",$pdate));
$smarty->assign('pdate',$pdate);
$smarty->assign('pdate_h',$pdate_h);

$ev_pdate = $pdate;
$ev_pdate_h = $pdate_h;
if($_REQUEST["eventId"]) {
  $info = $minicallib->minical_get_event($user,$_REQUEST["eventId"]);
  $ev_pdate = $info['start'];
  $ev_pdate_h = $info['start'];
} else {
  $info=Array();
  $info['title']='';
  $info['description']='';
  $info['start']=mktime(date("H"),date("i"),date("s"),date("m",$pdate),date("d",$pdate),date("Y",$pdate));
  $info['duration']=1;
}
$smarty->assign('ev_pdate',$ev_pdate);
$smarty->assign('ev_pdate_h',$ev_pdate_h);


if(isset($_REQUEST['save'])) {
  $start = mktime($_REQUEST['Time_Hour'],$_REQUEST['Time_Minute'],0,$_REQUEST['Date_Month'],$_REQUEST['Date_Day'],$_REQUEST['Date_Year']);
  $minicallib->minical_replace_event($user,$_REQUEST["eventId"],$_REQUEST["title"],$_REQUEST["description"],$start,$_REQUEST['duration']);
  $info=Array();
  $info['title']='';
  $info['description']='';
  $info['start']=mktime(date("h"),date("i"),date("s"),date("m",$pdate),date("d",$pdate),date("Y",$pdate));  
  $info['duration']=1;
  $_REQUEST["eventId"]=0;
}
$smarty->assign('eventId',$_REQUEST["eventId"]);
$smarty->assign('info',$info);

//Check here the interval for the calendar
if(!isset($_REQUEST['view'])) {
  $_REQUEST['view']='daily';
}
$smarty->assign('view',$_REQUEST['view']);

$minical_interval = $tikilib->get_user_preference($user,'minical_interval',60*60);
$minical_start_hour = $tikilib->get_user_preference($user,'minical_start_hour',9);
$minical_end_hour = $tikilib->get_user_preference($user,'minical_end_hour',20);
$minical_public = $tikilib->get_user_preference($user,'minical_public','n');

// Interval is in hours
if($_REQUEST['view']=='daily') {
	$slot_start = $pdate + 60*60*$minical_start_hour;
	$slot_end = $pdate + 60*60*$minical_end_hour;
	$interval = $minical_interval;
}
if($_REQUEST['view']=='weekly') {
	$interval=24*60*60;
	// Determine weekday
	$wd = date('w',$pdate);
	if($wd==0) $w=7;
	$wd=$wd-1;
	// Now get the number of days to substract
	$week_start = $pdate - ($wd*60*60*24);
	$week_end = $week_start + 60*60*24*7-1;
	$smarty->assign('week_start',$week_start);
	$smarty->assign('week_end',$week_end);
	$next_week_start = $week_end+1;
	$smarty->assign('next_week_start',$next_week_start);
	$prev_week_start = $week_start - (60*60*24*7);
	$smarty->assign('prev_week_start',$prev_week_start);
	$slot_start = $pdate - ($wd*60*60*24);
	$slot_end = $slot_start + 60*60*24*7-1;
}

$smarty->assign('slot_start',$slot_start);
$smarty->assign('slot_end',$slot_end);

$events = $minicallib->minical_events_by_slot($user,$slot_start,$slot_end,$interval);
//print_r($events);
$smarty->assign_by_ref('slots',$events);


include_once('tiki-mytiki_shared.php');


$smarty->assign('mid','tiki-minical.tpl');
$smarty->display("styles/$style_base/tiki.tpl");
?>
 
 
