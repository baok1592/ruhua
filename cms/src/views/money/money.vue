<template>
	<div class="tuikuan">
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
						<div class="article">
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="order_num" label="订单号"></el-table-column>
									<el-table-column prop="nickname" label="用户昵称"></el-table-column>
									<el-table-column prop="because" label="退款原因"></el-table-column>
									<el-table-column prop="message" label="客户留言"></el-table-column>
									<el-table-column prop="ip" label="IP"></el-table-column>
									<el-table-column prop="money" label="退款金额"></el-table-column>
									<el-table-column prop="status" label="状态">
										<template slot-scope="scope">
											<template v-if="scope.row.status == 0">待审核</template>
											<template v-if="scope.row.status == -1">已驳回</template>
											<template v-if="scope.row.status == 1">退款成功</template>
										</template>
									</el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">
											<el-button type="success" size="small" @click="agree(scope.row.id)">同意</el-button>
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="refuse(scope.row.id)">驳回</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
							</template>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">

			<el-input v-model="msg" placeholder="请输入驳回原因"></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_refuse">确 定</el-button>
			</span>
		</el-dialog>
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
				dialogVisible: false,
				list: '',
				msg: '',
				rid: ''
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
				this.http.get('order/admin/get_tui_all').then(res => {
					// this.list = res.data
					let arr = []
					for (let k in res.data) {
						let v = res.data[k]
						if (v.status != -1) {
							arr.push(v)
						}
					}
					this.list = arr
				})
			},
			agree(id) {
				this.$confirm('确定同意该退款', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post('order/admin/tui_money', {
						id: id
					}).then(res => {
						if (res.status == 400) {
							this.$message({
								message: res.msg,
								type: 'warning'
							});
						} else {
							this.$message({
								message: '退款成功',
								type: 'success'
							});
						}
						this._load()
					})
				})
			},
			refuse(id) {
				this.dialogVisible = true
				this.rid = id
			},
			sub_refuse() {
				let refuse_data = {
					id: this.rid,
					msg: this.msg
				}
				if (!refuse_data.msg) {
					this.$message({
						message: '未填写驳回原因',
						type: 'warning'
					});
					return
				}
				this.http.post('order/admin/tui_bohui', refuse_data).then(res => {
					this.$message({
						message: '驳回成功',
						type: 'success'
					});
					this.msg = ''
					this.dialogVisible = false
					this._load()
				})
			},
			cancel() {
				this.dialogVisible = false
				this.msg = ''
			},
			handleClose() {
				this.dialogVisible = false
				this.msg = ''
			}
		},

	}
</script>

<style lang="less">
	.tuikuan {
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
