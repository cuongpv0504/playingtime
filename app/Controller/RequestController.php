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

    private function sendChatWork($data)
    {
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
}
?>