<?php  
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use ChatWork\OAuth2\Client\ChatWorkProvider;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use GuzzleHttp\Client;

/**
 * 
 */
class ApiController extends AppController
{
	public $uses = array('User','Leave','Off','Comment');
	public $helpers = array('Html');

	//xoa xin nghi khi chua accept 
	//can kiem tra neu $_POST gui len bi thieu du lieu -> quan trong

	const APPROVED = 1;
	const WAITTING = 2;
	const DENY = 3;

	const ADMIN = 1;
	const MANAGER = 2;
	const USER = 3;

	const TEST_TOKEN = 'b26008724e3f7cfc392bfbd4d9707e5c';
	const TEST_ROOM = '132078386';
	const TEST_ID = '2503016'; // cuong chatwork id

	//Auth
	private function auth()
	{
		// $this->autoRender = false;
		if (empty($_SERVER['HTTP_USER_EMAIL'])) {
			return false;
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$check = $this->User->find('first',array(
			'conditions' => array(
				'email' => $email
			)
		));

		if (empty($check)) {
			return false;
		} else {
			return true;
		}
	}

	// role 1 admin
	// role 2 manager
	// role 3 user
	private function getRole()
	{
		$email = $_SERVER['HTTP_USER_EMAIL'];

		$check = $this->User->find('first',array(
			'conditions' => array(
				'email' => $email
			)
		));

		if (!empty($check)) {
			return $check['User']['role'];
		}

		return 0;
	}

	//response code here 
	//save info when login by chatwork
	public function saveInfo()
	{
		$this->autoRender = false;

		$email = $_POST['email'];
		$name = $_POST['name'];
		$avatar = $_POST['avatar'];
		$access_token = $_POST['access_token'];
		$refresh_token = $_POST['refresh_token'];

		$check = $this->User->find('first',array(
			'conditions' => array(
				'User.email' => $email
			)
		));

		if (empty($check)) {
			$this->response->statusCode(406);
		 	return json_encode(array(
		 		'error' => 'You can not access Yasumi PJ'
		 	));
		} 

		$id = $check['User']['id'];
		$save = array(
			'access_token' => $access_token,
			'refresh_token' => $refresh_token,
			'name' => $name,
			'avatar' => $avatar
		);

		$this->User->id = $id;
		if ($this->User->save($save)) {
			return '1';
		} else {
			return '0';
		}
	}

	// by refresh token
	public function getAccessToken($refreshToken = null)
	{
		$this->autoRender = false;

		$url = 'https://oauth.chatwork.com/token';
		$data = array(
			'grant_type' => 'refresh_token',
			'refresh_token' => $refreshToken,
			'scope' => 'users.all:read rooms.all:read_write'
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

		$access_token = json_decode($response)->access_token;
		return $access_token;
	}

	//generate login url and send back
	public function login()
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

		$this->response->header('Location',$url);
	}

	//tam thoi ko dung
	//for test
	public function callback()
	{
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
		pr($response);

		$access_token = json_decode($response)->access_token;
		$refresh_token = json_decode($response)->refresh_token;

		//get user info
		$url = 'https://api.chatwork.com/v2/me';
		$header = array(
			'Authorization: Bearer '.$access_token
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$response = curl_exec($ch);
		curl_close($ch);

		$user = json_decode($response);
		pr($user);
		$email = $user->login_mail;

		$url = 'yasumi://abc/xyz?';
		$url = $url.'email='.$email;
		// $this->response->header('Location',$url);
	}

	//return home data
	public function home()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			$this->response->statusCode(406);
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$offData = $this->Off->find('all');
		$leaveData = $this->Leave->find('all');

		$data = array();

		foreach ($offData as $key => $value) {
			$data[] = array(
				'id' => $value['Off']['id'],
				'user_id' => $value['Off']['user_id'],
				'duration' => $value['Off']['duration'],
				'dates' => $value['Off']['dates'],
				'create_at' => $value['Off']['create_at'],
				'reason' => $value['Off']['reason'],
				'emotion' => $value['Off']['emotion'],
				'type' => $value['Type']['description'],
				'day_left' => $value['Off']['day_left'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Off']['create_at']),
				'user_name' => $value['User']['name'],
				'info' => 'off',
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		foreach ($leaveData as $key => $value) {
			$data[] = array(
				'id' => $value['Leave']['id'],
				'user_id' => $value['Leave']['user_id'],
				'start' => $value['Leave']['start'],
				'end' => $value['Leave']['end'],
				'date' => $value['Leave']['date'],
				'create_at' => $value['Leave']['create_at'],
				'reason' => $value['Leave']['reason'],
				'emotion' => $value['Leave']['emotion'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Leave']['create_at']),
				'user_name' => $value['User']['name'],
				'info' => 'leave',
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		function build_sorter($key) {
		    return function ($a, $b) use ($key) {
		        return $a[$key] - $b[$key];
		    };
		}

		usort($data, build_sorter('time'));

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//edit user profile
	public function editProfile()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$id = $_POST['id'];
		$birthday = $_POST['birthday'];
		$country = $_POST['country'];
		$address = $_POST['address'];
		$description = $_POST['description'];

		$data = array(
			'birthday' => $birthday,
			'country' => $country,
			'address' => $address,
			'description' => $description
		);

		$this->User->id = $id;
		if ($this->User->save($data)) {
			return json_encode('1');
		} else {
			return json_encode('0');
		}
	}

	//view user profile
	public function viewProfile()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$userData = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'email' => $email
				)
			)
		);

		$userData['User']['role'] = $userData['Role']['role'];

		return json_encode($userData['User'], JSON_PRETTY_PRINT);
	}

	//view Leave Detail
	public function viewLeaveDetail()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$leave_id = $_POST['leave_id'];

		if (isset($_POST['read'])) {
			$this->Leave->id = $leave_id;
			$this->Leave->save(array(
				'notice' => '0'
			));
		}

		$leaveData = $this->Leave->find(
			'first',
			array(
				'conditions' => array(
					'Leave.id' => $leave_id
				)
			)
		);

		$commentData = $this->Comment->find(
			'all',
			array(
				'conditions' => array(
					'Leave.id' => $leave_id
				),
				'fields' => array(
					'Comment.id', 'Comment.comment','User.name','User.avatar'
				)
			)
		);

		$data['Leave'] = $leaveData['Leave'];
		$data['Leave']['status'] = $leaveData['Status']['status'];
		$data['Leave']['user_name'] = $leaveData['User']['name'];

		$data['Comment'] = $commentData;

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//edit leave
	public function editLeaveDetail()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		$id = $_POST['id'];
		$user_id = $data['User']['id'];

		$check = $this->Leave->find('first',array(
			'conditions' => array(
				'Leave.id' => $id
			)
		));

		if ($check['Leave']['user_id'] != $user_id) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		if ($check['Leave']['status'] != self::WAITTING) {
			return json_encode(array(
				'error' => 'Your request has been processed. You can not change request now.'
			));
		}

		$save = array(
			'user_id' => $user_id
		);

		if (isset($_POST['start'])) {
			$save['start'] = $_POST['start'];
		}
		if (isset($_POST['end'])) {
			$save['end'] = $_POST['end'];
		}
		if (isset($_POST['date'])) {
			$save['date'] = $_POST['date'];
		}
		if (isset($_POST['reason'])) {
			$save['reason'] = $_POST['reason'];
		}
		if (isset($_POST['emotion'])) {
			$save['emotion'] = $_POST['emotion'];
		}

		$this->Leave->id = $id;
		if ($this->Leave->save($save)) {
			return json_encode('1');
		} else {
			return json_encode('0');
		}
	}

	//edit off
	public function editOffDetail()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		//off_id
		$id = $_POST['id'];
		$user_id = $data['User']['id'];

		$check = $this->Off->find('first',array(
			'conditions' => array(
				'Off.id' => $id
			)
		));

		if ($check['Off']['user_id'] != $user_id) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		if ($check['Off']['status'] != self::WAITTING) {
			return json_encode(array(
				'error' => 'Your request has been processed. You can not change request now.'
			));
		}

		$save = array(
			'user_id' => $id
		);

		if (isset($_POST['duration'])) {
			$save['duration'] = $_POST['duration'];
		}
		if (isset($_POST['type'])) {
			$save['type'] = $_POST['type'];
		}
		if (isset($_POST['dates'])) {
			$save['dates'] = $_POST['dates'];
		}
		if (isset($_POST['reason'])) {
			$save['reason'] = $_POST['reason'];
		}
		if (isset($_POST['emotion'])) {
			$save['emotion'] = $_POST['emotion'];
		}

		$this->Off->id = $id;
		if ($this->Off->save($save)) {
			return json_encode('1');
		} else {
			return json_encode('0');
		}
	}

	//view Off Detail
	public function viewOffDetail()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$off_id = $_POST['off_id'];

		if (isset($_POST['read'])) {
			$this->Off->id = $off_id;
			$this->Off->save(array(
				'notice' => '0'
			));
		}

		$offData = $this->Off->find(
			'first',
			array(
				'conditions' => array(
					'Off.id' => $off_id
				)
			)
		);

		$commentData = $this->Comment->find(
			'all',
			array(
				'conditions' => array(
					'Off.id' => $off_id
				),
				'fields' => array(
					'Comment.id', 'Comment.comment','User.name','User.avatar'
				)
			)
		);

		$data['Off'] = $offData['Off'];
		$data['Off']['status'] = $offData['Status']['status'];
		$data['Off']['type'] = $offData['Type']['description'];
		$data['Off']['user_name'] = $offData['User']['name'];

		$data['Comment'] = $commentData;

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//admin and own & manager
	public function viewHistory()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$id = $_POST['id'];

		$checkOwn = $this->User->find('first',array(
			'conditions' => array(
				'User.id' => $id,
				'email' => $_SERVER['HTTP_USER_EMAIL']
			)
		));

		//check owned, if not check role
		if (empty($checkOwn)) {
			if ($this->getRole() == self::USER) {
				return json_encode(array(
					'error' => 'You dont have permission'
				));
			}
		}

		$offData = $this->Off->find('all',array(
			'conditions' => array(
				'Off.user_id' => $id
			)
		));
		$leaveData = $this->Leave->find('all',array(
			'conditions' => array(
				'Leave.user_id' => $id
			)
		));

		$data = array();

		foreach ($offData as $key => $value) {
			$data['Off'][] = array(
				'id' => $value['Off']['id'],
				'user_id' => $value['Off']['user_id'],
				'duration' => $value['Off']['duration'],
				'dates' => $value['Off']['dates'],
				'create_at' => $value['Off']['create_at'],
				'reason' => $value['Off']['reason'],
				'emotion' => $value['Off']['emotion'],
				'type' => $value['Type']['description'],
				'day_left' => $value['Off']['day_left'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Off']['create_at']),
				'user_name' => $value['User']['name'],
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		foreach ($leaveData as $key => $value) {
			$data['Leave'][] = array(
				'id' => $value['Leave']['id'],
				'user_id' => $value['Leave']['user_id'],
				'start' => $value['Leave']['start'],
				'end' => $value['Leave']['end'],
				'date' => $value['Leave']['date'],
				'create_at' => $value['Leave']['create_at'],
				'reason' => $value['Leave']['reason'],
				'emotion' => $value['Leave']['emotion'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Leave']['create_at']),
				'user_name' => $value['User']['name'],
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		return json_encode($data, JSON_PRETTY_PRINT);	
	}

	//admin
	public function accept()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() != self::ADMIN) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		//id cua off hoac leave
		$id = $_POST['id'];
		$info = $_POST['info'];
		$status = $_POST['status'];

		if ($info == 'off') {
			$this->Off->id = $id;
			$save = array(
				'status' => $status,
				'notice' => '1',
				'approve_time' => date("Y-m-d H:i:s")
			);
			if ($this->Off->save($save)) {
				return json_encode('1');
			}
		} elseif ($info == 'leave') {
			$this->Leave->id = $id;
			$save = array(
				'status' => $status,
				'notice' => '1',
				'approve_time' => date("Y-m-d H:i:s")
			);
			if ($this->Leave->save($save)) {
				return json_encode('1');
			}
		}

		return json_encode('0');
	}

	public function waitingList()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() != self::ADMIN) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		$offData = $this->Off->find('all',array(
			'conditions' => array(
				'Off.status' => self::WAITTING
			)
		));

		$leaveData = $this->Leave->find('all',array(
			'conditions' => array(
				'Leave.status' => self::WAITTING
			)
		));

		$data = array();

		foreach ($offData as $key => $value) {
			$data[] = array(
				'id' => $value['Off']['id'],
				'user_id' => $value['Off']['user_id'],
				'duration' => $value['Off']['duration'],
				'dates' => $value['Off']['dates'],
				'create_at' => $value['Off']['create_at'],
				'reason' => $value['Off']['reason'],
				'emotion' => $value['Off']['emotion'],
				'type' => $value['Type']['description'],
				'day_left' => $value['Off']['day_left'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Off']['create_at']),
				'user_name' => $value['User']['name'],
				'info' => 'off',
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		foreach ($leaveData as $key => $value) {
			$data[] = array(
				'id' => $value['Leave']['id'],
				'user_id' => $value['Leave']['user_id'],
				'start' => $value['Leave']['start'],
				'end' => $value['Leave']['end'],
				'date' => $value['Leave']['date'],
				'create_at' => $value['Leave']['create_at'],
				'reason' => $value['Leave']['reason'],
				'emotion' => $value['Leave']['emotion'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Leave']['create_at']),
				'user_name' => $value['User']['name'],
				'info' => 'leave',
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar']
				)
			);
		}

		function build_sorter($key) {
		    return function ($a, $b) use ($key) {
		        return $a[$key] - $b[$key];
		    };
		}

		usort($data, build_sorter('time'));

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//admin & manager
	public function searchUser()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() == self::USER) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		$query = $_POST['query'];

		$data = $this->User->find('all',array(
			'conditions' => array(
				'User.name LIKE' => '%'.$query.'%'
			)
		));

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	public function getMember()
	{
		$this->autoRender = false;
		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() == self::USER) {
			return json_encode(array(
				'error' => 'You dont have permission'
			));
		}

		$data = $this->User->find('all',array(
			'conditions' => array(
				'User.email !=' => $_SERVER['HTTP_USER_EMAIL']
			)
		));

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//add Leave
	public function addLeave()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		$id = $data['User']['id'];
		$end = $_POST['end'];
		$start = $_POST['start'];
		$date  = $_POST['date'];
		$create_at = date("Y-m-d H:i:s"); 
		$reason = $_POST['reason'];
		$emotion = $_POST['emotion'];
		$status = self::WAITTING;

		$save = array(
			'user_id' => $id,
			'start' => $start,
			'end' => $end,
			'date' => $date,
			'create_at' => $create_at,
			'reason' => $reason,
			'emotion' => $emotion,
			'status' => $status,
			'notice' => '0'
		);

		$this->Leave->create();
		if ($this->Leave->save($save)) {
			return json_encode('1');
		} else {
			return json_encode('0');
		}
	}

	//add Day off
	public function addOff()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];

		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		$id = $data['User']['id'];
		$duration = $_POST['duration'];
		$type = $_POST['type'];
		$dates  = $_POST['dates'];
		$create_at = date("Y-m-d H:i:s"); 
		$reason = $_POST['reason'];
		$emotion = $_POST['emotion'];
		$day_left = $data['User']['day_off_left'] - $duration;
		$status = self::WAITTING;

		$user = array(
			'day_off_left' => $day_left
		);

		$this->User->id = $id;
		$this->User->save($user);

		$save = array(
			'user_id' => $id,
			'type' => $type,
			'duration' => $duration,
			'dates' => $dates,
			'create_at' => $create_at,
			'reason' => $reason,
			'emotion' => $emotion,
			'day_left' => $day_left,
			'status' => $status,
			'notice' => '0'
		);

		$this->Off->create();
		if ($this->Off->save($save)) {
			return json_encode('1');
		} else {
			return json_encode('0');
		}
	}

	// chuyen sang private
	private function sendChatWork($data)
	{
		//room_id, accesstoken, 
		$room_id = ROOM_ID;
		$access_token = $data['access_token'];
		$content = $data['content'];

		$chatwork_name = 'Test';//$data['name'];
		$chatwork_id = '2503016';
		

		$this->autoRender = false;
		$url = 'https://api.chatwork.com/v2/rooms/'.$room_id.'/messages';

		$header = array(
			'Authorization: Bearer '.$access_token
		);

		//to someone
		$message = '[To:'.$chatwork_id.'] '.$chatwork_name.PHP_EOL.$content;

		//reply someone
		//$message = '[Reply aid='.$chatwork_id.'] '.$chatwork_name;

		//quote some one
		//todo

		$data = array(
			'body' => $message
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
	}

	public function addComment()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];
		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		$id = $data['User']['id'];
		$comment = $_POST['comment'];
		$off_id = $_POST['off_id'];
		$leave_id = $_POST['leave_id'];

		$save = array(
			'user_id' => $id,
			'comment' => $comment,
			'off_id' => $off_id,
			'leave_id' => $leave_id
		);

		$this->Comment->create();
		if ($this->Comment->save($save)) {
			return json_encode(1);
		} else {
			return json_encode(0);
		}
	}

	public function deleteComment()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		$email = $_SERVER['HTTP_USER_EMAIL'];
		$id = $_POST['id'];

		$data = $this->User->find(
			'first',
			array(
				'conditions' => array(
					'User.email' => $email
				)
		));

		$check = $this->Comment->find('first',array(
			'conditions' => array(
				'Comment.id' => $id,
				'Comment.user_id' => $data['User']['id']
			)
		));

		if (empty($check)) {
			return json_encode(array(
				'error' => 'You can not delete other people\'s comment'
			));
		}

		if ($this->Comment->delete($id)) {
			return json_encode("1");
		} else {
			return json_encode("0");
		}
	}

