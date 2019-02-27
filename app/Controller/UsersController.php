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

	public function index()
	{

	}

    public function profile()
    {

        $userData = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'email' => 'rainbow.bkhn@gmail.com'
                )
            )
        );
        $this->set('userData', $userData);

        if($this->request->is("post")){
            $this->log($this->request->data);
            $this->log("1");
            $this->response->header('Location',"/users");
        }
    }
}
?>