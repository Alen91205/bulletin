<?php
/**
 *  common.php 公共函数库
 */

/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
    if(!is_array($string)) return addslashes($string);
    foreach($string as $key => $val) $string[$key] = new_addslashes($val);
    return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
    if(!is_array($string)) return stripslashes($string);
    foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
    return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
    $encoding = 'utf-8';
    if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
    foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    return $string;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','&quot;',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace('"','',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    $string = str_replace('=','',$string);
    return $string;
}

/**
 * xss过滤函数
 *
 * @param $string
 * @return string
 */
function remove_xss($string) { 
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2); 

    for ($i = 0; $i < sizeof($parm); $i++) { 
        $pattern = '/'; 
        for ($j = 0; $j < strlen($parm[$i]); $j++) { 
            if ($j > 0) { 
                $pattern .= '('; 
                $pattern .= '(&#[x|X]0([9][a][b]);?)?'; 
                $pattern .= '|(&#0([9][10][13]);?)?'; 
                $pattern .= ')?'; 
            }
            $pattern .= $parm[$i][$j]; 
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, ' ', $string); 
    }
    return $string;
}

/**
 * 过滤ASCII码从0-28的控制字符
 * @return String
 */
function trim_unsafe_control_chars($str) {
    $rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
    return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
}

/**
 * 格式化文本域内容
 *
 * @param $string 文本域内容
 * @return string
 */
function trim_textarea($string) {
    $string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
    return $string;
}

/**
 * 将文本格式成适合js输出的字符串
 * @param string $string 需要处理的字符串
 * @param intval $isjs 是否执行字符串格式化，默认为执行
 * @return string 处理后的字符串
 */
function format_js($string, $isjs = 1) {
    $string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
    return $isjs ? 'document.write("'.$string.'");' : $string;
}

/**
 * 转义 javascript 代码标记
 *
 * @param $str
 * @return mixed
 */
 function trim_script($str) {
    if(is_array($str)){
        foreach ($str as $key => $val){
                $str[$key] = trim_script($val);
        }
    }else{
        $str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
        $str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
        $str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
        $str = str_replace ( 'javascript:', 'javascript：', $str );
    }
    return $str;
}
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
    $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
    // if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    //     $ip = getenv('HTTP_CLIENT_IP');
    // } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    //     $ip = getenv('HTTP_X_FORWARDED_FOR');
    // } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    //     $ip = getenv('REMOTE_ADDR');
    // } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    //     $ip = $_SERVER['REMOTE_ADDR'];
    // }
    // return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';

    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',$_SERVER['HTTP_X_REAL_IP'])){
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (isset($_SERVER['HTTP_X_REAL_FORWARDED_FOR']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_REAL_FORWARDED_FOR'];
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){//&& preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $ips[0];
    }
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    return $ip;
}

/**
 * 程序执行时间
 *
 * @return	int	单位ms
 */
function execute_time() {
    $stime = explode ( ' ', SYS_START_TIME );
    $etime = explode ( ' ', microtime () );
    return number_format ( ($etime [1] + $etime [0] - $stime [1] - $stime [0]), 6 );
}

