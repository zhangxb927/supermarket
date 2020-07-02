<template>
	<div>
		<el-container>

			<el-header style="height: 61px;padding: 0;box-shadow: 0 2px 4px 0 rgba(0,0,0,.4);opacity: 0.8;">
				<el-menu
					:default-active="this.$route.path"
					router
					class="el-menu-demo"
					mode="horizontal"
					@select="handleSelect"
					background-color="#545c64"
					text-color="#fff"
					active-text-color="#ffd04b">
					<el-menu-item index="0" style="opacity: 1;font-size: 18px;font-weight: bold;" disabled>无人超市后台管理系统</el-menu-item>
					<el-menu-item class="right_item" index="/login" @click="logout" style="margin-right: 20px;"><i class="el-icon-close"></i>注销</el-menu-item>
					<el-menu-item class="right_item" index="/home"><i class="el-icon-menu"></i>首页</el-menu-item>	
				</el-menu>
			</el-header>	

			<el-aside class="y-scroll" style="width: 250px;box-shadow: 0 0 10px 0 rgba(0,0,0,.6);opacity: 0.8;">
				<el-row class="left_menu">
					<el-col :span="12">
						<el-menu
							:default-active="this.$route.path"
							router
							class="el-menu-vertical-demo"
							background-color="#545c64"
							text-color="#fff"
							active-text-color="#ffd04b">
							<div class="user-info">
								<i class="iconfont icon-ren"></i>
								<span class="username">用户：{{username}}</span>
								<span class="identity">身份：{{identity}}</span>
							</div>
							<el-submenu index="1" v-show="managershow">
								<template slot="title">
									<i class="iconfont icon-guanliyuan"></i>
									<span>管理员设置</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="/addmanager">添加管理员</el-menu-item>
									<el-menu-item index="/managerlist">管理员列表</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
							
							<el-submenu index="2" >
								<template slot="title">
									<i class="iconfont icon-chaoshi"></i>
									<span>超市管理</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="/addmarket" v-show="marketshow">添加超市</el-menu-item>
									<el-menu-item index="/marketlist">超市列表</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
							
							<el-submenu index="3">
								<template slot="title">
									<i class="iconfont icon-shangpin"></i>
									<span>商品管理</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="/addgoods" v-show="marketshow">添加商品</el-menu-item>
									<el-menu-item index="/goodslist">商品列表</el-menu-item>
									<el-menu-item index="/goodscount">库存表</el-menu-item>
								</el-menu-item-group>
							</el-submenu>

							<el-submenu index="4">
								<template slot="title">
									<i class="iconfont icon-xiaoshou"></i>
									<span>销售管理</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="/salelist">销售列表</el-menu-item>
									<el-menu-item index="/saleanalysis">销售分析</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
							
							<el-submenu index="5">
								<template slot="title">
									<i class="iconfont icon-yonghu"></i>
									<span>用户列表</span>
								</template>
								<el-menu-item-group>
									<el-menu-item index="/userlist">用户列表</el-menu-item>
									<el-menu-item index="/usersale">消费详情</el-menu-item>
								</el-menu-item-group>
							</el-submenu>
						</el-menu>
					</el-col>
				</el-row>
			</el-aside>
			
			<el-main class="y-scroll">
				<router-view></router-view>
			</el-main>
		</el-container>	
	</div>	
</template>

<style scoped>
.el-container {
  height: 100%;
}
.right_item{
	float: right;
}
.el-aside{
  display: block;
  position: absolute;
  left: 0;
  top: 62px;
  bottom: 2px;
	overflow-y: auto;
	overflow-x: hidden;
}
.left_menu,.el-col,.el-menu{
	width: 100%;
	height: 100%;
}
.el-col-12{
	width: 100%;
}
.el-main{
	display: block;
  position: absolute;
  left: 250px;
  top: 62px;
	bottom: 2px;
	right: 0px;
	overflow-y: auto;
	overflow-x: hidden;
	padding: 0;
}
.icon-ren{
	font-size: 40px;
}
.user-info{
	color: white;
	padding: 20px 0 5px 20px;
	position: relative;
}
.username{
	position: absolute;
	top: 20px;
	left: 80px;
	font-size: 14px;
}
.identity{
	position: absolute;
	top: 48px;
	left: 80px;
	font-size: 14px;
}
.iconfont{
	color: white;
}
</style>

<script>
export default {
	name: 'Main',
	data() {
		return{
			activeIndex_top: '1',
			username: '',
			identity: '',
			managershow: false,
			marketshow: false
		}
	},
	methods: {
		handleSelect(key, keyPath) {
			
		},
		show_userinfo() {
			var _this = this
			var userinfo = _this.$store.state.userinfo;
			if(!userinfo){
				_this.$message.error('用户信息获取失败');
				return;
			}
			userinfo = JSON.parse(userinfo);
			this.username = userinfo.name;
			this.identity = _this.commonJS.get_level(userinfo.level);
			if(userinfo.level==0){
				this.managershow = true;
			}
			if(userinfo.level==0||userinfo.level==2){
				this.marketshow = true;
			}
		},
		logout() {
			var _this = this;
			sessionStorage.removeItem('state');
			sessionStorage.removeItem('token');
			_this.$store.commit("saveInfo",'');
			_this.$store.commit("addToken",'');
		}
	},
	mounted() {
		this.show_userinfo();
	}
}
</script>