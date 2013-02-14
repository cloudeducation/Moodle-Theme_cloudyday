<?php
 
class theme_cloudyday_core_renderer extends core_renderer {
	

    public function custom_menu($custommenuitems = '') {
        global $CFG;
        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }   
    protected function render_custom_menu(custom_menu $menu) {
        if (isloggedin()) {
            $branchlabel = get_string('mycourses');
            $branchurl   = new moodle_url('/my/index.php');
            $branchtitle = $branchlabel;
            $branchsort  = -1;
 
            $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
 			if ($courses = enrol_get_my_courses(NULL, 'fullname ASC')) {
 				foreach ($courses as $course) {
 					if ($course->visible){
 						$branch->add(format_string($course->fullname), new moodle_url('/course/view.php?id='.$course->id), format_string($course->shortname));
 					}
 				}
 			} else {
 				$branch->add('<em>'.get_string('noenrolments', 'theme_cloudyday').'</em>',new moodle_url('/'),get_string('noenrolments', 'theme_cloudyday'));
 			}
            
        }
 
        return parent::render_custom_menu($menu);
    }
 
}
include_once($CFG->dirroot . "/course/format/topics/renderer.php");
class theme_cloudyday_format_topics_renderer extends format_topics_renderer { 

    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;
		if ($displaysection == -1){
			$this->print_multiple_section_page2($course, $sections, $mods, $modnames, $modnamesused);
		} else {
	        $modinfo = get_fast_modinfo($course);
	        $course = course_get_format($course)->get_course();
	
	        // Can we view the section in question?
	        if (!($sectioninfo = $modinfo->get_section_info($displaysection))) {
	            // This section doesn't exist
	            print_error('unknowncoursesection', 'error', null, $course->fullname);
	            return;
	        }
	
	        if (!$sectioninfo->uservisible) {
	            if (!$course->hiddensections) {
	                echo $this->start_section_list();
	                echo $this->section_hidden($displaysection);
	                echo $this->end_section_list();
	            }
	            // Can't view this section.
	            return;
	        }
	
	        // Copy activity clipboard..
	        echo $this->course_activity_clipboard($course, $displaysection);
	        $thissection = $modinfo->get_section_info(0);
	        if ($thissection->summary or !empty($modinfo->sections[0]) or $PAGE->user_is_editing()) {
	            //echo $this->start_section_list();
	            //echo $this->section_header($thissection, $course, true, $displaysection);
	            //print_section($course, $thissection, null, null, true, "100%", false, $displaysection);
	            //if ($PAGE->user_is_editing()) {
	            //    print_section_add_menus($course, 0, null, false, false, $displaysection);
	            //}
	            //echo $this->section_footer();
	            //echo $this->end_section_list();
	        }
	
	        // Start single-section div
	        echo html_writer::start_tag('div', array('class' => 'single-section'));
	
	        // The requested section page.
	        $thissection = $modinfo->get_section_info($displaysection);
	
	        // Title with section navigation links.
	        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
	        $sectiontitle = '';
	        //$sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
	        //$sectiontitle .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
	        //$sectiontitle .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
	        // Title attributes
	        $titleattr = 'section-title';
	        if (!$thissection->visible) {
	            $titleattr .= ' dimmed_text';
	        }
	        if ($displaysection != 0){
	        	$sectiontitle .= html_writer::tag('div', get_section_name($course, $displaysection), array('class' => $titleattr));
	        }
	        //$sectiontitle .= html_writer::end_tag('div');
	        echo $sectiontitle;
	
	        // Now the list of sections..
	        echo $this->start_section_list();
	
	        echo $this->section_header($thissection, $course, true, $displaysection);
	        // Show completion help icon.
	        $completioninfo = new completion_info($course);
	        echo $completioninfo->display_help_icon();
	
	        print_section($course, $thissection, null, null, true, '100%', false, $displaysection);
	        if ($PAGE->user_is_editing()) {
	            print_section_add_menus($course, $displaysection, null, false, false, $displaysection);
	        }
	        echo $this->section_footer();
	        echo $this->end_section_list();
	
	        // Display section bottom navigation.
	        $courselink = html_writer::link(course_get_url($course), get_string('returntomaincoursepage'));
	        $sectionbottomnav = '';
	        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
	        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
	        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
	        $sectionbottomnav .= html_writer::tag('div', $courselink, array('class' => 'mdl-align'));
	        $sectionbottomnav .= html_writer::end_tag('div');
	        //echo $sectionbottomnav;
	
	        // close single-section div.
	        echo html_writer::end_tag('div');
    	}
    }
	
	
	public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
		$course = course_get_format($course)->get_course();
		if (empty($displaysection)) {$displaysection = 0;}
		if ($course->coursedisplay == COURSE_DISPLAY_MULTIPAGE){
			$this->print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, 0);
		} else {
			$this->print_multiple_section_page2($course, $sections, $mods, $modnames, $modnamesused);
		}
	}
	public function print_multiple_section_page2($course, $sections, $mods, $modnames, $modnamesused) {
		return parent::print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
	}

}