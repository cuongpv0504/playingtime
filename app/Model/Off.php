<?php  
/**
 * 
 */
class Off extends AppModel
{
	public $useTable = 't_off';
	public $name = 'Off';
	public $belongsTo = array(
        'Status' => array(
            'className' => 'Status',
            'foreignKey' => 'status'
        ),
        'Type' => array(
        	'className' => 'Type',
        	'foreignKey' => 'type'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );
    public $hasMany = array(
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'off_id'
        )
    );
}
?>