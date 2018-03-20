# 登入接口
------
## 地址
	index.php?r=user/login
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|userName 	|是  		|string		|用户名		|
|userPass	|是			|string		|登入密码		|

## 响应值
|响应值		|类型 |说明 	|
| -------- 	|  --------      | --------      |
|state 	|int|状态码 详情参看 全局状态码说明	|
|message |string	|文字说明	|
|data 	|array|如果接口有数据返回 在此字段中	|
------

# 添加会员卡
## 地址
	index.php?r=card/add-card
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----	| ---- 	| ----  	|
|cardNo 	|是  		|string		|会员卡卡号  最大长度20 		|
|cardUserName	|是			|string		|持卡人名称 最大长度20 		|
|cardTel	|是			|string		|持卡人电话 最大长度11		|
|cardMoney	|是			|int		|卡金额  单位分  最小0		|


## 响应值
|响应值		|类型 |说明 	|
| -------- 	|  --------      |
|state 	|int|状态码 详情参看 全局状态码说明	|
|message |string	|文字说明	|
|data 	|array|如果接口有数据返回 在此字段中	|


# 获取卡列表
## 地址
	index.php?r=card/add-list
## 请求参数参数
|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|page 	|否  		|int		|页数	 默认值1	|
|pageSize	|否			|int		|一页的数量 默认值20 		|

## 响应
```
{
	"state": 0,
	"message": "success",
	"data": {
		"list": [{
			"id": "24",								//系统编号
			"card_no": "1231231231217",				//卡号
			"card_user_name": "123123123123111",	//持卡人名称
			"card_user_tel": "18651616612",			//持卡人电话
			"card_money": "104080",					//卡金额
			"create_time": "1520929515",			//创建时间
			"mark": null,							//备注
		}],
		"page": 1,									//当前页码
		"pageSize": 15,								//一页的条数
		"total": "24",								//一共的数量
		"totalPage": 2								//一共的页数
	}
}
```

# 获取某张卡详情
## 地址
	index.php?r=card/add-inf
## 请求参数参数
|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|cardId 	|是  		|int		|卡列表中的id	|


## 响应
```
{
	"state": 0,
	"message": "success",
	"data": [{
		"id": "24",								//系统编号
		"card_no": "1231231231217",				//卡号
		"card_user_name": "123123123123111",	//持卡人名称
		"card_user_tel": "18651616612",			//持卡人电话
		"card_money": "104080",					//卡金额
		"create_time": "1520929515",			//创建时间
		"mark": null,							//备注
	}]
}
```


# 查询消费记录
## 地址
	index.php?r=money/get-expense-log
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|cardId 	|是  		|int		|卡列表中的id	|
|page 	|否  		|int		|页数	 默认值1	|
|pageSize	|否			|int		|一页的数量 默认值20 		



## 响应值
```
{
	"state": 0,
	"message": "success",
	"data": {
		"list": [{
			"id": 16,					//系统编号
			"money": 100,				//金额
			"after_mone": 103980,		//本次操作后卡中的余额
			"card_id": 24,				//卡号
			"create_time": 1521533930,	//创建记录的时间
			"type": 2
		}],
		"page": 1,						//当前页码
		"pageSize": 15,					//一页的查询数量
		"total": "12",					//一共的条数
		"totalPage": 1					//一共的页数
	}
}
```


# 查询充值记录
## 地址
	index.php?r=money/get-pay-log
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|cardId 	|是  		|int		|卡列表中的id	|
|page 	|否  		|int		|页数	 默认值1	|
|pageSize	|否			|int		|一页的数量 默认值20 		



## 响应值
```
{
	"state": 0,
	"message": "success",
	"data": {
		"list": [{
			"id": 16,					//系统编号
			"money": 100,				//金额
			"after_mone": 103980,		//本次操作后卡中的余额
			"card_id": 24,				//卡号
			"create_time": 1521533930,	//创建记录的时间
			"type": 2
		}],
		"page": 1,						//当前页码
		"pageSize": 15,					//一页的查询数量
		"total": "12",					//一共的条数
		"totalPage": 1					//一共的页数
	}
}
```

# 添加充值记录
## 地址
	index.php?r=money/add-expense-log
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----	| ---- 	| ----  	|
|cardId 	|是  		|int		|卡列表中的id	|
|money	|是			|int		|消费金额  单位分 最小0		|



##响应值
|响应值		|类型 |说明 	|
| -------- 	|  --------      |
|state 	|int|状态码 详情参看 全局状态码说明	|
|message |string	|文字说明	|
|data 	|array|如果接口有数据返回 在此字段中	|

# 添加消费记录
## 地址
	index.php?r=money/get-expense-log
## 请求参数参数

|参数名称		|是否毕传		|参数类型  	|说明      	|
| -------- 	| -----:	| :----:  	| :----:  	|
|cardId 	|是  		|int		|卡列表中的id	|
|money	|是			|int		|消费金额  单位分 最小0		|



## 响应值
|响应值		|类型 |说明 	|
| -------- 	|  --------      | --------      |
|state 	|int|状态码 详情参看 全局状态码说明	|
|message |string	|文字说明	|
|data 	|array|如果接口有数据返回 在此字段中	|




# 全局状态码说明
|响应值		|说明 	|
| -------- 	|  --------      |
|0 	|请求成功	|
|100 	|参数错误	|
|1000 	|未登入	|

