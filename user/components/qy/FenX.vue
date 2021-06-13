<template>
	<view > 
		<block  v-for="(item,index) of fxlist" :key="index">
			<view class='fenxiang'>
				<view class='app'>
					<view class="fx_01"> 
						<view class="fx_01_l" v-if="item.user"><img :src="item.user.headpic"></view>
						<view class="fx_01_r" v-if="item.user">
							{{item.user.nickname}}<br/><span>{{item.create_time}}</span>
						</view>
					</view>
					<view class="fx_02">{{item.title}}</view>
					<!-- <view class="fx_03">UR水果啥都诶㧣</view> -->
					<view class="fx_04" :style="'height: '+height">
						<rich-text :nodes="item.article"></rich-text>
					</view>
					<!-- <view class="fx_06" v-if="qw"><view class="fx_05"  @click="zsqw">显示全文</view></view> -->
					<view class="fx_07" @click="bigimage(item.image)">
						<block  v-for="(i,ins) of item.image" :key="ins" v-if="ins<9">
							<img :src="i">	
						</block>
					</view>
					<view class="fx_08" @click="jump_pro(item.goods.id)">
						<view class="fx_08_l"><img :src="item.goods.imgs"></view>
						<view class="fx_08_r">
							<view class="fx_08_r_1">{{item.goods.goods_name}}</view>
							<view class="price-box">
								<text class="coupon-tip">VIP</text>
								<text class="price">{{item.goods.vip_price}}</text>
								<text class="coupon-tip-grey">普通</text>
								<text class="prices">{{item.goods.price}}</text>
							</view>
							<!-- <view class="fx_08_r_2">¥<span style="font-size:28upx;">{{item.goods.price}}</span></view>
							<view class="fx_08_r_2" style="color:red;">VIP： <span>{{item.goods.vip_price}}</span></view> -->
						</view>
					</view>
				</view> 
			</view> 
		</block>
	</view>
</template>

<script>
	import Cache from "@/common/cache.js"
	export default {
		data() {
			return {
				height:"40px",
				qw:true         
			}			
		}, 
		props:{
			name:String,
			fxlist:Array
		},
		components: {
		}, 
		mounted() {
			console.log('xxxx')
			this._load()			
		},
		methods:{
			bigimage(imageList){
				uni.previewImage({
					current: 0,
					urls: imageList
				});

			},
			zsqw(){
				this.height="80px",
				this.qw=false
			},
			_load(){
				console.log('xxxx') 
			},
			jump_pro(id){
				uni.navigateTo({
					url:'/pages/product/product?id='+id
				})
			}
			
		}
	}
</script>

<style lang='scss'>
.fenxiang{
	padding: 10px;background-color: #fff;margin: 10px;border-radius: 8px;
	.fx_01{display: flex;}
	.fx_01_l{padding-right: 10px}
	.fx_01_l img{width: 40px;height: 40px;border-radius: 40px;}
	.fx_01_r{line-height: 20px;}
	.fx_01_r span{font-size: 12px;color: #A2A2A2;}
	.fx_02{font-weight: bold;padding:15px 0 5px; }
	.fx_03{min-height: 40px;line-height: 20px;}
	.fx_04{line-height: 20px;padding-bottom:20px;overflow: hidden;}
	.fx_05{color: #5F7FBA;font-size: 12px;position: absolute;left: 0;bottom: 0px;padding-top: 20px;z-index: 99;background-color:rgba(255,255,255,0.5);width: 100%;height: 20px;line-height: 20px;}
	.fx_06{height: 20px;position: relative;}
	.fx_07{padding:10px 0;overflow: hidden;box-sizing: border-box;}
	.fx_07 img{width: 26vw;margin: 5px 1.8%;height: 17.33vw;border-radius: 3px;}
	.fx_08{background-color: #f2f2f2;border-radius: 8px;margin: 10px 0;padding: 8px;display: flex;}
	.fx_08_l{display: flex;flex-direction: column;justify-content: center;}
	.fx_08_l img{width: 120px;height: 80px;border-radius: 8px;}
	.fx_08_r{padding-left: 10px;padding-top: 3px;line-height: 20px;display: flex;flex-direction: column;justify-content:space-between;}
	.fx_08_r_1{max-height: 40px;overflow: hidden;}
	.fx_08_r_2{font-size: 10px;}
	.fx_08_r_2 span{font-size: 18px;}
	.price-box {
		display: flex;
		align-items: baseline;
		height: 64upx;
		padding: 10upx 0;
		font-size: 12px;
		color: $uni-color-primary;
	}
	
	.price {
		
		margin:0px 6px 0 2px;
	}
	.prices{
		margin:0px 0px 0 2px;}
	.m-price {
		margin: 0 12upx;
		color: $font-color-light;
		text-decoration: line-through;
	}
	
	.coupon-tip {
		align-items: center;
		padding: 4upx 10upx;
		background: $uni-color-primary;
		color: #fff;
		border-radius: 6upx;
		line-height: 1;
		transform: translateY(-4upx);
	}
	.coupon-tip-grey {
		background: $btn-grey;
		align-items: center;
		padding: 4upx 10upx;
		color: #fff;
		border-radius: 6upx;
		line-height: 1;
		transform: translateY(-4upx);
	}
}
</style>
