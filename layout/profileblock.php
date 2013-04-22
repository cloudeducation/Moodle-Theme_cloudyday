<div class="profilepic" id="profilepic">	<?php		if (!isloggedin() or isguestuser()) {			echo '<a href="'.$CFG->wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'"><img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" title="Guest" alt="Guest"></a>';		}else{			echo '<a href="'.$CFG->wwwroot.'/user/view.php?id='.$USER->id.'&amp;course='.$COURSE->id.'"><img src="'.$CFG->wwwroot.'/user/pix.php?file=/'.$USER->id.'/f1.jpg" title="'.$USER->firstname.' '.$USER->lastname.'" alt="'.$USER->firstname.' '.$USER->lastname.'"></a>';		}	?></div><?php	function get_content () {	global $USER, $CFG, $SESSION, $COURSE;	$wwwroot = '';	$signup = '';}	if (empty($CFG->loginhttps)) {		$wwwroot = $CFG->wwwroot;	} else {		$wwwroot = str_replace("http://", "https://", $CFG->wwwroot);	}if (!isloggedin() or isguestuser()) {
	echo '<div class="profilename" id="profilename">';	echo $OUTPUT->login_info();
	echo '</div>';} else {	echo '<div class="profilename" id="profilename">';	echo $USER->firstname.' '.$USER->lastname;
	echo '</div>';
	echo '<div class="profileoptions" id="profileoptions">';
	echo '<ul>';
	echo '<li><a href="'.$CFG->wwwroot.'/user/editadvanced.php?id='.$USER->id.'">'.get_string('options', 'theme_cloudyday').'</a></li>';
	if ($CFG->messaging) {
		echo '<li><a href="'.$CFG->wwwroot.'/message">'.get_string('messages', 'theme_cloudyday').'</a></li>';
	}
	echo '<li><a href="'.$CFG->wwwroot.'/login/logout.php">'.get_string('logout').'</a></li>';
	echo '</ul>';
	echo '</div>';?><?php//echo '</div>'; // end of graphicwrap//echo '</div>'; // end of headerwrap?><?php }?>