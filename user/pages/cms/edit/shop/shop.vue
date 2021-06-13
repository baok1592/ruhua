<template>
	<view class="shop">
		<view class="biao">
			<view class="cu-form-group ">
				<view class="title">店铺名称：</view>
				<view class="title" style="flex-grow: 1;">{{content.shop_name}}</view>
			</view>
			<view class="cu-form-group ">
				<view class="title">店铺地址：</view>
				<view class="title" style="flex-grow: 1;">{{content.shop_address}}</view>
			</view>
			<view class="cu-form-group" @click="biaozu">
				<view class="title">标注位置：</view>
				<input :placeholder="address" name="input" disabled></input>
				<text v-if="!address" class='cuIcon-locationfill text-orange'></text>
			</view>
			<view class="cu-form-group ">
				<view class="title">店铺电话：</view>
				<input placeholder="请输入" name="input" v-model="list.shop_phone"></input>
			</view>
			<view class="biao_05 cu-form-group">
				<view class="title">营业时间：</view>
				<view class="time" >
					<view class="biao_05_2">
						<!-- <input class="uni-input" v-model="list.yy_time" /> -->
						<view class="uni-list-cell-db">
							<picker mode="time" :value="time"  @change="bindTimeChange" style="margin-top: 6px;">
								<view class="uni-input">{{time}}</view>
							</picker>
						</view>
					</view>
					<view class="biao_05_2" style="margin: 10upx 10px 0 0;">--</view>
					<view class="uni-list-cell-db">
						<picker mode="time" :value="time_close"  @change="bindTimeChange_close" style="margin-top: 6px;">
							<view class="uni-input">{{time_close}}</view>
						</picker>
					</view>
				</view>
				
			</view>
			<view class="cu-form-group ">
				<view class="title">购买须知：</view>
			</view>
			<view class="cu-form-group">
				<textarea maxlength="-1" :disabled="modalName!=null" @input="textareaAInput" placeholder="请输入购买须知"></textarea>
			</view>
			<view class="cu-form-group ">
				<view class="title">是否营业：</view>
				<view class="title" >
					<switch @change="SwitchA" :class="switchA?'checked':''"  :checked="switchA?true:false" color="#e54d42"></switch>
					<!-- <switch :checked="list.shop_state" @change="switch1Change" style="transform:scale(0.6)" /> -->
				</view>
			</view>
			
		</view>
		<view style="height: 80px;"></view>
		<view class="p_btn">
			<view class=" flex flex-direction">
				<button @click="sub()" class="cu-btn bg-red margin-tb-sm lg">提交</button>
			</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import wPicker from "@/components/w-picker/w-picker.vue";
	export default {
		data() {
			return {
				content: [],
				list:[],
				address: '',
				time: '09:00',
				time_close: '19:00'
			};
		},
		onLoad() {
			this.content=this.$api.json_cms.content
		},
		components: {
			uniIcon,
			wPicker
		},
		methods: {
			TimeChange(e) {
				this.time = e.detail.value
			},
			bindTimeChange: function(e) {
				this.time = e.target.value
			},
			bindTimeChange_close: function(e) {
				this.time_close = e.target.value
			},



			switch1Change: function(e) {
				if (e.target.value) {
					this.list.shop_state = true //   1   checked的值为布尔值
				} else {
					this.list.shop_state = false  //   0
				}
				console.log(this.list.shop_state)
			},
			//选择位置
			biaozu() {
				const that = this
				// uni.showLoading({
				// 	title: '加载中',
				// 	mask: true
				// });

				//#ifdef MP-WEIXIN 
				uni.getSetting({
					success(res) {
						if (!res.authSetting['scope.userLocation']) {
							uni.authorize({
								scope: 'scope.userLocation',
								success() {
									that.chooseLocation()
									return;
								}
							})
						} else {
							that.chooseLocation()
							return;
						}
					}
				})
				//#endif

				//#ifndef MP-WEIXIN 
				that.chooseLocation()
				//#endif
			},
			chooseLocation() {
				const that = this
				uni.chooseLocation({
					success: function(res) {
						console.log('address:', res)
						that.address = res.name
						that.list.position = res
					}
				});
			},
		}
	}
</script>

<style lang="less">
	.shop {background-color: #fff;min-height: 100vh;
		 .uni-input {background-color: #fff;} 
		.biao span {
			color: #F6996D;
			padding-right: 5px;
		}

		.biao_01 {
			padding: 20px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;font-size: 15px;
		}

		.biao_01_l {
			padding-top: 5px;
		}

		.biao_02 {
			padding: 5px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
			justify-content: space-between;
		}

		.biao_02_l {
			padding-top: 10px;
		}

		.biao_03 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.jgkc {
			padding: 10px;
			background-color: #F2F2F2;
		}

		.pro_btn {
			background-color: #E5E5E5;
			padding: 10px;
			text-align: center;
			margin: 10px;
			border-radius: 20px;
			background-color: #DF421D;
			color: #fff;
			position: fixed;
			bottom: 0;
			z-index: 99;
			width: 89%;
		}

		.biao_04 {
			padding: 20px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_04_l {
			flex-shrink: 0;
		}

		.biao_04 textarea {
			height: 80px;
			width: 90%;
			padding: 0 15px 0 10px;
			box-sizing: border-box;
			background: #FCFAED;
		}

		.biao_05 {
			padding: 15px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_05_1 {
			padding-top: 7px;
			flex-shrink: 0;
		}

		.biao_05_3 {
			padding: 7px 10px 0;
		}
		.time{display: flex;flex-grow: 1;font-size: 17px;}
		.mess_03 {
			display: flex;
			justify-content: space-between;
			padding: 20px 10px;
			border-bottom: 1px solid #F2F2F2;
			line-height: 20px;
		}

		.p_btn {
			background: #fff;
			padding: 0 10px;
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 99;
		}

		.result {
			margin-top: 12upx;
			font-size: 28upx;
		}
	}
</style>
