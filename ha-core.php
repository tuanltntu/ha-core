<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tuanltntu.com
 * @since             1.0.0
 * @package           Ha_Core
 *
 * @wordpress-plugin
 * Plugin Name:       HA Core
 * Plugin URI:        https://tuanltntu.com
 * Description:       Main core for all HA's plugin
 * Version:           1.0.0
 * Author:            Tuan Le
 * Author URI:        https://tuanltntu.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ha-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HA_CORE_VERSION', '1.0.0' );
define( 'HA_CORE_PATH', plugin_dir_path( __FILE__ ));
define( 'HA_CORE', 'ha-core' );
define( 'HA_MENU', 'ha-menu' );
define( 'HA_PLUGINS', 'https://plugins.tuanltntu.com/api/' );
define( 'HA_LOGO', plugin_dir_url( __FILE__ ) . 'admin/images/logo.png' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ha-core-activator.php
 */
function activate_ha_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ha-core-activator.php';
	Ha_Core_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ha-core-deactivator.php
 */
function deactivate_ha_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ha-core-deactivator.php';
	Ha_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ha_core' );
register_deactivation_hook( __FILE__, 'deactivate_ha_core' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ha-core.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ha_core() {

	$plugin = new Ha_Core();
	$plugin->run();

}
run_ha_core();
