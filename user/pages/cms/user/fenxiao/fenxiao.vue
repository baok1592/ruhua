<template>
	<view class="retail">
		<view class="head">
			<view class="head_01">
				<view class="head_01_l">总收益（元）</view>
				<view class="head_01_r"><uni-icon type="help-filled" size="15" color="#fff"></uni-icon> <span></span> 提现规则</view>
			</view>
			<view class="head_02">
				872
			</view>
			<view class="head_03">
				<view class="head_03_l">可提现金额</view>
				<view class="head_03_r">提现</view>
			</view>
		</view>
		<view class="shu">
			<view class="shu_01">
				<span>12万</span>
				<view>我的用户</view>
			</view>
			<view class="shu_01">
				<span>12万</span>
				<view>我的用户</view>
			</view>
			<view class="shu_01">
				<span>12万</span>
				<view>我的用户</view>
			</view>
			<view class="shu_01">
				<span>12万</span>
				<view>我的用户</view>
			</view>
		</view>
		<view class="BH"></view>
		<div class="list">
			<!-- <navigator url="/pages/Unitmessage/Unitmessage" hover-class="navigator-hover"> -->
			  <uni-list-item title="我的用户" :thumb="require('@/imgs/6.png')" ></uni-list-item>
			<!-- </navigator> -->
			<uni-list-item title="我的代理" :thumb="require('@/imgs/6.png')" ></uni-list-item>
		</div>
		<view class="BH"></view>
		<div class="list">
			<!-- <navigator url="/pages/Unitmessage/Unitmessage" hover-class="navigator-hover"> -->
			  <uni-list-item title="收益明细" :thumb="require('@/imgs/6.png')" ></uni-list-item>
			<!-- </navigator> -->
			<uni-list-item title="提现记录" :thumb="require('@/imgs/6.png')" ></uni-list-item>
		</div>
		<view class="BH"></view>
		<div class="list">
			<!-- <navigator url="/pages/Unitmessage/Unitmessage" hover-class="navigator-hover"> -->
			  <uni-list-item title="银行卡" :thumb="require('@/imgs/6.png')" ></uni-list-item>
			<!-- </navigator> -->
			<uni-list-item title="店铺推广二维码" :thumb="require('@/imgs/6.png')" ></uni-list-item>
		</div>
		<view class="BH"></view>
	</view>
</template>

<script>
	import uniList from '@/components/uni/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni/uni-list-item/uni-list-item.vue'
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				
			};
		},
		components: {uniIcon,uniList,uniListItem},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.retail{
	font-size: 12px;
	.head{background-color: #F66444;padding: 20px;color: #fff;}
	.head_01{display: flex;justify-content: space-between;padding-bottom: 5px;}
	.head_01_r span{padding-left: 5px;}
	.head_02{border-bottom:  1px solid #FFB2A0;font-size: 24px;padding: 8px 0;}
	.head_03{display: flex;justify-content: space-between;padding:8px 0;}
	.head_03_l{padding-top: 6px;}
	.head_03_r{border: 1px solid #FFB2A0;padding: 4px 12px;}
	.shu{display: flex;}
	.shu_01{width: 25%;line-height: 25px;text-align: center;padding: 8px 0;font-weight: 900;}
	.shu_01 span{color: #F66444;}
	.BH{background-color: #EEEEEE;height: 10px;}
	.uni-list-item__content-title{font-size: 12px;}
}
</style>
