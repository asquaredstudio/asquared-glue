<?php

/**
 * Class FlatsomeFixes
 */
class FlatsomeFixes {
	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * FlatsomeFixes constructor.
	 */
	private function __construct() {
		// This is responsible for proper WordPress hooking - giggidy
		add_action('init', [$this, 'actionMapper']);

	}

	/**
	 * Properly manages WordPress's actions
	 *
	 * @return void
	 */
	function actionMapper() {
		remove_action( 'flatsome_footer', 'flatsome_page_footer', 10 );
		add_action('flatsome_footer', [$this, 'newFlatsomeFooter'], 10);
		add_action('wp_head', [$this, 'insertCSSVariables'], 0);
	}

	/**
	 * Inserts :root level CSS variables
	 *
	 * @return string
	 */
	function insertCSSVariables() {
		?>
		<style>
			:root {
				--primary-color: <?php echo get_theme_mod('color_primary', Flatsome_Default::COLOR_PRIMARY ); ?>;
				--secondary-color: <?php echo get_theme_mod('color_secondary', Flatsome_Default::COLOR_SECONDARY ); ?>;
				--alert-color: <?php echo get_theme_mod('color_alert', Flatsome_Default::COLOR_ALERT ); ?>;
				--success-color: <?php echo get_theme_mod('color_success', Flatsome_Default::COLOR_SUCCESS ); ?>;
			}
		</style>
		<?php
	}

	/**
	 * The original Flatsome footer with the silly
	 * "absolute footer" removed!! Yay!
	 */
	function newFlatsomeFooter() {
		$block = get_theme_mod( 'footer_block' );

		if ( is_page() ) {
			// Custom Page footers.
			$page_footer = get_post_meta( get_the_ID(), '_footer', true );
			$default     = empty( $page_footer ) || $page_footer == 'normal';

			if ( $page_footer !== 'disabled' ) {
				if ( ! $block ) {
					if ( $default ) {
						get_template_part( 'template-parts/footer/footer' );
					} elseif ( ! empty( $page_footer ) ) {
						get_template_part( 'template-parts/footer/footer', $page_footer );
					}
				} else {
					echo do_shortcode( '[block id="' . $block . '"]' );
				}
			}
		} else {
			// Global footer.
			if ( $block ) {
				echo do_shortcode( '[block id="' . $block . '"]' );
			} else {
				get_template_part( 'template-parts/footer/footer' );
			}
		}
	}



	/**
	 * @return null|\FlatsomeFixes
	 */
	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new FlatsomeFixes();
		}

		return self::$instance;
	}
}