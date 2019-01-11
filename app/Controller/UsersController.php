<?php 
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use wataridori\ChatworkSDK\ChatworkApi;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use GuzzleHttp\Client;
/**
 * 
 */
class UsersController extends AppController
{	
	public function index(){
		$this->autoRender = false;
		$users = $this->User->find('all');
		return json_encode($users);
	}

	public function testApi()
	{
		$this->autoRender = false;
		// Apikey of Huy
		$apiKey = API_KEY;
		// roomId EM X
		$roomId = ROOM_ID;

		ChatworkSDK::setApiKey($apiKey);
		$api = new ChatworkApi();

		// Get user own information
		$me = $api->me();
		pr($me);

		// Get user own statics information
		$status = $api->getMyStatus();

		// Get user rooms list
		$room = new ChatworkRoom($roomId);
		// $members = $room->getMembers();
		// foreach ($members as $member) {
		//     // Print out User Information
		//     pr($member);
		//     if ($member->account_id == '1935679') {
		//     	$room->sendMessageToList(array($member), 'Test gui cho Tam');
		//     }
		// }
	}

	public function oauth()
	{
		$this->autoRender = false;
		$provider = new ChatWorkProvider(
		    OAUTH2_CLIENT_ID,
		    OAUTH2_CLIENT_SECRET,
		    OAUTH2_REDIRECT_URI
		);

		$url = $provider->getAuthorizationUrl([
		    'scope' => ['users.all:read', 'rooms.all:read_write']
		]);

		pr($url);
	}

	public function callback()
	{
		//session_start();
		$this->autoRender = false;
		pr(OAUTH2_CLIENT_ID);

		$provider = new ChatWorkProvider(
		    OAUTH2_CLIENT_ID,
		    OAUTH2_CLIENT_SECRET,
		    OAUTH2_REDIRECT_URI
		);

		$accessToken = $provider->getAccessToken((string) new AuthorizationCode(), [
		    'code' => $_GET['code']
		]);
		pr($accessToken->getToken());

		//$_SESSION['accessToken'] = $accessToken;
		// $apiKey = API_KEY;
		// // roomId EM X
		// $roomId = ROOM_ID;

		// ChatworkSDK::setApiKey($apiKey);
		// $api = new ChatworkApi();

		// // Get user own information
		// $me = $api->me();
		// pr($me);
	}
}
?>