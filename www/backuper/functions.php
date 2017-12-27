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