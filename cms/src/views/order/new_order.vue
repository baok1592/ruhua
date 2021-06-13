<template>
	<div>
		<transition appear appear-active-class="animated fadeInLeft">
			<div class="order">
				<div v-if="addShow">
					<div class="order-back">
						<el-button type="primary" size="small" @click="back">返回</el-button>
					</div>
					<order-details :order_id="this.order_id"></order-details>
				</div>

				<template v-if="!addShow">
					<!-- <div class="list-head">
						<div class="liat-head-02">订单号： {{reset_key}}
							<el-input placeholder="请输入内容" style="width: 70%" size="small" v-model="value"></el-input>
						</div>
						<div class="liat-head-03">
							<el-row>
								<el-button type="primary" size="small" @click="search">搜索</el-button>
								<el-button type="success" size="small" @click="alls">刷新</el-button>&emsp;
								<el-button size="small" @click="daochu">导出数据</el-button>
							</el-row>
						</div>
					</div> -->
					<el-table :data="list" border style="width: 100%">
						<el-table-column type="index" label="序号" width="50"></el-table-column>
						<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
						<el-table-column label="商品名称" width="280">
							<template slot-scope="scope">
								<div v-for="item,key of scope.row.ordergoods">{{item.goods_name | ellipsis}}</div>
							</template>
						</el-table-column>
						<el-table-column label="订单价格" width="100">
							<!-- <template slot-scope="scope" {{scope.row.order_money/100}}</template> -->
							<template slot-scope="scope">
								<el-button type="text" @click="open(scope.$index)">{{scope.row.order_money/100}}</el-button>
							</template>
						</el-table-column>
						<el-table-column prop="message" label="客户备注" width="160">
						</el-table-column>
						<el-table-column prop="users.nickname" label="用户" width="160"></el-table-column>
						<el-table-column prop="create_time" label="创建日期" width="180"></el-table-column>
						<el-table-column label="支付状态" width="100" :filters="[{ text: '已支付', value: 1 }, { text: '未支付', value: 0 }]"
						 :filter-method="filterTag">
							<template slot-scope="scope">
								<p v-if="scope.row.pay_status == 1">已支付</p>
								<p v-else style="color:Red;">未支付</p>
							</template>
						</el-table-column>

						<el-table-column label="退货状态" width="100" :filters="[{ text: '已退货', value: 1 }, { text: '未退货', value: 0 }]"
						 :filter-method="filter_tui">
							<template slot-scope="scope">
								<p v-if="scope.row.tui_status == 1">已退货</p>
								<p v-else style="color:Red;">未退货</p>
							</template>
						</el-table-column>

						<el-table-column label="收货状态" width="100" :filters="[{ text: '已收货', value: 1 }, { text: '未收货', value: 0 }]"
						 :filter-method="filter_receive">
							<template slot-scope="scope">
								<p v-if="scope.row.receive_status == 1">已收货</p>
								<p v-else style="color:#E6A23C;">未收货</p>
							</template>
						</el-table-column>

						<el-table-column label="货物状态" :filters="[{ text: '已发货', value: 1 }, { text: '未发货', value: 0 }]" :filter-method="filter_send">
							<template slot-scope="scope">
								<p style="color:#909399" v-if="scope.row.drive_status == 1 && scope.row.receive_status == 1">已收货</p>
								<p style="color:#67C23A" v-else-if="scope.row.drive_status == 1 && scope.row.receive_status == 0">已发货</p>
								<p style="color:#E6A23C" v-else>未发货</p>
							</template>
						</el-table-column>
						<el-table-column prop="operation" label="操作" width="160">
							<template slot-scope="scope">
								<el-button @click="show_order(scope.row.order_id)" type="primary" size="small">查看</el-button>
								<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.order_id,scope.$index)">删除</el-button>
							</template>
						</el-table-column>
					</el-table>

					<div class="H20"></div>
					<el-pagination background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page"></el-pagination>
				</template>
			</div>
		</transition>
	</div>
</template>

