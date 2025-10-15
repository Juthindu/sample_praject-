<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\Employee\Entities\Employee; 

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$todayDate = date('Y-m-d');
$sheet->setTitle('Employee Export ' . $todayDate);

$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'Employee Report');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A2', 'All Employees');
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

foreach ($allData as $employee) {
    $sheet->setCellValue('A' . $row, $employee->first_name);
    $sheet->setCellValue('B' . $row, $employee->last_name);
    $sheet->setCellValue('C' . $row, $employee->email);
    $sheet->setCellValue('D' . $row, $employee->phone_number);
    $sheet->setCellValue('E' . $row, $employee->address);
    $sheet->setCellValue('F' . $row, $employee->nic);
    $row++;
}

foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$fileName = 'employee_report_' . $todayDate . '.xlsx';
$fileName = str_replace(' ', '_', $fileName);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$fileName\"");
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit();
