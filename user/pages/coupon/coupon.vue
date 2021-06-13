<template>
	<view class="coupon">
		<view class='po'>
			<None v-if="list_empty"></None>
			<view v-for="(item,index) of list" :key="index" v-else>
				<!-- <view class='coupon'  v-if="item.coupon_status==state"> -->
				<view class='coupons'>
					<view class='cou_t'>
						<view class='cou_t_l'>
							<view :class='class_name'><span>¥</span>{{Math.floor(item.reduce)}}</view>
							<!-- <view class='cou_t_l_02'>无使用门槛最多优惠12元</view> -->
						</view>
						<view class='cou_t_r'>
							<view class='cou_t_r_01'>满{{Math.floor(item.full)}} 减{{Math.floor(item.reduce)}}</view>
							<block v-if="!item.end_time && item.day">
								<view class='cou_t_r_02'>有效期：领取起{{item.day}}天</view>
							</block>

							<block v-else>
								<view class='cou_t_r_02'>有效期：{{item.end_time}}</view>
							</block>

						</view>
						<view class='cou_t_rr' v-if="item.uesr_status == 1">
							<view class="" @click="get_coupon(item.id)">领取</view>
						</view>
						<view class='cou_t_rr_1' v-if="item.uesr_status == 0">
							<view class="">已领</view>
						</view>
					</view>
					<view class='cou_d' v-if="item.goods_ids == '0'">全平台优惠券</view>
					<view class='cou_d' v-else>部分商品可用</view>
				</view>
			</view>

		</view>
	</view>
</template>

<script>
	import Check from '@/common/check.js'
	import None from "@/components/qy/none.vue"
	import {
		Api_url
	} from '@/common/config.js'
	export default {
		data() {
			return {
				c_index: 0,
				list: [],
				class_name: 'cou_t_l_01',
				list_empty: false,
			};
		},
		components: {
			None
		},
		onLoad(options) {
			this.check_login()
			
		},
		methods: {
			check_login() {
				if(!Check.a()){return}
				this._load()
				
			},
			_load() {
				//获取优惠券
				this.$api.http.get('coupon/get_coupon').then((res) => {
					console.log(res)
					if (res.data == '') {
						this.list_empty = true
					} else {
						this.list = res.data
					}
					uni.stopPullDownRefresh();
				})
			},
			//领取优惠券
			get_coupon(id) {
				this.$api.http.get('coupon/add_coupon', {
					id: id
				}).then(res => {
					this.$api.msg('领取成功')
					this._load();
				})
			},

		},
		onPullDownRefresh() {
			this._load();
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
	.coupon {
		background-color: #F8F8F8;
		min-height: 100vh;

		.po {
			z-index: 99;
			width: 100%;
			padding-top: 15px;
		}

		.tab {
			padding: 10px 10px 0;
			display: flex;
			width: 100%;
			font-size: 14px;
			background-color: #fff;
		}

		.bb {
			padding-bottom: 5px;
			text-align: center;
			width: 50%;
		}

		.xz {
			border-bottom: 2px solid red;
			padding-bottom: 5px;
			width: 50%;
			text-align: center;
		}

		.coupons {
			margin: 15px;
			background-color: #fff;
			border: 1px solid #EEEEEE;
			border-radius: 5px;
			box-shadow: 2px 2px 2px #EEEEEE;
			color: #9FA0A2;
			min-height: 10px;
		}

		.cou_t {
			display: flex;
			padding: 20px 10px 10px;
			justify-content: space-between;
			font-size: 12px;
			width: 100%;
			box-sizing: border-box;
		}

		.cou_t_l {
			width: 20%;
			flex-shrink: 0;
		}

		.cou_t_r {
			width: 55%;
			box-sizing: border-box;
			padding-left: 10px;
		}

		.cou_t_rr {
			width: 70px;
			background-color: #EB113E;
			color: #fff;
			height: 25px;
			line-height: 25px;
			font-size: 12px;
			text-align: center;
			margin: 15px 0 0 10px;
			border-radius: 20px;
		}

		.cou_t_rr_1 {
			width: 70px;
			background-color: #BEBEBE;
			color: #fff;
			height: 25px;
			line-height: 25px;
			font-size: 12px;
			text-align: center;
			margin: 15px 0 0 10px;
			border-radius: 20px;
		}

		.cou_t_l_01 {
			color: #FF4444;
			font-size: 26px;
			padding-top: 8px;
		}

		.cou_t_l_01 span {
			font-size: 12px;
		}

		.cou_sss {
			font-size: 32px;
		}

		.cou_sss span {
			font-size: 12px;
		}

		.cou_t_r_01 {
			font-size: 18px;
			color: #323233;
			padding: 3px 0 5px;
		}

		.cou_d {
			background-color: #FAFAFA;
			padding: 6px 15px;
			border-top: 1px dashed #EBEDF0;
			font-size: 12px;
		}

		.H50 {
			height: 50px;
		}

		.btn {
			position: fixed;
			bottom: 0;
			width: 100%;
			height: 45px;
			line-height: 45px;
			text-align: center;
			border-top: 1px solid #EEEEEE;
			z-index: 999;
			font-size: 14px;
			background-color: #fff;
		}
	}
</style>
