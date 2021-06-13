<template> 
	<div class="hot">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<el-button style="margin-bottom: 20px; display:flex;" type="primary" @click="add_hot">新增热门词</el-button>
					<el-table :data="tableData" stripe style="width: 100%">
						<el-table-column type="index" label="排序" width="80">
						</el-table-column>
						<el-table-column prop="name" label="热门词">
							<template slot-scope="scope">{{scope.row}}</template>
						</el-table-column>
						<el-table-column prop="address" label="操作">
							<template slot-scope="scope">
								<el-button type="danger" size="medium" @click="del(scope.row)">删除</el-button>
							</template>

						</el-table-column>
					</el-table>
				</el-main>
			</el-container>
		</el-container>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="热门词">
					<el-input v-model="form.name"></el-input>
				</el-form-item>
				<el-form-item label="搜索次数">
					<el-input v-model="form.num"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>
<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data() {
			return {
				form: {
					name: '',
					num: ''
				},
				dialogVisible: false,
				tableData: []
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			this.get_hot_data()
		},
		methods: {
			get_hot_data() {
				this.http.get('search/record').then(res => {
					this.tableData = res.data
				})
			},
			del(item) {
				console.log(item)
				this.$confirm('确定删除该热门搜索词', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('search/admin/del_record?name=' + item).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.get_hot_data()
					})

				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消删除'
					});
				});
			},
			add_hot() {
				this.dialogVisible = true
			},
			sub() {
				this.http.post_show('search/admin/add_record', this.form).then(res => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					});
					this.form = {
						name: '',
						num: ''
					}
					this.get_hot_data()
					this.dialogVisible = false
				})

			},
			cancel() {
				this.form = {
					name: '',
					num: ''
				}
				this.dialogVisible = false
			},
			handleClose(){
				this.cancel()
			}
		}
	}
</script>



<style lang="less">
	/* <style>   */
	.home2 {
		.el-aside {
			color: #333;
			text-align: center;
			// line-height: 200px;
		}

		.el-main {

			color: #333;
			text-align: center;
			// line-height: 160px;
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
