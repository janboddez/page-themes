<?php
/**
 * Handles everything related to custom fields.
 *
 * @package Page_Themes
 */

namespace Page_Themes;

/**
 * Post handler class. Handles everything related to custom fields.
 */
class Post_Handler {
	/**
	 * Registers action callbacks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'transition_post_status', array( $this, 'update_meta' ), 11, 3 );
	}

	/**
	 * Registers the "Page Theme" meta box.
	 *
	 * @return void
	 */
	public function add_meta_box() {
		$post_types = (array) apply_filters( 'page_themes_post_types', array( 'post', 'page' ) );

		if ( empty( $post_types ) ) {
			return;
		}

		// Add meta box, for those post types that are supported.
		add_meta_box(
			'page-themes',
			__( 'Page Theme', 'page-themes' ),
			array( $this, 'render_meta_box' ),
			$post_types,
			'side',
			'default'
		);
	}

	/**
	 * Echoes the "Page Theme" meta box.
	 *
	 * @param \WP_Post $post Post being edited.
	 *
	 * @return void
	 */
	public function render_meta_box( $post ) {
		$themes = array_keys( wp_get_themes() );

		wp_nonce_field( basename( __FILE__ ), 'page_themes_nonce' );
		?>
		<label for="page-themes-theme" class="screen-reader-text"><?php esc_html_e( 'Page Theme', 'page-themes' ); ?></label>
		<select name="page_themes_theme" id="page-themes-theme">
			<option></option>
			<?php
			foreach ( $themes as $slug ) :
				$name = wp_get_theme( $slug )->get( 'Name' );
				?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $slug, get_post_meta( $post->ID, 'page_themes_theme', true ) ); ?>><?php echo esc_html( $name ); ?></option>
				<?php
			endforeach;
			?>
		</select>
		<?php
	}

	/**
	 * Stores the selected page theme.
	 *
	 * @param string   $new_status Old post status.
	 * @param string   $old_status New post status.
	 * @param \WP_Post $post       Post object.
	 *
	 * @return void
	 */
	public function update_meta( $new_status, $old_status, $post ) {
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		if ( ! isset( $_POST['page_themes_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['page_themes_nonce'] ), basename( __FILE__ ) ) ) {
			// Nonce missing or invalid.
			return;
		}

		if ( ! isset( $_POST['page_themes_theme'] ) ) {
			return;
		}

		$slug = wp_unslash( $_POST['page_themes_theme'] ); // phpcs:ignore

		if ( '' === slug ) {
			delete_post_meta( $post->ID, 'page_themes_theme' );
			return;
		}

		$theme = wp_get_theme( $slug );

		if ( ! $theme->exists() ) {
			return;
		}

		update_post_meta( $post->ID, 'page_themes_theme', $slug );
	}
}
