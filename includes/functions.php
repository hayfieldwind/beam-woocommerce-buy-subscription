<?php
function bwbs_dd($msg){
    die('<pre>'.print_r($msg, true).'</pre>');
}

function bwbs_bwg_AddLog($action, $object){

    $log_dir_path = WP_PLUGIN_DIR . '/beam-gateway-otp/logs';

    $log_file_path = $log_dir_path . "/log_".date('d-M-Y').".txt";

    if(!is_dir($log_dir_path))
        mkdir($log_dir_path.'/', 0777, TRUE) || die("Could not create directory");

    $log_entry = "[".date("d-M-Y H:i:s")."] ";
    $log_entry .= "Beam Woocommerce Payment Gateway | Action: ".$action . " | Message: ";
	$log_entry .= print_r( $object, true )." ".PHP_EOL;

    // echo "path: ".$log_file_path." entry: ".$log_entry;

    file_put_contents($log_file_path, $log_entry."\n", FILE_APPEND) || die("Could not create log");

}
function bwbs_printnice($msg){
    return "<pre>".print_r($msg, true)."</pre>";
}