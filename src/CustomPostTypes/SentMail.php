<?php

namespace InterFix\WPMailSMTPLogger\CustomPostTypes;

use CMB2_Field;
use DateTime;
use DateTimeZone;
use Exception;

/**
 * Class Project
 * @package InterFix\WPMailSMTPLogger\CustomPostTypes
 */
class SentMail {
	
	/**
	 *
	 */
	const POST_TYPE = 'interfix_sent_mail';
	
	/**
	 *
	 */
	const ADMIN_POSTS_SCREEN_ID = 'edit-' . self::POST_TYPE;
	
	/**
	 *
	 */
	public static function register() {
		
		$supports = [
			'title',
			'editor'
		];
		
		$metaPrefix = '_cmb_' . self::POST_TYPE . '_';
		
		register_extended_post_type( self::POST_TYPE, [
			'show_in_feed' => false,
			'show_in_rest' => true,
			'supports'     => $supports,
			
			// graduation cap / mortarboard icon
			'menu_icon'    => 'dashicons-email',
			
			'labels'             => array(
				'menu_name' => 'Sent Mail',
				'all_items' => 'View All',
				'name'      => 'Logger For WP Mail SMTP (3rd Party)',
			),
			
			# Don't add the post type to the 'Recently Published' section of the dashboard:
			'dashboard_activity' => false,
			
			# Add some custom columns to the admin screen:
			
			'admin_cols'          => [
				'to'        => [
					'title'    => 'To',
					'meta_key' => $metaPrefix . 'to',
					//'date_format' => 'd/m/Y',
				],
				'from'      => [
					'title'    => 'From',
					'meta_key' => $metaPrefix . 'from',
					//'date_format' => 'd/m/Y',
				],
				'timestamp' => [
					'title_icon'  => 'dashicons-calendar-alt',
					'post_field'  => 'post_date',
					'date_format' => 'Y-m-d H:i:s'
				]
			],
			
			# Add some dropdown filters to the admin screen:
			'admin_filters'       => [
				'm' => false, // hide months filter
			],
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_admin_bar'   => false,
			'menu_position'       => 98,
			'hierarchical'        => false,
			'has_archive'         => false,
			'rewrite'             => false,
			'can_export'          => false,
			'show_in_menu'        => true,
			'query_var'           => true,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts'       => false,
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
		
		
		], [
			
			# Override the base names used for labels:
			'singular' => 'Sent Mail',
			'plural'   => 'Sent Mail',
			'slug'     => self::POST_TYPE,
		
		] );
		
		add_action( 'cmb2_init', function () {
			
			$prefix = '_cmb_' . self::POST_TYPE;
			
			$cmb = new_cmb2_box( [
				'id'           => $prefix . self::POST_TYPE,
				'title'        => 'Sent Mail',
				'object_types' => [ self::POST_TYPE ],
				'context'      => 'normal',
				'priority'     => 'default'
			] );
			
			$prefix .= '_';
			
			$cmb->add_field( array(
				'name'          => 'Timestamp',
				'id'            => $prefix . 'timestamp',
				'type'          => 'text',
				'date_format'   => 'Y-m-d H:i:s',
				'render_row_cb' => function ( $field_args, $field ) {
					
					/** @var CMB2_Field $field */
					$id          = $field->args( 'id' );
					$label       = $field->args( 'name' );
					$type        = $field->args( 'type' );
					$name        = $field->args( '_name' );
					$value       = $field->escaped_value();
					$description = $field->args( 'description' );
					
					try {
						$dt = new DateTime( '@' . $value );
						$dt->setTimezone( new DateTimeZone( wp_timezone_string() ) );
						$displayValue = $dt->format( 'Y-m-s H:i:s T' );
					} catch ( Exception $e ) {
						$displayValue = "'$value'<br>" . $e->getMessage();
					}
					
					echo "
						<div class='cmb-row cmb-type-" . esc_attr( $type ) . " cmb2-id-" . esc_attr( $id ) . " table-layout'>
							<div class='cmb-th'>
								<label for='" . esc_attr( $id ) . "-disp'>" . esc_html( $label ) . "</label>
							</div>
							<div class='cmb-td'>
								<input type='text' class='regular-text' id='" . esc_attr( $id ) . "-disp' value='" . esc_attr( $displayValue ) . "' readonly='readonly'>
								<p class='description'>" . esc_html( $description ) . "</p>
								<input type='hidden' name='" . esc_attr( $name ) . "' id='" . esc_attr( $id ) . "' value='" . esc_attr($value) . "'>
							</div>
						</div>
					";
				},
				'attributes'    => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				],
			) );
			
			$cmb->add_field( [
				'name'       => 'Subject',
				'id'         => $prefix . 'subject',
				'type'       => 'text',
				'attributes' => [
					'placeholder' => 'No Subject Specified',
					'readonly'    => 'readonly',
					'style'       => 'width: 95%'
				]
			] );
			
			$cmb->add_field( [
				'name'       => 'To',
				'id'         => $prefix . 'to',
				'type'       => 'text',
				'attributes' => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				]
			] );
			
			$cmb->add_field( [
				'name'       => 'From',
				'id'         => $prefix . 'from',
				'type'       => 'text',
				'attributes' => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				]
			] );
			
