<template>
	<view class="yfset">
		<view class="cu-form-group margin-top">
			<view class="title">金额（元）</view>
			<view style="text-align: right;"><input placeholder="0" name="input"></input></view>
		</view>
		<view class="money">
			设置该商品的国内统一运费价格
		</view>
		<view class="padding flex flex-direction">
			<button class="cu-btn bg-red margin-tb-sm lg">保存</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		}
	}
</script>

<style lang="scss">
.yfset{background-color: #F3F4F6;min-height: 100vh;padding-top: 1px;
	.money{padding: 15px;}
}
</style>
