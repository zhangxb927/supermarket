<template>
  <div id="marketlist">
		<div style="display: flex;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-select v-model="type" placeholder="请选择" style="width: 120px;">
					<el-option :value="0" label="超市ID"></el-option>
					<el-option :value="1" label="超市名称"></el-option>
					<el-option :value="2" label="超市类型"></el-option>
				</el-select>
				<el-input
					v-show="type==0||type==1"
					style="width: 200px;"
					:placeholder="type==0?'请输入超市ID':'请输入超市名称'"
					v-model="type_text">
					<i slot="suffix" class="el-input__icon el-icon-search" @click="select_byType" style="cursor: pointer;"></i>
				</el-input>
				<el-select v-show="type==2" v-model="mk_type" placeholder="请选择" style="width: 200px;" @change="select_byType">
					<el-option :value="1" label="自营超市"></el-option>
					<el-option :value="2" label="半合作超市"></el-option>
					<el-option :value="3" label="全合作超市"></el-option>
				</el-select>
				<el-button @click="select_back">返回</el-button>
			</div>
		</div>
		<el-table
			:data="marketData"
			stripe
			style="width: 100%">
			<el-table-column
				width="80"
				prop="mkid"
				label="超市ID">
			</el-table-column>
			<el-table-column
				:show-overflow-tooltip='true'
				prop="descp"
				label="超市名称">
			</el-table-column>
			<el-table-column
				prop="kind"
				:formatter="kindSwitch"
				label="超市类型">
			</el-table-column>
			<el-table-column
				prop="pos"
				label="超市经纬度">
			</el-table-column>
			<el-table-column
				min-width="50"
				prop="state"
				:formatter="stateSwitch"
				label="超市状态">
			</el-table-column>
			<el-table-column
				prop="action"
				label="灯光状态">
				<template slot-scope="scope">
					<el-switch
						:active-value="1"
						:inactive-value="0"
						active-text ="开"
						inactive-text = "关"
						active-color="#5B7BFA"
						inactive-color="#dadde5"
						v-model="scope.row.action" 
						@change=changeAction(scope.row)                
					>
					</el-switch>
				</template>
			</el-table-column>
			<el-table-column
				v-if="show_operation()"
				width="270"
				label="操作">
				<template slot-scope="scope">
					<el-button @click="changeInfo(scope.row)" type="text" size="small"><i class="el-icon-edit"></i> 编辑</el-button>
					<el-button @click="deleteInfo(scope.row.mkid)" type="text" size="small"><i class="el-icon-delete"></i> 删除</el-button>
					<el-button @click="lockDoor(scope.row)" type="text" size="small">软锁门</el-button>
					<el-button @click="removeLock(scope.row)" type="text" size="small">解除软锁门</el-button>
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
			<el-form :model="form" label-width="100px">
				<el-form-item label="超市ID">
					<el-input v-model="form.mkid" disabled></el-input>
				</el-form-item>
				<el-form-item label="超市名称">
					<el-input v-model="form.descp" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="超市类型">
					<el-select v-model="form.kind">
						<el-option label="自营超市" :value="1"></el-option>
						<el-option label="半合作超市" :value="2"></el-option>
						<el-option label="全合作超市" :value="3"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="超市状态">
					<el-select v-model="form.state">
						<el-option label="软锁门" :value="0"></el-option>
						<el-option label="正常" :value="1"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="灯光状态">
					<el-select v-model="form.action">
						<el-option label="关灯" :value="0"></el-option>
						<el-option label="开灯" :value="1"></el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="超市经纬度">
					<el-input v-model="form.pos" auto-complete="off"></el-input>
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
#marketlist{
	padding: 20px;
	min-width: 1000px;
}
.el-table{
	margin-top: 10px;
	margin-bottom: 20px;	
	box-shadow: 1px 0 10px 1px rgba(0,0,0,.3);
	padding: 12px;	
}
td,.cell{
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}
</style>

