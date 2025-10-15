<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\Customer\Entities\Customer; 


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$todayDate = date('Y-m-d');
$sheet->setTitle('Customer Export ' . $todayDate);

$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'Customer Report');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A2', 'All Customers');
$sheet->getStyle('A2')->getFont()->setBold(true);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A3', 'First Name');
$sheet->setCellValue('B3', 'Last Name');
$sheet->setCellValue('C3', 'Email');
$sheet->setCellValue('D3', 'Phone Number');
$sheet->setCellValue('E3', 'Address');
$sheet->setCellValue('F3', 'NIC');

$sheet->getStyle('A3:F3')->getFont()->setBold(true);

$row = 4;

foreach ($allData as $customer) {
    $sheet->setCellValue('A' . $row, $customer->first_name);
    $sheet->setCellValue('B' . $row, $customer->last_name);
    $sheet->setCellValue('C' . $row, $customer->email);
    $sheet->setCellValue('D' . $row, $customer->phone_number);
    $sheet->setCellValue('E' . $row, $customer->address);
    $sheet->setCellValue('F' . $row, $customer->nic);
    $row++;
}

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = 'customer_report_' . $todayDate . '.xlsx';
$fileName = str_replace(' ', '_', $fileName);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$fileName\"");
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
