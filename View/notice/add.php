<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
.left_tab{
    float:left;
    width: 155px;
}

</style>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js"></script>
<script type="text/javascript" language="javascript" src="../js/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="../js/save.js"></script>
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

    $(document).ready(function(){
        $("input[name='bulletin']").click(function(){
            var bulletin = $("input[name='bulletin']:checked").val();
            var display = (bulletin == 1) ? 'none' : 'initial';
            $("select[name='role']").css('display', display);
        });
    });
</script>
</head>
<body>

    <div class="col-lg-12">
        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a> <i id="favorite" class="<?php echo $favorite; ?>" onclick="favorite(<?php echo $pageid; ?>)"></i> <?php echo '添加网站公告';?></a></li>
        </ul>
        <form action="" method="post" name="">
            <div class="col-lg-6 md-25" style="margin-top: 20px;">
                <div class="form-group">
                    <label class='left_tab'><?php echo '公告标题';?>：</label>
                    <span>
                        <input type="text" name="title" style="width: 500px;"></input>
                    </span>
                </div>

                <div class="form-group">
                    <label class='left_tab'><?php echo '公告内容';?>：</label>
                    <textarea  name="content" style="width:500px;height:200px;"></textarea>
                    <div class="text-primary" style="padding-left: 155px;font-style: italic;"> (*<?php echo '只限1000个字符內';?>)</div>
                </div>

                <div class="div_line div_input noborder gonggao_left">
                    <input type="submit" name="btn_submit" name="submit" value="<?php echo '添加';?>" class="me_btm btn btn-success btn-xs"></input>
                    <input type="reset"  name="reset"  value="<?php echo '重填';?>" class="me_btm btn btn-danger btn-xs"></input>
                    <input type="submit" value="<?php echo '上一页';?>" class="btn btn-info btn-xs" onclick="history.go(-1);"></input>
                </div>
            </div>
        </form>
    </div>

    <div class="inner_footer"></div>
<script>
    $('form').ajaxForm(function(data){
        eval("data="+data);
        if(data.status==0) {
            alert(data.msg);
            window.location.href="?c=notice";
        } else {
            alert(data.msg);
        }
    });
</script>
</body>
</html>
