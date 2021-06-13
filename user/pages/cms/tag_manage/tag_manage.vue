<template>
	<view>
		<block v-if="tag_show == 1">
			<view class="example-title">标签管理</view>
			<uni-list>
				<block v-for="(item,index) of tagList" :key="index">
					<uni-list-item :title="item.title" badge-type="none" show-badge="true" badge-text="12人" @click="add_tag" />
				</block>
			</uni-list>
			<view class="p_btn">
				<view class="flex flex-direction" >
					<button @click="add_tag" class="cu-btn bg-red margin-tb-sm lg">添加</button>
				</view>
			</view>
		</block>
		<block v-if="tag_show == 0">
			<view class="biao">
				<view class="biao_01">
					<view class="biao_01_l" > 标签名称：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="tag_title"/>
					</view>
				</view>
			</view>
			<view style="margin-left: 20px;margin-top: 20px;color: #5E5F61;">自动打标签条件</view>
			<view class="biao">
				<view class="biao_01">
					<view class="biao_01_l"> 累计成交笔数：</view>
					<view class="biao_01_r">
						<input class="uni-input"/>
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">累计成交金额：</view>
					<view class="biao_01_r">
						<input class="uni-input" />
					</view>
				</view>
			</view>
			<view class="p_btn">
				<view class="flex flex-direction" >
					<button @click="sub_add" class="cu-btn bg-red margin-tb-sm lg">保存</button>
				</view>
			</view>
		</block>

	</view>
</template>

<script>
	import uniList from '@/components/uni/uni-list/uni-list.vue'
	import uniListItem from '@/components/uni/uni-list-item/uni-list-item.vue'
	export default {
		components: {
			uniList,
			uniListItem
		},
		data() {
			return {
				tagList:[{
					title:'aa'
				}],
				tag_show: 1,
				id:'0',
				new_tag:{
					id:'',
					title:'',
				},
				tag_title:''
			}
		},
		methods: {
			//----------------------------------------------------添加标签
			add_tag() {
				this.tag_show = 0
			},
			sub_add(){
				if(!this.tag_title){
					this.$api.msg('标签名不能为空')
					return;
				}
				let id = this.id
				let new_tag = {
					id:id++,
					title:this.tag_title
				}
				this.tagList.push(new_tag)
				this.tag_title = ''
				this.id = id
				this.tag_show = 1
				this.$api.msg('添加成功')
				console.log(this.tagList)
			},
		},
		onPullDownRefresh() {
		
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style>
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4
	}

	view {
		font-size: 28upx;
		line-height: inherit
	}
	.biao {
		background-color: #fff;
		margin: 10px;
		border-radius: 5px;
		border: 1px solid #EAEAEA;
	}
	.biao_01 {
		padding: 10px 10px 10px;
		border-bottom: 1px solid #EDEDED;
		display: flex;
	}
	.biao_01_l {
		padding-top: 7px;
		flex-shrink: 0;
	}
	.biao_01_r {
		flex-grow: 1;
	}
	.uni-input-input,
	.uni-input {
		height: 30px;
		line-height: 30px;
	}

	.example {
		padding: 0 30upx 30upx
	}

	.example-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 32upx;
		color: #464e52;
		padding: 30upx 30upx 30upx 50upx;
		margin-top: 20upx;
		position: relative;
		background-color: #fdfdfd;
		border-bottom: 1px #f5f5f5 solid
	}

	.example-title__after {
		position: relative;
		color: #031e3c
	}

	.example-title:after {
		content: '';
		position: absolute;
		left: 30upx;
		margin: auto;
		top: 0;
		bottom: 0;
		width: 6upx;
		height: 32upx;
		background-color: #ccc
	}

	.example .example-title {
		margin: 40upx 0
	}

	.example-body {
		padding: 30upx;
		background: #fff
	}

	.example-info {
		padding: 30upx;
		color: #3b4144;
		background: #fff
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
</style>
