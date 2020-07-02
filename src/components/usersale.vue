<template>
  <div id="usersale">
		<div style="display: flex;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-input v-model="sel_text" placeholder="请输入用户openid" style="width: 300px;">
					<i slot="suffix" class="el-input__icon el-icon-search" @click="select_condition(1)" style="cursor: pointer;"></i>
				</el-input>
			</div>
		</div>
		<el-table
			v-show="total>0"
			:data="usersaleData"
			ref="userdata">
			<el-table-column 
				width="250"
				label="店铺"
				prop="mkid"
				:formatter="switch_mkid">
			</el-table-column>
			<el-table-column 
				label="商品"
				prop="gname">
			</el-table-column>
			<el-table-column 
				label="数量"
				prop="count">
			</el-table-column>
			<el-table-column 
				label="总金额"
				prop="subtotal"
				:formatter="switch_subtotal">
			</el-table-column>
			<el-table-column 
				label="时间"
				prop="time"
				:formatter="switch_time">
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
#usersale{
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
	name: 'Usersale',
	data() {
		return{
			iconName: 'el-icon-s-fold',
			sel_text: '',
			usersaleData: [{
				mkid: '',
				mname: '',
				gname: '',
				count: '',
				subtotal: '',
				time: ''
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
			var openid = this.sel_text;
			$.ajax({
				type: 'get',
				url: '/api/user.php?openid='+openid+'&page='+page,
				async: true,
				success: function(data) {
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.usersaleData = data.data;
						_this.total = data.num_results;
						_this.page = page;
					}else{
						_this.usersaleData = [],
						_this.total = 0;
						_this.$message.error('没有查询到用户信息');
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		switch_mkid(row) {
			return row.mkid+' '+row.mname;
		},
		switch_subtotal(row) {
			return (row.subtotal/100).toFixed(2);
		},
		switch_time(row) {
			return this.commonJS.get_date(row.time);
		}
	}
}
</script>