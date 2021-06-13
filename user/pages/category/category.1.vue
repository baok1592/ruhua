<template>
	<view class="container">
		<scroll-view scroll-y scroll-with-animation class="tab-view" :scroll-top="scrollTop" :style="{height:height+'px'}">
			<view v-for="(item,index) in tabbar" :key="index" class="tab-bar-item" :class="[currentTab==index ? 'active' : '']"
			 :data-current="index" @tap.stop="swichNav">
				<text @click="swichNav1(item.category_id)">{{item.category_name}}</text>
			</view>
		</scroll-view>
		<block v-for="(item,index) in tabbar" :key="index">
			<scroll-view scroll-y class="right-box" :style="{height:height+'px'}" v-if="currentTab==index">
				<!--内容部分 start 自定义可删除-->
				<view class="page-view">
					<!-- <swiper indicator-dots autoplay circular :interval="5000" :duration="150" class="swiper">
						<swiper-item v-if="index%2===0" @tap.stop="detail">
							<image src="../../static/images/mall/banner/2.jpg" class="slide-image" />
						</swiper-item>
						<swiper-item @tap.stop="detail">
							<image src="../../static/images/mall/banner/4.jpg" class="slide-image" />
						</swiper-item>
						<swiper-item @tap.stop="detail">
							<image src="../../static/images/mall/banner/5.jpg" class="slide-image" />
						</swiper-item>
					</swiper> -->
					<view class="class-box">
						<view class="class-item">
							<view class="class-name">{{item.category_name}}</view>
							<view class="g-container11">
								<view class="g-container">
									<view class="g-box" @click="productList(itemcate.pid,itemcate.category_name)" v-for="(itemcate,index) of cateList" :key="index" >
										<image class="g-image" :src="getimg + itemcate.imgs"></image>
										<view class="g-title">{{itemcate.category_name}}</view>
									</view>
								</view>
							</view>
						</view>
					</view>
				</view>
				<!--内容部分 end 自定义可删除-->
			</scroll-view>
		</block>
	</view>

</template>

