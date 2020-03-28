<?php
/**
 * Plugin Name:       Page Themes
 * Description:       Set per-page themes.
 * Plugin URI:        https://janboddez.tech/wordpress/page-themes
 * GitHub Plugin URI: https://github.com/janboddez/page-themes
 * Author:            Jan Boddez
 * Author URI:        https://janboddez.tech/
 * License:           GNU General Public License v3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       page-themes
 * Version:           0.1.0
 *
 * @author  Jan Boddez <jan@janboddez.be>
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @package Page_Themes
 */

namespace Page_Themes;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require dirname( __FILE__ ) . '/includes/class-page-themes.php';
require dirname( __FILE__ ) . '/includes/class-post-handler.php';

Page_Themes::get_instance()->register();
