<template>
	<view class="bankcard">
		<view class="card">
			<view class="card_l"><img :src="require('@/imgs/icbc2.png')" /></view>
			<view class="card_r">
				<view class="card_r_1">中国工商银行</view>
				<view class="card_r_2">王某某</view>
				<view class="card_r_3">1234123412341234</view>
			</view>
		</view>
		<view class="card_two">
			<view class="card_l"><img :src="require('@/imgs/zsyh.png')" /></view>
			<view class="card_r">
				<view class="card_r_1">中国招商银行</view>
				<view class="card_r_2">张某某</view>
				<view class="card_r_3">1234123412341234</view>
			</view>
		</view>
		<navigator url="/pages/user/fenxiao/addcard/addcard"><view class="btn">添加银行卡</view></navigator>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		onPullDownRefresh() {
			//this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.bankcard{
	.card{background-color: #45AAF0;margin: 20px 20px 30px;border-radius: 10px;padding: 20px 10px;color: #fff;display: flex;
	box-shadow: 0px 5px 10px #A5CBEA;}
	.card_l{width: 20%;text-align: center;}
	.card_l img{width: 45px;height: 45px;}
	.card_r{
		width: 80%;
		.card_r_1{font-size: 16px;padding-bottom: 10px;}
		.card_r_2{font-size: 14px;padding-bottom: 20px;}
		.card_r_3{font-size: 14px;}
	}
	.card_two{background-color: #FF5661;margin: 20px;border-radius: 10px;padding: 20px 10px;color: #fff;display: flex;
	box-shadow: 0px 5px 10px #F0B7B9;}
	.btn{background-color: #DE411C;color: #fff;margin: 10px 20px;height: 40px;line-height: 40px;text-align: center;
	border-radius: 20px;position: fixed;left: 0;bottom: 20px;z-index: 99;width: 90%;}
}
</style>
