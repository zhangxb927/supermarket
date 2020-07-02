<template>
	<div id="goodslist">
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
					<i slot="suffix" class="el-input__icon el-icon-search" @click="select_condition" style="cursor: pointer;"></i>
				</el-input>
				<el-button @click="select_back">返回</el-button>
			</div>
		</div>
		<el-table
			ref="goodsList"
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
				label="商品类型"
				prop="type"
				:formatter="switch_type">
			</el-table-column>
			<el-table-column 
				label="保质期/天"
				prop="expiration_date">
			</el-table-column>
			<el-table-column
				prop="expire_date"
				label="标签"
				:filters="[{ text: '正常', value: '正常' }, { text: '即将过期', value: '即将过期' }, { text: '已过期', value: '已过期' }]"
      	:filter-method="filterTag"
				filter-placement="bottom-end">
				<template slot-scope="scope">
					<el-tag
						:type="date_type(scope.row.expire_date)"
						disable-transitions>{{date_text(scope.row.expire_date)}}</el-tag>
				</template>
			</el-table-column>
			<el-table-column
				v-if="show_operation()"
				width="150"
				label="操作">
				<template slot-scope="scope">
					<el-button @click="changeInfo(scope.row)" type="text" size="small"><i class="el-icon-edit"></i> 编辑</el-button>
					<el-button @click="deleteInfo(scope.row.id)" type="text" size="small"><i class="el-icon-delete"></i> 下架</el-button>
				</template>
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

		<el-dialog title="修改信息" :visible.sync="dialogChange">
			<el-form :model="form" label-width="100px">
				<el-form-item label="商品ID">
					<el-input v-model="form.gid" disabled></el-input>
				</el-form-item>
				<el-form-item label="商品名称">
					<el-input v-model="form.name"></el-input>
				</el-form-item>
				<el-form-item label="进价/分">
					<el-input v-model="form.sprice" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="售价/分">
					<el-input v-model="form.price" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="商品类型">
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
					<el-input type="number" style="width: 220px;" min="0" v-model="form.expiration_date" auto-complete="off"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogChange = false">取 消</el-button>
				<el-button type="primary" @click="submitButton">确 定</el-button>
			</div>
		</el-dialog>
	</div>
</template>

<style scoped>
#goodslist{
	padding: 20px;
	min-width: 800px;
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
	name: 'Goodslist',
	data() {
		return{
			goodsData: [{
				mkid: '',
				gid: '',
				name: '',
				sprice: '',
				price: '',
				type: '',
				expiration_date: '',
				expire_date: ''
			}],
			total: 0,
			page: 1,
			dialogChange: false,
			form: {
				gid: '',
				name: '',
				sprice: '',
				price: '',
				type: '',
				gen_time: '',
				expiration_date: ''
			},
			iconName: 'el-icon-s-unfold',
			mkData: [{
				mkid: '',
				descp: ''
			}],
			mk_value: 0,
			sel_type: 0,
			sel_text: '',
			disable: false
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
		get_goods_list(page){
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
				success: function(data){
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
				error: function(){
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
		//将商品类型转换为文字
		switch_type(row) {
			return this.commonJS.get_type(row.type);
		},
		//显示标签类型
		date_type(expire_date){
			var now = this.commonJS.get_date(null);
			var nowdate = new Date(now);
			var nowunix = nowdate.getTime()/1000
			var expiredate = new Date(expire_date);
			var expireunix = expiredate.getTime()/1000;
			var poor = expireunix-nowunix;
			if(poor>=24*60*60){
				return 'success'
			}else if(poor<24*60*60&&poor>0){
				return 'warning';
			}else{
				return 'danger';
			}
		},
		//显示标签内容
		date_text(expire_date){
			var now = this.commonJS.get_date(null);
			var nowdate = new Date(now);
			var nowunix = nowdate.getTime()/1000
			var expiredate = new Date(expire_date);
			var expireunix = expiredate.getTime()/1000;
			var poor = expireunix-nowunix;
			if(poor>=24*60*60){
				return '正常'
			}else if(poor<24*60*60&&poor>0){
				return '即将过期';
			}else if(poor<=0){
				return '已过期';
			}
		},
		//编辑按钮
		changeInfo(row) {
			this.dialogChange = true;
			this.form = row;
		},
		//删除按钮
		deleteInfo(id) {
			var _this = this;
			_this.$confirm('是否删除此商品', '提示', {
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
							_this.$message.success('删除成功');
							_this.get_goods_list(_this.page);
						}else{
							_this.$message.error('删除失败');
						}
					},
					error: function(){
						_this.$message.error('服务器异常');
					}
				})
			})	
		},
		//根据等级是否显示操作
		show_operation() {
			var userinfo = this.$store.state.userinfo;
			userinfo = JSON.parse(userinfo);
			if(userinfo.level==2 || userinfo.level==3){
				return true;
			}else {
				return false;
			}
		},
		//修改提交
		submitButton() {
			var _this = this;
			$.ajax({
				type: 'patch',
				url: '/api/goods.php',
				async:true,
				data: JSON.stringify(_this.form),
				success: function(data) {
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.$message.success('修改成功');
						_this.dialogChange = false;
					}else{
						_this.$message.error('修改失败');
					}
				},
				error: function() {
					_this.$message.error('服务器异常');
				}
			})
		},
		//改变收缩框类名
		search_shrink() {
			if(this.iconName=='el-icon-s-unfold'){
				this.iconName = 'el-icon-s-fold'
			}else{
				this.iconName='el-icon-s-unfold';
			}	
		},
		//返回按钮
		select_back() {
			this.mk_value = 0;
			this.sel_type = 0;
			this.sel_text = '';
			this.get_goods_list(1);
		},
		//标签筛选
		filterTag(value, row) {
			var tag = this.date_text(row.expire_date);
			return tag === value;
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
	}
}
</script>