<?php 

/**
 * Provides a theme function to render test content.
 */
function ios_jqmobile_theme($existing, $type, $name, $path) {
 return array(
   'website_test_content' => array(
     'template' => 'templates/website-test-content',
   ),
 );
}

/**
 * Load the proper CSS and JS.
 * Add meta tags for use in <head>.
 */
function ios_jqmobile_preprocess_html(&$variables) {
 // Check that the helper module is enabled.
 if(module_exists('ios_jqm')) {
   // Add the libraries that are needed.
   ios_jqm_add_libraries();
   // Add the meta tags.
   ios_jqm_add_meta('charset'); // set charset
   $viewport_settings = ios_jqm_get_viewport_settings();
   ios_jqm_add_meta('viewport', $viewport_settings);
   ios_jqm_add_meta('apple-mobile-web-app-capable', 'yes');
 }
}

/**
 * Add variables to the page template.
 */
function ios_jqmobile_preprocess_page(&$variables) {
 // Swap out the page content for test content based on a query string parameter.
 $variables['show_test_content'] = FALSE;
 if(!empty($_GET['test_content'])) {
   $variables['show_test_content'] = TRUE;
   $variables['test_content'] = theme('website_test_content', array());
 }
 // @todo: Add more variables as needed.
}

/**
 * Add variables to the node template.
 */
function ios_jqmobile_preprocess_node(&$variables) {
 // Creates an imploded list of taxonomy terms for display on the node.
 $node = $variables['node'];
 $term_values = array();
 foreach(_ios_jqmobile_term_fields() as $fieldname) {
  // Iterate through the fields, load, and render the data.
  $items = field_get_items('node', $node, $fieldname);
  $term_values[$fieldname] = array();
  foreach($items as $delta => $item) {
    if(!empty($item['taxonomy_term']->name)) {
      // Build the link for the term.
      // field_view_value() would've worked since I was iterating and collecting.
      $term_value = l($item['taxonomy_term']->name, 'taxonomy/term/' . $item['tid']);
      $term_values[$fieldname][] = $term_value;
    }
  }
  if(is_array($term_values[$fieldname]) && count($term_values[$fieldname]) > 0) {
    $term_values[$fieldname] = implode(', ', $term_values[$fieldname]);
  }
  else {
    unset($term_values[$fieldname]);
  }
 }
 $variables['term_list'] = implode(', ', $term_values);
 // Hide term fields from showing as part of the content.
 foreach($variables['content'] as $element_name => $element) {
   if(strpos($element_name, 'field_term') !== FALSE) {
      hide($variables['content'][$element_name]);
   }
 }
 // Hide flags.
 hide($variables['content']['links']['flag']);
 // Hide the comments and links so they don't show as part of $content variable.
 hide($variables['content']['comments']);
 hide($variables['content']['links']);
 // @todo: Add variables as needed.
}

/* Defines which term fields should be pulled in theming. */
function _ios_jqmobile_term_fields() {
 return array('field_term_year', 'field_term_topic', 'field_term_speaker', 'field_term_org_affiliation',
   'field_term_category', 'field_term_other_tags');
}

/**
 * Add variables to the taxonomy term template.
 */
function ios_jqmobile_preprocess_taxonomy_term(&$variables) {
  if($variables['view_mode'] == 'taxonomy_term_image') {
    $variables['theme_hook_suggestions'] = array('term__view_mode__taxonomy_term_image');
  }
}