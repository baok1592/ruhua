<template>
	<view class="record">
		<view class="head">
			<view class="head_l">
				<view class="head_l_1">余额（元）</view>
				<view class="head_l_2">0.00</view>
			</view>
			<view class="head_r" @click="jump_cash">提现</view>
		</view>
		<block v-for="(item,index) of list" :key="index">
			<view class="list">
				<view class="list_01">
					<view class="list_01_l">{{item.type}}</view>
					<view class="list_01_r">-{{item.money}}</view>
				</view>
				<view class="list_02">
					<view class="list_01_l">{{item.time}}</view>
					<view class="list_01_r" v-if="item.state==0">处理中</view>
					<view class=""  v-if="item.state==1">交易成功</view>
				</view>
			</view>
		</block>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[],
				list2:[{type:"提现",money:"3000.00",time:"2019.06.29 14:00",state:1},
				{type:"提现",money:"3000.00",time:"2019.06.29 14:00",state:0},
				{type:"提现",money:"3000.00",time:"2019.06.29 14:00",state:1}]
			};
		},
		methods:{
			jump_cash(){
				uni.navigateTo({
					url: '/pages/user/cash/cash',
				});
			}
		}
	}
</script>

<style lang="scss">
.record{font-size: 14px;
	.head{background-color: #FF4D4D;padding:20px 10px;display: flex;justify-content: space-between;
		.head_l{color: #fff;
			.head_l_2{font-size: 22px;padding-top: 10px;}
		}
		.head_r{background-color: #fff;color: #FD6766;height: 30px;line-height: 30px;width: 90px;text-align: center;
		border-radius: 20px;margin-top: 15px;}
	}
	.list{margin:15px 10px;box-shadow: 0px 0px 10px #EDEDED;border-radius: 5px;padding:15px 10px;
		.list_01{display: flex;justify-content: space-between;font-size: 16px;font-weight: 600;padding-bottom: 10px;}
		.list_02{display: flex;justify-content: space-between;color: #9A9A9A;
			.list_01_r{color: #FC4F50;}
		}
	}
}
</style>
