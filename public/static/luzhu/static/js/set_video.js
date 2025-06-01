var videoPcUrl = '/autoplayer/index.html?tableVideo='
var videoH5Url = '/autoplayer/index.html?tableVideo='
var videoUrl = ''
var isFarVideo = true;
var far_video = ''
var near_video = ''
var tableId = $.getUrlParam('tableId');


function setVideo() {
	var videoNear = document.getElementById('videoNear')
	videoNear.setAttribute('src',videoUrl+near_video)
	var videoFar = document.getElementById('videoFar')
	videoFar.setAttribute('src',videoUrl+far_video)
}

function handleAdjust() {
    isFarVideo = !isFarVideo
    var adjust = document.getElementById('adjust')
    var videoNear = document.getElementById('videoNear')
    var videoFar = document.getElementById('videoFar')
    if(isFarVideo) {
        adjust.setAttribute('src','./static/img/enlarge.png')
        videoNear.style.zIndex = 0
        videoFar.style.zIndex = 1
    }else{
        adjust.setAttribute('src','./static/img/reduce.png')
        videoNear.style.zIndex = 1
        videoFar.style.zIndex = 0
    }
}

/**
 * 判断客户端是pc还是移动端
 * */
function browserRedirect() {
    let isPc = true
    let sUserAgent = navigator.userAgent.toLowerCase();
    if (/ipad|iphone|midp|rv:1.2.3.4|ucweb|android|windows ce|windows mobile/.test(sUserAgent)) {
        //跳转移动端页面
        isPc = false
    }
    return isPc
}


$(function(){
	// 获取基础数据 code = 1 成功  code = 0 失败 msg 信息 data.data 是返回的数据
	$.getJSON(baseUrl+'/api/diantou/table/getTableVideo/tableId/'+tableId,function(data){
		if(data.code == 1){
			far_video = data.data.video_far
			near_video = data.data.video_near
			
			// 返回后执行
			videoUrl = browserRedirect() ? videoPcUrl : videoH5Url
			setVideo()
			
		}else{
			console.log("获取数据失败")
		}
	})
	// 操作结束了
})
