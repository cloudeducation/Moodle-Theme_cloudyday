<?phpfunction cloudyday_process_css($css, $theme) {    // Set the menu hover color    if (!empty($theme->settings->menuhovercolor)) {        $menuhovercolor = $theme->settings->menuhovercolor;    } else {        $menuhovercolor = null;    }    $css = cloudyday_set_menuhovercolor($css, $menuhovercolor);          return $css;}function cloudyday_set_menuhovercolor($css, $menuhovercolor) {    $tag = '[[setting:menuhovercolor]]';    $replacement = $menuhovercolor;    if (is_null($replacement)) {        $replacement = '#5faff2';    }    $css = str_replace($tag, $replacement, $css);    return $css;}class cloudyday_custom_menu_item extends custom_menu_item {
    public function __construct(custom_menu_item $menunode) {
        parent::__construct($menunode->get_text(), $menunode->get_url(), $menunode->get_title(), $menunode->get_sort_order(), $menunode->get_parent());
        $this->children = $menunode->get_children();
 
        $matches = array();
        if (preg_match('/^\[\[([a-zA-Z0-9\-\_\:]+)\]\]$/', $this->text, $matches)) {
            try {
                $this->text = get_string($matches[1], 'theme_cloudyday');
            } catch (Exception $e) {
                $this->text = $matches[1];
            }
        }
 
        $matches = array();
        if (preg_match('/^\[\[([a-zA-Z0-9\-\_\:]+)\]\]$/', $this->title, $matches)) {
            try {
                $this->title = get_string($matches[1], 'theme_cloudyday');
            } catch (Exception $e) {
                $this->title = $matches[1];
            }
        }
    }
}