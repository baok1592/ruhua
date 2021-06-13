<template>
	<view class="mingxi">

		<view class="head">
			<view class="head_l">
				<view :class="num==0?'ling':'head_l_1'" @click="change(0)">全部</view>
				<view :class="num==1?'ling':'head_l_1'" @click="change(1)">代理提成</view>
				<view :class="num==2?'ling':'head_l_1'" @click="change(2)">用户提成</view>
			</view>
			<view class="head_r">
				<uni-icon type="pengyouquan" size="25" color="#B2B2B2"></uni-icon>
			</view>
		</view>

		<view class="shouyi">
			<view class="sy_l">总收入：<span>¥ {{total}}</span></view>
			<!-- <view class="sy_l">今日收入：<span>¥</span></view> -->
		</view>
		<view class="ticheng">
			<None v-if="list_empty"></None>
			<view class="vip" v-for="(item,index) of vipList" :key="index" v-else>
				<li class="tc">
					<view class="tc_l">
						<span>{{item.user.nickname}}</span>
						<br />{{item.create_time}}
					</view>
					<view class="tc_2">+{{item.money}}</view>
				</li>
			</view>
		</view>
	</view>
</template>

<script>
	import None from "@/components/qy/none.vue"
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				// list:[1,2,3,4,5],
				total: 0,
				num: 0,
				vipList: [],
				vipList2: [{
					"user_id": 4,
					"vip_order_id": 17,
					"from_mobile": "18208627757",
					"money": "0.01",
					"status": 0,
					"type": 1,
					"create_time": "2019-09-15"
				}],
				list_empty: false
			};
		},
		components: {
			uniIcon,
			None
		},
		onLoad() {
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('fx/user/get_fx_record').then(res=>{
					this.vipList = res.data
				})
			},
			change(e) {
				this.num = e
				console.log(e)
			}
		},
		onPullDownRefresh() {
			console.log('refresh');
			this._load();
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
	.mingxi {
		.none {
			padding: 150px 0;
			text-align: center;
			color: #00A9F4;
			line-height: 50px;

			img {
				width: 60px;
				height: 60px;
			}
		}

		.head {
			display: flex;
			margin: 10px 0;
		}

		.head_l {
			display: flex;
			width: 85%;
			justify-content: space-around;
		}

		.head_r {
			width: 15%;
			text-align: center;
		}

		.head_l_1 {
			border: 1px solid #F2F2F2;
			padding: 0px 15px;
			line-height: 25px;
			font-size: 15px;
			border-radius: 5px
		}

		.ling {
			color: #E1461D;
			border: 1px solid #E1461D;
			padding: 0px 15px;
			line-height: 25px;
			font-size: 15px;
			border-radius: 5px
		}

		.shouyi {
			border-top: 1px solid #EBEBEB;
			display: flex;
			height: 30px;
			line-height: 30px;
			padding: 3px 10px;
			margin-top: 15px;
			margin-bottom: 15px
		}

		.sy_l {
			width: 50%;
			font-size: 17px;
		}

		.sy_l span {
			font-weight: bold;
			font-size: 17px
		}

		.ticheng li:nth-of-type(odd) {
			background-color: #EEEEEE;
		}

		.ticheng li:nth-of-type(even) {
			background-color: #fff;
		}

		.tc {
			display: flex;
			justify-content: space-between;
			padding: 10px;
			line-height: 25px;
			font-size: 14px;
		}

		.tc_l {
			color: #9A9A9A;
		}

		.tc_l span {
			font-size: 14px;
			font-weight: bold;
			color: #000;
		}

		.tc_2 {
			color: #E1461D;
		}
	}
</style>