			$cmb->add_field( [
				'name'       => 'Message Body',
				'id'         => $prefix . 'body',
				'type'       => 'textarea',
				'attributes' => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				],
			] );
			
			$cmb->add_field( [
				'name'       => 'Headers',
				'id'         => $prefix . 'headers',
				'type'       => 'textarea',
				'attributes' => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				],
			] );
			
			$cmb->add_field( [
				'name'       => 'Additional Data',
				'id'         => $prefix . 'extra',
				'type'       => 'textarea',
				'attributes' => [
					'readonly' => 'readonly',
					'style'    => 'width: 95%'
				],
			] );
		} );
	}
	
	/**
	 *
	 */
	public static function adminInit() {
		
		add_filter( 'manage_' . self::ADMIN_POSTS_SCREEN_ID . '_columns', function ( $columns ) {
			
			$columns['title'] = 'Subject';
			
			return $columns;
		} );
		
		add_filter( 'bulk_actions-' . self::ADMIN_POSTS_SCREEN_ID, function ( $actions ) {
			
			unset( $actions['edit'] );
			
			return $actions;
		} );
		
		add_filter( 'views_edit-' . self::POST_TYPE, function ( $views ) {
			
			unset( $views['draft'] );
			
			return $views;
		} );
		
		add_action( 'admin_head', function () {
			
			global $current_screen;
			
			if ( self::POST_TYPE != $current_screen->post_type ) {
				return;
			}
			
			echo "
				
				<script type='text/javascript'>
				
					document.addEventListener('DOMContentLoaded', function() {
						jQuery('<p>This plugin is not affiliated with WP Forms, makers of the WP Mail SMTP plugin. Please do not bother them with support requests. It is a 3rd party solution to the lack of logging in the free version of their product. Should WP Forms see fit to provide logging as a base feature of their plugin then development of this alternative will cease. Please bear in mind that future updates to WP Mail SMTP may break this plugin\'s ability to log messages, and it may take some time for an update to be released. If they go out of their way to remove the WordPress Action Hook it depends on then an update may not be possible at all. Please use this plugin at your own risk.</p>').insertBefore('.wp-header-end');
					
						jQuery('.postbox-header h2').removeClass('hndle').removeClass('ui-sortable-handle');
						jQuery('.handle-actions').hide();
					});
				
				</script>

			    <style>
			    
			    	.edit-post-visual-editor,
			    	.edit-post-header,
			    	.post-state,
			    	.row-actions .edit, 
			    	.row-actions .inline {
			    		display: none;
			    	}
			    
			    	.edit-post-meta-boxes-area .postbox-header {
			    		border-top: none;
			    	}
			    
			    	.cmb2_textarea,
			    	.regular-text {
			    		font-family: monospace;
			    		font-size: 12px;
			    	}
			    	
			    	.cmb-type-group .cmb2-wrap>.cmb-field-list>.cmb-row, .cmb2-postbox .cmb2-wrap>.cmb-field-list>.cmb-row {
			    		padding: 0;
			    		border-bottom: none;
			    	}
			    
			    	#poststuff h2 {
			    		font-size: 200%;
			    	}
			    	
			    	.editor-styles-wrapper {
			    		display: none;
			    	}
			    
			    	.wp-block-post-title {
			    		margin: 0; !important;
			    		max-width: 100%;
			    	}
			    	
			    	.edit-post-visual-editor__post-title-wrapper {
			    		margin-top: 0;
			    	}
			    	
			    	.block-editor-block-list__layout {
			    		display: none;
			    	}
			    	
			    </style>
    		";
		} );
	}
}
