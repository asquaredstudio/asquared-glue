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
	 * @var null|\GlueQuotes
	 */
	public $quotes;

	/**
	 * @var null|\FlatsomeFixes
	 */
	public $flatsomeFixes;

	/**
	 * @var null|\a2Marketing
	 */
	public $marketing;

	
	/**
	 * AsquaredGlue constructor.
	 */
	private function __construct() {
		$this->quotes        = GlueQuotes::getInstance();
		$this->flatsomeFixes = FlatsomeFixes::getInstance();
		$this->marketing     = a2Marketing::getInstance();
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