/**
* 产生随机字符
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 大小写字母+数字 默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1
* @return   string     字符串
*/
function randStr($length, $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

function randWord($length, $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
* 转换字节数为其他单位
*
*
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
function sizecount($filesize) {
    if ($filesize >= 1073741824) {
            $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
    } elseif ($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
    } elseif($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
    } else {
            $filesize = $filesize.' Bytes';
    }
    return $filesize;
}

/**
 * 查询字符是否存在于某字符串
 *
 * @param $haystack 字符串
 * @param $needle 要查找的字符
 * @return bool
 */
function str_exists($haystack, $needle)
{
    return !(strpos($haystack, $needle) === FALSE);
}

/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 判断email格式是否正确
 * @param $email
 */
function is_email($email) {
    return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

/**
 * 判断手机号是否正确
 * @param type $phone
 */
function is_phone($phone, $country1='') {
        $country = isset($country1) && $country1 ? $country1 : $GLOBALS['country'];
        if($GLOBALS['site'] == 'x29')
        {
            return preg_match("/^0\d{9}$/",$phone);
        }
        else if($GLOBALS['site'] == 'x80' || $GLOBALS['site'] == 'dvf')
        {
            return true;
        }
        else if($GLOBALS['site'] == 'x74' || $GLOBALS['site'] == 'ccf')
        {
            return preg_match("/^60\d{8,12}$|^84\d{8,12}$|^86\d{8,12}$|^66\d{9}$|^95\d{8,10}$/",$phone);
        }
        else if(strtolower($country) === 'tw') 
        // if(strtolower($country) === 'tw')
        {
            return preg_match("/09\d{8}$/",$phone);
        }
        else if(strtolower($country) === 'vn')
        {
            if($GLOBALS['site'] == 'w92' || $GLOBALS['site'] == 'vbf')
            {
                if(substr($phone, 0, 2) == '84')
                {
                    return preg_match("/84\d{10,11}$/",$phone);
                }
                else
                {
                    return preg_match("/^0[34578]{1}\d{8}$/",$phone);
                }
            }
            else if ($GLOBALS['site'] == 'x58' || $GLOBALS['site'] == 'caf' || $GLOBALS['site'] == 'x83' || $GLOBALS['site'] == 'akf')
            {
                return preg_match("/84\d{9,10}$/",$phone);
            }
            return preg_match("/84\d{10,11}$/",$phone);
        }
        else if(strtolower($country) === 'cn')
        {
            if( $GLOBALS['site'] == 'x38' || $GLOBALS['site'] == 'tpf' ||
                $GLOBALS['site'] == 'x50' || $GLOBALS['site'] == 'yaf' ||
                $GLOBALS['site'] == 'x51' || $GLOBALS['site'] == 'yyf' ||
                $GLOBALS['site'] == 'x52' || $GLOBALS['site'] == 'tgf')
            {
                return preg_match("/1\d{10}$/",$phone);
            }
            else if($GLOBALS['site'] == 'b07' || $GLOBALS['site'] == 'lsb')
            {
                return preg_match("/\d{11,13}$/",$phone);
            }
            else if ($GLOBALS['site'] == 'x82' || $GLOBALS['site'] == 'ycf')
            {
                return preg_match("/^86\d{11}$/",$phone);
            }
            else
            {
                return preg_match("/1[3456789]{1}\d{9}$/",$phone);
            }
        }
        else if(strtolower($country) === 'hk')
        {
            return preg_match("/^[23456789]{1}\d{7}$/",$phone);
        }
        else if(strtolower($country) === 'in')
        {
            return preg_match("/^91\d{10}$/",$phone);
        }
        else if(strtolower($country) === 'id')
        {
            if($GLOBALS['site'] == 'w91' || $GLOBALS['site'] == 'ynf')
            {
                # 首位數字默認為0，號碼為8-13位
                return preg_match("/0\d{7,12}$/",$phone);
            }
            return preg_match("/^62\d{9,12}$/",$phone);
        }
        else if(strtolower($country) === 'th')
        {
            if($GLOBALS['site'] == 'x81' || $GLOBALS['site'] == 'dtf' || $GLOBALS['site'] == 'x82' || $GLOBALS['site'] == 'ycf')
            {
                return preg_match("/^66\d{9}$/",$phone);
            }
            else
            {
                return preg_match("/0\d{9}$/",$phone);
            }
        }
        else if(strtolower($country) === 'kr') 
        {
            return preg_match("/0\d{10}$/",$phone);
        }
        else if(strtolower($country) === 'my')
        {
            return preg_match("/^60\d{9}$/",$phone);
        }
        else if(strtolower($country) === 'ph')
        {
            return preg_match("/^63\d{9,12}$/",$phone);
        }
        else if(strtolower($country) === 'ja')
        {
            return preg_match("/81\d{10}$/",$phone);
        }
        else if(strtolower($country) === 'kh')
        {
            if($GLOBALS['site'] == 'b01' || $GLOBALS['site'] == 'dab')
            {
                return preg_match("/0\d{8,9}$/",$phone);
            }
            if($GLOBALS['site'] == 'x72' || $GLOBALS['site'] == 'hhf')
            {
                return preg_match("/^855\d{8,10}$|^84\d{10}$/",$phone);
            }
            return preg_match("/^855\d{8,10}$/",$phone);
        }
        else if(strtolower($country) === 'us')
        {
            return preg_match("/^[0-9]{10}$/",$phone);
        }
        else
        {
            return true;
        }
    }

/**
 * 判断是否是汉字
 * @param type $value
 */
function is_text($value) {
    return preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$value);
}

/**
 * IE浏览器判断
 */
function is_ie() {
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)) return false;
    if(strpos($useragent, 'msie ') !== false) return true;
    return false;
}

