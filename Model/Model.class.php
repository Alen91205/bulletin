<?php

class Model extends mysql
{
    /**
     * 通用批量操作方法
     */
    public function batch()
    {
        $op  = $_POST['btn_submit'];
        if(!$op)
        {
            die(Language::Get('请选择操作', __FILE__));
        }
        if(!isset($_POST['id']) || empty($_POST['id']))
        {
            die(Language::Get('请选择你要操作的ID', __FILE__));
        }
        $where = 'id in ('.implode(',', $_POST['id']).')';
        switch($op)
        {
            case '批量删除':
                $result = $this->delete($where);
            break;	
            default;
                die(Language::Get('未知操作', __FILE__));
            break;	
        }	
    }
    
    /**
     * 通用删除方法
     * @return boolean
     */
    public function del()
    {
        $id = isset($_GET['id'])?intval($_GET['id']):'';
        if(!$id)
        {
            return false;
        }
        $where = 'id ='.$id;
        $result = $this->delete($where);
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 查询出分页数据
     * @param string $limit
     * @param type $where
     * @param type $order
     * @return boolean
     */
    public function getPageAll($limit, $where='true', $order='id desc', $col='*')
    {
        $data = $this->select($where, $limit, $order, $col);
        if($data)
        {
            return $data;
        }
        return false;
    }
    
    public function getInfo($data){
       $data=mysql_query("select * from t_fetch where status=0");
       $rs=mysql_fetch_row($data);     
       if($rs) return $rs;
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
        $path = $method ? "Model/{$class}/{$method}" : "Model/{$class}";
        $result = FileLog::WriteFileLog($path, $content);

        return $result;
    }

    /**
     * 子類用的查询函數
     */
    public function selectChild($where='true',$limit=false,$order='id desc',$col='*')
    {
        return $this->select($where, $limit, $order, $col);
    }

    /**
     * 獲取並導出CSV文件，用于最小化内存使用。
     * 僅適用於統計類。不適用於明細類。
     *
     * @param mixed  $res      需要導出的數據的数据库对象
     * @param string $csvTitle 第一行列表的標題名。
     * @param string $fromCode 編碼前編碼。
     * @param string $file     導出文件的絕對路徑。
     * @param string $csvEnd   最後一行添加結尾.
     * @param string $toCode   編碼後編碼.
     */
    public function getCsvMiniMem($res, $csvTitle, $fromCode, $file, $csvEnd = '', $toCode = 'UTF-8')
    {
        $csv = $csvTitle;

        $id = 1;

//        echo 777;
//        var_dump(memory_get_usage());

        //$res = mysql_unbuffered_query($sql);
        // 直接操作数据对象，降低内存使用。
        while($val = mysql_fetch_row($res))
        {
            // 處理big5編碼，iconv報錯會中斷，所以用mb_convert_encoding。
            $csv .= mb_convert_encoding($id . ',' . implode(",", $val) . "\r\n", $toCode, $fromCode);
            //unset($val);
            $id++;
        }

//        echo 888;
//        var_dump(memory_get_usage());

        $csv .= $csvEnd;
        // 覆蓋變量，降低內存使用。
        $csv = substr($csv, 0, -2);
        file_put_contents($file, $csv);

//        echo 'filesize:'.filesize($file);
//        die;

        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));

        readfile($file);
    }

    /**
     * 查询数据预处理,避免内存的立即占用。
     * @param string $where
     * @param string $limit
     * @param string $order
     * @param string $col
     * @return mixed
     */
    public function selectPre($where='true',$limit=false,$order='id desc',$col='*')
    {
        $limitStr = $limit ? "limit $limit" : '';
        $sql = "select {$col} from {$this->table} where {$where} order by {$order} {$limitStr}";

        $this->sql = $sql;
//        $this->log($this->sql);
        return mysql_unbuffered_query($sql);
    }

}


