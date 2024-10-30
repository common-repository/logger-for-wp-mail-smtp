<?php
/**
 * Plugin Name: Logger For WP Mail SMTP (3rd Party)
 * Version: 1.0
 * Requires at least: 5.8
 * Requires PHP: 7.2.0
 * Plugin URI: https://www.InterFix.net
 * Description: Logs email sent via WP Mail SMTP. This is not a product of WPForms / WP Mail SMTP. It is a 3rd party solution to the lack of logging in the free version of their product.
 * Author: InterFix Communications, Ltd.
 * Author URI: https://www.InterFix.net/
 *
 * @package interfix-wp-mail-smtp-logger
 */

defined( 'ABSPATH' ) or die( 'kthxbye!' );

// CMB2 is not autoloader compatible and requires manual inclusion 
use InterFix\WPMailSMTPLogger\Main;

// CMB2 is not autoloader compatible and requires manual inclusion 
require_once __DIR__ . '/vendor/cmb2/cmb2/init.php';

require_once __DIR__ . '/vendor/autoload.php';

const INTERFIX_WP_MAIL_SMTP_LOGGER_DIR = __DIR__;
define( 'INTERFIX_WP_MAIL_SMTP_LOGGER_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) );

try {
	$main = new Main();
} catch ( Exception $e ) {
	
	global $interfix_wp_mail_smtp_logger_did_not_load_exception;
	$interfix_wp_mail_smtp_logger_did_not_load_exception = $e;
	
	/**
	 * Create function only in the case of a failed plugin load
	 * @noinspection PhpUnused
	 */
	function interfix_wp_mail_smtp_logger_did_not_load_admin_notice_error() {
		
		global $interfix_wp_mail_smtp_logger_did_not_load_exception;
		$e = $interfix_wp_mail_smtp_logger_did_not_load_exception;
		
		$basename = basename( __FILE__ );
		
		$exceptionMessage = esc_attr( $e->getMessage() );
		
		$message = "There was an unrecoverable error while attempting to load the $basename plugin.<br>Exception: $exceptionMessage";
		
		echo "<div class='notice notice-error'><p>" . esc_html( $message ) . "</p></div>";
	}
	
	if ( is_admin() ) {
		add_action( 'admin_notices', 'interfix_wp_mail_smtp_logger_did_not_load_admin_notice_error' );
	}
	
}

