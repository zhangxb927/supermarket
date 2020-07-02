export default{
	get_time_Period: function() {
		var now = new Date(),hour = now.getHours() 
		if(hour < 6){return "凌晨好！"} 
		else if (hour < 9){return "早上好！"} 
		else if (hour < 12){return "上午好！"} 
		else if (hour < 14){return "中午好！"} 
		else if (hour < 17){return "下午好！"} 
		else if (hour < 19){return "傍晚好！"} 
		else if (hour < 22){return "晚上好！"} 
		else {return "夜里好！"} 
	},
	get_level: function(level) {
		switch(level){
			case 0:
				return '超级管理员'
			case 1: 
				return '普通管理员'
			case 2:
				return '仓库管理员'
			case 3:
				return '超市合作方管理员'			
		}
	},
	get_date: function(time) {
		var date = new Date(parseInt(time) * 1000);
		if(time==null){
			date = new Date();
		}
		var year = date.getFullYear();
		var month = date.getMonth()+1;
		month = month<10 ? '0'+month : month;
		var day = date.getDate();
		day = day<10 ? '0'+day : day;
    var hour = (date.getHours()<10 ? '0'+date.getHours() : date.getHours());
    var minute = (date.getMinutes()<10 ? '0'+date.getMinutes() : date.getMinutes());
		var second = (date.getSeconds()<10 ? '0'+date.getSeconds() : date.getSeconds());
		return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second; 
	},
	get_kind: function(kind) {
		switch(kind){
			case 1:
				return '自营超市'
			case 2:
				return '半合作超市'
			case 3:
				return '全合作超市'		
		}
	},
	get_state: function(state){
		switch(state){
			case 0:
				return '软锁门'
			case 1:
				return '正常'	
		}
	},
	get_type: function(type){
		switch(type){
			case 0:
				return '饮品类'
			case 1:
				return '零食类';
			case 2:
				return '日用品类';
			case 3:
				return '其它';		
		}
	}
}

