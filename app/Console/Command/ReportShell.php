<?php
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
/**
 * 
 */
class ReportShell extends AppShell
{
	public $uses = array('User','Off','Leave');

	public function main()
	{
		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		$section= $phpWord->addSection(array('orientation' => 'landscape'));
		$title = 'LEAVE REQUEST FORM';
		$subTitle = 'ĐƠN XIN NGHỈ';

		$section->addText($title,array(
			'name' => 'Times New Roman',
			'size' => 36,
			'bold' => true,
		));

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save(APP. DS . 'tmp' . DS . 'report' . DS . 'helloWorld.docx');
	}
}
?>