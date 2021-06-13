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
							<el-button type="primary" @click="add_user">添加优惠券</el-button>
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="name" label="优惠券名称"></el-table-column>
									<!-- <el-table-column prop="img.url" label="图片">
										<template slot-scope="scope">
											<template v-if="scope.row.img">
												<img :src="scope.row.img.url" />
											</template>
											<template v-if="!scope.row.img && scope.row.img_url">
												<img :src="scope.row.img_url[0]" />
											</template>
										</template>
									</el-table-column> -->
									<el-table-column prop="status" label="使用次数">
										<template slot-scope="scope">
											{{scope.row.status == 1?'一次':'不限'}}
										</template>
									</el-table-column>
									<el-table-column prop="type" label="类型">
										<template slot-scope="scope">
											{{scope.row.type == 1?'店铺优惠券':'其他'}}
										</template>
									</el-table-column>
									<el-table-column prop="goods_ids" label="可使用商品">
										<template slot-scope="scope">
											{{scope.row.goods_ids == 0?'全部商品':'部分商品'}}
										</template>
									</el-table-column>
									<el-table-column prop="full" label="满多少">
										<template slot-scope="scope">
											<template v-if="scope.row.full == 0">无门槛</template>
											<template v-else>{{scope.row.full}}</template>
										</template>
									</el-table-column>
									<el-table-column prop="reduce" label="减多少"></el-table-column>
									<el-table-column prop="start_time" label="起始时间"></el-table-column>
									<el-table-column prop="end_time" label="结束时间"></el-table-column>
									<el-table-column prop="stock" label="库存">
										<template slot-scope="scope">
											<template v-if="!scope.row.stock">无限张</template>
											<template v-else>{{scope.row.stock}}</template>
										</template>
									</el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">
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
	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				status: '1',
				goods_ids: '0',
				dialogImageUrl: '',
				dialogVisible: false,
				oid: 0,
				form: {},
				list: [],
				value: '',
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
			add_user() {
				this.$router.push({
					path: './addcoupon'
				})
			},
			edit(item) {
				this.form = item
				this.dialogVisible = true
			},
			//获取优惠券列表
			get_coupon() {
				this.http.get('coupon/admin/get_coupon').then(res => {
					this.list = res.data
				})
			},
			//删除优惠券
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('coupon/admin/del_coupon', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_coupon()
						// that.list.splice(index, 1);
					});
				})
			},
		},
		mounted() {
			this.get_coupon();
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
