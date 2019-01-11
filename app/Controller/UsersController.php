<?php 
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use wataridori\ChatworkSDK\ChatworkApi;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;
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
		$apiKey = 'ad5ca7107b498fade7e4082667090704';
		// roomId EM X
		$roomId = '134322843';

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
}
?>