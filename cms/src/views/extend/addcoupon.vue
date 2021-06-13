<template>
	<div class="addcoupon">
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
						<div class="add">
							<div class="add_btn">
								<el-button type="primary" @click="jumpback">返回</el-button>
							</div>
							<el-form ref="form" :model="form" label-width="120px" class="demo-dynamic">
								<el-form-item label="优惠券名称">
									<el-input v-model="form.name" hide-required-asterisk style="width:500px"></el-input>
								</el-form-item>
								<el-form-item label="发放总量">
									<el-input v-model="form.stock" style="width:200px"></el-input>
									<div class="dan">张</div>
								</el-form-item>
								<div class="ts">修改优惠券总量时只能增加不能减少，请谨慎设置</div>
								<el-row>
									<el-col :span="6">
										<el-form-item label="适用商品">
											<el-radio-group v-model="is_all" style="padding-top: 14px;" @change="get_value">
												<el-radio :label="0">全部商品可用</el-radio>
												<el-radio :label="1">指定商品可用</el-radio>
											</el-radio-group>

										</el-form-item>
									</el-col>
									<el-col :span="6">
										<div class="xzsp" v-if="is_all==1" @click="dialogVisibleadd = true">选择商品</div>
									</el-col>


								</el-row>

								<div class="spxz" v-if="is_all==1">
									<el-collapse v-model="activeNames" @change="handleChange">
										<el-collapse-item title="已选择商品" name="1">

											<el-table border :data="canjia_list" style="width: 100%;" :row-class-name="tableRowClassName">
												<el-table-column prop="" label="所有商品" width="">
													<template slot-scope="scope">
														<div class="shiyong">
															<div class="sy_01"><img :src="$getimg + tableData[scope.row].imgs" /></div>
															<div class="sy_02">
																<div class="sy_02_1">{{tableData[scope.row].goods_name}}--{{scope.row}}</div>
																<div class="sy_02_2">¥{{tableData[scope.row].price}}&emsp;库存：{{tableData[scope.row].stock}}</div>
															</div>
														</div>
													</template>

												</el-table-column>
												<el-table-column prop="operation" label="操作" width="100px">
													<template slot-scope="scope">
														<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row)">移除</el-button>
													</template>
												</el-table-column>
											</el-table>

										</el-collapse-item>
									</el-collapse>
								</div>
								<el-form-item label="使用门槛" prop="resource">
									<el-radio-group v-model="menkan" @change="is_menkan">
										<el-radio :label="0">无门槛</el-radio>
										<el-radio :label="2">订单满&emsp;
											<el-input v-if="show_ininput == 1" v-model="form.full" style="width:100px"></el-input>
											&emsp;元
										</el-radio>
									</el-radio-group>
								</el-form-item>
								<el-form-item label="优惠价格">
									<div class="jian">减免</div>
									<el-input v-model="form.reduce" style="width:100px"></el-input>
									<div class="dan">元</div>
								</el-form-item>
								<el-form-item label="用券时间">
									<el-radio-group v-model="form.time" style="text-align: left;">
										<el-radio label="">
											<el-date-picker type="date" placeholder="选择日期" v-model="form.start_time" style="width: 200px;" value-format="yyyy-MM-dd"></el-date-picker>
											&emsp;-&emsp;
											<el-date-picker type="date" placeholder="选择日期" v-model="form.end_time" style="width: 200px;" value-format="yyyy-MM-dd"></el-date-picker>
										</el-radio><br />
										<div style="height: 23px"></div>
										<el-radio label="1">领券当日起&emsp;<el-input v-model="form.day" style="width:100px"></el-input>&emsp;天内可用</el-radio>

									</el-radio-group>
								</el-form-item>
								<el-form-item label="使用次数" prop="resource">
									<el-radio-group v-model="form.status" style="margin-top: 15px;">
										<el-radio label="1">有限</el-radio>
										<el-radio label="2">无限</el-radio>
									</el-radio-group>
								</el-form-item>

							</el-form>
							<span slot="footer" class="dialog-footer ">
								<el-button @click="jumpback">取 消</el-button>
								<el-button type="primary" @click="onSubmit">确 定</el-button>
							</span>
						</div>
					</el-main>
				</transition>
			</el-container>

		</el-container>
		<!-- 选择商品弹出框 -->
		<el-dialog title="" :lock-scroll="true" :top="0" :destroy-on-close="true" :visible.sync="dialogVisibleadd" width="100%"
		 style="width:60vw;height:90vh;margin:10vh 0 0 20vw">
			<span>
				<div style="text-align: left;">
					<el-button style="margin-right: 10px" type="danger" size="small" slot="reference" @click="alls">全选</el-button>
				</div>
			</span>
			<el-table :data="tableData" style="width: 100%;" :row-class-name="tableRowClassName">
				<el-table-column prop="goods_name" label="商品">
					<template slot-scope="scope">
						<div class="pro">
							<div class="pro_02"><img :src="$getimg + scope.row.imgs" /></div>
							<div class="pro_03">
								<div class="pro_03_1">{{scope.row.goods_name}}</div>
								<div class="pro_03_2">¥{{scope.row.price}}</div>
							</div>
						</div>
					</template>

				</el-table-column>
				<!-- <el-table-column prop="name" label="商品分组" width="180">
				</el-table-column> -->
				<el-table-column prop="sales" label="近30天销量/累计销量" width="180">
				</el-table-column>
				<el-table-column prop="stock" label="库存" width="80">
				</el-table-column>

				<el-table-column prop="operation" label="操作" width="300px">
					<template slot-scope="scope">
						<el-button v-if="scope.row.is_canjia == 0" type="primary" size="small" @click="canjia(scope.$index)">选择</el-button>
						<el-button v-if="scope.row.is_canjia == 1" type="warning" size="small" @click="cancel(scope.$index)">取消</el-button>
					</template>
				</el-table-column>
			</el-table>
			<span slot="footer" class="dialog-footer ">

				<el-button type="danger" @click="dialogVisibleadd = false">关闭</el-button>
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
				show_ininput: 0,
				menkan: '',
				canjia_list: [],
				is_all: '',
				top: "0",
				activeNames: ['1'],
				tableRowClassName: '',
				tableData: [],
				dialogVisibleadd: false,
				form: {
					goods_ids: [],
					stock: '',
					name: '',
					full: '',
					reduce: '',
					start_time: '',
					end_time: '',
					time: '',
					day: '',
					status: '',
					stock_type: ''
				},
			}
		},
		components: {
			Header,
			NavTo
		},
		methods: {
			is_menkan(e) {
				console.log(e)
				if (e == 2) {
					this.show_ininput = 1
				} else {
					this.form.full = 0
					this.show_ininput = 0
				}
			},
			handleChange(val) {
				console.log(val);
			},
			get_value(e) {
				console.log(e)
				let a = []
				if (e == 0) {
					a.push(e)
				}
				this.form.goods_ids = a
			},
			//全选商品
			alls() {
				for (let k in this.tableData) {
					let v = this.tableData[k]
					this.canjia_list.push(k)
					v.is_canjia = 1
				}
			},
			//选择商品
			canjia(index) {
				this.tableData[index].is_canjia = 1
				this.canjia_list.push(index)
				console.log(this.canjia_list)
			},
			//取消新品参加优惠券
			cancel(index) {
				this.tableData[index].is_canjia = 0
				for (var i = 0; i < this.canjia_list.length; i++) {
					if (index == this.canjia_list[i]) {
						this.canjia_list.splice(i, 1)
					}
				}
				console.log(this.canjia_list)
			},
			//移除所选商品
			del(index) {
				for (var i = 0; i < this.canjia_list.length; i++) {
					if (index == this.canjia_list[i]) {
						this.canjia_list.splice(i, 1)
						this.tableData[index].is_canjia = 0
					}

				}
				console.log(this.canjia_list)
			},
			//获取所有商品
			get_pro() {
				this.http.post('product/admin/get_product').then(res => {
					for (let k in res.data) {
						let v = res.data[k]
						v.is_canjia = 0
					}
					this.tableData = res.data
				})
			},
			//添加优惠券
			onSubmit() {
				for (let k in this.canjia_list) {
					let v = this.canjia_list[k]
					this.form.goods_ids.push(this.tableData[v].goods_id)
				}
				console.log(this.form)

				if (this.form.time) {
					if (this.form.day == '') {
						this.$message({
							type: 'warning',
							message: '未填写天数!'
						});
						return
					}
				}
				if (!this.form.time) {
					if (this.form.start_time == '' || this.form.end_time == '') {
						this.$message({
							type: 'warning',
							message: '未填写起止日期'
						});
						return
					}
				}
				this.http.post_show('coupon/admin/add_coupon', this.form).then(() => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					});
					this.form = {}
					this.dialogVisibleadd = false
					this.$router.push({
						path: '/coupon'
					})
				})

			},
			//返回
			jumpback() {
				this.$router.push({
					path: '/coupon'
				})
			},
		},
		mounted() {
			this.get_pro()
		}
	}
