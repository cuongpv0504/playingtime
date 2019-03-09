<?php
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use ChatWork\OAuth2\Client\ChatWorkProvider;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use GuzzleHttp\Client;
/**
 *
 */
class RequestController extends AppController
{
    public $uses = array('User','Leave','Off','Comment','Type');
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

    public function index(){
        $typeData = $this->Type->find('all');
        $this->set('typeData', $typeData);
        if(!empty($_POST)){
            $email = $_SESSION['email'];
            $user_data = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.email' => $email
                    )
                ));
            if(!empty($_POST['check']) && $_POST['check'] == 'Off'){
                $this->addOff($user_data, $_POST);
            }else{
                $this->addLeave($user_data, $_POST);
            }
        }
    }

    public function addOff($user_data,$off_data){
        $countDay = 0;
        foreach ($off_data['in'] as $in){
            if($in == 'ALL'){
                $countDay++;
            }else{
                $countDay = $countDay + 0.5;
            }
        }

        foreach ($off_data['date'] as $key=>$date){
            $date = date('Y/m/d',strtotime($date));
            $dates[] = $date . '-'.$off_data['in'][$key];

        }
        if($off_data['reason'] == 'Other'){
            $reason = $off_data['reasonOther'];
        }else{
            $reason = $off_data['reason'];
        }
        $dates = implode(",", $dates);
        $id = $user_data['User']['id'];
        $duration = $countDay;
        $type = $off_data['type'];
        $dates  = $dates;
        $create_at = date("Y-m-d H:i:s");
        $reason = $reason;
        $emotion = $off_data['emotion'];

        $status = self::WAITING;
        if ($type == 0) {
            $day_left = $user_data['User']['day_off_left'] - $duration;
        } else {
            $day_left = $user_data['User']['day_off_left'];
        }

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

            //send chatwork
            $data = array(
                'access_token' => $user_data['User']['access_token'],
                'content' => 'Because ' . $reason . ', I want to take ' . $duration . ' day off on ' . $dates
                    . '. I hope you aprrove, Thank you (bow)',
                'method' => '2'
            );

            $data['users'][] = array(
                'chatwork_id' => JO_ID,
                'chatwork_name' => JO_NAME
            );

            $data['users'][] = array(
                'chatwork_id' => USUI_ID,
                'chatwork_name' => USUI_NAME
            );

            $res = $this->sendChatWork($data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($user_data['User']['refresh_token']);
                $data['access_token'] = $access_token;
                $res = $this->sendChatWork($data);
            }

            $this->response->header('Location',"/chatwork/users/home");
        } else {
            $this->response->header('Location',"/chatwork/request");
        }
    }

    public function addLeave($user_data,$leave_data){
        if($leave_data['reason'] == 'Other'){
            $reason = $leave_data['reasonOther'];
        }else{
            $reason = $leave_data['reason'];
        }
        $id = $user_data['User']['id'];
        $end = $leave_data['end'];
        $start = $leave_data['start'];
        $date  = $leave_data['date'];
        $create_at = date("Y-m-d H:i:s");
        $reason = $reason;
        $emotion = $leave_data['emotion'];
        $status = self::WAITING;

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

            //send chatwork
            $data = array(
                'access_token' => $user_data['User']['access_token'],
                'method' => '2'
            );

            $data['content'] = 'Because ' . $reason . ', I want to leave the office from ' . date("H:i", strtotime($start))
                . ' to ' . date("H:i", strtotime($end)) . '. I hope you aprrove. (bow)';

            if (strtotime($end) == strtotime('17:30:00')) {
                $data['content'] = 'Because ' . $reason . ', I want to leaving soon from ' . date("H:i", strtotime($start))
                    . '. I hope you aprrove. (bow)';
            }

            if (strtotime($start) == strtotime('08:30:00')) {
                $data['content'] = 'Because ' . $reason . ', I want to coming late at ' . date("H:i", strtotime($end))
                    . '. I hope you aprrove. (bow)';
            }

            $data['users'][] = array(
                'chatwork_id' => JO_ID,
                'chatwork_name' => JO_NAME
            );

            $data['users'][] = array(
                'chatwork_id' => USUI_ID,
                'chatwork_name' => USUI_NAME
            );

            $res = $this->sendChatWork($data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($user_data['User']['refresh_token']);
                $data['access_token'] = $access_token;
                $res = $this->sendChatWork($data);
            }

            $this->response->header('Location',"/chatwork/users/home");
        } else {
            $this->response->header('Location',"/chatwork/request");
        }
    }

    public function delete(){
        $this->autoRender = false;
        $info = $_POST['infoPost'];
        $id = $_POST['idPost'];
        $email = $_SESSION['email'];
//        $email = "thaovtp@tmh-techlab.vn";

        //get user_data
        $data = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'User.email' => $email
                )
        ));

        $user_id = $data['User']['id'];

        if ($info == 'off') {
            //get Off_data
            $check = $this->Off->find('first',array(
                'conditions' => array(
                    'Off.id' => $id,
                    'Off.user_id' => $user_id
                )
            ));

            //check owned
            if (empty($check)) {
                $this->response->statusCode(406);
                return 'You dont have permission';
            }


            $reason = $check['Off']['reason'];
            $duration = $check['Off']['duration'];
            $dates = $check['Off']['dates'];


            $pattern = '/[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])/';
            preg_match_all($pattern,$dates,$out,PREG_PATTERN_ORDER);

            $time = true;
            foreach ($out['0'] as $key => $value) {
                if (strtotime($value) <= strtotime(date('Y-m-d'))) {
                    $time = false;
                }
            }

            if (!$time) {
                $this->response->statusCode(406);
                return 'The day has gone, can not delete';
            }

            //send chatwork
            $chatwork_data = array(
                'access_token' => $data['User']['access_token'],
                'content' => 'I was looking for take '. $duration . ' day off on ' . $dates . ' because ' . $reason .
                '. But now Im no longer need to leave on those days. So I want to cancel my request. Sorry for inconvenience (bow).',
                'method' => '2'
            );

            $chatwork_data['users'][] = array(
            'chatwork_id' => JO_ID,
            'chatwork_name' => JO_NAME
            );

            $chatwork_data['users'][] = array(
                'chatwork_id' => USUI_ID,
                'chatwork_name' => USUI_NAME
            );

            $res = $this->sendChatWork($chatwork_data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($data['User']['refresh_token']);
                $chatwork_data['access_token'] = $access_token;
                $res = $this->sendChatWork($chatwork_data);
            }

            //delete off
            if ($this->Off->delete($id)) {

                //update all off after
                $offList = $this->Off->find('all',array(
                    'conditions' => array(
                        'Off.id >' => $id
                    )
                ));

                foreach ($offList as $key => $off_data) {
                    $off_save = array(
                        'day_left' => $off_data['Off']['day_left'] + $check['Off']['duration']
                    );
                    if ($check['Off']['status'] != self::DENY) {
                        $this->Off->id = $off_data['Off']['id'];
                        $this->Off->save($off_save);
                    }
                }

                //update User day_off_left
                if ($check['Off']['type'] == 0) {
                    $save = array(
                        'day_off_left' => $data['User']['day_off_left'] + $check['Off']['duration']
                    );
                } else {
                    $save = array(
                        'day_off_left' => $data['User']['day_off_left']
                    );
                }
                if ($check['Off']['status'] != self::DENY) {
                    $this->User->id = $user_id;
                    $this->User->save($save);
                }

                return json_encode('1');
            } else {
                $this->response->statusCode(406);
                return 'Error when delete';
            }
        } elseif ($info == 'leave') {
            $check = $this->Leave->find('first',array(
                'conditions' => array(
                    'Leave.id' => $id,
                    'Leave.user_id' => $user_id
                )
            ));

            //check owned
            if (empty($check)) {
                $this->response->statusCode(406);
                return 'You dont have permission';
            }

            if (strtotime($check['Leave']['date']) <= strtotime(date('Y-m-d'))) {
                $this->response->statusCode(406);
                return 'Can not delete. The day has gone.';
            }



            //send chatwork
            $chatwork_data = array(
                'access_token' => $data['User']['access_token'],
                'method' => '2'
            );

            $chatwork_data['content'] = 'I was looking for leaving the office from ' . date("H:i", strtotime($check['Leave']['start']))
                . ' to ' . date("H:i", strtotime($check['Leave']['end'])) . ' because ' . $check['Leave']['reason'] . '. But now Im no longer need to leaving the the office at that time. So I want to cancel my request. Sorry for inconvenience (bow)';

            if (strtotime($check['Leave']['end']) == strtotime('17:30:00')) {
                $chatwork_data['content'] = 'I was looking for leaving soon from ' . date("H:i", strtotime($check['Leave']['start']))
                    . ' because ' . $check['Leave']['reason'] . '. But now Im no longer need to leaving soon at that time. So I want to cancel my request. Sorry for inconvenience (bow)';
            }

            if (strtotime($check['Leave']['start']) == strtotime('08:30:00')) {
                $chatwork_data['content'] = 'I was looking for coming late at ' . date("H:i", strtotime($check['Leave']['end']))
                    . ' because ' . $check['Leave']['reason'] .  '. But now Im no longer need to coming late at that time. So I want to cancel my request. Sorry for inconvenience (bow)';
            }

            $chatwork_data['users'][] = array(
            'chatwork_id' => JO_ID,
            'chatwork_name' => JO_NAME
            );

            $chatwork_data['users'][] = array(
                'chatwork_id' => USUI_ID,
                'chatwork_name' => USUI_NAME
            );

            $res = $this->sendChatWork($chatwork_data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($data['User']['refresh_token']);
                $chatwork_data['access_token'] = $access_token;
                $res = $this->sendChatWork($chatwork_data);
            }

            //delete Leave
            if ($this->Leave->delete($id)) {
                return json_encode('1');
            } else {
                $this->response->statusCode(406);
                return 'Error when delete';
            }
        } else {
            $this->response->statusCode(406);
            return 'Wrong info';
        }
    }

    public function accept(){
        $this->autoRender = false;

        if ($this->getRole() != self::ADMIN) {
            $this->response->statusCode(406);
            return json_encode(array(
                'error' => 'You dont have permission'
            ));
        }

        $admin = $this->User->find('first',array(
            'conditions' => array(
                'User.email' => $_SESSION['email']
            )
        ));

        //id cua off hoac leave
        $id = $_POST['id'];
        $info = $_POST['info'];
        $status = $_POST['status'];
        $this->log($status);
        if ($info == 'off') {
            $user_data = $this->Off->find('first',array(
                'conditions' => array(
                    'Off.id' => $_POST['id']
                )
            ));
        } elseif ($info == 'leave') {
            $user_data = $this->Leave->find('first',array(
                'conditions' => array(
                    'Leave.id' => $_POST['id']
                )
            ));
        }

        if ($status == self::APPROVED) {
            $data = array(
                'access_token' => $admin['User']['access_token'],
                'content' => '(roger)',
                'method' => '3'
            );

            $data['users'][] = array(
                'chatwork_id' => $user_data['User']['chatwork_id'],
                'chatwork_name' => $user_data['User']['name']
            );

            $res = $this->sendChatWork($data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($admin['User']['refresh_token']);
                $data['access_token'] = $access_token;
                $res = $this->sendChatWork($data);
            }
        } elseif ($status == self::DENY) {
            $data = array(
                'access_token' => $user_data['User']['access_token'],
                'content' => '(shake)',
                'method' => '3'
            );

            $data['users'][] = array(
                'chatwork_id' => $user_data['User']['chatwork_id'],
                'chatwork_name' => $user_data['User']['name']
            );

            $res = $this->sendChatWork($data);

            if (isset($res->errors)) {
                $access_token = $this->getAccessToken($admin['User']['refresh_token']);
                $data['access_token'] = $access_token;
                $res = $this->sendChatWork($data);
            }
        }

        if ($info == 'off') {

            $off_data = $this->Off->find('first',array(
                'conditions' => array(
                    'Off.id' => $id
                )
            ));
            $this->Off->id = $id;

            $user_id = $off_data['User']['id'];
            $day_off_left = $off_data['User']['day_off_left'];
            $duration = $off_data['Off']['duration'];

            $save = array(
                'status' => $status,
                'notice' => '1',
                'approve_time' => date("Y-m-d H:i:s")
            );
            if ($this->Off->save($save)) {
                //update all off after
                $offList = $this->Off->find('all',array(
                    'conditions' => array(
                        'Off.id >' => $id
                    )
                ));

                foreach ($offList as $key => $off) {
                    $off_save = array(
                        'day_left' => $off['Off']['day_left'] + $off_data['Off']['duration']
                    );
                    if ($status != self::DENY && $off_data['Off']['type'] == 0) {
                        $this->Off->id = $off['Off']['id'];
                        $this->Off->save($off_save);
                    }
                }

                //update User day_off_left
                if ($off_data['Off']['type'] == 0) {
                    $day_off_left = $day_off_left + $duration;
                }
                if ($status == self::DENY) {
                    $this->User->id = $user_id;
                    $this->User->save(array(
                        'day_off_left' => $day_off_left
                    ));
                }
                return json_encode('1');
            } else {
                return json_encode('0');
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
            } else {
                return json_encode('0');
            }
        }

        return json_encode('0');
    }

    public function editOff($id = null){
        $email = $_SESSION['email'];

        $user_data = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'User.email' => $email
                )
            ));

        $user_id = $user_data['User']['id'];

        if(isset($id)){
            if(isset($id)){
                $check = $this->Off->find('first',array(
                    'conditions' => array(
                        'Off.id' => $id
                    )
                ));
            }
        }

        if ($check['Off']['user_id'] != $user_id) {
            $this->redirect('/users/home');
        }

        $pattern = '/[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])/';
        preg_match_all($pattern,$check['Off']['dates'],$out,PREG_PATTERN_ORDER);

        $time = true;
        foreach ($out['0'] as $key => $value) {
            if (strtotime($value) <= strtotime(date('Y-m-d'))) {
                $time = false;
            }
        }

        if (!$time) {
            $this->redirect('/users/home');
        }

        $pattern2 = '/[0-9]{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])-[A-Z]{2,3}/';
        preg_match_all($pattern2, $check['Off']['dates'],$out,PREG_PATTERN_ORDER);

        $date_data = array();

        foreach ($out['0'] as $key => $value) {
            $date = preg_split('/-/', $value);
            $date_data[] = $date;
        }

        $this->set('dates', $date_data);

        $this->set('off', $check);

        //save edit off
        if(isset($_POST) && !empty($_POST)){
            $countDay = 0;
            foreach ($_POST['in'] as $in){
                if($in == 'ALL'){
                    $countDay++;
                }else{
                    $countDay = $countDay + 0.5;
                }
            }

            foreach ($_POST['date'] as $key=>$date){
                $date = date('Y/m/d',strtotime($date));
                $dates[] = $date . '-'.$_POST['in'][$key];

            }

            $reason = $_POST['reason'];
            $dates = implode(",", $dates);
            $duration = $countDay;
            $type = $_POST['type'];
            $dates  = $dates;
            $create_at = date("Y-m-d H:i:s");
            $reason = $reason;
            $emotion = $_POST['emotion'];

            $status = self::WAITING;
            if ($type == 0) {
                $day_left = $check['User']['day_off_left'] + $check['Off']['duration'] - $duration;
            } else {
                $day_left = $check['User']['day_off_left'] ;
            }

            $user = array(
                'day_off_left' => $day_left
            );

            $this->User->id = $user_id;
            $this->User->save($user);

            $save = array(
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

            $this->Off->id = $id;
            if ($this->Off->save($save)) {

                //send chatwork
                $data = array(
                    'access_token' => $user_data['User']['access_token'],
                    'content' => 'Because ' . $reason . ', I want to take ' . $duration . ' day off on ' . $dates
                        . '. I hope you aprrove, Thank you (bow)',
                    'method' => '2'
                );

                $data['users'][] = array(
                    'chatwork_id' => JO_ID,
                    'chatwork_name' => JO_NAME
                );

                $data['users'][] = array(
                    'chatwork_id' => USUI_ID,
                    'chatwork_name' => USUI_NAME
                );

                $res = $this->sendChatWork($data);

                if (isset($res->errors)) {
                    $access_token = $this->getAccessToken($user_data['User']['refresh_token']);
                    $data['access_token'] = $access_token;
                    $res = $this->sendChatWork($data);
                }

                $this->redirect('/users/home');
            }
        }

    }

    public function editLeave($id = null){
        $email = $_SESSION['email'];

        $data = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'User.email' => $email
                )
            ));

        $user_id = $data['User']['id'];
        if(isset($id)){
            if(isset($id)){
                $check = $this->Leave->find('first',array(
                    'conditions' => array(
                        'Leave.id' => $id
                    )
                ));

                $this->log($check);
            }
        }


        if ($check['Leave']['user_id'] != $user_id) {
            $this->response->header('Location',"/users/home");
        }

        if (strtotime($check['Leave']['date']) <= strtotime(date('Y-m-d'))) {
            $this->response->header('Location',"/users/home");
        }

        $this->set('leave', $check);

        if(isset($_POST) && !empty($_POST)){
            $save = array(
                'user_id' => $user_id,
                'status' => self::WAITING
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
                $this->response->header('Location',"/users/home");
            }
        }

    }

    private function sendChatWork($data){
        $this->autoRender = false;
        //room_id, accesstoken, 
        $room_id = ROOM_ID;
        $access_token = $data['access_token'];
        $content = $data['content'];

        $method = $data['method'];
        $users = $data['users'];        

        $url = 'https://api.chatwork.com/v2/rooms/'.$room_id.'/messages';

        $header = array(
            'Authorization: Bearer '.$access_token
        );

        $message = '';

        // 1: basic
        // 2: to
        // 3: reply
        switch ($method) {
            case '1':
                $message = $message . $content;
                break;

            case '2':
                foreach ($users as $key => $user) {
                    $message = $message . '[To:' . $user['chatwork_id'] . '] ' . $user['chatwork_name'] . PHP_EOL;
                }
                $message = $message . $content;
                break;

            case '3':
                foreach ($users as $key => $user) {
                    $message = $message . '[rp aid=' . $user['chatwork_id'] . '] ' . $user['chatwork_name'] . PHP_EOL;
                }
                $message = $message . $content;
                break;
            
            default:
                $message = $message . $content;
                break;
        }

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

    private function getAccessToken($refreshToken = null){
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
}
?>