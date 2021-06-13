<template>
	<div class="adddiscount">
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
							<div class="xiao">活动信息</div>
							<el-form ref="form" :model="form" label-width="120px" class="demo-dynamic">
								<el-form-item label="活动名称">
									<el-input v-model="form.name" hide-required-asterisk style="width:500px"></el-input>
								</el-form-item>
								<el-form-item label="生效时间">
									<el-date-picker type="date" placeholder="选择日期" v-model="form.start_time" style="width: 200px;"></el-date-picker>
									&emsp;至&emsp;
									<el-date-picker type="date" placeholder="选择日期" v-model="form.end_time" style="width: 200px;"></el-date-picker>
								</el-form-item>
								<el-form-item label="活动标签">
									<el-input v-model="form.label" hide-required-asterisk style="width:300px"></el-input>
								</el-form-item>
								<el-form-item label="限购设置" prop="resource">
									<el-radio-group v-model="form.buy_rule">
										<el-radio :label="0">不限购</el-radio>
										<el-radio :label="1">每人每种商品限购&emsp;<el-input v-if="form.buy_rule == 1" v-model="form.buy_num" style="width:100px"></el-input>&emsp;件</el-radio>
										<el-radio :label="2">每人每种商品前&emsp;<el-input v-if="form.buy_rule == 2" v-model="form.buy_num" style="width:100px"></el-input>&emsp;件享受折扣</el-radio>
									</el-radio-group>
								</el-form-item>
							</el-form>
							<div class="xiao">选择活动商品</div>
							<el-tabs type="border-card" style="margin-bottom: 20px" v-model="active_name">
								<el-tab-pane label="第一步：选择商品" name="one">
									<div class="choose">
										<div class="search">
											<el-select v-model="value" placeholder="所有分组">
												<el-option label="一" value="shanghai"></el-option>
												<el-option label="二" value="beijing"></el-option>
											</el-select>&emsp;
											<el-button type="primary">搜索</el-button>
										</div>
										<el-table :data="tableData" style="width: 100%;" :row-class-name="tableRowClassName">
											<el-table-column prop="" label="商品信息">
												<template slot-scope="scope">
													<div class="pro">

														<div class="pro_02"><img :src="getimg +scope.row.imgs" /></div>
														<div class="pro_03">
															<div class="pro_03_1">{{scope.row.goods_name}}</div>
															<div class="pro_03_2">¥{{scope.row.price}}</div>
														</div>
													</div>
												</template>

											</el-table-column>
											<el-table-column prop="stock" label="库存">
											</el-table-column>
											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope">
													<el-button v-if="scope.row.is_discount == 1" type="primary" size="small" @click="add_discout(scope.row.goods_id,scope.$index,scope.row)">参加折扣</el-button>

													<el-button v-if="scope.row.is_discount == 2" type="danger" size="small" slot="reference" @click="del(scope.$index)">取消参加</el-button>
												</template>
											</el-table-column>
										</el-table>
									</div>
									<div class="quan">
										<!-- <div class="quan_l">
											<el-checkbox v-model="checked"></el-checkbox>&nbsp;全选
										</div> -->
										<div class="quan_r">共5条，每页30条</div>
									</div>
								</el-tab-pane>
								<el-tab-pane label="第二步：设置折扣" name="two" v-if="is_two == 1">
									<div class="choose">
										<el-table :data="discount_list" style="width: 100%;" :row-class-name="tableRowClassName">
											<el-table-column prop="" label="">
												<template slot-scope="scope">
													<div class="pro">
														<div class="pro_02"><img :src="getimg + scope.row.imgs" /></div>
														<div class="pro_03">
															<div class="pro_03_1">{{scope.row.goods.goods_name}}</div>
															<div class="pro_03_2">¥{{scope.row.goods.price}}</div>
														</div>
													</div>
												</template>
											</el-table-column>
											<el-table-column prop="" label="">
												<template slot-scope="scope">
													<div class="">
														减价：<el-input v-model="scope.row.reduce_price" hide-required-asterisk style="width:100px"></el-input>
													</div>
												</template>

											</el-table-column>
											<el-table-column prop="" label="">
												<template slot-scope="scope" v-if="scope.row.reduce_price">
													减价后：{{scope.row.goods.price-scope.row.reduce_price}}元
												</template>

											</el-table-column>
											<el-table-column prop="operation" label="" width="300px">
												<template slot-scope="scope">
													<el-button type="primary" size="small" @click="del_disgoods(scope.$index)">取消</el-button>
												</template>
											</el-table-column>
										</el-table>
									</div>
									<div class="quan">
										<!-- <div class="quan_l">
											<el-checkbox v-model="checked"></el-checkbox>&nbsp;全选
										</div> -->
										<div class="quan_r">共5条，每页30条</div>
									</div>
								</el-tab-pane>
								<el-pagination background layout="prev, pager, next" :total="total"  @current-change="jump_page">
								</el-pagination>

							</el-tabs>

							<span slot="footer" class="dialog-footer ">
								<el-button @click="jumpback">取 消</el-button>
								<el-button @click="next" v-if="is_two == 0">下一步</el-button>
								<el-button @click="back" v-if="is_two == 1">上一步</el-button>
								<el-button type="primary" @click="onSubmit" v-if="is_two == 1">确 定</el-button>
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
				active_name: 'two',
				is_two: 1,
				discount_list: [],
				getimg: this.$getimg,
				choose: true,
				tableData: [],
				tab_nav: false,
				dialogVisibleadd: false,
				dialogFormVisible: false,
				oid: 0,
				form: {
					id: '',
					name: '',
					buy_rule: '',
					goods_json: [],
					label: '',
					start_time: '',
					end_time: '',
					day_json: [],
					time_rule: 0,
					buy_num: '',
				},
				list: [],
				all: '',
				size: 10,
				total: '',
				value: '',
			}
		},
		watch: {
			active_name() {
				if (this.active_name == 'one') {
					this.is_two = 0
				}
				if (this.active_name == 'two') {
					this.is_two = 1
				}
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			const site = JSON.parse(localStorage.getItem("edit_data"));
			if (site) {
				console.log(site)
				this.form.id = site.id
				this.form.name = site.name
				this.form.start_time = site.start_time
				this.form.end_time = site.end_time
				this.form.label = site.label
				this.discount_list = site.discount_goods
				this.form.buy_rule = site.buy_rule
				this.form.buy_num = site.buy_num
			}
			this._load()
			// this.goods_json();
		},
		methods: {
			_load() {
				this.http.get('product/admin/get_normal_goods').then(res => {

					for (let k in res.data) {
						let v = res.data[k]
						v.is_discount = 1
					}
					this.all = res.data
					this.total = res.data.length
					this.tableData = res.data.slice(0,this.size)
					console.log(this.tableData)
				})
			},
			jump_page(e) {
				console.log(e)
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableData = this.all.slice(start, end);
			},
			//添加为折扣商品
			add_discout(id, index, item) {
				let obj = {
					goods_id: id,
					discount_type: 1,
					reduce_price: '',
					discount_price: 0
				}
				let obj_2 = {
					imgs: item.imgs,
					goods_id: item.goods_id,
					goods: {
						goods_name: item.goods_name,
						price: item.price
					},
					reduce_price: '',
					discount_type: 1,
					discount_price: 0
				}
				this.tableData[index].is_discount = 2
				this.form.goods_json.push(obj)
				this.discount_list.push(obj_2)
				console.log(this.form)
				console.log(this.discount_list)
			},
			//取消折扣
			del(index) {
				this.tableData[index].is_discount = 1
				this.form.goods_json.splice(index, 1)
				this.discount_list.splice(index, 1)
				console.log(this.form)
			},
			//删除已选中折扣商品列表中商品
			del_disgoods(index) {
				this.discount_list.splice(index, 1)
			},
			get_goods(e) {
				console.log(e)
			},
			next() {
				this.active_name = 'two'
				this.is_two = 1
			},
			back() {
				this.is_two = 0
				this.active_name = 'one'
			},
			onSubmit() {
				console.log(this.discount_list)
				const that = this
				let arr = []
				let obj = {
					goods_id: '',
					discount_type: '',
					reduce_price: '',
					discount_price: ''
				}
				// this.form.goods_json = this.discount_list
				for (let k in this.discount_list) {
					let v = this.discount_list[k]
					obj.goods_id = v.goods_id
					obj.discount_type = v.discount_type
					obj.reduce_price = v.reduce_price
					obj.discount_price = v.discount_price
					arr.push(obj)
					obj = {
						goods_id: '',
						discount_type: '',
						reduce_price: '',
						discount_price: ''
					}
				}
				this.form.goods_json = arr
				console.log(this.form)
				this.http.post_show('discount/admin/edit_discount', this.form).then(res => {
					if (res.status == 400) {
						that.$message({
							showClose: true,
							message: res.msg,
							type: 'success'
						});
					} else {
						that.$message({
							showClose: true,
							message: '修改成功',
							type: 'success'
						});
					}

					this.jumpback()
				})
			},
			add_user() {
				this.dialogVisibleadd = true
			},

			edit(id, goods_name, content, img_id, stock, points) {
				this.form.id = id
				this.form.goods_name = goods_name
				this.form.content = content
				this.form.img_id = img_id
				this.form.stock = stock
				this.form.points = points
				console.log(this.form)
			},
			jumpback() {
				this.$router.push({
					path: './discount'
				})
			},
			//获取商品列表
			goods_json() {},

		},

	}
</script>

<style lang="less">
	.adddiscount {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.search {
			text-align: left;
			border-bottom: 1px solid #E6E6E6;
			padding-bottom: 15px
		}

		.xiao {
			padding: 10px 10px 15px 15px;
			text-align: left;
			font-size: 15px;
			font-weight: 600;
		}

		.quan {
			display: flex;
			justify-content: space-between;
			padding: 15px 10px;

			.quan_r {
				font-size: 13px;
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
					color: #2768D7
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
				padding: 0 0 15px 130px;
				margin-top: -20px;
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
