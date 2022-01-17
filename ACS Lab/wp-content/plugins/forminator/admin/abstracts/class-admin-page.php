<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Forminator_Admin_Page
 *
 * @since 1.0
 */
abstract class Forminator_Admin_Page {

	/**
	 * Current page ID
	 *
	 * @var number
	 */
	public $page_id = null;

	/**
	 * Current page slug
	 *
	 * @var string
	 */
	protected $page_slug = '';

	/**
	 * Path to view folder
	 *
	 * @var string
	 */
	protected $folder = '';

	/**
	 * @since 1.0
	 *
	 * @param string $page_slug  Page slug.
	 * @param string $folder
	 * @param string $page_title Page title.
	 * @param string $menu_title Menu title.
	 * @param bool   $parent     Parent or not.
	 * @param bool   $render     Render the page.
	 */
	public function __construct(
		$page_slug,
		$folder,
		$page_title,
		$menu_title,
		$parent = false,
		$render = true
	) {
		$this->page_slug = $page_slug;
		$this->folder    = $folder;

		if ( ! $parent ) {
			$this->page_id = add_menu_page(
				$page_title,
				$menu_title,
				forminator_get_admin_cap(),
				$page_slug,
				$render ? array( $this, 'render' ) : null,
				'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNCAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04LjU5NjM3IDEuNDU1MTFIMTEuNjM2NEMxMi4wMjIxIDEuNDU1MTEgMTIuMzkyMSAxLjYwODM3IDEyLjY2NDkgMS44ODExNkMxMi45Mzc2IDIuMTUzOTMgMTMuMDkwOSAyLjUyMzkgMTMuMDkwOSAyLjkwOTY3VjE0LjU0NkMxMy4wOTA5IDE0LjkzMTggMTIuOTM3NiAxNS4zMDE3IDEyLjY2NDkgMTUuNTc0NUMxMi4zOTIxIDE1Ljg0NzMgMTIuMDIyMSAxNi4wMDA2IDExLjYzNjQgMTYuMDAwNkgxLjQ1NDU1QzEuMDY4NzggMTYuMDAwNiAwLjY5ODgyOCAxNS44NDczIDAuNDI2MDQ3IDE1LjU3NDVDMC4xNTMyNjggMTUuMzAxNyAwIDE0LjkzMTggMCAxNC41NDZWMi45MDk2N0MwIDIuNTIzOSAwLjE1MzI2OCAyLjE1MzkzIDAuNDI2MDQ3IDEuODgxMTZDMC42OTg4MjggMS42MDgzNyAxLjA2ODc4IDEuNDU1MTEgMS40NTQ1NSAxLjQ1NTExSDQuNDk0NTRDNC42NDc0MiAxLjAzMzkxIDQuOTI2MjYgMC42Njk5ODMgNS4yOTMxOSAwLjQxMjgxMUM1LjY2MDEzIDAuMTU1NjQgNi4wOTczNyAwLjAxNzY2OTcgNi41NDU0NiAwLjAxNzY2OTdDNi45OTM1NCAwLjAxNzY2OTcgNy40MzA3OCAwLjE1NTY0IDcuNzk3NzIgMC40MTI4MTFDOC4xNjQ2NSAwLjY2OTk4MyA4LjQ0MzQ5IDEuMDMzOTEgOC41OTYzNyAxLjQ1NTExWk02Ljk0OTQ5IDEuNTc3NjdDNi44Mjk4OSAxLjQ5Nzc3IDYuNjg5MyAxLjQ1NTExIDYuNTQ1NDYgMS40NTUxMUM2LjM1MjU3IDEuNDU1MTEgNi4xNjc2IDEuNTMxNzQgNi4wMzEyMSAxLjY2ODE1QzUuODk0ODIgMS44MDQ1MyA1LjgxODE4IDEuOTg5NSA1LjgxODE4IDIuMTgyMzdDNS44MTgxOCAyLjMyNjIzIDUuODYwODMgMi40NjY4MyA1Ljk0MDc0IDIuNTg2NDNDNi4wMjA2NSAyLjcwNjAyIDYuMTM0MjQgMi43OTkyNiA2LjI2NzE0IDIuODU0MzFDNi40MDAwMyAyLjkwOTM2IDYuNTQ2MjUgMi45MjM3NCA2LjY4NzMyIDIuODk1NjlDNi44Mjg0IDIuODY3NjEgNi45NTc5OSAyLjc5ODM0IDcuMDU5NyAyLjY5NjYyQzcuMTYxNDIgMi41OTQ5MSA3LjIzMDY4IDIuNDY1MzMgNy4yNTg3NSAyLjMyNDI1QzcuMjg2ODEgMi4xODMxNyA3LjI3MjQyIDIuMDM2OTYgNy4yMTczNyAxLjkwNDA1QzcuMTYyMzMgMS43NzExOCA3LjA2OTA4IDEuNjU3NTkgNi45NDk0OSAxLjU3NzY3Wk0xLjQ1NDU1IDIuOTA5NjdWMTQuNTQ2SDExLjYzNjRWMi45MDk2N0g5LjQ1NDU1VjQuMzY0MkgzLjYzNjM2VjIuOTA5NjdIMS40NTQ1NVpNMy42MzYzNSA4LjcyNzI2SDkuNDU0NTNDOS42NDc0MiA4LjcyNzI2IDkuODMyMzkgOC44MDM5MiA5Ljk2ODc4IDguOTQwMzFDMTAuMTA1MiA5LjA3NjY5IDEwLjE4MTggOS4yNjE2NiAxMC4xODE4IDkuNDU0NTZDMTAuMTgxOCA5LjY0NzQzIDEwLjEwNTIgOS44MzI0IDkuOTY4NzggOS45Njg4MUM5LjgzMjM5IDEwLjEwNTIgOS42NDc0MiAxMC4xODE4IDkuNDU0NTMgMTAuMTgxOEgzLjYzNjM1QzMuNDQzNDcgMTAuMTgxOCAzLjI1ODQ5IDEwLjEwNTIgMy4xMjIxIDkuOTY4ODFDMi45ODU3MSA5LjgzMjQgMi45MDkwOCA5LjY0NzQzIDIuOTA5MDggOS40NTQ1NkMyLjkwOTA4IDkuMjYxNjYgMi45ODU3MSA5LjA3NjY5IDMuMTIyMSA4Ljk0MDMxQzMuMjU4NDkgOC44MDM5MiAzLjQ0MzQ3IDguNzI3MjYgMy42MzYzNSA4LjcyNzI2Wk05LjQ1NDUzIDYuNTQ1NDRIMy42MzYzNUMzLjQ0MzQ3IDYuNTQ1NDQgMy4yNTg0OSA2LjYyMjEgMy4xMjIxIDYuNzU4NDhDMi45ODU3MSA2Ljg5NDg3IDIuOTA5MDggNy4wNzk4MyAyLjkwOTA4IDcuMjcyNzRDMi45MDkwOCA3LjQ2NTYxIDIuOTg1NzEgNy42NTA1NyAzLjEyMjEgNy43ODY5OUMzLjI1ODQ5IDcuOTIzMzcgMy40NDM0NyA4IDMuNjM2MzUgOEg5LjQ1NDUzQzkuNjQ3NDIgOCA5LjgzMjM5IDcuOTIzMzcgOS45Njg3OCA3Ljc4Njk5QzEwLjEwNTIgNy42NTA1NyAxMC4xODE4IDcuNDY1NjEgMTAuMTgxOCA3LjI3Mjc0QzEwLjE4MTggNy4wNzk4MyAxMC4xMDUyIDYuODk0ODcgOS45Njg3OCA2Ljc1ODQ4QzkuODMyMzkgNi42MjIxIDkuNjQ3NDIgNi41NDU0NCA5LjQ1NDUzIDYuNTQ1NDRaTTYuNTQ1NDYgMTAuOTA5MUg5LjQ1NDU1QzkuNjQ3NDMgMTAuOTA5MSA5LjgzMjQxIDEwLjk4NTcgOS45Njg4IDExLjEyMjFDMTAuMTA1MiAxMS4yNTg1IDEwLjE4MTggMTEuNDQzNSAxMC4xODE4IDExLjYzNjRDMTAuMTgxOCAxMS44MjkzIDEwLjEwNTIgMTIuMDE0MiA5Ljk2ODggMTIuMTUwNkM5LjgzMjQxIDEyLjI4NyA5LjY0NzQzIDEyLjM2MzYgOS40NTQ1NSAxMi4zNjM2SDYuNTQ1NDZDNi4zNTI1NyAxMi4zNjM2IDYuMTY3NiAxMi4yODcgNi4wMzEyMSAxMi4xNTA2QzUuODk0ODIgMTIuMDE0MiA1LjgxODE5IDExLjgyOTMgNS44MTgxOSAxMS42MzY0QzUuODE4MTkgMTEuNDQzNSA1Ljg5NDgyIDExLjI1ODUgNi4wMzEyMSAxMS4xMjIxQzYuMTY3NiAxMC45ODU3IDYuMzUyNTcgMTAuOTA5MSA2LjU0NTQ2IDEwLjkwOTFaIiBmaWxsPSIjRjBGNkZDIiBmaWxsLW9wYWNpdHk9IjAuNiIvPgo8L3N2Zz4K'
			);
		} else {
			$this->page_id = add_submenu_page(
				$parent,
				$page_title,
				$menu_title,
				forminator_get_admin_cap(),
				$page_slug,
				$render ? array( $this, 'render' ) : null
			);
		}

		if ( $render ) {
			$this->render_page_hooks();
		}

		$this->init();

		add_filter( 'removable_query_args', array( $this, 'remove_notice_params' ) );

	}

