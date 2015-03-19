<?php
/*
Plugin Name: Kindeditor For Wordpress
Plugin URI: https://github.com/panxianhai/kindeditor-for-wordpress
Description: kindeditor是一款轻量级的在线编辑器。
Version: 1.4.1
Author: hevin
Author URI: http://weibo.com/hevinpan
*/
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once('kindeditor_class.php');
// 关闭富文本编辑，否则会同时出现两个编辑器
global $current_user;
$is_rich_editing = get_user_option('rich_editing');
if ($is_rich_editing) {
    update_user_option($current_user->ID, 'rich_editing', 'false', true);
}
add_action('personal_options_update', array(&$kindeditor, 'user_personalopts_update'));
add_action('admin_head', array(&$kindeditor, 'add_admin_head'));
add_action('edit_form_advanced', array(&$kindeditor, 'load_kindeditor'));
add_action('edit_page_form', array(&$kindeditor, 'load_kindeditor'));
add_action('simple_edit_form', array(&$kindeditor, 'load_kindeditor'));
add_action('admin_print_styles', array(&$kindeditor, 'add_admin_style'));
add_action('admin_print_scripts', array(&$kindeditor, 'add_admin_js'));
register_activation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$kindeditor, 'activate'));
register_deactivation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$kindeditor, 'deactivate'));

// Option page
function kindeditor_option_page() {
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Kindeditor Options</h2>
        <p>欢迎来到Kindeditor for wordpress的设置页面。</p>
        <p><strong>1.4版本开始已经不需要进行任何设置了。</strong>如果你有什么好的意见和建议，可以到这里<a href="https://github.com/panxianhai/kindeditor-for-wordpress/issues">提交问题/建议</a> </p>
        <p>如果此插件对你有帮助，你可以选择捐赠作者（支付宝捐赠）：</p>
        <p><img src="http://7xi539.com1.z0.glb.clouddn.com/alipay.png" alt="支付宝捐赠"></p>
        <p>PS:  支付宝屏蔽了来自微信的请求，使用支付宝客户端扫描。</p>
    </div>
    <?php
}
function kindeditor_plugin_menu() {
    add_options_page('kindeditor settings', 'Kindeditor设置','manage_options', 'kindeditor-plugin', 'kindeditor_option_page');
}

add_action('admin_menu', 'kindeditor_plugin_menu');