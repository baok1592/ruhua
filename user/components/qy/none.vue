<template>
	<view class="no">
		<view class="none">
			<img src="@/imgs/none.png"></img>
			<view>暂无数据</view>
			<view class="guang" v-if="guang" @click="emits"><span>{{guang}}</span></view>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'none',
		props: ['guang'],
		data() {
			return {

			}
		},
		mounted() {

		},
		methods: {
			emits() { 
				this.$emit('emits'); 
			}
		}
	}
</script>

<style lang="less">
	.none {
		padding: 150px 0;
		text-align: center;
		color: #ADADAD;
		line-height: 50px;

		img {
			width: 60px;
			height: 60px;
		}

		.guang {
			text-align: center;
			color: #282828;
			font-size: 14px;

			span {
				height: 30px;
				line-height: 30px;
				border: 1px solid #282828;
				display: inline-block;
				padding: 0 25px;
				border-radius: 2px;
			}
		}
	}
</style>
