<?php
/**
 * Moodle SSO
 * 
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Juho Jaakkola <juho.jaakkola@mediamaisteri.com>
 * @copyright (C) Mediamaisteri Group
 * @link http://www.medimaisteri.com
 */

$english = array(
	'moodle_sso:settings:moodle_url' => 'Moodle URL',
	'moodle_sso:settings:moodle_url:desc' => 'Enter exactly the same URL that is used as wwwroot for the Moodle site.',
	'moodle_sso:settings:api_key' => 'API key',
	'moodle_sso:settings:api_key:desc' => 'The secret API key that is identical in both Moodle and Elgg',
	'moodle_sso:error:login_failed' => 'Automatic login failed',
	'moodle_sso:error:username_mismatch' => 'Error: Your Moodle username does not match your Elgg username! Please contact site administrator.',
	'moodle_sso:admin:username_mismatch' => 'Moodle SSO Error: Attempted to create an Elgg account for Moodle user %s but an account with the email %s already exists.',
	'moodle_sso:success' => 'Automatic login',
);

add_translation('en', $english);
