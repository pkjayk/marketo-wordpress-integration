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
class Program {

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
	protected $leadIds = array();

	function __construct() {

		$this->client = new Client([
		    // Base URI is used with relative requests
		    'base_uri' => Connect::getHost(),
		    // You can set any number of default request options.
		    'timeout'  => 2.0,
		]);

	}

	/**
	 * Grabs all program members
	 *
	 * @return List containing all members of the program
	 *	
	*/
	private function getProgramMembers(int $id) {

		$response = $this->client->request('GET', '/rest/v1/leads/programs/' . $id . '.json?access_token=' . Connect::getToken());

		return json_decode($response->getBody());

	}

	/**
	* Asks for program members and then formats them to only show the leadIds (useful for sending emails to multiple members)
	*
	* @return Array of leadIds
	*
	*/
	public function getProgramMembersIDs(int $id) {
		// get the program members and explicitly convert to object
		$response = (object)$this->getProgramMembers($id);

		// separate members of list
		$members = $response->result;

		// loop through the object and select ids to append to our leadIds array
		foreach ($members as $member) {
			$this->leadIds[] = array('id' => $member->id);
		}

		return $this->leadIds;
	}

}