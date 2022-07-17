	$(document).ready(function(){
   	 	var tdNods = $(".reset_cw_btm");
    	tdNods.click(resetClick);
		var delNode=$(".del_cw_btm");
		//delNode.click(delClick);
	});

	//修改按钮的点击事件
	function resetClick(){
		var thisbutm=$(this).parent();
		$(".reset_cw_btm").hide();
		var savebtm=$("<input>");
		savebtm.attr("type","submit");
		savebtm.attr("value","保存");
		savebtm.addClass("savebtm");
		savebtm.css({"background":"url('../img/reset_btm_bg.jpg') repeat-x scroll 0% 0% transparent","border":"1px solid #CCC","font-size":"12px","border-radius":"2px","color":"#00ABD1","cursor":"pointer"});
		thisbutm.prepend(savebtm);

		thisbutm.siblings().each(function (){
                        var kind = $(this).attr('kind');
			var td=$(this);
			var tdText=td.text();
			td.text("");
			var input = $("<input>");
			input.css({"width":"95%"});
			input.attr("value",tdText);
                        input.attr("name",td.attr('name'));

                        if(kind == 'selector'){
                            var data =  {
                                            text: tdText,
                                        }
                            $.get('?c=collection&m=changeselect',data, function(data){
                                var select = $("<select>");
                                select.attr("onchange",'showdata1()');
                                select.attr("name",'groupid');
                                select.attr("id",'selectdata1');
                                var select1 = $("<select>");
                                select1.attr("name","grouplvl");
                                select1.attr("id",'changedata1');
                                td.append(select);
                                td.append(select1);
                                if(tdText == "全部/全部"){
                                    var np = new Option("全部", 0, false, true);
                                    var np1 = new Option("全部", 0, false, true);
                                    select.append(np);

                                    select1.append(np1);
                                    for(var val in data.msg){
                                        var np = new Option(data.msg[val][0], data.msg[val][1], false, false);
                                        select.append(np);
                                    }
                                }
                                else{
                                    var splits = tdText.split("/");
                                    var np = new Option("全部", 0, false, false);
                                    var np1 = new Option("全部", 0, false, false);
                                    select.append(np);
                                    select1.append(np1);
                                    for(var val in data.msg[0]){
                                        if(splits[0] == data.msg[0][val][0]){
                                            var np = new Option(data.msg[0][val][0], data.msg[0][val][1], false, true);
                                        }
                                        else{
                                            var np = new Option(data.msg[0][val][0], data.msg[0][val][1], false, false);
                                        }
                                            select.append(np);
                                    }
                                    for(var val1 in data.msg[1]){
                                        if(splits[1] == data.msg[1][val1][0]){
                                            var np1 = new Option(data.msg[1][val1][0], data.msg[1][val1][1], false, true);
                                        }
                                        else{
                                           var np1 = new Option(data.msg[1][val1][0], data.msg[1][val1][1], false, false);
                                        }
                                        select1.append(np1);
                                    }
                                }
                            },"json");
                        }
                        else{
                            td.append(input);
                        }
		});

		var savebtma = $(".savebtm");
		//savebtma.click(save);
                savebtma.click.submit();
	}

	/*
		删除按钮函数
	*/
	function delClick(){
		var del=$(this);
		if(confirm("你确定删除么？")){
			var delpar=del.parent().parent();
			delpar.css({"color":"red"});
			delpar.fadeOut(1000);
			setTimeout(function(){delpar.remove();}, 1000);
		}
	}

	//保存按钮函数
	function save(thisbutm){
		var tds=$(this).parent();
		$(this).remove();$(".reset_cw_btm").show();
		tds.siblings().each(function (){
			var td=$(this);
			td.children("input").each(function(){
				$(this).parent().text($(this).val());
				$(this).remove();
			});
		});
	}
        function showdata1(){
            var data =  {
                            id: document.getElementById("selectdata1").value,
                        }
            if(data.id == 0){
                var html ="";
                html += '<option value="0">全部</option>';
                $("#changedata1").html(html);
                return false;
            }

            $.get('?c=collection&m=getselect', data, function(data){
                var html ="";
                html += '<option value="0">全部</option>';
                for(var val in data.msg){
                    html += '<option value="'+ data.msg[val].id + '">' + data.msg[val].lvlname + '</option>';
                }
                $("#changedata1").html(html);
            },"json");
        }


 
