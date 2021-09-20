<?php
namespace asquaredGlue;
/**
 * Class GlueQuotes
 */
class GlueQuotes {
	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * GlueQuotes constructor.
	 */
	private function __construct() {
		add_action('init', [$this, 'init']);
		add_shortcode('glue_quotes', [$this, 'outputQuotes']);
	}

	/**
	 * @return null|\GlueQuotes
	 */
	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register all necessary assets
	 *
	 * @return void
	 */
	function init() {
		register_post_type('quotes', array(
			'label'                 => 'Quotes',
			'description'           => '',
			'hierarchical'          => false,
			'supports'              => array(
				0 => 'title',
				1 => 'editor',
			),
			'taxonomies'            => [],
			'public'                => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'can_export'            => true,
			'delete_with_user'      => 'null',
			'labels'                => [],
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-admin-post',
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'rewrite'               => true,
			'has_archive'           => true,
			'show_in_rest'          => false,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'acfe_archive_template' => '',
			'acfe_archive_ppp'      => 10,
			'acfe_archive_orderby'  => 'date',
			'acfe_archive_order'    => 'DESC',
			'acfe_single_template'  => '',
			'acfe_admin_archive'    => false,
			'acfe_admin_ppp'        => 10,
			'acfe_admin_orderby'    => 'date',
			'acfe_admin_order'      => 'DESC',
			'capability_type'       => 'post',
			'capabilities'          => [],
			'map_meta_cap'          => NULL,
		));

		register_taxonomy('quotes_category', array(
			0 => 'quotes',
		), array(
			'label'                 => 'Quotes Category',
			'description'           => '',
			'hierarchical'          => false,
			'post_types'            => array(
				0 => 'quotes',
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'update_count_callback' => '',
			'sort'                  => false,
			'labels'                => [],
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'show_in_quick_edit'    => true,
			'show_admin_column'     => true,
			'rewrite'               => true,
			'show_in_rest'          => false,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'acfe_single_template'  => '',
			'acfe_single_ppp'       => 10,
			'acfe_single_orderby'   => 'date',
			'acfe_single_order'     => 'DESC',
			'acfe_admin_ppp'        => 10,
			'acfe_admin_orderby'    => 'name',
			'acfe_admin_order'      => 'ASC',
			'capabilities'          => [],
			'meta_box_cb'           => NULL,
		));

		if (function_exists('acf_add_local_field_group')):

			acf_add_local_field_group(array(
				'key'                   => 'group_5f68c9f50dfa5',
				'title'                 => 'Quotes Fields',
				'fields'                => array(
					array(
						'key'               => 'field_5f68c9fb92013',
						'label'             => 'Author',
						'name'              => 'author',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'acfe_permissions'  => '',
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
						'maxlength'         => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'quotes',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'left',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'acfe_display_title'    => '',
				'acfe_autosync'         => '',
				'acfe_permissions'      => '',
				'acfe_form'             => 0,
				'acfe_meta'             => '',
				'acfe_note'             => '',
			));

		endif;
	}

	/**
	 * Outputs the code in a slider
	 *
	 * @param $attributes
	 *
	 * @return false|string
	 */
	function outputQuotes($attributes) {
		$a = shortcode_atts(array(
			'layout'         => 'slider',
			'orderby'        => 'none',
			'posts_per_page' => 12,
			'columns'        => 1,
			'show_titles'    => true,
			'category'       => null,
			'nav_pos'        => null,
			'stars'          => false,
			'arrows'         => true,
			'bullets'        => true
		), $attributes);

		$args = [
			'orderby'        => $a['orderby'],
			'post_type'      => 'quotes',
			'posts_per_page' => $a['posts_per_page']
		];

		// add teh tax query if needed
		if (isset($a['category'])) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'quotes_category',
					'field'    => 'slug',
					'terms'    => [$a['category']]
				]
			];
		}

		$quotes = new WP_Query($args);

		ob_start();
		if ($quotes->have_posts()) {
			// Setup location of stars PNG if needed
			$stars = $a['stars'] == true ? ' <img src="' . plugin_dir_url(__DIR__) . 'assets/img-stars.png" alt="5 Star Rating" class="review-stars"> ' : '';

			if ($a['layout'] == 'slider') {
				// Parse any addition slider options
				$slider_options = [];

				if (isset($a['nav_pos'])) {
					$slider_options['nav_pos'] = 'nav_pos="' . $a['nav_pos'] . '"';
				}

				if (isset($a['bullets'])) {
					$slider_options['bullets'] = 'bullets="' . $a['bullets'] . '"';
				}

				if (isset($a['arrows'])) {
					$slider_options['arrows'] = 'arrows="' . $a['arrows'] . '"';
				}

				$output = '<div class="quotes-slider">';
				$output .= count($slider_options) > 0 ? '[ux_slider ' . implode(' ', $slider_options) . ']' : '[ux_slider]';

				$grid_span = 12 / $a['columns'];

				$counter = 0;
				$closed = false;
				while ($quotes->have_posts()) {
					$quotes->the_post();
					$counter++;
					if ($counter == 1) {
						$output .= '[row]';
						$closed = false;
					}


					$output .= '[col span__sm="12" span="' . $grid_span . '"]';
					if	($a['show_titles'] == true) {
						$output .= '<h3>' . get_the_title() . '</h3>';
					}
					$output .= '<div class="quote-content">' . get_the_content() . '</div>';
					$output .= '<div class="author-content"><span class="dash">-</span> ' . get_field('author') . '<span class="stars">' . $stars . '</span></div>';
					$output .= '[/col]';

					if ($a['columns'] == $counter) {
						$output .= '[/row]';
						$counter = 0;
						$closed = true;
					}
				}

				if ($closed == false) {
					$output .= '[/row]';
				}

				$output .= '[/ux_slider]';
				$output .= '</div>';

			}

			if ($a['layout'] == 'list') {
				$output = '<div class="quotes-list">';
				$output .= '<ul class="list-inline">';

				while ($quotes->have_posts()) {
					$quotes->the_post();
					$output .= '<li>';
					$output .= '<h3>' . get_the_title() . '</h3>';
					$output .= '<div class="quote-content">' . get_the_content() . '</div>';
					$output .= '<div class="author-content"><span>-</span> ' . get_field('author') . $stars . '</div>';
					$output .= '</li>';
				}

				$output .= '</ul>';
				$output .= '</div>';
			}

			echo do_shortcode($output);


		}

		wp_reset_query();

		return ob_get_clean();
	}

}