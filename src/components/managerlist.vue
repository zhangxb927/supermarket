<template>
  <div id="managerlist">
		<el-table
			:data="managerData"
			stripe
			style="width: 100%">
			<el-table-column
				prop="name"
				label="姓名">
			</el-table-column>
			<el-table-column
				prop="phone"
				label="手机号">
			</el-table-column>
			<el-table-column
				prop="email"
				label="邮箱">
			</el-table-column>
			<el-table-column
				prop="level"
				:formatter="levelSwitch"
				label="等级">
			</el-table-column>
			<el-table-column
				prop="mkid"
				label="合作超市">
			</el-table-column>
			<el-table-column
				v-show="operation"
				label="操作">
				<template slot-scope="scope">
					<el-button @click="changeInfo(scope.row)" type="text" size="small"><i class="el-icon-edit"></i> 编辑</el-button>
					<el-button @click="deleteInfo(scope.row.uid)" type="text" size="small"><i class="el-icon-delete"></i> 删除</el-button>
			</template>
    </el-table-column>
		</el-table>
		<el-pagination
			hide-on-single-page
			v-show="total>0"
			background
			layout="prev, pager, next"
			@prev-click='prev'
			@next-click='next'
			@current-change='pager'
			:total="total">
		</el-pagination>
		<el-dialog title="修改信息" :visible.sync="dialogChange">
			<el-form :model="form" label-width="80px">
				<el-form-item label="姓名">
					<el-input v-model="form.name" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="手机号">
					<el-input v-model="form.phone" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="邮箱">
					<el-input v-model="form.email" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="等级" >
					<el-input :value="levelSwitch(form)" auto-complete="off" disabled></el-input>
				</el-form-item>
				<el-form-item label="超市ID" v-show="form.level==3">
					<el-input v-model="form.mkid" auto-complete="off"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogChange = false">取 消</el-button>
				<el-button type="primary" @click="submitChange">确 定</el-button>
			</div>
		</el-dialog>

	</div>
</template>

<style scoped>
#managerlist{
	padding: 20px;
}
.el-table{
	margin-bottom: 20px;
	min-width: 1000px;
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 12px;
}
</style>

<script>
export default {
	name: 'Managerlist',
	data() {
		return{
			managerData: [{
				uid: '',
				name: '',
				phone: '',
				email: '',
				level: '',
				mkid: ''
			}],
			total: 0,
			page: 1,
			dialogChange: false,
			form: {
				uid: '',
				name: '',
				phone: '',
				email: '',
				level: '',
				mkid: ''
			},
			currentPage: 1,
			operation: false
		}
	},
	methods: {
		prev(page) {
			this.currentPage = page;
			this.get_managerInfo(page);
		},
		pager(page) {
			this.currentPage = page;
			this.get_managerInfo(page);
		},
		next(page) {
			this.currentPage = page;
			this.get_managerInfo(page);
		},
		get_managerInfo(page) {
			var _this = this;
			$.ajax({
				type: 'GET',
				url: '/api/manager.php?page='+page,
				async:true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.total = data.num_results;
						_this.managerData = data.data;	
						_this.page = page;	
					}else if(data.ret == -1){
						if(page>1){
							_this.get_managerInfo(page-1);
						}else{
							_this.managerData = [];
							_this.total = 0;
						}
					}else{
						_this.$message.error('获取管理员列表失败');
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		levelSwitch(row, column){
			return this.commonJS.get_level(row.level);
		},
		changeInfo(row) {
			this.form = row;
			this.dialogChange = true;
		},
		deleteInfo(uid) {
			var _this = this;
			_this.$confirm('是否删除此管理员', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			}).then(() => {
				$.ajax({
					type: 'delete',
					url: '/api/manager.php?uid='+uid,
					async:true,
					success: function(data) {
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.$message.success('删除成功');
							_this.get_managerInfo(_this.currentPage);
						}else{
							_this.$message.error('删除失败');
						}
					},
					error: function() {
						_this.$message.error('服务器异常');
					}
				})
			})
			
		},
		submitChange() {
			var _this = this;
			$.ajax({
				type: 'patch',
				url: '/api/manager.php',
				async:true,
				data: JSON.stringify(_this.form),
				success: function(data) {
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.$message.success('修改成功');
						_this.dialogChange = false;
						_this.get_managerInfo(_this.page);
					}else{
						_this.$message.error('修改失败');
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}

			})
		}
	},
	mounted() {
		this.get_managerInfo(1);
	}
}
</script>