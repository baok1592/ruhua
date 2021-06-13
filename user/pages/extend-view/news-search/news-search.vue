<template>
	<view class="container">
		<view class="tui-searchbox">
			<view class="tui-search-input">
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="search" :size='13' color='#333'></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view>
					<tui-icon name="search" :size='16' color='#333'></tui-icon>
				</view>
				<!-- #endif -->
				<input confirm-type="search" placeholder="搜索" :focus="true" auto-focus placeholder-class="tui-input-plholder" class="tui-input"
				 v-model.trim="key" />
				<!-- #ifdef APP-PLUS || MP -->
				<icon type="clear" :size='13' color='#bcbcbc' @tap="cleanKey" v-show="key"></icon>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<view @tap="cleanKey" v-show="key">
					<tui-icon name="close-fill" :size='16' color='#bcbcbc'></tui-icon>
				</view>
				<!-- #endif -->
			</view>
			<view class="tui-cancle" @tap="sub">搜索</view>
		</view>

		<view class="tui-search-history" v-if="history.length>0">
			<view class="tui-history-header">
				<view class="tui-search-title">搜索历史</view>
				<tui-icon name="delete" :size='14' color='#333' @tap="openActionSheet" class="tui-icon-delete"></tui-icon>
			</view>
			<view class="tui-history-content">
				<block v-for="(item,index) in history" :key="index">
					<tui-tag type="gray" shape="circle" @click="to_search(item)">{{item}}</tui-tag>
				</block>
			</view>
		</view>

		<view class="tui-search-hot">
			<view class="tui-hot-header">
				<view class="tui-search-title">大家正在搜</view>
			</view>
			<view class="tui-hot-content">
				<block v-for="(item,index) in hot" :key="index">
					<tui-tag type="light-blue" v-if="index%4==0"  shape="circle" @click="to_search(item)">{{item}}</tui-tag>
					<tui-tag type="light-brownish" v-if="index%4==1"  shape="circle" @click="to_search(item)">{{item}}</tui-tag>
					<tui-tag type="light-orange" v-if="index%4==2"  shape="circle" @click="to_search(item)">{{item}}</tui-tag>
					<tui-tag type="light-green" v-if="index%4==3"  shape="circle" @click="to_search(item)">{{item}}</tui-tag>
				</block>
			</view>
		</view>
		<tui-actionsheet :show="showActionSheet" :tips="tips" @click="itemClick" @cancel="closeActionSheet"></tui-actionsheet>
	</view>

</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiTag from "@/components/tag/tag"
	import tuiActionsheet from "@/components/actionsheet/actionsheet"
	export default {
		components: {
			tuiIcon,
			tuiTag,
			tuiActionsheet
		},
		data() {
			return {
				record_list: [],
				history: [],
				hot: [],
				key: "",
				showActionSheet: false,
				tips: "确认清空搜索历史吗？"
			}
		},
		onLoad() {
			this.get_record()
			this.get_hotSearch()
		},
		methods: {
			get_record() {
				let cache = uni.getStorageSync('record')
				this.record_list = cache ? cache : this.record_list
				this.history = cache
			},
			get_hotSearch() {
				let cache = uni.getStorageSync('hotSearch')
				this.hot = cache
			},
			sub() {
				const that = this
				this.record_list.push(this.key)
				if (this.record_list.length > 9) {
					this.record_list.splice(0, 1)
				}
				//数组去重
				let arr = that.record_list
				let arrs = arr.filter(function(ele, index, self) {
					return self.indexOf(ele) === index;
				})
				//数组去重结束赋值
				that.record_list = arrs
				uni.setStorageSync('record', this.record_list)
				uni.redirectTo({
					url: '../productList/productList?key=' + this.key
				})
			},
			to_search(item) {
				this.key = item
				this.sub()
			},
			back: function() {
				uni.navigateBack();
			},
			cleanKey: function() {
				this.key = ''
			},
			closeActionSheet: function() {
				this.showActionSheet = false
			},
			openActionSheet: function() {
				this.showActionSheet = true
			},
			itemClick: function(e) {
				let index = e.index;
				if (index == 0) {
					this.showActionSheet = false;
					this.history = []
					this.record_list = []
					uni.removeStorageSync('record')
				}
			}
		}
	}
</script>

<style>
	page {
		color: #333;
		background: #fff;
	}

	.container {
		padding: 0 30upx 30upx 30upx;
		box-sizing: border-box;
	}

	.tui-searchbox {
		padding: 30upx 0;
		box-sizing: border-box;
		display: flex;
		align-items: center;
	}

	.tui-search-input {
		width: 100%;
		height: 66upx;
		border-radius: 35upx;
		padding: 0 30upx;
		box-sizing: border-box;
		background: #f2f2f2;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
	}

	.tui-input {
		flex: 1;
		color: #333;
		padding: 0 16upx;
		font-size: 28upx;
	}

	.tui-input-plholder {
		font-size: 28upx;
		color: #b2b2b2;
	}

	.tui-cancle {
		color: #888;
		font-size: 28upx;
		padding-left: 30upx;
		flex-shrink: 0;
	}

	.tui-history-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30upx 0;
	}

	.tui-icon-delete {
		padding: 10upx;
	}

	.tui-search-title {
		font-size: 28upx;
		font-weight: bold;
	}

	.tui-hot-header {
		padding: 30upx 0;
	}

	.tui-tag-class {
		display: inline-block;
		margin-bottom: 20upx;
		margin-right: 20upx;
		font-size: 26upx !important;
	}
</style>
