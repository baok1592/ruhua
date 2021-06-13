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
							<div class="xiao">选择分销商品</div>
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
										<el-table :data="tableDataA" style="width: 100%;">
											<el-table-column prop="" label="商品信息">
												<template slot-scope="scope">
													<div class="pro">
														<!-- <div class="pro_01">
															<el-checkbox></el-checkbox>
														</div> -->
														<div class="pro_02"><img :src="$getimg + scope.row.imgs" /></div>
														<div class="pro_03">
															<div class="pro_03_1">{{scope.row.goods_name}}</div>
															<div class="pro_03_2">¥{{scope.row.price}}</div>
														</div>
													</div>
												</template>

											</el-table-column>

											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope">
													<el-button v-if="scope.row.is_reseller == 0" type="primary" size="small" @click="join(scope.row,scope.$index)">参加分销</el-button>

													<el-button v-if="scope.row.is_reseller == 1" style="margin-left: 10px" type="danger" size="small" slot="reference" @click="cancel(scope.$index,scope.row.goods_id)">取消</el-button>
												</template>
											</el-table-column>
										</el-table>
									</div>
									<div class="quan">
										<div class="quan_l">
											<el-button style="margin-left: 10px" type="danger" size="small" @click="quanxuan">全选</el-button>
										</div>
										<!-- <div class="quan_r">共{{tableDataA.length}}条，每页10条</div> -->
									</div>
								</el-tab-pane>
								<el-tab-pane label="第二步：设置佣金" name="two" v-if="is_two == 1">
									<div class="choose">
										<el-table :data="tableDataB" style="width: 100%;">
											<el-table-column prop="" label="">
												<template slot-scope="scope">
													<div class="pro">
														<!-- <div class="pro_01">
														<el-checkbox v-model="checked"></el-checkbox>
													</div> -->
														<div class="pro_02"><img :src="$getimg + scope.row.imgs" /></div>
														<div class="pro_03">
															<div class="pro_03_1">{{scope.row.goods_name}}</div>
															<div class="pro_03_2">¥{{scope.row.price}}</div>
														</div>
													</div>
												</template>

											</el-table-column>
											<el-table-column>
												<template slot-scope="scope">

													<div class="">
														分销佣金：<input v-model="scope.row.fx_price" placeholder="请输入佣金金额" style="width: 200px;border: 1px solid #00B7EE;border-radius: 4px; height: 30px;padding-left: 10px;"></input>
													</div>
												</template>
											</el-table-column>
											<el-table-column prop="operation" label="" width="300px">
												<template slot-scope="scope">
													<el-button type="primary" size="small" @click="cancel(scope.$index,scope.row.goods_id)">取消</el-button>
												</template>
											</el-table-column>
										</el-table>
									</div>
									<div class="quan">
										<!-- 	<div class="quan_l">
											<el-checkbox v-model="checked"></el-checkbox>&nbsp;全选
										</div> -->
										<!-- <div class="quan_r">共{{tableDataB.length}}条，每页10条</div> -->
									</div>
								</el-tab-pane>
								<el-pagination background layout="prev, pager, next" :total="total"  @current-change="jump_page">
								</el-pagination>
							</el-tabs>

							<span slot="footer" class="dialog-footer ">
								<el-button @click="jumpback">取 消</el-button>
								<el-button type="primary" v-if="is_next" @click="next">下一步</el-button>
								<el-button type="primary" v-if="!is_next" @click="back">上一步</el-button>
								<el-button type="primary" v-if="!is_next" @click="onSubmit">确 定</el-button>
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
				is_reseller:1,
				is_two:0,
				active_name: 'one',
				is_next: true,
				choose: true,
				price: '',
				obj: {
					id: '',
					commission: '',
				},
				checked: '',
				tableDataA: [], //未分销
				tableDataB: [], //已分销产品
				oid: 0,
				size: 8,
				total: 0,
				value: '',
			}
		},
		watch:{
			active_name(){
				if(this.active_name == 'one'){
					this.is_two = 0
					this.is_next = true
				}
				if(this.active_name == 'two'){
					this.is_two = 1
					this.is_next = false
				}
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this._load();
		},
		methods: {
			_load() {
				this.http.get('fx/admin/get_goods?type=0').then(res => {
					for(let k in res.data){
						let v = res.data[k]
						v.is_reseller = 0
					}
					this.all = res.data
					this.total = res.data.length
					this.tableDataA = res.data.slice(0,this.size)
					console.log(this.tableDataA)
				})
			},
			jump_page(e) {
				console.log(e)
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableDataA = this.all.slice(start, end);
			},
			quanxuan() {
				this.tableDataB = this.tableDataA
				// for (let v of this.tableDataA) {
					
				// 	this.tableDataB.push(v)
				// }
				this.$message({
					message: '已全部添加',
					type: 'success'
				});
			},
			next() {
				this.is_two = 1
				this.active_name = 'two'
				this.is_next = false
			},
			back() {
				this.is_two = 0
				this.active_name = 'one'
				this.is_next = true
			},
			join(item,index) {
				this.tableDataA[index].is_reseller = 1
				const that = this
				let list = that.tableDataB
				if (list.length == 0) {
					that.tableDataB.push(item)
					this.$message({
						message: '添加成功',
						type: 'success'
					});
				} else {
					if (JSON.stringify(list).indexOf(JSON.stringify(item)) == -1) {
						//使用string方法判断数组中对象是否重复需要保证对象中值的顺序一致
						list.push(item);
						this.$message({
							message: '添加成功',
							type: 'success'
						});
					} else {
						this.$message({
							message: '请勿重复添加',
							type: 'warning'
						});
					}
				}
			},
			cancel(index, id) {
				this.tableDataA[index].is_reseller = 0
				for (let s of this.tableDataB) {
					if (id == s.goods_id) {
						this.tableDataB.splice(index, 1)
					}
				}
				console.log(this.tableDataB)
			},
			onSubmit() {
				var arr = [];
				this.tableDataB.forEach(function(item) {
					arr.push({
						goods_id: item.goods_id,
						price: item.fx_price,
						pro_price: item.price
					})
				})
				for (let v in arr) {
					if (arr[v].price * 1 >= arr[v].pro_price * 1) {
						this.$message({
							message: '分销佣金必须小于商品价格',
							type: 'warning'
						});
						return
					}
				}
				this.http.post_show('fx/admin/add_goods', arr).then(res => {
					if (res.status == 200) {
						this.$message({
							message: '添加成功',
							type: 'success'
						});
						this.$router.push({
							path: '/extend/reseller'
						})
					}
				})
			},
			jumpback() {
				this.$router.push({
					path: '/extend/reseller'
				})
			},
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
