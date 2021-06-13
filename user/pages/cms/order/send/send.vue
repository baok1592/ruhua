<template>
	<view>

		<block>
			<view class="biao">
				<view class="biao_01" @click="show = true">
					<view class="biao_01_l"> 快递公司：</view>
					<view class="biao_01_r"  style="margin-top: 7px;margin-left: 60%;">
						<!-- <input class="uni-input" /> -->
						{{courier_name}}
					</view>
				</view>
			</view>
			<view class="biao">
				<view class="biao_01">
					<view class="biao_01_l"> 快递单号：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="courier_num" />
					</view>
				</view>
			</view>
			<view class="p_btn">
				<button class="pro_btn" @click="sub_send">确认发货</button>
			</view>
		</block>
		<uni-popup :show="show" type="bottom" mode="fixed" @hidePopup="show=false">
			<view class="uni-list leixin">
				<radio-group @change="choose_name">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in name_list" :key="index">
						<view class="xuan">
							<radio :value="index+''" />
							{{item.name}}
						</view>
					</label>
				</radio-group>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import uniList from '@/components/uni/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni/uni-list-item/uni-list-item.vue'
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	export default {
		components: {
			uniList,
			uniListItem,
			uniPopup
		},
		data() {
			return {
				show: false,
				courier_name:'',
				courier: '',
				courier_num: '',
				id: '',
				name_list: [{ 
						name:'顺丰速运',
						value:'SF',
					},
					{ 
						name:'百世快递',
						value:'HTKY',
					},
					{ 
						name:'中通快递',
						value:'ZTO',
					},
					{ 
						name:'申通快递',
						value:'STO',
					},
					{ 
						name:'圆通速递',
						value:'YTO',
					},
					{ 
						name:'韵达速递',
						value:'YD',
					},
					{ 
						name:'邮政快递包裹',
						value:'YZPY',
					},
					{ 
						name:'EMS',
						value:'EMS',
					},
					{ 
						name:'天天快递',
						value:'HHTT',
					},
					{ 
						name:'京东快递',
						value:'JD',
					},
					{ 
						name:'优速快递',
						value:'UC',
					},
					{ 
						name:'德邦快递',
						value:'DBL',
					},
					{ 
						name:'宅急送',
						value:'ZJS',
					},
					{
						name:'UPS',
						value:'UPS',
					},
					{
						name:'其他',
						value:'0',
					}
				]
			}
		},
		onLoad(options) {
			this.id = options.id
		},
		methods: {
			sub_send() {
				if(!this.courier_num){
					this.$api.msg('快递单号不能为空')
					return;
				}
				this.$api.http.post('order/mcms/edit_courier',{
					courier:this.courier,
					courier_num:this.courier_num,
					order_id:this.id
				}).then(res=>{
					if(res.status == 400){
						this.$api.msg(res.msg)
						setTimeout(()=>{uni.navigateBack({})},2000)
					}
					if(res.status == 200){
						this.$api.msg('发货成功')
						setTimeout(()=>{uni.navigateBack({})},2000)
					}
					
				})
			},
			choose_name(e){
				console.log(e.detail)
				const index = e.detail.value
				this.courier = this.name_list[index].value
				this.courier_name = this.name_list[index].name
				console.log(this.courier)
				this.show = false
				
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
	.leixin {
		height: 300px;
		width: 50vw;
		overflow: hidden;
		overflow-x: hidden;
		overflow-y: scroll;
	}
	.xuan {
		padding-bottom: 10px;
	}

	.biao {
		background-color: #fff;
		margin: 10px;
		border-radius: 5px;
		border: 1px solid #EAEAEA;
	}

	.biao_01 {
		padding: 10px 10px 10px;
		border-bottom: 1px solid #EDEDED;
		display: flex;
	}

	.biao_01_l {
		padding-top: 7px;
		flex-shrink: 0;
	}

	.biao_01_r {
		flex-grow: 1;
	}

	.uni-input-input,
	.uni-input {
		height: 30px;
		line-height: 30px;
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

	.p_btn {
		text-align: center;
		background: #F7F6FB;
		padding: 0 10px 10px;
		position: fixed;
		bottom: 0;
		width: 100%;
		z-index: 9999;
		background-color: #E5E5E5;
	}

	.pro_btn {
		background-color: #DF421D;
		color: #fff;
		border-radius: 20px;
		
	}
</style>
