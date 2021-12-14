<?php

namespace excel;
use PhpOffice\PhpSpreadsheet\Style\Alignment as Align;
use PhpOffice\PhpSpreadsheet\Style\Border as Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style;
require_once './vendor/autoload.php';

class excel_function {
    // 薄い先を枠すべてに引く
    public function setCellAllBorders($sheet, $pos) {
        $styleArray = [
            'borders' => [
                'outline' => [
                    // 線のスタイル
                    'style' => Border::BORDER_THIN,
                    // 線の色
                    'color' => ['argb' => '#C0C0C0'],
                ],
            ],
        ];
        $sheet->getStyle($pos)->applyFromArray($styleArray);
    }

    // 薄い先を枠の上側に引く
    public function setCellTopBorders($sheet, $pos) {
        $styleArray = [
            'borders' => [
                'top' => [
                    // 線のスタイル
                    'style' => Border::BORDER_THIN,
                    // 線の色
                    'color' => ['argb' => '#C0C0C0'],
                ],
            ],
        ];
        $sheet->getStyle($pos)->applyFromArray($styleArray);
    }

    //シートのコピー
    public function sheetcopy($sheet, $copy_sheet_name, $copied_new_sheet_name) {
        $cloneworksheet = clone $sheet->getSheet($copy_sheet_name);
        $cloneworksheet->setTitle($copied_new_sheet_name);
        $sheet->addSheet($cloneworksheet);
    }

    //シートのタイトル作成
    public function sheettitle($sheet, $title_name) {
        $sheet->setTitle($title_name);
    }

    // 文字の左詰め制御
    public function setLeftFont($sheet, $pos) {
        $sheet->getStyle($pos)->getAlignment()->setHorizontal(Align::HORIZONTAL_LEFT);
    }

    //シートの文字色指定
    public function setColor($sheet, $pos, $font_color) {
        $objStyle = $sheet->getStyle($pos);
        $objStyle->getFont()->getColor()->setARGB($font_color);
    }

    //シートの背景色指定
    public function setBackColor($sheet, $pos, $back_color) {
        $objStyle = $sheet->getStyle($pos);
        $objFill = $objStyle->getFill();
        $objFill->setFillType(Fill::FILL_SOLID);
        $objFill->getStartColor()->setARGB($back_color);
    }

    // 文字の太字化を解除
    public function setNonBolder($sheet, $pos) {
        $styleArray = array(
            'font' => array(
                'bold' => false
            )
        );
        $sheet->getStyle($pos)->applyFromArray($styleArray);
    }

    // セルの結合
    public function mergeCell($sheet, $pos) {
        $sheet->mergeCells($pos);
    }

    // セルに入力
    public function setCell($sheet, $pos, $comment) {
        $sheet->setCellValue($pos, $comment);
    }

    /*
    * 特定のセルだけフォントサイズを変更する
    */
    public function setCellChangeSize($sheet, $pos, $value, $fontsize) {
        $RichText = new RichText();
        $RichText->createTextRun($value)->getFont()->setSize($fontsize);
        $sheet->getCell($pos)->setValue($RichText);
    }

    // 改行ありの文字列を出力
    public function setTextCell($sheet, $pos, $comment){
        $sheet->getStyle($pos)->getAlignment()->setWraptext(true);
        $sheet->getCell($pos)->setValue($comment);
    }

    // 列の挿入
    public function insertRow($sheet, $pos, $count) {
        $sheet->insertNewRowBefore($pos, $count);
    }

    // 印刷ページの指定
    public function setPrint($sheet, $pos) {
        $sheet->getPageSetup()->setPrintArea($pos);
    }

    //　幅と高さを1ページに収める
    public function setWidthHeightOnePage($sheet, $pos) {
        $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(1);
    }

    //　幅を1ページに収める
    public function setWidthOnePage($sheet, $pos) {
        $sheet->getPageSetup()->setFitToWidth(1);
    }

    // 印刷サイズをA4横に設定
    public function PrintSizeAFour($sheet) {
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    }

    //幅の自動調整
    public function setCellAutoAdjustment($sheet, $pos) {
        $sheet->getColumnDimension($pos)->setAutoSize(true);
    }

    // エクセルをダウンロード出力
    public function excelDownload($spreadsheet, $name) {
        $writer = new Xlsx($spreadsheet);

        // 出力バッファをクリアしておく
        ob_end_clean(); // this
        ob_start(); // and this

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename=\"{$name}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    //ファイルに保存
    public function saveFile($spreadsheet, $path) {
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
    }
}
