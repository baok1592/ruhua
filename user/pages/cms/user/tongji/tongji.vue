<template>
	<view class="tongji">
		<view class="t_tit">经营数据</view>
		<view class="t_time">统计时间截止9月2号</view>
		<view class="card">
			<view class="card_01">经营收入</view>
			<view class="card_02" v-if="data.total">¥ {{data.total[0].all_money_total}}</view>
			<view class="card_03">
				<view class="card_03_1">
					<!-- 订单数<br/><span>{{data.total[0].all_num_total}}</span> -->
				</view>
				<view class="card_03_1">
					消费人数<br/><span>{{data.total[0].all_num_total}}</span>
				</view> 
			</view>
		</view>
		<view class="name">用户数据</view>
		<view class="uhsj">
			<view class="uhsj_l">
				<span>121</span> <br/>用户总数
			</view>
			<view class="uhsj_l">
				<span>38</span> <br/>本月新用户
			</view>
		</view>
		<view class="name">其他数据</view>
		<view class="shuju">
			<view class="sj_01"> 
				<view class="sj_01_1">{{data.today[0].today_num_total?data.today[0].today_num_total:0}}<br/>今日订单</view>
				<view class="sj_01_1">¥ {{data.today[0].today_money_total?data.today[0].today_money_total:0}}<br/>今日收益</view> 
			</view>
			<view class="sj_02"></view>
			<view class="sj_01">
				<view class="sj_01_1">{{data.yesterday[0].yesterday_num_total?data.yesterday[0].yesterday_num_total:0}}<br/>昨日订单</view>
				<view class="sj_01_1">¥ {{data.yesterday[0].yesterday_money_total?data.yesterday[0].yesterday_money_total:0}}<br/>昨日收益</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				data:{}
			};
		},
		onLoad() {
			this._load()
		},
		methods:{
			_load(){
				this.$api.http.get('shop/count_order').then(res=>{
					this.data=res
				})
				// this.data=this.$api.json_cms.count_order
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.tongji{padding: 10px;font-size:14px;
	.t_tit{font-size: 22px;padding: 20px 0px 0;font-weight: 900;}
	.t_time{padding: 10px 0px 15px;color: #B8B8B8;font-size: 10px;}
	.card{margin: 10px 0;background: linear-gradient(to bottom, #FA7458, #EB511B);border-radius: 5px;padding: 10px;color: #FCE8E3;
	box-shadow: 2px 2px 5px 3px  #FCEAE8}
	.card_01{padding: 5px 0 10px;color: #FCE8E3}
	.card_02{font-size: 20px;border-bottom: 1px solid #FFE4DC;padding-bottom: 15px;}
	.card_03{display: flex;line-height: 20px;padding: 10px 0 5px;}
	.card_03_1{width: 35%;}
	.name{margin: 25px 0 15px;font-size: 15px;font-weight: bold;}
	.uhsj{display: flex;justify-content: space-around;}
	.uhsj_l{width: 40%;text-align: center;box-shadow: 0px 0px 5px #E5E5E5;border-radius: 5px;line-height: 25px;padding: 15px 10px;}
	.uhsj_l span{font-size: 18px;font-weight: bold;}
	.shuju{background-color: #F8F8F8;border-radius: 5px;}
	.sj_01{display: flex;}
	.sj_01_1{width: 50%;text-align: center;line-height: 25px;padding: 10px;}
	.sj_02{height: 1px;background-color: #fff;}
}
</style>
