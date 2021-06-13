<template>
	<view>
		<view class="example-title">承运公司：{{kd_cpy}}</view>
		<view class="example-title" v-if="kd_num">快递单号：{{kd_num}}</view> 
		<view class="example-body" v-if="list">
			<uni-steps :options="list" :active="0" direction="column" />
		</view>
	</view>
</template>

<script>
	import uniSteps from '@/components/uni/uni-steps/uni-steps.vue'
	export default {
		components: {
			uniSteps
		},
		data() {
			return { 
				list: [],
				id: '',
				kd_cpy:'',
				kd_num:'',
				name_list: [{
						name: '顺丰速运',
						value: 'SF',
					},
					{
						name: '百世快递',
						value: 'HTKY',
					},
					{
						name: '中通快递',
						value: 'ZTO',
					},
					{
						name: '申通快递',
						value: 'STO',
					},
					{
						name: '圆通速递',
						value: 'YTO',
					},
					{
						name: '韵达速递',
						value: 'YD',
					},
					{
						name: '邮政快递包裹',
						value: 'YZPY',
					},
					{
						name: 'EMS',
						value: 'EMS',
					},
					{
						name: '天天快递',
						value: 'HHTT',
					},
					{
						name: '京东快递',
						value: 'JD',
					},
					{
						name: '优速快递',
						value: 'UC',
					},
					{
						name: '德邦快递',
						value: 'DBL',
					},
					{
						name: '宅急送',
						value: 'ZJS',
					},
					{
						name: 'UPS',
						value: 'UPS',
					},
					{
						name: '其他',
						value: '0',
					}

				]
			}
		},
		onLoad(options) {
			this.id = options.id
			this.get_courier()
		},
		methods: {
			change() {
				if (this.active < this.list1.length - 1) {
					this.active += 1
				} else {
					this.active = 0
				}
			},
			get_courier() {
				const that = this
				this.$api.http.post('order/get_kd', {
					id: this.id
				}).then(res => { 
					console.log(res);
					this.kd_cpy = res.result.type
					this.kd_num = res.result.number
					let list=[]
					for (let k in res.result.list) {
						const v=res.result.list[k]
						list[k]={}
						list[k]['title']=v.status
						list[k]['desc']=v.time
					} 
					this.list = list 
				})

			},
		}
	}
</script>

<style>
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4
	}

	view {
		font-size: 28upx;
		line-height: inherit
	}

	.example {
		padding: 0 30upx 30upx
	}

	.example-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 32upx;
		color: #464e52;
		padding: 30upx 30upx 30upx 50upx;
		margin-top: 20upx;
		position: relative;
		background-color: #fdfdfd;
		border-bottom: 1px #f5f5f5 solid
	}

	.example-title__after {
		position: relative;
		color: #031e3c
	}

	.example-title:after {
		content: '';
		position: absolute;
		left: 30upx;
		margin: auto;
		top: 0;
		bottom: 0;
		width: 6upx;
		height: 32upx;
		background-color: #ccc
	}

	.example .example-title {
		margin: 40upx 0
	}

	.example-body {
		padding: 30upx;
		background: #fff
	}

	.example-info {
		padding: 30upx;
		color: #3b4144;
		background: #fff
	}

	button {
		margin: 30upx;
	}
</style>
