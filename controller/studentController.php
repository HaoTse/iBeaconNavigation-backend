<?php
//initialize
require_once '../configs/source.php';
require_once HOME_DIR . 'model/student.lib.php';

$student = new Student();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        switch ($action) {
            case 'addStudent':
                $student->addStudent($_POST);
                break;
            case 'updateStudent':
                $student->updateStudent($_POST);
                break;
        }
        break;
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : 'view';
        switch ($action) {
            case 'viewAddForm':
                $student->viewAddForm();
                break;
            case 'viewEditForm':
                $student->viewEditForm($_GET);
                break;
            case 'viewStudentList':
                $student->viewStudentList();
                break;
            case 'deleteStudent':
                $student->deleteStudent($_GET);
                break;
            case 'logout':
                $student->logout();
                break;
            case 'view':
            default:
                $student->viewLogin();
        }
        break;
}
