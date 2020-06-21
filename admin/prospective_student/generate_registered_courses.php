<?php
session_start();
error_reporting(1);
include '../../lib/dbfunctions_extra.php';
require('fpdf182/fpdf.php');

$dbobject = new myDbObject();

// file_put_contents('rs.txt', json_encode($_SESSION));

$reg_id = $_SESSION['reg_id'];
// file_put_contents('rs.txt', $reg_id);
$semester = $_GET['registered_semester'];
$level = $_GET['registered_level'];
if($semester == '1'){
    $selected_semester = "FIRST SEMESTER";
}else if($semester == '2'){
    $selected_semester = "SECOND SEMESTER";
}

if(!isset($_SESSION['reg_id'])){
    header("Location:../index.php");
}

if (isset($reg_id)) {
    $result = $dbobject->getrecordset('student_information', 'reg_id', $reg_id);
    $numrows = mysql_num_rows($result);

    $query = "SELECT * FROM app_applicant_account_setup appl INNER JOIN student_information si ON si.reg_id = appl.reg_id WHERE si.reg_id=$reg_id";
	$student_details_resource = mysql_query($query);
	$details = mysql_fetch_array($student_details_resource);
    $student_id = $details['student_id'];

    $sql = "SELECT course_id FROM course_registration WHERE student_id = '".$student_id."' AND level = ".$level." AND semester = ".$semester." AND elected = 0";
    $comp_sql = $dbobject->db_query($sql);
    // file_put_contents('rs.txt', $comp_sql);

    $elect_sql = "SELECT course_id FROM course_registration WHERE student_id = '".$student_id."' AND level = ".$level." AND semester = ".$semester." AND elected = 1";
    $elect_sql = $dbobject->db_query($elect_sql);
    
	$programme_id = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'program');
	$programme = $dbobject->getitemlabel('programme_setup', 'programme_id', $programme_id, 'programme_name');
    $department_id = $dbobject->getitemlabel('programme_setup', 'programme_id', $programme_id, 'department_id');
    file_put_contents('rs.txt', $department_id);
    $department = $dbobject->getitemlabel('department_setup_tbl', 'dapartment_id', $department_id, 'department_name');
	$level = $dbobject->getitemlabel('student_information', 'student_id', $student_id, 'level');


    if ($numrows > 0) {
		$row = mysql_fetch_array($result);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $middle_name = $row['middle_name'];
        $level = $row['level'];
        $matric_number = $row['matric_number'];
        $state_id = $row['state_of_origin'];
        $state_of_origin = $dbobject->getitemlabel('app_states', 'id', $state_id, 'name');
        $lga = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'local_Gov_Area');
        $sex = $dbobject->getitemlabel('app_applicant_account_setup', 'reg_id', $reg_id, 'gender');
        $department_option = $dbobject->getitemlabel('department_option', 'option_id', $department_option_id, 'option_name');
        $full_name = strtoupper($last_name.", ".$first_name." ".$middle_name);
    }else{
        header("Location:../../index.php");
    }
}else{
    header("Location:../index.php");
}

$message = "Return this form to the H.O.D\’s office to confirm that you have been duly registered as a student of 
            Olivet College of Health Technology, Azuba. Failure to do this means you are not a recognized student. 
            A copy of the duly registered form should be submitted to the registrar’s office.";

$logo = "img/logo-primary.png";
$dbobject = new myDbObject();
// A4 => width: 210mm && height: 297mm;

$pdf = new FPDF('P', 'mm', 'A4');

$pdf->setMargins(10,20,10);

$pdf->AddPage();

$pdf->SetFont('Times', 'B', 16);

// $pdf->Cell(60,12, $pdf->Image("img/logo-primary.png", 10, 10, 60, 12), 0, 0, 'C');

$pdf->Cell(190, 6, 'OLIVET COLLEGE OF HEALTH TECHNOLOGY', 0, 1, 'C');

$pdf->SetFont('Times', 'B', 11);

$pdf->Cell(190, 5, 'PMB 117 AZUBA CENTRE', 0, 1, 'C');

$pdf->Cell(190, 5, 'LAFIA-NASARAWA STATE', 0, 1, 'C');

$pdf->Cell(190, 5, '(OFFICE OF REGISTRAR)', 0, 1, 'C');

$pdf->SetFont('Times', 'B', 13);

$pdf->Ln(4);

$pdf->Cell(190, 5, 'STUDENT COURSE REGISTRATION FORM', 0, 1, 'C');

$pdf->Ln(3);

$pdf->SetFont('Times', '', 11);

$pdf->Ln(1);

$pdf->setMargins(10,20,10);

$pdf->Cell(15, 5, 'NAME:', 0, 0, '');
$pdf->Cell(58, 5, $full_name, 0, 0, '');
$pdf->Cell(15, 5, 'LEVEL:', 0, 0, '');
$pdf->Cell(20, 5, $level, 0, 0, '');
$pdf->Cell(52, 5, 'MATRICULATION NUMBER:', 0, 0, '');
$pdf->Cell(40, 5, $matric_number, 0, 1, 'L');

$pdf->Ln(1);

$pdf->Cell(35, 5, 'STATE OF ORIGIN:', 0, 0, '');
$pdf->Cell(38, 5, strtoupper($state_id), 0, 0, '');
$pdf->Cell(10, 5, 'LGA:', 0, 0, '');
$pdf->Cell(60, 5, strtoupper($lga), 0, 0, '');
$pdf->Cell(10, 5, 'SEX:', 0, 0, '');
$pdf->Cell(20, 5, strtoupper($sex), 0, 1, '');

