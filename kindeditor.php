<?php
/*
Plugin Name: Kindeditor For Wordpress
Plugin URI: http://www.panxianhai.com/kindeditor-for-wordpress.html
Description: kindeditor是一款轻量级的在线编辑器。
Version: 1.2
Author: hevin
Author URI: http://www.panxianhai.com/
*/

$version = 1.2;

require_once('kindeditor_class.php');
add_action('personal_options_update', array(&$kindeditor, 'user_personalopts_update'));
add_action('admin_head', array(&$kindeditor, 'add_admin_head'));
add_action('edit_form_advanced', array(&$kindeditor, 'load_kindeditor'));
add_action('edit_page_form', array(&$kindeditor, 'load_kindeditor'));
add_action('simple_edit_form', array(&$kindeditor, 'load_kindeditor'));
add_action('admin_print_styles', array(&$kindeditor, 'add_admin_style'));
add_action('admin_print_scripts', array(&$kindeditor, 'add_admin_js'));
if ( get_option('ke_auto_highlight') == 'yes' )
{
    add_action('wp_enqueue_scripts', array(&$kindeditor, 'add_head_script'));
    add_action('wp_enqueue_scripts', array(&$kindeditor, 'add_head_style'));
}
register_activation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$kindeditor, 'activate'));
register_deactivation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$kindeditor, 'deactivate'));

// Option page
function kindeditor_option_page()
{

    //var_dump(check_admin_referer('ke_admin_options-update'));exit;
    if ( !empty($_POST) && check_admin_referer('ke_admin_options-update') )
    {
        update_option('ke_auto_highlight', $_POST['ke_highlight']);
        update_option('ke_version', $_POST['ke_version']);
        ?>
    <div id="message" class="updated">保存成功...</div>
    <?php
    }
    $checked = '';
    if ( get_option('ke_auto_highlight') == 'yes' )
    {
        $checked = "checked = 'checked'";
    }
    $version_checked = "checked = 'checked'";
    if ( get_option('ke_version') != 'yes' ) {
        $version_checked = '';
    }
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Kindeditor Options</h2>
        <p>欢迎来到Kindeditor for wordpress的设置页面。</p>
        <form action="" method="post" id="kindeditor-options-form">
            <h3><label for="ke_highlight">开启前台高亮:</label>
            <input type="checkbox" id="ke_highlight" name="ke_highlight" <?php echo $checked;?> value="yes" />
            </h3>
            <h3><label for="ke_highlight">是否提示更新(选中为提示):</label>
                <input type="checkbox" id="ke_version" name="ke_version" <?php echo $version_checked;?> value="yes" />
            </h3>
        <p><input type="submit" name="submit" value="保存设置" /></p>
        <?php wp_nonce_field('ke_admin_options-update'); ?>
        </form>
    </div>
    <?php
}

function kindeditor_plugin_menu()
{
    add_options_page('kindeditor settings', 'Kindeditor设置','manage_options', 'kindeditor-plugin', 'kindeditor_option_page');
}

add_action('admin_menu', 'kindeditor_plugin_menu');

// 控制面板更新提示
function kindeditor_admin_notice()
{
    global $version;
    $str = file_get_contents('http://getversion.sinaapp.com/index.php?go=kindeditor');
    $update_url = "https://github.com/panxianhai/kindeditor-for-wordpress";
    $ke = json_decode($str);

    if ($ke->version > $version) {
        if($ke->status == 'beta')
            $msg = "kindeditor插件有测试版发布,<a href='{$update_url}' target='_blank'>查看详情</a>";
        else
            $msg = "kindeditor插件有新的稳定版发布,<a href='{$update_url}' target='_blank'>查看详情</a>或者等待后台提示自动更新";

        echo "<div class='updated'>
            <p>{$msg}</p>
        </div>";
    }
}
if( get_option('ke_version') == 'yes' )
    add_action('admin_notices', 'kindeditor_admin_notice');

