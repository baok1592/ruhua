<template>
	<div class="user">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="background-color: #F3F3F3;">
				<!-- <el-header>
					<Header></Header>
				</el-header> -->

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div v-if="addShow">

							<div class="article">
								<div class="tab-btn">
									<el-button size="medium" type="primary" @click="add">新增项目</el-button>
								</div>
								<template>
									<el-table :data="list" border style="width: 100%">
										<el-table-column type="index" label="序号" width="50px"></el-table-column>
										<el-table-column prop="name" label="项目名称"></el-table-column>
										<el-table-column prop="user.user_name" label="管理人"></el-table-column>

										<el-table-column prop="create_time" label="创建时间"></el-table-column>
										<el-table-column prop="operation" label="操作" width="500px">
											<template slot-scope="scope">
												<el-button @click="edit(scope.row)" type="warning" size="small">修改</el-button>
												<el-button @click="jumo_yonggong(scope.row.id)" type="success" size="small">用工</el-button>
												<el-button style="margin-left: 10px" type="primary" size="small" slot="reference" @click="jumo_ruku(scope.row.id)">入库</el-button>
												<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="jumo_chuku(scope.row.id)">出库</el-button>
												<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>

											</template>
										</el-table-column>
									</el-table>
								</template>

							</div>
							<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
							 @current-change="jump_page">
							</el-pagination>
						</div>
						<div v-if="!addShow" class="article">
							<div class="tab-btn">
								<el-button size="medium" type="primary" @click="addShow=true">返回</el-button>
							</div>
							<el-form ref="forms" :model="form" label-width="120px">
								<el-form-item label="项目名称">
									<template v-if="is_edit == 1">
										<el-input v-model="form.name" style="width:35%" disabled=""></el-input>
									</template>
									<template v-else>
										<el-input v-model="form.name" style="width:35%"></el-input>
									</template>

								</el-form-item>
								<el-form-item label="保管员">
									<el-select v-model="form.aid" placeholder="保管员">
										<el-option v-for="(item,index) of user_list" :value="item.id" :label="item.user_name" :key="index"></el-option>
									</el-select>
								</el-form-item>
								<el-form-item label="成员">
									<el-checkbox-group v-model="form.user_ids">
										<el-checkbox v-for="(item,index) of user_list" :label="item.id+''" name="type">{{item.user_name}}</el-checkbox>
									</el-checkbox-group>
								</el-form-item>
								<el-form-item label="工人">
									<el-checkbox-group v-model="form.worker_ids">
										<el-checkbox v-for="(item,index) of worker_list" v-if="item.status != 0" :label="item.id+''" name="type">{{item.user_name}}</el-checkbox>
									</el-checkbox-group>
								</el-form-item>
							</el-form>
							<span slot="footer" class="dialog-footer ">
								<el-button @click="addShow=true">取 消</el-button>
								<template v-if="is_edit == 1">
									<el-button type="primary" @click="sub_edit">确定修改</el-button>
								</template>
								<template v-else>
									<el-button type="primary" @click="onSubmit">确 定</el-button>
								</template>

							</span>
						</div>
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
				addShow: true,
				all: '',
				form: {
					name: '',
					aid: '',
					user_ids: [],
					worker_ids: []
				},
				list: [],
				size: 10,
				total: 0,
				options: [],
				value: '',
				user_list: '',
				worker_list: []

			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this.getCompany();
			this.get_user()
			this.get_worker()
		},
		methods: {
			//删除项目
			del(id) {
				this.$confirm('确定删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put('project/admin/del_project?id='+id).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.getCompany();
						this.get_user()
						this.get_worker()
					})

				})
			},
			onSubmit() {
				console.log(this.form)
				this.http.post('project/admin/add_project', this.form).then(res => {
					this.$message({
						message: '添加成功',
						type: 'success'
					});
					this.form = {
							name: '',
							aid: '',
							user_ids: [],
							worker_ids: []
						},
						this.addShow = true
					this.getCompany()
				})
			},
			sub_edit() {
				this.$confirm('确定修改?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post('project/admin/edit_project', this.form).then(res => {
						this.$message({
							type: 'success',
							message: '修改成功!'
						});
						this.form = {
								name: '',
								aid: '',
								user_ids: [],
								worker_ids: []
							},
							this.addShow = true
						this.getCompany()
					})
				})
			},
			edit(item) {
				this.is_edit = 1
				console.log(item)
				this.addShow = false
				this.form.id = item.id
				this.form.name = item.name
				this.form.aid = item.aid
				this.form.user_ids = item.user_ids
				this.form.worker_ids = item.worker_ids
				console.log(this.form)
			},
			add() {
				this.addShow = false
				this.is_edit = 2
			},
			get_user() {
				this.http.get('admin/all_user').then((res) => {
					this.user_list = res.data
				})
			},
			get_worker() {
				this.http.get('worker/get_all_worker').then(res => {
					this.worker_list = res.data
				})
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				this.list = this.all.slice(start, end);
				console.log(start, end, this.list)
			},
			//获取列表
			getCompany() {
				var that = this;
				// let loadingInstance = Loading.service({
				// 	fullscreen: true
				// }); //显示加载 
				this.http.get('project/admin/get_project').then((res) => {
					//loadingInstance.close(); //关闭加载  

					this.all = res.data;
					this.list = res.data;
					that.total = res.data.length;
					this.jump_page(1)

				});
			},
			get_user_id(e, id, index) {

				console.log(this.form.user_ids)
				console.log(e)
			},
			jumo_yonggong(id) {
				this.$router.push({
					path: '/yonggong?id=' + id
				})
			},
			jumo_ruku(id) {
				this.$router.push({
					path: '/ruku?id=' + id
				})
			},
			jumo_chuku(id) {
				this.$router.push({
					path: '/chuku?id=' + id
				})
			},
			close_fun(done) {
				this.clear_data()
				done(); //官方实例用法
			},

			clear_data() {
				this.dialogFormVisible = false
			},
		}
	}
</script>

<style lang="scss">
	.tab-btn {
		text-align: left;
		margin-bottom: 15px;
	}

	.list-head {
		padding-bottom: 10px;
		display: flex;
	}

	.liat-head-02 {
		font-size: 14px;
		padding-right: 10px
	}

	.user {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 50px !important;
				height: 50px !important;
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

		.user_sear {
			line-height: 40px;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;
			padding: 15px 15px 10px;
			border-top: 1px solid #F0F0F0;

			.sear_01 {
				display: flex;
				margin-bottom: 20px;

				.sear_01_01 {
					display: flex;
					margin-right: 30px;

					.sear_01_01_1 {
						flex-shrink: 0;
					}

					.el-input__inner {
						width: 200px;
					}
				}
			}

			.sea_02_02_r_2_1 {
				background-color: #008DE1;
				color: #fff;
				padding: 0 10px;
				margin-right: 10px;
				border-radius: 3px;
				width: 45px;
			}
		}

		.article {
			background-color: #fff;
			padding: 15px;
			line-height: 30px;
			min-height: 650px;
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
