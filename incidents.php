<?php 
/**
 * Created on Apr 21, 2022
 * Plugin Name: Incidents
 * Description: Plugin that allows incidents
 * Version: 1.0.0
 * Author: Metatavu Oy
 */

  defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

  if (!defined('INCIDENTS_DOMAIN')) {
    define('INCIDENTS_DOMAIN', 'incidents');
  }
  
  if (!defined('INCIDENTS_PLUGIN_VERSION')) {
    define('INCIDENTS_PLUGIN_VERSION', '1.0.0');
  }

  require_once(__DIR__ . '/incidents/incidents.php');

  add_action('plugins_loaded', function() {
    load_plugin_textdomain( INCIDENTS_DOMAIN, false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
  });
?>