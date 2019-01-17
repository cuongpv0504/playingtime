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
        )
    );
}
?>