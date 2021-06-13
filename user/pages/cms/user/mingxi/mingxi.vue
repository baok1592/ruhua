<template>
	<view class="mingxi">
	<!-- 	<view class="head">
			<view class="head_l">
				<view :class="num==0?'ling':'head_l_1'"  @click="change(0)">全部</view>
				<view :class="num==1?'ling':'head_l_1'"  @click="change(1)">代理提成</view>
				<view :class="num==2?'ling':'head_l_1'"  @click="change(2)">用户提成</view>
			</view>
			<view class="head_r"><uni-icon type="pengyouquan" size="25" color="#B2B2B2"></uni-icon></view>
		</view> -->
		<view style="display: flex;justify-content: flex-end;margin-bottom: 10px;">
			<view class="head_btn" @click="jump_cash">去提现</view>
		</view>
		<!-- <view class="shouyi">
			<view class="sy_l">本月收入：<span>¥123.99</span></view>
			<view class="sy_l">今日收入：<span>¥123.99</span></view>
		</view> -->
		<view class="ticheng">
			<block v-for="(item,index) of list" :key="index">
				<li class="tc" @click="jump(item.id)">
					<view class="tc_l">
						<span>代理提成-{{item.card.bank_num.substr(item.card.bank_num.length-4)}}</span><br/>{{item.create_time}}
					</view>
					<view class="tc_2">
						+{{item.money}}<br/>
						<span v-if="item.status==0">提现中</span>
						<span v-if="item.status==1">已完成</span>
					</view>
				</li>
			</block>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				list:[],
				num:0,
			};
		},
		onLoad() {  
			this._load()
		},
		components: {uniIcon},
		methods:{
			_load(){
				// this.$api.http.post('shop_cms/get_tx_log').then(res=>{ 
				// 	this.list=res	
				// })		
				this.list=this.$api.json_cms.get_tx_log
			},
			jump_cash(){
				uni.navigateTo({
					url:'/pages/cms/user/fenxiao/tixian/tixian'
				})
			},
			jump(id){
				uni.navigateTo({
					url:'/pages/cms/user/fenxiao/success/success?id='+id
				})
			},
			change(e){
				this.num=e
				console.log(e)
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
.mingxi{
	.head{display: flex;margin: 10px 0;}
	.head_l{display: flex;width: 85%;justify-content: space-around;}
	.head_r{width: 15%;text-align: center;}
	.head_l_1{border: 1px solid #F2F2F2;padding: 0px 15px;line-height: 25px;}
	.head_btn{margin:10px 20px 0;border: 1px solid #F2F2F2;padding: 0px 15px;line-height: 25px;}
	.ling{color: #E1461D;border: 1px solid #E1461D;padding: 0px 15px;line-height: 25px;}
	.shouyi{border-top: 1px solid #EBEBEB;border-bottom:1px solid #EBEBEB; background-color: #FAFAFA;display: flex;height: 30px;
	line-height: 30px;padding: 3px 10px;margin-top: 15px;}
	.sy_l{width: 50%;}
	.sy_l span{font-weight: bold;}
	.ticheng li:nth-of-type(odd){ background-color: #EEEEEE;} 
	.ticheng li:nth-of-type(even){background-color: #fff;} 
	.tc{display: flex;justify-content: space-between;padding: 10px;line-height: 25px;font-size: 14px;}
	.tc_l{color: #9A9A9A;}
	.tc_l span{font-size: 14px;font-weight: bold;color: #000;}
	.tc_2{color: #E1461D;}
	.tc_2 span{color: #9A9A9A;}
}
</style>
