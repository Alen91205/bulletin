<?php
//数据库连接
    define('DB_HOST','127.0.0.1');
    define('DB_USER','root');
    define('DB_PWD','');
    define('DB_DATA','test');
    define('DB_PREFIX','t_');

class mysql
{
    public $con, $table, $sql='';
    
    public function __construct($table=false)
    {
        $this->con=mysqli_connect(DB_HOST, DB_USER, DB_PWD); 
        if (mysqli_connect_errno($this->con)) 
        { 
            echo "连接 MySQL 失败: " . mysqli_connect_error(); 
        } 
         
        // ...查询 "RUNOOB" 数据库的一些 PHP 代码...
         
        // 修改数据库为 "test"
        mysqli_select_db($this->con,"test");
        mysqli_query($this->con, "set names utf8");
        if(is_string($table))
        {
            $this->table=DB_PREFIX.$table;
        }
        else
        {
            $className = get_class($this);
            $tableName = substr ($className,0, strlen($className)-5);
            $this->table = DB_PREFIX.$tableName;
        }	
    }	
	
    /**
     * 执行sql 语句
     * @param type $sql
     * @return type
     */
    public function query($sql)
    {
        $this->sql = $sql;
		$this->log($this->sql);
        return mysqli_query($this->con, $sql);
    }
    
    /**
     * 插入数据
     * @param type $data 要插入的数据 数组
     * @param type $REPLACE 
     * @return boolean
     */
    public function insert($data, $REPLACE=false)
    {
        if(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                $data[$k] = "$k = '$v'";
            }
        }
        $lianjie = implode(',',$data);
        $op = $REPLACE ? 'REPLACE' : 'insert';
        $sql = "$op into {$this->table} set {$lianjie}";
        // echo $sql ."<br>";
        $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            return mysqli_insert_id($this->con)>0?mysqli_insert_id($this->con):true;
        }
        else
        {
            return false;
        }

    }
        
    /**
     * 修改数据
     * @param type $data 数组
     * @param type $where 条件
     * @return boolean
     */
    public function update($data,$where)
    {
        if(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                $data[$k] = "$k = '$v'";			
            }		
        }
        $lianjie = implode(',',$data);
        $sql = "update {$this->table} set {$lianjie} where {$where}";
        if($this->query($sql)){
            return true;
        }
        else
        {
            return false;
        }
    }
        
    /**
     * 查询数据
     * @param type $where 
     * @param type $limit
     * @param type $order
     * @param type $col
     * @return boolean
     */
    public function select($where='true',$limit=false,$order='id desc',$col='*')
    {
        $limitStr = $limit ? "limit $limit" : '';
        $sql = "select {$col} from {$this->table} where {$where} order by {$order} {$limitStr}";
        //echo $sql;
        $zhixing = $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            if($limit == '1' || $limit == '0,1')
            {
                return mysqli_fetch_assoc($zhixing);
            }
            else
            {
                $rows = array();
                while($row = mysqli_fetch_assoc($zhixing))
                {
                    $rows[] = $row;			
                }
                return $rows;		
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 查询数据
     * @param type $where
     * @param type $limit
     * @param type $order
     * @param type $col
     * @param type $group
     * @return boolean
     */
    public function groupselect($where='true',$limit=false,$order='id desc',$col='*',$group)
    {
        $limitStr = $limit ? "limit $limit" : '';
        $sql = "select {$col} from {$this->table} where {$where} group by {$group} order by {$order} {$limitStr}";
        //echo $sql;
        $zhixing = $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            if($limit == '1' || $limit == '0,1')
            {
                return mysqli_fetch_assoc($zhixing);
            }
            else
            {
                $rows = array();
                while($row = mysqli_fetch_assoc($zhixing))
                {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 查询出总记录数
     * @param type $where
     */
    public function count($where='true', $col='*')
    {
        $sql = "select count({$col}) as total from {$this->table} where {$where}";
        $zhixing = $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            $row = mysqli_fetch_assoc($zhixing);
            if($row && $row['total'])
            {
                return $row['total'];
            }
            else
            {
                return false;
            }
        }
    }

    public function sum($where='true', $col='*', $limit=false, $order='id desc')
    {
        $array = array();
        $limitStr = $limit ? "limit $limit" : '';
       
        foreach (explode(',', $col) as $value) {
            $array [] = 'SUM('.$value.') ';
        }
        
        $col = implode(",",$array);
        $sql = "select {$col} from (select * from {$this->table} where {$where} order by {$order} {$limitStr}) as sum";
        // echo $sql;
        $zhixing = $this->query($sql);

        if(mysqli_affected_rows($this->con)>0)
        {
            return mysqli_fetch_assoc($zhixing);
        }

        return false;
    }
        
    /**
     * 删除数据
     * @param type $where
     * @param type $limit
     * @return boolean
     */
    public function delete($where,$limit=false)
    {
        $limitStr = $limit ? "limit $limit":'';
        $sql = "delete from {$this->table} where {$where} {$limitStr}";
//        echo $sql;
        $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            return true;		
        }
        else
        {
            return false;		
        }
    }
    
    /**
     * 执行一条sql语句
     * @param type $where 
     * @param type $limit
     * @param type $order
     * @param type $col
     * @return boolean
     */
    public function querySql($sql)
    {
        $zhixing = $this->query($sql);
        if(mysqli_affected_rows($this->con)>0)
        {
            $rows = array();
            while($row = mysqli_fetch_assoc($zhixing))
            {
                $rows[] = $row;			
            }
            return $rows;		
        }
        else
        {
            return false;
        }
    }
	
	private function log($content)
    {
        $fileName = '/var/log/houtaisql/'.date('Ymd').'_sql.log';
        $fp = @fopen($fileName,'a+');
        @fwrite($fp,'Time:'.date('Y-m-d h:i:s').'    Info:  '.$content.PHP_EOL);
        @fclose($fp);
    }
    public function get_table_array($result,$key = 'id'){
        $return_arr = array();
        if(count($result)>0 && $result != ''){
            foreach ($result as $vv) {
                $return_arr[$vv[$key]] = $vv;
            }
        }
        return $return_arr;
    }    
}
/*$a = new mysql('members');
$c = $a->select();
var_dump($c);
echo $a->sql;*/
?>
