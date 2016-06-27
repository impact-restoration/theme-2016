<?php
/**
 * Adds extra meta boxes.
 *
 * @since {{VERSION}}
 *
 * @package ImpactRestoration
 * @subpackage ImpactRestoration/admin
 */

// Don't load directly
defined( 'ABSPATH' ) || die();

class ImpactRestoration_AdminExtraMeta_Home extends ImpactRestoration_AdminExtraMeta {

	/**
	 * Meta boxes to add.
	 *
	 * @since {{VERSION}}
	 *
	 * @var array
	 */
	public $meta_boxes = array(
		array(
			'external_callback'  => true,
			'condition_callback' => 'mb_home_condition',
			'args'               => array(
				'id'       => 'impactrestoration-mb-home-services',
				'title'    => 'Services',
				'callback' => array( 'IR_CPT_Service', 'mb_services' ),
				'screen'   => 'page',
			),
		),
		array(
			'condition_callback' => 'mb_home_condition',
			'args'               => array(
				'id'       => 'impactrestoration-mb-home-sections',
				'title'    => 'About Sections',
				'callback' => array( __CLASS__, 'mb_sections' ),
				'screen'   => 'page',
			),
		),
		array(
			'conditional_callback' => 'mb_home_condition',
			'args'                 => array(
				'id'       => 'impactrestoration-mb-home-getintouchform',
				'title'    => 'Get In Touch Form',
				'callback' => array( __CLASS__, 'mh_getintouchform' ),
				'screen'   => 'page',
				'context'  => 'side',
			),
		),
	);

	/**
	 * Conditional to show the home metabox or not.
	 *
	 * @since {{VERSION}}
	 * @access private
	 */
	function mb_home_condition() {

		global $post;

		return $post && get_option( 'page_on_front' ) == $post->ID && method_exists( 'IR_CPT_Service', 'mb_services' );
	}

	/**
	 * Metabox for sections.
	 *
	 * @since {{VERSION}}
	 */
	static public function mb_sections() {

		rbm_do_field_repeater( 'home_sections', false, array(
			'title'   => array(
				'label' => 'Title',
				'type'  => 'text',
			),
			'content' => array(
				'label' => 'Content',
				'type'  => 'textarea',
			),
			'image'   => array(
				'label' => 'Image',
				'type'  => 'image',
			)
		) );
	}

	/**
	 * Metabox for the "Get In Touch" Gravity Form.
	 *
	 * @since {{VERSION}}
	 */
	static public function mh_getintouchform() {

		$all_forms = false;
		if ( class_exists( 'GFAPI' ) ) {
			$all_forms = GFAPI::get_forms();
		}

		rbm_do_field_select( 'getintouchform', false, false, array(
			'options' => $all_forms ? wp_list_pluck( $all_forms, 'title', 'id' ) : array(),
		) );
	}
}