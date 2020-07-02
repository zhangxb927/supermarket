<template>
  <div id="userlist">
		<div style="display: flex;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-select filterable placeholder="请选择" style="width: 100px;" v-model="sel_value">
					<el-option label="手机号" :value="0"></el-option>
					<el-option label="昵称" :value="1"></el-option>
				</el-select>
				<el-input v-model="sel_text" :placeholder="sel_value==0?'请输入用户手机号':'请输入用户昵称'" style="width: 180px;">
					<i slot="suffix" class="el-input__icon el-icon-search" @click="select_condition(1)" style="cursor: pointer;"></i>
				</el-input>
			</div>
		</div>
		<el-table
			v-show="total>0"
			:data="userData"
			ref="userdata">
			<el-table-column 
				width="260"
				label="openid"
				prop="openid">
			</el-table-column>
			<el-table-column 
				label="昵称"
				prop="nickname">
			</el-table-column>
			<el-table-column 
				label="手机号"
				prop="phone">
			</el-table-column>
			<el-table-column 
				label="注册超市"
				prop="mkid">
			</el-table-column>
			<el-table-column 
				label="注册时间"
				prop="rtime"
				:formatter="switch_rtime">
			</el-table-column>
		</el-table>
		<el-pagination
			hide-on-single-page
			v-show="total>0"
			background
			layout="prev, pager, next"
			:current-page="page"
			@prev-click='prev'
			@next-click='next'
			@current-change='pager'
			:total="total">
		</el-pagination>
	</div>
</template>

<style scoped>
#userlist{
	padding: 20px;
	min-width: 900px;
}
.el-table{
	margin-top: 10px;
	margin-bottom: 20px;
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 12px;
}
</style>

<script>
export default {
	name: 'Userlist',
	data() {
		return{
			iconName: 'el-icon-s-fold',
			sel_value: 0,
			sel_text: '',
			userData: [{
				openid: '',
				nickname: '',
				phone: '',
				mkid: '',
				rtime: ''
			}],
			total: 0,
			page: 1
		}
	},
	methods: {
		prev(page) {
			this.page = page;
		},
		pager(page) {
			this.select_condition(page);
		},
		next(page) {
			this.page = page;
		},
		search_shrink() {
			if(this.iconName=='el-icon-s-unfold'){
				this.iconName = 'el-icon-s-fold'
			}else{
				this.iconName='el-icon-s-unfold';
			}	
		},
		select_condition(page){
			var _this = this;
			var sel_value = this.sel_value;
			var sel_text = this.sel_text;
			var url;
			if(sel_value==0){
				url = '/api/user.php?phone='+sel_text;
			}else if(sel_value==1){
				url = '/api/user.php?nickname='+sel_text+'&page='+page;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.userData = data.data;
						if(sel_value==1){
							_this.total = data.num_results;
							_this.page = page;
						}else{
							_this.total = 1;
						}
					}else{
						_this.userData = [];
						_this.total = 0;
						_this.$message.error('没有查询到用户信息');
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		switch_rtime(row) {
			return this.commonJS.get_date(row.rtime);
		}	
	}
}
</script>