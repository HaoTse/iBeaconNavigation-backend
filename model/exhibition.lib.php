<?php
// initialize
require_once HOME_DIR . 'configs/config.php';

/**
 * Exhibition類別
 */
class Exhibition
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
     * 顯示增加Exhibition畫面
     * @DateTime 2016-11-06
     * @param    array     $input [未知]
     */
    public function viewAddForm()
    {
        if ($_SESSION['isLogin'] == true) {
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('exhibition/exhibitionAdd.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 新增Exhibition
     * @DateTime 2016-11-06
     * @param    array     $input [Exhibition資料]
     */
    public function addExhibition($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $now  = date('Y-m-d H:i:s');

                $sql = "INSERT INTO exhibition (name, introduction, x, y, teacher, email, create_date, lastupdate_date)
                        VALUES (:name, :introduction, :x, :y, :teacher, :email, :createDate, :lastupdateDate)";
                $res = $this->db->prepare($sql);
                $res->bindParam(':name', $input['name'], PDO::PARAM_STR);
                $res->bindParam(':introduction', $input['introduction'], PDO::PARAM_STR);
                $res->bindValue(':x', $input['x'], PDO::PARAM_INT);
                $res->bindValue(':y', $input['y'], PDO::PARAM_INT);
                $res->bindParam(':teacher', $input['teacher'], PDO::PARAM_STR);
                $res->bindParam(':email', $input['email'], PDO::PARAM_STR);
                $res->bindParam(':createDate', $now, PDO::PARAM_STR);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);

                if ($res->execute()) {
                    $exhibitionId      = $this->db->lastInsertId();
                    $this->error = '';
                    $this->msg   = '新增成功';

                    if(!empty($_FILES) && $_FILES["img_path"]["error"] == 0){
                        $file_tmp = substr($_FILES['img_path']['name'], 0, 5);
                            
                        $newFilePath = '../media/exhibition/'.uniqid($file_tmp, true);
                    
                        move_uploaded_file($_FILES['img_path']['tmp_name'], $newFilePath);//複製檔案
                        try {
                            $sql = "UPDATE exhibition
                                    SET img_path = :img_path
                                    WHERE exhibition_id = :exhibitionId";
                            $res = $this->db->prepare($sql);
                            $res->bindParam(':img_path', $newFilePath, PDO::PARAM_STR);
                            $res->bindParam(':exhibitionId', $exhibitionId, PDO::PARAM_INT);
                            if ($res->execute()) {
                                $this->error = '';
                                $this->msg = '新增成功';
                                $this->viewExhibitionList();
                            } else {
                                $error = $res->errorInfo();

                                $this->error = $error[0];
                                $this->viewExhibitionList();
                            }
                        } catch (PDOException $e) {
                            print "Error!: " . $e->getMessage();
                        }
                    } else{
                        $this->viewExhibitionList();
                    }

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
     * 顯示編輯Exhibition畫面
     * @DateTime 2016-11-06
     * @param    array     $input [ExhibitionID]
     */
    public function viewEditForm($input)
    {
        if ($_SESSION['isLogin'] == true) {
            // get all data from exhibition
            $sql = 'SELECT *
            		FROM exhibition
            		WHERE exhibition_id = :exhibitionId';
            $res = $this->db->prepare($sql);
            $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
            $res->execute();
            $exhibitionData = $res->fetchAll();

            $this->smarty->assign('exhibition', $exhibitionData[0]);
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('exhibition/exhibitionEdit.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 更新Exhibition資料
     * @DateTime 2016-11-06
     * @param    array     $input [Exhibition資料]
     * @return   json             [Exhibition更新後的資料]
     */
    public function updateExhibition($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                // update data from exhibition
                $sql = 'UPDATE exhibition
                    SET name = :name, introduction = :introduction , x = :x, y = :y, teacher = :teacher, email = :email
                    WHERE exhibition_id = :exhibitionId';
                $res = $this->db->prepare($sql);
                $res->bindParam(':name', $input['name'], PDO::PARAM_STR);
                $res->bindValue(':introduction', $input['introduction'], PDO::PARAM_STR);
                $res->bindValue(':x', $input['x'], PDO::PARAM_INT);
                $res->bindValue(':y', $input['y'], PDO::PARAM_INT);
                $res->bindValue(':teacher', $input['teacher'], PDO::PARAM_STR);
                $res->bindValue(':email', $input['email'], PDO::PARAM_STR);
                $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
                $res->execute();
                // 更新最後修改時間
                $now = date('Y-m-d H:i:s');
                $sql = "UPDATE exhibition
                    SET lastupdate_date = :lastupdateDate
                    WHERE exhibition_id = :exhibitionId";
                $res = $this->db->prepare($sql);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);
                $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
                $res->execute();

                if(!empty($_FILES) && $_FILES["img_path"]["error"] == 0){

                    $sql = 'SELECT img_path
                        FROM exhibition
                        WHERE exhibition_id = :exhibitionId';
                    $res = $this->db->prepare($sql);
                    $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
                    $res->execute();
                    $file = $res->fetchAll();

                    $file_tmp = substr($_FILES['img_path']['name'], 0, 5);

                    $newFilePath = '../media/exhibition/'.uniqid($file_tmp, true);
                
                    move_uploaded_file($_FILES['img_path']['tmp_name'], $newFilePath);//複製檔案
                    try {
                        $sql = "UPDATE exhibition
                                SET img_path = :img_path
                                WHERE exhibition_id = :exhibitionId";

                        $res = $this->db->prepare($sql);
                        $res->bindParam(':img_path', $newFilePath, PDO::PARAM_STR);
                        $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
                        if ($res->execute()) {

                            if(strpos($file[0]['img_path'], 'default') === false)
                                unlink($file[0]['img_path']);
                            
                            $this->error = '';
                            $this->msg = '更新成功';
                            $this->viewExhibitionList();
                        } else {
                            $error = $res->errorInfo();

                            $this->error = $error[0];
                            $this->viewExhibitionList();
                        }
                    } catch (PDOException $e) {
                        print "Error!: " . $e->getMessage();
                    }
                } else{
                    $this->viewExhibitionList();
                }
            } catch (PDOException $e) {
                print_r("Error!: " . $e->getMessage());
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 刪除Exhibition
     * @DateTime 2016-11-06
     * @param    array     $input [Exhibitionid]
     */
    public function deleteExhibition($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = 'SELECT img_path
                    FROM exhibition
                    WHERE exhibition_id = :exhibitionId';
                $res = $this->db->prepare($sql);
                $res->bindParam(':exhibitionId', $input['exhibitionId'], PDO::PARAM_INT);
                $res->execute();
                $file = $res->fetchAll();

                $this->db->beginTransaction();
                $exhibitionId = $input['exhibitionId'];
                $sql    = "DELETE FROM exhibition
                           WHERE exhibition_id = $exhibitionId";
                $this->db->exec($sql);
                $this->db->commit();

                if(strpos($file[0]['img_path'], 'default') === false)
                    unlink($file[0]['img_path']);

                $this->error = '';
                $this->msg   = '刪除成功';
                $this->viewExhibitionList();
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
     * 顯示Exhibition清單
     * @DateTime 2016-11-06
     */
    public function viewExhibitionList()
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = 'SELECT *
            			FROM exhibition
            			ORDER BY exhibition_id';
                $res = $this->db->prepare($sql);
                $res->execute();
                $exhibitionData = $res->fetchAll();

                $this->smarty->assign('exhibitionData', $exhibitionData);
                $this->smarty->assign('msg', $this->msg);
                $this->smarty->assign('error', $this->error);
                $this->smarty->assign('homePath', APP_ROOT_DIR);
                $this->smarty->display('exhibition/exhibitionList.html');
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
