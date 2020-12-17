<?php
/**
 * (a)squared glue
 *
 * @package           asquaredglue
 * @author            Chris Carvache
 * @copyright         2020 (a)squaredstudio
 * @license           GPL-2.0-or-later
 * @wordpress-plugin
 * Plugin Name:       (a)squared glue
 * Plugin URI:        https://asquaredstudio.com
 * Description:       Custom functionality for this theme 'n stuff.
 * Version:           0.1.2
 * Requires at least: 5.5
 * Requires PHP:      7.0
 * Author:            Chris Carvache
 * Author URI:        https://asquaredstudio.com
 * Text Domain:       asquaredglue
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/asquaredstudio/asquared-glue
 */

require_once ('classes/GlueQuotes.Class.php');
require_once ('classes/FlatsomeFixes.Class.php');
require_once ('classes/AsquaredGlue.Class.php');
$asquaredGlue = AsquaredGlue::getInstance();