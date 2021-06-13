<template>
	<view class="shop_login" :style="'min-height:'+ body_height">
		<view class="shop_tit">资质认证123</view>
		<view class="s_login">
			<view class="l_01">
				<view class="l_01_l">选择类型</view>
				<view class="l_01_r">自行车 <span><uni-icon type="arrowright" size="15"></uni-icon></span></view>
			</view>
			<view class="l_02">图片</view>
			<view class="l_02">上传图片</view>
			<view class="l_02">身份证照片</view>
			<view class="l_02">上传图片</view>
		</view>
		<view class="biao">
			<view class="biao_01">
				<view class="biao_01_l">姓名：</view>
				<view class="biao_01_r">
					<input class="uni-input"  placeholder="请输入……" />
				</view>
			</view>
			<view class="biao_01">
				<view class="biao_01_l">手机号码：</view>
				<view class="biao_01_r">
					<input class="uni-input"  placeholder="请输入电话" />
				</view>
			</view>
			<view class="biao_01">
				<view class="biao_01_l">身份号码：</view>
				<view class="biao_01_r">
					<input class="uni-input"  placeholder="请输入……" />
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue" 
	export default {
		data() {
			return {
				body_height:'640px'
			};
		},
		components: {uniIcon}
	}
</script>

<style lang="less">
.shop_login{
	background: url(../../../../imgs/4.jpg)  repeat-x #F6F6F6; padding-bottom: 1px;
	.shop_tit{font-size: 22px;color: #fff;padding:20px 10px;}
	.s_login{margin:0 10px;border-radius: 5px;padding: 10px;background-color: #fff;}
	.l_01{display: flex;justify-content: space-between;border-bottom: 1px solid #FBFBFB;height: 40px;line-height: 40px;}
	.l_02{border-bottom: 1px solid #FBFBFB;height: 40px;line-height: 40px;color: #C2C2C2;}
	.biao{background-color: #fff;margin:5px 10px;border-radius: 5px;}
	.biao_01{padding:10px 10px 10px;border-bottom: 1px solid #EDEDED;display: flex;}
	.biao_01_l{padding-top: 7px;}
	
}
</style>
