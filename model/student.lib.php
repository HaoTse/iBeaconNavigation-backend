<?php
// initialize
require_once HOME_DIR . 'configs/config.php';

/**
 * Student類別
 */
class Student
{
    // database object
    public $db = null;
    // smarty template object
    public $smarty = null;
    // success messages
    public $msg = '';
    // error messages
    public $error = '';

    /**
     * class constructor
     * @DateTime 2016-11-06
     */
    public function __construct()
    {
        session_start();
        // instantiate the pdo object
        $this->db = dbSetup::getDbConn();
        // instantiate the template object
        $this->smarty = new SmartyConfig();
    }
    /**
     * 顯示login form
     * @DateTime 2016-11-06
     */
    public function viewLogin()
    {
        $_SESSION['isLogin'] = false;
        $this->smarty->assign('error', $this->error);
        $this->smarty->assign('homePath', APP_ROOT_DIR);
        $this->smarty->display('login.html');
    }
    /**
     * 顯示增加Student畫面
     * @DateTime 2016-11-06
     * @param    array     $input [未知]
     */
    public function viewAddForm()
    {
        if ($_SESSION['isLogin'] == true) {
            //get all exhibition_id
            $sql = "SELECT exhibition_id, name
                    FROM exhibition
                    ORDER BY exhibition_id";
            $res = $this->db->prepare($sql);
            $res->execute();
            $exhibition = $res->fetchAll();

            $this->smarty->assign('exhibition', $exhibition);
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('student/studentAdd.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 新增Student
     * @DateTime 2016-11-06
     * @param    array     $input [Student資料]
     */
    public function addStudent($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $now  = date('Y-m-d H:i:s');

                $sql = "INSERT INTO student (exhibition_id, name, phone, email, create_date, lastupdate_date)
                        VALUES (:exhibition_id, :name, :phone, :email, :createDate, :lastupdateDate)";
                $res = $this->db->prepare($sql);
                $res->bindParam(':exhibition_id', $input['exhibition_id'], PDO::PARAM_INT);
                $res->bindParam(':name', $input['name'], PDO::PARAM_STR);
                $res->bindValue(':phone', $input['phone'], PDO::PARAM_STR);
                $res->bindValue(':email', $input['email'], PDO::PARAM_STR);
                $res->bindParam(':createDate', $now, PDO::PARAM_STR);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);

                if ($res->execute()) {
                    $studentId      = $this->db->lastInsertId();
                    $this->error = '';
                    header('Location:../controller/studentController.php?action=viewStudentList');
                } else {
                    $error = $res->errorInfo();

                    $this->error = $error[0];
                    $this->viewAddForm();
                }
                
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage();
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 顯示編輯Student畫面
     * @DateTime 2016-11-06
     * @param    array     $input [StudentID]
     */
    public function viewEditForm($input)
    {
        if ($_SESSION['isLogin'] == true) {
            // get all data from student
            $sql = 'SELECT *
            		FROM student
            		WHERE student_id = :studentId';
            $res = $this->db->prepare($sql);
            $res->bindParam(':studentId', $input['studentId'], PDO::PARAM_INT);
            $res->execute();
            $studentData = $res->fetchAll();

            $this->smarty->assign('studentData', $studentData[0]);
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('student/studentEdit.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 更新Student資料
     * @DateTime 2016-11-06
     * @param    array     $input [Student資料]
     * @return   json             [Student更新後的資料]
     */
    public function updateStudent($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                // update data from student
                $sql = 'UPDATE student
                    SET name = :name, phone = :phone, email = :email
                    WHERE student_id = :studentId';
                $res = $this->db->prepare($sql);
                $res->bindParam(':name', $input['name'], PDO::PARAM_STR);
                $res->bindValue(':phone', $input['phone'], PDO::PARAM_STR);
                $res->bindValue(':email', $input['email'], PDO::PARAM_STR);
                $res->bindParam(':studentId', $input['studentId'], PDO::PARAM_INT);
                $res->execute();
                // 更新最後修改時間
                $now = date('Y-m-d H:i:s');
                $sql = "UPDATE student
                    SET lastupdate_date = :lastupdateDate
                    WHERE student_id = :studentId";
                $res = $this->db->prepare($sql);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);
                $res->bindParam(':studentId', $input['studentId'], PDO::PARAM_INT);
                $res->execute();

                $this->error = '';
                $this->msg   = '更新成功';
                $this->viewStudentList();
            } catch (PDOException $e) {
                print_r("Error!: " . $e->getMessage());
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 刪除Student
     * @DateTime 2016-11-06
     * @param    array     $input [Studentid]
     */
    public function deleteStudent($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $this->db->beginTransaction();
                $studentId = $input['studentId'];
                $sql    = "DELETE FROM student
                           WHERE student_id = $studentId";
                $this->db->exec($sql);
                $this->db->commit();

                $this->error = '';
                $this->msg   = '刪除成功';
                $this->viewStudentList();
            } catch (PDOException $e) {
                $this->db->rollBack();
                print "Error!: " . $e->getMessage();
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 顯示Student清單
     * @DateTime 2016-11-06
     */
    public function viewStudentList()
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = 'SELECT *
            			FROM student
            			ORDER BY student_id';
                $res = $this->db->prepare($sql);
                $res->execute();
                $studentData = $res->fetchAll();

                $this->smarty->assign('studentData', $studentData);
                $this->smarty->assign('msg', $this->msg);
                $this->smarty->assign('error', $this->error);
                $this->smarty->assign('homePath', APP_ROOT_DIR);
                $this->smarty->display('student/studentList.html');
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage();
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->viewLogin();
    }
}
