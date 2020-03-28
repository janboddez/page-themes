<?php

class Test_Page_Themes extends \WP_Mock\Tools\TestCase {
	public function setUp() : void {
		\WP_Mock::setUp();
	}

	public function tearDown() : void {
		\WP_Mock::tearDown();
	}

	public function test_page_themes_register() {
		$page_themes = \Page_Themes\Page_Themes::get_instance();

		\WP_Mock::expectActionAdded( 'setup_theme', array( $page_themes, 'define_ready' ), 1 );
		\WP_Mock::expectFilterAdded( 'pre_option_template', array( $page_themes, 'template' ) );
		\WP_Mock::expectFilterAdded( 'pre_option_stylesheet', array( $page_themes, 'stylesheet' ) );
		\WP_Mock::expectActionAdded( 'plugins_loaded', array( $page_themes, 'load_textdomain' ) );

		$page_themes->register();

		$this->assertHooksAdded();
	}
}
