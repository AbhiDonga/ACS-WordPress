<?php
$current_user = wp_get_current_user();
$banner_1x    = forminator_plugin_url() . 'assets/images/conditional-data.png';
$banner_2x    = forminator_plugin_url() . 'assets/images/conditional-data@2x.png';
?>

<div
	id="forminator-new-feature"
	class="sui-dialog sui-dialog-onboard"
	aria-hidden="true"
>

	<div class="sui-dialog-overlay sui-fade-out" data-a11y-dialog-hide="forminator-new-feature" aria-hidden="true"></div>

	<div
		class="sui-dialog-content sui-fade-out"
		role="dialog"
	>

		<div class="sui-slider forminator-feature-modal" data-prop="forminator_dismiss_feature_1158" data-nonce="<?php echo esc_attr( wp_create_nonce( 'forminator_dismiss_notification' ) ); ?>">

			<ul role="document" class="sui-slider-content">

				<li class="sui-current sui-loaded" data-slide="1">

					<div class="sui-box">

						<div class="sui-box-banner" role="banner" aria-hidden="true" style="background: #0073AA;">
							<img
								src="<?php echo esc_url( $banner_1x ); ?>"
								srcset="<?php echo esc_url( $banner_1x ); ?> 1x, <?php echo esc_url( $banner_2x ); ?> 2x"
								class="sui-image"
								alt="Forminator"
							/>
						</div>

						<div class="sui-box-header sui-block-content-center">

							<button data-a11y-dialog-hide="forminator-new-feature" class="sui-dialog-close forminator-dismiss-new-feature" aria-label="<?php esc_html_e( 'Close this dialog window', 'forminator' ); ?>"></button>

							<?php // if ( FORMINATOR_PRO ) { ?>

								<h2 class="sui-box-title"><?php esc_html_e( 'New! Conditionally Send Data to Apps', 'forminator' ); ?></h2>

							<?php // } else { ?>

							<?php // } ?>

						</div>

						<div class="sui-box-body sui-block-content-center">

							<p class="sui-description"><?php printf( esc_html__( 'Hey, %s! You can now conditionally send form data from Forminator to connected applications, such as adding a user to a relevant mailing list or user group, or sending data to a connected app only when the Consent checkbox is checked.', 'forminator' ), esc_html( ucfirst( $current_user->display_name ) ) ); ?></p>

						</div>
						<div class="sui-box-body" sui-spacing-bottom="0">

							<ul class="sui-list" sui-type="bullets">

								<li>
									<p class="sui-description"><strong sui-color="darkgray"><?php esc_html_e( 'Conditional After Submission Behavior', 'forminator' ); ?></strong></p>
									<p class="sui-description"><?php esc_html_e( 'After submission behaviors have a great new feature: you can now choose what happens after users successfully submit a form based on the data they provide. For example, you can redirect users to specific pages or display different submission messages.', 'forminator' ); ?></p>
								</li>

								<li>
									<p class="sui-description"><strong sui-color="darkgray"><?php esc_html_e( 'Support for hCaptcha', 'forminator' ); ?></strong></p>
									<p class="sui-description"><?php esc_html_e( 'In the latest release, you can choose between reCAPTCHA and hCaptcha to stop pesky robots from submitting form data.', 'forminator' ); ?></p>
								</li>

							</ul>

						</div>

						<div class="sui-box-footer sui-block-content-center">

							<button class="sui-button forminator-dismiss-new-feature" type="button" data-a11y-dialog-hide="forminator-new-feature"><?php esc_html_e( 'Got It', 'forminator' ); ?></button>

						</div>

					</div>

				</li>

			</ul>

		</div>

	</div>

</div>

<script type="text/javascript">
	jQuery( '#forminator-new-feature .forminator-dismiss-new-feature' ).on( 'click', function( e ) {
		e.preventDefault();

		var $notice = jQuery( e.currentTarget ).closest( '.forminator-feature-modal' );
		var ajaxUrl = '<?php echo esc_url( forminator_ajax_url() ); ?>';

		jQuery.post(
			ajaxUrl,
			{
				action: 'forminator_dismiss_notification',
				prop: $notice.data('prop'),
				_ajax_nonce: $notice.data('nonce')
			}
		).always( function() {
			$notice.hide();
		});
	});
</script>
