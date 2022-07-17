<?php

class noticeModel extends Model
{
    public function add($post)
    {
        $data['title'] = new_html_special_chars($post['title']);
        $data['content'] = new_html_special_chars($post['content']);
        $result = $this->insert($data);
        return $result;
    }

    public function edit($post)
    {
        $where = 'id=' . $post['id'];
        $data['title'] = new_html_special_chars($post['title']);
        $data['content'] = new_html_special_chars($post['content']);
        return $this->update($data, $where);
    }

    /**
     * 通过ID查询一条数据
     * @param type $id
     */
    public function getById($id)
    {
        $where = 'id='. (int)$id;
        $res = $this->select($where,1);
        return $res;
    }
}
