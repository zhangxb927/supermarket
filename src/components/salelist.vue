<template>
  <div id="salelist">
		<div style="display: flex;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-select filterable placeholder="请选择" style="width: 180px;" v-model="mk_value" :disabled="disable">
					<el-option label="全部超市" :value="0"></el-option>
					<el-option v-for="item in mkData" :key="item.mkid" :label="item.mkid+' '+item.descp" :value="item.mkid"></el-option>
				</el-select>
				——>
				<el-date-picker
					v-model="interval_time"
					type="datetimerange"
					:picker-options="pickerOptions"
					range-separator="至"
					start-placeholder="开始日期"
					end-placeholder="结束日期"
					align="right"
					format="yyyy-MM-dd HH:mm:ss"
					value-format="yyyy-MM-dd HH:mm:ss">	
				</el-date-picker>
				<i title="查询" slot="suffix" class="el-input__icon el-icon-search" @click="select_condition(1)" style="cursor: pointer;"></i>
				<i title="返回" class="iconfont icon-fanhui" @click="select_back" style="cursor: pointer;"></i>
			</div>
		</div>
		<el-table
			ref="saleList"
			:data="saleData"
			style="width: 100%">
			<el-table-column 
				label="超市"
				prop="mkid"
				:show-overflow-tooltip='true'
				:formatter="switch_market">
			</el-table-column>
			<el-table-column 
				label="商品ID"
				prop="gid">
			</el-table-column>
			<el-table-column
				:show-overflow-tooltip='true'
				label="商品名称"
				prop="gname">
			</el-table-column>
			<el-table-column 
				label="售价/元"
				prop="price"
				:formatter="switch_price">
			</el-table-column>
			<el-table-column 
				label="数量"
				prop="count">
			</el-table-column>
			<el-table-column 
				label="小计/元"
				prop="subtotal"
				:formatter="switch_subtotal">
			</el-table-column>
			<el-table-column
				width="260" 
				label="用户"
				prop="openid"
				:show-overflow-tooltip='true'>
			</el-table-column>
			<el-table-column
				width="180"
				:show-overflow-tooltip='true'
				label="支付时间"
				prop="time"
				:formatter="switch_time">
			</el-table-column>
			<el-table-column 
				label="支付方式"
				prop="payway"
				:formatter="switch_payway">
			</el-table-column>
		</el-table>
		<el-pagination
			hide-on-single-page
			v-show="total>0"
			background
			:current-page="page"
			layout="prev, pager, next"
			@prev-click='prev'
			@next-click='next'
			@current-change='pager'
			:total="total">
		</el-pagination>
	</div>
</template>

<style scoped>
#salelist{
	padding: 20px;
	min-width: 1000px;
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
	name: 'Salelist',
	data() {
		return{
			saleData: [{
				mkid: '',
				mname: '',
				gid: '',
				gname: '',
				openid: '',
				price: '',
				sprice: '',
				count: '',
				subtotal: '',
				username: '',
				phone: '',
				time: '',
				payway: ''
			}],
			total: 0,
			page: 1,
			iconName: 'el-icon-s-unfold',
			mk_value: 0,
			mkData: [{
				mkid: '',
				descp: ''
			}],
			disable: false,
			interval_time: '',
			pickerOptions: {
				shortcuts: [{
					text: '最近一周',
					onClick(picker) {
						const end = new Date();
						const start = new Date();
						start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
						picker.$emit('pick', [start, end]);
					}
				}, {
					text: '最近一个月',
					onClick(picker) {
						const end = new Date();
						const start = new Date();
						start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
						picker.$emit('pick', [start, end]);
					}
				}, {
					text: '最近三个月',
					onClick(picker) {
						const end = new Date();
						const start = new Date();
						start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
						picker.$emit('pick', [start, end]);
					}
				}]
			},
			conditions: false
		}
	},
	methods: {
		prev(page) {
			this.page = page;
		},
		pager(page) {
			if(this.conditions){
				this.select_condition(page);
			}else{
				this.get_sale_list(page);
			}
			
		},
		next(page) {
			this.page = page;
		},
		get_sale_list(page) {
			var _this = this;
			var mkid = this.mk_value,url;
			if(mkid==0){
				url = '/api/sale.php?page='+page;
			}else{
				url = '/api/sale.php?page='+page+'&mkid='+mkid;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.saleData = data.data;
						_this.total = data.num_results;
						_this.page = page;
					}else if(data.ret==-1){
						if(page>1){
							_this.get_sale_list(page-1);
						}else{
							_this.saleData = [];
							_this.total = 0;
						}
					}else{
						_this.$message.error("获取销售列表失败");
					}
				},
				error: function() {
					_this.$message.error("服务器异常");
				}
			})
		},
		switch_market(row){
			return row.mkid+' '+row.mname;
		},
		switch_price(row) {
			return '￥'+(row.price/100).toFixed(2);
		},
		switch_subtotal(row) {
			return '￥'+(row.subtotal/100).toFixed(2);
		},
		switch_time(row) {
			return this.commonJS.get_date(row.time);
		},
		switch_payway(row){
			if(row.payway==0){
				return "微信";
			}else if(row.payway==1){
				return "支付宝";
			}
		},
		//改变收缩框类名
		search_shrink() {
			if(this.iconName=='el-icon-s-unfold'){
				this.iconName = 'el-icon-s-fold'
			}else{
				this.iconName='el-icon-s-unfold';
			}	
		},
		//获取所有超市
		get_market_list() {
			var _this = this;
			$.ajax({
				type: 'get',
				url: '/api/market.php',
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.mkData = data.data;
					}else{
						_this.$message.error('超市列表获取失败');
					}
				},
				error:function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		select_back() {
			this.mk_value = 0;
			this.def_time();
			this.conditions = false;
			this.get_sale_list(1);
		},
		def_time() {
			var now = this.commonJS.get_date();
			var date = now.split(" ")[0];
			var start = date+' 00:00:00';
			this.interval_time = [start,now];
		},
		select_condition(page) {
			var _this = this;
			this.conditions = true;
			var time = this.interval_time;
			var s_time = time[0];
			var e_time = time[1];
			var s_date = new Date(s_time);
			s_date = s_date.getTime()/1000;
			var e_date = new Date(e_time);
			e_date = e_date.getTime()/1000;
			var mkid = this.mk_value,url;
			if(mkid==0){
				url = '/api/sale.php?s_time='+s_date+'&e_time='+e_date+'&page='+page;
			}else{
				url = '/api/sale.php?mkid='+mkid+'&s_time='+s_date+'&e_time='+e_date+'&page='+page;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data) {
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.saleData = data.data;
						_this.total = data.num_results;
						_this.page = page;
					}else if(data.ret==-1){
						if(page>1){
							_this.select_condition(page-1);
						}else{
							_this.saleData = [];
							_this.total = 0;
						}
					}else{
						_this.saleData = [];
						_this.total = 0;
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		}
	},
	mounted() {
		this.get_market_list();
		this.def_time();
		var userinfo = this.$store.state.userinfo;
		userinfo = JSON.parse(userinfo);
		if(userinfo.mkid){
			this.mk_value = userinfo.mkid;
			this.disable = true;
		}
		this.get_sale_list(1);
	}
}
</script>