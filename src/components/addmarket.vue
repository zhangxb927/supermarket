<template>
  <div id="addmarket">
		<el-form ref="form" :model="form" :rules="rules" label-width="100px">
			<el-form-item label="超市ID" prop="mkid">
				<el-input v-model="form.mkid" placeholder="请输入超市ID,必填"></el-input>
			</el-form-item>
			<el-form-item label="超市名称" prop="descp">
				<el-input v-model="form.descp" placeholder="请输入超市名,必填"></el-input>
			</el-form-item>
			<el-form-item label="超市类型" prop="kind">
				<el-select v-model="form.kind">
					<el-option label="自营超市" value="1"></el-option>
					<el-option label="半合作超市" value="2"></el-option>
					<el-option label="全合作超市" value="3"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="超市经纬度">
				<el-input v-model="form.pos"></el-input>
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onSubmit">注册</el-button>
				<el-button @click="resetForm">重置</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>

<style scoped>
#addmarket{
	/* display: flex; */
	min-width: 700px;
	padding: 20px 100px 0 20px;
}
.el-form{
	background-color: #fbfbfb;
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 30px 20px;
}
</style>

<script>
export default {
	name: 'Addmarket',
	data(){
		var validatorMkid =  (rule, value, callback) => {
			var reg = new RegExp(/^1[0-9]{3}$/);
			if(!reg.test(value)){
				return callback(new Error('超市ID必须为1开头的四位数字'));
			}else{
				callback();
			}
		};
		var validatorDescp = (rule, value, callback) => {
			if(!value){
				return callback(new Error('请输入超市名称'));
			}else{
				callback();
			}
		};
		return{
			form: {
				mkid: '',
				descp: '',
				kind: '1',
				pos: ''
			},
			rules: {
				mkid: [{
					validator: validatorMkid,trigger: 'blur',required: true
				}],
				descp: [{
					validator: validatorDescp,trigger: 'blur',required: true
				}],
				kind: [{
					required: true
				}]
			}
		}
	},
	methods: {
		onSubmit() {
			var _this = this;
			this.$refs['form'].validate((valid) => {
				if(valid){
					$.ajax({
						type: 'post',
						url: '/api/market.php',
						async:true,
						data: {
							mkid: _this.form.mkid,
							descp: _this.form.descp,
							kind: Number(_this.form.kind),
							pos: _this.form.pos
						},
						success: function(data){
							var data = JSON.parse(data);
							if(data.ret == 0){
								_this.$message.success('添加成功');
								_this.resetForm();
							}else if(data.ret == 2){
								_this.$message.error('添加失败,超市已存在');
							}else{
								_this.$message.error('添加失败');
							}
							
						},
						error: function(){
							_this.$message.error('服务器异常');
						}
					})
				}	
			})
		},
		resetForm() {
			this.$refs['form'].resetFields();
		}
	}
}
</script>