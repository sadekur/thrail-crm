<?php
/**
 * Plugin Name:      Thrail CRM
 * Plugin URI:        https://srs.com
 * Description:       A plugin Thrail CRM for Customert.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.2
 * Author:            SRS
 * Author URI:        https://srs.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       thrail-crm
 * Domain Path:       /languages
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
register_activation_hook(__FILE__, 'thrail_crm_activate');

/**
 * The main plugin class
 */
final class Thrail_Crm{

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const version = '1.0';

	/**
	 * Class construcotr
	 */
	private function __construct() {
		$this->define_constants();

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
	}

	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'THRAIL_CRM_VERSION', self::version );
		define( 'THRAIL_CRM_FILE', __FILE__ );
		define( 'THRAIL_CRM_PATH', __DIR__ );
		define( 'THRAIL_CRM_URL', plugins_url( '', THRAIL_CRM_FILE ) );
		define( 'THRAIL_CRM_ASSETS', THRAIL_CRM_URL . '/assets' );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {

		new Thrail\Crm\Assets();
		new Thrail\Crm\Email();
		new Thrail\Crm\RestAPI();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Thrail\Crm\Ajax();
		}

		if ( is_admin() ) {
			new Thrail\Crm\Admin();
		} else {
			new Thrail\Crm\Frontend();
		}

	}
}


function thrail_crm() {
	return Thrail_Crm::init();
}

thrail_crm();