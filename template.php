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
 //print_r($variables['content']);
 $term_values = array();
 foreach(_ios_jqmobile_term_fields() as $fieldname => $field) {
  // Hide from showing as part of the content.
  hide($content[$field]);
  hide($content['body'][$field]);
  // Get the data from the field.
  $items = field_get_items('node', $node, $field);
  print_r($items);
  foreach($items as $delta => $item) {
    $term_value = field_view_value('node', $node, $fieldname, $item[$delta]);
    print_r($term_value);
    $term_values[$field][] = render($term_value);
  }
  $term_values[$field] = implode(',', $term_values[$field]);
 }
 $variables['term_list'] = implode(',', $term_values);
 // @todo: Add variables as needed.
}

/* Defines which term fields should be pulled in theming. */
function _ios_jqmobile_term_fields() {
 return array('field_term_year', 'field_term_topic', 'field_term_speaker', 'field_term_org_affiliation',
   'field_term_category', 'field_term_other_tags');
}

/**
 * Fix Javascript compatibility issue with jPlayer.
 * @todo: Find a more elegant solution. 
 */
function ios_jqmobile_preprocess_jplayer(&$vars) {
 // Determine a unique player ID.
 $ids = entity_extract_ids($vars['entity_type'], $vars['entity']);
 $vars['player_id'] = _jplayer_check_id('jplayer-' . $vars['entity_type'] . '-' . $ids[0] . '-' . str_replace('_', '-', $vars['field_name']));
 
 $vars['mode'] = $vars['settings']['mode'];
 
 $player = jplayer_sort_files($vars['items'], $vars['player_id'], $vars['mode']);
 
 $vars['playlist'] = theme('jplayer_item_list', array('items' => $player['playlist']));
 $vars['type'] = $player['type'];
 
 // Add player settings
 $player = array(
   'jplayerInstances' => array(
     $vars['player_id'] => array(
       'files' => $player['files'],
       'solution' => $vars['settings']['solution'],
       'supplied' => $player['extensions'],
       'preload' => $vars['settings']['preload'],
       'volume' => $vars['settings']['volume'] / 100,
       'muted' => (boolean)$vars['settings']['muted'],
       'autoplay' => (boolean)$vars['settings']['autoplay'],
       'repeat' => $vars['settings']['repeat'],
       'backgroundColor' => $vars['settings']['backgroundColor'],
     ),
   ),
 );
 
  $player_js = '<script type="text/javascript">jQuery.extend(Drupal.settings, ' . 
    drupal_json_encode($player) . '); Drupal.attachBehaviors();</script>';
  $vars['inline_js'] = $player_js;
}