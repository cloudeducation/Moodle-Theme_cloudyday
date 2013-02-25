<?php

/* Menu Navigation
 * Jason Cleaver, November 2011
 * Last update: 15 September, 2012
 */
 
//Course Menu
//Get Current Page Section
$navitems = $PAGE->navbar->get_items();
$navitemcount = count($navitems);
$sectionCurrent = -1;
if ($navitemcount == 3){
	if (strrchr($PAGE->url,'/course/view.php')){$sectionCurrent = 0;}
	
}
if ($navitemcount == 4){
	if (strrchr($PAGE->url,'/course/view.php')){
		$sectionCurrent = -2;
		$navitem = $navitems[3];
	}
	
}


if ($COURSE->id != SITEID){

	//New Navigation
	$coursenode = $PAGE->navigation->find($COURSE->id, navigation_node::TYPE_COURSE);
	$newnavmenu = '';
	$hassubsections = false;
	//if ($coursenode && $coursenode->has_children() && $COURSE->coursedisplay){ 
	if ($coursenode && $coursenode->has_children()){ 
		$crse = course_get_format($COURSE->id)->get_course();
		if ($crse->coursedisplay){
			$hassubsections = true;
	    	foreach ($coursenode->children as $topicnode) {
	        	if ($topicnode->type == 30){
	        		$currentsectionpage = ($topicnode->contains_active_node())? 'selected' : '';
	        		if ($topicnode->action == null && $PAGE->url == ($wwwroot.'/course/view.php?id='.$COURSE->id)){$currentsectionpage = 'selected';}
	        		if ($topicnode->action == null){
	        			$newnavmenu .= '<li class="'.$currentsectionpage.'"><a class="section" href="'.$wwwroot.'/course/view.php?id='.$COURSE->id.'">'.$topicnode->text.'</a></li>';
	        		} else {
	        			$newnavmenu .= '<li class="'.$currentsectionpage.'"><a class="section" href="'.$topicnode->action.'">'.$topicnode->text.'</a></li>';
	        		}
	        	}
	       	}			
		} else {
			$currentsectionpage ='';
			if ($PAGE->url == ($wwwroot.'/course/view.php?id='.$COURSE->id)){$currentsectionpage = 'selected';}
			$newnavmenu .= '<li class="'.$currentsectionpage.'"><a class="welcome" href="'.$wwwroot.'/course/view.php?id='.$COURSE->id.'">'.get_string('coursehome', 'theme_cloudyday').'</a></li>';
		}

    }


	$text = '<div id="menunav">';
	$text .= '<ul class="list">';
	$text .= $newnavmenu;

	$currentsectionpage = '';
	
	//Show all sections
	if (strstr($PAGE->url,$CFG->wwwroot.'/course/')&&strstr($PAGE->url,'section=-1')){$currentsectionpage = 'selected';}
	if ($PAGE->user_is_editing() && $hassubsections){
		$text .='<li class="'.$currentsectionpage.'"><em><a href="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'&section=-1" id="lnk-showAllSections" class="show-all-sections">Show All Sections</a></em></li>';
	}
	$currentsectionpage = '';
	//Course summary
	if ($PAGE->user_is_editing()){
		//$text .='<li class="'.$currentsectionpage.'"><em><a href="'.$CFG->wwwroot.'/course/view.php?id='.$COURSE->id.'#showSummaries" id="lnk-showSummaries" class="show-summaries">Change Section Preferences</a></em></li>';
	}
	//Participants
	if (strstr($PAGE->url,$CFG->wwwroot.'/user/index.php')){$currentsectionpage = 'selected';}
	$text .='<li id="lst-participants" class="'.$currentsectionpage.'"><a id="lnk-participants" title="'.get_string('listofallpeople').'" href="'.$CFG->wwwroot.'/user/index.php?id='.$COURSE->id.'" class="show-participants">'.get_string('participants').'</a></li>';
	$currentsectionpage = '';
	//Grades
	if ($COURSE->showgrades) {
		if (strstr($PAGE->url,$CFG->wwwroot.'/grade/')){$currentsectionpage = 'selected';}
		$text .='<li id="lst-showGrades" class="'.$currentsectionpage.'"><a id="lnk-showGrades" href="'.$CFG->wwwroot.'/grade/index.php?id='.$COURSE->id.'" class="show-grades">'.get_string('gradebook','grades').'</a></li>';
		$currentsectionpage = '';
	}
	//Calendar
	if (strstr($PAGE->url,$CFG->wwwroot.'/calendar/view.php')){$currentsectionpage = 'selected';}
	$text .='<li id="lst-calendar" class="'.$currentsectionpage.'"><a id="lnk-calendar" href="'.$CFG->wwwroot.'/calendar/view.php?view=upcoming&amp;course='.$COURSE->id.'" class="show-calendar">'.get_string('calendar', 'calendar').'</a></li>';
	$currentsectionpage = '';
	$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);	
	//Activity Report
	if (strstr($PAGE->url,$CFG->wwwroot.'/report/outline/index.php')){$currentsectionpage = 'selected';}
	if (has_capability('report/outline:view', $context)){
		$text .='<li id="lst-report-outline" class="'.$currentsectionpage.'"><a id="lnk-report-outline" href="'.$CFG->wwwroot.'/report/outline/index.php?id='.$COURSE->id.'" class="show-report">Activity Report</a></li>';
	}
	$currentsectionpage = '';
	//Course Completion Report
	if (strstr($PAGE->url,$CFG->wwwroot.'/report/participation/index.php')){$currentsectionpage = 'selected';}
	if (has_capability('report/participation:view', $context)){
		$text .='<li id="lst-report-particiaption" class="'.$currentsectionpage.'"><a id="lnk-report-particiaption" href="'.$CFG->wwwroot.'/report/participation/index.php?id='.$COURSE->id.'" class="show-report">Participation Report</a></li>';
	}
	$currentsectionpage = '';
   $text .= '</ul></div>';
   echo $text;
}

