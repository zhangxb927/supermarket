<template>
	<div id="login" @keydown="key_login">
		<div class="login-title">无人超市后台管理系统</div>
		<div style="width: 100%;">
			<div class="login-box">
				<div class="title">管理员登录</div><hr>
				<el-form ref="form" :model="form" validatorPass :rules="rules">
					<el-form-item prop="username">
						<el-input placeholder="请输入用户名" v-model="form.username" auto-complete="off">
							<i slot="prefix" class="iconfont icon-yonghu1"></i>
						</el-input>
					</el-form-item>
					<el-form-item prop="password">
						<el-input :type="Pass.type" placeholder="请输入密码" v-model="form.password" auto-complete="off">
							<i slot="prefix" class="iconfont icon-mima"></i>
							<i slot="suffix" class="iconfont" style="cursor: pointer;font-size: 18px;" :class="Pass.icon" @click="changePassIcon"></i>
						</el-input>
					</el-form-item>
					<el-switch
						v-model="checked"
						active-text="记住密码？"
						inactive-color="#ccc">
					</el-switch>
					<!-- <template>
						<el-checkbox v-model="checked">记住密码</el-checkbox>
					</template> -->
					<el-button class="login-submit" @click="submit_login()">登录</el-button>
				</el-form>
				<div class="foot">欢迎登录系统</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
	#login{
		width: 100%;
		height: 100vh;
		background: url(../assets/image/bg.png) no-repeat 0 0 transparent;
		background-size: cover;
		display: flex;
		box-sizing: border-box;
		flex-direction:column; 
	}
	.login-title{
		width: 100%;
		height: 50px;
		text-align: center;
		font-size: 36px;
		display: block;
		color: #727A84;
		margin-top: 50px;
		min-width: 360px;
	}
	.login-box{
		display: block;
		margin: 0 auto;
		width: 380px;
		height: 350px;
		margin-top: 50px;
		background-color: rgba(255, 255, 255, 0.407843);
		border: 1px solid rgba(255, 255, 255, 0.407843);
		border-radius: 20px;
	}
	.title{
		text-align: center;
		color: #727A84;
		margin-top: 20px;
		margin-bottom: 20px;
		font-size: 18px;
	}
	hr{
		background-color: white;
		height: 1px;
		border: none;
	}
	.el-form{
		padding: 0 40px;
		margin-top: 30px;
	}
	.iconfont{
		color: #259EF0;
	}
	.login-submit{
		width: 100%;
		margin-top: 20px;
		background-color: #16A3FF;
		color: white;
	}
	.foot{
		margin-top: 15px;
		text-align: center;
		color: #727A84;
	}
</style>

<script>
export default {
	name: 'Login',
	data() {
		var validatorName = (rule, value, callback) => {
			var reg = new RegExp(/^[a-zA-Z0-9]{4,10}$/);
			if(!reg.test(value)){
				return callback(new Error('用户名错误'));
			}else{
				callback();
			}
		};
		var validatorPass = (rule, value, callback) => {
			var reg = new RegExp(/^[a-zA-Z0-9]{4,10}$/);
			if(!reg.test(value)){
				return callback(new Error('密码错误'));
			}else{
				callback();
			}
		}
		return{
			form: {
				username: '',
				password: ''
			},
			rules: {
				username: [{
					validator: validatorName,trigger: 'blur'
				}],
				password: [{
					validator: validatorPass,trigger: 'blur'
				}]
			},
			checked: false,
			Pass: {
				type: 'password',
				icon: 'icon-hidePass'
			}
		}
	},
	methods: {
		changePassIcon() {
			if(this.Pass.type=='password'){
				this.Pass.type = 'text';
				this.Pass.icon = 'icon-showPass';
			}else {
				this.Pass.type = 'password';
				this.Pass.icon = 'icon-hidePass';
			}
			
		},
		show_userinfo() {
			var userinfo = localStorage.getItem('checked');
			if(userinfo){
				this.checked = true;
				userinfo = JSON.parse(userinfo);
				this.form.username = userinfo.name;
				this.form.password = userinfo.password;
			}
		},
		key_login() {
			var _self = this;			
			var key = window.event.keyCode;
			if(key == 13 || key == 100){
				_self.submit_login();
			}			
		},
		submit_login() {
			var _this = this;
			var name = this.form.username;
			var password = this.form.password;
			if(name != '' && password != ''){
				$.ajax({
					type: 'post',
					url: '/api/login.php',
					async:true,
					data: {
						'name': name,
						'password': password
					},
					success: function(data){
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.$message({
								type: 'success',
								message: '登录成功',
								duration: 800
							});
							if(_this.checked){
								var savedata = data.data;
								savedata.password = _this.form.password;
								localStorage.setItem('checked',JSON.stringify(savedata));
							}
							_this.$store.commit("saveInfo",JSON.stringify(data.data));
							_this.$store.commit("addToken",name+password);
							_this.$router.push('/home');
						}else{
							_this.$message.error('登录失败,用户名或密码错误！');
						}
					},
					error: function(){
						_this.$message.error('登录失败');
					}
				})
			}
		}
	},
	mounted() {
		this.show_userinfo();
	}
}
</script>