<?php

// Register system init event handler
elgg_register_event_handler('init','system','moodle_sso_init');

/**
 * Initialize plugin functionalities.
 */
function moodle_sso_init() {
	// Register event that handles custom logout
	elgg_register_event_handler('logout', 'user', 'moodle_sso_logout', 0);

	moodle_sso();
}

/**
 * Trigger authentication request or response handling.
 */
function moodle_sso () {
	$pluginconfig = elgg_get_plugin_from_id('moodle_sso');

	if (!$pluginconfig->moodle_url || !$pluginconfig->api_key) {
		return false;
	}

	// Initiate authentication only if not logged in
	if (elgg_is_logged_in()) {
		return true;
	}

	$response = get_input('response');

	if ($response != null) {
		if ($response == '1') {
			moodle_sso_handle_response();
			return true;
		}
		if ($response == '0') {
			return true;
		}
	}

	$site = elgg_get_site_entity();

	// Walled Garden allows index page so check it separately
	if (!$site->isPublicPage() || current_page_url() == elgg_get_site_url()) {
		moodle_sso_request_auth();
	}

	return true;
}

/**
 * Request user authentication from Moodle
 */
function moodle_sso_request_auth() {
	$moodle_url = elgg_get_plugin_setting('moodle_url', 'moodle_sso');
	$referer = $_SERVER['HTTP_REFERER'];

	if (strstr($referer, $moodle_url)) {
		// Save the Moodle address that user came from
		$_SESSION['moodle_sso_referer_url'] = $_SERVER['HTTP_REFERER'];
	}

	$url = urlencode(current_page_url());

	$request = "{$moodle_url}/local/elgg_sso/?url={$url}";

	header("Location: {$request}");
	exit;
}

/**
 * Process the response from Moodle.
 */
function moodle_sso_handle_response () {
	$url        = get_input('url');
	$username   = get_input('username');
	$email	    = get_input('email');
	$name       = get_input('name');
	$code       = get_input('code');
	$time       = (int) get_input('time');

	$difference = time() - $time;

	// Test that request wasn't made more than a minute ago
	if ($difference < 60) {
		$secret = elgg_get_plugin_setting('api_key', 'moodle_sso');
		$moodle_url = elgg_get_plugin_setting('moodle_url', 'moodle_sso');

		// Test that request was made from correct Moodle
		if ($code === md5($username . $time . $moodle_url . $secret)) {
			$user = get_user_by_username($username);

			if (!$user) {
				// Check that email doesn't exist
				if (!get_user_by_email($email)) {
					// Create new user
					$password = generate_random_cleartext_password();

					try {
						$guid = register_user($username, $password, $name, $email);

						if ($guid) {
							elgg_set_user_validation_status($guid, true, 'moodle_sso');
							$user = get_user($guid);
						}
					} catch (RegistrationException $e) {
						$msg = $e->getMessage();
						register_error($msg);
					}
				} else {
					register_error(elgg_echo('moodle_sso:error:username_mismatch'));

					$msg = elgg_echo('moodle_sso:admin:username_mismatch', array($username, $email));
					elgg_add_admin_notice('moodle_sso_username_mismatch', $msg);
				}
			}

			if ($user) {
				login($user);

				system_message(elgg_echo('moodle_sso:success'));

				if (empty($url)) {
					$url = elgg_get_site_url();
				}

				// Forward to the page where user was going in the first place
				header("Location: $url");
				exit;
			}
		}
	}

	register_error(elgg_echo('moodle_sso:error:login_failed'));
}

/**
 * Log the current user out. Also forward back to Moodle if necessary.
 */
function moodle_sso_logout() {
	$user_guid = elgg_get_logged_in_user_guid();

	// Check if user accessed Elgg through Moodle
	$redirect_url = $_SESSION['moodle_sso_referer_url'];
	unset($_SESSION['moodle_sso_referer_url']);

	if (!$redirect_url) {
		/**
		 * User didn't access through Moodle. Forward to Elgg front page.
		 * 
		 * Parameter response=0 assures that SSO doesn't get
		 * triggered even if there is an open Moodle session.
		 */
		$redirect_url = elgg_get_site_url() . "?response=0";
	}

	if (isset($_SESSION['user'])) {
		$_SESSION['user']->code = "";
		$_SESSION['user']->save();
	}

	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['code']);
	unset($_SESSION['guid']);
	unset($_SESSION['id']);
	unset($_SESSION['user']);

	setcookie("elggperm", "", (time() - (86400 * 30)), "/");

	// pass along any messages
	$old_msg = $_SESSION['msg'];

	session_destroy();

	// starting a default session to store any post-logout messages.
	_elgg_session_boot(NULL, NULL, NULL);
	$_SESSION['msg'] = $old_msg;

	// Forward back to Moodle or to Elgg front page
	header("Location: $redirect_url");

	// Halt further code execution
	exit;
}
