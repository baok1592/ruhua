<template>
	<view>
		<None v-if="list_empty"></None>
		<view class="notice-item" v-for="item of event_list" v-else>
			<text class="time">{{item.create_time}}</text>
			<view class="content" @click="jump_article(item.id)">
				<text class="title">{{item.title}}</text>
				<view class="img-wrapper">
					
					<image class="pic" :src="get_img+item.img.url"></image>
				</view>
				<text class="introduce">
					<rich-text :nodes="item.content"></rich-text>
				</text>
				<view class="bot b-t">
					<text>查看详情</text>
					<text class="more-icon yticon icon-you"></text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				event_list:'',
				list_empty: false,
				get_img:this.$getimg
			}
		},
		components:{
			None
		},
		onLoad() {
			this.get_event()
		},
		methods: {
			get_event(){
				this.$api.http.get('article/type_article?type=4').then(res=>{
					
					if (res.data=='') {
						this.list_empty = true
					} else {
						this.event_list = res.data
					}
				})
			},
			jump_article(id){
				uni.navigateTo({
					url: '/pages/article/article?id='+id
				});
			}
		},
		onPullDownRefresh() {
			this.get_event()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang='scss'>
	page {
		background-color: #f7f7f7;
		padding-bottom: 30upx;
	}

	.notice-item {
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.time {
		display: flex;
		align-items: center;
		justify-content: center;
		height: 80upx;
		padding-top: 10upx;
		font-size: 26upx;
		color: #7d7d7d;
	}

	.content {
		width: 710upx;
		padding: 0 24upx;
		background-color: #fff;
		border-radius: 4upx;
	}

	.title {
		display: flex;
		align-items: center;
		height: 90upx;
		font-size: 32upx;
		color: #303133;
	}

	.img-wrapper {
		width: 100%;
		height: 260upx;
		position: relative;
	}

	.pic {
		display: block;
		width: 100%;
		height: 100%;
		border-radius: 6upx;
	}

	.cover {
		display: flex;
		justify-content: center;
		align-items: center;
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, .5);
		font-size: 36upx;
		color: #fff;
	}

	.introduce {
		display: inline-block;
		padding: 16upx 0;
		font-size: 28upx;
		color: #606266;
		line-height: 38upx;
	}

	.bot {
		display: flex;
		align-items: center;
		justify-content: space-between;
		height: 80upx;
		font-size: 24upx;
		color: #707070;
		position: relative;
	}

	.more-icon {
		font-size: 32upx;
	}
</style>
