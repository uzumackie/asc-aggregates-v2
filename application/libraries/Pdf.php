<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once dirname(__FILE__) . '/TCPDF/tcpdf.php';

class Pdf extends TCPDF
{
	private $tableHeader1 = false;

	function __construct()
	{
		parent::__construct();
	}

	public function SetTableHeader1($header)
	{
		$this->tableHeader1 = $header;
	}

	//Page header
	// public function Header() {
	// 	// Logo
	// 	$image_file = K_PATH_IMAGES.'logo_example.jpg';
	// 	$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	// 	// Set font
	// 	$this->SetFont('helvetica', 'B', 20);
	// 	// Title
	// 	$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	// }
	public function Header()
	{

		if ($this->CurOrientation == 'P') {
			$this->Ln(-5);
			$this->SetFont('helvetica', 'I', 7);
			$this->Cell(161, 5, '', 0, 0, 'L');
			$this->Cell(0, 15, "Page " . $this->getAliasNumPage() . " of " . $this->getAliasNbPages(), 0, 0, 'L');
		}

		if ($this->CurOrientation == 'L') {
			$this->Ln(-5);
			$this->SetFont('helvetica', 'I', 7);
			$this->Cell(161, 5, '', 0, 0, 'L');
			$this->Cell(0, 15, "Page " . $this->getAliasNumPage() . " of " . $this->getAliasNbPages(), 0, 0, 'R');
		}

		$this->Ln(10);
		if ($this->tableHeader1) {
			$this->Ln(10); //next line
			$this->Cell(270, 0, '', 'B', '', '', false); //line
			$this->Ln(2.5);
			$this->SetFont('helvetica', 'B', 10);
			$this->Cell(20, 8, 'Code', 0, 0, 'L');
			$this->Cell(40, 8, 'Name/TDS No.', 0, 0, 'L');
			$this->Cell(65, 8, 'Item', 0, 0, 'L');
			$this->Cell(20, 8, 'TDS Date', 0, 0, 'L');
			$this->Cell(25, 8, 'Gross Sales', 0, 0, 'L');
			$this->Cell(30, 8, 'Cus. Returns', 0, 0, 'L');
			$this->Cell(18, 8, 'WTax', 0, 0, 'L');
			$this->Cell(25, 8, 'Discount', 0, 0, 'L');
			$this->Cell(0, 8, 'Net Amount', 0, 0, 'L');
			$this->Ln(2.5);
			$this->Cell(270, 0, '', 'B', '', '', false); //line

			$this->SetMargins(15, 20, 15); // left, top, right
		}
	}

	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}