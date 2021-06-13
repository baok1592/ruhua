<template>
	<view class="product">
		<view class="top">
		</view>
		<view class="cu-form-group">
			<view class="title">店铺优惠券</view>
		</view>
		<view class="cu-form-group">
			<view class="title">优惠券名：</view>
			<input placeholder="最多10个字"  v-model="coupon_form.name"></input>
		</view>
		<view class="cu-form-group">
			<view class="title">面&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;值：</view>
			<input placeholder="0.01-1000000"  v-model="coupon_form.reduce"></input>
		</view>
		<view class="cu-form-group">
			<view class="title">使用次数：</view>
			<view class="title" >
				<radio-group @change="radioChange" style="display: flex;justify-content: flex-end;" v-model="coupon_form.status">
					<label class="radio" v-for="(item, index) in status" :key="item.value">
						<view class="danxuan" >
							<radio class='margin-left-sm'   :value="item.value" ></radio>
							<!-- <radio :value="item.value"  style="transform:scale(0.7);" /> -->
							<span>{{item.name}}</span>
						</view>
					</label>
				</radio-group>
			</view>
		</view>
		<view class="cu-form-group">
			<view class="title">使用门槛：</view>
			<switch @change="switch1Change" :class="switchA?'checked':''" :checked="switchA?true:false"></switch>
		</view>
		<view class="cu-form-group" v-if="threshold == true">
			<view class="title">发放总量：</view>
			<input placeholder="0.01-1000000"  v-model="coupon_form.full"></input>
		</view>
		<view class="cu-form-group" >
			<view class="title">门槛金额：</view>
			<input placeholder="1000000"  v-model="coupon_form.stock"></input>
		</view>
		<view class="cu-form-group margin-top" @click="show_cate_list=true">
			<view class="title">有效期类型：</view>
			<view class="title" style="padding: 0;">
				{{category_name}}&nbsp;
				<uni-icon type="arrowdown" size="20" color="#A7A7A7"></uni-icon>
			</view>
		</view>
		<view class="cu-form-group" v-if="category_id == 2">
			<view class="title">有效期天数：</view>
			<input placeholder="请输入有效期天数"  v-model="coupon_form.day"></input>
		</view>
		<view class="cu-form-group" >
			<view class="title">开始时间：</view>
			<picker mode="date" :value="date" start="2015-09-01" end="2020-09-01" @change="DateChange">
				<view class="picker">
					{{date}}
				</view>
			</picker>
		</view>
		<view class="cu-form-group" >
			<view class="title">结束时间：</view>
			<picker mode="date" :value="date_end" start="2015-09-01" end="2020-09-01" @change="bindDateChange_end">
				<view class="picker">
					{{date_end}}
				</view>
			</picker>
		</view>
		<view class="cu-form-group" @click="show_mail_list = true">
			<view class="title">使用设置：</view>
			<view class="title" style="padding: 0;">
				{{product_cate}}
				<uni-icon type="arrowdown" size="20" color="#A7A7A7"></uni-icon>
			</view>
		</view>
		
		<view class="H50"></view>
		<view class="p_btn">
			<view class=" flex flex-direction">
				<button class="cu-btn bg-red margin-tb-sm lg" @click="sub()">保存</button>
			</view>
			
		</view>
		<!-- 商品类型弹窗 -->
		<uni-popup :show="show_cate_list" type="top" mode="fixed" @hidePopup="show_cate_list=false">
			<view class="uni-list leixin">
				<radio-group @change="cate_change">
					<label class="radio" v-for="(item, index) in category_list" :key="index">
						<view class="xuan">
							<radio :value="index+''" />
							<span style="padding-left: 5px;">{{item.category_name}}</span>
						</view>
					</label>
				</radio-group>
			</view>
		</uni-popup>


		<!-- 配送方式弹窗 -->
		<uni-popup :show="show_mail_list" type="bottom" mode="fixed" @hidePopup="show_mail_list=false">
			<view class="uni-list leixin">
				<radio-group @change="mail_change">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in mail_list" :key="index">
						<view class="xuan">
							<radio :value="index+''" />
							<span style="padding-left: 5px;">{{item.name}}</span>
						</view>
					</label>
				</radio-group>
			</view>
		</uni-popup>

	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	import robbyImageUpload from '@/components/plan-image-upload/up_img'
	import {
		Api_url
	} from '@/common/config'
	export default {
		data() {
			const currentDate = this.getDate({
				format: true
			})
			const currentDate_end = this.getDate_end({
				format: true
			})
			return {
				status: [{
						value: '0',
						name: '一次'
					},
					{
						value: '1',
						name: '无限制'
					},
				],
				current: 0,
				coupon_form: {
					status: '',
					full: '',
					reduce: '',
					name: '',
					stock: '',
					goods_ids: [],
					start_time: '',
					end_time: '',
					stock_type: '',
					day: ''

				},
				product_cate: '',
				set: 0,
				category_id: 1,
				date: currentDate,
				date_end: currentDate_end,
				time: '12:01',
				threshold: 1,
				sku: [],
				show_cate_list: false,
				show_mail_list: false,
				category_name: '固定时间',
				category_list: [{
						category_id: '1',
						category_name: '固定时间'
					},
					{
						category_id: '2',
						category_name: '领卷当日开始计算有效期'
					}
				],
				id: 0,
				is_edit: false,
				goods: {
					banner_imgs: [],
					goods_name: '',
					category_id: '',
					description: '',
					price: '',
					vip_price: '',
					market_price: '',
					leixin: '1', //类型0快递 1核销
					fx_tc: '', //分销提成
					sales: '',
					stock: '',
					content: '',
					goods_name: ''
				},
				mail: 1,
				mail_list: [{
						id: '1',
						name: '全部商品',
						value:'0'
					},
					{
						id: '2',
						name: '指定商品可用'
					}
				],
				peisong: '',
				group: '未分组',
			};
		},
		components: {
			uniIcon,
			uniPopup,
			robbyImageUpload
		},
		computed: {
			startDate() {
				return this.getDate('start');
			},
			endDate() {
				return this.getDate('end');
			}
		},
		watch: {},
		onLoad(options) {
			this.id = options.id
		},
		onShow() {
			let goods_ids = uni.getStorageSync('pro_id_list')
			console.log(goods_ids)
			this.coupon_form.goods_ids = goods_ids
			console.log(this.coupon_form.goods_ids)
			uni.removeStorageSync('pro_id_list')
		},
		methods: {
			DateChange(e) {
				this.date = e.detail.value
			},
			DateChangeend(e) {
				this.dateend = e.detail.value
			},
			//----------------------------------------------------------使用设置
			setting() {
				this.set = 1
			},
			//-------------------------------------------------------------选择使用次数
			radioChange: function(evt) {
				for (let i = 0; i < this.status.length; i++) {
					if (this.status[i].value === evt.target.value) {
						this.current = i;
						console.log(this.status[i].value)
						this.coupon_form.status = this.status[i].value
						break;
					}
				}
			},
			//-----------------------------------------------------------优惠券开始日期
			getDate(type) {
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let month_end = date.getMonth() + 2;
				let day = date.getDate();

				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year + 2;
				}
				month = month > 9 ? month : '0' + month;;
				day = day > 9 ? day : '0' + day;
				return `${year}-${month}-${day}`;
			},
			//-----------------------------------------------------优惠券结束时间
			getDate_end(type) {
				const date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 2;
				let day = date.getDate();

				if (type === 'start') {
					year = year - 60;
				} else if (type === 'end') {
					year = year + 20;
				}
				month = month > 9 ? month : '0' + month;;
				day = day > 9 ? day : '0' + day;

				return `${year}-${month}-${day}`;
			},
			bindDateChange: function(e) {
				this.date = e.target.value
				var time = Date.parse(this.date)
				this.coupon_form.start_time = time / 1000 //获取时间戳
			},
			bindDateChange_end: function(e) {
				this.date_end = e.target.value
				var time = Date.parse(this.date_end)
				this.coupon_form.end_time = time / 1000
				console.log(this.coupon_form.end_time)
			},
			//-------------------------------------------------改变switch状态
			switch1Change() {
				if (this.threshold == 1) {
					this.threshold = 0
					this.coupon_form.full = 0
				} else {
					this.threshold = 1
				}
			},

			//---------------------------------------------------------------提交
			sub() {
				//this.coupon_form.goods_ids = [0]
				console.log(this.coupon_form)
				if(!this.coupon_form.stock){
					this.coupon_form.stock_type = '0'
				}else{
					this.coupon_form.stock_type = '1'
				}
				if (this.category_id == 2) {
					this.$api.http.post('shop_cms/add_shop_coupon', {
						stock_type: this.coupon_form.stock_type,
						full: this.coupon_form.full,
						name: this.coupon_form.name,
						reduce: this.coupon_form.reduce,
						status: this.coupon_form.status,
						goods_ids: this.coupon_form.goods_ids,
						day: this.coupon_form.day
					}).then(res => {
						this.$api.msg('添加成功')
						uni.redirectTo({
							url:'../couponlist/couponlist'
						})
					})
				} else {
					this.$api.http.post('shop_cms/add_shop_coupon', {
						stock_type: this.coupon_form.stock_type,
						full: this.coupon_form.full,
						name: this.coupon_form.name,
						reduce: this.coupon_form.reduce,
						status: this.coupon_form.status,
						goods_ids: this.coupon_form.goods_ids,
						start_time: this.coupon_form.start_time,
						end_time: this.coupon_form.end_time,
					}).then(res => {
						console.log(this.coupon_form.end_time,)
						this.$api.msg('添加成功')
						uni.redirectTo({
							url:'../couponlist/couponlist'
						})
					})
				}
				//this.coupon_form = {}


			},

			compressImg(e) {
				console.log(e)
			},
			changeIndicatorDots(e) {
				this.isYasuo = !this.isYasuo
			},
			//选择分类
			cate_change(event) {
				const index = event.detail.value
				this.category_id = this.category_list[index].category_id
				this.category_name = this.category_list[index].category_name
				this.show_cate_list = false
			},

			mail_change(event) {
				const index = event.detail.value
				this.product_cate = this.mail_list[index].name
				this.show_mail_list = false
				if (this.mail_list[index].id == 1) {
					this.coupon_form.goods_ids = [0]
				}
				if (this.mail_list[index].id == 2) {
					uni.navigateTo({
						url: '../couponxuan/couponxuan'
					})
				}
			},
			buy(type) {
				this.$api.http.post('user/pay_vip', {
					type
				}).then(res => {
					this.$api.msg('购买成功')
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/user/user'
						})
					}, 1500)
				})
			}
		}
	}