$pdf->Ln(1);

$pdf->Cell(38, 5, 'COURSE OF STUDY:', 0, 0, '');
$pdf->Cell(83, 5, strtoupper($programme), 0, 1, '');
$pdf->Ln(1);
$pdf->Cell(30, 5, 'DEPARTMENT:', 0, 0, '');
$pdf->Cell(50, 5, strtoupper($department), 0, 1, '');

$pdf->Ln(1);

$pdf->Cell(25, 5, 'SEMESTER:', 0, 0, '');
$pdf->Cell(50, 5, $selected_semester, 0, 0, '');
$pdf->Cell(15, 5, 'YEAR:', 0, 0, '');
$pdf->Cell(50, 5, date('Y'), 0, 1, '');

$pdf->Ln(3);

$pdf->SetFont('Times', 'B', 12);

$pdf->Cell(190, 5, 'COMPULSORY COURSES', 0, 1, 'C');

$pdf->SetFont('Times', '', 11);

$pdf->Cell(10, 7, '#', 1, 0, 'C');
$pdf->Cell(104, 7, 'COURSE TITLE', 1, 0, 'C');
$pdf->Cell(18, 7, 'CODE', 1, 0, 'C');
$pdf->Cell(18, 7, 'UNIT', 1, 0, 'C');
$pdf->Cell(40, 7, 'LECTURER\'S SIGN', 1, 1, 'C');

$pdf->SetFont('Times', '', 10);
$serial_number = 1;
$total_load = 0;
foreach($comp_sql as $key => $value){
    $sql = "SELECT * FROM course_setup_tbl WHERE course_id = ".$value['course_id'];
    $course = $dbobject->db_query($sql);
    $course = $course[0];
    $pdf->Cell(10, 8, $serial_number, 1, 0, 'C');
    $pdf->Cell(104, 8, strtoupper($course['course_title']), 1, 0, '');
    $pdf->Cell(18, 8, strtoupper($course["course_code"]), 1, 0, 'C');
    $pdf->Cell(18, 8, $course["course_unit"], 1, 0, 'C');
    $pdf->Cell(40, 8, '', 1, 1, 'C');
    $serial_number++;
    $total_load += floatval($course["course_unit"]);
}

if($elect_sql != NULL){
    $pdf->Ln(3);

    $pdf->SetFont('Times', 'B', 12);

    $pdf->Cell(190, 5, 'ELECTIVE COURSES', 0, 1, 'C');

    $pdf->SetFont('Times', '', 11);

    $pdf->Cell(10, 7, '#', 1, 0, 'C');
    $pdf->Cell(104, 7, 'COURSE TITLE', 1, 0, 'C');
    $pdf->Cell(18, 7, 'CODE', 1, 0, 'C');
    $pdf->Cell(18, 7, 'UNIT', 1, 0, 'C');
    $pdf->Cell(40, 7, 'LECTURER\'S SIGN', 1, 1, 'C');

    $pdf->SetFont('Times', '', 10);

    foreach($elect_sql as $key => $value){
        $sql = "SELECT * FROM course_setup_tbl WHERE course_id = ".$value['course_id'];
        $course = $dbobject->db_query($sql);
        $course = $course[0];
        $pdf->Cell(10, 8, $serial_number, 1, 0, 'C');
        $pdf->Cell(104, 8, strtoupper($course['course_title']), 1, 0, '');
        $pdf->Cell(18, 8, strtoupper($course["course_code"]), 1, 0, 'C');
        $pdf->Cell(18, 8, $course["course_unit"], 1, 0, 'C');
        $pdf->Cell(40, 8, '', 1, 1, 'C');
        $serial_number++;
        $total_load += floatval($course["course_unit"]);
    }
}

$pdf->Ln(5);

$pdf->Cell(149, 5, '', 0, 0, '');
$pdf->Cell(0, 5, strtoupper('Total credit load: '.$total_load), 0, 1, '');
$pdf->Ln(5);

$pdf->Cell(120, 5, 'STUDENT\'S SIGNATURE: ........................................................................................', 0, 0, '');

$pdf->Cell(70, 5, 'DATE: ................................................................', 0, 1, '');

$pdf->Ln(2);

$pdf->Cell(120, 5, 'BURSAR\'S SIGNATURE: ........................................................................................', 0, 1, '');

$pdf->Ln(2);

// $pdf->Cell(70, 5, 'DATE: ................................................................', 0, 1, '');

$pdf->Cell(120, 5, 'REGISTRAR\'S SIGNATURE: ........................................................................................', 0, 1, '');

$pdf->Ln(2);

// $pdf->Cell(70, 5, 'DATE: ................................................................', 0, 1, '');

$pdf->Cell(120, 5, 'HOD\'S SIGNATURE: ........................................................................................', 0, 1, '');

$pdf->Ln(4);

$pdf->SetFont('Times', '', 11);

$pdf->Cell(190, 5, 'Return this form to the H.O.D\'s office to confirm that you have been duly registered as a student of Olivet College of Health ', 0, 1, '');
$pdf->Cell(190, 5, 'Technology, Azuba. Failure to do this means you are not a recognized student. A copy of the duly registered form should be ', 0, 1, '');
$pdf->Cell(190, 5, 'submitted to the registrar\'s office.', 0, 1, '');


$pdf->Output('I', 'registered_courses.pdf', false);

?>