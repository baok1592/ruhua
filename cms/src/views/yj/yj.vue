<template>
	<div class="tixian">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main style="background-color: #F3F3F3;">
						<el-table :data="tableData" stripe style="width: 100%">
							<el-table-column type="index" label="序号" width="80">
							</el-table-column>
							<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
							<el-table-column prop="user_id" label="用户ID"></el-table-column>
							<el-table-column prop="agent_id" label="分销商ID"></el-table-column>
							<el-table-column prop="money" label="佣金"></el-table-column>
							<el-table-column prop="create_time" label="日期"></el-table-column>
							<el-table-column prop="status" label="申请提现">
								<template slot-scope="scope">
									<span v-if="scope.row.status == 0">未申请</span>
									<span v-if="scope.row.status == 1">申请中</span>
									<span v-if="scope.row.status == 2">已完成</span>
								</template>
							</el-table-column>
							<el-table-column label="提现状态">
								<template slot-scope="scope">
									<span v-if="scope.row.cpy_pay_status == 0">未打款</span>
									<span v-if="scope.row.cpy_pay_status == 1">打款成功</span>
									<span v-if="scope.row.cpy_pay_status == 2">打款失败</span>
									<span v-if="scope.row.cpy_pay_status == 3">当天未打款</span>
								</template>
							</el-table-column>
						</el-table>
					</el-main>
				</transition>
			</el-container>
		</el-container>

	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from "@/common/config";

	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				tableData: [ ]
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('fx/admin/get_record').then(res => {
					this.tableData = res.data
				})
			}
		},

	}
</script>

<style lang="less">
	.tixian {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.el-main {
			height: auto !important;
			background: #fff;

			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}
		}

		.article {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
			text-align: left;
		}

		.list-head {
			padding-bottom: 10px
		}

		.el-form-item__content {
			display: flex;
			justify-content: flex-start;
		}
	}
</style>
