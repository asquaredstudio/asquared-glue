<?php

/**
 * Class GlueShortCodes
 */
class GlueShortCodes {
	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * GlueShortCodes constructor.
	 */
	private function __construct() {
		add_shortcode('glue_quotes', [$this, 'outputQuotes']);
	}

	/**
	 * @return null|\GlueShortCodes
	 */
	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new GlueShortCodes();
		}

		return self::$instance;
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
			'posts_per_page' => 3,
			'category'       => null,
			'nav_pos'        => null
		), $attributes);

		$args = [
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
			// Parse any addition slider options
			$slider_options = [];

			if (isset($a['nav_pos'])) {
				$slider_options['nav_pos'] == 'nav_pos="' . $a['nav_pos'] . '"';
			}

			//$output = count($slider_options) > 0 ? '[ux_slider' . implode(' ', $slider_options) . ']' : '[ux_slider]';
			$output = '<div class="quotes-slider">';
			$output .= '[ux_slider]';

			while ($quotes->have_posts()) {
				$quotes->the_post();
				$output .= '[row]';
				$output .= '[col span__sm="12"]';
				$output .= '<h3>' . get_the_title() . '</h3>';
				$output .= '<div class="quote-content">' . get_the_content() . '</div>';
				$output .= '<div class="author-content"><span>-</span> ' . get_field('author') . '</div>';
				$output .= '[/col]';
				$output .= '[/row]';
			}

			$output .= '[/ux_slider]';
			$output .= '</div>';

			echo do_shortcode($output);
			//echo $output;

		}

		wp_reset_query();

		return ob_get_clean();
	}
}