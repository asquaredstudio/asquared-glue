<?php
/**
 * Class AsquaredGlue
 */
class AsquaredGlue {
	/**
	 * @var null
	 */
	private static $instance = null;

	/**
	 * AsquaredGlue constructor.
	 */
	private function __construct() {
		GlueShortcodes::getInstance();
	}

	/**
	 * @return null|\AsquaredGlue
	 */
	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new AsquaredGlue();
		}

		return self::$instance;
	}
}