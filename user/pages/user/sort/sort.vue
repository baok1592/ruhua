<template>
	<view class="sort">
		<view class="head">
			<view class="head_tit">分销排行榜</view>
			<view>已赚佣金:{{self.total_money}}</view>当前排名：{{self.rank}}
		</view>
		<view class="t_tou">
			<view class="tou_1">排名</view>
			<view class="tou_2">头像/昵称</view>
			<view class="tou_1">分销</view>
		</view>
		<view class="t">
			<block v-for="(item,index) of list" :key="index">
				<li class="t_01">
					<view class="t_01_1">{{index+1}}</view>
					<!-- <view class="t_01_1" v-if="index<3"> <img src="@/imgs/win.png"></img></view> -->
					<view class="t_01_2">
						<view>
							<img :src="item.agent.headpic"></img>
						</view>
						<view class="name_1">
							{{item.agent.nickname}}
						</view>
					</view>
					<view class="t_01_1">{{item.total_money}}</view>
				</li>
			</block>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list: [],
				self: ''
			};

		},
		onLoad() {
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('fx/user/get_fx_rank').then(res => {
					this.list = res.data.rank
					this.self = res.data.self
				})
			}
		}
	}
</script>

<style lang="less">
	.sort {
		.head {
			background-color: #E0451D;
			color: #fff;
			padding: 40px 10px 30px;
			line-height: 20px;
			font-size: 14px;

			.head_tit {
				font-size: 14px;
				padding-bottom: 20px;
			}
		}

		.head span {
			font-size: 30px;
			padding: 0 5px;
		}

		.t_tou {
			font-size: 14px;
			display: flex;
			padding: 10px;
			height: 30px;
			line-height: 30px;
			margin-bottom: 10px;
			justify-content: space-between;
		}

		.tou_1 {
			width: 20%;
			text-align: center;
		}

		.tou_2 {
			width: 45%;
			text-align: left;
		}

		.t_01 {
			font-size: 14px;
			display: flex;
			height: 50px;
			line-height: 50px;
			justify-content: space-between;
		}

		.t_01_1 {
			width: 20%;
			text-align: center;
			margin-right: 10px;
		}

		.t_01_1 img {
			width: 30px;
			height: 30px;
		}

		.t_01_2 {
			width: 45%;
			text-align: left;
			display: flex;
		}

		.t_01_2 img {
			width: 30px;
			height: 30px;
			border-radius: 30px;
			margin-right: 10px;
			margin-top: 10px;
		}

		.t li:nth-of-type(odd) {
			background-color: #EEEEEE;
		}

		.t li:nth-of-type(even) {
			background-color: #fff;
		}

	}
</style>
