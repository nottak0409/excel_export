<?php

namespace excel;

require_once './vendor/autoload.php';
require_once './xls_function.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use excel\excel_function;

class excel_download
{
    public function excel_write(array $table_info, string $file_name)
    {
        // エクセル出力用
        $reader = new Xlsx();
        $spreadsheet = $reader->load("./template.xlsx");

        $excel_function_obj = new excel_function();
        $table_counts = 0;
        //テンプレートのシートは1つしかないので、テーブルの数に応じてテンプレートのシートをコピーして増やす
        foreach ($table_info as $table_name => $table) {
            if ($table_counts === 0) {
                $table_counts++;
                continue;
            }
            //新しく作成したテンプレートシートに名前を付ける
            $excel_function_obj->sheetcopy($spreadsheet, 0, $table_name);
            $table_counts++;
        }

        $table_counts = 0;
        //色の変更が必要なセルを配列化
        $color_column = ["A1", "A2", "B1", "B2", "C1", "C2", "H1", "H2", "I1", "I2", "A4", "A5", "B4", "B5", "C4", "C5", "D4", "D5", "E4", "E5", "F4", "F5", "G4", "G5", "H4", "H5", "I4", "I5", "J4", "J5"];
        foreach ($table_info as $table_name => $column_data) {
            $book = $spreadsheet->getSheet($table_counts);
            //1枚目のシートには名前がついていないので、テーブル名をシート名として名前を付ける
            if ($table_counts === 0) {
                $excel_function_obj->sheettitle($book, $table_name);
            }
            //物理名をつける
            $excel_function_obj->setCell($book, 'D2', $table_name);
            //エクセルのヘッダーに色を付ける
            foreach ($color_column as $soezi => $value) {
                //文字色,白 該当セルの色を変えたい場合は第三引数の指定をARGBで指定してください
                $excel_function_obj->setColor($book, $value, "FFFFFFFF");
                //背景色,青っぽいの　該当セルの色を変えたい場合は第三引数の指定をARGBで指定してください
                $excel_function_obj->setBackColor($book, $value, "FF1F9CAF");
            }
            $i = 6;
            //各カラム項目のデータを入れていく
            foreach ($column_data as $type => $value) {
                if (strpos($value["Type"], '(') !== false) {
                    $explode_type = explode('(', $value["Type"]);
                    $type = $explode_type[0];
                    $size = rtrim($explode_type[1], ')');
                } else {
                    $type = $value["Type"];
                    $size = null;
                }
                $excel_function_obj->setCell($book, 'B' . $i, $value["Field"]);
                $excel_function_obj->setCell($book, 'D' . $i, $type);
                $excel_function_obj->setCell($book, 'E' . $i, $type !== "enum" ? $size : "");
                $excel_function_obj->setCell($book, 'F' . $i, $type !== "enum" ? $value["Default"] : "");
                $excel_function_obj->setCell($book, 'G' . $i, $value["Null"] === "YES" ? "〇" : null);
                $excel_function_obj->setCell($book, 'H' . $i, $value["Key"] === "UNI" ? "〇" : null);
                $excel_function_obj->setCell($book, 'I' . $i, $value["Key"] === "PRI" ? "〇" : null);
                $excel_function_obj->setCell($book, 'J' . $i, $type === "enum" ? (!empty($value["Default"]) ? $value["Default"] . ":" . $size : $size) : $value["Comment"]);
                $i++;
            }
            $table_counts++;
        }

        //エクセルのファイル名を出力
        $bookname = $file_name . ".xlsx";

        // エクセルをダウンロード出力
        $excel_function_obj->excelDownload($spreadsheet, $bookname);
        exit;
    }
}
