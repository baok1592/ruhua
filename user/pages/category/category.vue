<template>
	<view class="content">
		<scroll-view scroll-y class="left-aside">
			<view v-for="item in flist" :key="item.category_id" class="f-item b-b" :class="{active: item.category_id === currentId}"
			 @click="tabtap(item)">
				{{item.category_name}}
			</view>
		</scroll-view>

		<scroll-view scroll-with-animation scroll-y class="right-aside" @scroll="asideScroll" :scroll-top="tabScrollTop">
			<view class="s-list">
				<view class="nav-title" @click="navToList(0)">——— {{currentName}} ———</view>
				<view class="t-list">
					<view v-for="titem in slist" :key="titem.category_id" v-if="titem.pid == currentId" @click="navToList(titem.category_id)"
					 class="t-item">
						<image :src="getimg+titem.imgs"></image>
						<text>{{titem.category_name}}</text>
					</view>
				</view>
			</view>
		</scroll-view>
		<!-- #ifdef MP-WEIXIN -->
		<button class="btn1" open-type="contact">
			<image class="btnImg" src="../../static/images/kefu.png"></image>
			<!-- <view>客服</view> -->
		</button>
		<!-- #endif -->
	</view>
</template>

<script>
	export default {
		data() {
			return {
				getimg: this.$getimg,
				sizeCalcState: false,
				tabScrollTop: 0,
				currentId: 1,
				currentName: '',
				flist: [],
				slist: []
			}
		},
		onLoad() {
			this.loadData()
		},
		methods: {
			loadData() {
				uni.showLoading({
					title: '加载中'
				});
				this.flist = []
				this.slist = []
				this.tabbar = this.$api.http.get('category/all_category').then(res => {
					let list = res.data
					list.forEach(item => {
						if (!item.pid) {
							this.flist.push(item); //pid为父级id, 没有pid或者pid=0是一级分类
						} else {
							this.slist.push(item); //2级分类
						}
					})
					if (list[0]) {
						this.currentName = list[0].category_name;
						this.currentId = list[0].category_id;
					}
					uni.hideLoading();
				})
			},
			//一级分类点击
			tabtap(item) {
				if (!this.sizeCalcState) {
					this.calcSize();
				}
				this.currentName = item.category_name;
				this.currentId = item.category_id;
				let index = this.slist.findIndex(sitem => sitem.pid === item.category_id);
				this.tabScrollTop = this.slist[index].top;
			},
			//右侧栏滚动
			asideScroll(e) {
				if (!this.sizeCalcState) {
					this.calcSize();
				}
				let scrollTop = e.detail.scrollTop;
				let tabs = this.slist.filter(item => item.top <= scrollTop).reverse();
				if (tabs.length > 0) {
					this.currentId = tabs[0].pid;
				}
			},
			//计算右侧栏每个tab的高度等信息
			calcSize() {
				let h = 0;
				this.slist.forEach(item => {
					let view = uni.createSelectorQuery().select("#main-" + item.category_id);
					view.fields({
						size: true
					}, data => {
						item.top = h;
						h += data.height;
						item.bottom = h;
					}).exec();
				})
				this.sizeCalcState = true;
			},
			navToList(sid) {
				const cid = this.currentId
				uni.navigateTo({
					url: `/pages/extend-view/productList/productList?cid=${cid}&sid=${sid}`
				})
			}
		},
		onPullDownRefresh() {
			this.loadData()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 1500);
		}
	}
</script>

<style lang='scss'>
	/* #ifdef MP-WEIXIN */
	.btn1{
	  width: 60rpx;
	  height: 60rpx; 
	  font-size: 30rpx; 
	  position: fixed;
	  padding: 0px;
	  margin: 0px;
	  top:50%;
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

	.content {
		height: 100%;
		background-color: #f8f8f8;
	}

	.nav-title {
		text-align: center;
		padding: 20px 0;
	}

	.content {
		display: flex;
	}

	.left-aside {
		flex-shrink: 0;
		width: 200upx;
		height: 100%;
		background-color: #fff;
	}

	.f-item {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100upx;
		font-size: 28upx;
		color: $font-color-base;
		position: relative;

		&.active {
			color: $base-color;
			background: #f8f8f8;

			&:before {
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				height: 36upx;
				width: 8upx;
				background-color: $base-color;
				border-radius: 0 4px 4px 0;
				opacity: .8;
			}
		}
	}

	.right-aside {
		flex: 1;
		overflow: hidden;
		padding-left: 20upx;
	}

	.s-item {
		display: flex;
		align-items: center;
		height: 70upx;
		padding-top: 8upx;
		font-size: 28upx;
		color: $font-color-dark;
	}

	.s-list {
		width: 100%;
		background: #fff;
	}

	.t-list {
		display: flex;
		flex-wrap: wrap;
		width: 100%;
		background: #fff;
		padding-top: 12upx;

		&:after {
			content: '';
			flex: 99;
			height: 0;
		}
	}

	.t-item {
		flex-shrink: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		width: 176upx;
		font-size: 26upx;
		color: #666;
		padding-bottom: 20upx;

		image {
			width: 140upx;
			height: 140upx;
		}
	}
</style>
