<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL & ~E_DEPRECATED);
class baseController extends Controller
{
    public $pids;
    public $orginazition=[];
    public $lowers=[];

    protected $sortType = 'DESC';// 排列順序ASC正序， DESC 倒序
    protected $orderBy = '';// 排列字段
    protected $orderByList = [];// 排列字段的列表

    protected $isBigData = false;// 是否大數據模式

    public function __construct() 
    {
        parent::__construct();

    }
    
    public function getPid()
    {
        $m = new agentModel();
        $this->pids = $m->getAll();
    }

    protected function getOrderBy()
    {
        // 排序类型
        $this->sortType = !empty($_GET['sort_type']) ? $_GET['sort_type'] : 'DESC';
        // 排序字段編號
        $this->orderBy = !empty($_GET['order_by']) ? $_GET['order_by'] : '';
    }

    protected function setOrderBy($order = '')
    {
        if (!empty($this->orderBy)) {
            $order = $this->orderBy . ' ' . $this->sortType;
        }
        return $order;
    }

    protected function setOrderByList($orderByList = [])
    {
        $this->orderByList = $orderByList;
    }

    protected function getSortDataBy($data)
    {
        // 根據$this->orderBy和$this->sortType排序。
        return self::arraySort($data, $this->orderBy, $this->sortType);

    }

    // 根據數組的key值排序
    public function arraySort($array, $keySort, $sort='ASC')
    {
        $newArr = $valArr = [];

        if (strpos($keySort, '.') !== false) {
            $keySortList = explode('.', $keySort);
            $num = count($keySortList);
            if ($num == 2) {
                foreach ($array as $key => $value) {
                    $valArr[$key] = $value[$keySortList[0]][$keySortList[1]];
                }
            } elseif ($num == 3) {
                foreach ($array as $key => $value) {
                    $valArr[$key] = $value[$keySortList[0]][$keySortList[1]][$keySortList[2]];
                }
            }
            // 暫時只支持三層。如果有更多層，可以在這裡添加。

        } else {
            foreach ($array as $key => $value) {
                $valArr[$key] = $value[$keySort];
            }
        }

        ($sort == 'ASC') ? asort($valArr) : arsort($valArr);//先利用keys对数组排序，目的是把目标数组的key排好序

        reset($valArr); //指针指向数组第一个值

        foreach($valArr as $key=>$value) {
            $newArr[$key] = $array[$key];
        }

        return $newArr;
    }

    /**
     * 獲取查詢用的時間間隔
     *
     * @param $timetype
     * @return array
     */
    protected function getTime($timetype, $strtime, $endtime)
    {
        //北京時間
        $BeijingTime = date("Y-m-d H:i:s");
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
        if (date("w", strtotime($BeijingTime)) == '1' && date("H", strtotime($BeijingTime)) < '12' || date("w", strtotime($BeijingTime)) == '0') {
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
        } else {
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
        if (date("j", strtotime($BeijingTime)) == '1' && date("H", strtotime($BeijingTime)) < '12') {
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
        } else {
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

        return array($strtime, $endtime);
    }

    // 獲取導出CSV時，編碼前的編碼。
    public static function getCsvCodeFrom()
    {
        return 'UTF-8';
    }

    // 獲取導出CSV時，編碼後的編碼。
    public static function getCsvCodeTo()
    {
        // 中文繁體用BIG5，中文簡體用GBK
//        return 'BIG5';
        return isset($_GET['csvCode']) && !empty($_GET['csvCode']) ? $_GET['csvCode'] : 'GBK';
    }

    /**
     * 獲取並導出CSV文件。
     * 僅適用於統計類。不適用於明細類。
     *
     * @param array  $data     需要導出的數據 加引用，減少佔用內存。
     * @param string $csvTitle 第一行列表的標題名。
     * @param string $fromCode 編碼前編碼。
     * @param string $file     導出文件的絕對路徑。
     * @param array  $notHaves 不需要的字段名.
     * @param string $csvEnd   最後一行添加結尾.
     * @param string $toCode   編碼後編碼.
     */
    protected function getCsv(&$data, $csvTitle, $fromCode, $file, $notHaves = [], $csvEnd = '', $toCode = 'UTF-8')
    {
        $csv = $csvTitle;

        $id = 1;

//        echo 'getCsv begin:';
//        var_dump(memory_get_usage());

        // foreach為引用，降低內存使用。
        foreach ($data as &$val) {

            if (!empty($notHaves)) {
                foreach ($notHaves as $value) {
                    unset($val[$value]);
                }
            }

            // $csv .= iconv('UTF-8', $strCode, $id . ',' . implode(",", $val) . "\r\n");

            // 處理big5編碼，iconv報錯會中斷，所以用mb_convert_encoding。
            $csv .= mb_convert_encoding($id . ',' . implode(",", $val) . "\r\n", $toCode, $fromCode);
            $id++;
        }

//        echo 'getCsv end:';
//        var_dump(memory_get_usage());

        $csv .= $csvEnd;
        // 覆蓋變量，降低內存使用。
        $csv = substr($csv, 0, -2);
        file_put_contents($file, $csv);

        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));

        readfile($file);
    }

    /**
     *
     * 批量獲取in數組。並以inKey作為查詢key,以outKey作為輸出key
     *
     * @param object $model  數據庫對象
     * @param string $idList 批量查詢的數組
     * @param string $inKey  需要什麼字段IN,查詢key
     * @param string $outKey  輸出數組的key
     * @param integer $limit  限制
     * @param string $order  排序
     * @param string $col     需要獲取的字段
     * @param string $where   補充查詢條件
     * @param string $after   where后的查詢條件
     * @return array
     */
    protected function selectInArray($model, $idList, $inKey, $outKey, $limit, $order = 'id desc',$col='*', $where = "true", $after = '')
    {
        $idStr = implode('\',\'', $idList);
        $idStr = "'" . $idStr . "'";
        $where .=' AND '.$inKey.' IN (' . $idStr . ')';

        if (!empty($after)) {
            $where .= $after;
        }

        $re = $model->select($where, $limit, $order, $col);

        $dataList = [];
        if (!empty($re) && is_array($re)) {
            foreach ($re as $value) {
                $dataList[$value[$outKey]] = $value;
            }
        }

        return $dataList;
    }

}

