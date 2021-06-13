<template>
	<div class="data">
		<el-container>
			<el-aside width="200px">
				<NavTo @logout="getmsg"></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="search">
							<div class="sea_01">搜索条件</div>
							<div class="sea_02">
								<div class="da_sea01">
									<div class="da_sea01_01">昵称/ID</div>
									<el-input v-model="form.ni"></el-input>
								</div>
								<div class="da_sea02">
									<div class="da_sea02_01">时间范围</div>
									<el-col :span="11">
										<el-date-picker type="date" placeholder="开始日期" v-model="form.date1" style="width: 100%;"></el-date-picker>
									</el-col>
									<el-col class="line" :span="2">-</el-col>
									<el-col :span="11">
										<el-date-picker type="date" placeholder="结束日期" v-model="form.date2" style="width: 100%;"></el-date-picker>
									</el-col>
								</div>
								<div class="sea_02_02_r_2_1"><i class="el-icon-search"></i>搜索</div>
								<div class="sea_02_02_r_2_2"><i class="el-icon-upload2"></i>导出</div>
							</div>
						</div>
						<el-row :gutter="20">
							<template v-for="(item,index) of message">
								<el-col :span="6">
									<div class="tishi">
										<div class="ts_01">
											<div class="ts_01_l">{{item.types}}</div>
											<div :class="item.state?'ts_01_r':'ts_01_m'"><i class="el-icon-top" style="font-size: 18px"></i></div>
										</div>
										<div class="ts_02">
											<div class="ts_02_l">
												<span>{{item.num}}</span>
											</div>
										</div>
									</div>
								</el-col>
							</template>
						</el-row>
						<div class="article">
							<el-button type="primary" @click="add_user">添加奖品</el-button>
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="goods_name" label="商品名称"></el-table-column>
									<el-table-column prop="img.url" label="图片">
										<template slot-scope="scope">
											<template v-if="scope.row.img">
												<img :src="scope.row.img.url" />
											</template>
											<template v-if="!scope.row.img && scope.row.img_url">
												<img :src="scope.row.img_url[0]" />
											</template>
										</template>
									</el-table-column>
									<el-table-column prop="content" label="商品内容"></el-table-column>
									<el-table-column prop="points" label="积分"></el-table-column>
									<el-table-column prop="stock" label="库存"></el-table-column>
									<el-table-column prop="state" label="能否兑换">
										<template slot-scope="scope">
											{{scope.row.state == 1?'是':'否'}}
										</template>
									</el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">
											<el-button @click="edit(scope.row.id,scope.row.goods_name,scope.row.content,
											scope.row.img_id,scope.row.stock,scope.row.points)"
											 type="success" size="small">修改</el-button>

											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
							</template>

						</div>
						<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
						 @current-change="jump_page">
						</el-pagination>
					</el-main>

				</transition>


			</el-container>

		</el-container>


		<el-dialog title="修改" :visible.sync="dialogVisible" width="30%">

			<!-- 修改 -->
			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="商品名称">
					<el-input v-model="form.goods_name"></el-input>
				</el-form-item>
				<el-form-item label="商品内容">
					<el-input v-model="form.content"></el-input>
				</el-form-item>
				<el-form-item label="库存">
					<el-input v-model="form.stock"></el-input>
				</el-form-item>
				<el-form-item label="积分">
					<el-input v-model="form.points"></el-input>
				</el-form-item>
				<!-- {{img_url}}++ -->
				<el-form-item label="商品图片">
					<el-upload action="" list-type="picture-card" :on-preview="handlePictureCardPreview" :on-remove="handleRemove"
					 :limit="3">
						<i class="el-icon-plus"></i>
					</el-upload>
					<el-dialog :visible.sync="dialogVisiblea">
						<img width="100%" :src="dialogImageUrl" alt="">
					</el-dialog>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="sub_edit()">确
					定</el-button>
			</span>

		</el-dialog>


		<!-- 添加弹出框 -->
		<el-dialog title="添加商品" :visible.sync="dialogVisibleadd" width="30%">
			<el-form ref="form" :model="form_pro" label-width="80px">
				<el-form-item label="商品名称">
					<el-input v-model="form_pro.goods_name"></el-input>
				</el-form-item>
				<el-form-item label="商品内容">
					<el-input v-model="form_pro.content"></el-input>
				</el-form-item>
				<el-form-item label="库存">
					<el-input v-model="form_pro.stock"></el-input>
				</el-form-item>
				<el-form-item label="积分">
					<el-input v-model="form_pro.points"></el-input>
				</el-form-item>
				<el-form-item label="商品图片">
					<!-- <el-upload action="https://jsonplaceholder.typicode.com/posts/" list-type="picture-card" :on-preview="handlePictureCardPreview"
					 :on-remove="handleRemove" :on-success="res_banner_imgs" :file-list="upfile_banner_list">
						<i class="el-icon-plus"></i>
					</el-upload> -->

					<el-upload :action="upfile_url" :data="{use:'jp'}" :on-success="res_banner_imgs" :headers="upfile_head" :limit="5"
					 :file-list="upfile_banner_list" name="upload-images" :on-remove="handleRemove" list-type="picture-card">
						<i class="el-icon-plus"></i>
					</el-upload>
					<el-dialog :visible.sync="dialogVisiblea">
						<img width="100%" :src="dialogImageUrl" alt="">
					</el-dialog>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisibleadd = false">取 消</el-button>
				<el-button type="primary" @click="onSubmit">确 定</el-button>
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
				form: {
					ni: "",
					data1: '',
					data2: ""
				},
				message: [{
						"types": '总积分',
						"num": 12371289,
						"mess": "待发货",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '客户签到次数',
						"num": 88,
						"mess": "退换货",
						"state": 0,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '亲到送出积分',
						"num": 2131,
						"mess": "库存预警",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 1
					},
					{
						"types": '使用积分',
						"num": 1112,
						"mess": "待提现",
						"state": 1,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
				],
				dialogImageUrl: '',
				dialogVisiblea: false,
				tab_nav: false,
				dialogVisible: false,
				dialogVisibleadd: false,
				dialogFormVisible: false,
				oid: 0,
				form: {
					id: '',
					goods_name: '',
					content: '',
					img_id: [],
					stock: '',
					points: ''
				},
				form_pro: {
					goods_name: '',
					content: '',
					img_id: [],
					stock: '',
					points: ''
				},
				formLabelWidth: '120px',
				list: [],
				all: '',
				size: 10,
				total: '',
				options: [],
				value: '',
				typeList: [],
				upfile_banner_list: [],
				upfile_url: Api_url + 'com/up_img?back=id',
				upfile_head: {
					token: localStorage.getItem("token")
				},
				upfile_list: [], //上传文件列表
			}
		},
		components: {
			Header,
			NavTo
		},
		methods: {
			handleRemove(file, fileList) {
				console.log(file, fileList);
			},
			handlePictureCardPreview(file) {
				this.dialogImageUrl = file.url;
				this.dialogVisible = true;
			},
			onSubmit() {
				this.http.post_show('admin/add_goods', this.form_pro).then(() => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					});
					this.form = {}
					this.upfile_banner_list = []
					this.form_pro = {
							goods_name: '',
							content: '',
							img_id: [],
							stock: '',
							points: ''
						},
						this.dialogVisibleadd = false
					this.getCompany()
				})

			},
			res_banner_imgs(res) {

				console.log('res:', res)
				this.form_pro.img_id.push(res);
				// if (this.form_pro.img_id.length < 1) {
				// 	this.form_pro.img_id = res;
				// } else {
				// 	this.form_pro.img_id = this.form_pro.img_id + "," + res;
				// }
				console.log('xx:', this.form_pro.img_id)

			},
			add_user() {
				this.dialogVisibleadd = true
			},
			getmsg() {
				this.$confirm('此操作将退出管理系统, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					localStorage.clear(); //清除所有缓存
					this.$router.push({
						path: '/',
					})
					this.$message({
						type: 'success',
						message: '退出成功!'
					});
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消'
					});
				});
			},
			edit(id, goods_name, content, img_id, stock, points) {
				this.form.id = id
				this.form.goods_name = goods_name
				this.dialogVisible = true
				this.form.content = content
				this.form.img_id = img_id
				this.form.stock = stock
				this.form.points = points
				console.log(this.form)
			},
			sub_edit() {

				this.$confirm('确定修改?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('admin/edit_goods', this.form).then(() => {
						this.$message({
							type: 'success',
							message: '修改成功!'
						});
						this.dialogVisible = false
						this.clear_data()
						this.getCompany()
					})

				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消删除'
					});
				});



				this.http.post_show('admin/edit_goods', this.form).then(() => {
					this.clear_data()
					this.getCompany()
				})
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			sub() {
				var that = this;
				this.http.post_show('/admin/add_article', that.form)
					.then(() => {
						that.$message({
							showClose: true,
							message: '添加成功',
							type: 'success'
						});
						that.clear_data()
						that.getCompany()
					});
			},
			//获取商品列表
			getCompany() {
				// var that = this;
				// let loadingInstance = Loading.service({
				// 	fullscreen: true
				// }); //显示加载 
				// this.http.get('points/get_goods').then((res) => {
				// 	loadingInstance.close(); //关闭加载 
				// 	var res_code = res.status.toString();
				// 	if (res_code.charAt(0) == 2) {
				// 		that.all = res.data
				// 		that.list = res.data;
				// 		console.log(that.list)
				// 		that.list = res.data.slice(0, that.size);
				// 		that.total = res.data.length;
				// 	}
				// });
			},
			//删除商品
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('admin/del_goods', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.getCompany()
						// that.list.splice(index, 1);
					});
				})
			},
			close_fun(done) {
				this.clear_data()
				done(); //官方实例用法
			},

			clear_data() {
				this.dialogFormVisible = false
			},
		},
		mounted() {
			this.getCompany();
		}
	}
