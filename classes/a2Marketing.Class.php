<?php

/**
 * Class a2Marketing
 */
class a2Marketing {
	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Stores the interval for which the maintenance nag was last seen
	 *
	 * @var int
	 */
	private $maintenanceNagInterval;

	/**
	 * Array of dashboard widgets that we want to keep
	 *
	 * @var string[]
	 */
	private $allowedWidgets;

	/**
	 * Defines the number of columns that the dashboard will have
	 *
	 * @var int
	 */
	private $dashboardColumns;

	/**
	 * a2Marketing constructor.
	 */
	private function __construct() {
		$this->maintenanceNagInterval = 7890000;                    // 3 month interval
		$this->dashboardColumns = 2;
		$this->allowedWidgets =  [
			'dashboard_site_health',
			'asquared_greeting',
//			'rg_forms_dashboard',
			'dashboard_activity',
			'dashboard_right_now'
		];

		add_action('admin_notices', [$this, 'maintenanceNag']);
		add_action('wp_dashboard_setup', [$this, 'addDashboardGreeting'],900);

//		add_filter( 'screen_layout_columns', [$this, 'single_screen_columns'] );
//		add_filter( 'get_user_option_screen_layout_dashboard', [$this, 'single_screen_dashboard'] );
	}

	function single_screen_columns( $columns ) {
		$columns['dashboard'] = $this->dashboardColumns;
		return $columns;
	}


	function single_screen_dashboard(){
		return $this->dashboardColumns;
	}


	/**
	 * @return null|\a2Marketing
	 */
	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new a2Marketing();
		}

		return self::$instance;
	}

	/**
	 * Adds our widget to the dashboard
	 *
	 * Removes ALL dashboard widgets
	 */
	function addDashboardGreeting() {
		wp_add_dashboard_widget('asquared_greeting', esc_html__('Greetings from (a)squaredstudio', 'wporg'), [$this, 'asquared_greeting_function']);
		global $wp_meta_boxes;

		foreach ($wp_meta_boxes['dashboard']['normal']['high'] as $key =>  $value) {
			if (!in_array($key, $this->allowedWidgets)) {
				unset($wp_meta_boxes['dashboard']['normal']['high'][$key]);
			}
		}

		foreach ($wp_meta_boxes['dashboard']['normal']['core'] as $key =>  $value) {
			if (!in_array($key, $this->allowedWidgets)) {
				unset($wp_meta_boxes['dashboard']['normal']['core'][$key]);
			}
		}

		foreach ($wp_meta_boxes['dashboard']['side']['core'] as $key =>  $value) {
			if (!in_array($key, $this->allowedWidgets)) {
				unset($wp_meta_boxes['dashboard']['side']['core'][$key]);
			}
		}

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	function asquared_greeting_function() {
		$output = [];
		$output[] = '<img src="https://asquaredstudio.com/wp-content/uploads/2015/12/img_logo-1.png" alt="(a)squaredstudio"><br>';
		$output[] = '<p style="font-weight: bold;">Questions? Need help? We are here! We offer updates, maintenance, and ongoing assistance to keep you going. Give us a call!</p>';
		$output[] = '<a href="tel:860.685.0741" class="button button-primary"><span class="dashicons dashicons-smartphone" style="padding-top: 4px;"></span> 860.685.0741</a>';
		$output[] = '&nbsp;';
		$output[] = '<a href="https://asquaredstudio.com" class="button button-primary" target="_blank"><span class="dashicons dashicons-admin-site-alt2" style="padding-top: 5px"></span> asquaredstudio.com</a>';

		echo implode('', $output);
	}

	/**
	 * Displays the admin nag that alerts the user to sign up for
	 * a maintenance package.
	 */
	function maintenanceNag() {
//		$now = time();
//		$last_nag = get_option('maintenance_nag');
//		update_option('maintenance_nag', $now);
//		$message = [];
//		$message[] = 'This nag last appeared at ' . $now . '.';
//		$message[] = 'The last SAVED time this appeared was at ' . $last_nag . '.';
		?>
		<!--		<div class="notice notice-success is-dismissible">-->
		<!--			<p>--><?php //_e( implode('<br>', $message), 'sample-text-domain' ); ?><!--</p>-->
		<!--		</div>-->
		<?php
	}
}