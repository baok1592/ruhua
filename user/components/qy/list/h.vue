<template>
	<view class="h"> 
	    <view class="pro" @click="jump_pro()">
		  <view class='pro_01'><img src='../../../imgs/3.jpg'></img></view>
		  <view class='pro_02'>123123123</view>
		  <view class='pro_03'>这里填写的是描述</view>
		  <view class='pro_04'><span>自营</span><span>特卖</span></view>
		  <view class='pro_05'>
			<view class='pro_05_l'>¥<span>1199</span><text>¥1399</text></view>
			<view class='pro_05_r'>立即抢购</view>
		  </view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		props:{
			name:String
		},
		components: {
		},
		methods:{
			jump_pro(){
				uni.navigateTo({
					url:'/pages/product/product'
				})
			}
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.h{
	.pro{margin: 10px;background-color: #fff;border-radius: 8px;}
	.pro_01 img{width: 100%;border-top-left-radius: 8px;border-top-right-radius: 8px;height: 150px;}
	.pro_02{padding: 8px 10px;font-weight: bold;font-size: 16px;}
	.pro_03{padding: 0 10px;color: #848484;font-size: 12px;}
	.pro_04{padding: 10px;color: #E83468;font-size: 12px;font-weight: bold;}
	.pro_04 span{border: 1px solid #E9E8EA;border-radius: 5px;padding: 2px 3px;margin-right: 5px;}
	.pro_05{padding: 5px 10px 10px;display: flex;justify-content: space-between;}
	.pro_05_l{color: #E83468;}
	.pro_05_l span{font-size: 20px;}
	.pro_05_l text{color: #999999;text-decoration: line-through;}
	.pro_05_r{background-color: #F00D3A;padding: 6px 15px;border-radius: 20px;color: #FBF0F3;font-size: 14px;}
}
</style>
