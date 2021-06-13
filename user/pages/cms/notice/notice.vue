<template>
	<view class="notice">
		<view class="head">
			<view class="head_l">通知公告</view> 
		</view>
	  <block v-for="(item,index) of list" :key="index">
		<view class="list">
			<!-- <view class="list_01">张经理</view> -->
			<view class="list_02">
				<view class="list_l">标题标题标题</view>
				<!-- <view class="list_r">-50</view> -->
			</view>
			<view class="list_03">
				<view class="list_l">2019-07-28</view>
				<!-- <view class="list_r">已完成</view> -->
			</view>
		</view>
	  </block>	
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[]
			};
		},
		onPullDownRefresh() {
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.notice{
	.head{display: flex;background-color: #E2481D;justify-content: space-between;padding: 80px 15px 40px 20px;}
	.head_l{color: #fff;font-size: 28px;}
	.head_r{background-color: #fff;color: #E2481D;height: 30px;line-height: 30px;width: 100px;border-radius: 20px;text-align: center;font-size: 16px;}
	.list{margin: 10px;border-bottom: 1px solid #F2F2F2;padding: 0 10px 10px 0;}
	.list_01{color: #C1C1C1;padding-bottom: 10px;}
	.list_02{display: flex;justify-content: space-between;font-size: 14px;padding-bottom: 10px;}
	.list_03{color: #C1C1C1;display: flex;justify-content: space-between;}
}
</style>
