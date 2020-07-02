<template>
	<div id="goodscount">
		<div style="display: flex;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-select filterable placeholder="请选择" style="width: 180px;" v-model="mk_value" @change="ChangeMarket" :disabled="disable">
					<el-option label="全部超市" :value="0"></el-option>
					<el-option v-for="item in mkData" :key="item.mkid" :label="item.mkid+' '+item.descp" :value="item.mkid"></el-option>
				</el-select>
				——>
				<el-select v-model="sel_type" style="width: 110px;">
					<el-option label="商品ID" :value="0"></el-option>
					<el-option label="商品名称" :value="1"></el-option>
				</el-select>
				<el-input v-model="sel_text" :placeholder="sel_type==0?'请输入商品ID':'请输入商品名称'" style="width: 150px;">
					<i title="查询" slot="suffix" class="el-input__icon el-icon-search" @click="select_condition" style="cursor: pointer;"></i>
				</el-input>
				<el-button @click="select_back">返回</el-button>
				<el-button v-show="mk_value!=0" @click="add_count">增加库存</el-button>
			</div>
		</div>
		<el-table
			class="body-table"
			ref="goodsCount"
			:data="goodsData"
			style="width: 100%">
			<el-table-column 
				width="70"
				label="超市ID"
				prop="mkid">
			</el-table-column>
			<el-table-column 
				label="商品ID"
				prop="gid">
			</el-table-column>
			<el-table-column 
				:show-overflow-tooltip='true'
				label="商品名称"
				prop="name">
			</el-table-column>
			<el-table-column 
				label="数量">
				<template slot-scope="scope">
					<el-input-number style="width: 75px;padding-right: 0;" size="mini" controls-position="right" :min="0" v-model="scope.row.count" type="number" @change="changeCount(scope)"></el-input-number>
				</template>
			</el-table-column>
			<el-table-column 
				label="进价"
				prop="sprice"
				:formatter="switch_sprice">
			</el-table-column>
			<el-table-column 
				label="售价"
				prop="price"
				:formatter="switch_price">
			</el-table-column>
			<el-table-column 
				width="200"
				label="生产日期"
				prop="gen_time">
			</el-table-column>
			<el-table-column 
				label="保质期/天"
				prop="expiration_date">
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
		<el-drawer
			title="选择商品"
			:visible.sync="drawer"
			direction="rtl"
			:before-close="handleClose">
			<el-select v-model="drawer_sel_type" style="width: 110px;margin-left: 10px;">
				<el-option label="商品ID" :value="0"></el-option>
				<el-option label="商品名称" :value="1"></el-option>
			</el-select>
			<el-input v-model="drawer_sel_text" :placeholder="drawer_sel_type==0?'输入ID':'输入名称'" style="width: 110px;">
				<i title="查询" slot="suffix" class="el-input__icon el-icon-search" @click="drawer_select_goods" style="cursor: pointer;"></i>
			</el-input>
			<el-button @click="drawer_select_back">返回</el-button>
			<el-table
				class="drawer_table"
				ref="drawer_goods"
				:data="drawer_goods"
				@select="chooseGoods">
				<el-table-column
					type="selection"
					width="30">
				</el-table-column>
				<el-table-column 
					label="商品ID"
					prop="gid">
				</el-table-column>
				<el-table-column 
					:show-overflow-tooltip='true'
					label="商品名称"
					prop="name">
				</el-table-column>
				<el-table-column label="数量">
					<template slot-scope="scope">
						<el-input size="mini" min="0" type="number" v-model="drawer_count[scope.row.gid]"></el-input>
					</template>
				</el-table-column>
			</el-table>
			<el-pagination
				style="margin-top:10px;"
				hide-on-single-page
				v-show="drawer_total>0"
				background
				layout="prev, pager, next"
				@prev-click='drawer_prev'
				@next-click='drawer_next'
				@current-change='drawer_pager'
				:total="drawer_total">
			</el-pagination>
			<el-button class="submit" @click="drawer_submit">提交</el-button>
		</el-drawer>
	</div>
