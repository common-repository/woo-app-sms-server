<?php

/*
 * Plugin Name: APPSMSServer
 * Author: APPSMSServer
 * Text Domain: APPSMSServer
 */

/*
*	WP Main Menu Elements
*/

$lang['wp_menu_appsmsserver'] = 'APPSMSServer';
$lang['wp_menu_settings'] = 'Settings';
$lang['wp_menu_predefined'] = 'Predefined SMS';


/*
*	WP Menu Section: APPSMSServer
*/

$lang['tab_label_1'] = 'Home';
$lang['tab_label_2'] = 'Sent messages';

/*
*	APPSMSServer Tab: Statistics 
*/

$lang['stats_heading'] = 'Take your store beyond the typical - sell anything';
$lang['stats_subheading'] = '';
$lang['improve_woo'] = 'Improve the main features of your WooCommerce';
$lang['keep_clients_uptodate'] = 'Keep your clients up to date via SMS notifications.';
$lang['wp_feature_heading_1'] = 'SMS Server';
$lang['wp_feature_text_1'] = 'Now you can easily send SMS notification and bulk SMS to your customers using your smartphone and your telephone plan,without other costs and without external gateways.';
$lang['wp_feature_heading_2'] = 'WooCommerce SMS';
$lang['wp_feature_text_2'] = 'Now you can easily send SMS notification and bulk SMS to your customers using your smartphone and your telephone plan,without other costs and without external gateways.';
$lang['wp_feature_heading_3'] = 'Scheduled sending';
$lang['wp_feature_text_3'] = 'SMSs can be programmed (date, time) or priority queue (immediate referral) Install the SMS Server server application, configure your site API or platform and have your own sms server ready for use.';
$lang['follow_us'] = 'Follow Us on';
$lang['download_app'] = 'Download APP SMS';
$lang['download_from'] = 'from Google Magazin Play for';
$lang['account_info'] = 'Account Info';
$lang['app_connect_qr'] = 'APPSMSServer application connect QR';
$lang['sent_messages'] = 'Sent messages';
$lang['project_expires_on'] = 'Project expires on';
$lang['wallet'] = 'Wallet';

/*
*	APPSMSServer Tab: Sent Messages
*/

$lang['id'] = 'ID';
$lang['phone'] = 'Phone';
$lang['text'] = 'Text';
$lang['status'] = 'Status';
$lang['date'] = 'Date';

/*
*	WP Menu Section: Settings
*/

$lang['settings_tab_label_1_of_2'] = 'Login';
$lang['settings_tab_label_2_of_2'] = 'My account';
$lang['settings_tab_label_2'] = 'WooCommerce API';
$lang['settings_tab_label_3'] = 'Webhooks';

/*
*	Settings Tab: Login/My Account
*/

$lang['logged_in_as'] = 'You are now connected on APPSMSServer as: '. $this->api_sms_user_name;
$lang['chosen_project'] = 'Chosen project';
$lang['no_chosen_project'] = 'No project chosen.Please choose one from below';

/*
*	Settings Tab: WooCommerce API
*/

$lang['key_and_secret_location'] = 'You can find the WooCommerce API Key & Secret or create new ones';
$lang['wc_consumer_key'] = 'WC Consumer Key';
$lang['wc_consumer_secret'] = 'WC Consumer secret';

/*
*	Settings Tab: Webhooks
*/

$lang['change_webhooks'] = 'Change webhooks';

/*
*	WP Main Menu: Predefined SMS
*/

$lang['predef_tab_label_1'] = 'Lista mesaje predefinite';
$lang['predef_tab_label_2'] = 'Adauga mesaj predefinit (coming soon)';
$lang['predef_heading'] = 'APPSMSServer: Predefined SMS';

/*
*	Predefined SMS Tab: list
*/

$lang['predef_table_heading_1'] = 'WC Status';
$lang['predef_table_heading_2'] = 'Text';
$lang['predef_table_heading_3'] = 'Status APPSMSServer';
$lang['predefined_how_to'] = 'In order to add predefined messages you need to log into your account on <a target="_blank" href="https://www.appsmsserver.com/index.php/acp/predef_sms">appsmsserver.com</a>';

/*
*	WP Notices
*/

$lang['connected_as'] = 'You are now connected on APPSMSServer as '. esc_attr($_POST['app_sms_user_name']);
$lang['notice_err_invalid_cred'] = 'Username/password invalid.';
$lang['installed_but_no_project'] = 'APPSMSServer was installed, but a project hasn`t been chosen';
$lang['installed_but_not_conf'] = 'APPSMSServer was installed, but not configured';
$lang['webhooks_not_conf'] = 'WooCommerce API not configured yet. Please go to Woocommerce API Tab to setup.';
$lang['notice_err_invalid_wc_api'] = 'WooCommerce API invalid credentials';

/*
*	Solo words
*/

$lang['here'] = 'here';
$lang['project'] = 'project';
$lang['logout'] = 'Log-Out';
$lang['topic'] = 'topic';
$lang['name'] = 'name';
$lang['username'] = 'Username';
$lang['pass'] = 'Password';
$lang['or'] = 'or';
$lang['free'] = 'free';

//Login first
$lang['login_first'] = 'You have to login first in plugin <a href="' . admin_url('admin.php?page=app-sms-settings&tab=login') . '">Settings</a> in Wp ACP menu.';
$lang['click_here_to_register'] = 'Click here to register new account';

?>