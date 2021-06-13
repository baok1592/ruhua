<template>
	<view class="kehu">
		<view class="search">
			<view class='sear_01'>
				<uni-icon type="search" size="20" color="#A5A5A5"></uni-icon><span>搜索</span>
			</view>
			<view class="sear_02"><button class="cu-btn round bg-grey sm">搜索</button></view>
		</view>
		<scroll-view class="scroll-view_x" scroll-x style="width: auto;overflow:hidden;">
			<view class='tab'>
				<view :class="c_index==0?'xz':'bb' " @click="num(0)">全部</view>
				<block v-for="(item,index) of list">
					<view :class="c_index==(index+1)?'xz':'bb' " @click="num(index+1)">{{item.category_name}}</view>
				</block>
			</view>
		</scroll-view>
		<block v-for="(item,index) of kehu" >
			<view class="list" @click="jump_to_detail(item.id)">
				<view class="list_l"><img :src="item.pic"></img></view>
				<view class="list_r">
					<view class="list_r_01">{{item.name}} <span class="hui">会员</span></view>
					<view class="list_r_02">{{item.tell}}</view>
				</view>
			</view>
		</block>
		<view class="p_btn">
			<view class="flex flex-direction" >
				<button @click="jump_tag_manage" class="cu-btn bg-red margin-tb-sm lg">新增标签</button>
			</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				list: ['a', 'b', 'c', 'a', 'b', 'c'],
				c_index: 0,
				kehu: ''
			};
		},
		components: {
			uniIcon
		},
		onLoad() {  
			this.kehu=this.$api.json_cms.kehu
			this.list=this.$api.json_cms.kh_category
		},
		methods: {
			jump_to_detail() {
				uni.navigateTo({
					url: '../kedetail/kedetail'
				});
			},
			jump_tag_manage() {
				uni.navigateTo({
					url: '../tag_manage/tag_manage'
				});
			},
			num(index) {
				this.c_index = index
			},
			jump_xiang(id) {
				uni.navigateTo({
					url: '/pages/kedetail/kedetail?id=' + id,
				});
			}
		},
		onPullDownRefresh() {
			//this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
	.kehu {font-size: 14px;
		.search {
			background: #F4F4F4;
			display: flex;
			width: 100%;
			box-sizing: border-box;
			padding: 10px;

			.sear_01 {
				padding: 0 10px;
				height: 30px;
				border-radius: 5px;
				line-height: 30px;
				background-color: #fff;
				width: 100%;

				span {
					padding-left: 10px;
					color: #ADADAD;
				}
			}
			.sear_02{width: 80px;padding:3px 0 0 20px;}
		}

		.tab {
			padding: 10px 10%;
			display: flex;
			width: 100%;

			.bb {
				padding-bottom: 5px;
				min-width: 80px;
				text-align: center;
			}

			.xz {
				border-bottom: 2px solid red;
				padding-bottom: 5px;
				min-width: 80px;
				text-align: center;
			}
		}

		.list {
			display: flex;
			padding: 10px;
			border-bottom: 1px solid #EAEAEA;

			.list_l {
				padding: 0 10px 0 0;

				img {
					width: 50px;
					height: 50px;
					border-radius: 5px;
				}
			}

			.list_r {
				line-height: 25px;

				.list_r_01 {
					.hui {
						border: 1px solid #FF6D6D;
						border-radius: 3px;
						color: #ff6d6d;
						font-size: 12px;
						padding: 0 5px;
						margin-left: 8px;
					}
				}

				.list_r_02 {
					color: #ABABAB;
				}
			}
		}

		.p_btn {
			background: #F7F6FB;
			padding: 0 10px 0px;
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 9999;
		}

		.pro_btn {
			background-color: #E5E5E5;
			padding: 10px;
			text-align: center;
			border-radius: 20px;
			background-color: #DF421D;
			color: #fff;
			width: 94%;
		}
	}
</style>
