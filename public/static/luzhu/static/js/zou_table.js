
// var res_org_obj = {// 主要测试 和 数据的
//     "k0":{"result":1,"ext":0},"k1":{"result":1,"ext":1},"k2":{"result":1,"ext":2},"k3":{"result":1,"ext":3},"k4":{"result":2,"ext":0},"k5":{"result":1,"ext":0},"k6":{"result":1,"ext":0},"k7":{"result":1,"ext":0},"k8":{"result":1,"ext":0},"k9":{"result":2,"ext":0},
//     "k10":{"result":2,"ext":0},"k11":{"result":2,"ext":1},"k12":{"result":1,"ext":1},"k13":{"result":2,"ext":0},"k14":{"result":2,"ext":0},"k15":{"result":2,"ext":0},"k16":{"result":1,"ext":0},"k17":{"result":2,"ext":0},"k18":{"result":2,"ext":0},"k19":{"result":2,"ext":0},
//     "k20":{"result":1,"ext":2},"k21":{"result":3,"ext":0},"k22":{"result":3,"ext":1},"k23":{"result":3,"ext":0},"k24":{"result":2,"ext":0},"k25":{"result":1,"ext":1},"k26":{"result":1,"ext":0},"k27":{"result":1,"ext":0},"k28":{"result":1,"ext":0},"k29":{"result":1,"ext":0},
//     "k30":{"result":1,"ext":3},"k31":{"result":2,"ext":0},"k32":{"result":1,"ext":2},"k33":{"result":1,"ext":0},"k34":{"result":3,"ext":0},"k35":{"result":2,"ext":2},"k36":{"result":2,"ext":0},"k37":{"result":1,"ext":0},"k38":{"result":1,"ext":0},"k39":{"result":1,"ext":0},
//     "k40":{"result":1,"ext":0},"k41":{"result":1,"ext":0},"k42":{"result":3,"ext":0},"k43":{"result":1,"ext":0},"k44":{"result":2,"ext":0},"k45":{"result":2,"ext":3},"k46":{"result":2,"ext":0},"k47":{"result":1,"ext":0},"k48":{"result":1,"ext":0},"k49":{"result":1,"ext":0},
// };
// 开局配置时间
/**
 * - 109
 * 
 */
// 数据类型定义
/**
 * 主结果：1 庄(龙) 2 闲(虎) 3 和 4 幸运6
 * 扩展： 0 无 1 庄对 2 闲对 3 庄对 + 闲对
 * */
// 是否有结果了
var resultFlag = 0;
 // 时间句柄
var set = '';
var res_now = {'result':0,'ext':0}
var time_start_init = 45; // start 内部有重置的数据
var num_pu = 0;// 初始化铺号
var num_xue = 1; // 初始化靴号
var num_tai = 1; // 初始化台桌名称
var key_now = 0;
var key_pre = 0;
// 此处重新处理函数
$(document).keydown(function(event){
	// 默认数据向前存档
	console.log(event.keyCode)
	key_pre = key_now
	key_now = event.keyCode
	
	// 开局 - 109
	if(event.keyCode == 109){
		start()
	}
	// 结束 * 106
	if(event.keyCode == 106){
		end()
	}
	// 重新开始 00 96/96  | 99 105/105
	if(event.keyCode == 105){
		if(key_pre == 105){
			resetXueNumber()
		}
	}
	// 全屏 88 104/104
	if(event.keyCode == 104){
		if(key_pre == 104){
			fullScreen()
		}
	}
	// 退出全屏 89 104/105
	if(event.keyCode == 105){
		if(key_pre == 104){
			exitFullScreen()
		}
	}
	// 清理台桌之前的数据 66 102/102
	if(event.keyCode == 102){
		if(key_pre == 102){
			clearOneTable()
		}
	}
	
	// 撤回 7 103  撤回上一铺结果
	if(event.keyCode == 103){
		cancel()
	}
	
	// 换靴加 + 107
	if(event.keyCode == 107){
		changeXue('+')
	}
	
	// 换靴减 + 111
	if(event.keyCode == 111){
		changeXue('-')
	}
	
	// // 换铺加 8 104  应客户需求，取消
	// if(event.keyCode == 104){
	// 	changePu('+')
	// }
	
	// // 换铺减 9 105  应客户需求，取消
	// if(event.keyCode == 105){
	// 	changePu('-')
	// }
	
	// 处理业务逻辑 庄 / 闲 / 对 1，2，3
	if(event.keyCode == 97){
		if(checkTimeStatusFaile()){
			return;
		}
		res_now = {'result':0,'ext':0}
		res_now.result = 1
		showResult()
	}
	if(event.keyCode == 98){
		if(checkTimeStatusFaile()){
			return;
		}
		res_now = {'result':0,'ext':0}
		res_now.result = 2
		showResult()
	}
	if(event.keyCode == 99){
		if(checkTimeStatusFaile()){
			return;
		}
		res_now = {'result':0,'ext':0}
		res_now.result = 3
		showResult()
	}
	// 庄对 + 闲对 4，5
	if(event.keyCode == 100){
		if(key_pre != 101){
			res_now.ext = 1;
		}else{
			res_now.ext = 3;
		}
		showResult()
	}
	if(event.keyCode == 101){
		if(key_pre != 100){
			res_now.ext = 2;
		}else{
			res_now.ext = 3;
		}
		showResult()
	}
	
	// 确认结果 enter 13 小键盘
	if(event.keyCode == 13 ){
		sentReslt()
	}

});

