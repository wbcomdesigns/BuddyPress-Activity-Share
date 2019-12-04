<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<div class="reign-demos-wrapper">
			<?php
			$wb_json_request_url           = WB_PLUGINS_JSON_URL . 'bp-activity-social-share/themes.json';
    		$theme_request_url             = BP_ACTIVITY_SHARE_PLUGIN_URL . 'admin/wbcom/assets/json/themes.json';

			$response_wb_json_request      = wp_remote_get( $wb_json_request_url, array( 'timeout' => 120 ) );
			$response_wb_json_request_head = wp_remote_head( $wb_json_request_url );
			$response_themes               = array();
			$get_plugin_response           = false;

			if ( ! is_wp_error( $response_wb_json_request_head ) ) {

				if ( isset( $response_wb_json_request_head['response']['code'] ) && ( $response_wb_json_request_head['response']['code'] == 200 ) ) {
					$response_themes = isset( $response_wb_json_request['body'] ) ? $response_wb_json_request['body'] : '';
				}
			}

			if ( empty( $response_themes ) ) {
				$response_theme_request  = wp_remote_get( $theme_request_url, array( 'timeout' => 120 ) );
				if ( ! is_wp_error( $response_theme_request ) ) {
					if ( isset( $response_theme_request['response']['code'] ) && ( $response_theme_request['response']['code'] == 200 ) ) {
						$response_themes = isset( $response_theme_request['body'] ) ? $response_theme_request['body'] : '';
						$get_plugin_response = true;
					}
				}
			}

			if ( ! empty( $response_themes ) ) {
				$response_themes = json_decode( $response_themes, true );
				if( !empty( $response_themes ) && is_array( $response_themes ) ) {
					$wb_admin_obj = new Wbcom_Admin_Settings();
					foreach ( $response_themes as $key => $theme_details ) {
						if ( $get_plugin_response ) {
							$image_url = BP_ACTIVITY_SHARE_PLUGIN_URL . 'admin/wbcom/assets/imgs/'. $theme_details['image_url'];
						} else {
							$image_url = $theme_details['image_url'];
						}
						?>
						<h4 class="wbcom-demo-name">
							<?php echo $key; ?>
						</h4>
						<div class="wbcom-demo-content-wrap">
							<div class="wbcom-demo-importer">			
								<div class="container">
									<div class="wbcom-image-wrapper">
										<img src="<?php echo $image_url; ?>" alt="Image" class="image" style="width:100%">
									</div>
									<div class="wbcom-demo-title">
										<h2><?php echo $theme_details['name']; ?></h2>
										<ul class="wbcom_theme_features_list">
											<?php
											if ( ! empty( $theme_details['features'] ) ) {
												foreach( $theme_details['features'] as $single_feature ) { ?>
				                            	<li><?php echo $single_feature; ?></li>
				                            	<?php }
				                        	}
				                            ?>
				                        </ul>
										<div class="wbcom-middle">
											<a href="<?php echo $theme_details['purchase_url']; ?>" class="wbcom-button wbcom-purchase" target="_blank"><?php esc_html_e( 'Purchase', 'buddypress-share' ); ?></a>
											<a target="_blank" href="<?php echo $theme_details['demo_url']; ?>" class="wbcom-button"><?php esc_html_e( 'Preview
											', 'buddypress-share' ); ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
				}
			}			
			?>
	</div>
</div>