<?php
/**
 * Moodle SSO
 * 
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Juho Jaakkola <juho.jaakkola@mediamaisteri.com>
 * @copyright (C) Mediamaisteri Group
 * @link http://www.mediamaisteri.com/
 */

$finnish = array(
	'moodle_sso:settings:moodle_url' => 'Moodlen URL',
	'moodle_sso:settings:moodle_url:desc' => 'Syötä osoite täsmälleen samassa muodossa kuin se on Moodlen configuraation wwwroot-asetuksessa.',
	'moodle_sso:settings:api_key' => 'API-avain',
	'moodle_sso:settings:api_key:desc' => 'Salainen API-avain, joka syötetään sekä Elggiin että Moodleen',
	'moodle_sso:error:login_failed' => 'Automaattinen kirjautuminen epäonnistui',
	'moodle_sso:error:username_mismatch' => 'Virhe: Moodle-tunnuksesi ei vastaa Elgg-tunnustasi! Ota yhteys sivuston ylläpitäjään.',
	'moodle_sso:admin:username_mismatch' => 'Moodle SSO virhe: Yritettiin luoda Elgg-tili Moodle-käyttäjälle %s, mutta sähköpostiosoite %s on jo toisen tilin käytössä.',
	'moodle_sso:success' => 'Automaattinen kirjautuminen',
);

add_translation('fi', $finnish);
