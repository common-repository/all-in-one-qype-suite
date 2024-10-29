<?php
require_once dirname(__FILE__).'/OAuth.php';
require_once dirname(__FILE__).'/xml2array.php';

class QypeAPI {			
	public static $key = 'YzMb2zIPVVmN95ATmcS4A';
	public static $secret = 'vCIdoEqYDPXgLAZJOUGKwm1AMfAgfzomDzDzEXJkXjk';
	public static $endpoint = 'http://api.qype.com/v1/';
	
	public static function get( $uri ) {
		$url = QypeAPI::sign($uri);
		$content = QypeAPI::request($url);
		return XML2Array::parse($content);		
	}
	
	public static function request( $url, $content = '' ) {
		if(!($fp = @ fopen($url, 'rb'))) return '';
		while (!feof($fp)) {
			$content .= fread($fp, 8192);
		}
		fclose($fp);		
		return $content;
	}	
	
	# Alternative:
	public static function curl_request( $url, $content = '' ) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);       	
		curl_close($ch);
		return $content;
	}		
	
	public static function sign( $uri ) { 
		$test_consumer = new OAuthConsumer( QypeAPI::$key, QypeAPI::$secret, NULL );
		$hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
		$url = QypeAPI::$endpoint.$uri;
		$parsed = parse_url($url);
		$params = array();
		if( !empty($parsed['query']) ) parse_str($parsed['query'], $params);		
		$req_req = OAuthRequest::from_consumer_and_token( $test_consumer, NULL, "GET", $url, $params );		
		$req_req->sign_request( $hmac_method, $test_consumer, NULL );
		return $req_req->to_url();	
	}	
}

class QypeResource {
	public $api_id;	         #unique string identifying this place
	public $id;	         #unique string identifying this place
	
	public $link;	         #link
	
	public $count = 0;       # count of assoc resource
	
	function __construct( $data, $resource, $debug = false ) {
		$this->api_id         = $this->s($data, 'id');
		$this->id             = $this->get_id($this->api_id);		
		
		if(!empty($data['link'])) {		
			$links = $data['link'];		
			foreach( $links AS $link ) {
				if( !empty($link['rel']) && $link['rel'] == 'alternate' ) $this->link = $link['href'];
				if( !empty($link['count']) ) $this->count += $link['count'];
			}
		}		
	}
	
	public static function find( $r, $id ) {
		$rs = $r.'s'; $caller = 'Qype'.ucfirst($r);				
		//check if place with given id exits
		//TODO
		// if not fetch it via API
		$data = QypeAPI::get( $rs.'/'.$id );
		// and store to DB			
		//TODO
		return new $caller( $data[$r] );	
	}
	
	protected static function find_all( $r, $params, $limit = 50, $page = 1 ) {
		$rs = $r.'s'; $caller = 'Qype'.ucfirst($r);
                $items = array();
		do {
			$uri = $rs.$params.'&page='.$page;
			$data = QypeAPI::get( $uri );
			if( empty($data[$rs]) ) return $items;
			if( !empty($data[$rs][$r]) ) {
				if(!empty($data[$rs][$r][0]) ) {					
					foreach( $data[$rs][$r] AS $item ) {
						$items[] = new $caller( $item );
					}
				}
				else {
					$items[] = new $caller( $data[$rs][$r] );
				}
			}
			$page++;			
		} while( sizeof($items) < $limit && $data[$rs]['total_entries'] >= $limit );
		return $items;
	}
	
	public function lat() {
		$p = explode(',', $this->point);
		return $p[0];
	}
	
	public function lng() {
		$p = explode(',', $this->point);
		return $p[1];
	}
	
	public function valid() {
		return 	!empty( $this->id );
	}
	
	protected function get_id($api_id) {
		$ids = explode(':', $api_id);
		$id = explode('/', array_pop($ids));
		return array_pop($id);
	}
	
	protected function s($data, $key) {
		if( !empty($data[$key]) && is_string($data[$key]) ) return $data[$key];
		return '';
	}
}

class QypePlace extends QypeResource {
	public $title;	         #name of this place
	public $average_rating;	 #rating based on all reviews and a secret formula
	public $point;	         #latitude and longitude of this place, comma seperated
	public $url;	         #website of the place	
	public $phone;	         #phonenumber, as entered by the user (not always a valid phonenumber, can contain other strings	
	
	public $image_large;	 #link to the main photo of this place, in three thumbnail sizes (small, medium, large)	
	public $image_medium;	 #link to the main photo of this place, in three thumbnail sizes (small, medium, large)	
	public $image_small;	 #link to the main photo of this place, in three thumbnail sizes (small, medium, large)	
	
