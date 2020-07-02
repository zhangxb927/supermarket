<template>
  <div id="addmanager">
		<el-form ref="form" :model="form" :rules="rules" label-width="80px">
			<el-form-item label="姓名" prop="name">
				<el-input v-model="form.name" placeholder="请输入用户名,必填"></el-input>
			</el-form-item>
			<el-form-item label="密码" prop="password">
				<el-input :type="Pass.type" v-model="form.password" placeholder="请输入密码,必填">
					<i slot="prefix" class="iconfont" :class="Pass.icon" @click="changePassIcon"></i>
				</el-input>
			</el-form-item>
			<el-form-item label="确认密码" prop="repassword">
				<el-input :type="RePass.type" v-model="form.repassword" placeholder="请再次输入密码">
					<i slot="prefix" class="iconfont" :class="RePass.icon" @click="changeRePassIcon"></i>
				</el-input>
			</el-form-item>
			<el-form-item label="手机号" prop="phone">
				<el-input v-model="form.phone"></el-input>
			</el-form-item>
			<el-form-item label="邮箱" prop="email">
				<el-input v-model="form.email"></el-input>
			</el-form-item>
			<el-form-item label="类别" prop="level">
				<el-select v-model="form.level">
					<el-option label="普通管理员" value="1"></el-option>
					<el-option label="仓库管理员" value="2"></el-option>
					<el-option label="超市合作方管理员" value="3"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="超市id" prop="mkid" v-show="this.form.level=='3'">
				<el-input v-model="form.mkid"></el-input>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onSubmit">注册</el-button>
				<el-button @click="resetForm">重置</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>

<style scoped>
#addmanager{
	/* display: flex; */
	min-width: 700px;
	padding: 20px 100px 0 30px;
}
i{
	cursor: pointer;
}
.el-form{
	background-color: #fbfbfb;
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 30px 20px;
}
</style>

<script>
export default {
	name: 'Addmanager',
	data() {
		var validatorName =  (rule, value, callback) => {
			var reg = new RegExp(/^[a-zA-Z0-9]{4,10}$/);
			if(!reg.test(value)){
				return callback(new Error('用户名必须为4-10位数字加字母'));
			}else{
				callback();
			}
		};
		var validatorPass = (rule, value, callback) => {
			var reg = new RegExp(/^[a-zA-Z0-9]{4,10}$/);
			if(!reg.test(value)){
				return callback(new Error('密码必须为六位以上数字加字母'));
			}else{
				callback();
			}
		};
		var validatorRePass = (rule, value, callback) => {
			var pass = this.form.password;
			if(value != pass){
				return callback(new Error('两次输入密码不一致'));
			}else{
				callback();
			}
		};
		var validatorPhone = (rule, value, callback) => {
			var reg = new RegExp(/^[0-9]{11}$/);
			if(value!=''&&!reg.test(value)){
				return callback(new Error('请输入正确的手机号'));
			}else{
				callback();
			}
		};
		var validatorEmail = (rule, value, callback) => {
			var reg = new RegExp(/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/);
			if(value!=''&&!reg.test(value)){
				return callback(new Error('请输入正确的邮箱'));
			}else{
				callback();
			}
		};
		return{
			form: {
				name: '',
				password: '',
				repassword: '',
				phone: '',
				email: '',
				level: '1',
				mkid: ''
			},
			rules: {
				name: [{
					validator: validatorName,trigger: 'blur',required: true
				}],
				password: [{
					validator: validatorPass,trigger: 'blur',required: true
				}],
				repassword: [{
					validator: validatorRePass,trigger: 'blur',required: true
				}],
				phone: [{
					validator: validatorPhone,trigger: 'blur'
				}],
				email: [{
					validator: validatorEmail,trigger: 'blur'
				}],
				level: [{
					validator: "请选择类别", trigger: 'change',required: true
				}],
				mkid: [{
					required: true
				}]
			},
			Pass: {
				type: 'password',
				icon: 'icon-hidePass'
			},
			RePass: {
				type: 'password',
				icon: 'icon-hidePass'
			}
		}
	},
	methods: {
		resetForm() {
			this.$refs['form'].resetFields();
		},
		onSubmit() {
			var _this = this;
			this.$refs['form'].validate((valid) => {
				if(valid){
					if(this.form.level == '3' && !this.form.mkid){
						return;	
					}
					$.ajax({
						type: 'post',
						url: '/api/manager.php',
						async:true,
						data: {
							'password': this.form.password,
							'name': this.form.name,
							'email': this.form.email,
							'phone': this.form.phone,
							'level': Number(this.form.level),
							'mkid': this.form.mkid
						},
						success: function(data){
							var data = JSON.parse(data);
							if(data.ret==0){
								_this.$message.success('注册成功');
								_this.resetForm();
							}else if(data.ret==1){
								_this.$message.error('注册失败，该超市不存在')
							}else if(data.ret == 3){
								_this.$message.error('注册失败，该超市不是合作超市')
							}else{
								_this.$message.error('注册失败');
							}
						},
						error: function(data){
							_this.$message.error('服务器异常');
						}
					})
				}	
			})
		},
		changePassIcon() {
			if(this.Pass.type=='password'){
				this.Pass.type = 'text';
				this.Pass.icon = 'icon-showPass';
			}else {
				this.Pass.type = 'password';
				this.Pass.icon = 'icon-hidePass';
			}
			
		},
		changeRePassIcon() {
			if(this.RePass.type=='password'){
				this.RePass.type = 'text';
				this.RePass.icon = 'icon-showPass';
			}else {
				this.RePass.type = 'password';
				this.RePass.icon = 'icon-hidePass';
			}
		}
	}
}
</script>