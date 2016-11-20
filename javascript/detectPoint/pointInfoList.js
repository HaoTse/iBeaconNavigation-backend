/**
 * [刪除已知點資訊]
 * @DateTime 2016-11-06
 * @param    {int}   pointInfo [point_id]
 * @param    {int}   pointInfo [beacon_id]
 * @return   {bool}         [是否成功]
 */
function deletePointInfo(point_id, beacon_id) {
    BootstrapDialog.show({
        title: '提醒',
        message: '確定刪除已知點資訊嗎?',
        buttons: [{
            label: '確定',
            cssClass: 'btn-danger',
            action: function() {
                location = '../controller/detectPointController.php?action=deletePointInfo&detectPointId=' + point_id + '&beacon_id=' + beacon_id;
            }
        }, {
            label: '取消',
            action: function(dialogItself) {
                dialogItself.close();
            }
        }]
    });
}