<script>
	export default {
		data() {
			return {
				getimg:this.$getimg,
				tabbar: [],
				cateList: [],
				cid: 0,
				height: 0, //scroll-view高度
				currentTab: 0, //预设当前项的值
				scrollTop: 0 //tab标题的滚动条位置
			}
		},
		onLoad: function(options) {
			this._load()
			
			uni.getSystemInfo({
				success: (res) => {
					let header = 92;
					// #ifdef H5
					header = 0;
					// #endif
					this.height = res.windowHeight - uni.upx2px(header)
				}
			});

		},
		methods: {
			async _load() {
				this.tabbar = await this.$api.http.get('category/get_category?id=1').then(res => {
					return res.data
				})
				if(this.tabbar && this.tabbar[0]){
					console.log('aa')
					const cid=this.tabbar[0].category_id
					this.$api.http.get('category/category_cid',{id:cid}).then(res => {
						this.cateList = res.data.category
						console.log(this.cateList) 
					})
				}				
				console.log('xx',this.tabbar)
			},
			swichNav1(id) {
				// this.cid = id
				this.$api.http.get('category/category_cid',{id:id}).then(res => {
					this.cateList = res.data.category
					console.log(this.cateList) 
				})
			},

			// 点击标题切换当前页时改变样式
			swichNav: function(e) {
				let cur = e.currentTarget.dataset.current;
				if (this.currentTab == cur) {
					return false;
				} else {
					this.currentTab = cur;
					this.checkCor();
				}
			},
			// 判断当前滚动超过一屏时，设置tab标题滚动条。
			checkCor: function() {
				let that = this;
				//这里计算按照实际情况进行修改，动态数据要进行动态分析
				//思路：窗体高度/单个分类高度 200rpx 转px计算 =>得到一屏幕所显示的个数，结合后台传回分类总数进行计算
				//数据很多可以多次if判断然后进行滚动距离计算即可
				if (that.currentTab > 7) {
					that.scrollTop = 500
				} else {
					that.scrollTop = 0
				}
			},
			detail(e) {
				uni.navigateTo({
					url: '../extend-view/productDetail/productDetail'
				})
			},
			productList(id,name) {
				uni.navigateTo({
					url: '../extend-view/productList/productList?pid=' + id + '&searchkey=' + name
				})
			},
			search: function() {
				uni.navigateTo({
					url: '../extend-view/news-search/news-search'
				})
			}
		},
		onShareAppMessage(res) { 
			return {
				title: '如花',
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

<style>
	page {
		background: #fcfcfc;
	}

	/* 左侧导航布局 start*/

	/* 隐藏scroll-view滚动条*/

	::-webkit-scrollbar {
		width: 0;
		height: 0;
		color: transparent;
	}

	.tui-searchbox {
		width: 100%;
		height: 92upx;
		padding: 0 30upx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		left: 0;
		top: 0;
		z-index: 100;
	}

	.tui-searchbox::after {
		content: '';
		position: absolute;
		border-bottom: 1upx solid #d2d2d2;
		-webkit-transform: scaleY(0.5);
		transform: scaleY(0.5);
		bottom: 0;
		right: 0;
		left: 0;
	}

	.tui-search-input {
		width: 100%;
		height: 60upx;
		background: #f1f1f1;
		border-radius: 30upx;
		font-size: 26upx;
		color: #999;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.tui-search-text {
		padding-left: 16upx;
	}

	.tab-view {
		/* height: 100%; */
		width: 200upx;
		position: fixed;
		left: 0;
		top: 92upx;
		z-index: 10;
	}

	.tab-bar-item {
		width: 200upx;
		height: 110upx;
		background: #f6f6f6;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 26upx;
		color: #444;
		font-weight: 400;
	}

	.active {
		position: relative;
		color: #000;
		font-size: 30upx;
		font-weight: 600;
		background: #fcfcfc;
	}

	.active::before {
		content: "";
		position: absolute;
		border-left: 8upx solid #E41F19;
		height: 30upx;
		left: 0;
	}

	/* 左侧导航布局 end*/

	.right-box {
		width: 100%;
		position: fixed;
		padding-left: 220upx;
		box-sizing: border-box;
		left: 0;
		top: 92upx;
	}

	.page-view {
		width: 100%;
		overflow: hidden;
		padding-top: 20upx;
		padding-right: 20upx;
		box-sizing: border-box;
		padding-bottom: env(safe-area-inset-bottom);
	}

	.swiper {
		width: 100%;
		height: 220upx;
		border-radius: 12upx;
		overflow: hidden;
		transform: translateZ(0);
	}

	/* #ifdef APP-PLUS || MP */
	.swiper .wx-swiper-dot {
		width: 8upx;
		height: 8upx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	.swiper .wx-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16upx;
		overflow: hidden;
	}

	.swiper .wx-swiper-dot-active::before {
		background: #fff;
	}

	.swiper .wx-swiper-dot.wx-swiper-dot-active {
		width: 16upx;
	}

	/* #endif */

	/* #ifdef H5 */
	>>>.swiper .uni-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	>>>.swiper .uni-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	>>>.swiper .uni-swiper-dot-active::before {
		background: #fff;
	}

	>>>.swiper .uni-swiper-dot.uni-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	.slide-image {
		width: 100%;
		height: 220upx;
	}

	.class-box {
		padding-top: 30upx;
	}

	.class-item {
		background: #fff;
		width: 100%;
		box-sizing: border-box;
		padding: 20upx;
		margin-bottom: 20upx;
		border-radius: 12upx;
	}

	.class-name {
		font-size: 22upx;
	}

	.g-container11 {}

	.g-container {
		/* padding-top: 20upx; */
		display: flex;
		display: -webkit-flex;
		justify-content: flex-start;
		flex-direction: row;
		flex-wrap: wrap;
	}



	.g-box {
		width: 40.3333%;
		text-align: center;
		padding-top: 40upx;
	}

	.g-image {
		width: 120upx;
		height: 120upx;
	}

	.g-title {
		font-size: 22upx;
	}
</style>