// 倒计时检测
function checkTimeStatusFaile(){
	end()
	console.log('resultFlag:'+resultFlag)
	if(resultFlag == 2){
		alert("请开局倒计时")
		return true;
	}
	return false;
}

// 显示结果
function showResult(){
	$("#result_dian_leftn").hide()
	$("#result_dian_right").hide()
	
	if(res_now.result == 1){
		$("#result_quan").css("border-color","#af071e")
		$("#result_zi").css("color","#af071e")
		$("#result_zi").text('庄')
	}
	if(res_now.result == 2){
		$("#result_quan").css("border-color","#1c2bea")
		$("#result_zi").css("color","#1c2bea")
		$("#result_zi").text('闲')
	}
	if(res_now.result == 3){
		$("#result_quan").css("border-color","#01B90F")
		$("#result_zi").css("color","#01B90F")
		$("#result_zi").text('和')
	}
	if(res_now.result == 4){
		$("#result_quan").css("border-color","#af071e")
		$("#result_zi").css("color","#af071e")
		$("#result_zi").text('6')
	}
	if(res_now.ext == 0){
		$("#result_dian_leftn").hide()
		$("#result_dian_right").hide()
	}
	if(res_now.ext == 1){
		$("#result_dian_leftn").show()
		$("#result_dian_right").hide()
	}
	if(res_now.ext == 2){
		$("#result_dian_leftn").hide()
		$("#result_dian_right").show()
	}
	if(res_now.ext == 3){
		$("#result_dian_leftn").show()
		$("#result_dian_right").show()
	}
	
	
	$('.show_result').show()
}
var p = document.getElementById('time')

function timeDown() {
    time_start--;
    p.innerHTML = time_start;
    if(time_start === 0) {
        p.innerHTML = 0;
		end()
        clearInterval(set);
    }
}

