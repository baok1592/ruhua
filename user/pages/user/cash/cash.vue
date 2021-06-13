<template>
	<view class="cash">
		<view class="H10"></view>
		<!-- <view class='txz'>提现至:</view> -->
		<view class="H10"></view>
		<view class='money'>
			<view class='ktx'>可提现金额：0.00元</view>
		</view>
		<view class='jine'>
			<view class="jine_01">¥</view>
			<view class="jine_02">
				<input type="number" class="uni-input" placeholder="请输入金额" />
			</view>
		</view>
		<!-- <view class='kg'>        
		<view class="kg_l">大额提现(单笔5万以上）</view>
		<switch checked style="transform:scale(0.7)" />            
      </view> -->
		<view class='khh'>银行名称 &emsp;<input type="text" /></view>
		<view class='khh'>银行户名 &emsp;<input type="text" /></view>
		<view class='khh'>银行卡号 &emsp;<input type="number" /></view>
		<view class='txfy'>
			<view class='txfy_01'>
				<view class='txfy_01_l'>提现费用:</view>
				<view class='txfy_01_r'>0.00元</view>
			</view>
			<view class='txfy_02'>
				<view class='txfy_02_l'>预计到账时间</view>
				<view class='txfy_02_r'>当日到账</view>
			</view>
		</view>
		<view class='btn'>提现</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				checked: true
			};
		},
		computed: {

		},
		components: {},
		methods: {}
	};
</script>

<style lang="less">
	
	.cash {
		.uni-input{
			background-color: #FFFFFF;
			margin-top: 5px;
		}
		
		
		background-color: #f5f5f5;
		height: 100vh;
		font-size: 14px;

		.txz {
			padding: 15px 10px;
			background-color: #fff;
		}

		.money {
			background-color: #fff;
			padding: 10px;
		}

		.ktx {
			font-size: 12px;
			color: #949398;
		}

		.jine {
			display: flex;
			background-color: #fff;
			padding: 9px 10px;
			border-bottom: 1px solid #F8F8F8;
			border-top: 1px solid #F8F8F8;
		}

		.jine_01 {
			padding: 7px 10px 0 0;
		}

		.kg {
			background-color: #fff;
			display: flex;
			justify-content: space-between;
			padding: 9px 0px 7px 10px;
		}

		.kg_l {
			padding-top: 8px;
		}

		.khh {
			background-color: #fff;
			border-top: 1px solid #F8F8F8;
			padding: 17px 15px 12px;
		}

		.txfy {
			background-color: #fff;
			padding: 5px 10px;
			font-size: 12px;
		}

		.txfy_01 {
			display: flex;
			justify-content: space-between;
			border-top: 1px solid #F8F8F8;
			padding: 10px 0 5px;
		}

		.txfy_02 {
			display: flex;
			justify-content: space-between;
			color: #B8B8BC;
			padding-bottom: 10px;
		}

		.btn {
			margin: 30px 10px 10px;
			background-color: #FF976A;
			height: 43px;
			line-height: 43px;
			text-align: center;
			border-radius: 20px;
			color: #fff;
		}
	}
</style>
