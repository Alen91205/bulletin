# 公告

網址:
	1. C:\Windows\System32\drivers\etc 添加 example.com
	2. vhost添加 example.com 指向專案路徑 ag/Public

mysql設定檔:
	ag/Core/mysql.class.php 需配置 DB_HOST, DB_USER, DB_PWD

資料表: 
	t_notice

後端開啟網址示意
	公告頁面 (本頁含刪除按鈕.新增按鈕)
	example.com/?c=notice&m=index

	公告新增
	example.com/?c=notice&m=add

	公告修改
	example.com/?c=notice&m=edit


API 介接方法
	api url : example.com/?c=api&m=getdata
	Response :
	{
		"status":"0",
		"msg":[
			{
				"id":"1",
				"title":"標題一",
				"content":"內容一"
			},
			{
				"id":"2",
				"title":"標題二",
				"content":"內容二"
			},
			{
				"id":"3",
				"title":"標題三",
				"content":"內容三"
			}
		]
	}


服務架構
	apache mysql php 