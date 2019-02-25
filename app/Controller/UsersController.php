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
	public function index()
	{
		
	}
}
?>