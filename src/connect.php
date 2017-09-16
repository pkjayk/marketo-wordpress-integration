<?php 
namespace MarketoIntegration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Exception;

/**
 * @author Jayson Kadlecek
 * @version 1.0
*/
final class Connect {

	/**
	 * The Marketo host url (omit "/identity")
	 *
	 *	@var string
	 *	
	*/
	private static $host;

	/**
	 * Guzzle Client
	 *
	 *	@var string
	 *	
	*/
	private $client;

	/**
	 * The Marketo clientId
	 *
	 *	@var string
	 *	
	*/
	private $clientId;

	/**
	 * The Marketo client secret
	 *
	 * @var string
	 *
	*/
	private $clientSecret;

	/**
	 * The Marketo accessToken granted to us upon successful authorization
	 *
	 * @var string
	 *
	*/
	private static $accessToken;

	function __construct($host, $clientId, $clientSecret) {
		Connect::$host = $host;
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;

		$this->client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => Connect::getHost(),
		    // You can set any number of default request options.
		    'timeout'  => 2.0,
		]);

		Connect::$accessToken = $this->requestToken();
	}

	/**
	 * Retrieves the access token from the private variable
	 * 
	 * @return access token string
	 *
	*/
	public static function getToken() {
		return Connect::$accessToken;
	}

	/**
	 * Retrieves the host url from the private variable
	 * 
	 * @return the host url
	 *
	*/
	public static function getHost() {
		return Connect::$host;
	}

	/**
	 * Requests an access token from Marketo by passing the clientId and clientSecret
	 * 
	 * @return access token
	 *
	*/
	private function requestToken() {

		try {
			$response = $this->client->request('GET', '/identity/oauth/token?grant_type=client_credentials&client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret);
		} catch (RequestException $e) {
			die("Error occurred retrieving Marketo access token.");
		}

		$token = (object)json_decode($response->getBody());

		return $token->access_token;
	}

	/**
	 * DOESN'T WORK YET!!!
	 *
	 * Takes host url and formats it in case the user left "/identity" trailing it. (since marketo provides the URL this way...)
	 * 
	 * @return properly formatted host
	 *
	*/
	private function formatHostURL($host) {
		return $host;
	}

}

?>