<template>
	<div id="addgoods">
		<el-form ref="form" :model="form" :rules="rules" label-width="100px">
			<el-form-item label="超市ID" prop="mkid">
				<el-input v-model="form.mkid" placeholder="请输入超市ID,必填"></el-input>
			</el-form-item>
			<el-form-item label="商品ID" prop="gid">
				<el-input v-model="form.gid" placeholder="请输入商品ID,必填"></el-input>
			</el-form-item>
			<el-form-item label="商品名称" prop="name">
				<el-input v-model="form.name" placeholder="请输入商品名,必填"></el-input>
			</el-form-item>
			<el-form-item label="商品进价/分">
				<el-input v-model="form.sprice" placeholder="不填默认为0,单位：分"></el-input>
			</el-form-item>
			<el-form-item label="商品售价/分" prop="price">
				<el-input v-model="form.price" placeholder="请输入商品售价,单位：分"></el-input>
			</el-form-item>
			<el-form-item label="商品类型" prop="type">
				<el-select v-model="form.type">
					<el-option label="饮品类" :value="0"></el-option>
					<el-option label="零食类" :value="1"></el-option>
					<el-option label="日用品类" :value="2"></el-option>
					<el-option label="其它" :value="3"></el-option>
				</el-select>
			</el-form-item>
			<el-form-item label="生产日期">
				<el-date-picker
					v-model="form.gen_time"
					type="datetime"
					placeholder="选择生产日期"
					format="yyyy-MM-dd HH:mm:ss"
					value-format="yyyy-MM-dd HH:mm:ss"
					style="cursor: pointer;">
				</el-date-picker>
			</el-form-item>
			<el-form-item label="保质期/天">
				<el-input style="width: 220px;" type="number" min="0" v-model="form.expiration_date"></el-input>
				<!-- <el-select v-model="date_type" style="width: 80px;">
					<el-option label="天" value="天"></el-option>
					<el-option label="小时" value="小时"></el-option>
				</el-select> -->
			</el-form-item>
			<el-form-item>
				<el-button type="primary" @click="onSubmit">添加</el-button>
				<el-button @click="resetForm">重置</el-button>
			</el-form-item>
		</el-form>
	</div>
</template>

<style scoped>
#addgoods{
	/* display: flex; */
	min-width: 700px;
	padding: 20px 100px 0 20px;
}
.el-form{
	background-color: #fbfbfb;
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 30px 20px 10px 20px;
}
</style>

<script>
export default {
	name: 'Addgoods',
	data() {
		var validatorMkid = (rule, value, callback) => {
			var reg = new RegExp(/^[0-9]{4}$/);
			if(!reg.test(value)){
				return callback(new Error('超市ID必须为四位数字'));
			}else{
				callback();
			}
		}
		var validatorGid = (rule, value, callback) => {
			var reg = new RegExp(/^[0-9]{8}$/);
			if(!reg.test(value)){
				return callback(new Error('商品ID必须为八位数字'));
			}else{
				callback();
			}
		}
		return{
			form: {
				mkid: '',
				gid: '',
				name: '',
				sprice: '',
				price: '',
				type: 0,
				gen_time: '',
				expiration_date: '',
			},
			rules: {
				mkid: [{
					validator: validatorMkid,trigger: 'blur',required: true
				}],
				gid: [{
					validator: validatorGid,trigger: 'blur',required: true
				}],
				name: [{
					required: true
				}],
				price: [{
					required: true
				}],
				type: [{
					required: true
				}]
			},
		}
	},
	methods: {
		onSubmit() {
			var _this = this;
			this.$refs['form'].validate((valid) => {
				if(valid){
					$.ajax({
						type: 'post',
						url: '/api/goods.php',
						async: true,
						data: {
							'mkid': _this.form.mkid,
							'gid': _this.form.gid,
							'name': _this.form.name,
							'sprice': _this.form.sprice,
							'price': _this.form.price,
							'type': Number(_this.form.type),
							'gen_time': _this.form.gen_time,
							'expiration_date': _this.form.expiration_date
						},
						success: function(res){
							var res = JSON.parse(res);
							if(res.ret==0){
								_this.$message.success('添加成功');
								_this.resetForm();
							}else if(res.ret==1){
								_this.$message.error('添加失败，该超市不存在');
							}else if(res.ret==4){
								_this.$message.error('添加失败，该商品已存在');
							}else{
								_this.$message.error('添加失败');
							}
						},
						error: function(){
							_this.$message.error('服务器异常')
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