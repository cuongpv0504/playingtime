<?php  
/**
 * 
 */
class ApiController extends AppController
{
	public $uses = array('User','Leave','Off');

	//return home data
	public function home()
	{
		$this->autoRender = false;
		$offData = $this->Off->find('all');
		$leaveData = $this->Leave->find('all');

		// pr($offData);
		// pr($leaveData);

		$data = array(
			'off' => $offData,
			'leave' => $leaveData
		);

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
		$userData = $this->User->find('all');

		return json_encode($userData, JSON_PRETTY_PRINT);
	}

	//view Leave Detail
	public function viewLeaveDetail()
	{
		
	}

	//view Off Detail
	public function viewOffDetail()
	{
		
	}

	//add Leave
	public function addLeave()
	{
		
	}

	//add Day off
	public function addOff()
	{
		
	}

	//
}
?>