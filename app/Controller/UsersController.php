 <?php 
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use ChatWork\OAuth2\Client\ChatWorkProvider;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use GuzzleHttp\Client;
/**
 * 
 */
class UsersController extends AppController
{	
	public $uses = array('User','Leave','Off','Comment');
	public $helpers = array('Html');

	//xoa xin nghi khi chua accept
	//can kiem tra neu $_POST gui len bi thieu du lieu -> quan trong

	const APPROVED = 1;
	const WAITING = 2;
	const DENY = 3;

	const ADMIN = 1;
	const MANAGER = 2;
	const USER = 3;

	const TEST_TOKEN = 'b26008724e3f7cfc392bfbd4d9707e5c';
	const TEST_ROOM = '132078386';
	const TEST_ID = '2503016'; // cuong chatwork id

	public function beforeFilter() {
	    if (!session_id()) {
            session_start();
        }

        if ($this->action != 'login' && $this->action != 'callback')
        {
            if (empty($_SESSION['email'])) {
            	$this->redirect('/users/login');
            }

            $data = $this->User->find('first',array(
		    	'conditions' => array(
		    		'User.email' => $_SESSION['email']
		    	)
		    ));
            if($data['User']['role'] == 1){
                $countOff = $this->Off->find('count',array(
                    'conditions' => array(
                        'Off.status' => self::WAITING
                    )
                ));
                $countLeave = $this->Leave->find('count',array(
                    'conditions' => array(
                        'Leave.status' => self::WAITING
                    )
                ));
            }else{
                $countOff = $this->Off->find('count',array(
                    'conditions' => array(
                        'Off.user_id' => $data['User']['id'],
                        'Off.notice' => 1
                    )
                ));
                $countLeave = $this->Leave->find('count',array(
                    'conditions' => array(
                        'Leave.user_id' => $data['User']['id'],
                        'Leave.notice' => 1
                    )
                ));
            }

            $data['User']['notice'] = $countOff + $countLeave;
		    $this->set('user_data',$data['User']);
        }	    
	}

    private function getRole() {
        $email = $_SESSION['email'];

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
		$provider = new ChatWorkProvider(
		    OAUTH2_CLIENT_ID,
		    OAUTH2_CLIENT_SECRET,
		    OAUTH2_REDIRECT_URI2
		);

		$url = $provider->getAuthorizationUrl([
		    'scope' => ['users.all:read', 'rooms.all:read_write']
		]);

		$this->set('login_url',$url);
         // $_SESSION['email'] = "thaovtp@tmh-techlab.vn";
	}

	public function logout()
	{
		session_destroy();
		$this->redirect('login');
	}

