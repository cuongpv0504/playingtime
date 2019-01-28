<?php  
/**
 * 
 */
class Leave extends AppModel
{
	public $useTable = 't_leave';
	public $name = 'Leave';
	public $belongsTo = array(
        'Status' => array(
            'className' => 'Status',
            'foreignKey' => 'status'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    public $hasMany = array(
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'leave_id'
        )
    );
}
?>