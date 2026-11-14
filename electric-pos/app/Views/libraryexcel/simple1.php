<?php
    require_once 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    if(isset($_POST['download'])){
        // Create new excel file
        $spreadsheet = new Spreadsheet();
        // Add data to first worksheet
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Sheet 1');
        $sheet1->setCellValue('A1', 'Hello');
        $sheet1->setCellValue('B2', 'World!');

        // Add data to second worksheet
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Sheet 2');
        $sheet2->setCellValue('A1', 'Hello');
        $sheet2->setCellValue('B2', 'World!');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="MyExcelSheet.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
?>
<form method="post">
    <input type="submit" name="download" value="Download Excel">
</form>
