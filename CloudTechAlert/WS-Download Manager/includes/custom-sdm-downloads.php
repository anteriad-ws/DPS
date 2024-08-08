<?php

//Handles the download request
function handle_sdm_download_via_direct_post() {
	if ( isset( $_REQUEST['smd_process_download'] ) && $_REQUEST['smd_process_download'] == '1' ) {
		global $wpdb;
		$download_id    = absint( $_REQUEST['download_id'] );
		$download_title = get_the_title( $download_id );
		$download_link  = get_post_meta( $download_id, 'sdm_upload', true );

		//Do some validation checks
		if ( ! $download_id ) {
			wp_die( __( 'Error! Incorrect download item id.', 'simple-download-monitor' ) );
		}
		if ( empty( $download_link ) ) {
			wp_die( printf( __( 'Error! This download item (%s) does not have any download link. Edit this item and specify a downloadable file URL for it.', 'simple-download-monitor' ), $download_id ) );
		}

		
		$main_option = get_option( 'sdm_downloads_options' );

		$ipaddress  = '';
		//Check if do not capture IP is enabled.
		if ( ! isset( $main_option['admin_do_not_capture_ip'] ) ) {
			$ipaddress = sdm_get_ip_address();
		}

                $user_agent = '';
		//Check if do not capture User Agent is enabled.
		if ( ! isset( $main_option['admin_do_not_capture_user_agent'] ) ) {
			//Get the user agent data. The get_browser() function doesn't work on many servers. So use the HTTP var.
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$user_agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
			}
                }

                $referrer_url = '';
		//Check if do not capture Referer URL is enabled.
		if ( ! isset( $main_option['admin_do_not_capture_referrer_url'] ) ) {
			//Get the user agent data. The get_browser() function doesn't work on many servers. So use the HTTP var.
			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				$referrer_url = sanitize_text_field( $_SERVER['HTTP_REFERER'] );
			}
                }

		$date_time       = current_time( 'mysql' );
		$visitor_country = ! empty( $ipaddress ) ? sdm_ip_info( $ipaddress, 'country' ) : '';

		$visitor_name = sdm_get_logged_in_user();

		$anonymous_can_download = get_post_meta( $download_id, 'sdm_item_anonymous_can_download', true );

		// Check if we only allow the download for logged-in users
		if ( isset( $main_option['only_logged_in_can_download'] ) ) {
			if ( $main_option['only_logged_in_can_download'] && $visitor_name === false && ! $anonymous_can_download ) {
				//User not logged in, let's display the error message.
				//But first let's see if we have login page URL set so we can display it as well
				$loginMsg = '';
				if ( isset( $main_option['general_login_page_url'] ) && ! empty( $main_option['general_login_page_url'] ) ) {
					//We have a login page URL set. Lets use it.

					if ( isset( $main_option['redirect_user_back_to_download_page'] ) ) {
						//Redirect to download page after login feature is enabled.
						$dl_post_url    = get_permalink( $download_id );//The single download item page
						$redirect_url   = apply_filters( 'sdm_after_login_redirect_query_arg', $dl_post_url );
						$login_page_url = add_query_arg( array( 'sdm_redirect_to' => urlencode( $redirect_url ) ), $main_option['general_login_page_url'] );
					} else {
						$login_page_url = $main_option['general_login_page_url'];
					}

					$tpl      = __( '__Click here__ to go to login page.', 'simple-download-monitor' );
					$loginMsg = preg_replace( '/__(.*)__/', ' <a href="' . $login_page_url . '">$1</a> $2', $tpl );
				}
				wp_die( __( 'You need to be logged in to download this file.', 'simple-download-monitor' ) . $loginMsg );
			}
		}

		$visitor_name = ( $visitor_name === false ) ? __( 'Not Logged In', 'simple-download-monitor' ) : $visitor_name;

		// Get option for global disabling of download logging
		$no_logs = isset( $main_option['admin_no_logs'] );

		// Get optoin for logging only unique IPs
		$unique_ips = isset( $main_option['admin_log_unique'] );

		// Get post meta for individual disabling of download logging
		$get_meta             = get_post_meta( $download_id, 'sdm_item_no_log', true );
		$item_logging_checked = isset( $get_meta ) && $get_meta === 'on' ? 'on' : 'off';

		$dl_logging_needed = true;

		// Check if download logs have been disabled (globally or per download item)
		if ( $no_logs === true || $item_logging_checked === 'on' ) {
			$dl_logging_needed = false;
		}

		// Check if we are only logging unique ips
		if ( $unique_ips === true ) {
			$check_ip = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'custom_sdm_downloads WHERE post_id="' . $download_id . '" AND visitor_ip = "' . $ipaddress . '"' );

			//This IP is already logged for this download item. No need to log it again.
			if ( $check_ip ) {
				$dl_logging_needed = false;
			}
		}

		

		if ( $dl_logging_needed ) {
			// We need to log this download.
			$table = $wpdb->prefix . 'custom_sdm_downloads';
			$data  = array(
				'post_id'         => $download_id,
				'post_title'      => $download_title,
				'file_url'        => $download_link,
				'visitor_ip'      => $ipaddress,
				'date_time'       => $date_time,
				'visitor_country' => $visitor_country,
				'visitor_name'    => $visitor_name,
				'user_agent'      => $user_agent,
                                'referrer_url'     => $referrer_url,
			);

			$data = array_filter( $data ); //Remove any null values.
			$insert_table = $wpdb->insert( $table, $data );

			if ( $insert_table ) {
				//Download request was logged successfully
			} else {
				//Failed to log the download request
				wp_die( __( 'Error! Failed to log the download request in the database table', 'simple-download-monitor' ) );
			}
		}

		// Allow plugin extensions to hook into download request.
		do_action( 'sdm_process_download_request', $download_id, $download_link );

		// Should the item be dispatched?
		$dispatch = apply_filters( 'sdm_dispatch_downloads', get_post_meta( $download_id, 'sdm_item_dispatch', true ) );

		// Only local file can be dispatched.
		if ( $dispatch && ( stripos( $download_link, WP_CONTENT_URL ) === 0 ) ) {
			// Get file path
			$file = path_join( WP_CONTENT_DIR, ltrim( substr( $download_link, strlen( WP_CONTENT_URL ) ), '/' ) );
			// Try to dispatch file (terminates script execution on success)
			sdm_dispatch_file( $file );
		}

		// As a fallback or when dispatching is disabled, redirect to the file
		// (and terminate script execution).
		sdm_redirect_to_url( $download_link );
	}
}