/**
 * 文件下载
 * @param $filepath 文件路径
 * @param $filename 文件名称
 */
function file_down($filepath, $filename = '') {
    if(!$filename) $filename = basename($filepath);
    if(is_ie()) $filename = rawurlencode($filename);
    $filetype = fileext($filename);
    $filesize = sprintf("%u", filesize($filepath));
    if(ob_get_length() !== false) @ob_end_clean();
    header('Pragma: public');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: '.$filetype);
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Content-length: '.$filesize);
    readfile($filepath);
    exit;
}

/**
 * 判断字符串是否为utf8编码，英文和半角字符返回ture
 * @param $string
 * @return bool
 */
function is_utf8($string) {
	return preg_match('%^(?:
                            [\x09\x0A\x0D\x20-\x7E] # ASCII
                            | [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
                            | \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
                            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
                            | \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
                            | \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
                            | [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
                            | \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
                            )*$%xs', $string);
}

 /**
 * 检测输入中是否含有错误字符
 *
 * @param char $string 要检查的字符串名称
 * @return TRUE or FALSE
 */
function is_badword($string) {
    $badwords = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n","#");
    foreach($badwords as $value){
        if(strpos($string, $value) !== FALSE) {
            return TRUE;
        }
    }
    return FALSE;
}

/**
 * 检查用户名是否符合规定
 *
 * @param STRING $username 要检查的用户名
 * @return 	TRUE or FALSE
 */
function is_username($username) {
    $strlen = strlen($username);
    if(is_badword($username) || !preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/", $username)){
        return false;
    } elseif ( 20 < $strlen || $strlen < 2 ) {
        return false;
    }
    return true;
}

/**
 * 对数据进行编码转换
 * @param array/string $data       数组
 * @param string $input     需要转换的编码
 * @param string $output    转换后的编码
 */
function array_iconv($data, $input = 'gbk', $output = 'utf-8') {
    if (!is_array($data)) {
        return iconv($input, $output, $data);
    } else {
        foreach ($data as $key=>$val) {
            if(is_array($val)) {
                $data[$key] = array_iconv($val, $input, $output);
            } else {
                $data[$key] = iconv($input, $output, $val);
            }
        }
        return $data;
    }
}

 /**
  * 返回json信息
  * @param type $status 状态码
  * @param type $data   内容信息
  */
 function ajaxReturn($status, $data)
 {
    die( json_encode( array('status'=>$status, 'msg'=>$data) ) );
 }
 
 /**
  * 返回当天的开始和结束时间的时间戳
  * @return type
  */
 function today()
 {
    $strtime = strtotime( date('Y-m-d 00:00:00') );
    $endtime = strtotime( date('Y-m-d 23:59:59') );
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
  /**
  * 返回当天的开始和结束时间的时间戳(美東時間)
  * @return type
  */
function eastToday()
{
  $strtime = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day +12 hours')));
  $endtime = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day +12 hours')));
  return array('strarttime'=>$strtime,'endtime'=>$endtime);
}
  /**
  * 返回当天的开始和结束时间的时间戳(美東時間)/優惠審核
  * @return type
  */
function request_eastToday()
{
  $strtime = strtotime(date('Y-m-d 00:00:00', strtotime('-1 day +12 hours')));
  $endtime = strtotime(date('Y-m-d 23:59:59', strtotime('-1 day +12 hours')));
  return array('request_timeStar'=>$strtime,'request_timeEnd'=>$endtime);
}
 /**
  * 返回昨天的开始时间和结束时间的时间戳
  * @return type
  */
 function yesterday()
 {
    $strtime = strtotime(date('Y-m-d 00:00:00'))-86400;
    $endtime = strtotime(date('Y-m-d 23:59:59'))-86400;
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
 
 /**
  * 返回本周的开始时间和结束时间的时间戳
  * @return type
  */
 function thisweek()
 {
    $strtime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
    $endtime = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
 
 /**
  * 返回上周的开始时间和结束时间的时间戳
  * @return type
  */
 function lastweek()
 {
    $strtime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
    $endtime = mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }

  /**
  * 返回上上周的开始时间和结束时间的时间戳
  * @return type
  */
 function lastlastweek()
 {
    $strtime = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-14,date("Y"));
    $endtime = mktime(23,59,59,date("m"),date("d")-date("w")+7-14,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
 
 /**
  * 返回本月的开始时间和结束时间的时间戳
  * @return type
  */
 function thismonth()
 {
    $strtime = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
    $endtime = mktime(23,59,59,date("m") ,0,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
  function thismonth2()
 {
    $strtime = mktime(0, 0 , 0,date("m"),1,date("Y"));
    $endtime = mktime(23,59,59,date("m")+1 ,0,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
 
 
 /**
  * 返回上月的开始时间和结束时间的时间戳
  * @return type
  */
 function lastmonth()
 {
    $strtime = mktime(0, 0 , 0,date("m"),1,date("Y"));
    $endtime = mktime(23,59,59,date("m"),date("t"),date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }

  /**
  * 返回上上月的开始时间和结束时间的时间戳
  * @return type
  */
 function lastlastmonth()
 {
    $strtime = mktime(0, 0 , 0,date("m")-2,1,date("Y"));
    $endtime = mktime(23,59,59,date("m")-1,0,date("Y"));
    return array('strarttime'=>$strtime,'endtime'=>$endtime);
 }
 
 /** 
  * 根据腾讯IP分享计划的地址获取IP所在地 
  */
 function getQQIp($queryIP)
 { 
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP; 
    $ch = curl_init($url); 
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312'); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
    $result = curl_exec($ch); 
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码 
    curl_close($ch); 
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
    if($ipArray && $ipArray[1])
    {
        return $ipArray[1]; 
    }
    return '目前暂时没有您的IP信息,  期待您的分享';
 } 
 
 /** 
  * 根据新浪IP查询接口获取IP所在地 
  */ 
 function getSinaIp($queryIP)
 {
     return "";
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP; 
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
    $location = curl_exec($ch); 
    $location = json_decode($location); 
    curl_close($ch); 
    $loc = ""; 
    if($location)
    {
        if (empty($location->desc) ) 
        { 
            $loc = $location->province.$location->city.$location->district.$location->isp; 
        }
        else
        { 
            $loc = $location->desc; 
        } 
        return $loc; 
    }
    return '目前暂时没有您的IP信息,  期待您的分享';
 } 
   
 /**
  * 生成唯一订单号
  * @return string
  */
 function orderNumber()
 {
    $num = date('Ymd').substr(microtime(), 2, 5).random(2);
    return $num;
 }
 
 
    /**
    * 将xml转换成数组
    * @param type $xml
    * @return boolean
    */
    function xml_to_array($xml)                              
    {                                                        
       $array = (array)(simplexml_load_string($xml));     
       if(is_array($array) && isset($array['@attributes']))
       {
           return $array['@attributes'];   
       }
       return false;                                   
    } 
   
   /**
     * 服务端发送请求
     * @param type $url
     * @return type
     */ 
    function curl($url, $post = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cache-Control: no-cache"));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WEB_LIB_GI_81288128');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    #多存header_ 紀錄
    function curl_GetHeader($url, $post = '')
    {
        $result = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cache-Control: no-cache"));
        curl_setopt($ch, CURLOPT_TIMEOUT,300); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'WEB_LIB_GI_81288128');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $output = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $result = array(
            'header' => $header,
            'output' => $output,
        );
        
        return $result;
    }
    function sky_encode($pass, $salt='FXKih3Ranp6S36H', $salt2='BcDzNKMyz')
    {
        $key  = 'ASD&*!)_)><>21ASDasnk<>MNd67&2561A21SKJ';$key2 = 'p,as;asd&^&7!)I(!@)*,><!@!#KANKasDLlml';$pass1 = md5(md5($pass).$salt.md5($salt.$key2.$salt2.md5($salt.$pass).$salt2.$salt.md5($salt.$salt2).md5($salt2)).$salt2);$pass2 = md5(md5($key.$key2).$key2.md5($key,true).$pass1.$salt2.$key2.$pass.md5($pass.md5($key).$salt2.$pass1).md5($pass1.$key2));
        return md5(md5($pass1.$pass2).$key.$salt2.md5($key2).md5($pass2));
    }
    
    function tzformat($time,$mode=1){
        $newtime = strtotime($time);
        //輸入北京時間 輸出格式：2017-01-01 12:00:00
        //0,北京時間 輸出格式：2017-01-01 12:00:00
        if($mode == 0){
            return date("Y-m-d H:i:s",$newtime);
        }
        //1,美东時間 輸出格式：2017-01-01 12:00:00
        else if($mode == 1){
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime - (12 * 60 * 60)));
            return date("Y-m-d H:i:s",$newtime);
        }
        //2,北京加美东時間 前面代有地區
        else if($mode == 2){
            $newtime2 = $newtime;
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime - (12 * 60 * 60)));
            if ($GLOBALS['showTime'] == '0')
            {
                return Language::Get('北京', __FILE__) . "：" . "<br/>" . date("Y-m-d H:i:s",$newtime2) . "<br/>" . Language::Get('美东', __FILE__) . "：" . "<br/>" . date("Y-m-d H:i:s",$newtime);
            }
            else if ($GLOBALS['showTime'] == '1')
            {
                return Language::Get('北京', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime2);
            }
            else if ($GLOBALS['showTime'] == '2')
            {
                return Language::Get('美东', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime);
            }
        }
        else if($mode == 3){
            $newtime2 = $newtime;
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime - (12 * 60 * 60)));
            return Language::Get('北京', __FILE__) . " ： " . date("Y-m-d H:i:s",$newtime2) . " " . Language::Get('美东', __FILE__) . " ： " . date("Y-m-d H:i:s",$newtime);
        }
        //3,北京UNIX time
        //4,美东UNIX time
        //5,北京日期
        //6,美东日期
        
        //輸入美东時間
        //10,北京時間 輸出格式：2017-01-01 12:00:00
        else if($mode == 10){
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime + (12 * 60 * 60)));
            return date("Y-m-d H:i:s",$newtime);
        }
        
        //11,美东時間 輸出格式：2017-01-01 12:00:00
        
        //輸入時間由config決定
        //20,北京時間 輸出格式：2017-01-01 12:00:00
        
        
        
        return date("Y-m-d H:i:s",$newtime);
        //21,美东時間 輸出格式：2017-01-01 12:00:00
        
        
        return;
    }
    function tzformat1($time,$mode=1){
        $newtime = strtotime($time);
        //輸入北京時間 輸出格式：2017-01-01 12:00:00
        //0,北京時間 輸出格式：2017-01-01 12:00:00
        if($mode == 0){
            return date("Y-m-d H:i:s",$newtime);
        }
        //1,美东時間 輸出格式：2017-01-01 12:00:00
        else if($mode == 1){
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime - (12 * 60 * 60)));
            return date("Y-m-d H:i:s",$newtime);
        }
        //2,北京加美东時間 前面代有地區
        else if($mode == 2){
            $newtime2 = $newtime;
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime - (12 * 60 * 60)));
            if ($GLOBALS['showTime'] == '0')
            {
                return Language::Get('北京', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime2) . "<br/>" . Language::Get('美东', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime);
            }
            else if ($GLOBALS['showTime'] == '1')
            {
                return Language::Get('北京', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime2);
            }
            else if ($GLOBALS['showTime'] == '2')
            {
                return Language::Get('美东', __FILE__) . "：" . date("Y-m-d H:i:s",$newtime);
            }
        }
        //3,北京UNIX time
        //4,美东UNIX time
        //5,北京日期
        //6,美东日期
        
        //輸入美东時間
        //10,北京時間 輸出格式：2017-01-01 12:00:00
        else if($mode == 10){
            $newtime = strtotime(date('Y-m-d H:i:s', $newtime + (12 * 60 * 60)));
            return date("Y-m-d H:i:s",$newtime);
        }
        
        //11,美东時間 輸出格式：2017-01-01 12:00:00
        
        //輸入時間由config決定
        //20,北京時間 輸出格式：2017-01-01 12:00:00
        
        
        
        return date("Y-m-d H:i:s",$newtime);
        //21,美东時間 輸出格式：2017-01-01 12:00:00
        
        
        return;
    }
    function is_qq($qq)
    {
    return preg_match("/^[1-9]\d{5,9}$/", $qq);
    }

    function is_IDNumber($IDNumber) {
        if($GLOBALS['site'] == 'x64' || $GLOBALS['site'] == 'lkf' || $GLOBALS['site'] == 'x76' || $GLOBALS['site'] == 'gtf'){
            return  preg_match("/^[a-zA-Z\d]{7,13}$/i",$IDNumber);
        }
        return  preg_match("/^[A-Z]{1}\d{9}$/",$IDNumber);
    }  

    function getIpInfo($loginip, $url = false)
    {
        $url = ($url ?: $GLOBALS['ipInfoUrl']) . "?ip={$loginip}";
        
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return json_decode($output, true);
    }

    function numberHide($value, $start = 3, $end = -3)
    {
        return (
            $value 
                ? substr_replace($value, '*****', $start, $end) 
                : ''
        );
    }

    function emailHide($value, $start = 3, $end = -3)
    {
        if ($value)
        {
            $ary = explode('@', $value);
            $value = substr_replace($ary[0], '*****', $start, $end) . '@' . (isset($ary[1]) ? $ary[1] : '');
        }
        return $value;
    }
    
    function getSearchTime($strtime = FALSE, $endtime = FALSE, $timetype = FALSE)
    {
        $BeijingTime = date("Y-m-d H:i:s");

        //日期快速鍵
            if ($timetype == 1) {
                $strtotime = $GLOBALS['timezone'] == '1' ? '-1 day +12 hours' : 'now';
                $strtime = date('Y-m-d 00:00:00', strtotime($strtotime));
                $endtime = date('Y-m-d 23:59:59', strtotime($strtotime));
            }
            if ($timetype == 2) {
                $strtotime = $GLOBALS['timezone'] == '1' ? '-2 day +12 hours' : '-1 day';
                $strtime = date('Y-m-d 00:00:00', strtotime($strtotime));
                $endtime = date('Y-m-d 23:59:59', strtotime($strtotime));
            }
            if(date("w", strtotime($BeijingTime)) == '1' && date("H", strtotime($BeijingTime)) < '12' || date("w", strtotime($BeijingTime)) == '0'){
                if ($timetype == 3) {
                    $lastweek = lastweek();
                    $strtime = date("Y-m-d 00:00:00", $lastweek['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $lastweek['endtime']);
                }
                if ($timetype == 4) {
                    $lastlastweek = lastlastweek();
                    $strtime = date("Y-m-d 00:00:00", $lastlastweek['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $lastlastweek['endtime']);
                }
            }else{
                if ($timetype == 3) {
                    $thisweek = thisweek();
                    $strtime = date("Y-m-d 00:00:00", $thisweek['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $thisweek['endtime']);
                }
                if ($timetype == 4) {
                    $lastweek = lastweek();
                    $strtime = date("Y-m-d 00:00:00", $lastweek['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $lastweek['endtime']);
                }
            }
            if(date("j", strtotime($BeijingTime)) == '1' && date("H", strtotime($BeijingTime)) < '12'){
                if ($timetype == 7) {
                    $lastlastmonth = lastlastmonth();
                    $strtime = date("Y-m-d 00:00:00", $lastlastmonth['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $lastlastmonth['endtime']);
                }
                if ($timetype == 6) {
                    $thismonth = thismonth();
                    $strtime = date("Y-m-d 00:00:00", $thismonth['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $thismonth['endtime']);
                }
            }else{
                if ($timetype == 7) {
                    $thismonth = thismonth();
                    $strtime = date("Y-m-d 00:00:00", $thismonth['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $thismonth['endtime']);
                }
                if ($timetype == 6) {
                    $lastmonth = lastmonth();
                    $strtime = date("Y-m-d 00:00:00", $lastmonth['strarttime']);
                    $endtime = date("Y-m-d H:i:s", $lastmonth['endtime']);
                }
            }
        //日期

        $searchtime = array();
        $searchtime['strtime'] = $strtime;
        $searchtime['endtime'] = $endtime;

        return $searchtime;
    }
    function getGameAccount($length, $site = '') {
        $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $site.$hash;
    }


    /**
     * 記錄接口執行時間
     *
     * @param $actionName 時間節點名稱
     *
     */
    function recordTime($actionName, $redis = null)
    {
        global $nowTime;
        global $actionProcess;
        global $lastTime;

        $nowTime = microtime(true);
        $actionProcess[$actionName] =  number_format(($nowTime - $lastTime)*1000, 4).'ms';

        // 记录具体的参数。测试时会用到。
        /*
        if (!empty($redis)) {
            $num = $nowTime - $lastTime;
            $num = round($num*1000);
            $redis->HINCRBY('api_send_gift_time', $actionName, $num);
        }
        */

        $lastTime = $nowTime;
    }


    /**
     * 超簡化版仿Thinkphp函數
     *
     * @param string $key 需要獲取的key
     *
     */
    function C($key='')
    {
        // 全局化自定義配置
        global $C;

        if (isset($C[$key])) {
            return $C[$key];
        } else {
            return null;
        }

    }


if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * 获取当前页面完整URL地址
 */
function getCompleteUrl()
{
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}