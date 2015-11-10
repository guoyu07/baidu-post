<?php
/**
 * 百度推送设置页面
 * Created by PhpStorm.
 * User: tanteng
 * Date: 2015/11/9
 * Time: 19:20
 */
$wxpay = plugins_url('/assets/wxpay.jpg', BAIDUPOST);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
    $baidu_api_url = $_POST['baiduAPI'];
    update_option('baidu_post_url', $baidu_api_url);
}

$api_url = get_option('baidu_post_url');
?>
<div class="wrap">
    <h2>设置百度推送链接</h2>
    <p>
        请先获取百度推送链接，操作步骤：
    </p>
    <p>
        1、使用百度会员或百度联盟帐号登录百度站长平台：http://zhanzhang.baidu.com/site/index <br>
        2、网站必须通过站点验证，验证方式请见百度站长平台<br>
        3、点击左侧网页抓取>>链接提交，http://zhanzhang.baidu.com/linksubmit/index，也就是进入这个页面，右侧可以找到自己网站的接口调用url，自行复制粘贴到本页面。<br>
        有任何疑问请发邮件到tanteng@gmail.com，将第一时间回复，谢谢！
    </p>
    <form action="<?= admin_url( 'options-general.php?page=baidu-post/api-setting.php' ) ?>" name="settings-baidu" method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th><label for="API">百度推送接口调用URL</label></th>
                <td><input type="text" class="regular-text code" value="<?= $api_url ?>" id="API" name="baiduAPI"></td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" value="保存更改" class="button button-primary" id="submit" name="submit"></p>
    </form>
    <hr>
    <p>如果你觉得这个插件不错，给我打赏吧！！微信扫一扫</p>
    <p><img src="<?= $wxpay ?>" alt="微信打赏二维码"> </p>
</div>