	public function callback()
	{
		$this->autoRender = false;

		$url = 'https://oauth.chatwork.com/token';
		$data = array(
			'grant_type' => 'authorization_code',
			'code' => $_GET['code'],
			'redirect_uri' => OAUTH2_REDIRECT_URI2
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

		$email = $user->login_mail;
		$avatar = $user->avatar_image_url;
		$name = $user->name;
		$chatwork_id = $user->account_id;

		$user_data = $this->User->find('first',array(
			'conditions' => array(
				'email' => $email
			)
		));

		$id = $user_data['User']['id'];

		$save = array(
			'name' => $name,
			'avatar' => $avatar,
			'chatwork_id' => $chatwork_id,
			'access_token' => $access_token,
			'refresh_token' => $refresh_token
		);

		$this->User->id = $id;
		$this->User->save($save);

		$_SESSION['email'] = $email;
		// $_SESSION['avatar'] = $avatar;

		$this->redirect(array(
			'controller' => 'users',
			'action' => 'home'
		));
	}

	// by refresh token
	private function getAccessToken($refreshToken = null)
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

	public function home()
	{
		$offData = $this->Off->find('all');
		$leaveData = $this->Leave->find('all');		

		$data = array();

		foreach ($offData as $key => $value) {

			$start = $value['Off']['create_at'];
			$end = date('Y-m-d H:i:s');

			$dtStart = new DateTime($start);
			$dtEnd = new DateTime($end);

			$diff = $dtStart->diff($dtEnd);

			if (($diff->format("%y") >= 1) || ($diff->format("%m") >= 1) || ($diff->format("%d") >= 1)) {
				$post_at = date('Y-m-d',strtotime($value['Off']['create_at']));
			} elseif ($diff->format("%h") >= 1) {
				$post_at = $diff->format("%h") . ' hour ago';
			} elseif ($diff->format("%i") >= 1) {
				$post_at = $diff->format("%i") . ' min ago';
			} else {
				$post_at = 'just now';
			}

			$data[] = array(
				'id' => $value['Off']['id'],
				'user_id' => $value['Off']['user_id'],
				'duration' => $value['Off']['duration'],
				'dates' => $value['Off']['dates'],
				'create_at' => $value['Off']['create_at'],
				'post_at' => $post_at,
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
					'avatar' => $value['User']['avatar'],
                    'email' => $value['User']['email']
				)
			);
		}

		foreach ($leaveData as $key => $value) {
			$start = $value['Leave']['create_at'];
			$end = date('Y-m-d H:i:s');

			$dtStart = new DateTime($start);
			$dtEnd = new DateTime($end);

			$diff = $dtStart->diff($dtEnd);

			if (($diff->format("%y") >= 1) || ($diff->format("%m") >= 1) || ($diff->format("%d") >= 1)) {
				$post_at = date('Y-m-d',strtotime($value['Leave']['create_at']));
			} elseif ($diff->format("%h") >= 1) {
				$post_at = $diff->format("%h") . ' hours ago';
			} elseif ($diff->format("%i") >= 1) {
				$post_at = $diff->format("%i") . ' mins ago';
			} else {
				$post_at = 'just now';
			}

			$check = 'leave';

			if (strtotime($value['Leave']['end']) == strtotime('17:30:00')) {
				$check = 'leaving soon';
			}

			if (strtotime($value['Leave']['start']) == strtotime('08:30:00')) {
				$check = 'coming late';
			}

			$data[] = array(
				'id' => $value['Leave']['id'],
				'user_id' => $value['Leave']['user_id'],
				'start' => $value['Leave']['start'],
				'end' => $value['Leave']['end'],
				'date' => $value['Leave']['date'],
				'create_at' => $value['Leave']['create_at'],
				'post_at' => $post_at,
				'reason' => $value['Leave']['reason'],
				'emotion' => $value['Leave']['emotion'],
				'status' => $value['Status']['status'],
				'time' => strtotime($value['Leave']['create_at']),
				'user_name' => $value['User']['name'],
				'info' => 'leave',
				'check' => $check,
				'author' => array(
					'name' =>  $value['User']['name'],
					'avatar' => $value['User']['avatar'],
                    'email' => $value['User']['email']
				)
			);
		}

		function build_sorter($key) {
		    return function ($a, $b) use ($key) {
		        return $b[$key] - $a[$key];
		    };
		}

		usort($data, build_sorter('time'));

		$this->set('data',$data);
	}

	public function test()
	{
		$this->autoRender = false;
		$leaveData = $this->Leave->find('all');

		foreach ($leaveData as $key => $value) {
			$start = $value['Leave']['create_at'];
			$end = date('Y-m-d H:i:s');

			$dtStart = new DateTime($start);
			$dtEnd = new DateTime($end);

			$diff = $dtStart->diff($dtEnd);

			if (($diff->format("%y") >= 1) || ($diff->format("%m") >= 1) || ($diff->format("%d") >= 1)) {
				$post_at = date('Y-m-d',strtotime($value['Leave']['create_at']));

				pr($post_at);
			}

			// if (($diff->format("%y") >= 1) || ($diff->format("%m") >= 1) || ($diff->format("%d") >= 1)) {
			// 	pr(($diff->format("%d").' days ago'));
			// }
		}
	}