	public function notification()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() == self::ADMIN) {

			$offData = $this->Off->find('all',array(
				'conditions' => array(
					'Off.status' => self::WAITTING
				)
			));

			$leaveData = $this->Leave->find('all',array(
				'conditions' => array(
					'Leave.status' => self::WAITTING
				)
			));

			$data = array();

			foreach ($offData as $key => $value) {
				$data[] = array(
					'id' => $value['Off']['id'],
					'user_id' => $value['Off']['user_id'],
					'duration' => $value['Off']['duration'],
					'dates' => $value['Off']['dates'],
					'create_at' => $value['Off']['create_at'],
					'approve_time' => $value['Off']['approve_time'],
					'reason' => $value['Off']['reason'],
					'emotion' => $value['Off']['emotion'],
					'type' => $value['Type']['description'],
					'day_left' => $value['Off']['day_left'],
					'status' => $value['Status']['status'],
					'time' => strtotime($value['Off']['create_at']),
					'user_name' => $value['User']['name'],
					'info' => 'off',
					'author' => array(
						'name' =>  $value['User']['name'],
						'avatar' => $value['User']['avatar']
					)
				);
			}

			foreach ($leaveData as $key => $value) {
				$data[] = array(
					'id' => $value['Leave']['id'],
					'user_id' => $value['Leave']['user_id'],
					'start' => $value['Leave']['start'],
					'end' => $value['Leave']['end'],
					'date' => $value['Leave']['date'],
					'create_at' => $value['Leave']['create_at'],
					'approve_time' => $value['Leave']['approve_time'],
					'reason' => $value['Leave']['reason'],
					'emotion' => $value['Leave']['emotion'],
					'status' => $value['Status']['status'],
					'time' => strtotime($value['Leave']['create_at']),
					'user_name' => $value['User']['name'],
					'info' => 'leave',
					'author' => array(
						'name' =>  $value['User']['name'],
						'avatar' => $value['User']['avatar']
					)
				);
			}

			function build_sorter($key) {
			    return function ($a, $b) use ($key) {
			        return $a[$key] - $b[$key];
			    };
			}

			usort($data, build_sorter('time'));

			return json_encode($data, JSON_PRETTY_PRINT);
		} else {
			$offData = $this->Off->find('all',array(
				'conditions' => array(
					'Off.notice' => '1',
					'User.email' => $_SERVER['HTTP_USER_EMAIL']
				)
			));

			$leaveData = $this->Leave->find('all',array(
				'conditions' => array(
					'Leave.notice' => '1',
					'User.email' => $_SERVER['HTTP_USER_EMAIL']
				)
			));

			$data = array();

			foreach ($offData as $key => $value) {
				$data[] = array(
					'id' => $value['Off']['id'],
					'user_id' => $value['Off']['user_id'],
					'duration' => $value['Off']['duration'],
					'dates' => $value['Off']['dates'],
					'create_at' => $value['Off']['create_at'],
					'reason' => $value['Off']['reason'],
					'emotion' => $value['Off']['emotion'],
					'type' => $value['Type']['description'],
					'day_left' => $value['Off']['day_left'],
					'status' => $value['Status']['status'],
					'notice' => $value['Off']['notice'],
					'time' => strtotime($value['Off']['create_at']),
					'user_name' => $value['User']['name'],
					'info' => 'off',
					'author' => array(
						'name' =>  $value['User']['name'],
						'avatar' => $value['User']['avatar']
					)
				);
			}

			foreach ($leaveData as $key => $value) {
				$data[] = array(
					'id' => $value['Leave']['id'],
					'user_id' => $value['Leave']['user_id'],
					'start' => $value['Leave']['start'],
					'end' => $value['Leave']['end'],
					'date' => $value['Leave']['date'],
					'create_at' => $value['Leave']['create_at'],
					'reason' => $value['Leave']['reason'],
					'emotion' => $value['Leave']['emotion'],
					'status' => $value['Status']['status'],
					'notice' => $value['Leave']['notice'],
					'time' => strtotime($value['Leave']['create_at']),
					'user_name' => $value['User']['name'],
					'info' => 'leave',
					'author' => array(
						'name' =>  $value['User']['name'],
						'avatar' => $value['User']['avatar']
					)
				);
			}

			function build_sorter($key) {
			    return function ($a, $b) use ($key) {
			        return $a[$key] - $b[$key];
			    };
			}

			usort($data, build_sorter('time'));

			return json_encode($data, JSON_PRETTY_PRINT);
		}
	}

	public function test()
	{
		$this->autoRender = false;

		$email = 'rainbow.bkhn@gmail.com';
		$data = array('email' => $email);
		$check_url = 'http://118.70.151.39:3000/chatwork/api/checkexist/';//.$email;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,TRUE);
		curl_setopt($ch, CURLOPT_URL, $check_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);
		pr($response);

		// if (empty($data)) {
		//         echo "0";
		// } else {
		//         echo "1";
		// }

		// $access_token = '1234';
		// $refresh_token = 'r1234';
		// $name = 'HuanCao';
		// $avatar = '123';
		// $email = 'huancaopro93@gmail.com';

		// $save_url = 'http://192.168.0.22/chatwork/api/saveinfo/'.$access_token.'/'.$refresh_token.'/'.
		// $name.'/'.$avatar.'/'.$email;
		// $save = file_get_contents($save_url);

		// echo $save;

		//test account
		// $id = '30';
		// $user = $this->User->find('first',array(
		// 	'conditions' => array(
		// 		'User.id' => $id
		// 	)
		// ));

		// $data = array(
		// 	'access_token' => $user['User']['access_token'],
		// 	'content' => 'Test chatwork'
		// );

		// $res = $this->sendChatWork($data);

		// if (isset($res->errors)) {
		// 	$access_token = $this->getAccessToken($user['User']['refresh_token']);
		// 	$data['access_token'] = $access_token;
		// 	$res = $this->sendChatWork($data);
		// 	pr($res);
		// }
	}
}
?>