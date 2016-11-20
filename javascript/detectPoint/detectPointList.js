/**
 * [刪除使用者]
 * @DateTime 2016-09-06
 * @param    {int}   detectPointId [DetectPointID]
 * @return   {bool}         [是否成功]
 */
function deleteDetectPoint(detectPointId) {
    BootstrapDialog.show({
        title: '提醒',
        message: '確定刪除已知點嗎?',
        buttons: [{
            label: '確定',
            cssClass: 'btn-danger',
            action: function() {
                location = '../controller/detectPointController.php?action=deleteDetectPoint&detectPointId=' + detectPointId;
            }
        }, {
            label: '取消',
            action: function(dialogItself) {
                dialogItself.close();
            }
        }]
    });
}
