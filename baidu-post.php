<?php
/**
 * @package Baidu Link Post(百度链接推送)
 * @version 1.1
 */
/*
Plugin Name: 百度链接推送
Plugin URI: http://www.tantengvip.com
Description: 发布或更新文章自动推送到百度站长平台，加快网站内容收录，效果不错！
Author: tán téng
Version: 1.1
Author URI: http://www.tantengvip.com
*/

define('BAIDUPOST',__FILE__);

class BaiduPost
{
    public function __construct()
    {
        add_action('save_post',array($this,'post'));
        add_action('publish_post',array($this,'post'));
        $this->check();
    }

    public function check()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['page'] == 'baidu-post/api-setting.php')
            return false;
        $api_url = get_option('baidu_post_url');
        if(!$api_url){
            add_action('admin_notices', array($this,'notice') );
        }
        return true;
    }

    public function notice()
    {
        echo '<div class="error"><p>百度推送插件需要先设置百度推送调用接口URL，<a href="'.admin_url( 'options-general.php?page=baidu-post/api-setting.php' ).'">点此设置</a> </p></div>';
    }

    //推送链接到百度站长平台
    public function post()
    {
        $api_url = get_option('baidu_post_url');
        if(!$api_url){
            return false;
        }

        $post_id = $_POST['post_ID'] ? $_POST['post_ID'] : 0;
        if(!$post_id){
            return false;
        }

        $cats = isset($_POST['post_category']) ? $_POST['post_category'] : '';
        if(is_array($cats) && $cats){
            $cats = array_filter($cats);
        }

        $cat_urls = array();
        if($cats){
            $cat_urls = array();
            foreach ($cats as $catid) {
                $cat_urls[] = get_category_link($catid);
            }
        }

        //要提交百度的链接
        $urls = array(
            home_url(),//首页
            get_permalink($post_id),//文章页
        );

        //文章所属类别页
        foreach ($cat_urls as $cat_url) {
            array_push($urls,$cat_url);
        }

        $args = array(
            'headers'   => array('Content-Type' => 'text/plain'),
            'body'      => implode("\n", $urls),
            'timeout'   => 10,
        );

        $result = wp_remote_post($api_url, $args);
        //var_dump($result);exit;

        return true;
    }
}

new BaiduPost;

//百度推送插件设置页面
function bd_add_pages() {
    add_options_page( '百度推送', '百度推送', 'manage_options' , 'baidu-post/api-setting.php');
}
add_action('admin_menu', 'bd_add_pages');

//设置链接
function baidu_settings_link( $links, $file )
{
    if($file == 'baidu-post/baidu-post.php'){
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=baidu-post/api-setting.php' ) . '">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link );
    }

    return $links;
}
add_filter( 'plugin_action_links', 'baidu_settings_link', 10, 2 );
