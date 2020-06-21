<?php
error_reporting(0);
session_start();
include '../../lib/dbfunctions_extra.php';
require('fpdf182/fpdf.php');
class PDF extends FPDF {
    function Footer()
                {
                    /* Position at 1.5 cm from bottom */
                    $this->SetY(-15);
                    /* Arial italic 8 */
                    $this->SetFont('Arial','I',8);
                    /* Page number */
                    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                }
}
$dbobject = new myDbObject();

//$Program = cryptoJsAesDecrypt(SONMPASSWORDKEY, $Program);
$reg_id = $_SESSION['reg_id'];

if(!isset($_SESSION['reg_id'])){
    header("Location:../../index.php");
}

if (isset($reg_id)) {
    $result = $dbobject->getrecordset('app_applicant_account_setup', 'reg_id', $reg_id);
    $numrows = mysql_num_rows($result);
    if($numrows>0){
        $row = mysql_fetch_array($result);
        $surname = $row['surname'];
        $othernaame = $row['othernaame'];
        $phone_number = $row['phone_number'];
        $program = $row['program'];
        $rrr = $row['rrr'];
        $program = $row['program'];
        $reg_status = $row['reg_status'];
        $rrr_status = $row['rrr_status'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['vcode'] = $row['linkCode'];
        $vcode = $row['linkCode'];
        $rr_es  = $row['rrr_acceptance'];
        $second_status = $row['rrr_acceptance_status'];
        $admissionstatus = $row['admissionstatus'];
        $lock = $row['application_lock'];
        $exam_center = $row['exam_center_id'];  
        
        $intro = "With Reference to your application for admission into the college of education to study Medical Laboratory Science, we are pleased to inform you that you have been offered provisional admission according to the acceptance payment being made for the course";
        $image1 = "img/logo-primary.png";
        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->setMargins(10,5,10);
        $pdf->setTitle("Admission Letter");
        $pdf->AddPage();

        $pdf->SetFont('Times','',14);
        $pdf->Cell( 0, 20, $pdf->Image($image1, 80, 10,50), 0, 1, 'C');
        $pdf->Cell(0, 5, "OLIVET COLLEGE OF EDUCATION",0,1,"C");

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(1);
        $pdf->Cell(0, 10, "______________________________  Office of the Dean_________________________________",0,1,"C");
        $pdf->SetFont('Arial','BI',9);
        $pdf->Cell(130, 5, "Vice-Chancellor: Someone Name",0,0,"L");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 5, "P.M.B 704, AKURE,",0,1,"L");
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(130, 5, "Bsc, M.Sc, Ph.D:",0,0,"L");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 5, "ONDO STATE, NIGERIA.",0,1,"L");
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(130, 5, "",0,0,"L");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 5, "e-mail: info@olivet.com.ng",0,1,"L");
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(130, 5, "",0,0,"L");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(0, 5, "website: olivet.com.ng",0,1,"L");
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial','B',12);
        $pdf->Multicell(0, 5, $surname." ".$othernaame."\n".$reg_id,0,"L");
        $pdf->Ln();

        $pdf->SetFont('Times','',12);
        $pdf->Multicell(0, 7, "Dear Applicant,",0,"L");
        $pdf->Ln();


        $pdf->SetFont('Times','U'); 
        $pdf->Multicell(0, 6, "OFFER OF PROVISIONAL ADMISSION INTO DEGREE PROGRAMME (2020/2021 ACADEMIC SESION)",0,"C");
        $pdf->Ln();

        $pdf->SetFont('Times','',12); 
        $pdf->Multicell(0, 7, $intro,0,"L");
        $pdf->Ln();

        $pdf->Output('','Olivet Admission Letter.pdf');

    }

}

?>