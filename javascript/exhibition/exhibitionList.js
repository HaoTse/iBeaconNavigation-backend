/**
 * [刪除Exhibition]
 * @DateTime 2016-09-06
 * @param    {int}   exhibitionId [ExhibitionID]
 * @return   {bool}         [是否成功]
 */
function deleteExhibition(exhibitionId) {
    BootstrapDialog.show({
        title: '提醒',
        message: '確定刪除專題攤位嗎?',
        buttons: [{
            label: '確定',
            cssClass: 'btn-danger',
            action: function() {
                location = '../controller/exhibitionController.php?action=deleteExhibition&exhibitionId=' + exhibitionId;
            }
        }, {
            label: '取消',
            action: function(dialogItself) {
                dialogItself.close();
            }
        }]
    });
}
