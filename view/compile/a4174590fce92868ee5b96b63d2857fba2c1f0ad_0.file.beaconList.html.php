<?php
/* Smarty version 3.1.28, created on 2016-09-12 11:30:23
  from "C:\xampp\htdocs\backend\web\view\beacon\beaconList.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_57d6214fd7b277_85208956',
  'file_dependency' => 
  array (
    'a4174590fce92868ee5b96b63d2857fba2c1f0ad' => 
    array (
      0 => 'C:\\xampp\\htdocs\\backend\\web\\view\\beacon\\beaconList.html',
      1 => 1473612280,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/resource.html' => 1,
    'file:../common/header.html' => 1,
    'file:../common/menu.html' => 1,
    'file:../common/footer.html' => 1,
  ),
),false)) {
function content_57d6214fd7b277_85208956 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>

<head>
    <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../common/resource.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../common/header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../common/menu.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1></h1>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <?php if ($_smarty_tpl->tpl_vars['error']->value != '') {?>
                        <div class="alert alert-warning alert-dismissable text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4 style="display: inline;"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</h4>
                        </div>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['msg']->value != '') {?>
                        <div class="alert alert-success alert-dismissable text-center">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4 style="display: inline;"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</h4>
                        </div>
                        <?php }?>
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title">Beacon列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr style="background-color: #00c0ef;color: #fff;">
                                            <th>ID</th>
                                            <th>Beacon Mac</th>
                                            <th>名稱</th>
                                            <th>電量</th>
                                            <th>隸屬分區編號</th>
                                            <th>紀錄佈建狀態</th>
                                            <th>X座標</th>
                                            <th>Y座標</th>
                                            <th>註冊時間</th>
                                            <th>最後更新</th>
                                            <th>操作選項</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
$_from = $_smarty_tpl->tpl_vars['beaconData']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_beacon_0_saved_item = isset($_smarty_tpl->tpl_vars['beacon']) ? $_smarty_tpl->tpl_vars['beacon'] : false;
$_smarty_tpl->tpl_vars['beacon'] = new Smarty_Variable();
$__foreach_beacon_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_beacon_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['beacon']->value) {
$__foreach_beacon_0_saved_local_item = $_smarty_tpl->tpl_vars['beacon'];
?>
                                        <tr>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['beacon_id'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['mac_addr'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['name'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['power'];?>
</td>
                                            <?php if ($_smarty_tpl->tpl_vars['beacon']->value['zone'] == 9) {?>
                                            <td>9</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['zone'] == 10) {?>
                                            <td>10</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['zone'] == 11) {?>
                                            <td>11</td>
                                            <?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['beacon']->value['status'] == 0) {?>
                                            <td>0</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['status'] == 1) {?>
                                            <td>1</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['status'] == 2) {?>
                                            <td>2</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['status'] == 3) {?>
                                            <td>3</td>
                                            <?php } elseif ($_smarty_tpl->tpl_vars['beacon']->value['status'] == 4) {?>
                                            <td>4</td>
                                            <?php }?>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['x'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['y'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['create_date'];?>
</td>
                                            <td><?php echo $_smarty_tpl->tpl_vars['beacon']->value['lastupdate_date'];?>
</td>
                                            <td>
                                                <a href="../controller/beaconController.php?action=viewEditForm&beaconId=<?php echo $_smarty_tpl->tpl_vars['beacon']->value['beacon_id'];?>
">
                                                    <button class="btn btn-sm btn-success"><i class="glyphicon glyphicon-pencil"></i></button>
                                                </a>&nbsp;
                                                <a href="#">
                                                    <button class="btn btn-sm btn-danger" onclick="deleteBeacon(<?php echo $_smarty_tpl->tpl_vars['beacon']->value['beacon_id'];?>
);"><i class="glyphicon glyphicon-remove"></i></button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
$_smarty_tpl->tpl_vars['beacon'] = $__foreach_beacon_0_saved_local_item;
}
}
if ($__foreach_beacon_0_saved_item) {
$_smarty_tpl->tpl_vars['beacon'] = $__foreach_beacon_0_saved_item;
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:../common/footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    </div>
    <?php echo '<script'; ?>
 src="../javascript/beaconList.js"><?php echo '</script'; ?>
>
</body>

</html>
<?php }
}
