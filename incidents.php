<?php 
/**
 * Created on Apr 21, 2022
 * Plugin Name: Wordpress Incidents
 * Description: Plugins that allows incidents
 * Version: 1.0.0
 * Author: Metatavu Oy
 * Text Domain: incidents
 */

  defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

  if (!defined('INCIDENTS_DOMAIN')) {
    define('INCIDENTS_DOMAIN', 'incidents');
  }
  
  if (!defined('INCIDENTS_PLUGIN_VERSION')) {
    define('INCIDENTS_PLUGIN_VERSION', '1.0.0');
  }

  require_once("/incidents/incidents.php");
?>