	public $created;	 #time this place was created
	public $updated;	 #time this place was last updated
	
	#address;
	public $city;	         #city the place is in
	public $street;	         #street of the place	
	public $housenumber;	 #number of the place	
	public $postcode;	 #postcode of the place	
	
	protected static $resource = 'place';
	
	function __construct( $data, $debug = false ) {
		parent::__construct( $data, $debug );
		
		$this->title          = $this->s($data, 'title');
		$this->average_rating = $this->s($data, 'average_rating');
		$this->point          = $this->s($data, 'point');
		$this->url            = $this->s($data, 'url');
		$this->phone          = $this->s($data, 'phone');		
		$this->opening_hours  = $this->s($data, 'opening_hours');
		
		$this->created        = $this->s($data, 'created');		
		$this->updated        = $this->s($data, 'updated');
		
		if(!empty($data['image_attr'])) {		
			$image = $data['image_attr'];
			$this->image_large    = $this->s($image, 'large');
			$this->image_medium   = $this->s($image, 'medium');
			$this->image_small    = $this->s($image, 'small');
		}
		
		if(!empty($data['address'])) {		
			$address = $data['address'];
			$this->city           = $this->s($address, 'city');
			$this->street         = $this->s($address, 'street');
			$this->housenumber    = $this->s($address, 'housenumber');
			$this->postcode       = $this->s($address, 'postcode');
		}		
	}
	
	public static function find_by_id( $id ) {		
		return self::find( self::$resource, $id );	
	}	
	
	public static function find_by_term_and_city( $search_term, $city) {		
		$places = find_all_by_term_and_city( $search_term, $city, 1 );
		return $places[0];
	}
	
	public static function find_all_by_term_and_city( $search_term, $city, $limit = 20, $page = 1 ) {		
		return self::find_all( self::$resource, '?show='.$search_term.'&in='.$city, $limit, $page );
	}
	
	public function review_count() {
		return $this->count;
	}
	
}


class QypeLocator extends QypeResource {
	public $title;	         #name of this place
	public $point;	         #latitude and longitude of this place, comma seperated
	
	protected static $resource = 'locator';
	
	function __construct( $data, $debug = false ) {
		parent::__construct( $data, $debug );
		
		$this->title          = $this->s($data, 'title');
		$this->point          = $this->s($data, 'point');		
	}
	
	public static function find_by_id( $id ) {		
		return self::find( self::$resource, $id );
		
	}
	
	public static function find_by_term( $search_term ) {		
		$locators = find_all_by_term( $search_term, 1 );
		return $locators[0];
	}
	
	public static function find_all_by_term( $search_term, $limit = 20, $page = 1 ) {		
		return self::find_all( self::$resource, '?show='.$search_term, $limit, $page );
	}
	
}

class QypeUser extends QypeResource {
	public $title;	         #name of this place
	
	public $login;   	 # user interface language the user chose	
        public $language;	 # user interface language the user chose	
        public $points;	         # amount of Qype points the user collected	
        public $level;	         # written insider level status of the user, in the currently selected user interface language	
	
        public $image_tiny;      # link to the avatar photo of this user, in three thumbnail sizes (tiny, small, medium)	
        public $image_small;     # link to the avatar photo of this user, in three thumbnail sizes (tiny, small, medium)	
        public $image_medium;    # link to the avatar photo of this user, in three thumbnail sizes (tiny, small, medium)		
	
	public $created;	 #time the user registered on the site
	public $updated;	 #time the user was last updated	
	
	protected static $resource = 'user';
	
	function __construct( $data, $debug = false ) {
		parent::__construct( $data, $debug );
		
		$this->title          = $this->s($data, 'title');
		$this->language       = $this->s($data, 'language');
		$this->points         = $this->s($data, 'points');
		$this->level          = $this->s($data, 'level');
		$this->login          = $this->id;
		
		$this->created        = $this->s($data, 'created');		
		$this->updated        = $this->s($data, 'updated');
		
		if(!empty($data['image_attr'])) {		
			$image = $data['image_attr'];
			$this->image_tiny    = $this->s($image, 'tiny');
			$this->image_medium   = $this->s($image, 'medium');
			$this->image_small    = $this->s($image, 'small');
		}
	}
	
	public static function find_by_login( $login ) {		
		return self::find( self::$resource, $login );
		
	}
	
	public function get_id() {
		if( ereg("/([0-9]{4})/([0-9]{4})/", $this->image_tiny, $regs) ) return $regs[1].$regs[2];
		return 0;
	}
}
