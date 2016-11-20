<?php
/* Smarty version 3.1.30, created on 2016-11-05 22:46:09
  from "/var/www/html/iBeaconNavigation/view/common/header.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_581df0b1351fe2_45529166',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df57103dd308707463fe91e7565ffcdcf42c53a2' => 
    array (
      0 => '/var/www/html/iBeaconNavigation/view/common/header.html',
      1 => 1478351227,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_581df0b1351fe2_45529166 (Smarty_Internal_Template $_smarty_tpl) {
?>
<header class="main-header">
    <!-- Logo -->
    <div class="logo">
    </div>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php if ($_SESSION['isLogin']) {?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="../image/living3_icon.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $_SESSION['user']['email'];?>
</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="../controller/userController.php?action=logout" class="btn btn-default btn-flat">登出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php }?>
            </ul>
        </div>
    </nav>
</header>
<?php }
}
