<template>
	<view class="index">
		<view class="head">
			<view class="tit">{{shop.shop_name}}</view>
			<view class="jiu">
				<view class="jiu_01" @click="shao()">
					<view><img src="@/imgs/1.png" /></view>
					<view>扫码验证</view>
				</view>
				<view class="jiu_01">
					<navigator url="/pages/cms/edit/yanzheng/yanzheng">
						<view><img src="@/imgs/2.png" /></view>
						<view>输码验证</view>
					</navigator>
				</view>
				<!-- <view class="jiu_01 ">
					<navigator url="/pages/cms/user/mingxi/mingxi">
						<view><img src="@/imgs/3.png" /></view>
						<view>财务结算</view>
					</navigator>
				</view> -->
			</view>
		</view>
		<view class="more">
			<view class='mo'>
				<view class='mo_01'>数据交易</view>
				<navigator url="/pages/cms/user/tongji/tongji">
					<view class='mo_02'>更多 <uni-icon type="arrowright" size="15" color="#D6D6D6"></uni-icon>
					</view>
				</navigator>
			</view>
		</view>
		<view class="shuju">
			<view class="shuju_01">
				<view>今日订单/金额</view>
				<view class="shuju_01_s" v-if="order_data">{{order_data.today_order}} / ¥{{order_data.today_money}}</view>
			</view>
			<view class="shuju_01 no">
				<view>昨日订单/金额</view>
				<view class="shuju_01_s" v-if="order_data">{{order_data.yesterday_order}} /
					{{order_data.yesterday_money}}</view>
			</view>

			<view class="shuju_01">
				<view>总订单数</view>
				<view class="shuju_01_s" v-if="order_data">{{order_data.all_order}}</view>
			</view>
			<view class="shuju_01 no">
				<view>总销售金额</view>
				<view class="shuju_01_s" v-if="order_data">¥ {{order_data.all_money}}</view>
			</view>
		</view>
		<view class="BH"></view>
		<view class="six">
			<!-- <view class="jiu_01">
				<navigator url="/pages/cms/edit/product/product">
					<view><img src="@/imgs/6.png" /></view>
					<view>添加商品</view>
				</navigator>
			</view> -->
			<view class="jiu_01">
				<navigator url="/pages/cms/edit/pro_manage/pro_manage">
					<view><img src="@/imgs/6.png" /></view>
					<view>商品管理</view>
				</navigator>
			</view>
			<view class="jiu_01" @click="jump_order">

				<view><img src="@/imgs/8.png" /></view>
				<view>订单管理</view>

			</view>
			<!-- <view class="jiu_01">
				<navigator url="/pages/cms/edit/shop/shop">
					<view><img src="@/imgs/5.png" /></view>
					<view>门店编辑</view>
				</navigator>
			</view>
			<view class="jiu_01">
				<navigator url="/pages/cms/user/tongji/tongji"> 
					<view><img src="@/imgs/date.png" /></view>
					<view>经营数据</view>
				</navigator>
			</view> -->
			<!-- <view class="jiu_01 ">
				<navigator url="/pages/cms/notice/notice">
					<view><img src="@/imgs/4.png" /></view>
					<view>通知</view>
				</navigator>
			</view> -->
			<!-- <view class="jiu_01">
				<navigator url="/pages/cms/couponlist/couponlist">
					<view><img src="@/imgs/yhq.png" /></view>
					<view>优惠券</view>
				</navigator>
			</view>
			 <view class="jiu_01 ">
				<navigator url="/pages/cms/kehu/kehu">
					<view><img src="@/imgs/people.png" /></view>
					<view>客户管理</view>
				</navigator>
			</view>  -->
			<!-- <view class="jiu_01"> 
					<view><img src="@/imgs/7.png" /></view>
					<view>商家注册</view> 
			</view> -->
		</view>
		<view class="BH"></view>
		 
		<view class="H50">
		</view>
		<!-- 弹窗 -->
		<view v-if="tancl">
			<Tan @close_add="tan"></Tan>
		</view>
	</view>
</template>

<script>
	import uniBadge from "@/components/uni/uni-badge/uni-badge.vue"
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import Tan from "@/components/qy/Tan.vue"
	export default {
		data() {
			return {
				tancl: false,
				shop: {},
				order_data: '',
			}
		},
		components: {
			uniIcon,
			uniBadge,
			Tan
		},
		onLoad() {
			this._load()
		},
		methods: {
			jump_order() {
				uni.navigateTo({
					url: '../order/order?state=0'
				})
			},
			_load() {
				this.$api.http.get('/order/mcms/get_order_num').then(res => {
					this.order_data = res.data
				})
			},
			//弹窗
			tan() {
				this.tancl = !this.tancl
			},
			//扫码
			shao() {
				uni.scanCode({
					onlyFromCamera: true,
					success: function(res) {
						uni.navigateTo({
							url: 'pages/edit/yanzheng/yanzheng?code=' + res.result
						})
					}
				});
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>


<style lang="scss">
	.index {
		background: #fff;
		font-size: 14px;

		.H50 {
			height: 50px;
		}

		.head {
			background: linear-gradient(to bottom, #52c693, #27a26c);
			text-align: center;
			padding: 20px 0 10px;
			color: #fff;
			font-size: 14px;
		}

		.jiu {
			padding: 10px;
			display: flex;
			flex-wrap: wrap;
			color: #fff;
		}

		.jiu_01 {
			width: 50%;
			text-align: center;
			padding: 15px 10px 15px;
			box-sizing: border-box;
			font-size: 12px;
		}

		.jiu_01 img {
			width: 32px;
			height: 32px;
			margin-bottom: 10px;
		}

		.mo {
			padding: 10px;
			display: flex;
			justify-content: space-between;
			border-bottom: 1px solid #f0f0f0;
			line-height: 20px;
		}

		.mo_01 {
			padding-left: 10px;
			box-sizing: border-box;
			width: 80%;
			height: 20px;
			overflow: hidden;
			border-left: 3px solid #EC521B;
			font-size: 14px;
			font-weight: bold;
		}

		.mo_01 span {
			color: #8E8E8E;
			font-weight: 100;
			font-size: 12px;
			padding-left: 10px;
		}

		.mo_02 {
			color: #D6D6D6;
		}

		.shuju {
			display: flex;
			flex-wrap: wrap;
		}

		.shuju_01 {
			width: 50%;
			text-align: center;
			padding: 18px 10px;
			box-sizing: border-box;
			font-size: 14px;
			line-height: 25px;
			font-size: 14px;
			border-right: 1px solid #f0f0f0;
			border-bottom: 1px solid #f0f0f0;
		}

		.shuju_01_s {
			color: #EB511B;
			font-weight: 800;
			font-size: 16px;
		}

		.no {
			border-right: none;
		}

		.BH {
			background-color: #F1F1F1;
			height: 5px;
		}

		.six {
			padding: 10px;
			display: flex;
			flex-wrap: wrap;
		}

		.list_01 {
			padding: 15px 10px 8px;
			border-bottom: 1px solid #F4F4F4;
			font-size: 12px;
		}
	}
</style>
