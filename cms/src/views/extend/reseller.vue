<template>
	<div class="discount">
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
							<el-button type="primary" @click="add_user">添加分销商品</el-button>
							<div style="height:20px;">&nbsp;</div>
							<el-table :data="list" border>
								<el-table-column type="index" label="序号" width="50px"></el-table-column>
								<el-table-column prop="goods_info.goods_name" label="商品名称" width="400"></el-table-column>
								<el-table-column prop="goods_info.imgs" label="图片" width="110">
									<template slot-scope="scope">
										<template v-if="scope.row.goods_info">
											<img :src="$getimg + scope.row.goods_info.imgs" />
										</template>
									</template>
								</el-table-column>
								<el-table-column prop="goods_info.price" label="商品价格" width="100"></el-table-column>
								<el-table-column prop="price" label="分销佣金" width="150">
									<template slot-scope="scope">
										<el-input v-model="scope.row.price"></el-input>
									</template>
								</el-table-column>
								<el-table-column prop="operation" label="操作">
									<template slot-scope="scope">
										<el-button @click="edit(scope.row.id,scope.row.price,scope.row.goods_info.price)" type="success" size="small">修改</el-button>
										<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
									</template>
								</el-table-column><strong></strong>
							</el-table>
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
				dialogImageUrl: '',
				dialogVisiblea: false,
				tab_nav: false,
				price: '',
				dialogVisibleadd: false,
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
				list: [],
				all: '',
				size: 10,
			}
		},
		components: {
			Header,
			NavTo
		},
		methods: {
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
					this.get_reseller()
				})

			},
			add_user() {
				this.$router.push({
					path: '/extend/addreseller'
				})
			},
			edit(id, price, goods_price) {
				if (price * 1 < goods_price * 1) {
					this.http.post_show('fx/admin/edit_goods', {
						id: id,
						price: price
					}).then(res => {
						this.$message({
							showClose: true,
							message: '修改成功',
							type: 'success'
						});
					})
				} else {
					this.$message({
						message: '分销佣金必须小于商品价格',
						type: 'warning'
					});
				}

			},
			//获取商品列表
			get_reseller() {
				this.http.get('fx/admin/get_goods?type=1').then(res => {
					this.list = res.data
				})
			},
			//删除商品
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('fx/admin/del_goods', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_reseller()
						// that.list.splice(index, 1);
					});
				})
			},
		},
		mounted() {
			this.get_reseller();
		}
	}
</script>

<style lang="less">
	.discount {
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
