<?php  
App::import('Vendor', 'vendor', array('file' => 'autoload.php'));

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;
/**
 * 
 */
class WordController extends AppController
{
	public $uses = array('User','Off','Leave');

	public function downloadDocument($id)
	{

		$this->response->file($path);
	}

	public function export($id)
	{
		$this->autoRendeer = false;
		$data = $this->User->find('first',array(
			'conditions' => array(
				'User.id' => $id
			)
		));

		$user_data = array(
			'email' => $data['User']['email'],
			'name' => $data['User']['name'],
			'id' => $id
		);

		$off_data = $this->Off->find('first',array(
			'conditions' => array(
				'User.id' => $id
			)
		));
		
		$this->createDocument($user_data,$off_data);
		
	}

	public function createDocument($user_data,$off_data)
	{
		$this->autoRendeer = false;
		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		$section= $phpWord->addSection(array(
			'orientation' => 'landscape',
			'marginLeft' => 600,
			'marginRight' => 600,
			'marginTop' => 600, 
			'marginBottom' => 600
		));
		$title = 'LEAVE REQUEST FORM';
		$subTitle = 'ĐƠN XIN NGHỈ NĂM 2019';

		$paragraphStyle = array(
			'alignment' => 'center'
		);

		$paragraphCellStyle = array(
			'alignment' => 'center',
			'spaceBefore' => 100,
			'spaceAfter' => 100
		);

		$section->addText(
			$title,
			array(
			'size' => 26,
			'bold' => true
			),
			$paragraphStyle
		);
		$section->addText(
			$subTitle,
			array(
			'size' => 20
			),
			$paragraphStyle
		);

		//table
		$table = $section->addTable(
			array(
				'borderSize' => 6, 
				'borderColor' => '000000',
				'alignment' => 'center'
			)
		);

		$colSpan = array(
			'gridSpan' => 2
		);

		$cellRowSpan = array(
			'vMerge' => 'restart'
		);

		$cellRowContinue = array(
			'vMerge' => 'continue'
		);

		$fontHeaderFormat = array(
			'size' => 12,
			'bold' => true
		);

		$fontCellFormat = array(
			'size' => 12
		);

		$row = $table->addRow();
		$row->addCell(3000, $colSpan)->addText('Division', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(2000, $colSpan)->addText('Team', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(2000, $colSpan)->addText('Position', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(5000, $colSpan)->addText('Full Name', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(2000)->addText('Enable Annual Leave', $fontCellFormat, $paragraphCellStyle);

		$row = $table->addRow();
		$row->addCell(null, $colSpan)->addText('Engineer', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(null, $colSpan)->addText('', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(null, $colSpan)->addText('Staff', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(null, $colSpan)->addText($user_data['email'], $fontHeaderFormat, $paragraphCellStyle);
		$row->addCell(null)->addText('12', $fontHeaderFormat, $paragraphCellStyle);

		$row = $table->addRow();
		$row->addCell(1500, $cellRowSpan)->addText('Date of request', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(1500, $cellRowSpan)->addText('Date of leave', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(null, $colSpan)->addText('Annual Leave', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(1000, $cellRowSpan)->addText('Orther leave', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(1000, $cellRowSpan)->addText('Type of orther leave', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(3500, $cellRowSpan)->addText('Reason', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(1500, $cellRowSpan)->addText('Signature of employee', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(2000, $cellRowSpan)->addText('Approval Authority', $fontCellFormat, $paragraphCellStyle);

		$row = $table->addRow();
		$row->addCell(null, $cellRowContinue);
		$row->addCell(null, $cellRowContinue);
		$row->addCell(1000)->addText('No of AL day', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(1000)->addText('No of AL day remain', $fontCellFormat, $paragraphCellStyle);
		$row->addCell(null, $cellRowContinue);
		$row->addCell(null, $cellRowContinue);
		$row->addCell(null, $cellRowContinue);
		$row->addCell(null, $cellRowContinue);
		$row->addCell(null, $cellRowContinue);

		if (count($off_data) > 12) {
			$row_num = count($off_data);
		} else {
			$row_num = 12;
		}

		for ($i=0; $i < $row_num; $i++) { 
			$row = $table->addRow();
			if ($i >= count($off_data)) {
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
				$row->addCell(null);
			} else {
				$row->addCell(null)->addText(date('m/d',strtotime($off_data[$i]['Off']['create_at'])), $fontCellFormat, $paragraphCellStyle);
				$row->addCell(null)->addText($off_data[$i]['Off']['dates'], $fontCellFormat, $paragraphCellStyle);
				$row->addCell(null)->addText($off_data[$i]['Off']['duration'], $fontCellFormat, $paragraphCellStyle);
				$row->addCell(null)->addText($off_data[$i]['Off']['day_left'], $fontCellFormat, $paragraphCellStyle);

				if ($off_data[$i]['Off']['type'] != 0) {
					$row->addCell(null)->addText($off_data[$i]['Off']['duration'], $fontCellFormat, $paragraphCellStyle);
					$row->addCell(null)->addText($off_data[$i]['Type']['description'], $fontCellFormat, $paragraphCellStyle);
				} else {
					$row->addCell(null);
					$row->addCell(null);
				}

				$row->addCell(null)->addText($off_data[$i]['Off']['reason'], $fontCellFormat, $paragraphCellStyle);
				$row->addCell(null)->addText($user_data['name'], $fontCellFormat, $paragraphCellStyle);
				$row->addCell(null)->addText($off_data[$i]['Status']['status'], $fontCellFormat, $paragraphCellStyle);
			}		
		}

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save(WWW_ROOT . 'report' . DS . $user_data['id'].'.docx');
	}
}
?>