</template>

<style>
#goodscount{
	padding: 20px;
	min-width: 800px;
}
.body-table{
	margin-top: 10px;
	margin-bottom: 20px;	
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 12px;	
}
.el-drawer{
	overflow-y: scroll;
}
.el-drawer::-webkit-scrollbar {
	display: none;/*隐藏滚动条*/
}
.el-input-number.is-controls-right .el-input__inner{
	padding-left: 10px;
	padding-right: 40px; 
}
.submit{
	margin-top: 5px;
	margin-left: 5px;
}
.drawer_table th:first-child{
	visibility:hidden;
}
</style>

<script>
export default {
	name: 'Goodscount',
	data() {
		return{
			goodsData: [{
				mkid: '',
				gid: '',
				name: '',
				count: '',
				sprice: '',
				price: '',
				gen_time: '',
				expiration_date: ''
			}],
			total: 0,
			page: 1,
			iconName: 'el-icon-s-unfold',
			mk_value: 0,
			mkData: [{
				mkid: '',
				descp: ''
			}],
			sel_type: 0,
			sel_text: '',
			disable: false,
			drawer: false,
			drawer_sel_type: 0,
			drawer_sel_text: '',
			drawer_goods: [{
				gid: '',
				name: ''
			}],
			drawer_total: 0,
			drawer_page: 1,
			submit_data: [],
			drawer_count: {}
		}
	},
	methods: {
		prev(page) {
			this.page = page;
		},
		pager(page) {
			this.get_goods_list(page);
		},
		next(page) {
			this.page = page;
		},
		get_goods_list(page) {
			var _this = this;
			var mkid = this.mk_value,url;
			if(mkid==0){
				url = '/api/goods.php?page='+page;
			}else{
				url = '/api/goods.php?page='+page+'&mkid='+mkid;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data) {
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.goodsData = data.data;
						_this.total = data.num_results;
						_this.page = page;
					}else if(data.ret == -1){
						if(page>1){
							_this.get_goods_list(page-1);
						}else{
							_this.goodsData = [];
							_this.total = 0;
						}						
					}else{
						_this.$message.error('获取商品列表失败');
					}
				},
				error:function(){
					_this.$message.error('服务器异常');
				}
			})
		},
		switch_price(row) {
			return '￥'+(row.price/100).toFixed(2);
		},
		//显示商品价格
		switch_sprice(row) {
			return '￥'+(row.sprice/100).toFixed(2);
		},
		changeCount(scope) {
			var _this = this;
			var id = scope.row.id;
			var count = scope.row.count;
			if(count==0){
				_this.$confirm('是否要下架此商品', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					$.ajax({
						type: 'delete',
						url: '/api/goods.php?id='+id,
						async: true,
						success: function(data){
							var data = JSON.parse(data);
							if(data.ret==0){
								_this.$message.success('修改成功');
								_this.get_goods_list(_this.page);
							}else{
								_this.$message.error('修改失败');
								_this.get_goods_list(_this.page);
							}
						},
						error: function(){
							_this.$message.error('服务器异常');
						}
					})
				}).catch(() => {
					_this.get_goods_list(_this.page);
				})
			}else{
				$.ajax({
					type: 'patch',
					url: '/api/goods.php?id='+id+'&count='+count,
					async: true,
					success:function(data){
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.$message.success('修改成功');
						}else{
							_this.$message.error('修改失败');
							_this.get_goods_list(_this.page);
						}
					},
					error:function(){
						_this.$message.error('服务器异常');
					}
				})
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
		ChangeMarket() {
			this.get_goods_list(1);
		},
		//搜索按钮
		select_condition() {
			var _this = this;
			var mkid = this.mk_value;
			var sel_type = this.sel_type;
			if(sel_type==0){
				var gid = this.sel_text;
				$.ajax({
					type: 'get',
					url: '/api/goods.php?mkid='+mkid+'&gid='+gid,
					async: true,
					success: function(data){
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.goodsData = data.data;
							_this.total = 0;
						}else{
							_this.goodsData = [],
							_this.total = 0;
						}
					},
					error: function(){
						_this.$message.error('服务器异常');
					}
				})
			}else if(sel_type==1){
				var name = this.sel_text;
				$.ajax({
					type: 'get',
					url: '/api/goods.php?mkid='+mkid+'&name='+name,
					async: true,
					success: function(data){
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.goodsData = data.data;
							_this.total = 0;
						}else{
							_this.goodsData = [],
							_this.total = 0;
						}
					},
					error: function(){
						_this.$message.error('服务器异常');
					}
				})
			}
		},
		//返回按钮
		select_back() {
			this.mk_value = 0;
			this.sel_type = 0;
			this.sel_text = '';
			this.get_goods_list(1);
		},
		add_count() {
			this.drawer = true;
			this.get_allgoods(1);
		},
		get_allgoods(page) {
			var _this = this;
			$.ajax({
				type: 'get',
				url: '/api/allgoods.php?page='+page,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.drawer_goods = data.data;
						_this.drawer_total = data.num_results;
						_this.drawer_page = page;
					}else{
						if(page>1){
							_this.get_allgoods(page-1);
						}else{
							_this.drawer_goods = [];
							_this.drawer_total =  0;
						}
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		drawer_prev(page) {
			this.get_allgoods(page);
		},
		drawer_pager(page) {
			this.get_allgoods(page);
		},
		drawer_next(page) {
			this.get_allgoods(page);
		},
		//关闭抽屉
		handleClose() {
			this.drawer = false;
		},
		//查询商品
		drawer_select_goods() {
			var _this = this;
			var type = this.drawer_sel_type;
			var text = this.drawer_sel_text;
			var url;
			if(type==0){
				url = '/api/allgoods.php?gid='+text;
			}else if(type==1){
				url = '/api/allgoods.php?name='+text;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.drawer_goods = data.data;
						_this.drawer_total = 0;
					}else{
						_this.drawer_goods = [],
						_this.drawer_total = 0;
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		drawer_select_back() {
			this.get_allgoods(this.drawer_page);
			this.drawer_sel_type = 0;
			this.drawer_sel_text = '';
		},
		chooseGoods(select, row) {
			if(select.length>0){
				var submit_data = [];
				for(let i=0;i<select.length;i++){
					var option = select[i];
					option.mkid = this.mk_value;
					option.count = this.drawer_count[option.gid];
					if(option.count==undefined||option.count==''){
						this.$message.error('请输入要添加的数量');
						this.$refs.drawer_goods.toggleRowSelection(row);
					}else{
						option.count = Number(option.count);
						submit_data.push(option);
					}					
				}
				this.submit_data = submit_data;
			}else{
				this.submit_data = [];
			}
		},
		drawer_submit() {
			var _this = this;
			var data = this.submit_data;
			$.ajax({
				type: 'post',
				url: '/api/allgoods.php',
				data: JSON.stringify(data),
				async: true,
				success: function(res){
					var res = JSON.parse(res);
					if(res.ret==0){
						_this.$message.success('添加成功');
						_this.drawer = false;
						_this.ChangeMarket();
					}else{
						_this.$message.error('添加失败');
					}
				},
				error: function(){
					_this.$message.error('服务器异常');
				}
			})
		}
	},
	mounted() {
		this.get_market_list();
		var userinfo = this.$store.state.userinfo;
		userinfo = JSON.parse(userinfo);
		if(userinfo.mkid){
			this.mk_value = userinfo.mkid;
			this.disable = true;
		}
		this.get_goods_list(1);
	},
	watch: {
		drawer_goods: function() {
			var data = this.drawer_goods;
			var counts = {}
			for(let i=0;i<data.length;i++){
				counts["'"+data[i].gid+"'"] = '';
			}
			this.drawer_count = counts;
		}
	}
}
</script>