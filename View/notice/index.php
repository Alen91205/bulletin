<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
 <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">

<style type="text/css">
    table tbody tr td {
        position: relative;
    }
</style>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/DatePicker/WdatePicker.js"></script>
<script>
   (function() {
     if (!
     /*@cc_on!@*/
     0) return;
     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
     var i= e.length;
     while (i--){
         document.createElement(e[i])
     }
})()
</script>
</head>
<body>
    <div class="col-lg-12">
        <ul class="nav nav-pills nav-stacked">
            <li class="active">
                <a>
                    <h id="noticeAdd" class="btn btn-info">添加</h>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-lg-12" style="margin-top: 20px;">
        <div class="form-inline">
            <div style="position: sticky;top: 0;z-index: 1;">
                <table class="table table-striped border-top dataTable" cellspacing="0" style="width:100%;margin-bottom: 0px;" id='field'>
                    <thead style="background-color: rgb(199, 238, 255);">
                        <tr>
                            <th class="hidden-phone sorting inputwidth-5">公告ID</th>
                            <th class="hidden-phone sorting inputwidth-10">公告标题</th>
                            <th class="hidden-phone sorting inputwidth-12">公告内容</th>
                            <th class="hidden-phone sorting inputwidth-5">基本操作</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div>
                <table class="table table-striped border-top dataTable" cellspacing="0" style="width:100%;" id='content'>
                    <tbody id='UpdatesBet'>
                        <?php if (is_array($data)) {?>
                            <?php foreach ($data as $val) {?>
                                <tr class="all_data" >
                                    <td class="inputwidth-5"><?php echo $val['id']; ?></td>
                                    <td class="inputwidth-13" style="word-wrap: break-word;"><?php echo $val['title']; ?></td>
                                    <td class="inputwidth-15" style="word-wrap: break-word;"><?php echo $val['content']; ?></td>
                                    <td class="inputwidth-5">
            							<a href="?c=notice&m=edit&id=<?php echo $val['id']; ?>" class="alt_btn btn btn-success btn-xs md-5">修改</a>
                                        <input onclick="del(<?php echo $val['id']; ?>);" type="button" value="删除" class="del_cw_btm btn btn-danger btn-xs"/>
                                    </td>
                                </tr>
                            <?php }?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="inner_footer"></div>
<script>
    function changeField()
    {
        $('#UpdatesBet tr').eq(0).find('td').each(function(){
            var $this = $(this);
            var eq = $this.index();
            var width = $this.css('width');
            $('#field thead tr th').eq(eq).css('width', width);
        });
        var width = $('#content').css('width');
        $('#field').css('width', width);
    }
    $(window).load(changeField);

    $(window).resize(function() {
        changeField();
    });

    function del(id) {
        if(confirm("你确定删除吗")) {
            $.get('?c=notice&m=del&id='+id,function(data){
                eval("data="+data);
                if(data.status!=0) {
                    alert(data.msg);
                } else {
                    window.location.reload();
                }
            })
        }
        return false;
    }

    $('#noticeAdd').on('click', function(){
        var url = location.origin + '/?c=notice&m=add'; 
        window.location = url;
    })
</script>
</body>
</html>