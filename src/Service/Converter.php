<?php
namespace App\Service;
class Converter {

    public $inputfile;
    public $outputfile;


    public function convert($inputfile, $outputfile)
    {
        $ecode;
        $output=[];
        $this->inputfile = $inputfile;
        $this->outputfile = $outputfile;
        exec("ffmpeg -i $inputfile -f mp3 {$outputfile}");
    }
}
