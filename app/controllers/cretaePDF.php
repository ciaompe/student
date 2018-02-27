<?php 

class CretaePDF extends Controller
{
	
	private $_user;

	public function __construct() {
		$this->_user = $this->model('User');
	}

	public function index() {

		if (!$this->_user->isLoggedIn()) {
		  	Redirect::to('login');
		}

		if ($this->_user->student()) {

 			$student = $this->model('StudentModel');
			$subjects = $this->model('SubjectModel');
			$batch = $this->model('BatchModel');

			$data = array(
				'grade' => $student->getResults($student->getStudentByUserID($this->_user->data()->user_id)->studentId),
				'subjects' => $subjects->getallSubjects(),
				'batch' => $batch->validBatchID($student->getStudentByUserID($this->_user->data()->user_id)->batchId),
			);

			$suject = array();
			$result = array();
			$studentName = $student->getStudentByUserID($this->_user->data()->user_id)->studentName;
			$batch = $batch->validBatchID($student->getStudentByUserID($this->_user->data()->user_id)->batchId)[0]->batch_number;

				foreach ($data['subjects'] as $subject) {
					foreach ($data['grade'] as $grade) {

						if ($subject->sub_id == $grade->sub_id) {

							$grading = '';

							if ($grade->grade == "D") {
								$grading = 'DISTINCTION';
							} else if($grade->grade == "M") {
								$grading = 'MERIT';
							} else if($grade->grade == "P") {
								$grading = 'PASS';
							} else if($grade->grade == "R") {
								$grading = 'RE SUBMIT';
							}

							$suject[] = $subject->sub_name;
							$result[] = $grading;
						} 

						if ($subject->sub_id != $grade->sub_id) {
							$suject[] = $subject->sub_name;
							$result[] = '';
						}

					}
				}


			$pdf= new FPDF('P','mm','A4');

 			$textColour = array( 0, 0, 0 );
			$headerColour = array( 100, 100, 100 );
			$tableHeaderTopTextColour = array(255, 255, 255 );
			$tableHeaderTopFillColour = array(39, 174, 96);
			$tableHeaderTopProductTextColour = array( 255, 255, 255 );
			$tableHeaderTopProductFillColour = array(39, 174, 96);
			$tableHeaderLeftTextColour = array( 99, 42, 57 );
			$tableHeaderLeftFillColour = array( 220, 228, 236);
			$tableBorderColour = array( 50, 50, 50 );
			$tableRowFillColour = array( 220, 228, 236);
			$columnLabels = array( "Grade");

			$rowLabels = $suject;
			$data = $result;

			$pdf->AddPage();

			$pdf->SetTextColor( $headerColour[0], $headerColour[1], $headerColour[2] );
			$pdf->SetFont( 'Arial', '', 11);
			$pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
			$pdf->Write( 6, "Title : MYSARA College Student Subject Report");
			$pdf->Ln(6);
			$pdf->Write( 6, "Student Name : ".$studentName);
			$pdf->Ln(6);
			$pdf->Write( 6, "Batch Name : Batch ".$batch );
			$pdf->Ln(6);
			$pdf->Write( 6, "Generated Time & Date : ".date('Y:m:d H:i:s'));
			$pdf->Ln( 12 );

			$pdf->SetDrawColor( $tableBorderColour[0], $tableBorderColour[1], $tableBorderColour[2] );
			// Create the table header row
			$pdf->SetFont( 'Arial', 'B', 15 );

			// "PRODUCT" cell
			$pdf->SetTextColor( $tableHeaderTopProductTextColour[0], $tableHeaderTopProductTextColour[1], $tableHeaderTopProductTextColour[2] );
			$pdf->SetFillColor( $tableHeaderTopProductFillColour[0], $tableHeaderTopProductFillColour[1], $tableHeaderTopProductFillColour[2] );
			$pdf->Cell( 150, 12, "Subject", 1, 0, 'L', true );

			// Remaining header cells
			$pdf->SetTextColor( $tableHeaderTopTextColour[0], $tableHeaderTopTextColour[1], $tableHeaderTopTextColour[2] );
			$pdf->SetFillColor( $tableHeaderTopFillColour[0], $tableHeaderTopFillColour[1], $tableHeaderTopFillColour[2] );

			for ( $i=0; $i<count($columnLabels); $i++ ) {
			  $pdf->Cell( 36, 12, $columnLabels[$i], 1, 0, 'C', true );
			}

			$pdf->Ln( 12 );

			// Create the table data rows

			$fill = false;
			$row = 0;

			foreach ( $data as $dataRow ) {

			  // Create the left header cell
			  $pdf->SetFont( 'Arial', '', 12 );
			  $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
			  $pdf->SetFillColor( $tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2] );
			  $pdf->Cell( 150, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

			  // Create the data cells
			  $pdf->SetTextColor( $textColour[0], $textColour[1], $textColour[2] );
			  $pdf->SetFillColor( $tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2] );
			  $pdf->SetFont( 'Arial', '', 12 );

			  $pdf->Cell( 36, 12, $dataRow, 1, 0, 'C', $fill);
			 

			  $row++;
			  $fill = !$fill;
			  $pdf->Ln( 12 );
			}

			$pdf->Output( "report.pdf", "D" );

		}

	}

}


 ?>