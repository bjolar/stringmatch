<?php
/**
* Class for curl calls.
*/

class Curl {

	private $cookie_location = "C:\wamp\www\Scrape\cookies.txt"; //byt till egen mapp
	private $last_url;
	private $http_code;

	public function getLastUrl() {
		return $this->last_url;
	}

	public function getHttpCode() {
		return $this->http_code;
	}

	/**
	* Get the content of specific url
	* Optional $post statements
	* @param string $url the url to get
	* @param array $post optional post array ex. array( "postName" => "postValue" )
	* @return string HTML(probably)
	*/
	public function getUrl($url, $post = null, $agent = null, $delete = false){

		if ($agent == null) {
			$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
		}


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_location);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_location);
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($post != null) {

			foreach ( $post as $key => $value) {
				$post_items[] = $key . '=' . $value;
			}
			$post_string = implode ('&', $post_items);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
		}

		if ($delete == true) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.google.com/");

		$result = curl_exec($ch);

		$this->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

		curl_close($ch);

		return $result;

	}
}

?>