	/**
	 * Use that method instead of __construct
	 *
	 * @todo  : deperecate this, since its not correct way to execute action on page,
	 * instead this function will executed everywhere on all pages,
	 *        unless you are really wanna do that?!
	 *
	 * @since 1.0
	 */
	public function init() {
	}

	/**
	 * Hooks before content render
	 *
	 * @since 1.0
	 */
	public function render_page_hooks() {
		add_action( 'load-' . $this->page_id, array( $this, 'before_render' ) );
		add_action( 'load-' . $this->page_id, array( $this, 'trigger_before_render_action' ) );
		add_filter( 'load-' . $this->page_id, array( $this, 'add_page_hooks' ) );
	}

	/**
	 * Return page slug
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_the_slug() {
		return $this->page_slug;
	}

	/**
	 * Called when page is loaded and content not rendered yet
	 *
	 * @since 1.0
	 */
	public function before_render() {
	}

	/**
	 * Trigger an action before this screen is rendered
	 *
	 * @since 1.0
	 */
	public function trigger_before_render_action() {
		do_action( 'forminator_loaded_admin_page_' . $this->get_the_slug() );
	}

	/**
	 * Add page screen hooks
	 *
	 * @since 1.0
	 */
	public function add_page_hooks() {
		add_filter( 'user_can_richedit', '__return_true' ); // Confirms wp editor script is loaded on Forminator admin pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_classes' ) );
		add_action( 'init', array( $this, 'init_scripts' ) );
	}

