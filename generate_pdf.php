<?php
session_start();
require_once "db.php";
require_once __DIR__ . "/libs/tcpdf/tcpdf.php";

if (!isset($_SESSION['user_id'])) {
    die("Access Denied!");
}

$user_id = $_SESSION['user_id'];

// Fetch license info
$stmt = $conn->prepare("SELECT id, full_name, nida_no, control_number, expiry_date, created_at, photo_path FROM licenses WHERE user_id = ? AND license_status = 'Active' LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($license_id, $full_name, $nida_no, $control_number, $expiry_date, $created_at, $photo_path);
    $stmt->fetch();
} else {
    die("No active license found!");
}

// Create PDF with credit card dimensions
$pdf = new TCPDF('L', 'mm', array(86, 54), true, 'UTF-8', false);
$pdf->SetCreator("License Management System");
$pdf->SetAuthor("Government License Dept.");
$pdf->SetTitle("License Card");

// Remove margins and headers
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(2, 2, 2);
$pdf->SetAutoPageBreak(false);

// Add page
$pdf->AddPage();

// Light blue background
$pdf->SetFillColor(240, 245, 255);
$pdf->Rect(0, 0, 86, 54, 'F');

// White card body
$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(200, 200, 200);
$pdf->RoundedRect(2, 2, 82, 50, 2, '1111', 'DF');

// Header
$pdf->SetFont("helvetica", "B", 7);
$pdf->SetTextColor(30, 70, 150);
$pdf->Cell(0, 4, "NATIONAL LICENSE CARD", 0, 1, "C");

// Photo (right side - 20×20mm)
if (!empty($photo_path) && file_exists($photo_path)) {
    $pdf->Image($photo_path, 62, 6, 20, 20, '', '', '', false, 300);
    // Photo border
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Rect(62, 6, 20, 20);
}

// License details (adjusted 5mm rightward from previous version)
$pdf->SetFont("helvetica", "", 6);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(6, 8); // Changed from 4 to 6 for X position

$html = '
<style>
    .label { font-weight: bold; font-size: 5.5pt; line-height: 1.2; }
    .value { font-size: 6pt; line-height: 1.2; }
    .expiry { color: #d9534f; font-weight: bold; }
</style>
<table cellpadding="1" cellspacing="0">
<tr><td class="label" width="22">ID:</td><td class="value" width="33">' . $license_id . '</td></tr>
<tr><td class="label">Name:</td><td class="value">' . mb_strimwidth(htmlspecialchars($full_name), 0, 24, '...') . '</td></tr>
<tr><td class="label">NIDA:</td><td class="value">' . $nida_no . '</td></tr>
<tr><td class="label">Issued:</td><td class="value">' . date("d/m/Y", strtotime($created_at)) . '</td></tr>
<tr><td class="label">Expires:</td><td class="expiry">' . date("d/m/Y", strtotime($expiry_date)) . '</td></tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Barcode (moved left and down slightly)
$pdf->SetFont("helvetica", "B", 5);
$pdf->SetTextColor(30, 70, 150);
$pdf->SetXY(4, 30); // Changed from 4,28 to 4,30 (2mm lower)
$pdf->Cell(38, 3, "CTRL: " . $control_number, 0, 1, "L");

$pdf->write1DBarcode($control_number, 'C128', 4, 33, 38, 8, 0.3, // Width reduced to 38mm
    [
        'position' => 'C',
        'bgcolor' => [255, 255, 255],
        'color' => [30, 70, 150]
    ], 
    'N');

// QR Code (unchanged position)
$style = array(
    'border' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(30, 70, 150),
    'bgcolor' => false,
    'module_width' => 0.5,
    'module_height' => 0.5
);
$pdf->write2DBarcode($control_number, 'QRCODE,L', 62, 28, 18, 18, $style, 'N');

// Footer text
$pdf->SetFont("helvetica", "I", 4);
$pdf->SetTextColor(100, 100, 100);
$pdf->SetXY(4, 46);
$pdf->Cell(78, 3, "Scan QR to verify • National Licensing Authority", 0, 0, "C");

// Output
$filename = "LicenseCard_" . preg_replace("/[^A-Za-z0-9]/", "_", $control_number) . ".pdf";
$pdf->Output($filename, "D");

$stmt->close();
$conn->close();
?>