<script>
	import OrderDetails from "./Details.vue";
	import {
		Loading
	} from "element-ui";
	import {
		Api_url
	} from "../../common/config";

	export default {
		name: "Order",
		filters: {
			ellipsis(value) {
				if (!value) return "";
				if (value.length > 18) {
					return value.slice(0, 18) + "...";
				}
				return value;
			}
		},
		data() {
			return {
				value: "",
				visible2: false,
				addShow: false, //设置要显示的页面部分默认为false，隐藏
				checkdDistributor: null,
				order_id: 0,
				size: 10,
				total: "",
				all: "",
				list: "",
				status: 0,
				total2:''
			};
		},
		props: ['search_key', 'reset_key', 'range_start', 'range_end','quanbu','total'],
		components: {
			OrderDetails
		},
		watch: {
			search_key() {
				if (this.search_key) {
					this.value = this.search_key
					this.search()
				}

			},
			reset_key() {
				if (this.reset_key == 1) {
					this.total = ''
					console.log(this.reset_key)
					this.alls()
				}
			},
			range_start() {
				if (this.range_start) {
					this.status = 1
					console.log(this.range_start)
					this.post_order()
				}
			},
			range_end() {
				if (this.range_end) {
					console.log(this.range_end)
				}
			},
			quanbu(){
				if(this.quanbu == 1){
					this.post_order()
				}
			},
			total(){
				if(this.total){
					console.log(this.total)
					this.total2 = this.total
					this.post_order()
				}
			}

		},
		//vue生命函数
		mounted() {
			this.post_order();
		},
		methods: {
			filter_receive(value, row) {
				return row.receive_status === value;
			},
			filter_tui(value, row) {
				return row.tui_status === value;
			},
			filter_send(value, row) {
				return row.drive_status === value;
			},
			filterTag(value, row) {
				return row.pay_status === value;
			},
			search() {

				if (this.search_key) {
					this.value = this.search_key
				}
				this.post_order(1, this.value);
			},
			alls() {
				let loadingInstance = Loading.service();
				this.value = "";
				this.post_order();
				loadingInstance.close();

			},

			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				this.list = this.all.slice(start, end);
			},
			handleClick(row) {
				console.log(row);
			},
			post_order(index = 1, keywords = "") {
				var that = this;
				this.http
					.post("/admin/get_order_all", {
						page: index,
						size: this.size,
						keywords: keywords
					}).then(res => {
						that.all = res.data;
						that.list = res.data.slice(0, that.size);
						if(!that.total2){
							that.total = res.data.length;
						}else{
							that.total = that.total2
						}
						let time = []
						let arr = []
						for (let v in res.data) {
							time[v] = res.data[v].create_time
							time[v] = new Date(res.data[v].create_time).getTime()
						}
						localStorage.setItem("time", JSON.stringify(time))
						
						if (that.status == 1) {
							that.list = []
							for (let v in res.data) {
								time[v] = res.data[v].create_time
								time[v] = new Date(res.data[v].create_time).getTime()
								let T = new Date(res.data[v].create_time).getTime()
								if (this.range_start < T && T < this.range_end) {
									that.list.push(res.data[v])
								}
							}
						}




						// for (let v in res.data) {     //日期去重
						// 	arr[v] = res.data[v].create_time
						// 	if(arr.indexOf(arr[v]) == v){
						// 		time.push(arr[v])
						// 	}
						// }

					});
			},
			show_order(row) {
				this.addShow = true; // addshow为要显示的页面
				this.order_id = row;
			},
			back() {
				this.order_id = 0;
				this.addShow = false;
			},
			//删除订单
			del(id, index) {
				var that = this;
				this.$confirm("是否删除?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning"
				}).then(() => {
					this.http
						.put_show("/admin/del_order", {
							id: id
						})
						.then(res => {
							if (res.data == 1) {
								this.$message({
									showClose: true,
									message: "删除成功",
									type: "success"
								});
								this.order.splice(index, 1);
								return false;
							} else if (res.response.status == 400) {
								this.$alert(res.response.data.msg, "");
								return false;
							} else {
								this.$message({
									showClose: true,
									message: "删除失败",
									type: "error"
								});
								return false;
							}
						});
				});
			},
			daochu() {
				const aLink = document.createElement('a');
				let token = localStorage.getItem('token');
				aLink.href = Api_url + 'daochu?token=' + token
				aLink.target = '_blank'
				aLink.download = 'ly_2019.csv';
				aLink.click();
				aLink.remove();
			},
			open(index) {
				this.$prompt('原价' + this.list[index].order_money / 100 + '，增减金额:', '修改', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					inputValue: '如:-50',
				}).then(({
					value
				}) => {
					this.$message({
						type: 'success',
						message: '修改为: ' + value
					});
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '取消修改'
					});
				});
			}

		},

	};
</script>

<style lang="less">
	.order {
		line-height: 30px;
		// text-align: left;
		margin-top: 20px;
	}

	.order-back {
		margin-bottom: 10px;
	}

	.list-head {
		padding-bottom: 10px;
		display: flex;
	}

	.liat-head-02 {
		font-size: 14px;
		padding-left: 10px;
	}

	.H20 {
		height: 20px;
		line-height: 20px;
	}

	.el-pagination {
		text-align: center;
		margin: auto;
	}
</style>
