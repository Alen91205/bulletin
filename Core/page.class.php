<?php  
class page{  
    
    public $pagesize;            //每页显示的条目数  
    public $total;                //总条目数  
    public $current_page;        //当前被选中的页  
    public $sub_pages = 10;      //每次显示的页数  
    public $totalPage;            //总页数  
    public $subPage_link;        //每个分页的链接  
    public $page_array = array();   //用来构造分页的数组  
    public $limit;
   
    public function __construct() 
    {
        $url = $_SERVER['REQUEST_URI'];
        $url = preg_replace('/\?page=\d+/', '', $url);
        $url = preg_replace('/&page=\d+/', '', $url);
        if(preg_match('/\?/',$url))
        {
            $url .= '&page=';	
        }
        else
        {
            $url .= '?page=';	
        }
        $this->subPage_link = $url;
    }
    
    public function pages()
    {
        $this->totalPage = ceil($this->total / $this->pagesize);  
        $this->limit = ($this->current_page-1)*$this->pagesize.','.$this->pagesize;
        return $this->subPageCss();
    }
   
    private function initArray()
    {  
        for($i=0; $i<$this->sub_pages; $i++)
        {  
            $this->page_array[$i]=$i;  
        }  
        return $this->page_array;  
    }  
    
    private function construct_num_Page()
    {  
        if($this->totalPage < $this->sub_pages)
        {  
            $current_array = array();  
            for($i=0;$i<$this->totalPage;$i++)
            {   
                $current_array[$i] = $i+1;  
            }  
        }
        else
        {  
            $current_array = $this->initArray();  
            if($this->current_page <= 3) 
            {  
                for($i=0;$i<count($current_array);$i++)
                {  
                    $current_array[$i] = $i+1;  
                }  
            }
            elseif ($this->current_page <= $this->totalPage && $this->current_page > $this->totalPage - $this->sub_pages + 1 )
            {  
                for($i=0;$i<count($current_array);$i++)
                {  
                    $current_array[$i]=($this->totalPage)-($this->sub_pages)+1+$i;  
                }  
            }
            else
            {  
                for($i=0;$i<count($current_array);$i++)
                {  
                    $current_array[$i]=$this->current_page-2+$i;  
                }  
            }  
        }  
        return $current_array;  
    }  
   
    public function subPageCss()
    {   $subPageCss2Str ='<div class="col-sm-3" style="margin-bottom: 20px;">';
        $subPageCss2Str .= '<span class="page_info">' . Language::Get('共', __FILE__) .' '. $this->total .' '. Language::Get('条记录，每页显示', __FILE__) .' '. $this->pagesize .' '. Language::Get('条', __FILE__) . '，';
        $subPageCss2Str .= Language::Get('当前第', __FILE__) . ' ' . $this->current_page."/".$this->totalPage .' '. Language::Get('页', __FILE__) . ' ';
        $subPageCss2Str .= Language::Get('一页笔数', __FILE__) . ' <select name="pagesize">';
        for($i=10;$i<=200;$i++){
            if($i == 10 || $i == 20 || $i == 50 || $i == 100 || $i == 200){
                $subPageCss2Str .='<option value="'.$i.'"';
            }
            if($i==$this->pagesize){
                $subPageCss2Str .= ' selected = "selected"';
            }
            $subPageCss2Str .= '>'.$i.'</option>';
        }
        $subPageCss2Str .= "</select></span>";
        $subPageCss2Str .= '<input value='.Language::Get('确认', __FILE__).' class="btn btn-success btn-xs" type="submit"/>';
        
        if($this->total > $this->pagesize)
        {
            $subPageCss2Str .='</div>';
            $subPageCss2Str .='<div class="col-sm-6">';
            
            if($this->current_page > 1)
            {  
                $firstPageUrl = $this->subPage_link."1";  
                $prewPageUrl = $this->subPage_link.($this->current_page-1);  
                $subPageCss2Str .="<a href='$firstPageUrl'>".Language::Get('首页', __FILE__)."</a> ";  
                $subPageCss2Str .="<a href='$prewPageUrl'>← ".Language::Get('上一页', __FILE__)."</a> ";
            }
            else 
            {  
                $subPageCss2Str .="<em>".Language::Get('首页', __FILE__)."</em> ";  
                $subPageCss2Str .="<em>← ".Language::Get('上一页', __FILE__)."</em> ";
            }  
            $a = $this->construct_num_Page();  
            for($i=0;$i<count($a);$i++)
            {  
                $s=$a[$i];  
                if($s == $this->current_page )
                {  
                    $subPageCss2Str.="<em style='color:black;'>".$s."</em>";  
                }
                else
                {  
                    $url=$this->subPage_link.$s;  
                    $subPageCss2Str.="<a href='$url'>".$s."</a>";  
                }  
            }  
            if($this->current_page < $this->totalPage)
            {  
                $lastPageUrl=$this->subPage_link.$this->totalPage;  
                $nextPageUrl=$this->subPage_link.($this->current_page+1);  
                $subPageCss2Str.="<a href='$nextPageUrl'> ".Language::Get('下一页', __FILE__)." →</a> ";
                $subPageCss2Str.="<a href='$lastPageUrl'>".Language::Get('尾页', __FILE__)."</a> ";  
            }
            else 
            {  
                $subPageCss2Str.="<em> ".Language::Get('下一页', __FILE__)." →</em> ";
                $subPageCss2Str.="<em>".Language::Get('尾页', __FILE__)."</em>";  
            }  
        }
        $subPageCss2Str .='</div>';
        return $subPageCss2Str;
    }  
}  
?>  