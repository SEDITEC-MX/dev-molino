<?php

// echo "hola";


// session_start();
//     if (!isset($_SESSION['authenticated'])) {
//         exit;
//     }
    $file = './../uploads/dummy.pdf';
    // if (file_exists($file)){
    //     echo "hola";
    // } else{
    //     echo "no";
    // }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
?>