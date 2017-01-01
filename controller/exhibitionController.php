<?php
//initialize
require_once '../configs/source.php';
require_once HOME_DIR . 'model/exhibition.lib.php';

$exhibition = new Exhibition();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        switch ($action) {
            case 'addExhibition':
                $exhibition->addExhibition($_POST);
                break;
            case 'updateExhibition':
                $exhibition->updateExhibition($_POST);
                break;
        }
        break;
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : 'view';
        switch ($action) {
            case 'viewAddForm':
                $exhibition->viewAddForm();
                break;
            case 'viewEditForm':
                $exhibition->viewEditForm($_GET);
                break;
            case 'viewExhibitionList':
                $exhibition->viewExhibitionList();
                break;
            case 'deleteExhibition':
                $exhibition->deleteExhibition($_GET);
                break;
            case 'getExhibitionData':
                $exhibition->getExhibitionData();
                break;
            case 'logout':
                $exhibition->logout();
                break;
            case 'view':
            default:
                $exhibition->viewLogin();
        }
        break;
}
