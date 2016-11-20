<?php
// initialize
require_once HOME_DIR . 'configs/config.php';

/**
 * DetectPoint類別
 */
class DetectPoint
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
     * 顯示增加DetectPoint畫面
     * @DateTime 2016-11-06
     * @param    array     $input [未知]
     */
    public function viewAddForm()
    {
        if ($_SESSION['isLogin'] == true) {
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('detectPoint/detectPointAdd.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 顯示增加PointInfo畫面
     * @DateTime 2016-11-06
     * @param    array     $input [未知]
     */
    public function viewAddInfoForm()
    {
        if ($_SESSION['isLogin'] == true) {
            //get all point_id
            $sql = "SELECT point_id
                    FROM detect_point
                    ORDER BY point_id";
            $res = $this->db->prepare($sql);
            $res->execute();
            $pointId = $res->fetchAll();

            //get all beacon_id
            $sql = "SELECT beacon_id, name
                    FROM ibeacon
                    ORDER BY beacon_id";
            $res = $this->db->prepare($sql);
            $res->execute();
            $beaconId = $res->fetchAll();

            $this->smarty->assign('pointId', $pointId);
            $this->smarty->assign('beaconId', $beaconId);
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('msg', $this->msg);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('detectPoint/pointInfoAdd.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 新增DetectPoint
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPoint資料]
     */
    public function addDetectPoint($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                
                $now  = date('Y-m-d H:i:s');

                $sql = "INSERT INTO detect_point (x, y, create_date, lastupdate_date)
                        VALUES (:x, :y, :createDate, :lastupdateDate)";
                $res = $this->db->prepare($sql);
                $res->bindValue(':x', $input['x'], PDO::PARAM_INT);
                $res->bindValue(':y', $input['y'], PDO::PARAM_INT);
                $res->bindParam(':createDate', $now, PDO::PARAM_STR);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);

                if ($res->execute()) {
                    $detectPointId      = $this->db->lastInsertId();
                    $this->error = '';
                    $this->viewDetectPointList();
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
     * 新增PointInfo
     * @DateTime 2016-11-06
     * @param    array     $input [PointInfo資料]
     */
    public function addPointInfo($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = "SELECT *
                        FROM point_info
                        WHERE point_id = :point_id
                        AND beacon_id = :beacon_id";
                $res = $this->db->prepare($sql);
                $res->bindParam(':point_id', $input['point_id'], PDO::PARAM_INT);
                $res->bindParam(':beacon_id', $input['beacon_id'], PDO::PARAM_INT);
                $res->execute();
                $rows = $res->fetchAll();
                if (count($rows) == 1) {
                    $this->error = '此組合已登記';
                    $this->viewAddInfoForm();
                } else {

                    $sql = "INSERT INTO point_info (point_id, beacon_id, rssi)
                            VALUES (:point_id, :beacon_id, :rssi)";
                    $res = $this->db->prepare($sql);
                    $res->bindValue(':point_id', $input['point_id'], PDO::PARAM_INT);
                    $res->bindValue(':beacon_id', $input['beacon_id'], PDO::PARAM_INT);
                    $res->bindValue(':rssi', $input['rssi'], PDO::PARAM_INT);

                    if ($res->execute()) {
                        $this->error = '';
                        $this->msg   = '新增成功';
                        $this->viewAddInfoForm();
                    } else {
                        $error = $res->errorInfo();

                        $this->error = $error[0];
                        $this->viewAddInfoForm();
                        
                    }
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
     * 顯示編輯DetectPoint畫面
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPointID]
     */
    public function viewEditForm($input)
    {
        if ($_SESSION['isLogin'] == true) {
            // get all data from detectPoint
            $sql = 'SELECT *
            		FROM detect_point
            		WHERE point_id = :detectPointId';
            $res = $this->db->prepare($sql);
            $res->bindParam(':detectPointId', $input['detectPointId'], PDO::PARAM_INT);
            $res->execute();
            $detectPointData = $res->fetchAll();

            $this->smarty->assign('detectPointData', $detectPointData[0]);
            $this->smarty->assign('error', $this->error);
            $this->smarty->assign('homePath', APP_ROOT_DIR);
            $this->smarty->display('detectPoint/detectPointEdit.html');
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 更新DetectPoint資料
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPoint資料]
     * @return   json             [DetectPoint更新後的資料]
     */
    public function updateDetectPoint($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                // update data from detectPoint
                $sql = 'UPDATE detect_point
                    SET x = :x, y = :y
                    WHERE point_id = :detectPointId';
                $res = $this->db->prepare($sql);
                $res->bindValue(':x', $input['x'], PDO::PARAM_INT);
                $res->bindValue(':y', $input['y'], PDO::PARAM_INT);
                $res->bindParam(':detectPointId', $input['detectPointId'], PDO::PARAM_INT);
                $res->execute();
                // 更新最後修改時間
                $now = date('Y-m-d H:i:s');
                $sql = "UPDATE detect_point
                    SET lastupdate_date = :lastupdateDate
                    WHERE point_id = :detectPointId";
                $res = $this->db->prepare($sql);
                $res->bindParam(':lastupdateDate', $now, PDO::PARAM_STR);
                $res->bindParam(':detectPointId', $input['detectPointId'], PDO::PARAM_INT);
                $res->execute();

                $this->error = '';
                $this->viewDetectPointList();
            } catch (PDOException $e) {
                print_r("Error!: " . $e->getMessage());
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }

    /**
     * 刪除DetectPoint
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPointid]
     */
    public function deleteDetectPoint($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $this->db->beginTransaction();
                $detectPointId = $input['detectPointId'];
                $sql    = "DELETE detect_point, point_info FROM detect_point
                           INNER JOIN point_info
                           WHERE detect_point.point_id = point_info.point_id
                           AND detect_point.point_id = $detectPointId";
                $this->db->exec($sql);
                $this->db->commit();

                $this->error = '';
                $this->msg   = '刪除成功';
                $this->viewDetectPointList();
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
     * 刪除PointInfo
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPointid, beacon_id]
     */
    public function deletePointInfo($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $this->db->beginTransaction();
                $detectPointId = $input['detectPointId'];
                $beacon_id = $input['beacon_id'];
                $sql    = "DELETE FROM point_info
                           WHERE point_id = $detectPointId
                           AND beacon_id = $beacon_id";
                $this->db->exec($sql);
                $this->db->commit();

                $this->error = '';
                $this->msg   = '刪除成功';
                header('Location:../controller/detectPointController.php?action=viewPointInfoList&detectPointId=' . $detectPointId);
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
     * 顯示DetectPoint清單
     * @DateTime 2016-11-06
     */
    public function viewDetectPointList()
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = 'SELECT *
            			FROM detect_point
            			ORDER BY point_id';
                $res = $this->db->prepare($sql);
                $res->execute();
                $detectPointData = $res->fetchAll();

                $this->smarty->assign('detectPointData', $detectPointData);
                $this->smarty->assign('msg', $this->msg);
                $this->smarty->assign('error', $this->error);
                $this->smarty->assign('homePath', APP_ROOT_DIR);
                $this->smarty->display('detectPoint/detectPointList.html');
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage();
            }
        } else {
            $this->error = '請先登入!';
            $this->viewLogin();
        }
    }
    /**
     * 顯示PointInfo清單
     * @DateTime 2016-11-06
     * @param    array     $input [DetectPointID]
     */
    public function viewPointInfoList($input)
    {
        if ($_SESSION['isLogin'] == true) {
            try {
                $sql = 'SELECT *
                        FROM point_info
                        LEFT JOIN ibeacon on ibeacon.beacon_id = point_info.beacon_id
                        WHERE point_id = :point_id
                        ORDER BY point_info.beacon_id';
                $res = $this->db->prepare($sql);
                $res->bindParam(':point_id', $input['detectPointId'], PDO::PARAM_INT);
                $res->execute();
                $pointInfoData = $res->fetchAll();

                $this->smarty->assign('pointInfoData', $pointInfoData);
                $this->smarty->assign('msg', $this->msg);
                $this->smarty->assign('error', $this->error);
                $this->smarty->assign('homePath', APP_ROOT_DIR);
                $this->smarty->display('detectPoint/pointInfoList.html');
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
