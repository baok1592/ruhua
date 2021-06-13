<template>
	<view class="order_detail">
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
			<view class="list_01">
				<view class="list_01_l">总金额</view>
				<view class="list_01_r">{{order.order_money}}元</view>
			</view>
		</view>
		<view class="btn">
			<view class="btn_01" v-if="order.pay_status==0" @click="pay">待付款</view>
			<view class="btn_01" v-if="order.pay_status==1 && order.drive_status==0 && order.tui_status==0" @click="jump_tui(order.order_money)">退款</view>
			<navigator url="../drive/drive">
				<view class="btn_01" v-if="order.pay_status==1 && order.drive_status==1">物流</view>
			</navigator>
			<navigator url="../evaluate/evaluate">
				<view class="btn_01" v-if="order.pay_status==1 && order.drive_status==1">评价</view>
			</navigator>
		</view>
		<view class="ewm" v-if="order.pay_status==1 && order.drive_status==1 && order.qrcode">
			<img :src="order.qrcode"></img>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				id:0,
				order:{}
			}
		},
		onLoad(options) {
			this.id=options.id
			console.log(this.id)
			if(!this.id){
				return;
			}
			this._load()
		},
		methods:{
			_load(){
				const id=this.id
				this.$api.http.post('order/get_order_one',{id}).then(res=>{
					this.order=res
					console.log(res)
				}) 
			},
			jump_tui(e){
				uni.navigateTo({
					url: '../tui/tui?order_money='+e+"&id="+this.id
				});
			},
			//支付
			pay(){
				const that=this
				let id=this.order.order_id
				that.$api.http.post('pay/pre_order',{id}).then(res=>{ 
					this.wxPay(res);
				})
			},
			wxPay(json) {
			  uni.requestPayment({
			      provider: 'wxpay',
			      timeStamp: json.timeStamp,
			      nonceStr: json.nonceStr,
			      package: json.package,
			      signType: json.signType,
			      paySign:json.paySign, 
			      success: function (res) {
			          console.log('success:' + JSON.stringify(res));
			      },
			      fail: function (err) {
			          console.log('fail:' + JSON.stringify(err));
			      }
			  });
			},
			
		}
	}
</script>

<style lang="less">
	.order_detail{
		.tit{padding: 15px 10px;border-bottom: 1px solid #EEF0EF;font-size: 16px;}
		.tag-e{
			.goods{display: flex;font-size: 14px;width: 100%;background-color: #ffffff;padding: 10px;box-sizing: border-box;background-color: #FAFAFA;border-bottom: 1px solid #EEF0EF;
				img{width: 160rpx; height: 160rpx;}
				.goods_02{width: 80%;box-sizing: border-box;display: flex;flex-direction: column;height: 160rpx;justify-content: space-between;padding-top: 10rpx;padding-left: 20rpx;}
				.goods_title{height: 60px;overflow: hidden;line-height: 20px;font-weight: 600;font-size: 15px;}
				.good_p{display: flex;justify-content: space-between;
					.good_price{color: #F04E42;font-weight: 600;}
			}
			
			}
		} 
		.list{padding: 0 10px; font-size: 15px;
			.list_01{border-bottom: 1px solid #EEF0EF;display: flex;justify-content: space-between;padding: 15px 3px;
				.list_01_r{color: #959796;}
			}
		}
		.btn{display: flex;justify-content: flex-end;padding-top:20px;
			.btn_01{color: #fff;font-size: 14px;padding: 5px 10px;border-radius: 3px;background-color: #07C160;margin: 10px 10px 0 0;}
		}
		.ewm{padding: 50px 0 0;text-align: center;
			img{width: 150px;height: 150px;}
		}
	}
</style>
