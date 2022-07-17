<?php
/**
* 网站公告接口
*/

class apiController extends Controller
{    
    /**
     * 获取公共信息
     */
    public function getdata()
    {            
        $m = new noticeModel();
        $data = $m->select('true');
        // echo "<pre>";
        // print_r($data);
        // echo '</pre>';
        if($data)
        {   
            $this->ajaxReturn('0', $data);
        }
        $this->ajaxReturn(-2601, '获取公告失败');
    }
}

