<?php  
/**
 * 
 */
class User extends AppModel
{
	public $useTable = 't_users';
	public $name = 'User';
	public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role'
        )
    );
}
?>