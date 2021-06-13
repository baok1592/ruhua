<template>
	<div class="home">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<el-row :gutter="20">
						<template v-for="(item,index) of message">
							<el-col :span="6">
								<div class="tishi">
									<div class="ts_01">
										<div class="ts_01_l">{{item.types}}</div>
										<div :class="item.state?'ts_01_r':'ts_01_m'">{{item.wen}}</div>
									</div>
									<div class="ts_02">
										<div class="ts_02_l">
											<span>{{item.num}}</span><br />
											{{item.mess}}
										</div>
										<!-- <div class="ts_02_r" v-if="item.duibi!=0">
											0% <i :class="item.fudu?'el-icon-top':'el-icon-bottom'" style="font-size: 18px"></i>
										</div> -->
									</div>
								</div>
							</el-col>
						</template>
					</el-row>
					<el-row style="background-color: #FFFFFF;">
						<el-row style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
							<span>按月查询：</span>
							<el-date-picker v-model="value2" type="month" placeholder="选择月" format="yyyy年MM月" value-format="timestamp"
							 @change="get_month(value2)">
							</el-date-picker>
						</el-row>
						<ve-line :data="chartData" :settings="chartSettings"></ve-line>
					</el-row>
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>
<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data() {

			this.chartSettings = {
				xAxisType: 'time',
				labelMap: {
					'day': '日期',
					'order_num': '用户量',
					'user_num': '订单量'
				},
				legendName: {
					'访问用户': '访问用户 total: 10000'
				}
			}
			return {
				value2: '',
				tableData: [],
				userList: '',
				points_rank: '',
				chartData: {
					columns: ['day', 'order_num', 'user_num'],
					rows: []
				},
				message: [{
						"types": '订单',
						"num": 0,
						"mess": "待发货",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '订单',
						"num": 0,
						"mess": "退换货",
						"state": 0,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '商品',
						"num": 0,
						"mess": "库存预警",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 1
					},
					{
						"types": '待提现',
						"num": 0,
						"mess": "待提现",
						"state": 1,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '订单',
						"num": 2,
						"mess": "今日订单/收入",
						"state": 0,
						"wen": "今",
						"duibi": 1,
						"fudu": 1
					},
					{
						"types": '交易',
						"num": 2,
						"mess": "本月订单/收入",
						"state": 0,
						"wen": "月",
						"duibi": 1,
						"fudu": 0
					},
					{
						"types": '用户',
						"num": 20,
						"mess": "今日新增用户",
						"state": 0,
						"wen": "今",
						"duibi": 1,
						"fudu": 1
					},
					{
						"types": '用户',
						"num": 27,
						"mess": "本月新增用户",
						"state": 0,
						"wen": "月",
						"duibi": 1,
						"fudu": 0
					}
				]
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			const token = localStorage.getItem("token");
			if (token) {
				this.check_login()
				
			}else{
				localStorage.clear();  	
				location.href = './#/login';
			}

		},
		methods: {
			get_home_data(){
				this.http.get('statistic/admin/get_index_data').then(res=>{
					this.message[0].num = res.data.shipment
					this.message[1].num = res.data.tui
					this.message[4].num = res.data.yesterday_order + '/'+ res.data.yesterday_money
					this.message[5].num = res.data.month_order + '/'+ res.data.month_money
					this.message[6].num = res.data.today_user
					this.message[7].num = res.data.month_user
				})
			},
			get_data() {
				this.http.post('statistic/admin/get_table').then(res => {
					this.chartData.rows = res.data
				})
			},
			get_month(month) {
				this.http.post('statistic/admin/get_table', {
					month: month / 1000
				}).then(res => {
					this.chartData.rows = res.data
				})
				console.log(month)
			},
			get_points_rank() {
				// this.http.get('stati/points_rank').then(res => {
				// 	this.points_rank = res.data
				// 	console.log(this.points_rank)
				// })
			},
			async check_login() {
				await this.http.get('index/admin/check_login').then(res => {
					if (!res.data) {
						localStorage.clear()
						this.$router.push('/login')
					}
				})
				this.get_home_data()
				this.get_all_city()
				this.get_points_rank()
				this.get_data()
			},
			get_all_city() {
				// this.http.post('stati/all_user').then(res => {
				// 	this.tableData = res.data.county_cpy
				// 	this.userList = res.data
				// 	console.log(this.tableData)
				// })
			},

			handleOpen(key, keyPath) {
				console.log(key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			},
		}
	}
</script>



<style lang="less">
	/* <style>   */
	.home {
		.el-aside {
			color: #333;
			text-align: center;
			line-height: 200px;
		}

		.el-main {

			color: #333;
			text-align: center;
			line-height: 160px;
		}

		.tishi {
			line-height: 20px;
			margin-bottom: 15px;
			background-color: #fff;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;

			.ts_01 {
				display: flex;
				justify-content: space-between;
				border-bottom: 1px solid #F0F0F0;
				padding: 10px;

				.ts_01_l {
					font-weight: 600;
				}

				.ts_01_r {
					background-color: #F54864;
					color: #fff;
					font-size: 12px;
					padding: 0px 10px;
					border-radius: 3px
				}

				.ts_01_m {
					background-color: #0392E4;
					color: #fff;
					font-size: 12px;
					padding: 0px 10px;
					border-radius: 3px
				}
			}

			.ts_02 {
				padding: 20px 15px;
				font-size: 13px;
				display: flex;
				justify-content: space-between;

				.ts_02_l {
					span {
						font-size: 28px;
					}
				}

				.ts_02_r {
					color: #0092E5;
					padding-top: 25px;
				}
			}
		}

		.card {
			display: flex;
			width: 450px;
			height: 100px;
			background-color: #ffffff;
			/* margin-left: 130px; */
		}

		.col {
			margin-top: 50px;
			/* height: 500px;	 */
		}

		.el-table__body-wrapper,
		.is-scrolling-none {
			/* height: 500px; */
		}

		.el-table__body {
			height: 500px;
		}

		.table {
			display: flex;
			justify-content: space-between;
		}

		.el-table__header-wrapper {
			height: 40px;
			line-height: 10px;
		}
	}

	/* 	.right {
		width: 70%;
		height: 300px;

		.top {
			height: 20%;

			background-color: aqua;
		}

		.content {
			height: 80%;
			background-color: burlywood;
		}
	} */
</style>
