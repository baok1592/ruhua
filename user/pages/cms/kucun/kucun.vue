<template>
	<view class="kucun">
		<view class="search">搜索商品</view>
		<view class="kc">
			<view class="kc_l">全网库存</view>
			<view class="kc_r">商品库存</view>
		</view>
		<view class="sply">
			<view class="sply_l">商品来源 <span><uni-icon type="arrowdown" size="20"></uni-icon></span></view>
		</view>
		<template>
			<view class="sply">
				<view class="sply_l">未售 <span>库龄：27</span> </uni-badge></view>
				<view class="sply_r"><uni-icon type="upload" size="20"></uni-icon><span>上架</span></view>
			</view>
			<view class="pro">
				<view class="pro_l"><img :src="require('@/imgs/8.jpg')" /></view>
				<view class="pro_r">
					<view class="pro_01">这是晒晒单</view>
					<view class="pro_02">
						<view class="pro_02_l">采购价：¥123 <span> </uni-badge></span></view>
						<view class="pro_02_r"></view>
					</view>
					<view class="pro_02">
						<view class="pro_02_l">销售价：¥123</view>
						<view class="pro_02_r"></view>
					</view>
					<view class="pro_02">
						<view class="pro_02_l">同行价：¥123</view>
						<view class="pro_02_r"><span> </uni-badge></span> 商家：白美剧</view>
					</view>
				</view>
			</view>
			<view class="pro_btn">
				<view class="pt_01"> </uni-badge>销售</view>
				<view class="pt_01">出全网</view>
			</view>
			<view class="H4"></view>
		</template>
		<view class="sply">
			<view class="sply_l">未售 <span>库龄：27</span></view>
			<view class="sply_r"><uni-icon type="upload" size="20"></uni-icon><span>上架</span></view>
		</view>
		<view class="pro">
			<view class="pro_l"><img :src="require('@/imgs/8.jpg')" /></view>
			<view class="pro_r">
				<view class="pro_01">这是晒晒单</view>
				<view class="pro_02">
					<view class="pro_02_l">采购价：¥123 </view>
					<view class="pro_02_r"></view>
				</view>
				<view class="pro_02">
					<view class="pro_02_l">销售价：¥123</view>
					<view class="pro_02_r"></view>
				</view>
				<view class="pro_02">
					<view class="pro_02_l">同行价：¥123</view>
					<view class="pro_02_r"></view>
				</view>
			</view>
		</view>
		<view class="pro_btn">
			<view class="pt_01">销售</view>
			<view class="pt_01">出全网</view>
			<view class="pt_02"> </uni-badge></view>
		</view>
		<view class="down">
			<view class="tjsp">添加商品</view>
			<view class="shu"> </uni-badge></view>
		</view>
		
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				
			};
		},
		components: {uniIcon},
		onPullDownRefresh() {
			//this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.kucun{
	background-color: #fff;
	.search{margin: 10px;border: 2px solid #EDEDED;border-radius: 20px;padding-left: 10px;color: #B6B6B6;height: 30px;line-height: 30px;}
	.kc{box-shadow:inset 0px 15px 20px -15px #EAEAEA;display: flex;height: 40px;line-height: 40px;border-bottom:  1px solid #F3F3F3;}
	.kc_l{width: 50%;text-align: center;border-right: 1px solid #F3F3F3;}
	.kc_r{width: 50%;text-align: center;}
	.sply{height: 40px;line-height: 40px;padding: 0 10px;display: flex;justify-content: space-between;border-bottom:1px solid #F3F3F3; }
	.sply span{padding:0 5px;}
	.pro{padding:15px 10px;display: flex;border-bottom:1px solid #F3F3F3;}
	.pro_l{width: 25%;text-align: center;}
	.pro_l img{width: 60px;height: 60px;}
	.pro_r{width: 75%;}
	.pro_01{font-size: 14px;padding-bottom: 5px;}
	.pro_02{display: flex;justify-content: space-between;color: #79797A;height: 20px;line-height: 20px;}
	.pro_btn{padding: 10px;display: flex;flex-direction: row-reverse;}
	.pt_01{border: 1px solid #8A8A8A;color: #79797A;text-align: center;padding:0 6px;width: 60px;margin-left: 8px;border-radius: 2px;height:28px;
	line-height:28px;}
	.pt_02{padding-top: 5px;}
	.H4{height: 4px;background-color:#F3F3F3;}
	.down{display: flex;justify-content: center;}
	.tjsp{background-color: #9D9D9D;color: #EFEFEF;border-radius: 20px;margin: 20px 10px 10px;height: 30px;line-height: 30px;width: 80px;text-align: center;}
	.shu{padding-top: 29px;}
}
</style>
