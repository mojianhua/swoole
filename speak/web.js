var msg = document.getElementById('msg');
var wsServer = 'ws://0.0.0.0:9090';    //调用websocket对象建立链接 ://参数ws/wss(加密) :ip:port (字符串)
var websocket = new WebSocket(wsServer);
websocket.onopen = function(evt){
	//应该显示远程服务器链接成功
	//msg.innerHTML = websocket.readyState;
	//websocket.readyState 属性
	//CONNECTING 0 还没开启
	//OPEN 1 已经开启
	//CLOSEING 2 正在关闭
	//CLOSE 3 已经关闭或者不能打开
	console.log(websocket.readyState);
};

//onmessage 监听服务器推送
websocket.onmessage = function(evt){
	msg.innerHTML += evt.data + '<br>'; //递增数据
	console.log('从服务器获取并取得的数据' + evt.data);
};

//监听联系关闭
websocket.onclose = function(evt){
	console.log('服务器拒绝');
}

//监听错误信息
websocket.onerror = function(evt,e){
	console.log('错误：'+ evt.data);
}

//发送信息
function send_msg(){
	var text = document.getElementById('text').value; //获取数据
	//alert(text);
	document.getElementById('text').value = ''; //清空数据
	websocket.send(text);
}

//发送昵称
function send_name(){
	var text = document.getElementById('myname').value; //获取昵称
	websocket.send("#name#"+text); //发送昵称到服务器
	var myTitle = document.getElementById('myTitle');
	myTitle.innerHTML = "IM" + text;
	alert('设置成功');
	var setName = document.getElementById('setName');
	setName.style.display = "none";
	var send_msg = document.getElementById('send_msg');
	send_msg.style.display = "block";
}