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
 * Add meta tags for use in <head>.
 */
function ios_jqmobile_preprocess_html(&$variables) {
 // @todo: Add the meta tags.
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
 // @todo: Add variables as needed.
}