</script>

<style lang="less">
	.addcoupon {
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

			.el-form-item__content {
				padding-left: 15px;
			}
		}

		.pro {
			display: flex;

			.pro_01 {
				padding-right: 10px;
				display: flex;
				flex-direction: column;
				justify-content: center;
			}

			.pro_02 img {
				height: 60px !important;
				;
				width: 60px !important;
				padding-right: 10px
			}

			.pro_03 {
				display: flex;
				flex-direction: column;
				justify-content: space-between;

				.pro_03_1 {
					color: #2768D7;
					text-overflow: -o-ellipsis-lastline;
					overflow: hidden;
					text-overflow: ellipsis;
					display: -webkit-box;
					-webkit-line-clamp: 2;
					-webkit-box-orient: vertical;
				}

				.pro_03_2 {
					color: #FF6600
				}
			}
		}

		.tableRowClassName {
			background-color: #f0f0f0;
		}

		.add {
			background-color: #fff;
			padding: 15px;

			.add_btn {
				padding: 0 0 20px 20px;
				text-align: left;
			}

			.dan {
				padding-left: 10px
			}

			.ts {
				text-align: left;
				font-size: 12px;
				color: #A6A7A8;
				padding-left: 130px;
				margin-top: -18px;
				padding-bottom: 18px;
			}

			.jian {
				padding-right: 10px
			}

			.line {
				padding: 0 15px;
			}

			.xzsp {
				color: #155BD4;
				text-align: left;
				// padding: 0 0 15px 130px;
				margin-top: 10px;
				font-size: 14px;
			}

			.spxz {
				padding: 0 0 25px 130px;
				width: 40%;

				.shiyong {
					display: flex;

					.sy_01 img {
						height: 40px !important;
						;
						width: 40px !important;
						padding-right: 10px
					}

					.sy_02 {
						display: flex;
						flex-direction: column;
						justify-content: space-between;

						.sy_01_1 {
							color: #2768D7
						}

						.sy_01_2 {
							color: #FF6600
						}
					}
				}
			}
		}

		.article {
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