/**
 * Dispatch file with $filename and terminate script execution, if the file is
 * readable and headers have not been sent yet.
 * @param string $filename
 * @return void
 */
function sdm_dispatch_file( $filename ) {

	if ( headers_sent() ) {
		trigger_error( __FUNCTION__ . ": Cannot dispatch file $filename, headers already sent." );
		return;
	}

	if ( ! is_readable( $filename ) ) {
		trigger_error( __FUNCTION__ . ": Cannot dispatch file $filename, file is not readable." );
		return;
	}

	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/octet-stream' ); // http://stackoverflow.com/a/20509354
	header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate' );
	header( 'Pragma: public' );
	header( 'Content-Length: ' . filesize( $filename ) );

	ob_end_clean();
	readfile( $filename );
	exit;
}

/* 
 * Handles the after login redirect if standard wordpress's user login is being used with this feature.
 */
function sdm_after_wp_user_login_redirect( $redirect_to, $request, $user ) {
    if(isset($_REQUEST['sdm_redirect_to']) && !empty($_REQUEST['sdm_redirect_to'])){
        //Check if the "redirect_user_back_to_download_page" feature is enabled on this site.
        $main_option = get_option( 'sdm_downloads_options' );
        if ( isset( $main_option[ 'redirect_user_back_to_download_page' ] ) ) {
            $redirect_to = urldecode($_REQUEST['sdm_redirect_to']);
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'sdm_after_wp_user_login_redirect', 10, 3 );

/*
 * Handles the redirect in some other situation (example: a plugin is being used for user management/membership system).
 */
function sdm_check_redirect_query_and_settings(){

    if(isset($_REQUEST['sdm_redirect_to']) && !empty($_REQUEST['sdm_redirect_to'])){
        //Check if the "redirect_user_back_to_download_page" feature is enabled on this site.
        $main_option = get_option( 'sdm_downloads_options' );
        if ( isset( $main_option[ 'redirect_user_back_to_download_page' ] ) ) {
            //Check if the user is logged-in (since we only want to redirect a logged-in user.
            $visitor_name = sdm_get_logged_in_user();
            if ($visitor_name !== false ) {
                $redirect_url = urldecode($_REQUEST['sdm_redirect_to']);
                wp_safe_redirect( $redirect_url );//user wp safe redirect.
                exit;
            }
        }
    }    
}

function sdm_get_download_count_for_post( $id ) {
    // Get number of downloads by counting db columns matching postID
    global $wpdb;
    
    $table		 = $wpdb->prefix . 'custom_sdm_downloads';
    

    $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE post_id=%s', $id ) );
    // Count database rows
    $db_count	 = $wpdb->num_rows;

    // Check post meta to see if we need to offset the count before displaying to viewers
    $get_offset = get_post_meta( $id, 'sdm_count_offset', true );

    if ( $get_offset && $get_offset != '' ) {

	$db_count = $db_count + $get_offset;
    }

    return $db_count;
}



function sdm_get_ip_address( $ignore_private_and_reserved = false ) {
    $flags = $ignore_private_and_reserved ? (FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) : 0;
    foreach ( array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ) as $key ) {
	if ( array_key_exists( $key, $_SERVER ) === true ) {
	    foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
		$ip = trim( $ip ); // just to be safe

		if ( filter_var( $ip, FILTER_VALIDATE_IP, $flags ) !== false ) {
		    return $ip;
		}
	    }
	}
    }
    return null;
}

