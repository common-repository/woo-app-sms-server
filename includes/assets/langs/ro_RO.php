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
$lang['wp_menu_settings'] = 'Setari';
$lang['wp_menu_predefined'] = 'Mesaje predefinite';


/*
*	WP Menu Section: APPSMSServer
*/

$lang['tab_label_1'] = 'Acasa';
$lang['tab_label_2'] = 'Mesaje trimise';

/*
*	APPSMSServer Tab: Statistics 
*/

$lang['stats_heading'] = 'In afara tiparului - WooCommerce Notificari SMS';
$lang['stats_subheading'] = '';
$lang['improve_woo'] = 'Imbunatateste calitatea vanzarilor WooCommerce';
$lang['keep_clients_uptodate'] = 'Tine-ti-va clientii la curent cu notificari sms.';
$lang['wp_feature_heading_1'] = 'SMS Server';
$lang['wp_feature_text_1'] = 'Acum poti sa trimiti notificari SMS targetate sau in masa catre clientii tai utilizand doar un dispozitiv android cu abonamentul sau cartela PrePaid, fara alte costuri sau comisioane externe!';
$lang['wp_feature_heading_2'] = 'WooCommerce SMS';
$lang['wp_feature_text_2'] = 'Trimite sms usor catre clientii tai WooCommerce.

Poti configura cand si ce SMS sa trimita catre clienti. (ex. Alege "Comanda noua" si clientul va primi mesajul tau predefinit instantaneu)';
$lang['wp_feature_heading_3'] = 'Trimitere programata';
$lang['wp_feature_text_3'] = 'SMS-urile pot fi programate (data, ora) sau in lista de asteptare in functie de prioritate (trimitere imediata) Instalezi aplicatia SMS Server server, configurezi API-ul sau platforma site-ului tau si ai propiul tau server sms gata de utilizare.';
$lang['follow_us'] = 'Urmareste-ne pe';
$lang['download_app'] = 'Descarca APP SMS';
$lang['download_from'] = 'din Google Magazin Play gratis';
$lang['account_info'] = 'Informatii cont';
$lang['app_connect_qr'] = 'QR de conectare aplicatie APPSMSServer';
$lang['sent_messages'] = 'Mesaje trimise';
$lang['project_expires_on'] = 'Proiect expira la data de ';
$lang['wallet'] = 'Portofel';

/*
*	APPSMSServer Tab: Sent Messages
*/

$lang['id'] = 'ID';
$lang['phone'] = 'Telefon';
$lang['text'] = 'Text';
$lang['status'] = 'Status';
$lang['date'] = 'Data';

/*
*	WP Menu Section: Settings
*/

$lang['settings_tab_label_1_of_2'] = 'Conectare';
$lang['settings_tab_label_2_of_2'] = 'Contul meu';
$lang['settings_tab_label_2'] = 'WooCommerce API';
$lang['settings_tab_label_3'] = 'Webhooks';

/*
*	Settings Tab: Login/My Account
*/

$lang['logged_in_as'] = 'Sunteti conectat la APPSMSServer pe adresa: '. $this->api_sms_user_name;
$lang['chosen_project'] = 'Proiect ales';
$lang['no_chosen_project'] = 'Nici un proiect ales. Va rugam alegeti din selectia de mai sus.';

/*
*	Settings Tab: WooCommerce API
*/

$lang['key_and_secret_location'] = 'Puteti gasi Cheia si secret-ul WooCommerce sau sa creati unele noi';
$lang['wc_consumer_key'] = 'Cheia de consumator WooCommerce';
$lang['wc_consumer_secret'] = 'Secretul de consumator WooCommerce';

/*
*	Settings Tab: Webhooks
*/

$lang['change_webhooks'] = 'Schimba webhook-uri';

/*
*	WP Main Menu: Predefined SMS
*/

$lang['predef_tab_label_1'] = 'Lista mesaje predefinite';
$lang['predef_tab_label_2'] = 'Adauga mesaj predefinit (In curand)';
$lang['predef_heading'] = 'APPSMSServer: Mesaje predefinite';

/*
*	Predefined SMS Tab: list
*/

$lang['predef_table_heading_1'] = 'Status WooCommerce';
$lang['predef_table_heading_2'] = 'Text';
$lang['predef_table_heading_3'] = 'Status APPSMSServer';
$lang['predefined_how_to'] = 'Pentru a adauga mesaje predefinite accesati contul dvs din <a target="_blank" href="https://www.appsmsserver.com/index.php/acp/predef_sms">appsmsserver.com</a>';

/*
*	WP Notices
*/

$lang['connected_as'] = 'Sunteti conectat la APPSMSServer pe adresa '. esc_attr($_POST['app_sms_user_name']);
$lang['notice_err_invalid_cred'] = 'Utilizator/parola gresite.';
$lang['installed_but_no_project'] = 'APPSMSServer a fost instalat, dar un proiect nu a fost selectat';
$lang['installed_but_not_conf'] = 'APPSMSServer a fost instalat, dar nu e configurat';
$lang['webhooks_not_conf'] = 'WooCommerce API nu este configurat inca. Va rugam sa deschideti Woocommerce API Tab pentru a configura.';
$lang['notice_err_invalid_wc_api'] = 'Date conectare WooCommerce API incorecte';

/* 
*	Solo words
*/

$lang['here'] = 'aici';
$lang['project'] = 'proiect';
$lang['logout'] = 'Deconectare';
$lang['topic'] = 'subiect';
$lang['name'] = 'nume';
$lang['username'] = 'Utilizator';
$lang['pass'] = 'Parola';
$lang['or'] = 'sau';
$lang['free'] = 'gratis';

//Login first
$lang['login_first'] = 'Trebuie mai intai sa va conectati in contuls dvs. APPSMSServer pentru a accesa <a href="' . admin_url('admin.php?page=app-sms-settings&tab=login') . '">setarile</a> in Wp ACP.';
$lang['click_here_to_register'] = 'Click aici pentru cont nou';

?>