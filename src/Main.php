<?php

namespace InterFix\WPMailSMTPLogger;

use InterFix\WPMailSMTPLogger\CustomPostTypes\SentMail;
use ReflectionClass;
use ReflectionException;

defined( 'ABSPATH' ) or die( 'kthxbye!' );

/**
 * Class Main
 * @package InterFix\WPMailSMTPLogger
 */
class Main {
	
	/**
	 * Main constructor.
	 */
	public function __construct() {
		
		add_action( 'init', function () {
			SentMail::register();
		} );
		
		add_action( 'admin_init', function () {
			SentMail::adminInit();
		} );
		
		add_action( 'wp_mail_smtp_mailcatcher_smtp_send_before', [ $this, 'logSentEmail' ] );
		add_action( 'wp_mail_smtp_mailcatcher_send_before', [ $this, 'logSentEmail' ] );
	}
	
	/**
	 * @throws ReflectionException
	 */
	public function logSentEmail( $arg1 = null ) {
		
		if ( ! is_object( $arg1 ) ) {
			return;
		}
		
		$prefix = '_cmb_' . SentMail::POST_TYPE . '_';
		
		wp_insert_post( [
			'post_type'    => SentMail::POST_TYPE,
			'post_title'   => $arg1->Subject,
			'post_content' => '',
			'meta_input'   => [
				$prefix . 'subject'   => $arg1->Subject,
				$prefix . 'timestamp' => date( 'U' ),
				$prefix . 'to'        => self::accessProtected( $arg1, 'to' )[0][0],
				$prefix . 'from'      => $arg1->From,
				$prefix . 'body'      => $arg1->Body,
				$prefix . 'headers'   => self::accessProtected( $arg1, 'MIMEHeader' ),
				$prefix . 'extra'     => "Request Data: " . print_r( $_REQUEST, true )
			]
		] );
	}
	
	/**
	 * @throws ReflectionException
	 */
	private static function accessProtected( $obj, $prop ) {
		$reflection = new ReflectionClass( $obj );
		$property   = $reflection->getProperty( $prop );
		$property->setAccessible( true );
		
		return $property->getValue( $obj );
	}
}