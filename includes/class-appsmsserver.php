<?php 
/*
*	No direct access
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once dirname(__FILE__) . '/class-api.php';

final class APPSMSServer { 

	protected $cfg;
	protected $_nonce;

	/*
	*	WP DB Class
	*/

	protected $wpdb;

	/*
	*	Define consumer key and secret
	*/

	protected $consumer_key;
	protected $consumer_secret;

	/*
	*	APP SMS API KEY & SECRET
	*/

	protected $app_sms_connected;
	protected $app_sms_user_name;
	protected $app_sms_user_pass;

	/*
	*	Api class
	*/

	protected $Api;
	protected $Api_project;
	protected $api_settings;
	
	/*
	* Language var
	*/

	public $_l;


	public function __construct($cfg) {

		$this->cfg = $cfg;
		//$this->reset_helper();
		$this->_nonce = isset($_GET['_wpnonce']) ? sanitize_text_field($_GET['_wpnonce']) : '';


		global $wpdb;
		$this->wpdb = $wpdb;

		/*
		*	Get Consumer's
		*/

		$this->consumer_key = get_option('app_sms_woo_consumer_key');
		$this->consumer_secret = get_option('app_sms_woo_consumer_secret');

		/*
		*	GET APPSMS CFG
		*/

		$this->app_sms_connected = get_option('app_sms_connected');
		$this->app_sms_user_name = get_option('app_sms_user_name');
		$this->app_sms_user_pass = get_option('app_sms_user_pass');
		$this->api_project = get_option('app_sms_project_id');

		/*
		*	Notify admin if plugin not configured
		*/

		if($this->app_sms_connected !== 'T') {
			add_action('admin_notices', array($this, 'admin_notice'));
		}else{
		}

		/*
		*	Init API
		*/

		$this->api_settings = array(
			'url' => $this->cfg['server_url'] . 'index.php/WPPlugin/',
			'key' => $this->appsms_api_key,
			'headers' => array(),
			'data' => '',
			'user_name' => $this->app_sms_user_name,
			'user_pass' => $this->app_sms_user_pass
		);
		add_action( 'wp_dashboard_setup', array($this, 'example_add_dashboard_widgets') );

		/*
		*	Get lang by wp locale
		*/

		if(require_once(dirname(__FILE__) . '/assets/langs/' . get_locale()  . '.php'))
			$this->_l = $lang;
		else {
			require_once(dirname(__FILE__) . '/assets/langs/en_US.php');
			$this->_l = $lang;
		}
	}

	public function reset_helper() {
		update_option('app_sms_connected', 'F');
		update_option('app_sms_woo_consumer_key', '');
		update_option('app_sms_woo_consumer_secret', '');
		update_option('app_sms_user_name', '');
		update_option('app_sms_user_pass', '');
		update_option('app_sms_project_id', '');
	}

	public function bind_init_act() {
		$act = ( isset( $_GET['act'] ) && ! empty($_GET['act'] ) ) ? esc_attr( $_GET['act'] ) : '';
		if($act=== 'logout') {
			update_option('app_sms_connected', 'F');
			update_option('app_sms_user_name', '');
			update_option('app_sms_user_pass', '');
			header('Location: ' . admin_url('admin.php?page=app-sms-settings&tab=login', 'https'));
		}


	}

	/*
	*	WP Actions
	*/

	public function init() {
		$this->bind_init_act();
		add_action('admin_enqueue_scripts', array($this, 'append_css'));
		add_action('admin_menu', array($this, 'APPSMSTopMenu'));
		add_action('admin_menu', array($this, 'APPSMSServerSubElement'));
		add_action('admin_menu', array($this, 'APPSMSServerPredef'));
		if(empty($this->api_project))
			add_action('admin_notices', array($this, 'no_project_notice'));
		if(!get_option('APPSMSCFG')) {
			add_action('admin_notices', array($this, 'admin_notice'));
		}
	}

	/*
	*	Append APPSMSServer CSS to WP_head();
	*/

	public function append_css() {
		wp_register_style( 'appsmsserver', plugins_url('assets/appsms.css', __FILE__) );
    	wp_enqueue_style( 'appsmsserver' );
	}

	/*
	*	Plugin functions
	*/

	public function admin_notice() {
	    echo "<div id='notice' class='updated fade'><p>" . __( $this->_l['installed_but_not_conf'] ) . ". <a href='".admin_url('admin.php?page=app-sms-settings', 'https')."'>Click here to configure</a></p></div>\n";
	}

	public function no_project_notice() {
		echo "<div id='notice' class='updated fade'><p>" . __( $this->_l['installed_but_no_project'] ) . ". <a href='".admin_url('admin.php?page=app-sms-settings&tab=login', 'https')."'>Click here to choose one</a></p></div>\n";
	}

	public function APPSMSServerSubElement() {
		add_submenu_page('app-sms', $this->_l['wp_menu_settings'], $this->_l['wp_menu_settings'], 'manage_options', 'app-sms-settings', array($this, 'app_sms_settings'));
	}

	public function APPSMSTopMenu() {
		add_menu_page($this->_l['wp_menu_appsmsserver'], $this->_l['wp_menu_appsmsserver'], 'manage_options', 'app-sms', array($this, 'app_sms_main'), '', 55);
	}

	public function APPSMSServerPredef() {
		add_submenu_page('app-sms', $this->_l['wp_menu_predefined'], $this->_l['wp_menu_predefined'], 'manage_options', 'app-sms-predef', array($this, 'app_sms_predef'));
	}

	/*
	*	Pages functions
	*/

	public function update_vars() {
		/*
		*	Get Consumer's
		*/

		if(!empty($_POST['app_sms_woo_consumer_key']))
			$this->consumer_key = get_option('app_sms_woo_consumer_key');
		if(!empty($_POST['app_sms_woo_consumer_secret']))
			$this->consumer_secret = get_option('app_sms_woo_consumer_secret');

		/*
		*	GET APPSMS CFG
		*/
	
		if(!empty($_POST['app_sms_user_name']))
		{
			$this->app_sms_user_name = get_option('app_sms_user_name');
			$this->app_sms_connected = get_option('app_sms_connected');
		}
		if(!empty($_POST['app_sms_user_pass']))
		{
			$this->app_sms_user_pass = get_option('app_sms_user_pass');
			$this->app_sms_connected = get_option('app_sms_connected');
		}

		if(!empty($_POST['app_sms_project_id']))
			$this->api_project = get_option('app_sms_project_id');
	}

	public function app_sms_settings() {
		global $wpdb;
	    $customers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users;");
		    //must check that the user has the required capability 
		    if (!current_user_can('manage_options'))
		    {
		      wp_die( __('You do not have sufficient permissions to access this page.') );
		    }

		    // See if the user has posted us some information
		    // If they did, this hidden field will be set to 'Y'
		
		    if( isset($_POST['settings_posted']) && $_POST['settings_posted'] == 'Y' ) {
		    	if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'login-form'))
		    		wp_die('Invalid nonce');
		    	$this->api_settings['user_name'] = sanitize_email($_POST['app_sms_user_name']);
		    	$this->api_settings['user_pass'] = sanitize_text_field($_POST['app_sms_user_pass']);
				$this->Api = new APPSMSApi($this->api_settings);
		    	$chk = $this->Api->connect();
		    	if($chk['status']==='CONNECTED') {
		    		//Save login details to wp db
		    		update_option('app_sms_connected', 'T');
		    		if(isset($_POST['app_sms_user_name']))
		    			update_option('app_sms_user_name', sanitize_email($_POST['app_sms_user_name']));
		    		if(isset($_POST['app_sms_user_pass']))
		    			update_option('app_sms_user_pass', sanitize_text_field($_POST['app_sms_user_pass']));
		    		if(isset($_POST['app_sms_woo_consumer_key']))
		    			update_option('app_sms_woo_consumer_key', sanitize_text_field($_POST['app_sms_woo_consumer_key']));
		    		if(isset($_POST['app_sms_woo_consumer_secret']))
		    			update_option('app_sms_woo_consumer_secret', sanitize_text_field($_POST['app_sms_woo_consumer_secret']));
		    		if(isset($_POST['app_sms_project_id'])) 
		    			update_option('app_sms_project_id', sanitize_text_field($_POST['app_sms_project_id']));

		    	}else{
		    		if(isset($_POST['app_sms_project_id']) && is_int($_POST['app_sms_project_id'])) 
		    			update_option('app_sms_project_id', sanitize_text_field($_POST['app_sms_project_id']));
		    		if(isset($_POST['app_sms_woo_consumer_key']))
		    			update_option('app_sms_woo_consumer_key', sanitize_text_field($_POST['app_sms_woo_consumer_key']));
		    		if(isset($_POST['app_sms_woo_consumer_secret']))
		    			update_option('app_sms_woo_consumer_secret', sanitize_text_field($_POST['app_sms_woo_consumer_secret']));
		    		$this->api_settings['user_name'] = get_option('app_sms_user_name');
		    		$this->api_settings['user_pass'] = get_option('app_sms_user_pass');
		    		$this->Api = new APPSMSApi($this->api_settings);
		    		$chk = $this->Api->connect();
		    		if($chk['status'] !== 'CONNECTED' && $_POST['login_posted'] === 'Y') : ?>
		    			<div class="notice notice-error"><?=_e( $this->_l['notice_err_invalid_cred'] ); ?></div>
		    		<?php endif;
		    	}
		    	$this->update_vars();
		    	require_once dirname(__FILE__) . '/class-webhooks.php';
		    	$webhooks = new APPSMSWebHooks($this->consumer_key, $this->consumer_secret);
				$webhooks = $webhooks->get_webhooks();
				if(!$webhooks && $_POST['login_posted'] !== 'Y') : ?>
					<div class="notice notice-error"><?=_e( $this->_l['notice_err_invalid_wc_api'] ); ?></div>
				<?php endif;
		        // Put a "settings saved" message on the screen
		if($this->app_sms_connected === 'T') : 
		?>
		<div class="updated"><p><strong><?php _e( $this->_l['connected_as'] ); ?></strong></p></div>
		<?php
		endif;
		    }

		    // Now display the settings editing screen

		    echo '<div class="wrap">';

		    // header

		    echo "<h2>" . __( 'Plugin settings', 'menu-test' ) . "</h2>";

		    // settings form
		    $tab = ( ! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'login';
			$this->settings_tabs( $tab );
			if ( $tab == 'login' ) : ?>
					<?php if($this->app_sms_connected !== 'T') : ?>
						<form name="form1" method="post" action="">
						<?php wp_nonce_field('login-form'); ?>
						<input type="hidden" name="settings_posted" value="Y">
						<input type="hidden" name="login_posted" value="Y">
						<p><?php _e( $this->_l['username'] ); ?> 
						<input type="text" name="app_sms_user_name" value="<?=$this->app_sms_user_name;?>">
						</p>

						<p><?=_e( $this->_l['pass'] );?>
							<input type="password" name="app_sms_user_pass" value="<?=$this->app_sms_user_pass;?>">
						</p>

						<hr />
						<p class="submit">
						<input type="submit" name="Submit" class="button-primary button-appsms" value="<?php esc_attr_e('Login') ?>" />
						</p>
						<hr />
							<p class="text-center gray"><?=_e( $this->_l['or'] ); ?></p>
							<p class="text-center">
								<a onclick="window.location = '<?=$this->cfg['server_url'] . 'index.php/front#register';?>';return false;">
									<button class="woocommerce-BlankState-cta button-primary button button-appsms btn-lg"><?=_e ( $this->_l['click_here_to_register'] ); ?></button>
								</a>
							</p>
						</form>
					<?php else : ?>
						<p><b><?=_e( $this->_l['logged_in_as'] );?></b><?=$this->app_sms_user_name;?></p>
					<?php
						$this->api_settings['user_name'] = get_option('app_sms_user_name'); 
						$this->api_settings['user_pass'] = get_option('app_sms_user_pass'); 
						$this->Api = new APPSMSApi($this->api_settings); 
						$projects = $this->Api->get_projects(); 
						if($projects) : ?>
						<form name="form1" method="post" action="">
							<?php wp_nonce_field('login-form'); ?>
						<input type="hidden" name="settings_posted" value="Y">
						<?php $pr_def = ''; foreach($projects as $project) if($project['proiect_id'] == $this->api_project) $pr_def = $project['proiect_nume'] ?>
						<?php if(!empty($pr_def)) : ?>
							<p><?=_e( $this->_l['chosen_project'] ); ?>: <?=$pr_def;?></p>
						<?php else : ?>
							<p><?=_e( $this->_l['no_chosen_project'] ); ?></p>
						<?php endif; ?>
						<p><?=_e( ucfirst($this->_l['project']) );?>
							<select name="app_sms_project_id">
								<?php foreach($projects as $project) : ?>
									<option value="<?=$project['proiect_id'];?>"><?=$project['proiect_nume'];?></option>
								<?php endforeach; endif; ?>
							</select>
						</p>
						<p class="submit">
						<input type="submit" name="Submit" class="button-primary button-appsms" value="<?php esc_attr_e('Save Changes') ?>" />
						</p>
						</form>
						<p>
							<a href="<?=admin_url('admin.php?page=app-sms-settings&act=logout'); ?>"><button class="button"><?=_e( $this->_l['logout'] ); ?></button></a>
						</p>
					<?php endif;?>
			    <?php endif; if($tab==='woo-api') : ?>
			    	<?=_e( $this->_l['key_and_secret_location'] . ' <a href="' . admin_url('admin.php?page=wc-settings&tab=api&section=keys') . '">' . $this->_l['here'] . '</a>.');?>
			    	<hr />
			    	<form name="form1" method="post" action="">
			    		<?php wp_nonce_field('login-form'); ?>
						<input type="hidden" name="settings_posted" value="Y">
				    	<p><?=_e( $this->_l['wc_consumer_key'] );?>
							<input type="text" name="app_sms_woo_consumer_key" value="<?=$this->consumer_key;?>">
						</p>

						<p><?=_e( $this->_l['wc_consumer_secret'] ); ?>
							<input type="text" name="app_sms_woo_consumer_secret" value="<?=$this->consumer_secret;?>">
						</p>

						<hr />

						<p class="submit">
						<input type="submit" name="Submit" class="button-primary button-appsms" value="<?php esc_attr_e('Save Changes') ?>" />
						</p>
					</form>
				<?php endif; 
				if($tab==='webhooks') : 
					require_once dirname(__FILE__) . '/class-webhooks.php';
					$webhooks = new APPSMSWebHooks($this->consumer_key, $this->consumer_secret);
					$data = $webhooks->get_webhooks();
					ob_start();
					if($data) : foreach($data as $w) :
					?>
					<p><b><?=_e( ucfirst( $this->_l['name'] ) ); ?>:</b> <?=$w['name'];?></p>
					<p><b><?=_e( ucfirst( $this->_l['topic'] ) ); ?>:</b> <?=$w['topic'];?></p>
					<hr />
					<?php endforeach; 
						echo _e('<a class="button button-appsms" href="' . admin_url('admin.php?page=wc-settings&tab=api&section=webhooks') . '">' . $this->_l['change_webhooks'] . '</a>');
					else : 
						_e( '<p>' . $this->_l['webhooks_not_conf'] . '</p>' );
					endif;
					$html = ob_get_clean(); 
					echo $html;
				endif; ?>
		</div>

		<?php
	}

	public function app_sms_main() {
		if (!current_user_can('manage_options')) {
	        return;
	    }
		$tab = (! empty($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'stats';
		echo '<div class="wrap">';
		echo '<h2>' . __('APPSMSServer', 'menu-test') . '</h2>';
		$this->main_tabs( $tab );
		$this->Api = new APPSMSApi($this->api_settings);
		$qr = $this->Api->get('stats', array('proiect_id' => (int) $this->api_project));
		if($tab === 'stats') : ?>
			<div class="wrap woocommerce appsms_addons_wrap">
					<div class="addons-featured">
						<div class="addons-banner-block">
			<h1><?php _e( $this->_l['stats_heading'] ); ?></h1>
			<p><?php _e( $this->_l['stats_subheading'] ); ?></p>
			<div class="addons-banner-block-items">	
					<div class="addons-column-section">
						<div class="addons-column">
					<div class="addons-column-block">
			<h1><?php _e( $this->_l['improve_woo'] ); ?></h1>
			<p><?php _e( $this->_l['keep_clients_uptodate'] ); ?></p>
							<?php if($this->app_sms_connected === 'T') : ?>

							<?php else : ?>
								<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="/wp-content/plugins/appsmsserver/includes/assets/img/icon.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2><?php _e( $this->_l['wp_feature_heading_1'] ); ?></h2>
									<p><?php _e( $this->_l['wp_feature_text_1'] ); ?></p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="/wp-content/plugins/appsmsserver/includes/assets/img/woo.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2><?php _e( $this->_l['wp_feature_heading_2'] ); ?></h2>
									<p><?php _e( $this->_l['wp_feature_text_2'] ); ?></p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="/wp-content/plugins/appsmsserver/includes/assets/img/clock.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2><?php _e( $this->_l['wp_feature_heading_3'] ); ?></h2>
									<p><?php _e( $this->_l['wp_feature_text_3'] ); ?></p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Checkout Field Editor</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-checkout-field-editor/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $49		</a>
									<p>The checkout field editor provides you with an interface to add, edit and remove fields shown on your WooCommerce checkout page.</p>
						</div>
					</div>
							<?php endif; ?>					
									</div>

				<div class="addons-small-light-block">
			<div class="addons-small-light-block-content">
				<h1><?php _e( $this->_l['follow_us'] ); ?>: </h1>
				<p><a href="https://twitter.com/APPSMSServer?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @APPSMSServer</a></p>
				<p><a class="twitter-timeline" href="https://twitter.com/APPSMSServer?ref_src=twsrc%5Etfw">Tweets by APPSMSServer</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>
				<div class="addons-small-light-block-buttons">
											</div>
			</div>
		</div>
					</div>
						<div class="addons-column">
					<div class="addons-small-dark-block">
			<h1><?php _e( $this->_l['download_app'] ); ?></h1>
			<p><?php _e( $this->_l['download_from'] ); ?></p>
			<p><img style="height:150px;" src="/wp-content/plugins/appsmsserver/includes/assets/img/qrcode.png"></p>
			<div class="addons-small-dark-items">
									<div class="addons-small-dark-item">
														<a href="https://play.google.com/store/apps/details?id=ro.simpapp.smsserver"><img style="height: 44px;" src="/wp-content/plugins/appsmsserver/includes/assets/img/android.png"></a>
							</div>
							</div>
		</div>
		<div class="addons-small-dark-block">
			<h1><?php _e( $this->_l['account_info'] ); ?></h1>
			<hr />
			<?php if($this->app_sms_connected === 'T') : ?>
			<p><b><?=_e( $this->_l['sent_messages'] );?>:</b> <?=$qr['sms'];?></p>
			<p><b><?=_e( $this->_l['project_expires_on'] );?>:</b> <?=$qr['pr']['proiect_expires_at'];?></p>
			<p><b><?=_e( $this->_l['wallet'] );?>:</b> $<?=$qr['user']['wallet']['wallet_amount'];?></p>
			<?php else : ?>
				<p><?php _e( $this->_l['login_first'] ); ?></p>
			<?php endif; ?>
			<div class="addons-small-dark-items">
							</div>
		</div>
				<!--<div class="addons-column-block">
			<h1>APPSMSServer News Box</h1>
			<p>Learn how your store is performing with enhanced reporting</p>
												<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/ga-icon@2x.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Google Analytics</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-google-analytics/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			Free		</a>
									<p>Understand your customers and increase revenue with the world’s leading analytics platform.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/cart-reports-icon@2x.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Cart reports</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-cart-reports/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $79		</a>
									<p>Get real-time reports on what customers are leaving in their cart.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/cost-of-goods-icon@2x.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Cost of Goods</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-cost-of-goods/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $79		</a>
									<p>Easily track profit by including  cost of goods in your reports.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>WooCommerce Google Analytics Pro</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-google-analytics-pro/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $29		</a>
									<p>Add advanced event tracking and enhanced eCommerce tracking to your WooCommerce site.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>WooCommerce Customer History</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-customer-history/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $49		</a>
									<p>Observe how your customers use your store, keep a full purchase history log, and calculate the total customer lifetime value.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Kissmetrics</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/kiss-metrics/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $79		</a>
									<p>Easily add Kissmetrics event tracking to your WooCommerce store with one click.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>Mixpanel</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/mixpanel/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $79		</a>
									<p>Add event tracking powered by Mixpanel to your WooCommerce store.</p>
						</div>
					</div>
																<div class="addons-column-block-item">
						<div class="addons-column-block-item-icon">
							<img class="addons-img" src="https://d3t0oesq8995hv.cloudfront.net/add-ons/generic.png">
						</div>
						<div class="addons-column-block-item-content">
							<h2>WooCommerce Sales Report Email</h2>
									<a class="addons-button addons-button-solid" href="https://woocommerce.com/products/woocommerce-sales-report-email/?utm_source=product&amp;utm_medium=upsell&amp;utm_campaign=appsmsaddons&amp;utm_content=featured">
			From: $29		</a>
									<p>Receive emails daily, weekly or monthly with meaningful information about how your products are performing.</p>
						</div>
					</div>
									</div>-->

					</div>
						</div>
					</div>
					
	</div>
		<?php
		endif; 
		if($tab === 'sms_list') : ?>
			<?php if($this->app_sms_connected !== 'T') : ?>
				<p>
					<?=_e( $this->_l['login_first'] ); ?>
				</p>
			<?php else : ?>
			<table style="width:100%;text-align:center;">
				<thead>
					<tr>
						<th><?=_e( $this->_l['id'] );?></th>
						<th><?=_e( $this->_l['phone'] );?></th>
						<th><?=_e( $this->_l['text'] );?></th>
						<th><?=_e( $this->_l['status'] );?></th>
						<th><?=_e( $this->_l['date'] );?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?=_e( $this->_l['id'] );?></th>
						<th><?=_e( $this->_l['phone'] );?></th>
						<th><?=_e( $this->_l['text'] );?></th>
						<th><?=_e( $this->_l['status'] );?></th>
						<th><?=_e( $this->_l['date'] );?></th>
					</tr>
				</tfoot>
				<tbody>
			<?php
			$data = $this->Api->get('sms_list', array('proiect_id' => $this->api_project));
			if($data) if(is_array($data)) foreach($data as $sms) : if(is_array($sms) && count($sms) > 0) { $seen = 'Not sent'; if($sms['sms_seen_by_app'] === 'T') $seen = 'Sent'; ?>
			<tr>
				<td><?=$sms['sms_id'];?></td>
				<td><?=$sms['sms_phone'];?></td>
				<td><?=$sms['sms_text'];?></td>
				<td><?=$seen;?></td>
				<td><?=$sms['sms_data_created'];?></td>
			</tr>
			<?php } endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			<?php
		endif;

		if($tab === 'predefined_list') : ?>
		<?php if($this->app_sms_connected !== 'T') : ?>
			<p>
				You have to login first in plugin <a href="<?=admin_url('admin.php?page=app-sms-settings&tab=login');?>">Settings</a> in Wp ACP menu.
			</p>
		<?php else : ?>
			<!--Old tab-->
		<?php endif; ?>
		<?php endif;
		echo '</div>';
	}

	public function main_tabs( $current = 'stats' ) {
	    $tabs = array(
	        'stats'   => __( $this->_l['tab_label_1'] ),
	        'sms_list'   => __( $this->_l['tab_label_2'] )
	    );
	    $html = '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
	        $html .= '<a class="nav-tab ' . $class . '" href="' . admin_url('admin.php?page=app-sms&tab=' . $tab) . '">' . $name . '</a>';
	    }
	    $html .= '</h2>';
	    echo $html;
	}

	public function settings_tabs( $current = 'login' ) {
	    $tabs = array(
	        'login'   => __( $this->_l['settings_tab_label_1_of_2'] ), 
	        'woo-api'  => __( $this->_l['settings_tab_label_2'] ),
	        'webhooks' => __( $this->_l['settings_tab_label_3'] ) 
	    );
	    if($this->app_sms_connected === 'T')
	    	$tabs['login'] = __( $this->_l['settings_tab_label_2_of_2'] );

	    $html = '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
	        $html .= '<a class="nav-tab ' . $class . '" href="' . admin_url('admin.php?page=app-sms-settings&tab=' . $tab) . '">' . $name . '</a>';
	    }
	    $html .= '</h2>';
	    echo $html;
	}

	public function sms_predef_tabs( $current = 'list' ) {
	    $tabs = array(
	        'list'   => __( $this->_l['predef_tab_label_1'] )
	    );

	    $html = '<h2 class="nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
	        $html .= '<a class="nav-tab ' . $class . '" href="' . admin_url('admin.php?page=app-sms-predef&tab=' . $tab) . '">' . $name . '</a>';
	    }
	    $html .= '</h2>';
	    echo $html;
	}

	public function app_sms_predef() {
		if (!current_user_can('manage_options')) {
	        return;
	    }
		$tab = (! empty( $_GET['tab'] ) ) ? sanitize_text_field( $_GET['tab'] ) : 'list';
		?>
		<div class="wrap">
			<h2><?=_e( $this->_l['predef_heading'] );?></h2>
			<?php $this->sms_predef_tabs( $tab ); ?>
			<?php if($tab === 'list') : ?>
				<?php if($this->app_sms_connected === 'T') : ?>
					<table style="width:100%;text-align:center;">
					<thead>
						<tr>
							<th><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
							<th style="width:50%;"><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
							<th><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
							<th style="width:50%;"><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
							<th><?=_e( $this->_l['predef_table_heading_1'] ); ?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php else : ?>
						<p><?=_e( $this->_l['installed_but_not_conf'] ); ?></p>
					<?php endif; ?>
				<?php
				$this->Api = new APPSMSApi($this->api_settings);
				$data = $this->Api->get('predefined_list', array('proiect_id' => $this->api_project));
				if($data) if(is_array($data)) foreach($data as $sms) : $isActive = 'Inactive'; if($sms['predef_isActive'] === 'T') $isActive = 'Active'; ?>
				<tr>
					<td><?=$sms['predef_status'];?></td>
					<td style="width:50%;"><?=$sms['predef_text'];?></td>
					<td><?=$isActive;?></td>
				</tr>
				<?php endforeach; ?>
					</tbody>
				</table>

				<p><?php _e( $this->_l['predefined_how_to'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}


	/*
	*	Dashboard Widget
	*/

	public function example_add_dashboard_widgets() {

		/*wp_add_dashboard_widget(
	                 'example_dashboard_widget',         // Widget slug.
	                 'Example Dashboard Widget',         // Title.
	                 array($this, 'example_dashboard_widget_function') // Display function.
	        );*/
	}

	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	public function example_dashboard_widget_function() {

		// Display whatever it is you want to show.
		//echo "Hello World, I'm a great Dashboard Widget";
	}
}