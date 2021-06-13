<template>
	<view class="tui">
		<!-- <view style="position: absolute;top:45%;left:45%;"><van-loading size="50px" v-if="shua" /></view> -->
		<view class="number">
			<view>金额</view>
			<span>¥ {{price}}</span>
		</view>
		<view class="tkyy">退款原因</view>
		<view class="uni-list">
			<radio-group @change="radioChange">
				<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in items" :key="item.value">
					<view class="yuany">
						<view>{{item.short}}</view>
						<view>
							<radio :value="item.value" :checked="index === current" style="transform:scale(0.8)" />
						</view>
					</view>
				</label>
			</radio-group>
		</view>
		<view class="tksm">
			退款说明
			<span>(可不填)</span>
		</view>
		<view class="shuom"><textarea placeholder="请输入" v-model="content"></textarea></view>
		<view class="btn">
			<button type="warn" @click="sub">提交退款申请</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				// id: this.$route.query.id,
				// total: this.$route.query.total,
				id:'',
				price: '',
				radio: "",
				content: "",
				items: [{
						value: '3',
						short: '多拍了'
					},
					{
						value: '2',
						short: '不想买了',
						checked: 'true'
					},
					{
						value: '1',
						short: '其他'
					}
				],
				current: -1
			};
		},
		components: {},
		onLoad(options) {
			this.id = options.id
			const cache = uni.getStorageSync('price')
			this.price = cache
		},
		methods: {
			radioChange(e){
				this.radio = e.detail.value
			},
			sub() {
				const that = this
				if (this.radio < 1) {
					this.$api.msg('未选择退款原因')
					// this.$toast('未选择退款原因')
					return;
				}

				let data = {
					order_id:this.id,
					radio:this.radio,
					content:this.content,
					goods_id:0
				}
				this.$api.http.post('order/user/tui_kuan', data).then(res => {
					if(res.data == 1){ 
						this.$api.msg('操作成功')
						setTimeout(()=>{
							uni.navigateBack({
							})
						},1000)
					}else{
						this.$api.msg(res.msg)
					}
				});
			}
		},
		mounted() {}
	};
</script>

<style lang="less">
	.tui {
		font-size: 14px;

		.yuany {
			display: flex;
			justify-content: space-between;
			padding: 10px;
			border-bottom: 1px solid #F5F6F7;
		}

		.shuom {
			padding: 10px;
			border-bottom: 1px solid #F5F6F7;

			textarea {
				width: 100%;
				height: 20px;
			}
		}

		.number {
			height: 50px;
			border-bottom: 1px solid #ccc;
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			text-align: right;
			line-height: 50px;
			padding: 0 15px;
			font-size: 16px;
		}

		.number span {
			color: #fe8506;
		}

		.tkyy {
			background-color: #efefef;
			padding-left: 10px;
			height: 35px;
			line-height: 35px;
			font-weight: bold;
		}

		.tksm {
			font-weight: bold;
			background-color: #efefef;
			padding-left: 10px;
			height: 35px;
			line-height: 35px;
		}

		.tksm span {
			color: #999999;
			font-size: 12px;
		}

		.btn {
			padding: 40px 20px;
			background-color: #fff;
		}
	}
</style>
