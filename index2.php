<?php
require_once 'vendor/autoload.php';
require_once 'DocxConversion.php';
    function addtext($doc_file_path,$data)
    {

        $doc1=new DocxConversion($doc_file_path);
        $text=$doc1->convertToText();
        $phpword= new \PhpOffice\PhpWord\PhpWord();

        $newString=$text.$data;

        echo $doc_file_path."\n";
        $section=$phpword->addsection();
        $section->getStyle()->setBreakType('continuous');
        $section->addText($newString);


        $objWriter=\PhpOffice\PhpWord\IOFactory::createWriter($phpword, "Word2007");
        $objWriter->save($doc_file_path);
        return $newString;
    }
?>