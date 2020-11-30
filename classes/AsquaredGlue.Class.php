<?php
/**
 * Class AsquaredGlue
 */
class AsquaredGlue {
	/**
	 * @var null
	 */
	private static $instance = null;
	public $quotes;
	public $flatsomeFixes;

	/**
	 * AsquaredGlue constructor.
	 */
	private function __construct() {
		$this->quotes = GlueQuotes::getInstance();
		$this->flatsomeFixes = FlatsomeFixes::getInstance();
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