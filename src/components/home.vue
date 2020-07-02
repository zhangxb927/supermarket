<template>
	<div id="home">
		<div class="home-box">
			<div class="home-time">{{time_Period}}{{username}} [ {{identity}} ]</div>
			<div class="last-time">
				<i class="iconfont icon-shijian"></i>
				您上次的登录时间是：{{login_time}}  如非您本人操作，请及时更改密码
			</div>
		</div>
		<img src="../assets/image/font.png" class="font-img" />
	</div>
</template>

<style scoped>
#home{
	width: 100%;
	height: 100%;
	background: url(../assets/image/home_bg.png) no-repeat 0 0 transparent;
	background-size: cover;
	display: flex;
	box-sizing: border-box;
	flex-direction:column; 
}
.home-box{
	margin: 20px 0 0 30px;
}
.font-img{
	position: absolute;
	bottom: 20px;
	right: 20px;
}
.home-time{
	color: #382754;
	font-weight: bold;
	font-size: 20px;
	margin-bottom: 20px;
	line-height: 40px;
}
.last-time{
	font-size: 14px;
	line-height: 35px;
}
</style>

<script>
export default {
	name: 'Home',
	data() {
		return{
			time_Period: '',
			username: '',
			identity: '',
			login_time: ''
		}
	},
	mounted() {
		var _this = this;
		this.time_Period = _this.commonJS.get_time_Period();
		var userinfo = _this.$store.state.userinfo;
			if(!userinfo){
				return;
			}
			userinfo = JSON.parse(userinfo);
			this.username = userinfo.name;
			this.identity = _this.commonJS.get_level(userinfo.level);
			if(userinfo.login_time){
				this.login_time = _this.commonJS.get_date(userinfo.login_time);
			}else{
				this.login_time = '暂无';
			}
			
	}
}
</script>