	/**
	 * Remove Get parameters for Forminator notices
	 *
	 * @param string[] $vars An array of query variables to remove from a URL.
	 * @return array
	 */
	public function remove_notice_params( $vars ) {
		$vars[] = 'forminator_notice';
		$vars[] = 'forminator_text_notice';

		return $vars;
	}

	/**
	 * Add page screen hooks
	 *
	 * @since 1.0
	 *
	 * @param $hook
	 */
	public function enqueue_scripts( $hook ) {
		// Load admin scripts.
		wp_register_script(
			'forminator-admin',
			forminator_plugin_url() . 'build/main.js',
			array(
				'backbone',
				'underscore',
				'jquery',
				'wp-color-picker',
			),
			FORMINATOR_VERSION,
			true
		);
		forminator_common_admin_enqueue_scripts();
	}

	/**
	 * Init Admin scripts
	 *
	 * @since 1.0
	 *
	 * @param $hook
	 */
	public function init_scripts( $hook ) {
		// Init jquery ui.
		forminator_admin_jquery_ui_init();
	}

	/**
	 * Render page header
	 *
	 * @since 1.0
	 */
	protected function render_header() {
		$this->show_css_warning();

		if ( $this->template_exists( $this->folder . '/header' ) ) {
			$this->template( $this->folder . '/header' );
		} else {
			?>
			<header class="sui-header">
				<h1 class="sui-header-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			</header>
			<?php
		}
	}