/**
 * Get location information (country or other info) for given IP address.
 * @param string $ip
 * @param string $purpose
 * @return mixed
 */
function sdm_ip_info( $ip, $purpose = "location" ) {

    $continents = array(
	"AF"	 => "Africa",
	"AN"	 => "Antarctica",
	"AS"	 => "Asia",
	"EU"	 => "Europe",
	"OC"	 => "Australia (Oceania)",
	"NA"	 => "North America",
	"SA"	 => "South America"
    );

    $ipdat = @json_decode( file_get_contents( "http://www.geoplugin.net/json.gp?ip=" . $ip ) );

    if ( @strlen( trim( $ipdat->geoplugin_countryCode ) ) === 2 ) {
	switch ( $purpose ) {
	    case "location":
		return array(
		    "city"		 => @$ipdat->geoplugin_city,
		    "state"		 => @$ipdat->geoplugin_regionName,
		    "country"	 => @$ipdat->geoplugin_countryName,
		    "country_code"	 => @$ipdat->geoplugin_countryCode,
		    "continent"	 => @$continents[ strtoupper( $ipdat->geoplugin_continentCode ) ],
		    "continent_code" => @$ipdat->geoplugin_continentCode
		);
	    case "address":
		$address	 = array( $ipdat->geoplugin_countryName );
		if ( @strlen( $ipdat->geoplugin_regionName ) >= 1 )
		    $address[]	 = $ipdat->geoplugin_regionName;
		if ( @strlen( $ipdat->geoplugin_city ) >= 1 )
		    $address[]	 = $ipdat->geoplugin_city;
		return implode( ", ", array_reverse( $address ) );
	    case "city":
		return @$ipdat->geoplugin_city;
	    case "state":
		return @$ipdat->geoplugin_regionName;
	    case "region":
		return @$ipdat->geoplugin_regionName;
	    case "country":
		return @$ipdat->geoplugin_countryName;
	    case "countrycode":
		return @$ipdat->geoplugin_countryCode;
	}
    }

    // Either no info found or invalid $purpose.
    return null;
}

/*
 * Checks if the string exists in the array key value of the provided array. If it doesn't exist, it returns the first key element from the valid values.
 */

function sdm_sanitize_value_by_array( $to_check, $valid_values ) {
    $keys	 = array_keys( $valid_values );
    $keys	 = array_map( 'strtolower', $keys );
    if ( in_array( $to_check, $keys ) ) {
	return $to_check;
    }
    return reset( $keys ); //Return the first element from the valid values
}

function sdm_get_logged_in_user() {
    $visitor_name = false;

    if ( is_user_logged_in() ) {  // Get WP user name (if logged in)
	$current_user	 = wp_get_current_user();
	$visitor_name	 = $current_user->user_login;
    }

   

    $visitor_name = apply_filters('sdm_get_logged_in_user_name', $visitor_name);

    return $visitor_name;
}



function sdm_get_default_download_button_text( $download_id ) {
    $default_text	 = __( 'Download Now!', 'simple-download-monitor' );
    $meta_text	 = get_post_meta( $download_id, 'sdm_download_button_text', true );

    $button_text = ! empty( $meta_text ) ? $meta_text : $default_text;
    return $button_text;
}

/*
 * Use this function to read the current page's URL
 */
function sdm_get_current_page_url() {
    $page_url = 'http';

    if (isset($_SERVER['SCRIPT_URI']) && !empty($_SERVER['SCRIPT_URI'])) {
        $page_url = $_SERVER['SCRIPT_URI'];
        $page_url = apply_filters('sdm_get_current_page_url', $page_url);
        return $page_url;
    }

    if (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) {
        $page_url .= "s";
    }
    $page_url .= "://";
    if (isset($_SERVER["SERVER_PORT"]) && ($_SERVER["SERVER_PORT"] != "80")) {
        $page_url .= ltrim($_SERVER["SERVER_NAME"], ".*") . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $page_url .= ltrim($_SERVER["SERVER_NAME"], ".*") . $_SERVER["REQUEST_URI"];
    }

    $page_url = apply_filters('sdm_get_current_page_url', $page_url);
    return $page_url;
}

