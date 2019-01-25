<?php  
/**
 * 
 */
class Comment extends AppModel
{
	public $useTable = 't_comments';
	public $name = 'Comment';
	public $belongsTo = array(
        'Off' => array(
            'className' => 'Off',
            'foreignKey' => 'off_id'
        ),
        'Leave' => array(
        	'className' => 'Leave',
        	'foreignKey' => 'leave_id'
        )
    );
}
?>