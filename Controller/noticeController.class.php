<?php
/**
* 公告控制器
*/

class noticeController extends baseController
{
    /**
     * 公告列表
     */
    public function index()
    {
        $m = new noticeModel();

        $data = $m->select();
        // var_dump($data);
        // exit;
        $params = array(
            'data'=> $data,
        );
        $this->display($params);
    }

    /**
     * 添加网站公告
     */
    public function add()
    {
        $post = $_POST;

        if(isset($post['btn_submit']))
        {
            $this->checkForm($post);
            $m = new noticeModel();
            $res = $m->add($post);
            if(!$res)
            {
                $this->ajaxReturn('-1', '添加失败');
            }
            $this->ajaxReturn('0', '添加成功');
        }
        $params = array();
        $this->display($params);
    }

    /**
     * 编辑公告
     */
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $m = new noticeModel();
        $post = $_POST;
        if(isset($post['btn_submit']))
        {
            $this->checkForm($post);
            $res = $m->edit($post);
            if(!$res)
            {
                $this->ajaxReturn('-1', '修改失败');
            }
            $this->ajaxReturn('0', '修改成功');
        }
        if(!$id)
        {
            die('操作错误');
        }
        $data = $m->getById($id);
        $params = array(
            'data' => $data
        );
        $this->display($params);
    }

    /**
     * 效验用户提交表单
     * @param type $post
     */
    private function checkForm($post)
    {
        if(!empty($post['title']))
        {
            if(strlen($post['title']) > 90)
            {
                $this->ajaxReturn('-1', '标题不得超过30个汉字');
            }
        }
        if(empty($post['content']))
        {
            $this->ajaxReturn('-1', '请填写公告内容');
        }
    }

    /**
     * 删除数据
     */
    public function del()
    {
        $id = isset($_GET['id'])?(int)$_GET['id']:null;
        if($id)
        {
            $m = new noticeModel();
            $res = $m->del();
            if(!$res)
            {
                $this->ajaxReturn('-1', '删除失败');
            }
            $this->ajaxReturn('0', '删除成功');
        }
    }
}