/*
 * Use this function to redirect to a URL
 */
function sdm_redirect_to_url( $url, $delay = '0', $exit = '1' ) {
    $url = apply_filters( 'sdm_before_redirect_to_url', $url );
    if ( empty( $url ) ) {
	echo '<strong>';
	_e( 'Error! The URL value is empty. Please specify a correct URL value to redirect to!', 'simple-download-monitor' );
	echo '</strong>';
	exit;
    }
    if ( ! headers_sent() ) {
	header( 'Location: ' . $url );
    } else {
	echo '<meta http-equiv="refresh" content="' . $delay . ';url=' . $url . '" />';
    }
    if ( $exit == '1' ) {//exit
	exit;
    }
}

/*
 * Utility function to insert a download record into the logs DB table. Used by addons sometimes.
 */
function sdm_insert_download_to_logs_table( $download_id ){
    global $wpdb;

    if ( ! $download_id ) {
        SDM_Debug::log('Error! insert to logs function called with incorrect download item id.', false);
        return;
    }

    $main_option = get_option( 'sdm_downloads_options' );

    $download_title = get_the_title( $download_id );
    $download_link  = get_post_meta( $download_id, 'sdm_upload', true );

    $ipaddress  = '';
    //Check if do not capture IP is enabled.
    if ( ! isset( $main_option['admin_do_not_capture_ip'] ) ) {
            $ipaddress = sdm_get_ip_address();
    }

    $user_agent = '';
    //Check if do not capture User Agent is enabled.
    if ( ! isset( $main_option['admin_do_not_capture_user_agent'] ) ) {
            //Get the user agent data. The get_browser() function doesn't work on many servers. So use the HTTP var.
            if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
                    $user_agent = sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] );
            }
    }

    $referrer_url = '';
    //Check if do not capture Referer URL is enabled.
    if ( ! isset( $main_option['admin_do_not_capture_referrer_url'] ) ) {
            //Get the user agent data. The get_browser() function doesn't work on many servers. So use the HTTP var.
            if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
                    $referrer_url = sanitize_text_field( $_SERVER['HTTP_REFERER'] );
            }
    }

    $date_time = current_time( 'mysql' );
    $visitor_country = ! empty( $ipaddress ) ? sdm_ip_info( $ipaddress, 'country' ) : '';

    $visitor_name = sdm_get_logged_in_user();
    $visitor_name = ( $visitor_name === false ) ? __( 'Not Logged In', 'simple-download-monitor' ) : $visitor_name;

    // Get option for global disabling of download logging
    $no_logs = isset( $main_option['admin_no_logs'] );

    // Get optoin for logging only unique IPs
    $unique_ips = isset( $main_option['admin_log_unique'] );

    // Get post meta for individual disabling of download logging
    $get_meta = get_post_meta( $download_id, 'sdm_item_no_log', true );
    $item_logging_checked = isset( $get_meta ) && $get_meta === 'on' ? 'on' : 'off';

    $dl_logging_needed = true;

    // Check if download logs have been disabled (globally or per download item)
    if ( $no_logs === true || $item_logging_checked === 'on' ) {
            $dl_logging_needed = false;
    }

    // Check if we are only logging unique ips
    if ( $unique_ips === true ) {
            $check_ip = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . ' custom_sdm_downloads WHERE post_id="' . $download_id . '" AND visitor_ip = "' . $ipaddress . '"' );

            //This IP is already logged for this download item. No need to log it again.
            if ( $check_ip ) {
                    $dl_logging_needed = false;
            }
    }

   

    if ( $dl_logging_needed ) {
            // We need to log this download.
            
            $table = $wpdb->prefix .'custom_sdm_downloads';
            $data  = array(
                    'post_id'         => $download_id,
                    'post_title'      => $download_title,
                    'file_url'        => $download_link,
                    'visitor_ip'      => $ipaddress,
                    'date_time'       => $date_time,
                    'visitor_country' => $visitor_country,
                    'visitor_name'    => $visitor_name,
                    'user_agent'      => $user_agent,
                    'referrer_url'    => $referrer_url,
            );

            $data = array_filter( $data ); //Remove any null values.
            $insert_table = $wpdb->insert( $table, $data );

            if ( $insert_table ) {
                    //Download request was logged successfully
                    SDM_Debug::log('Download has been logged in the logs table for download ID: '. $download_id);
            } else {
                    //Failed to log the download request
                    SDM_Debug::log('Error! Failed to log the download request in the database table.', false);
            }
    }
}