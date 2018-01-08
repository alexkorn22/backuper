<?
function debug($value, $die = false) {
    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/","\\",$dRoot);
    $bt["file"] = str_replace($dRoot,"",$bt["file"]);
    $dRoot = str_replace("\\","/",$dRoot);
    $bt["file"] = str_replace($dRoot,"",$bt["file"]);
    $res = "<div style=\"font-size: 9pt; color: #000; background: #fff; border: 1px dashed #000;\">";
    $res .= "<div style=\"padding: 3px 5px; background: #99CCFF; font-weight: bold;\">File: " . $bt["file"] . " [" . $bt["line"] . "]</div>";
    $res .= " <pre style=\"padding: 10px;\">" . print_r($value,true) . "</pre>";
    $res .= "</div>";
    echo $res;
    if ($die) {
        die();
    }
}
function debDie($value) {
    debug($value, true);
}

function fsplit($file, $outputFolder, $buffer=1024) {
    //open file to read
    $file_handle = fopen($file,'r');
    //get file size
    $file_size = filesize($file);
    //no of parts to split
    $parts = $file_size / $buffer;

    //store all the file names
    $file_parts = array();

    //path to write the final files
    $store_path = "splits/";

    //name of input file
    $file_name = basename($file);

    for($i=0;$i<$parts;$i++){
        //read buffer sized amount from file
        $file_part = fread($file_handle, $buffer);
        //the filename of the part
        $file_part_path = $store_path.$file_name.".part$i";
        //open the new file [create it] to write
        $file_new = fopen($file_part_path,'w+');
        //write the part of file
        fwrite($file_new, $file_part);
        //add the name of the file to part list [optional]
        array_push($file_parts, $file_part_path);
        //close the part file handle
        fclose($file_new);
    }
    //close the main file handle

    fclose($file_handle);
    return $file_parts;
}

// https://books.google.com.ua/books?id=2EW6AgAAQBAJ&pg=PA172&lpg=PA172&dq=php+%D1%80%D0%B0%D0%B7%D0%B1%D0%B8%D0%B5%D0%BD%D0%B8%D0%B5+%D1%84%D0%B0%D0%B9%D0%BB%D0%BE%D0%B2+fread&source=bl&ots=L7U-Io7Cq5&sig=l9cmB9WyfNiumPhN6n8ekPAFdhE&hl=ru&sa=X&ved=0ahUKEwjZ7_HMrMjYAhVBEJoKHc8sCvcQ6AEINTAC#v=onepage&q&f=false