</script>

<style lang="less">
	page {
		background-color: #F7F6FB;font-size: 14px;
	}
	.danxuan{width: 100px;
		span{padding:2px 0 0 10px;}
	}
	.pic {
		padding: 20upx 10upx;
	}

	.input-view {
		font-size: 28upx;
	}

	.leixin {
		// height: 300px;
		width: 50vw;
		overflow: hidden;
		overflow-x: hidden;
		overflow-y: scroll;
	}

	.xuan {
		padding-bottom: 10px;
		padding-top: 10px;
	}

	.close-view {
		text-align: center;
		line-height: 14px;
		height: 16px;
		width: 16px;
		border-radius: 50%;
		background: #FF5053;
		color: #FFFFFF;
		position: absolute;
		top: -6px;
		right: -4px;
		font-size: 12px;
	}

	.product {
		background-color: #F7F6FB;
		height: 100%;

		.head {
			position: relative;
			padding: 0 5px;
		}

		.head img {
			width: 100%;
			height: 150px;
		}

		.btn {
			position: absolute;
			top: 10px;
			right: 5px;
		}

		.btn button {
			font-size: 10px;
			color: red;
			border: none;
			border-radius: 20px;
			background-color: #CBCBCB;
			padding: 5px;
		}

		.top {
			box-shadow: 0 0 8px 5px #EDEDED;
			height: 1px;
			background-color: #EDEDED;
		}

		.pro_tit {
			padding: 15px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.BH {
			height: 5px;
			background-color: #F2F2F2;
		}

		.biao {
			background-color: #fff;
			margin: 10px;
			border-radius: 5px;
			border: 1px solid #EAEAEA;
		}

		.biao span {
			color: red;
			padding-right: 5px;
		}

		.biao textarea {
			width: 100%;
			border-bottom: 1px solid #EDEDED;
			padding: 10px;
			height: 100px;
		}

		.biao_01 {
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_01_l {
			padding-top: 7px;
			flex-shrink: 0;
		}

		.biao_01_1 {
			padding-top: 5px;
		}

		.biao_01_r {
			flex-grow: 1;
		}

		.biao_01_r text {
			padding-right: 10px;
		}

		.biao_02_r select {
			padding: 0 10px;
			line-height: 25px;
			min-width: 80px;
			text-align: center;
		}

		.biao_01_m {
			border-right: 1px solid #EDEDED;
		}

		.biao_02 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
			justify-content: space-between;
		}

		.biao_03 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_04 {
			display: flex;
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.biao_04_l {
			display: flex;
			width: 50%;
			line-height: 30px;
			padding-right: 10px;
		}

		.biao_04_l_i {
			flex-shrink: 1;
		}

		.biao_04_l_1 {
			flex-shrink: 0;
		}

		.biao_05 {
			padding: 10px 10px 0;
		}

		.jgkc {
			padding: 15px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.p_btn {
			background: #F7F6FB;
			padding: 0 10px 0px;
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 9999;
		}

		.pro_btn {
			background-color: #E5E5E5;
			padding: 10px;
			text-align: center;
			border-radius: 20px;
			background-color: #DF421D;
			color: #fff;
			width: 94%;
		}

		.guige {
			border: 1px solid #EDEDED;
			margin: 10px 20px 5px 10px;
			position: relative;
		}

		.stop {
			position: absolute;
			right: -15px;
			top: 60px;
		}

		.stop img {
			width: 30px;
			height: 30px;
		}

		.uni-input-input,
		.uni-input {
			height: 30px;
			line-height: 30px;
		}
	}
</style>
