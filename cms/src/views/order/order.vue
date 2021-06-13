<template>
	<div class="order">
		<el-container>
			<a href="#" name="top"></a>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<el-row>
							<el-col style="display: flex;justify-content: flex-start;margin-bottom: 10px;" >
								<el-button plain :autofocus="show_default" @click="reset">1000条数据</el-button>
								<el-button plain>所有数据</el-button>
								<el-button plain @click="get_excel">导出</el-button>
								<el-button plain @click="reset">刷新</el-button>
							</el-col>
						</el-row>
							
						<el-collapse v-model="activeNames" style="padding:25px;background-color: #fff;margin-bottom:15px;" v-if="!addShow">
							<el-collapse-item title="订单搜索" name="1">
								<div class="search">
									<div class="sea_02">
										<div class="sea_02_01">
											<div class="sea_02_01_l">创建时间：</div>
											<div class="sea_02_01_r">
												<el-button type="primary" plain @click="reset">全部</el-button>
												<template v-for="item of time">
													<el-button type="primary" plain size="mini" @click="choose_time(item.value)">{{item.ti}}</el-button>
												</template>
											</div>
										</div>
										<div class="sea_02_01">
											<div class="sea_02_01_l">搜索：</div>
											<div class="sea_02_02_r">
												<div class="sea_02_02_r_1">
													<el-input placeholder="请输入用户名、商品名或订单编号" v-model="input" style="" clearable></el-input>
													<el-button size="small" style="margin-left: 10px;" type="success" @click="search(input)">搜索</el-button>
												</div>
											</div>
										</div>
									</div>

								</div>
								<el-row :gutter="20" v-if="!addShow">
									<template v-for="(item,index) of message">
										<el-col :span="6">
											<div class="tishi" style="border: 1px solid #DADADA;border-radius: 5px;">
												<div class="ts_01">
													<div class="ts_01_l">{{item.types}}</div>
													<div class="ts_01_m">{{item.wen}}</div>
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
							</el-collapse-item>
						</el-collapse>
						<!-- 订单列表 -->
						<div v-if="!addShow" style="padding: 15px;background-color: #fff">
							<el-table :data="list" border style="width: 100%" @filter-change="xxx">
								<el-table-column type="index" label="序号" width="50"></el-table-column>
								<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
								<el-table-column label="商品名称" width="280">
									<template slot-scope="scope">
										<div v-for="item,key of scope.row.ordergoods">{{item.goods_name | ellipsis}}</div>
									</template>
								</el-table-column>
								<el-table-column label="订单价格" width="100">
									<template slot-scope="scope">
										<template v-if="scope.row.payment_state == 1">{{scope.row.order_money}}</template>
										<el-button v-else type="text" @click="open(scope.row.order_money,scope.row.order_id)">{{scope.row.order_money}}</el-button>
									</template>
								</el-table-column>
								<el-table-column prop="message" label="客户备注" width="160">
								</el-table-column>
								<el-table-column prop="users.nickname" label="用户" width="160"></el-table-column>
								<el-table-column prop="create_time" label="创建日期" width="180"></el-table-column>
								<el-table-column label="支付状态" width="100" :filters="[{ text: '已支付', value: 1 }, { text: '未支付', value: 0 }]"
								 :filter-method="filter_pay" filter-placement="bottom-end" column-key="zf">
									<template slot-scope="scope">
										<p v-if="scope.row.payment_state == 1">已支付</p>
										<p v-else style="color:Red;">未支付</p>
									</template>
								</el-table-column>
								<!-- <el-table-column label="退货状态" width="100" :filters="[{ text: '已退货', value: 1 }, { text: '未退货', value: 0 }]" :filter-method="filter_tui">
									<template slot-scope="scope">
										<p v-if="scope.row.tui_status == 1">已退货</p>
										<p v-else style="color:Red;">未退货</p>
									</template>
								</el-table-column> -->
								<el-table-column label="订单状态" width="100" :filters="[{ text: '已退款', value: -2 }, { text: '退款中', value: -1 },{ text: '未完成', value: 0 },{ text: '已完成', value: 1 },{ text: '已评价', value: 2 },]"
								 filter-placement="bottom-end" :column-key="'dd'">
									<template slot-scope="scope">
										<p v-if="scope.row.state == -2">已退款</p>
										<p v-if="scope.row.state == -1">退款中</p>
										<p v-if="scope.row.state == 0">未完成</p>
										<p v-if="scope.row.state == 1">已完成</p>
										<p v-if="scope.row.state == 2">已评价</p>
										<!-- <p v-else style="color:#E6A23C;">未收货</p> -->
									</template>
								</el-table-column>

								<el-table-column label="运输状态" width="100" :filters="[{ text: '待发货', value: 0 }, { text: '已发货', value: 1 },{ text: '已收货', value: 2 }]"
								 :filter-method="filter_send" filter-placement="bottom-end">
									<template slot-scope="scope">
										<p style="color:#E6A23C" v-if="scope.row.shipment_state == 0">待发货</p>
										<p style="color:#909399" v-if="scope.row.shipment_state == 1">已发货</p>
										<p style="color:#909399" v-if="scope.row.shipment_state == 2">已收货</p>
										<!-- <p style="color:#67C23A" v-else-if="scope.row.drive_status == 1 && scope.row.receive_status == 0">已发货</p>
										<p style="color:#E6A23C" v-else>未发货</p> -->
									</template>
								</el-table-column>
								<el-table-column prop="operation" label="操作" width="160">
									<template slot-scope="scope">
										<el-button @click="show_order(scope.row.order_id)" type="primary" size="small">
											<a href="#top" style="text-decoration:none;color: #FFFFFF;">查看</a>
										</el-button>

										<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.order_id,scope.$index)">删除</el-button>
									</template>
								</el-table-column>
							</el-table>
							<el-pagination v-if="fy_show == 1" style="margin-top: 50px;" background layout="prev, pager, next" :total="total"
							 :page-size="size" @current-change="jump_page">
							</el-pagination>
						</div>

						<div v-if="addShow" style="padding: 15px;background-color: #fff">
							<div class="order-back" style="text-align: left;">
								<el-button type="primary" size="small" @click="back">返回</el-button>
							</div>
							<order-details :order_id="this.order_id"></order-details>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>
		<el-dialog title="修改" :visible.sync="ed" width="35%" :before-close="handleClose">
			<span style="display: flex;justify-content: flex-start;">原价{{yj}},增减金额：</span>
			<el-input v-model="ed_money" placeholder="例:+100或者-100" style=""></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_ed_order">确 定</el-button>
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
	import Order from './new_order.vue'
	import OrderDetails from "./Details.vue";
	export default {
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
				show_default:true,
				fy_show: 1,
				eid: '',
				ed_money: '',
				yj: '',
				ed: false,
				activeNames: '',
				input: '',
				type: [{
						"names": "普通订单",
						"num": 879,
						"state": 0
					}, {
						"names": "拼团订单",
						"num": 879,
						"state": 0
					}, {
						"names": "秒杀订单",
						"num": 879,
						"state": 0
					},
					{
						"names": "砍价订单",
						"num": 879,
						"state": 0
					},
				],
				time: [{
					"ti": "昨天",
					value: '0'
				}, {
					"ti": "今天",
					value: '1'
				}, {
					"ti": "本周",
					value: '2'
				}, {
					"ti": "本月",
					value: '3'
				}, {
					"ti": "本季度",
					value: '4'
				}, {
					"ti": "今年",
					value: '5'
				}],
				state: [],
				message: [{
						"types": '订单',
						"num": 0,
						"mess": "待发货",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '订单',
						"num": 0,
						"mess": "退换货",
						"state": 0,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '商品',
						"num": 0,
						"mess": "库存预警",
						"state": 1,
						"wen": "急",
						"duibi": 0,
						"fudu": 1
					},
					{
						"types": '待提现',
						"num": 0,
						"mess": "待提现",
						"state": 1,
						"wen": "待",
						"duibi": 0,
						"fudu": 0
					},
					{
						"types": '订单',
						"num": 2,
						"mess": "昨日订单数",
						"state": 0,
						"wen": "昨",
						"duibi": 1,
						"fudu": 1
					},
					{
						"types": '交易',
						"num": 2,
						"mess": "昨日订单数",
						"state": 0,
						"wen": "昨",
						"duibi": 1,
						"fudu": 0
					},
					{
						"types": '粉丝',
						"num": 20,
						"mess": "今日新增粉丝",
						"state": 0,
						"wen": "今",
						"duibi": 1,
						"fudu": 1
					},
					{
						"types": '粉丝',
						"num": 27,
						"mess": "本月新增粉丝",
						"state": 0,
						"wen": "月",
						"duibi": 1,
						"fudu": 0
					}
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
				total: 0,
				options: [],
				value: '',
				typeList: [],
				upfile_banner_list: [],
				upfile_url: Api_url + 'com/up_img?back=id',
				upfile_head: {
					token: localStorage.getItem("token")
				},
				upfile_list: [], //上传文件列表
				search_key: '',
				reset_key: '',
				week_start: '',
				week_end: '',
				month_start: '',
				month_end: '',
				now_year_firstDay: '',
				now_year_lastDay: '',
				Quarter_start: '',
				Quarter_end: '',
				yestoday_start: '',
				yestoday_end: '',
				range_start: '',
				range_end: '',
				quanbu: '',
				addShow: false,
			}
		},
		components: {
			Header,
			NavTo,
			Order,
			OrderDetails
		},
		mounted() {
			this.get_week();
			this.get_month()
			this.get_year()
			this.get_Quarter()
			this.getDate()
			this.get_today()
			this.get_all_order()
			this.show_default = true
		},
		methods: {
			xxx(filters) {
				console.log(filters)
			},
			open(order_money, id) {
				this.ed = true
				this.yj = order_money
				this.eid = id
			},
			handleClose() {
				this.ed = false
			},
			sub_ed_order() {
				const that = this
				this.http.post('order/admin/edit_price', {
					price: this.ed_money,
					order_id: this.eid
				}).then(res => {
					that.$message({
						showClose: true,
						message: '修改成功',
						type: 'success'
					});
					this.ed = false
					this.ed_money = ''
					this.get_all_order()
				})
			},
			cancel() {
				this.ed = false
				this.ed_money = ''
			},
			//导出数据
			get_excel() {
				const aLink = document.createElement('a');
				let token = localStorage.getItem('token');
				aLink.href = Api_url + 'index/export_excl?token=' + token
				aLink.target = '_blank'
				aLink.download = 'ly_2019.csv';
				aLink.click();
				aLink.remove();
			},
			back() {
				this.order_id = 0;
				this.addShow = false;
			},

			show_order(row) {
				this.addShow = true; // addshow为要显示的页面
				this.order_id = row;
			},
			get_all_order() {
				const that = this
				this.http.post('order/admin/get_order').then(res => {
					that.all = res.data
					that.list = res.data;
					that.list = res.data.slice(0, that.size);
					that.total = res.data.length;
					for (let v in that.all) {
						let time = that.all[v].pay_time * 1000
						this.all[v].pay_time = this.Conversiontime(time)
					}
				})
			},
			Conversiontime(timestamp) {
				var date = new Date(timestamp);
				let Y = date.getFullYear() + '-';
				let M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
				let D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
				let h = (date.getHours() < 10 ? '0' + (date.getHours()) : date.getHours()) + ':';
				let m = (date.getMinutes() < 10 ? '0' + (date.getMinutes()) : date.getMinutes()) + ':';
				let s = (date.getSeconds() < 10 ? '0' + (date.getSeconds()) : date.getSeconds());
				return Y + M + D + h + m + s;
			},
			filter_pay(value, row) {
				console.log(value)
				return row.payment_state === value;
			},
			filter_tui(value, row) {
				return row.tui_status === value;
			},
			filter_send(value, row) {
				return row.shipment_state === value;
			},
			filterTag(value, row) {
				return row.state === value;
			},

			jump_to_range() {
				// this.$router.push({
				// 	path: '/components/time_range/time_range'
				// })
			},

			choose_time(value) {
				const that = this
				this.reset_key = 0
				let arr = []

				if (value == 0) {
					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.pay_time).getTime()
						console.log(T)
						console.log(this.yestoday_start)
						console.log(this.yestoday_end)
						if (this.yestoday_start < T && T < this.yestoday_end) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return
				}
				if (value == 1) {

					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.create_time).getTime()
						if (this.day_start < T && T < this.day_end) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return
				}
				if (value == 2) {
					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.create_time).getTime()

						if (this.week_start < T && T < this.week_end) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return
				}
				if (value == 3) {

					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.create_time).getTime()
						if (this.month_start < T && T < this.month_end) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return
				}
				if (value == 4) {

					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.create_time).getTime()
						if (this.Quarter_start < T && T < this.Quarter_end) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return
				}
				if (value == 5) {
					for (let k in this.all) {
						let t = this.all[k]
						let T = new Date(t.create_time).getTime()
						if (this.now_year_firstDay < T && T < this.now_year_lastDay) {
							arr.push(t)
						}
					}
					this.list = arr.slice(0, that.size);
					this.total = arr.length
					console.log(arr)
					return

				}

			},

			reset() {
				this.get_all_order()
				this.fy_show = 1
				this.list = this.all.slice(0, this.size)

			},
			search(input) { //订单号  商品名称  用户名
				console.log(input)
				let arr = []
				for (let k in this.all) {
					let v = this.all[k]
					if (v.order_num.indexOf(input) >= 0) {
						arr.push(v)
					} else if (v.users.nickname.indexOf(input) >= 0) {
						arr.push(v)
					} else {
						for (let s of v.ordergoods) {
							if (s.goods_name.indexOf(input) >= 0) {
								arr.push(v)
							}
						}
					}
				}
				// this.all = arr
				this.fy_show = 0
				this.list = arr

				// this.list = arr.slice(0, this.size);
				// this.total = arr.length
				console.log(arr)
				this.input = ''
			},
			handleRemove(file, fileList) {
				console.log(file, fileList);
			},
			handlePictureCardPreview(file) {
				this.dialogImageUrl = file.url;
				this.dialogVisible = true;
			},
			onSubmit() {},
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
			sub_edit() {},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			sub() {},
			//获取商品列表
			//删除商品
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('order/admin/del_order', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_all_order()
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
			get_week() {
				//按周日为一周的最后一天计算  
				var date = new Date();

				//今天是这周的第几天  
				var today = date.getDay();

				//上周日距离今天的天数（负数表示）  
				var stepSunDay = -today + 1;

				// 如果今天是周日  
				if (today == 0) {

					stepSunDay = -7;
				}

				// 周一距离今天的天数（负数表示）  
				var stepMonday = 7 - today;

				var time = date.getTime()
				console.log('当前时间', time);
				var monday = new Date(time + stepSunDay * 24 * 3600 * 1000);
				var sunday = new Date(time + stepMonday * 24 * 3600 * 1000);

				//本周一的日期 （起始日期）  
				var startDate = monday.getTime()

				//本周日的日期 （结束日期）  
				var endDate = sunday.getTime() // 日期变换  
				this.week_start = startDate
				this.week_end = endDate
				console.log(this.week_start);
				console.log(this.week_end);
				// console.log(startDate + ' - ' + endDate)
				// return startDate + ' - ' + endDate;
			},
			get_month() {
				// 获取当前月的第一天  
				var start = new Date();
				start.setDate(1);

				// 获取当前月的最后一天  
				var date = new Date();
				var currentMonth = date.getMonth();
				console.log(currentMonth)
				var nextMonth = ++currentMonth;
				var nextMonthFirstDay = new Date(date.getFullYear(), nextMonth, 1);
				var oneDay = 1000 * 60 * 60 * 24;
				var end = new Date(nextMonthFirstDay - oneDay);

				// var startDate = transferDate(start); // 日期变换  
				var startDate = start.getTime() // 日期变换  
				// var endDate = transferDate(end); // 日期变换  
				var endDate = end.getTime() // 日期变换  
				this.month_start = startDate
				this.month_end = endDate
				console.log(startDate + ' - ' + endDate)
				// return startDate + ' - ' + endDate;  
			},
			get_year() { //获取当前年开始时间-结束时间
				var now = new Date()
				var now_year = now.getFullYear()
				var now_year_firstDay = new Date(now_year, 0, 1).getTime()
				var now_year_lastDay = new Date(now_year, 11, 31).getTime()
				this.now_year_firstDay = now_year_firstDay
				this.now_year_lastDay = now_year_lastDay
			},
			get_Quarter_date() { //获得本季度的开始月份
				var now = new Date()
				var nowMonth = now.getMonth();
				var quarterStartMonth = 0;
				if (nowMonth < 3) {
					quarterStartMonth = 0;
				}
				if (2 < nowMonth && nowMonth < 6) {
					quarterStartMonth = 3;
				}
				if (5 < nowMonth && nowMonth < 9) {
					quarterStartMonth = 6;
				}
				if (nowMonth > 8) {
					quarterStartMonth = 9;
				}
				console.log('季度开始月：', quarterStartMonth)
				return quarterStartMonth;
			},
			get_Quarter() { //获取当前季度
				var now = new Date()
				var nowYear = now.getYear()
				nowYear += (nowYear < 2000) ? 1900 : 0;
				var Quarter_start = new Date(nowYear, this.get_Quarter_date(), 1).getTime()
				var Quarter_end_month = this.get_Quarter_date() + 2
				console.log('季度结束月：', Quarter_end_month)
				var Quarter_end = new Date(nowYear, Quarter_end_month, this.get_month_days(Quarter_end_month)).getTime()
				this.Quarter_start = Quarter_start
				this.Quarter_end = Quarter_end
			},
			get_month_days(myMonth) { //获取当前月天数
				var now = new Date()
				var nowYear = now.getYear();
				var monthStartDate = new Date(nowYear, myMonth, 1);
				var monthEndDate = new Date(nowYear, myMonth + 1, 1);
				var days = (monthEndDate - monthStartDate) / (1000 * 60 * 60 * 24);
				return days;
			},
			getDate() {
				var dd = new Date();
				var n = -1
				dd.setDate(dd.getDate() + n);
				var y = dd.getFullYear();
				var m = dd.getMonth() + 1;
				var d = dd.getDate();
				m = m < 10 ? "0" + m : m;
				d = d < 10 ? "0" + d : d;
				var day = y + "-" + m + "-" + d;
				this.yestoday_start = new Date(day).getTime() - 8 * 60 * 60 //昨天开始时间
				this.yestoday_end = new Date(day).getTime() + 16 * 60 * 60
			},
			get_today() {
				var dd = new Date();
				var n = 0
				dd.setDate(dd.getDate() + n);
				var y = dd.getFullYear();
				var m = dd.getMonth() + 1;
				var d = dd.getDate();
				m = m < 10 ? "0" + m : m;
				d = d < 10 ? "0" + d : d;
				var day = y + "-" + m + "-" + d;
				this.day_start = new Date(day).getTime() - 8 * 60 * 60
				this.day_end = new Date(day).getTime() + 16 * 60 * 60
			},
			formatNumber(n) {
				n = n.toString()
				return n[1] ? n : '0' + n;
			},
			formatTime(number, format) {
				let time = new Date(number)
				let newArr = []
				let formatArr = ['Y', 'M', 'D', 'h', 'm', 's']
				newArr.push(time.getFullYear())
				newArr.push(this.formatNumber(time.getMonth() + 1))
				newArr.push(this.formatNumber(time.getDate()))

				newArr.push(this.formatNumber(time.getHours()))
				newArr.push(this.formatNumber(time.getMinutes()))
				newArr.push(this.formatNumber(time.getSeconds()))

				for (let i in newArr) {
					format = format.replace(formatArr[i], newArr[i])
				}
				return format;
			}
		},



	}
