<template>
	<view class="container">
			
		<!--header-->
		<view class="tui-header">
			<view class="tui-category" hover-class="opcity" :hover-stay-time="150" @tap="classify">
				<tui-icon name="manage-fill" color="#fff" :size="22"></tui-icon>
				<view class="tui-category-scale">分类</view>
			</view>
			<view class="tui-rolling-search">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#999'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<tui-icon name="search" :size='16' color='#999'></tui-icon>
				</view>
				<!-- #endif -->
				<swiper vertical autoplay circular interval="8000" class="tui-swiper">
					<swiper-item v-for="(item,index) in hotSearch" :key="index" class="tui-swiper-item" @tap="search">
						<view class="tui-hot-item">{{item}}</view>
					</swiper-item>
				</swiper>
			</view>
		</view>
		<!--header-->
		<view class="tui-header-banner">
			<view class="tui-hot-search">
				<view style="width: 50px;flex-shrink: 0;">热搜</view>
				<view class="tui-hot-tag" v-for="(item,index) of resou" :key="index" v-if="index<4">
					<view @click="tosearch(item)">{{item}}</view>
				</view>
			</view>
			<view class="tui-banner-bg">
				<view class="tui-primary-bg tui-route-left"></view>
				<view class="tui-primary-bg tui-route-right"></view>
				<!--banner-->
				<view class="tui-banner-box">
					<swiper :indicator-dots="true" :autoplay="true" :interval="5000" :duration="150" class="tui-banner-swiper"
					 :circular="true" indicator-color="rgba(255, 255, 255, 0.8)" indicator-active-color="#fff">
						<swiper-item v-for="(item,index) in banner" :key="index" @click="jump_article(item.jump_id,item.type)">
							<image :src="getimg+item.imgs" class="tui-slide-image" mode="scaleToFill" />
						</swiper-item>
					</swiper>
				</view>
			</view>
		</view>

		<view class="tui-product-category">
			<swiper :indicator-dots="true" :interval="5000" :duration="150" class="tui-banner-swiper" style='height:160px'
			 :circular="true" indicator-color="#ccc" indicator-active-color="rgba(251,88,106, 0.8)">
				<swiper-item>
					<view style="display: flex;flex-wrap: wrap;">
						<view class="tui-category-item" v-for="(item,index) in category" :key="index" :data-key="item.name" v-if="index<8">
							<!-- <navigator :url="item.url">
								<image :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></image>
								<view class="tui-category-name">{{item.nav_name}}</view>
							</navigator> -->
							<view @click="jump(item.url,item.id,item.url_name)">
								<image :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></image>
								<view class="tui-category-name">{{item.nav_name}}</view>
							</view>
						</view>
					</view>
				</swiper-item>
				<swiper-item>
					<view style="display: flex;flex-wrap: wrap;">
						<view class="tui-category-item" v-for="(item,index) in category" :key="index" :data-key="item.name" v-if="index>7">
							<navigator :url="item.url">
								<block v-if="item.nav_name == '优惠券'" @click="check">
									<image :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></image>
									<view class="tui-category-name">{{item.nav_name}}</view>
								</block>
								<block v-else>
									<image :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></image>
									<view class="tui-category-name">{{item.nav_name}}</view>
								</block>

							</navigator>
						</view>
					</view>
				</swiper-item>
			</swiper>
			<!-- <view class="tui-category-item" v-for="(item,index) in category" :key="index" :data-key="item.name"> 
				<navigator :url="item.url">
					<image :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></image>
					<view class="tui-category-name">{{item.nav_name}}</view>
				</navigator>
			</view> -->
		</view>

		<!-- 	 <view class="tui-product-box tui-pb-20 tui-bg-white">
			<view class="tui-group-name" @tap="more">
				<text>新人专享</text>
				<tui-icon name="arrowright" :size="20" color="#555"></tui-icon>
			</view>
			<view class="tui-activity-box" @tap="detail">
				<image src="../../static/images/mall/activity/activity_1.jpg" class="tui-activity-img" mode="widthFix"></image>
				<image src="../../static/images//mall/activity/activity_2.jpg" class="tui-activity-img" mode="widthFix"></image>
			</view>
		</view> -->
		<!-- #ifdef MP-WEIXIN -->
		<button class="btn1" open-type="contact">
			<image class="btnImg" src="../../static/images/kefu.png"></image>			 
			<!-- <view>客服</view> -->
		</button>
		<!-- #endif -->

		<view class="tui-product-box tui-pb-20 tui-bg-white">
			<view class="tui-group-name">
				<text>新品推荐</text>
			</view>
			<view class="tui-new-box">
				<view class="tui-new-item" :class="[index!=0 && index!=1 ?'tui-new-mtop':'']" v-for="(item,index) in newProduct"
				 :key="index" @tap="detail(item.goods_id)">
					<!-- <img src="@/imgs/6.jpg" class="tui-new-label" /> -->
					<view class="tui-title-box">
						<view class="tui-new-title">{{item.goods_name}}</view>
						<view class="tui-new-price">
							<text class="tui-new-present">￥{{item.price}}</text>
							<!-- <text class="tui-new-original">￥{{item.market_price}}</text> -->
						</view>
					</view>
					<img :src="getimg+item.imgs" class="tui-new-img2" />
				</view>

			</view>

		</view>

		<view class="tui-product-box">
			<view class="tui-group-name">
				<text>热门推荐</text>
			</view>
			<view class="tui-product-list">
				<view class="tui-product-container">
					<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2!=0">
						<!--商品列表-->
						<view class="tui-pro-item" @tap="detail(item.goods_id)">
							<view class='pic'>
								<image :src="getimg+item.imgs" class="tui-pro-img" style="height: 46vw;width: 46vw;" />
								<view v-if="item.stock==0">
									<view class='cont-img'> </view>
									<view class='maiguang'>
										<img src='@/imgs/x.png'></img>
									</view>
								</view>
							</view>
							<view class="tui-pro-content">
								<view class="tui-pro-tit">{{item.goods_name}}</view>
								<view>
									<view class="tui-pro-price">
										<text class="tui-sale-price" v-if="is_vip">vip{{item.price}}</text>
										<text class="tui-sale-price" v-else>￥{{item.price}}</text>
										<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
										<xianshi v-if="item.discount && item.discount.reduce_price" title="限时" :price="item.price-item.discount.reduce_price*1"></xianshi>
										<xianshi v-if="item.pt && item.pt.price" title="拼团" :price="(item.price*100-item.pt.price*100)/100"></xianshi>
									</view>
									<!-- <view class="tui-pro-pay">{{item.sales}}人付款</view> -->
								</view>
							</view>
						</view>
						<!--商品列表-->
						<!-- <template is="productItem" data="{{item,index:index}}" /> -->
					</block>
				</view>
				<view class="tui-product-container">
					<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2==0">
						<!--商品列表-->
						<view class="tui-pro-item" hover-class="hover" :hover-start-time="150" @tap="detail(item.goods_id)">

							<view class='pic'>
								<image :src="getimg+item.imgs" class="tui-pro-img" style="height: 46vw;width: 46vw;" />
								<view v-if="item.stock==0">
									<view class='cont-img'> </view>
									<view class='maiguang'>
										<img src='@/imgs/x.png'></img>
									</view>
								</view>
							</view>
							<view class="tui-pro-content">
								<view class="tui-pro-tit">{{item.goods_name}}</view>
								<view>
									<view class="tui-pro-price">
										<text class="tui-sale-price" v-if="is_vip">vip {{item.price}}</text>
										<text class="tui-sale-price" v-else>￥{{item.price}}</text>
										<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
										<xianshi v-if="item.discount.reduce_price" title="限时" :price="item.price-item.discount.reduce_price*1"></xianshi>
										<xianshi v-if="item.pt.price" title="拼团" :price="item.price-item.pt.price*1"></xianshi>
									</view>
									<!-- <view class="tui-pro-pay">{{item.sales}}人付款</view> -->
								</view>
							</view>
						</view>
						<!--商品列表-->
						<!-- <template is="productItem" data="{{item,index:index}}" /> -->
					</block>
				</view>
			</view>
		</view>

		<!--加载loadding-->
		<tui-loadmore :visible="loadding" :index="3" type="red"></tui-loadmore>
		<!-- <tui-nomore visible="{{!pullUpOn}}"></tui-nomore> -->
		<!--加载loadding-->
		<view class="tui-safearea-bottom"></view>
		<!-- <Coupon :coupon="coupon" :coulist="coulist" @close_add="close_add"></Coupon> -->
		<!-- 弹出 -->
		<!-- #ifdef APP-PLUS -->
		<Xieyi></Xieyi>
		<!-- #endif -->
	</view>
