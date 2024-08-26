<?php

namespace App\Helpers;

use PhpOffice\PhpWord\PhpWord;

class WordHelper
{
    // Konversi dan merge dokumen
    public function mergeDocuments($files)
    {
        // Buat objek PhpWord baru untuk dokumen yang digabung
        $phpWord = new PhpWord;

        // Fungsi untuk menambahkan konten dari dokumen ke section
        foreach ($files as $file) {
            // dd($file);
            $this->addContentFromDocx($phpWord, $file);
        }
        // dd(1);
        // dd($phpWord);
        // Simpan dokumen yang digabungkan ke file sementara
        $tempFile = storage_path('app/public/temp_document.docx');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        dd($objWriter->save($tempFile));

        return $tempFile; // Kembalikan path file dokumen yang digabung
    }

    // Fungsi untuk menambahkan konten dari file DOCX ke objek PhpWord
    private function addContentFromDocx($phpWord, $filePath)
    {
        $source = \PhpOffice\PhpWord\IOFactory::load($filePath);

        foreach ($source->getSections() as $section) {
            $newSection = $phpWord->addSection();
            foreach ($section->getElements() as $element) {
                $this->copyElement($newSection, $element);
            }
        }
    }

    // Fungsi untuk menyalin elemen ke section baru
    private function copyElement($newSection, $element)
    {
        $type = get_class($element);

        switch ($type) {
            case 'PhpOffice\PhpWord\Element\TextRun':
                $textRun = $newSection->addTextRun($element->getParagraphStyle());
                foreach ($element->getElements() as $childElement) {
                    if (method_exists($childElement, 'getText')) {
                        $textRun->addText($childElement->getText(), $childElement->getFontStyle(), $childElement->getParagraphStyle());
                    }
                }
                break;
            case 'PhpOffice\PhpWord\Element\Text':
                $newSection->addText($element->getText(), $element->getFontStyle(), $element->getParagraphStyle());
                break;
            case 'PhpOffice\PhpWord\Element\Title':
                $newSection->addTitle($element->getText(), $element->getDepth());
                break;
            case 'PhpOffice\PhpWord\Element\Image':
                $newSection->addImage($element->getSource(), $element->getStyle());
                break;
            case 'PhpOffice\PhpWord\Element\Link':
                $newSection->addLink($element->getSource(), $element->getText(), $element->getFontStyle(), $element->getParagraphStyle());
                break;
            case 'PhpOffice\PhpWord\Element\Table':
                $newTable = $newSection->addTable($element->getStyle());
                foreach ($element->getRows() as $row) {
                    $tableRow = $newTable->addRow();
                    foreach ($row->getCells() as $cell) {
                        $tableCell = $tableRow->addCell();
                        foreach ($cell->getElements() as $cellElement) {
                            $this->copyElement($tableCell, $cellElement);
                        }
                    }
                }
                break;
            default:
                break;
        }
    }
}