	/**
	 * Render page footer
	 *
	 * @since 1.0
	 */
	protected function render_footer() {
		$hide_footer = false;
		$footer_text = sprintf(/* translators: ... */
			__( 'Made with %s by WPMU DEV', 'wpmudev' ),
			' <i class="sui-icon-heart"></i>'
		);

		$hide_footer = apply_filters( 'wpmudev_branding_change_footer', $hide_footer );
		$footer_text = apply_filters( 'wpmudev_branding_footer_text', $footer_text );

		if ( $this->template_exists( $this->folder . '/footer' ) ) {
			$this->template( $this->folder . '/footer' );
		}
		?>
		<div class="sui-footer"><?php echo wp_kses_post( $footer_text ); ?></div>

		<?php if ( FORMINATOR_PRO ) { ?>

			<?php if ( ! $hide_footer ) : ?>
				<ul class="sui-footer-nav">
					<li><a href="https://wpmudev.com/hub2/" target="_blank"><?php esc_html_e( 'The Hub', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/projects/category/plugins/" target="_blank"><?php esc_html_e( 'Plugins', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/hub2/support/" target="_blank"><?php esc_html_e( 'Support', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/hub2/community/" target="_blank"><?php esc_html_e( 'Community', 'forminator' ); ?></a></li>
					<li><a href="https://wpmudev.com/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'forminator' ); ?></a></li>
					<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'forminator' ); ?></a></li>
				</ul>
			<?php endif; ?>

		<?php } else { ?>

			<ul class="sui-footer-nav">
				<li><a href="https://profiles.wordpress.org/wpmudev#content-plugins" target="_blank"><?php esc_html_e( 'Free Plugins', 'forminator' ); ?></a></li>
				<li><a href="https://wpmudev.com/features/" target="_blank"><?php esc_html_e( 'Membership', 'forminator' ); ?></a></li>
				<li><a href="https://wpmudev.com/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'forminator' ); ?></a></li>
				<li><a href="https://wordpress.org/support/plugin/forminator" target="_blank"><?php esc_html_e( 'Support', 'forminator' ); ?></a></li>
				<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'forminator' ); ?></a></li>
				<li><a href="https://wpmudev.com/hub-welcome/" target="_blank"><?php esc_html_e( 'The Hub', 'forminator' ); ?></a></li>
				<li><a href="https://wpmudev.com/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'forminator' ); ?></a></li>
				<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'forminator' ); ?></a></li>
			</ul>

		<?php } ?>

		<?php if ( ! $hide_footer ) : ?>
			<ul class="sui-footer-social">
				<li><a href="https://www.facebook.com/wpmudev" target="_blank">
					<i class="sui-icon-social-facebook" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Facebook', 'forminator' ); ?></span>
				</a></li>
				<li><a href="https://twitter.com/wpmudev" target="_blank">
					<i class="sui-icon-social-twitter" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Twitter', 'forminator' ); ?></span>
				</a></li>
				<li><a href="https://www.instagram.com/wpmu_dev/" target="_blank">
					<i class="sui-icon-instagram" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Instagram', 'forminator' ); ?></span>
				</a></li>
			</ul>
		<?php endif; ?>

