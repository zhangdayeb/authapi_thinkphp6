$(function(){
	// base url
	// 获取 数据
	var tableId = $.getUrlParam('tableId');
	console.log('tableId:'+tableId)
	// 获取 系统 数据
	// 默认 如果 没有 台桌的 ID 直接报错
	if(tableId == null){
		alert("参数错误,请联系技术处理！")
		window.location.href="?tableId=1"
	}
	// 获取基础数据 code = 1 成功  code = 0 失败 msg 信息 data.data 是返回的数据
	$.getJSON(baseUrl+'/api/diantou/table/getTableVideo/tableId/'+tableId,function(data){
		if(data.code == 1){
			//$("#video").attr('src',data.data.video_near);
			$("#video").attr('src',"/player/index.html?id="+data.data.video_far);
		}else{
			console.log("获取数据失败")
		}
	})
	
// 操作结束了
})