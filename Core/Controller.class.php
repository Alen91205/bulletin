<?php
class Controller
{
    public function __construct()
    {

    }
    
    /**
     * 渲染页面
     * @param type $data 分配变量 数组格式
     * @param string $view 页面
     */
    public function display($data=false, $view='')
    {
        if(empty($view))
        {
            $view = '../View/'.$GLOBALS['c'].'/'.$GLOBALS['m'].'.php';	
        }
        if($data)
        {
            //$data = new_html_special_chars($data);
        }
        if(!file_exists($view))
        {
            die('文件不存在');
        }
        if(is_array($data))
        {
            extract($data);	
        }
        include_once $view;
    }
    
    /**
     * 返回json信息
     * @param type $status 状态码
     * @param type $data   内容信息
     */
    protected function ajaxReturn($status, $data)
    {
        die( json_encode( array('status'=>$status, 'msg'=>$data) ) );
    }
    
    /**
     * URL重定向
     * @param type $url 重定向的URL地址
     */
    protected function redirect($url)
    {
        header('location:'.$url);exit;
    }
    protected function go_to($url)
    {
        //header('location:'.$url);exit;
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<script>';
        if($url == '-1'){
            echo 'history.go(-1);';
        }else{
            echo 'location.href="'.$url.'";';
        }
        
        echo '</script>';        
    }    
    protected function msg_box($msg)
    {
        //header('location:'.$url);exit;
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<script>';
        echo 'alert("'.$msg.'")';
        echo '</script>';
        //exit;
    }    
    protected function WriteLog($method = null, $content = array())
    {
        if (! class_exists('FileLog'))
        {
            return array(
                'status' => false,
                'msg' => 'class is not exists',
            );
        }
        if (! method_exists('FileLog', 'WriteFileLog'))
        {
            return array(
                'status' => false,
                'msg' => 'method is not exists',
            );
        }
        $content['date'] = date('Y-m-d H:i:s');
        $content = json_encode($content);
        $class = get_class($this);
        $path = $method ? "Controller/{$class}/{$method}" : "Controller/{$class}";
        $result = FileLog::WriteFileLog($path, $content);

        return $result;
    }
}
?>