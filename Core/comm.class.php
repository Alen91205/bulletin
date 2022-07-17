<?php
class comm{
    /**
    * 上傳檔案 jpg/png/jpeg
    */
    public function do_post_file($_url,$_file,$_params=false){
        if($_params==false) $_params=array();
        $_params["filekey"]='';
        if(is_array($_file)){
            foreach($_file as $k=>$v){
                if(!file_exists($v)) return -1;
                //$_params["filekey"][]=$k;
                $_params[$k]="@".$v;
            }
        }else{
            $key="userfile";
            //$_params["filekey"][]=$key;
            if(!file_exists($_file)) return -1;
            if (function_exists('curl_file_create'))
            {
                $_params[$key] = curl_file_create($_file);
            }
            else
            {
                $_params[$key]="@".$_file;
            }
        }


        //print_r($_params);
         //"userfile"=>"@C:/XXX/OOO/oxox.doc"
         //檔案若和程式在同一目錄或相對目錄, 可以用getcwd(), 如:
         // "userfile"=>"@".getcwd()."/oxox.doc",
         // 另外還可以在檔名後面加上分號指定mimetype(較新版的PHP才能使用)
         // (預設的 mimetype 為application/octet-stream)
         // "userfile"=>"@".getcwd()."\\somePic.png;type=image/png"
        $ch = curl_init();
        $options = array(
          CURLOPT_URL=>$_url,
          CURLOPT_POST=>true,
          CURLOPT_POSTFIELDS=>$_params, // 直接給array
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //不印出來
        curl_setopt_array($ch, $options);
        $result=curl_exec($ch);
        curl_close($ch);
        print_r($result);
        return $result;
    }

    /**
    * 上傳檔案 html
    */
    public function do_post_Html($_url,$_params=false){
        if($_params==false) $_params=array();
        $ch = curl_init();
        $options = array(
          CURLOPT_URL=>$_url,
          CURLOPT_POST=>true,
          CURLOPT_POSTFIELDS=>$_params, // 直接給array
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //不印出來
        curl_setopt_array($ch, $options);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    function get_post_html($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cache-Control: no-cache"));
        curl_setopt($ch, CURLOPT_TIMEOUT,20); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WEB_LIB_GI_81288128');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
?>