</script>

<style lang="less">
	.order {

		margin-top: 0;

		.list-head {
			padding-bottom: 10px;
			display: flex;
		}

		.liat-head-02 {
			font-size: 14px;
			padding-left: 10px;
		}

		.liat-head-03 {
			margin-left: 20px;
		}

		.H20 {
			height: 20px;
			line-height: 20px;
		}

		.el-pagination {
			text-align: center;
			margin: auto;
		}

		.order {
			line-height: 30px;
			// text-align: left;
		}

		.order-back {
			margin-bottom: 10px;
		}

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

		.el-input__inner {
			height: 30px;
			line-height: 30px;
			width: 600px;
			border: 1px solid #EFEFEF;
		}

		.search {
			line-height: 20px;
			margin-bottom: 15px;
			background-color: #fff;
			// text-align: left;
			color: #6B6B6B;
			font-size: 14px;

			.sea_01 {
				border-bottom: 1px solid #F0F0F0;
				padding: 10px;
			}

			.sea_02 {
				padding: 10px 15px;
				font-size: 12px;

				.sea_02_01 {
					display: flex;
					line-height: 30px;
					margin-bottom: 15px;

					.sea_02_01_l {
						width: 100px;
					}

					.sea_02_01_r {
						display: flex;

						.sea_02_01_r_1 {
							background-color: #008DE1;
							color: #fff;
							padding: 0 10px;
							margin-right: 10px;
							border-radius: 3px;
						}

						.sea_02_01_r_2 {
							border: 1px solid #EFEFEF;
							margin-right: 10px;
							padding: 0 10px;

							.grey {
								background-color: #EEEEEE;
								padding: 3px 6px;
								border-radius: 3px;
							}

							.orange {
								background-color: #FF551D;
								padding: 3px 5px;
								color: #fff;
								border-radius: 3px;
							}
						}
					}

					.sea_02_02_r {
						.sea_02_02_r_1 {
							display: flex;
							margin-bottom: 15px;
							width: 600px;
						}

						.sea_02_02_r_2 {
							display: flex;

							.sea_02_02_r_2_1 {
								background-color: #008DE1;
								color: #fff;
								padding: 0 10px;
								margin-right: 10px;
								border-radius: 3px;
							}

							.sea_02_02_r_2_2 {
								padding: 0 10px;
								margin-right: 10px;
								border-radius: 3px;
								border: 1px solid #EFEFEF;
							}
						}
					}
				}
			}
		}

		.tishi {
			line-height: 20px;
			margin-bottom: 15px;
			background-color: #fff;
			// text-align: left;
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

		.article {
			background-color: #fff;
			padding: 15px;
			line-height: 30px;
			// text-align: left;
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
