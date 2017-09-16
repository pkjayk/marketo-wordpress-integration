<?php
namespace MarketoIntegration;

use MarketoIntegration\Connect;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use \stdClass;

/**
 * @author Jayson Kadlecek
 * @version 1.0
*/
class Campaign {

	/**
	 * Guzzle client init
	 *
	 *	@var object
	 *	
	*/
	public $client;

	/**
	 * Array of Marketo leadIds
	 *
	 *	@var array
	 *	
	*/
	public $leads;

	/**
	 * Array of Marketo tokens
	 *
	 *	@var array
	 *	
	*/
	public $tokens;

	function __construct() {

		$this->client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => Connect::getHost(),
		    // You can set any number of default request options.
		    'timeout'  => 2.0,
		]);

	}

	/**
	 * Trigger the campaign by sending a POST request
	 * @uses Connect::getToken()
	 *
	 * @return response telling us success or failure
	 * 
	*/
	public function triggerCampaign(int $id) {

		$body = $this->bodyBuilder();

		$response = $this->client->request('POST', '/rest/v1/campaigns/' . $id . '/trigger.json?access_token=' . Connect::getToken(), ['headers' => ['Content-Type' => 'application/json'],
		    'body' => $body
		]);

		return $response;
	}

	/**
	 * Builds out the proper body structure for triggering a campaign
	 *
	 * @return Nicely formatted JSON to send in campaign trigger post request
	 * 
	*/
	private function bodyBuilder(){
		// create empty structures
		$body = new stdClass();
		$input = new stdClass();


		// add leads array to input
		$body->input = $this->leads;

		// if tokens exists, add them to body->input
		if (isset($this->tokens)) {
			$body->input->tokens = $this->tokens;
		}

		$json = json_encode($body, JSON_PRETTY_PRINT);

		return $json;
	}




}