</script>

<style lang="less">
	.data {
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

		.search {
			line-height: 20px;
			margin-bottom: 15px;
			background-color: #fff;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;

			.sea_01 {
				border-bottom: 1px solid #F0F0F0;
				padding: 10px;
			}

			.sea_02 {
				padding: 15px;
				font-size: 13px;
				display: flex;
				line-height: 38px;

				.da_sea01 {
					display: flex;
					border: 1px solid #DCDFE6;
					margin-right: 20px;
					height: 38px;
					line-height: 38px;

					.da_sea01_01 {
						flex-shrink: 0;
						width: 120px;
						text-align: center;
						border-right: 1px solid #DCDFE6;
					}

					.el-input__inner {
						border: none;
						height: 38px;
					}
				}

				.da_sea02 {
					display: flex;
					margin-right: 20px;

					.da_sea02_01 {
						flex-shrink: 0;
						line-height: 40px;
						width: 120px;
						text-align: center;
						border: 1px solid #DCDFE6;
						border-right: none;
						height: 38px;
					}

					.line {
						text-align: center;
					}

					.el-input__inner {
						border-radius: 0px;
					}
				}

				.sea_02_02_r_2_1 {
					background-color: #008DE1;
					height: 40px;
					line-height: 40px;
					color: #fff;
					padding: 0 10px;
					margin-right: 10px;
					border-radius: 3px;
				}

				.sea_02_02_r_2_2 {
					height: 40px;
					line-height: 40px;
					padding: 0 10px;
					margin-right: 10px;
					border-radius: 3px;
					border: 1px solid #EFEFEF;
				}
			}
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
					background-color: #2E405C;
					color: #fff;
					font-size: 12px;
					padding: 0px 5px;
					border-radius: 3px
				}

				.ts_01_m {
					background-color: #0392E4;
					color: #fff;
					font-size: 12px;
					padding: 0px 5px;
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

		.article {
			background-color: #fff;
			padding: 15px;
			line-height: 30px;
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
