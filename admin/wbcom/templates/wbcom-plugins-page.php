<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' );

    $wb_json_request_url           = WB_PLUGINS_JSON_URL . 'bp-activity-social-share/plugins.json';
    $plugin_request_url            = BP_ACTIVITY_SHARE_PLUGIN_URL . 'admin/wbcom/assets/json/plugins.json';

	$response_wb_json_request      = wp_remote_get( $wb_json_request_url, array( 'timeout' => 120 ) );
	$response_wb_json_request_head = wp_remote_head( $wb_json_request_url );
	$response_plugins              = array();

	if ( ! is_wp_error( $response_wb_json_request_head ) ) {

		if ( isset( $response_wb_json_request_head['response']['code'] ) && ( $response_wb_json_request_head['response']['code'] == 200 ) ) {
			$response_plugins = isset( $response_wb_json_request['body'] ) ? $response_wb_json_request['body'] : '';
		}
	}

	if ( empty( $response_plugins ) ) {
		$response_plugin_request  = wp_remote_get( $plugin_request_url, array( 'timeout' => 120 ) );
		if ( ! is_wp_error( $response_plugin_request ) ) {
			if ( isset( $response_plugin_request['response']['code'] ) && ( $response_plugin_request['response']['code'] == 200 ) ) {
				$response_plugins = isset( $response_plugin_request['body'] ) ? $response_plugin_request['body'] : '';
			}
		}
	}

	?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Addons', 'buddypress-share' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			if ( ! empty( $response_plugins ) ) {
				$response_plugins = json_decode( $response_plugins, true );
				if( !empty( $response_plugins ) && is_array( $response_plugins ) ) {
					$wb_admin_obj = new Wbcom_Admin_Settings();
					foreach ( $response_plugins as $key => $plugin_details ) {
						if ( 'free' === $plugin_details['type'] ) {
							$status = $wb_admin_obj->wbcom_plugin_status( $plugin_details[ 'slug' ] );
							if ( 'not_installed' == $status ) {
								$plugin_btn_text = esc_html__( 'Install', 'buddypress-share' );
								$toggle_class	 = 'fa fa-toggle-off';
								$plugin_action	 = 'install_plugin';
							} else if ( 'installed' == $status ) {
								$plugin_btn_text = esc_html__( 'Activate', 'buddypress-share' );
								$toggle_class	 = 'fa fa-toggle-off';
								$plugin_action	 = 'activate_plugin';
							} else {
								$plugin_btn_text = esc_html__( 'Deactivate', 'buddypress-share' );
								$toggle_class	 = 'fa fa-toggle-on';
								$plugin_action	 = 'deactivate_plugin';
							}
						}
						if ( 'free' === $plugin_details['type'] ) {
								?>
							<div class="wbcom-req-plugin-card">
								<div class="wbcom_single_left">
									<div class="wbcom_single_icon_wrapper">
										<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
									</div>
								</div>
								<div class="wbcom_single_right">
									<h3><a href="<?php echo esc_url( $plugin_details[ 'wp_url' ] ); ?>"><?php echo esc_html( $plugin_details[ 'name' ] ); ?></a></h3>
									<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
									<input type="hidden" class="plugin-slug" name="plugin-slug" value="<?php echo esc_attr( $plugin_details[ 'slug' ] ); ?>">
									<input type="hidden" class="plugin-action" name="plugin-action" value="<?php echo esc_attr( $plugin_action ); ?>">
									<div class="activation_button_wrap">
										<a href="" class="wbcom-plugin-action-button wb_btn wb_btn_default" >
											<i class="<?php echo $toggle_class; ?>"></i>
											<?php echo $plugin_btn_text; ?>
											<i class="fas fa-spinner fa-pulse" style="display:none"></i>
										</a>
									</div>
								</div>
							</div>
							<?php
						} else { ?>
							<div class="wbcom-req-plugin-card">
								<div class="wbcom_single_left">
									<div class="wbcom_single_icon_wrapper">
										<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
									</div>
								</div>
								<div class="wbcom_single_right">
									<h3><?php echo esc_html( $plugin_details[ 'name' ] ); ?></h3>
									<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
									<div class="activation_button_wrap">
										<a href="<?php echo esc_url( $plugin_details[ 'download_url' ] ); ?>" class="wb_btn wb_btn_default" target="_blank" >
											<i class="fa fa-eye"></i>
											<?php esc_html_e( 'View', 'buddypress-share' ); ?>
										</a>
									</div>
								</div>
							</div>
						<?php }	
					}
				}
			}
			?>
		</div>
	</div>

</div>