<script>
export default {
	name: 'Marketlist',
	data(){
		return{
			marketData: [{
				mkid: '',
				descp: '',
				kind: '',
				pos: '',
				state: '',
				action: ''
			}],
			total: 0,
			page: 1,
			dialogChange: false,
			form: {
				mkid: '',
				descp: '',
				kind: '',
				state: '',
				action: '',
				pos: ''
			},
			type: 0,
			type_text: '',
			mk_type: '',
			iconName: 'el-icon-s-unfold',
			operation: false
		}
	},
	methods: {
		prev(page) {
			this.get_marketList(page);
		},
		pager(page) {
			this.get_marketList(page);
		},
		next(page) {
			this.get_marketList(page);
		},
		get_marketList(page) {
			var _this = this;
			$.ajax({
				type: 'get',
				url: '/api/market.php?page='+page,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret == 0){
						_this.marketData = data.data;
						_this.total = data.num_results;
						_this.page = page;
					}else if(data.ret == -1){
						if(page>1){
							_this.get_marketList(page-1);
						}else{
							_this.marketData = [];
							_this.total = 0;
						}						
					}else{
						_this.$message.error('获取超市列表失败');
					}
				},
				error: function(){	
					_this.$message.error('服务器异常');
				}
			})
		},
		kindSwitch(row, column) {
			return this.commonJS.get_kind(row.kind);
		},
		stateSwitch(row, column) {
			return this.commonJS.get_state(row.state);
		},
		actionSwitch(row, column){
			if(row.action == 0){
				return false;
			}else if(row.action == 1){
				return true;
			}
		},
		changeAction(row) {
			var form = JSON.stringify(row);
			this.submitChange(form);
		},
		changeInfo(row) {
			this.dialogChange = true;
			this.form = row;
		},
		submitButton() {
			var form = JSON.stringify(this.form);
			this.submitChange(form);
		},
		submitChange(form) {
			var _this = this;
			$.ajax({
				type: 'patch',
				url: '/api/market.php',
				async:true,
				data: form,
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
		deleteInfo(mkid) {
			var _this = this;
			_this.$confirm('是否删除此超市', '提示', {
				confirmButtonText: '确定',
				cancelButtonText: '取消',
				type: 'warning'
			}).then(() => {
				$.ajax({
					type: 'delete',
					url: '/api/market.php?mkid='+mkid,
					async:true,
					success: function(data) {
						var data = JSON.parse(data);
						if(data.ret==0){
							_this.$message.success('删除成功');
							_this.get_marketList(_this.page);
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
		lockDoor(row) {
			var form = row;
			form.state = 0;
			this.submitChange(JSON.stringify(form));
		},
		removeLock(row) {
			var form = row;
			form.state = 1;
			this.submitChange(JSON.stringify(form));
		},
		select_byType() {
			var _this = this;
			var type = this.type;
			var text = this.type_text;
			var url = ''
			if(type==0){
				url = '/api/market.php?mkid='+text;
			}else if(type==1){
				url = '/api/market.php?descp='+text;
			}else if(type==2){
				var kind = this.mk_type;
				url = '/api/market.php?kind='+kind;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(res){
					var res = JSON.parse(res);
					console.log(res);
					if(res.ret==0){
						_this.marketData = res.data;
						_this.total = 0;
					}else{
						_this.marketData = [];
						_this.total = 0;
						_this.$message.error('没有查询到相关信息');
					}
				},
				error: function(){	
					_this.$message.error('服务器异常');
				}
			})
		},
		select_back() {
			this.get_marketList(1);
			this.type = 0;
			this.type_text = '';
		},
		search_shrink() {
			if(this.iconName=='el-icon-s-unfold'){
				this.iconName = 'el-icon-s-fold'
			}else{
				this.iconName='el-icon-s-unfold';
			}	
		},
		show_operation() {
			var userinfo = this.$store.state.userinfo;
			userinfo = JSON.parse(userinfo);
			if(userinfo.level==0 || userinfo.level==2){
				return true;
			}else {
				return false;
			}
		}
	},
	mounted() {
		this.get_marketList(1);
	}
}
</script>