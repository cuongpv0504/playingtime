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
		$members = $room->getMembers();
		foreach ($members as $member) {
		    // Print out User Information
		    pr($member);
		    if ($member->account_id == '1935679') {
		    	$room->sendMessageToList(array($member), 'Test gui cho Tam');
		    }
		}
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

		echo "<a href='$url'>Login with chatwork</a>";
	}

	public function callback()
	{
		//session_start();
		$this->autoRender = false;

		$url = 'https://oauth.chatwork.com/token';
		$data = array(
			'grant_type' => 'authorization_code',
			'code' => $_GET['code'],
			'redirect_uri' => OAUTH2_REDIRECT_URI
		);
		$header = array(
			'Authorization: Basic '.base64_encode(OAUTH2_CLIENT_ID.':'.OAUTH2_CLIENT_SECRET)
		);

		$data = http_build_query($data, '', '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);

		pr($response['access_token']);
		// $provider = new ChatWorkProvider(
		//     OAUTH2_CLIENT_ID,
		//     OAUTH2_CLIENT_SECRET,
		//     OAUTH2_REDIRECT_URI
		// );

		// $accessToken = $provider->getAccessToken((string) new AuthorizationCode(), [
		//     'code' => $_GET['code']
		// ]);
		// pr($accessToken->getToken());

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