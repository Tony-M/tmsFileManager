<?php
header('Content-Type: text/html; charset=utf8');

define('ROOT_PATH', dirname(__FILE__));
require_once('lib/tmsFileManager.php');

$path = (isset($_POST['path'])?$_POST['path']:(isset($_GET['path'])?$_GET['path']:''));
$act = (isset($_POST['act'])?$_POST['act']:(isset($_GET['act'])?$_GET['act']:''));


try{
    tmsFileManager::setPath($path);

    switch ($act){
        default:
            tmsFileManager::scan();
            break;
        case 'download':
            tmsFileManager::download();
            exit;
            break;
        case 'remove':
            tmsFileManager::remove();
            exit;
            break;
    }

}catch (Exception $e){

}

require_once('templates/indexSuccess.php');