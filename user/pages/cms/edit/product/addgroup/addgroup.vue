<template>
	<view class="product">

		<view class="top">
		</view>
		<view class="biao" style="display: flex;justify-content: space-between;">
			<view class="biao_01_1">
				<input placeholder="请输入要添加的分组名" v-model="name" style="margin-top: 5px;margin-left: 10px;"/>
				
			</view>

			<view class="biao_02" @click="add_group">
				<view class="biao_01_1" >添加
					<!-- <uni-icon type="plus"></uni-icon> -->
				</view>
			</view>

		</view>
		<checkbox-group @change="group_change">
			<view class="biao">
				<label class="biao_02" v-for="(item,index) of groupList" :key="index">
					<view>
						<checkbox :value="index+''" class="biao_01_1"> </checkbox>
						<span style="padding-left: 5px;">组名：{{item.name}}</span>
					</view>
					
					<view>
						<uni-icon type="trash" size="23" @click="del(item.id)"></uni-icon>
					</view>
				</label>

			</view>
		</checkbox-group>



		<view class="H50"></view>
		<view class="p_btn" >
			<view class="flex flex-direction" >
				<button @click="sub()" class="cu-btn bg-red margin-tb-sm lg">完成</button>
			</view>
		</view>



	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	import robbyImageUpload from '@/components/plan-image-upload/up_img'
	import {
		Api_url
	} from '@/common/config'
	export default {
		data() {
			return {
				groupList: [],
				form: {
					id: '',
					name: ''
				},
				name: '',
				id: '4',
				group: '',
				group_id: ''
			};
		},
		components: {
			uniIcon,
			uniPopup,
			robbyImageUpload
		},
		computed: {},
		watch: {},
		onLoad() {
			this.get_groupList()
			
		},
		onShow() {},
		methods: {
			get_groupList(){
				this.groupList=this.$api.json_cms.get_category
			},
			sub() {
				console.log(this.groupList)
				let group_cache = {
					groupList: this.groupList,
					group_id: this.group_id,
					group_name: this.group
				}
				uni.setStorageSync('groupList', group_cache)
				uni.navigateBack({

				})
			},
			group_change(event) {
				const that = this
				const li = event.target.value
				console.log(event.target.value)
				let arr = []
				let arr2 = []
				for (let x in li) {
					let v = li[x]
					arr[x] = that.groupList[v].id + ''
					arr2[x] = that.groupList[v].name + ''
				}
				this.group = arr2.join(" ")
				this.group_id = arr
				console.log(this.group)
				console.log(this.group_id)
				uni.setStorageSync('group_id',this.group_id)
			},
		},
		onPullDownRefresh() {
			this.get_groupList()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
	page{background-color: #F7F6FB;font-size: 14px;min-height: 100vh;}
	.pic {
		padding: 20upx 10upx;
	}

	.input-view {
		font-size: 28upx;
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

	.close-view {
		text-align: center;
		line-height: 14px;
		height: 16px;
		width: 16px;
		border-radius: 50%;
		background: #FF5053;
		color: #FFFFFF;
		position: absolute;
		top: -6px;
		right: -4px;
		font-size: 12px;
	}

	.product {
		background-color: #F7F6FB;
		height: 100%;

		.head {
			position: relative;
			padding: 0 5px;
		}

		.head img {
			width: 100%;
			height: 150px;
		}

// 		.btn {
// 			position: absolute;
// 			top: 10px;
// 			right: 5px;
// 		}
// 
// 		.btn button {
// 			font-size: 10px;
// 			color: red;
// 			border: none;
// 			border-radius: 20px;
// 			background-color: #CBCBCB;
// 			padding: 5px;
// 		}
// 
		.top {
			box-shadow: 0 0 8px 5px #EDEDED;
			height: 1px;
			background-color: #EDEDED;
		}

		.pro_tit {
			padding: 15px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.BH {
			height: 5px;
			background-color: #F2F2F2;
		}

		.biao {
			background-color: #fff;
			margin: 10px;
			border-radius: 5px;
			border: 1px solid #EAEAEA;
		}

// 		.biao span {
// 			color: red;
// 			padding-right: 5px;
// 		}
// 
		.biao textarea {
			width: 100%;
			border-bottom: 1px solid #EDEDED;
			padding: 10px;
			height: 100px;
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

		.biao_01_1 {
			padding: 5px 0 0 0;
		}

		.biao_01_r {
			flex-grow: 1;
		}

		.biao_01_r text {
			padding-right: 10px;
		}

		.biao_02_r select {
			padding: 0 10px;
			line-height: 25px;
			min-width: 80px;
			text-align: center;
		}

		.biao_01_m {
			border-right: 1px solid #EDEDED;
		}

		.biao_02 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
			justify-content: space-between;
		}

		.biao_03 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_04 {
			display: flex;
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.biao_04_l {
			display: flex;
			width: 50%;
			line-height: 30px;
			padding-right: 10px;
		}

		.biao_04_l_i {
			flex-shrink: 1;
		}

		.biao_04_l_1 {
			flex-shrink: 0;
		}

		.biao_05 {
			padding: 10px 10px 0;
		}

		.jgkc {
			padding: 15px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.p_btn {
			background: #F7F6FB;
			padding: 0 10px 0px;
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 9999;
		}

		.pro_btn {
			background-color: #E5E5E5;
			padding: 10px;
			text-align: center;
			border-radius: 20px;
			background-color: #DF421D;
			color: #fff;
			width: 94%;
		}

		.guige {
			border: 1px solid #EDEDED;
			margin: 10px 20px 5px 10px;
			position: relative;
		}

		.stop {
			position: absolute;
			right: -15px;
			top: 60px;
		}

		.stop img {
			width: 30px;
			height: 30px;
		}

		.uni-input-input,
		.uni-input {
			height: 30px;
			line-height: 30px;
		}
	}
</style>
