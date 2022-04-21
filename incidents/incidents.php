<?php

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once( __DIR__ . '/incidents-type.php');
require_once( __DIR__ . '/incidents-rest.php');
require_once( __DIR__ . '/incidents-settings.php');
require_once( __DIR__ . '/incidents-taxonomy.php');
require_once( __DIR__ . '/acf.php');

add_action('admin_enqueue_scripts', function () {
  wp_enqueue_script('incident-acf-js', plugin_dir_url(__FILE__) . 'acf.js', array(), INCIDENTS_PLUGIN_VERSION, true);
});

?>