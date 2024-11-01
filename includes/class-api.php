<?php 
/*
*	No direct access
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
*	API Class to communicate with APPSMSServer.com API
*/

class APPSMSApi {

	/*
	*	Defining class variables for init
	*/

	protected $ch;
	protected $ret;
	protected $settings;

	public function __construct($settings=array()) {

		/*
		*	Class init settings
		*/

		$this->settings = $settings;
		$this->ret = NULL;
		
	}

	/*
	*	Functions
	*/

	public function hb_digest_auth_remote_get( $url, $username, $password ) {
		/*
		 * Makes an initial request for the server's provided headers
		 */
		$response = wp_remote_get( $url, $request );
		$header = wp_remote_retrieve_header( $response, 'www-authenticate' );
		if ( empty( $header ) ) {
			return false;
		}
		/*
		 * Parses the 'www-authenticate' header for nonce, realm and other values.
		 */
		preg_match_all( '#(([\w]+)=["]?([^\s"]+))#', $header, $matches );
		$server_bits = array();
		foreach( $matches[2] as $i => $key ) {
			$server_bits[ $key ] = $matches[3][ $i ];
		}
		$nc = '00000001';
		$path = parse_url( $url, PHP_URL_PATH );
		$client_nonce = uniqid();
		$ha1 = md5( $username . ':' . $server_bits['realm'] . ' API' . ':' . $password );
		$ha2 = md5( 'GET:' . $path );
		// The order of this array matters, because it affects resulting hashed val
		$response_bits = array(
			$ha1,
			$server_bits['nonce'],
			$nc,
			$client_nonce,
			$server_bits['qop'],
			$ha2
		);
		$digest_header_values = array(
			'username'       => '"' . $username . '"',
			'realm'          => '"' . $server_bits['realm'] . ' API"',
			'nonce'          => '"' . $server_bits['nonce'] . '"',
			'uri'            => '"' . $path . '"',
			'response'       => '"' . md5( implode( ':', $response_bits ) ) . '"',
			'opaque'         => '"' . $server_bits['opaque'] . '"',
			'qop'            => $server_bits['qop'],
			'nc'             => $nc,
			'cnonce'         => '"' . $client_nonce . '"',
			);
		$digest_header = 'Digest ';
		foreach( $digest_header_values as $key => $value ) {
			$digest_header .= $key . '=' . $value . ', ';
		}
		$digest_header = rtrim( $digest_header, ', ' );
		$request = array(
			'headers'        => array(
				'Authorization'       => $digest_header,
			),
		);
		$response = wp_remote_get( $url, $request );
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}
		return wp_remote_retrieve_body( $response );
	}

	public function connect() {
		$httpapi = $this->hb_digest_auth_remote_get($this->settings['url'] . 'connect', $this->settings['user_name'], md5($this->settings['user_pass']));
		//$httapi = wp_remote_get($this->settings['url'] . 'connect');
		//$this->ret = wp_remote_retrieve_body($httpapi);
		if($this->ret)
			$this->ret = json_decode($this->ret, true);
		return $this->ret;
	}

	public function get_projects() {
		$httpapi = $this->hb_digest_auth_remote_get($this->settings['url'] . 'projects/user/'.urlencode($this->settings['user_name']), $this->settings['user_name'], md5($this->settings['user_pass']));
		$this->ret = $httpapi;
		if($this->ret)
			$this->ret = json_decode($this->ret, true);
		return $this->ret;
	}

	public function get($path='',$data=array()) {
		$strUrl = '';
		foreach($data as $k=>$v) if(!empty($k) && !empty($v)) $strUrl .= '/' . $k . '/' . $v;
		$httpapi = $this->hb_digest_auth_remote_get($this->settings['url'] . $path . $strUrl, $this->settings['user_name'], md5($this->settings['user_pass']));
		$this->ret = $httpapi;
		$this->ret = json_decode($this->ret, true);
		return $this->ret;
	}

	public function post() {

	}

	public function update() {

	}


}

?>