    public function profile($idUser = null)
    {
        //data profile
        if(isset($idUser) && !empty($idUser)){
            $userData = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $idUser,
                    )
                )
            );
        }else{
            $userData = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'email' => $_SESSION['email'],
                    )
                )
            );
        }
        $userData['listUser'] = $this->User->find(
            'all',
            array(
                'conditions' => array(
                    'User.role !=' => '1',
                )
            )
        );
        //data history off
        $offData = $this->Off->find('all',array(
            'conditions' => array(
                'Off.user_id' => $userData['User']['id']
            )
        ));

        foreach ($offData as $key => $value) {
            $post_at = $this->timePost($value['Off']['create_at']);

            $userData['Off'][] = array(
                'id' => $value['Off']['id'],
                'user_id' => $value['Off']['user_id'],
                'duration' => $value['Off']['duration'],
                'dates' => $value['Off']['dates'],
                'create_at' => $value['Off']['create_at'],
                'post_at' => $post_at,
                'reason' => $value['Off']['reason'],
                'emotion' => $value['Off']['emotion'],
                'type_id' => $value['Off']['type'],
                'type' => $value['Type']['description'],
                'day_left' => $value['Off']['day_left'],
                'status' => $value['Status']['status'],
                'info'  => "off",
                'time' => strtotime($value['Off']['create_at']),
                'user_name' => $value['User']['name'],
                'author' => array(
                    'name' =>  $value['User']['name'],
                    'avatar' => $value['User']['avatar'],
                    'email' => $value['User']['email']
                )
            );
        }
        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return $b[$key] - $a[$key];
            };
        }
        if(!empty($userData['Off'])){
            usort($userData['Off'], build_sorter('time'));
        }
        //data history leave
        $leaveData = $this->Leave->find('all',array(
            'conditions' => array(
                'Leave.user_id' => $userData['User']['id']
            )
        ));
        foreach ($leaveData as $key => $value) {
            $post_at = $this->timePost($value['Leave']['create_at']);

            $check = 'leave';

            if (strtotime($value['Leave']['end']) == strtotime('17:30:00')) {
                $check = 'leaving soon';
            }

            if (strtotime($value['Leave']['start']) == strtotime('08:30:00')) {
                $check = 'coming late';
            }

            $userData['Leave'][] = array(
                'id' => $value['Leave']['id'],
                'user_id' => $value['Leave']['user_id'],
                'start' => $value['Leave']['start'],
                'end' => $value['Leave']['end'],
                'date' => $value['Leave']['date'],
                'create_at' => $value['Leave']['create_at'],
                'post_at' => $post_at,
                'reason' => $value['Leave']['reason'],
                'emotion' => $value['Leave']['emotion'],
                'status' => $value['Status']['status'],
                'time' => strtotime($value['Leave']['create_at']),
                'user_name' => $value['User']['name'],
                'info' => 'leave',
                'check' => $check,
                'author' => array(
                    'name' =>  $value['User']['name'],
                    'avatar' => $value['User']['avatar'],
                    'email' => $value['User']['email']
                )
            );
        }
        function buildSorter($key) {
            return function ($a, $b) use ($key) {
                return $b[$key] - $a[$key];
            };
        }
        if(!empty($userData['Leave'])){
            usort($userData['Leave'], buildSorter('time'));
        }
        $this->set('userData', $userData);

        if($this->request->is("post")){
            if($this->User->save($this->request->data)){
                $this->response->header('Location',"/chatwork/users/profile");
                $this->Flash->set('save success');
            }
        }
    }

    public function profileAdmin(){
        //data profile
        $userData = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'email' => $_SESSION['email'],
                )
            )
        );
        $userData['listUser'] = $this->User->find(
            'all',
            array(
                'conditions' => array(
                    'User.role !=' => '1',
                )
            )
        );
        $this->set('userData', $userData);

        if($this->request->is("post")){
            if($this->User->save($this->request->data)){
                $this->response->header('Location',"/chatwork/users/profile");
                $this->Flash->set('save success');
            }
        }
    }

    public function notice(){
        if ($this->getRole() == self::ADMIN) {

            $offData = $this->Off->find('all',array(
                'conditions' => array(
                    'Off.status' => self::WAITING
                )
            ));

            $leaveData = $this->Leave->find('all',array(
                'conditions' => array(
                    'Leave.status' => self::WAITING
                )
            ));

            $noticeData = array();

            foreach ($offData as $key => $value) {
                $post_at = $this->timePost($value['Off']['create_at']);
                $noticeData[] = array(
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
                    'post_at' => $post_at,
                    'time' => strtotime($value['Off']['create_at']),
                    'user_name' => $value['User']['name'],
                    'info' => 'off',
                    'approve_time' => $value['Off']['approve_time'],
                    'role' => 1,
                    'author' => array(
                        'name' =>  $value['User']['name'],
                        'email' =>  $value['User']['email'],
                        'avatar' => $value['User']['avatar'],
                    )
                );
            }

            foreach ($leaveData as $key => $value) {
                $check = 'leave';

                if (strtotime($value['Leave']['end']) == strtotime('17:30:00')) {
                    $check = 'leaving soon';
                }

                if (strtotime($value['Leave']['start']) == strtotime('08:30:00')) {
                    $check = 'coming late';
                }
                $post_at = $this->timePost($value['Leave']['create_at']);
                $noticeData[] = array(
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
                    'post_at' => $post_at,
                    'time' => strtotime($value['Leave']['create_at']),
                    'user_name' => $value['User']['name'],
                    'info' => 'leave',
                    'approve_time' => $value['Leave']['approve_time'],
                    'check' => $check,
                    'role' => 1,
                    'author' => array(
                        'name' =>  $value['User']['name'],
                        'email' =>  $value['User']['email'],
                        'avatar' => $value['User']['avatar']
                    )
                );
            }

            function build_sorter($key) {
                return function ($a, $b) use ($key) {
                    return $b[$key] - $a[$key];
                };
            }

            usort($noticeData, build_sorter('time'));
            $this->set('noticeData', $noticeData);
        } else {
            $offData = $this->Off->find('all',array(
                'conditions' => array(
                    'Off.notice' => '1',
                    'User.email' => $_SESSION['email']
                )
            ));

            $leaveData = $this->Leave->find('all',array(
                'conditions' => array(
                    'Leave.notice' => '1',
                    'User.email' => $_SESSION['email']
                )
            ));
            $this->set('notice','0');

            foreach ($offData as $key => $value) {
            	$this->Off->id = $value['Off']['id'];
            	$this->Off->save(array(
            		'notice' => '0'
            	));
            }

            foreach ($leaveData as $key => $value) {
            	$this->Leave->id = $value['Leave']['id'];
            	$this->Leave->save(array(
            		'notice' => '0'
            	));
            }

            $noticeData = array();

            foreach ($offData as $key => $value) {
                $post_at = $this->timePost($value['Off']['create_at']);
                $noticeData[] = array(
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
                    'post_at' => $post_at,
                    'notice' => $value['Off']['notice'],
                    'time' => strtotime($value['Off']['create_at']),
                    'user_name' => $value['User']['name'],
                    'info' => 'off',
                    'approve_time' => $value['Off']['approve_time'],
                    'author' => array(
                        'name' =>  $value['User']['name'],
                        'avatar' => $value['User']['avatar'],
                        'email' =>  $value['User']['email']
                    )
                );
            }

            foreach ($leaveData as $key => $value) {
                $check = 'leave';

                if (strtotime($value['Leave']['end']) == strtotime('17:30:00')) {
                    $check = 'leaving soon';
                }

                if (strtotime($value['Leave']['start']) == strtotime('08:30:00')) {
                    $check = 'coming late';
                }
                $post_at = $this->timePost($value['Leave']['create_at']);
                $noticeData[] = array(
                    'id' => $value['Leave']['id'],
                    'user_id' => $value['Leave']['user_id'],
                    'start' => $value['Leave']['start'],
                    'end' => $value['Leave']['end'],
                    'date' => $value['Leave']['date'],
                    'create_at' => $value['Leave']['create_at'],
                    'reason' => $value['Leave']['reason'],
                    'emotion' => $value['Leave']['emotion'],
                    'status' => $value['Status']['status'],
                    'post_at' => $post_at,
                    'notice' => $value['Leave']['notice'],
                    'time' => strtotime($value['Leave']['create_at']),
                    'user_name' => $value['User']['name'],
                    'info' => 'leave',
                    'approve_time' => $value['Leave']['approve_time'],
                    'check' => $check,
                    'author' => array(
                        'name' =>  $value['User']['name'],
                        'avatar' => $value['User']['avatar'],
                        'email' =>  $value['User']['email'],
                    )
                );
            }

            function build_sorter($key) {
                return function ($a, $b) use ($key) {
                    return $b[$key] - $a[$key];
                };
            }

            usort($noticeData, build_sorter('time'));
            return  $this->set('noticeData', $noticeData);
        }
    }

    public function search(){
        $this->log("thao");
        if(isset($_POST)){
            $query = $_POST['search'];

            $data = $this->User->find('all',array(
                'conditions' => array(
                    'User.name LIKE' => '%'.$query.'%'
                )
            ));

            $this->set('searchData', $data);
        }
    }

    public function timePost($time)
    {
        $start = $time;
        $end = date('Y-m-d H:i:s');

        $dtStart = new DateTime($start);
        $dtEnd = new DateTime($end);

        $diff = $dtStart->diff($dtEnd);

        if (($diff->format("%y") >= 1) || ($diff->format("%m") >= 1) || ($diff->format("%d") >= 1)) {
            $post_at = date('Y-m-d',strtotime($time));
        } elseif ($diff->format("%h") >= 1) {
            $post_at = $diff->format("%h") . ' hour ago';
        } elseif ($diff->format("%i") >= 1) {
            $post_at = $diff->format("%i") . ' min ago';
        } else {
            $post_at = 'just now';
        }
        return $post_at;
    }

    public function viewHistory()
    {
	    $this->autoRender = false;
        $userID = $this->request->data['userID'];
        $data = array();
        if(isset($this->request->data['offID'])){
            $offData = $this->Off->find('all',array(
                'conditions' => array(
                    'Off.user_id' => $userID
                )
            ));
            foreach ($offData as $key => $value) {
                $post_at = $this->timePost($value['Off']['create_at']);
                $data['Off'][] = array(
                    'id' => $value['Off']['id'],
                    'user_id' => $value['Off']['user_id'],
                    'duration' => $value['Off']['duration'],
                    'dates' => $value['Off']['dates'],
                    'create_at' => $value['Off']['create_at'],
                    'post_at' => $post_at,
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
        }elseif(isset($this->request->data['leaveID'])){
            $leaveData = $this->Leave->find('all',array(
                'conditions' => array(
                    'Leave.user_id' => $userID
                )
            ));
            foreach ($leaveData as $key => $value) {
                $post_at = $this->timePost($value['Leave']['create_at']);
                $data['Leave'][] = array(
                    'id' => $value['Leave']['id'],
                    'user_id' => $value['Leave']['user_id'],
                    'start' => $value['Leave']['start'],
                    'end' => $value['Leave']['end'],
                    'date' => $value['Leave']['date'],
                    'create_at' => $value['Leave']['create_at'],
                    'post_at' => $post_at,
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
        }
    }
}
?>