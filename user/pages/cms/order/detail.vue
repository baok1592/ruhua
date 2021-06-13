<template>
	<view class="detail">
		<view class="tit">订单编号：{{order.order_num}}</view>
		<view class='tag-e'>
			<view class="goods " >
				<view><img :src='order.goods_picture'></img></view>
				<view class='goods_02'>
				  <view class='goods_title'>{{order.message}}</view>
				  <view class='good_p'>
					<view class="good_price">¥{{order.goods_money}}</view>
					<view class='i'>x {{order.num}}</view>
				  </view>
				</view>
			</view>
		</view>
		<view class="list">
			<view class="list_01">
				<view class="list_01_l">下单时间</view>
				<view class="list_01_r">{{order.pay_time}}</view>
			</view>
			<!-- <view class="list_01">
				<view class="list_01_l">运费</view>
				<view class="list_01_r">免运费</view>
			</view> -->
			<view class="list_01">
				<view class="list_01_l">总金额</view>
				<view class="list_01_r">{{order.order_money}}</view>
			</view>
			<view class="list_01">
				<view class="list_01_l">收货地址</view>
				<view class="list_01_r">{{order.receiver_address}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id:'',
				order:''
			}
			
		},
		onLoad(options) {
			this.id = options.id
			this.get_order()
		},
		methods:{
			get_order(){
				this.$api.http.post('shop/get_one_order',{id:this.id}).then(res=>{
					this.order = res
				})
			}
		},
		onPullDownRefresh() {
			this.get_order()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
		
	}
</script>

<style lang="less">
	.detail{
		.tit{padding: 15px 10px;border-bottom: 1px solid #EEF0EF;font-size: 16px;}
		.tag-e{
			.goods{display: flex;width: 100%;background-color: #ffffff;padding: 10px;box-sizing: border-box;background-color: #FAFAFA;border-bottom: 1px solid #EEF0EF;}
			.goods img{width: 160rpx; height: 160rpx;}
			.goods_02{width: 80%;box-sizing: border-box;display: flex;flex-direction: column;height: 160rpx;justify-content: space-between;padding-top: 10rpx;padding-left: 20rpx;}
			.goods_title{height: 60px;overflow: hidden;line-height: 20px;font-weight: 600;}
			.good_p{display: flex;justify-content: space-between;
				.good_price{color: #F04E42;font-weight: 600;}
			}
		} 
		.list{padding: 0 10px; font-size: 15px;
			.list_01{border-bottom: 1px solid #EEF0EF;display: flex;justify-content: space-between;padding: 15px 3px;
				.list_01_r{color: #959796;}
			}
		}
			
	}
</style>
