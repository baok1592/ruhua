<template>
	<view class="success">
		<view class="s_tit">
			提现申请成功<span>完成</span>
		</view>
		<view class="BH10"></view>
		<view class="chengg">
			<img :src="require('@/imgs/12.png')" />
			<view class="cg">提现申请成功</view>
			<view class="cg_q">¥ {{list.money}}</view>
		</view>
		<view class="txfs">
			<view class="txfs_l">提现方式</view>
			<view class="txfs_r"><img :src="require('@/imgs/yin'+list.card.type+'.png')" v-if="list.card.type!=0" />{{name}}（{{card_num}}）</view>
		</view>
		<view class="txzt">提现状态</view>
		<uni-steps :options="
			buzhou" 
			direction="column" 
			:active="step" active-color="#E1461D">
		</uni-steps>
		<view class="dzsj">
			<view class="dzsj_01">到账时间</view>
			<view class="dzsj_02">
				1、银行卡方式体现工作日15:00之前提现，2小时到账，15:00之后体现次日到账。<br/>
				2、节假日提现顺延。
			</view>
		</view>
		<view class="btn" @click="jump()">确认</view>
	</view>
</template>

<script>
	import uniSteps from "@/components/uni/uni-steps/uni-steps.vue"
	export default {
		data() {
			return {
				id:'',
				list:'',
				name:'',
				num:'',
				card_num:'',
				buzhou:'',
				step:0,
				one:[{title:'提现申请中，等待处理',desc:'create_time'}],
				two:[{title:'提现申请中，等待处理',desc:'create_time'},
					{title:'已完成'}]
			};
		},
		components: {uniSteps},
		onLoad(options) { 
			this.id=options.id
			this._load()
		},
		methods:{
			_load(){
				// this.$api.http.post('shop_cms/get_one_tx?id='+this.id).then(res=>{ 
				this.list=this.$api.json_cms.get_one_tx
					const card_num=this.list.card.bank_num
					this.card_num=card_num.substr(card_num.length-4)
					if(this.list.status==0){
						this.one[0].desc=this.list.create_time
						this.buzhou=this.one
					}
					if(this.list.status==1){
						this.two[0].desc=this.list.create_time
						this.buzhou=this.two
						this.step=1
					}
					if(this.list.card.type==0){
						this.name=this.list.card.bank_name;
					}
					if(this.list.card.type==1){
						this.name="人民银行";
					}
					if(this.list.card.type==2){
						this.name="农业银行";
					}
					if(this.list.card.type==3){
						this.name="工商银行";
					}
					if(this.list.card.type==4){
						this.name="建设银行";
					}
				// })	
			},
			jump(){
				uni.switchTab({
					url:'/pages/index/index'
				})
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
.success{
	.s_tit{text-align: center;padding: 10px;font-size: 16px;position: relative;}
	.s_tit span{position: absolute;right: 20px;top: 10px;color: #F66444;font-size: 14px;}
	.BH10{height: 8px;background-color: #F2F2F2;}
	.chengg{padding: 15px;text-align: center;border-bottom: 1px solid #FBFBFB;}
	.chengg img{width: 65px;height: 65px;}
	.cg{padding: 5px 0 15px;}
	.cg_q{font-size: 30px;font-weight: bold;}
	.txfs{padding: 0px 10px;border-bottom: 1px solid #FBFBFB;display: flex;justify-content: space-between;height: 40px;line-height: 40px;}
	.txfs_r{color: #9D9D9D;display: flex;}
	.txfs_r img{width: 20px;height: 20px;margin:10px 5px 0 0;}
	.txzt{padding: 15px 10px 5px;}
	.dzsj{padding: 0px 10px;color: #919191;line-height: 19px;}
	.dzsj_01{color: #000;font-size: 14px;padding-bottom: 5px;}
	.btn{margin:25px;background-color: #F66444;color: #fff;text-align: center;height: 40px;line-height: 40px;font-size: 16px;
	border-radius: 5px;box-shadow: 0px 0px 2px #F66444;}
}
</style>
