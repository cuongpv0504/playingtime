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
        )
    );
}
?>