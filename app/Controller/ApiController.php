<?php  
/**
 * 
 */
class ApiController extends AppController
{
	public $uses = array('User','Leave','Off','Comment');

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
				'info' => 'off'
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
				'info' => 'leave'
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
				)
			)
		);

		$data['Off'] = $offData['Off'];
		$data['Off']['status'] = $offData['Status']['status'];
		$data['Off']['type'] = $offData['Type']['description'];
		$data['Off']['user_name'] = $offData['User']['name'];

		$data['Comment'] = $commentData;

		// foreach ($commentData as $key => $value) {
		// 	$data['Comment'][] = array(
		// 		'id' => $value['']
		// 	);
		// }

		return json_encode($data, JSON_PRETTY_PRINT);
	}

	//add Leave
	public function addLeave()
	{
		
	}

	//add Day off
	public function addOff()
	{
		
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