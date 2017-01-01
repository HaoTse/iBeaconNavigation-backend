<?php
//initialize
require_once '../configs/source.php';
require_once HOME_DIR . 'model/detectPoint.lib.php';

$detectPoint = new DetectPoint();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        switch ($action) {
            case 'addDetectPoint':
                $detectPoint->addDetectPoint($_POST);
                break;
            case 'addPointInfo':
                $detectPoint->addPointInfo($_POST);
                break;
            case 'updateDetectPoint':
                $detectPoint->updateDetectPoint($_POST);
                break;
        }
        break;
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : 'view';
        switch ($action) {
            case 'viewAddForm':
                $detectPoint->viewAddForm();
                break;
            case 'viewAddInfoForm':
                $detectPoint->viewAddInfoForm();
                break;
            case 'viewEditForm':
                $detectPoint->viewEditForm($_GET);
                break;
            case 'viewDetectPointList':
                $detectPoint->viewDetectPointList();
                break;
            case 'viewPointInfoList':
                $detectPoint->viewPointInfoList($_GET);
                break;
            case 'deleteDetectPoint':
                $detectPoint->deleteDetectPoint($_GET);
                break;
            case 'deletePointInfo':
                $detectPoint->deletePointInfo($_GET);
                break;
            case 'getPointData':
                $detectPoint->getPointData();
                break;
            case 'logout':
                $detectPoint->logout();
                break;
            case 'view':
            default:
                $detectPoint->viewLogin();
        }
        break;
}