function start(){
	// 判读是否可以倒计时
	var num_pu = parseInt($("#num_pu").html());
	if(num_pu > 1){
		// 判断 是否有结果了
		if (resultFlag == 1){
			alert("请输入结果")
			return;
		}
	}
	// 重置结果样式
	resultFlag = 1;
	
	// 倒计时的设计
	time_start = (parseInt($("#set_time").html())>0)?parseInt($("#set_time").html()):time_start_init;; // 倒计时重置数据
	$("#time").html(time_start)
	$(".right_action_title_en").hide()
	$(".right_action_title_name").hide()
	$(".right_action_title_num").show()
	set = setInterval('timeDown()', 1000);
	
}
function end(){
	$(".right_action_title_en").show()
	$(".right_action_title_name").show()
	$(".right_action_title_num").hide()
	clearInterval(set);
}
function reset(){
	// 倒计时的设计
	time_start = (parseInt($("#set_time").html())>0)?parseInt($("#set_time").html()):time_start_init; // 倒计时重置数据
	$("#time").html(time_start)
	$(".right_action_title_en").hide()
	$(".right_action_title_name").hide()
	$(".right_action_title_num").show()
	set = setInterval('timeDown()', 1000);
	// 没有铺号的提升而已
}
function resetXueNumber(){
	// 重置结果
	var tableId = $.getUrlParam('tableId');
	var num_xue = parseInt($("#num_xue").html());
	num_xue++
	
	if(confirm('确实要重新开局?')){
		$.getJSON(baseUrl+'/api/diantou/table/AddXue/gameType/3/tableId/'+tableId+'/num_xue/'+num_xue,function(data){
			if(data.code == 1){
				console.log("重新开局tableId:"+tableId+" 靴号:"+num_xue)
			}else{
				console.log("获取数据失败")
			}
		})
		window.location.reload(true)
	}	
}
// 撤回结果 本局结果，相当于重置了
function cancelLocal(){
	// 重置结果
	res_now = {'result':0,'ext':0}
	$(".show_result").hide()
	$("#result_dian_leftn").hide()
	$("#result_dian_right").hide()
}
// 撤回结果
function cancel(){
	// 重置结果
	var tableId = $.getUrlParam('tableId');
	var num_xue = parseInt($("#num_xue").html());
	var num_pu = parseInt($("#num_pu").html());
	num_pu -= 1;
	
	if(confirm('确实要删除'+num_xue+'靴'+num_pu+'铺数据?')){
		$.getJSON(baseUrl+'/api/diantou/table/ClearLuZhu/gameType/3/tableId/'+tableId+'/num_xue/'+num_xue+'/num_pu/'+num_pu,function(data){
			if(data.code == 1){
				console.log("取消tableId:"+tableId+" 靴号:"+num_xue+"铺号:"+num_pu)
			}else{
				console.log("获取数据失败")
			}
		})
		window.location.reload(true)
	}	
}
// 清除一个台桌的全部历史
function clearOneTable(){
	// 重置结果
	var tableId = $.getUrlParam('tableId');
	var num_xue = parseInt($("#num_xue").html());
	var num_pu = parseInt($("#num_pu").html());
	num_pu -= 1;
	
	if(confirm('确实要删除'+tableId+'号台桌的全部数据?')){
		$.getJSON(baseUrl+'/api/diantou/table/ClearLuZhuOneTable/gameType/3/tableId/'+tableId,function(data){
			if(data.code == 1){
				console.log("取消tableId:"+tableId)
			}else{
				console.log("获取数据失败")
			}
		})
		window.location.reload(true)
	}	
}
// 换靴号
function changeXue(type){
	if(type == '+'){
		num_xue++
	}
	if(type == '-'){
		num_xue--
	}
	$("#num_xue").text(num_xue)
}
// 换铺号
function changePu(type){
	if(type == '+'){
		num_pu++
	}
	if(type == '-'){
		num_pu--
	}
	$("#num_pu").text(num_pu)
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 系统启动函数
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
var res_org_obj = {};
var tableId = $.getUrlParam('tableId');
// 发送结果
function sentReslt(){
	if(res_now.result == 0){
		alert("请开局后发送")
		return
	}
	$('.show_result').hide()
	console.log('发送结果到后台/ 上传到服务器')
	var num_pu = parseInt($("#num_pu").html());
	var postData = {
		'tableId':tableId,
		'xueNumber':parseInt($("#num_xue").html()),
		'puNumber':parseInt($("#num_pu").html()),
		'result':res_now.result,
		'ext':res_now.ext
	}
	$.ajax({
         type: "POST",           
         url: baseUrl+'/api/diantou/table/postData/gameType/3',             
         data: postData,                          
         contentType: "application/x-www-form-urlencoded",
         success: function (data, status) {
			
			resultFlag = 2; // 结果已发送
			res_now.result = 0;
			res_now.ext = 0;
			
			console.log("post OK result:"+resultFlag)

			// 铺号的设计
			num_pu++
			$("#num_pu").text(num_pu);			 
		 }
	})
}
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// 系统启动函数
// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
var arrLength_pre = 0
function startZoushi(){
	var xue = parseInt($("#num_xue").html());
	// 获取走势图数据
	$.getJSON(baseUrl+'/api/diantou/table/getData/gameType/3/tableId/'+tableId+'/xue/'+xue,function(data){
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
			show_zhuang_xian_wenlu_for_luzhu();
		}else{
			console.log("获取数据失败")
		}
	})
}
// 执行开始
var timeHandle = setInterval(startZoushi,1000)