		<?php
	}

	/**
	 * Render page container
	 *
	 * @since 1.0
	 */
	public function render() {

		$accessibility_enabled = get_option( 'forminator_enable_accessibility', false );
		?>

		<main class="sui-wrap <?php echo $accessibility_enabled ? 'sui-color-accessible' : ''; ?> <?php echo esc_attr( 'wpmudev-forminator-' . $this->page_slug ); ?>">

			<?php
			$this->render_header();

			$this->render_page_content();

			$this->render_footer();
			?>

		</main>

		<?php
	}

	/**
	 * Render actual page template
	 *
	 * @since 1.0
	 */
	protected function render_page_content() {
		$this->template( $this->folder . '/content' );
	}

	/**
	 * Load an admin template
	 *
	 * @since 1.0
	 *
	 * @param       $path
	 * @param array $args
	 * @param bool  $echo
	 *
	 * @return string
	 */
	public function template( $path, $args = array(), $echo = true ) {
		$file    = forminator_plugin_dir() . "admin/views/$path.php";
		$content = '';

		if ( is_file( $file ) ) {
			ob_start();

			if ( isset( $args['id'] ) ) {
				$template_class  = $args['class'];
				$template_id     = $args['id'];
				$title           = $args['title'];
				$header_callback = $args['header_callback'];
				$main_callback   = $args['main_callback'];
				$footer_callback = $args['footer_callback'];
			}

			include $file;

			$content = ob_get_clean();
		}

		if ( $echo ) {
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $content;
	}

	/**
	 * Check if template exist
	 *
	 * @since 1.0
	 *
	 * @param $path
	 *
	 * @return bool
	 */
	protected function template_exists( $path ) {
		$file = forminator_plugin_dir() . "admin/views/$path.php";

		return is_file( $file );
	}

	/**
	 * Generates the admin body class required for WPMU DEV Shared UI
	 *
	 * @since 1.0.2
	 * @return string $sui_body_class
	 */
	public function get_sui_body_class() {
		$sanitize_version = str_replace( '.', '-', FORMINATOR_SUI_VERSION );
		$sui_body_class   = "sui-$sanitize_version";

		return $sui_body_class;
	}

	/**
	 * Add admin body classes
	 *
	 * @since 1.0.2
	 *
	 * @param string $classes
	 *
	 * @return string $classes
	 */
	public function admin_body_classes( $classes ) {

		$screen = get_current_screen();

		$classes = '';

		// Do nothing if not a forminator page.
		if ( strpos( $screen->base, '_page_forminator' ) === false ) {
			return $classes;
		}

		$classes .= $this->get_sui_body_class();

		return $classes;

	}

	/**
	 * Get admin page param
	 *
	 * @since 1.5.4
	 * @return string
	 */
	protected function get_admin_page() {
		return Forminator_Core::sanitize_text_field( 'page' );
	}

	/**
	 * Redirect to referer if available
	 *
	 * @since 1.6
	 *
	 * @param string $fallback_redirect url if referer not found.
	 */
	protected function maybe_redirect_to_referer( $fallback_redirect = '', $to_referer = true ) {
		$referer = wp_get_referer();
		$referer = ! empty( $referer ) ? $referer : wp_get_raw_referer();
		$referer = remove_query_arg( array( 'export', 'delete', 'forminator_notice', 'forminator_text_notice' ), $referer );

		if ( $referer && $to_referer ) {
			wp_safe_redirect( $referer );
		} elseif ( $fallback_redirect ) {
			wp_safe_redirect( $fallback_redirect );
		} else {
			$admin_url = admin_url( 'admin.php' );
			$admin_url = add_query_arg(
				array(
					'page' => $this->get_admin_page(),
				),
				$admin_url
			);
			wp_safe_redirect( $admin_url );
		}

		exit();
	}

	/**
	 * Get css class used for box summary on admin page
	 *
	 * @since 1.6
	 * @return string
	 */
	public function get_box_summary_classes() {
		$classes = '';
		if ( Forminator::is_wpmudev_member() ) {
			$hide_branding         = false;
			$hide_branding         = apply_filters( 'wpmudev_branding_hide_branding', $hide_branding );
			$custom_branding_image = '';
			$custom_branding_image = apply_filters( 'wpmudev_branding_hero_image', $custom_branding_image );
			if ( $hide_branding && ! empty( $custom_branding_image ) ) {
				$classes .= ' sui-rebranded';
			} elseif ( $hide_branding && empty( $custom_branding_image ) ) {
				$classes .= ' sui-unbranded';
			}
		}

		return $classes;
	}

	/**
	 * Get image url for summary box
	 *
	 * @since 1.6
	 * @return string
	 */
	public function get_box_summary_image_url() {
		$image_url = '';
		if ( Forminator::is_wpmudev_member() ) {
			$image_url = apply_filters( 'wpmudev_branding_hero_image', $image_url );
		}

		return $image_url;
	}

	/**
	 * Get inline style for box summary-image div
	 *
	 * @since 1.6
	 * @return string
	 */
	public function get_box_summary_image_style() {
		$image_url = $this->get_box_summary_image_url();
		if ( ! empty( $image_url ) ) {
			return 'background-image:url(' . esc_url( $image_url ) . ')';
		}

		return '';
	}

	/**
	 * Show warning if frontend is loaded in https but the WordPress address url setting uses http only
	 *
	 * @since 1.15.1
	 */
	public function show_css_warning() {
		$home_url        = parse_url( home_url() );
		$site_url_option = parse_url( get_option( 'siteurl' ) ); // WordPress Address (URL).

		if (
			( 'https' === $home_url['scheme'] && 'https' === $site_url_option['scheme'] ) ||
			( 'http' === $home_url['scheme'] && 'http' === $site_url_option['scheme'] )
		) {
			return;
		}

		if ( is_multisite() && ! is_main_site() ) {

			$fix_notice = esc_html__( 'Kindly contact your network administrator.', 'forminator' );

		} else {

			/* translators: %1$s, %2$s. are placeholders */
			$fix_notice = sprintf(
				esc_html__( 'To fix this, go to Network Admin > Sites and change each site\'s URL from "http://" to "https://". If you are unable to make the change through the WordPress interface, you may need to %1$schange the URL directly in the database%2$s.', 'forminator' ),
				'<a href="https://wordpress.org/support/article/changing-the-site-url/#changing-the-url-directly-in-the-database" target="_blank">',
				'</a>'
			);
		}

		?>
			<div class="sui-notice sui-notice-warning">
				<p><?php esc_html_e( 'Forminator\'s CSS style cannot be loaded because your website\'s address is configured in WordPress to use HTTP instead of HTTPS. This may cause some web content, including Forminator forms, to display incorrectly.', 'forminator' ); ?></p>
				<p><?php echo wp_kses_post( $fix_notice ); ?></p>
			</div>
		<?php
	}
}
