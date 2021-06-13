<template>
	<view class="cartnone">
		<view class="nimg"><img src="@/imgs/gou.png"></img></view>
		<view class="tiao">购物袋还是空空的,去挑选几件中意的商品吧</view>
		<view class="nbtn">
			
			<view class="nbtn_l">逛一逛</view>
			<view class="nbtn_l">看看收藏</view>
		</view>
		<view class="haowu">发现心仪好物</view>
		<view class="tui-product-box">
			
			<view class="tui-product-list">
				<view class="tui-product-container">
					<block v-for="(item,index) in productList" :key="index">
						<!--商品列表-->
						<view class="tui-pro-item" @tap="detail(item.goods_id)">
							<image :src="$getimg+item.imgs" class="tui-pro-img" style="height: 46vw;width: 46vw;" />
							<view class="tui-pro-content">
								<view class="tui-pro-tit">{{item.goods_name}}</view>
								<view>
									<view class="tui-pro-price">
										<text class="tui-sale-price" v-if="is_vip">vip {{item.price}}</text>
										<text class="tui-sale-price" v-else>￥ {{item.price}}</text>
										<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
									</view>
									<view class="tui-pro-pay">{{item.sales}}人付款</view>
								</view>
							</view>
						</view>
						<!--商品列表-->
						<!-- <template is="productItem" data="{{item,index:index}}" /> -->
					</block>
				</view>
				<view class="tui-product-container">
					<block v-for="(item,index) in productList" :key="index" >
						<!--商品列表-->
						<view class="tui-pro-item" hover-class="hover" :hover-start-time="150" @tap="detail(item.goods_id)">
							<image :src="$getimg+item.imgs" class="tui-pro-img" style="height: 46vw;width: 46vw;" />
							<view class="tui-pro-content">
								<view class="tui-pro-tit">{{item.goods_name}}</view>
								<view>
									<view class="tui-pro-price">
										<text class="tui-sale-price" v-if="is_vip">vip {{item.price}}</text>
										<text class="tui-sale-price" v-else>￥ {{item.price}}</text>
										<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
									</view>
									<view class="tui-pro-pay">{{item.sales}}人付款</view>
								</view>
							</view>
						</view>
						<!--商品列表-->
						<!-- <template is="productItem" data="{{item,index:index}}" /> -->
					</block>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				productList: [],
			};
		},
		onLoad() {
			this._load()
		},
		methods:{
			_load() {
				this.$api.http.get('product/get_recent',{type:'hot'}).then(res=>{    //热门
					this.productList = res.data
				})
			}
		}
	}
</script>

<style lang="scss">
.cartnone{
	.nimg{text-align: center;margin: 10px 0 15px;
		img{width: 64px;height: 64px;}
	}
	.tiao{text-align: center;color: #A6A6A6;}
	.nbtn{display: flex;justify-content:space-around;margin: 15px 75px 25px;
		.nbtn_l{border: 1px solid #969696;padding: 5px 15px;border-radius: 15px;color: #888888;}
	}
	.haowu{text-align: center;font-weight: 600;font-size: 16px;margin-bottom: 20px;}
	.tui-product-box {
		margin-top: 20rpx;
		padding: 0 20rpx;
		box-sizing: border-box;
	}
	
	.tui-pb-20 {
		padding-bottom: 20rpx;
	}
	
	.tui-bg-white {
		background: #fff;
	}
	
	.tui-group-name {
		font-size: 32rpx;
		font-weight: bold;
		text-align: center;
		padding: 24rpx 0;
	}
	
	.tui-activity-box {
		display: flex;
		border-radius: 12rpx;
		overflow: hidden;
	}
	
	.tui-activity-img {
		width: 50%;
		display: block;
	}
	
	.tui-new-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
	}
	
	.tui-new-item {
		width: 49%;
		height: 200rpx;
		padding: 0 20rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		background: #f5f2f9;
		position: relative;
		border-radius: 12rpx;
	}
	
	.tui-new-mtop {
		margin-top: 2%;
	}
	
	.tui-title-box {
		font-size: 24rpx;
	}
	
	.tui-new-title {
		line-height: 32rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}
	
	.tui-new-price {
		padding-top: 18rpx;
	}
	
	.tui-new-present {
		color: #ff201f;
		font-weight: bold;
	}
	
	.tui-new-original {
		display: inline-block;
		color: #a0a0a0;
		text-decoration: line-through;
		padding-left: 12rpx;
		transform: scale(0.8);
		transform-origin: center center;
	}
	
	.tui-new-img {
		width: 140rpx;
		height: 140rpx;
		display: block;
		flex-shrink: 0;
		border-radius: 5px;
	}
	
	.tui-new-label {
		width: 56rpx;
		height: 56rpx;
		border-top-left-radius: 12rpx;
		position: absolute;
		left: 0;
		top: 0;
	}
	
	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
		/* padding-top: 20rpx; */
	}
	
	.tui-product-container {
		flex: 1;
		margin-right: 2%;
	}
	
	.tui-product-container:last-child {
		margin-right: 0;
	}
	
	.tui-pro-item {
		width: 100%;
		margin-bottom: 4%;
		background: #fff;
		box-sizing: border-box;
		border-radius: 12rpx;box-shadow: 0px 0px 5px #E9E8E5;
		overflow: hidden;
	}
	
	.tui-pro-img {
		width: 320rpx;
		height: 320rpx;
		display: block;
	}
	
	.tui-pro-content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		padding: 20rpx;
	}
	
	.tui-pro-tit {
		color: #2e2e2e;
		font-size: 26rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}
	
	.tui-pro-price {
		padding-top: 18rpx;
	}
	
	.tui-sale-price {
		font-size: 34rpx;
		font-weight: 500;
		color: #e41f19;
	}
	
	.tui-factory-price {
		font-size: 24rpx;
		color: #a0a0a0;
		padding-left: 12rpx;
	}
	
	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 24rpx;
		color: #656565;
	}
}
</style>
