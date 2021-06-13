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
					<el-row style="background-color: #FFFFFF;padding:60px;">
						<el-row style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
							<span>按月查询：</span>
							<el-date-picker v-model="value" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
							 @change="get_month_sale(value)">
							</el-date-picker>
						</el-row>
						<ve-line :data="chartData_sale" :settings="chartSettings_sale"></ve-line>
					</el-row>
					<el-row style="height: 50px;"></el-row>
					<el-row style="background-color: #FFFFFF; padding: 60px;">
						<el-row style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
							<span>按月查询：</span>
							<el-date-picker v-model="value2" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
							@change="get_month_order(value2)">
							</el-date-picker>
						</el-row>
						<ve-line :data="chartData_order" :settings="chartSettings_order"></ve-line>
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

			// this.chartSettings = {
			// 	xAxisType: 'time',
			// 	labelMap: {
			// 		'day': '日期',
			// 		'order_num': '用户量',
			// 		'user_num': '订单量'
			// 	},
			// 	legendName: {
			// 		'访问用户': '访问用户 total: 10000'
			// 	}
			// }
			this.chartSettings_sale = {
				xAxisType: 'time',
				labelMap: {
					'day': '日期',
					'total_price': '销售额',
				},
				legendName: {
					'访问用户': '访问用户 total: 10000'
				}
			}
			this.chartSettings_order = {
				xAxisType: 'time',
				labelMap: {
					'day': '日期',
					'order_num': '订单量',
				},
				legendName: {
					'访问用户': '访问用户 total: 10000'
				}
			}
			return {
				now:'',//当前日期
				value2: '',
				value:'',
				tableData: [],
				userList: '',
				chartData_sale: {
					columns: ['day','total_price'],
					rows: []
				},
				chartData_order: {
					columns: ['day', 'order_num'],
					rows: []
				},
				
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			
			this.get_data_sale()
			this.get_data_order()
			var date = new Date();
			this.now = date .toLocaleDateString();
			
		},
		methods: {
			get_data_sale() {
				this.http.post('statistic/admin/get_money').then(res => {
					this.chartData_sale.rows = res.data
				})
			},
			get_data_order() {
				this.http.post('statistic/admin/get_table').then(res => {
					this.chartData_order.rows = res.data
				})
			},
			get_month_sale(month) {
				this.http.post('statistic/admin/get_money', {
					month: month / 1000
				}).then(res => {
					this.chartData_sale.rows = res.data
				})
			},
			get_month_order(month) {
				this.http.post('statistic/admin/get_table', {
					month: month / 1000
				}).then(res => {
					this.chartData_order.rows = res.data
				})
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
