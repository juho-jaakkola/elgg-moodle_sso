<?php
/**
 * Moodle SSO
 *
 * @author Juho Jaakkola
 * @copyright Mediamaisteri Group
 * @link http://www.mediamaisteri.com
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU Public License Version 2
 */

$moodle_url_label = elgg_echo('moodle_sso:settings:moodle_url');
$moodle_url_desc = elgg_echo('moodle_sso:settings:moodle_url:desc');
$moodle_url_input = elgg_view('input/text', array(
	'name' => 'params[moodle_url]',
	'value' => $vars['entity']->moodle_url
));

$api_key_label = elgg_echo('moodle_sso:settings:api_key');
$api_key_desc = elgg_echo('moodle_sso:settings:api_key:desc');
$api_key_input = elgg_view('input/password', array(
	'name' => 'params[api_key]',
	'value' => $vars['entity']->api_key
));

echo <<<FORM
	<div>
		<label>$moodle_url_label</label>
		$moodle_url_input
		<span class="elgg-text-help">$moodle_url_desc</span>
	</div>
	<div>
		<label>$api_key_label</label><br/>
		$api_key_input
		<span class="elgg-text-help">$api_key_desc</span>
	</div>
FORM;
