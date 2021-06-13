<template>
	<div class="coupon">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="article">
							<el-button type="primary" @click="add_tc">添加会员套餐</el-button>
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="title" label="套餐名称"></el-table-column>
									<el-table-column prop="price" label="套餐价格"></el-table-column>
									<el-table-column prop="day_num" label="套餐天数"></el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">
											<el-button style="margin-left: 10px" type="primary" size="small" slot="reference" @click="edit(scope.row)">修改</el-button>
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
							</template>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>
		<!-- //添加套餐 -->
		<el-dialog title="添加会员套餐" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="套餐名称">
					<el-input v-model="form.title"></el-input>
				</el-form-item>
				<el-form-item label="套餐价格">
					<el-input v-model="form.price"></el-input>
				</el-form-item>
				<el-form-item label="套餐天数">
					<el-input v-model="form.day_num"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_add">确 定</el-button>
			</span>
		</el-dialog>
		<!-- //添加套餐结束 -->

		<!-- 修改套餐 -->
		<el-dialog title="修改会员套餐" :visible.sync="dialogVisible_edit" width="30%" :before-close="handleClose">
			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="套餐名称">
					<el-input v-model="form.title"></el-input>
				</el-form-item>
				<el-form-item label="套餐价格">
					<el-input v-model="form.price"></el-input>
				</el-form-item>
				<el-form-item label="套餐天数">
					<el-input v-model="form.day_num"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_edit">确 定</el-button>
			</span>
		</el-dialog>
		<!-- 修改套餐 结束-->

	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				list: [],
				form: {
					title: '',
					price: '',
					day_num: ''
				},
				dialogVisible: false,
				dialogVisible_edit: false
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this.get_tc_data()
		},
		methods: {
			//获取所有套餐
			get_tc_data() {
				this.http.get('vip/get_tc').then(res => {
					this.list = res.data
				})
			},
			//点击提交添加套餐
			sub_add() {
				console.log("点击确定")
				this.http.post_show('vip/admin/add_tc', this.form).then(res => {
					this.clear_data()
					this.dialogVisible = false
					this.get_tc_data()
				})
			},
			//点击提交修改
			sub_edit(){
				this.dialogVisible_edit = false
			},
			// 点击取消
			cancel() {
				console.log("点击取消")
				this.dialogVisible = false
				this.dialogVisible_edit = false
				this.clear_data()
			},
			add_tc() {
				this.dialogVisible = true
			},
			//点击修改套餐
			edit(item) {
				this.dialogVisible_edit = true
				this.form.title = item.title
				this.form.price = item.price
				this.form.day_num = item.day_num
				
			},
			//删除套餐
			del(id) {
				this.$confirm('确定删除该套餐', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('vip/admin/del_tc?id=' + id).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.get_tc_data()
					})

				})
			},
			clear_data() {
				this.form = {
					price: '',
					day_num: '',
					name: ''
				}
			},
			handleClose() {
				this.dialogVisible = false
				this.dialogVisible_edit = false
			}
		}

	}
</script>

<style lang="less">
	.coupon {
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
			background-color: #F3F3F3;
			padding: 15px;

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
