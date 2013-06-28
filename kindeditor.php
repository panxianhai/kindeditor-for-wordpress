<?php
/*
Plugin Name: Kindeditor For Wordpress
Plugin URI: https://github.com/panxianhai/kindeditor-for-wordpress
Description: kindeditor是一款轻量级的在线编辑器。
Version: 1.3.6
Author: hevin
Author URI: http://weibo.com/hevinpan
*/

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
    if ( !empty($_POST) && check_admin_referer('ke_admin_options-update') ) {
        update_option('ke_auto_highlight', $_POST['ke_highlight']);
        update_option('ke_highlight_type', $_POST['ke_highlight_type']);
        echo '<div id="message" class="updated">保存成功...</div>';
    }
    if ( get_option('ke_auto_highlight') == 'yes' )
        $checked = "checked = 'checked'";
    $type = get_option('ke_highlight_type');
    $$type = "selected = 'selected'";
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Kindeditor Options</h2>
        <p>欢迎来到Kindeditor for wordpress的设置页面。</p>
        <p><a href="https://github.com/panxianhai/kindeditor-for-wordpress/issues">提交问题</a> </p>
        <form action="" method="post" id="kindeditor-options-form">
            <p><label for="ke_highlight">开启前台高亮:</label>
            <input type="checkbox" id="ke_highlight" name="ke_highlight" <?php echo $checked;?> value="yes" />
            </p>
            <p>
                高亮样式：
                <select name="ke_highlight_type">
                    <option value="prettify" <?php echo $prettify; ?>>Default</option>
                    <option value="desert" <?php echo $desert; ?>>Desert</option>
                    <option value="obsidian" <?php echo $obsidian; ?>>Obsidian</option>
                    <option value="sunburst" <?php echo $sunburst; ?>>Sunburst</option>
                </select>
            </p>
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