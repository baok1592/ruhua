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
							<el-table-column prop="name" label="姓名" width="80"></el-table-column>
							<el-table-column prop="address" label="日期"></el-table-column>
							<el-table-column prop="address" label="提现金额"></el-table-column>
							<el-table-column prop="address" label="可提金额"></el-table-column>
							<el-table-column prop="address" label="提现后剩余"></el-table-column>
							<el-table-column prop="address" label="状态"></el-table-column>
							<el-table-column prop="address" label="操作">
								<template slot-scope="scope">
									<el-button size="small" type="primary">同意</el-button>
									 <el-button size="small" type="danger">驳回</el-button>
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
				tableData: [{
					date: '2016-05-02',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1518 弄'
				}, {
					date: '2016-05-04',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1517 弄'
				}, {
					date: '2016-05-01',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1519 弄'
				}, {
					date: '2016-05-03',
					name: '王小虎',
					address: '上海市普陀区金沙江路 1516 弄'
				}]
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
