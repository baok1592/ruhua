<template>
	<div class="reseller">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<el-row style="background-color: #FFFFFF;" :gutter="20">
						<el-col :span="12" style="height: 800px;">
							<el-row style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
								<span>按月查询：</span>
								<el-date-picker v-model="value2" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
								 @change="get_month(value2)">
								</el-date-picker>
							</el-row>
							<ve-line :data="chartData" :settings="chartSettings" height="800px"></ve-line>
						</el-col>
						<!-- <el-col :span="0.6" style="background-color: #F3F3F3;height: auto"></el-col> -->
						<el-col :span="12" style="height: 800px;">
							<el-row>
								<el-col :span="12" style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
									<span>按月查询：</span>
									<el-date-picker v-model="value3" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
									 @change="get_monthB(value3)">
									</el-date-picker>
								</el-col>
								<el-col :span="12" style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
									总数：
								</el-col>
							</el-row>
							<el-row style="height: 50px;"></el-row>
							<el-table :data="tableData" style="width: 100%;padding: 0px;" stripe>
								<el-table-column type="index" label="排名" width="100">
								</el-table-column>
								<el-table-column prop="nickname" label="昵称">
								</el-table-column>
								<el-table-column label="头像">
									<template slot-scope="scope">
										<img style="width: 60px; height: 60px;" :src="scope.row.headpic" />
									</template>
								</el-table-column>
								<el-table-column prop="num" label="总订单">
								</el-table-column>
								<el-table-column prop="all_money" label="总佣金">
								</el-table-column>
								<el-table-column prop="money" label="未提现佣金">
								</el-table-column>
							</el-table>
						</el-col>

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
				now: '',
				value2: '',
				value3: '',
				userList: '',
				points_rank: '',
				chartData: {
					columns: ['day', 'order_num', 'user_num'],
					rows: []
				},
				tableData: []
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			this.get_fx_data()
			this.get_data()
			var date = new Date();
			this.now = date.toLocaleDateString();
		},
		methods: {
			get_fx_data(month) {
				if (!month) {
					this.http.post('fx/admin/count_fx').then(res => {
						this.tableData = res.data
					})
				}else{
					this.http.post('fx/admin/count_fx',{month:month}).then(res => {
						this.tableData = res.data
					})
				}

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
			get_monthB(month){
				this.get_fx_data(month/1000)
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
	.reseller {
		.ve-line {
			height: 600px;
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
