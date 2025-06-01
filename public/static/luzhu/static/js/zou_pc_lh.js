// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 系统启动函数
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
var res_org_obj = {};
var tableId = $.getUrlParam('tableId');
var xueNumber = $.getUrlParam('xueNumber');
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 系统启动函数
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
var arrLength_pre = 0
function startZoushi(){
	var xue = xueNumber;
	// 获取走势图数据
	$.getJSON(baseUrl+'/api/diantou/table/getData/gameType/2/tableId/'+tableId+'/xue/'+xue,function(data){
		// console.log("成功接收到数据")
		// console.log(data)
		if(data.code == 1){
			var arr = Object.keys(data.data);
			arrLength_now = arr.length
			if(arrLength_now < arrLength_pre){
				window.location.reload();
			}
			arrLength_pre = arr.length
			start_zoushi(data.data);// 启动函数 系统主函数入口
			// 更新庄闲数据
			init_wenlu();
			// 展示 庄闲问路的 路径
			show_zhuang_xian_wenlu();
		}else{
			console.log("获取数据失败")
		}
	})
}
// 执行开始
var timeHandle = setInterval(startZoushi,1000)