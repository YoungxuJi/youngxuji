<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.10
 * Time: 15:10
 */
namespace pubfun;
class VerificationCode
{
    CONST CODE_TRUE = 1;
    CONST CODE_FALSE = 0;
    CONST CODE_ERROR = -1;

    private $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public function showPic()
    {
        /*************生产随机验证码**************/
        $code = '';
        $i = 6;
        while ($i--){
            $code .= $this->chars[mt_rand(0,61)];
        }
        /*************记录随机验证码到session里**************/
        session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
        session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
        session_start();
        $_SESSION['vercode'] = $code;
        $_SESSION['vercode_time'] = time();
        session_write_close();
        /*************生产验证码图片**************/
        $this->genPic($code);
        exit;
    }

    public function checkCode($code)
    {
        session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
        session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
        session_start();
        if(empty($_SESSION['vercode'])){//读取不到验证码信息
            session_write_close();
            return self::CODE_ERROR;
        }elseif(0!==strcasecmp($_SESSION['vercode'],$code)){//验证码错误
            session_write_close();
            return self::CODE_FALSE;
        }else{//验证码正确
            unset($_SESSION['vercode']);
            unset($_SESSION['vercode_time']);
            session_write_close();
            return self::CODE_TRUE;
        }


    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param String $code
     * <br>生成随机验证码图片并显示和退出程序
     */
    private function genPic(String$code)
    {
        $pic_bin = '';

        $img_handle = imagecreate(80,20);
        $back_color = ImageColorAllocate($img_handle, 255, 255, 255); //背景颜色（白色）
        $txt_color = ImageColorAllocate($img_handle, 0,0, 0);  //文本颜色（黑色）
        //加入干扰线
        for($i=0;$i<3;$i++)
        {
            $line = ImageColorAllocate($img_handle,rand(0,255),rand(0,255),rand(0,255));
            Imageline($img_handle, rand(0,15), rand(0,15), rand(100,150),rand(10,50), $line);
        }
        //加入干扰 像素
        for($i=0;$i<200;$i++)
        {
            $randcolor = ImageColorallocate($img_handle,rand(0,255),rand(0,255),rand(0,255));
            Imagesetpixel($img_handle, rand()%80 , rand()%20 , $randcolor);
        }

        Imagefill($img_handle, 0, 0, $back_color);             //填充图片背景色
        ImageString($img_handle, 5, 10, 0, $code, $txt_color);//水平填充一行字符串
        ob_clean();   // ob_clean()清空输出缓存区
        header("Content-type: image/png"); //生成验证码图片
        header('Cache-Control: no-cache, must-revalidate');
        Imagepng($img_handle);//显示图片
        exit;
    }


}