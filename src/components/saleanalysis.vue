<template>
<!-- 销量排行：查找销量最高的商品
		 金额排行：查找销售金额最高的商品-->
	<div id="saleanalysis">
		<div style="display: flex;margin-bottom: 10px;">
			<i class="el-input__icon" :class="iconName" @click="search_shrink" title="收缩搜索框" style="cursor: pointer;"></i>
			<div v-show="iconName=='el-icon-s-fold'" style="margin-left: 20px;">
				<el-select filterable placeholder="请选择" style="width: 180px;" v-model="mk_value" :disabled="disable">
					<el-option label="全部超市" :value="0"></el-option>
					<el-option v-for="item in mkData" :key="item.mkid" :label="item.mkid+' '+item.descp" :value="item.mkid"></el-option>
				</el-select>
				<el-button @click="select_analysis('count')">销量排行</el-button>
				<el-button @click="select_analysis('price')">金额排行</el-button>
			</div>
		</div>
		<div id="myChart" :style="{width: '1000px', height: '400px'}"></div>
	</div>
</template>

<style scoped>
#saleanalysis{
	padding: 20px;
	min-width: 1000px;
}
</style>

<script>
export default {
	name: 'Saleanalysis',
	data() {
		return{
			option: {
				title: { text: '' },
				tooltip: {},
				toolbox: {
					show : true,
					top:10,
					right:10,
					feature : {
						mark : {show: true},
						magicType : {show: true, type: ['line', 'bar']},
						restore : {show: true},
						saveAsImage : {show: true}
					}
				},
				grid:{
					top:100
        },
				legend: {
					top:32,
					left:'center',
					data:[]
				},
				calculable : true,
				xAxis: {
					type : 'category',
					data: []//x轴
				},
				yAxis : [{
					type: 'value',
					name:"",
					nameLocation:"center",
					nameGap:35,
					nameRotate:0,
					nameTextStyle:{
						fontSize: 16,
					},
					axisLabel : {   
						show:true,
						showMinLabel:true,
						showMaxLabel:true,
						formatter: function (value) {
							return value;
						}
					}
				},{
					type: 'value',
					name:"单\n价\n︵\n元\n︶",
					nameLocation:"center",
					nameGap:50,
					nameRotate:0,
					nameTextStyle:{
						fontSize: 16,
					},
					axisLabel : {  
						show:true,
						showMinLabel:true,
						showMaxLabel:true,
						formatter: function (value) {
							return value;
						}
					}
				}],
				series : [{
					name:'',
					type:'bar',
					yAxisIndex: 0,
					data:[],
					markPoint : {
						data : [
							{type : 'max', name: '最大值'},
							{type : 'min', name: '最小值'}
						]
					}
				},{
					name:'单价',
					type:'bar',
					yAxisIndex: 1,
					data:[],
					markPoint : {
						data : [
							{type : 'max', name: '最大值'},
							{type : 'min', name: '最小值'}
						]
					}
        }],
			},
			iconName: 'el-icon-s-unfold',
			mk_value: 0,
			mkData: [{
				mkid: '',
				descp: ''
			}],
			disable: false
		}
	},
	methods:{
		drawLine() {
			let myChart = this.$echarts.init(document.getElementById('myChart'))
			myChart.setOption(this.option);
		},
		search_shrink() {
			if(this.iconName=='el-icon-s-unfold'){
				this.iconName = 'el-icon-s-fold'
			}else{
				this.iconName='el-icon-s-unfold';
			}	
		},
		select_analysis(logo) {
			var _this = this;
			var mkid = this.mk_value,url;
			if(mkid==0){
				url = '/api/saleAnalysis.php?logo='+logo;
			}else{
				url = '/api/saleAnalysis.php?mkid='+mkid+'&logo='+logo;
			}
			$.ajax({
				type: 'get',
				url: url,
				async: true,
				success: function(data){
					var data = JSON.parse(data);
					if(data.ret==0){
						_this.analysis(data.data,logo);
					}
				},
				error: function() {
					_this.$message.error("获取销售列表失败");
				}
			})
		},
		analysis(data,logo) {
			var xAxis_data = [],series_data1 = [],series_data2 = [];
			if(logo=='count'){
				this.option.title.text = '销量排行';
				this.option.legend.data = ['销量','单价'];
				this.option.yAxis[0].name = '销\n量\n︵\n个\n︶';
				this.option.series[0].name = '销量';
				for(let i=0;i<data.length;i++){
					xAxis_data.push(data[i].gname);
					series_data1.push(data[i].count);
					series_data2.push((data[i].price/100).toFixed(2));
				}
			}else if(logo=='price'){
				this.option.title.text = '金额排行';
				this.option.legend.data = ['金额','单价'];
				this.option.yAxis[0].name = '金\n额\n︵\n元\n︶';
				this.option.series[0].name = '金额';
				for(let i=0;i<data.length;i++){
					xAxis_data.push(data[i].gname);
					series_data1.push((data[i].subtotal/100).toFixed(2));
					series_data2.push((data[i].price/100).toFixed(2));
				}
			}
			this.option.xAxis.data = xAxis_data;
			this.option.series[0].data = series_data1;
			this.option.series[1].data = series_data2;
			this.drawLine();
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
	},
	mounted() {
		this.get_market_list();
		this.drawLine();
		var userinfo = this.$store.state.userinfo;
		userinfo = JSON.parse(userinfo);
		if(userinfo.mkid){
			this.mk_value = userinfo.mkid;
			this.disable = true;
		}
		this.select_analysis('count');
	}
}
</script>