</template>
<script>
	import Check from '@/common/check.js'
	import Xieyi from "@/components/qy/xieyi"
	import tuiIcon from "@/components/icon/icon"
	import Coupon from "@/components/qy/Coupon"
	import tuiTag from "@/components/tag/tag"
	import tuiLoadmore from "@/components/loadmore/loadmore"
	import tuiNomore from "@/components/nomore/nomore"
	import Cache from "@/common/cache.js"
	import xianshi from "@/components/qy/xianshi"
	export default {
		components: {
			tuiIcon,
			tuiTag,
			tuiLoadmore,
			tuiNomore,
			Coupon,
			xianshi,
			Xieyi
		},
		data() {
			return {
				xy: true,
				resou: '',
				coulist: [1, 2, 3, 4],
				coupon: true,
				is_vip: 0,
				getimg: this.$getimg,
				current: 0,
				tabbar: [{
					icon: "home",
					text: "首页",
					size: 21
				}, {
					icon: "category",
					text: "分类",
					size: 24
				}, {
					icon: "cart",
					text: "购物车",
					size: 22
				}, {
					icon: "people",
					text: "我的",
					size: 24
				}],
				hotSearch: [],
				banner: [],
				category: [],
				newProduct: [],
				productList: [],

				pageIndex: 1,
				loadding: false,
				pullUpOn: true,
				switch_list: '',
				fx_switch: false
			}
		},
		onLoad(options) {
			if(options.sfm){
				uni.setStorageSync('level_one',options.sfm)	//上级分销的身份码
			}
			this._load()
			console.log(this.$getimg)
			this.switch_list = uni.getStorageSync('switch')
			this.check_switch()
			// this.laoddata()
			// let time = Date.parse(new Date()) / 1000 //当前时间

			// const cache = uni.getStorageSync('home'); //抓取缓存
			// if (!cache || this._CheckCacheTime(cache.cache_time, 0.2)) { //判断缓存是否存在
			// 	this._load()
			// } else {        cue shizdyoudiannan let cache = uni.getstorege a happy journey
			// 	this.category = cache.data[0].data
			// 	this.newProduct = cache.data[1].data
			// 	this.productList = cache.data[2].data
			// 	this.banner = cache.data[3].data

			// }
		},
		methods: {
			check_switch() {
				const that = this
				that.fx_switch = this.switch_list.fx_status == 0?false:true
			},
			jump(url, id,name) {
				const that = this
				if (id == 8) { //优惠券
					if (!Check.a()) {
						return
					}

				}
				if (name == '分销商品') { //分销
					//判断分销开关是否开启
					if (!this.fx_switch) {
						this.$api.msg('未开启分销模式')
						return
						url = '/pages/extend-view/productList/productList?type=no_fx'
							uni.navigateTo({
								url: url
							})
						
						return
					}
				}
				uni.navigateTo({
					url: url
				})

			},

			close_add() {
				this.coupon = false
			},
			tabbarSwitch: function(e) {
				let index = e.currentTarget.dataset.index;
				this.current = index;
				if (index != 0) {
					if (index == 1) {
						this.classify();
					} else if (index == 2) {
						uni.switchTab({
							url: '/pages/cart/cart'
						})
					} else if (index == 3) {
						uni.switchTab({
							url: '/pages/user/user'
						})
					}
				}
			},
			_load() {
				this.$api.http.get('search/record').then(res => { //首页banner 
					this.resou = res.data
					if (res.data == 1) {
						return;
					}
					this.hotSearch = res.data.slice(0, 3)
					uni.setStorageSync('hotSearch', this.resou)
				})
				let a = this.$api.http.get('product/get_recent', {
					type: 'hot'
				}) //热门推荐
				let b = this.$api.http.get('product/get_recent', {
					type: 'new'
				}) //新品推荐
				let c = this.$api.http.get('nav/get_nav') //导航
				let d = this.$api.http.get('banner/get_banner?id=1') //轮播图

				Promise.all([a, b, c, d]).then(res => {
					this.productList = res[0].data
					this.newProduct = res[1].data
					this.category = res[2].data
					console.log(this.category)
					this.banner = res[3].data.items
				})
			},
			_CheckCacheTime(times, xs = 5) {
				const time = Date.parse(new Date()) / 1000 //当前时间
				const end_time = times + 60 * xs
				return time > end_time ? true : false
			},
			detail: function(id) {
				uni.navigateTo({
					url: '/pages/extend-view/productDetail/productDetail?id=' + id
				})
				
			},
			classify: function() {
				uni.switchTab({
					url: '/pages/category/category'
				})
			},
			more: function(e) {
				let key = e.currentTarget.dataset.key || "";
				uni.navigateTo({
					url: '/pages/extend-view/productList/productList?searchKey=' + key
				})

			},
			search: function() {
				uni.navigateTo({
					url: '/pages/extend-view/news-search/news-search'
				})
			},
			jump_article(id, type) {
				let url = '/pages/article/article?id=' + id
				if (type == 'goods') {
					url = '/pages/extend-view/productDetail/productDetail?id=' + id
				}
				uni.navigateTo({
					url: url
				});
			},
			tosearch(item) {
				console.log(this.resou)
				console.log(item)
				uni.navigateTo({
					url: '../extend-view/productList/productList?key=' + item
				})
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		},
		
		//小程序右上角原生菜单分享按钮，也可是页面中放置的分享按钮
		onShareAppMessage(res) {
			let my = uni.getStorageSync('my')
			let path="/pages/index/index"
			if(my && my.data && my.data.sfm){
				 path = path + '?sfm='+my.data.sfm
			}	
			console.log('path:',path)
			return {
			  title: '商城',
			  path: path
			}
		},
	}
</script>

<style lang="scss">
	page {
		background: #f7f7f7;
	}

	/* #ifdef MP-WEIXIN */
	.btn1{
	  width: 60rpx;
	  height: 60rpx; 
	  font-size: 30rpx; 
	  position: fixed;
	  padding: 0px;
	  margin: 0px;
	  right:10rpx;
	  z-index: 999;
	  background: none !important; 
	  
	}
	
	.btnImg {
	  width: 60rpx;
	  height: 60rpx;
	  opacity: 0.8;
	}
	
	.btn1::after {
	  border: 0; 
	}

	/* #endif */



	.container {
		padding-bottom: 100rpx;
		color: #333;
	}

	/*tabbar*/

	.tui-tabbar {
		/* 
		width: 100%;
		position: fixed;
		display: flex;
		align-items: center;
		justify-content: space-between;
		z-index: 99999;
		background: #fff;
		height: 100rpx;
		left: 0;
		bottom: 0;
		padding-bottom: env(safe-area-inset-bottom);
	 */
	}







	.tui-ptop-4 {
		padding-top: 4rpx;
	}

	.tui-scale {
		font-weight: bold;
		transform: scale(0.8);
		transform-origin: center 100%;
		line-height: 30rpx;
	}

	.tui-item-active {
		color: #FB586A !important;
	}

	/*tabbar*/

	.tui-header {
		width: 100%;
		height: 100rpx;
		padding: 0 30rpx 0 20rpx;
		box-sizing: border-box;
		background: #FB586A;
		display: flex;
		align-items: center;
		justify-content: space-between;
		position: fixed;
		left: 0;
		top: 0;
		/* #ifdef H5 */
		top: 44px;
		/* #endif */
		z-index: 999;
	}

	.tui-rolling-search {
		width: 100%;
		height: 60rpx;
		border-radius: 35rpx;
		padding: 0 40rpx 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		color: #999;
	}

	.tui-category {
		font-size: 24rpx;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		margin: 0;
		margin-right: 22rpx;
		flex-shrink: 0;
	}

	.tui-category-scale {
		transform: scale(0.7);
		line-height: 24rpx;
	}

	.tui-icon-category {
		line-height: 20px !important;
		margin-bottom: 0 !important;
	}

	.tui-swiper {
		font-size: 26rpx;
		height: 60rpx;
		flex: 1;
		padding-left: 12rpx;
	}

	.tui-swiper-item {
		display: flex;
		align-items: center;
	}

	.tui-hot-item {
		line-height: 26rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.tui-header-banner {
		padding-top: 100rpx;
		box-sizing: border-box;
		background: #FB586A;
	}

	.tui-hot-search {
		color: #fff;
		font-size: 24rpx;
		display: flex;
		align-items: center;
		padding: 0 20rpx;
		box-sizing: border-box;
		position: relative;
		z-index: 2;
	}

	.tui-hot-tag {
		background: rgba(255, 255, 255, 0.15);
		padding: 10rpx 24rpx;
		border-radius: 30rpx;
		display: flex;
		white-space: nowrap;
		align-items: center;
		margin-right: 15px;
		justify-content: center;
		line-height: 24rpx;
		/* margin-left: 20rpx; */
	}

	.tui-banner-bg {
		display: flex;
		height: 180rpx;
		background: #FB586A;
		position: relative;
	}

	.tui-primary-bg {
		width: 50%;
		display: inline-block;
		height: 224rpx;
		border: 1px solid transparent;
		position: relative;
		z-index: 1;
		background: #FB586A;
	}

	.tui-route-left {
		transform: skewY(8deg);
	}

	.tui-route-right {
		transform: skewY(-8deg);
	}

	.tui-banner-box {
		width: 100%;
		padding: 0 20rpx;
		box-sizing: border-box;
		position: absolute;
		/* overflow: hidden; */
		z-index: 99;
		bottom: -80rpx;
		left: 0;
	}

	.tui-banner-swiper {
		width: 100%;
		height: 240rpx;
		border-radius: 12rpx;
		overflow: hidden;
		transform: translateY(0);
	}

	.tui-slide-image {
		width: 100%;
		height: 240rpx;
		display: block;
	}

	/* #ifdef APP-PLUS || MP */
	// .tui-banner-swiper .wx-swiper-dot {
	// 	width: 8rpx;
	// 	height: 8rpx;
	// 	display: inline-flex;
	// 	background: none;
	// 	justify-content: space-between;
	// }

	// .tui-banner-swiper .wx-swiper-dot::before {
	// 	content: '';
	// 	flex-grow: 1;
	// 	background: rgba(255, 255, 255, 0.8);
	// 	border-radius: 16rpx;
	// 	overflow: hidden;
	// }

	// .tui-banner-swiper .wx-swiper-dot-active::before {
	// 	background: #fff;
	// }

	// .tui-banner-swiper .wx-swiper-dot.wx-swiper-dot-active {
	// 	width: 16rpx;
	// }

	/* #endif */

	/* #ifdef H5 */
	>>>.tui-banner-swiper .uni-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	>>>.tui-banner-swiper .uni-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	>>>.tui-banner-swiper .uni-swiper-dot-active::before {
		background: #fff;
	}

	>>>.tui-banner-swiper .uni-swiper-dot.uni-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	.tui-product-category {
		background: #fff;
		padding: 80rpx 20rpx 0rpx 20rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		font-size: 24rpx;
		color: #555;
		margin-bottom: 20rpx;
	}

	.tui-category-item {
		width: 25%;
		height: 130rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-direction: column;
		padding: 10px 0 10px;
		margin-bottom: 5px;
	}

	.tui-category-img {
		height: 80rpx;
		width: 80rpx;
		display: block;
	}

	.tui-category-name {
		line-height: 20px;
		text-align: center;
	}

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
		padding: 0px 7px 0;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background: #f5f2f9;
		position: relative;
		border-radius: 12rpx;
	}

	.tui-new-mtop {
		margin-top: 2%;
	}

	.tui-title-box {
		width: 55%;
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

	.tui-new-img2 {
		width: 125rpx;
		height: 125rpx;
		display: block;
		flex-shrink: 0;
		border-radius: 5px;
		margin-left: 2px;
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
		border-radius: 12rpx;
		overflow: hidden;

		.pic {
			position: relative;

			.maiguang {
				position: absolute;
				top: 50%;
				left: 50%;
				z-index: 199;
				width: 100px;
				height: 100px;
				margin: -50px 0 0 -50px;
			}

			.maiguang img {
				width: 100px;
				height: 100px;
			}

			.cont-img {
				background-color: #000000;
				opacity: 0.3;
				width: 100%;
				height: 100%;
				z-index: 99;
				position: absolute;
				top: 0;
				left: 0;
			}
		}
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
		line-height: 18px;
		height: 36px;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-pro-price {
		padding-top: 18rpx;
		display: flex;
		flex-wrap: wrap;
	}

	.tui-sale-price {
		font-size: 14px;
		margin-bottom: 5px;
		font-weight: 500;
		color: #FB586A;
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
</style>
