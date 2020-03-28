<?php
/**
 * Contains most of this plugin's logic.
 *
 * @package Page_Themes
 */

namespace Page_Themes;

/**
 * Main plugin class. Contains most of this plugin's logic.
 */
class Page_Themes {
	/**
	 * This plugin's single instance.
	 *
	 * @var Page_Themes $instance Plugin instance.
	 */
	private static $instance;

	/**
	 * `Post_Handler` instance.
	 *
	 * @var Post_Handler $instance `Post_Handler` instance.
	 */
	private $post_handler;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Page_Themes Single class instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * (Private) constructor.
	 *
	 * @return self
	 */
	private function __construct() {
		$this->post_handler = new Post_Handler();
		$this->post_handler->register();
	}

	/**
	 * Registers action and filter callbacks.
	 *
	 * @return void
	 */
	public function register() {
		// Any hook after `setup_theme` and the wrong `functions.php` will get
		// loaded. Anything earlier and `url_to_postid()` might not work.
		add_action( 'setup_theme', array( $this, 'define_ready' ), 1 );

		add_filter( 'pre_option_template', array( $this, 'template' ) );
		add_filter( 'pre_option_stylesheet', array( $this, 'stylesheet' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Enables localization.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'page-themes', false, basename( dirname( dirname( __FILE__ ) ) ) . '/languages' );
	}

	/**
	 * Sets the `PAGE_THEMES_READY` constant.
	 *
	 * URLs should only be parsed after `PAGE_THEMES_READY` has been defined.
	 *
	 * @return void
	 */
	public function define_ready() {
		define( 'PAGE_THEMES_READY', true );
	}

	/**
	 * Returns the current page's template.
	 *
	 * For child themes, the parent theme is returned.
	 *
	 * @param string $template Site's active template.
	 *
	 * @return string Filtered template slug.
	 */
	public function template( $template ) {
		if ( ! defined( 'PAGE_THEMES_READY' ) || ! PAGE_THEMES_READY ) {
			return $template;
		}

		global $page_themes_theme;

		if ( ! empty( $page_themes_theme['template'] ) ) {
			return $page_themes_theme['template'];
		}

		$post_id = url_to_postid( $this->get_current_url() );

		if ( 0 === $post_id ) {
			return $template;
		}

		$slug = get_post_meta( $post_id, 'page_themes_theme', true );

		if ( '' === $slug ) {
			return $template;
		}

		$theme = wp_get_theme( $slug );

		if ( ! $theme->exists() ) {
			return $template;
		}

		$page_themes_theme['template'] = $theme->get_template();

		return apply_filters(
			'page_themes_theme_template',
			$page_themes_theme['template']
		);
	}

	/**
	 * Returns the current page's stylesheet.
	 *
	 * @param string $stylesheet Site's active stylesheet.
	 *
	 * @return string Filtered stylesheet slug.
	 */
	public function stylesheet( $stylesheet ) {
		if ( ! defined( 'PAGE_THEMES_READY' ) || ! PAGE_THEMES_READY ) {
			return $stylesheet;
		}

		global $page_themes_theme;

		if ( ! empty( $page_themes_theme['stylesheet'] ) ) {
			return $page_themes_theme['stylesheet'];
		}

		$post_id = url_to_postid( $this->get_current_url() );

		if ( 0 === $post_id ) {
			return $stylesheet;
		}

		$slug = get_post_meta( $post_id, 'page_themes_theme', true );

		if ( '' === $slug ) {
			return $stylesheet;
		}

		$theme = wp_get_theme( $slug );

		if ( ! $theme->exists() ) {
			return $stylesheet;
		}

		$page_themes_theme['stylesheet'] = $theme->get_stylesheet();

		return apply_filters(
			'page_themes_theme_stylesheet',
			$page_themes_theme['stylesheet']
		);
	}

	/**
	 * Returns `Post_Handler` instance.
	 *
	 * @return Post_Handler This plugin's `Post_Handler` instance.
	 */
	public function get_post_handler() {
		return $this->post_handler;
	}

	/**
	 * Returns the current URL.
	 *
	 * @return string Current request's URL.
	 */
	private function get_current_url() {
		$protocol    = ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http' );
		$host        = ( isset( $_SERVER['HTTP_HOST'] ) ? wp_unslash( $_SERVER['HTTP_HOST'] ) : '' ); // phpcs:ignore
		$request_uri = ( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '' ); // phpcs:ignore

		return apply_filters(
			'page_themes_current_url',
			esc_url_raw( "$protocol://{$host}{$request_uri}" )
		);
	}
}
