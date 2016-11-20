/**
 * [刪除學生]
 * @DateTime 2016-09-06
 * @param    {int}   studentId [StudentID]
 * @return   {bool}         [是否成功]
 */
function deleteStudent(studentId) {
    BootstrapDialog.show({
        title: '提醒',
        message: '確定刪除學生嗎?',
        buttons: [{
            label: '確定',
            cssClass: 'btn-danger',
            action: function() {
                location = '../controller/studentController.php?action=deleteStudent&studentId=' + studentId;
            }
        }, {
            label: '取消',
            action: function(dialogItself) {
                dialogItself.close();
            }
        }]
    });
}
