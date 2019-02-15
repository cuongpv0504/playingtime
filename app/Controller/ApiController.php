<?php  
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));
use wataridori\ChatworkSDK\ChatworkApi;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

/**
 * 
 */
class ApiController extends AppController
{
	public $uses = array('User','Leave','Off','Comment');

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

	public function login()
	{
		//to do
	}

	//return home data
	public function home()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
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

	//admin and own
	//nen chia thanh 2 phan off va leave
	//TODO
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
			if ($this->getRole() == 3) {
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

	//admin
	public function accept()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() != 1) {
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
			$save = array('status' => $status);
			if ($this->Off->save($save)) {
				return json_encode('1');
			}
		} elseif ($info == 'leave') {
			$this->Leave->id = $id;
			$save = array('status' => $status);
			if ($this->Leave->save($save)) {
				return json_encode('1');
			}
		}

		return json_encode('0');
	}

	//admin
	public function searchUser()
	{
		$this->autoRender = false;

		if (!$this->auth()) {
			return json_encode(array(
				'error' => 'Can not authenicate'
			));
		}

		if ($this->getRole() == 3) {
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

	//add Leave
	//date format chuan sql
	//chua test postman
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
			'status' => $status
		);

		$this->Leave->create();
		if ($this->Leave->save($save)) {
			return json_encode(1);
		} else {
			return json_encode(0);
		}
	}

	//add Day off
	// cung chua test
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

		$save = array(
			'user_id' => $id,
			'type' => $type,
			'duration' => $duration,
			'dates' => $dates,
			'create_at' => $create_at,
			'reason' => $reason,
			'emotion' => $emotion,
			'day_left' => $day_left,
			'status' => $status
		);

		$this->Off->create();
		if ($this->Off->save($save)) {
			return json_encode(1);
		} else {
			return json_encode(0);
		}
	}

	public function sendChatWork()
	{
		$this->autoRender = false;

		ChatworkSDK::setApiKey(self::TEST_TOKEN);
		$api = new ChatworkApi();
		$room = new ChatworkRoom(self::TEST_ROOM);

		//send message

		//$room->sendMessage("Test, Hello");

		$members = $room->getMembers();
		foreach ($members as $member) {
		    if ($member->account_id == self::TEST_ID) {
		    	$room->sendMessageToList(array($member), 'Test gui cho Cuong');
		    }
		}
	}

	public function addComment()
	{
		# code...
	}

	public function test()
	{
		$this->autoRender = false;
		$leave_id = 1;

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
				)
			)
		);

		pr($leaveData);
		pr($commentData);

		// echo json_encode($data, JSON_PRETTY_PRINT);
	}
}
?>