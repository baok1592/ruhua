<template>
	<view class="yfmoban">
		<view class="list">
			<view class="list_l">模板名称</view>
			<view class="list_r">{{yfmoban.name}}</view>
		</view>
		<view class="list">
			<view class="list_l">使用范围</view>
			<view class="list_r">{{yfmoban.fanwei}}</view>
		</view>
		<view class="fan" v-for="(item,index) of yfmoban.quyu" :key="index">
			<view class="fan_01">可配送区域</view>
			<view class="fan_02">
				{{item.province}}
			</view>
			<view class="fan_03">
				<view class="fan_01_01">
					<view class="fan_01_01_l">首件</view>
					<view class="fan_01_01_r">{{item.shou}}<span>件</span></view>
				</view>
				<view class="fan_01_01">
					<view class="fan_01_01_l">运费</view>
					<view class="fan_01_01_r">{{item.s_price}} <span>元</span></view>
				</view>
				<view class="fan_01_01">
					<view class="fan_01_01_l">续件</view>
					<view class="fan_01_01_r">{{item.xu}} <span>件</span></view>
				</view>
				<view class="fan_01_01">
					<view class="fan_01_01_l">续费</view>
					<view class="fan_01_01_r">{{item.x_price}} <span>元</span></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				list:[1,2,3],
				yfmoban:''
			};
		},
		onLoad() {  
			this.yfmoban=this.$api.json.yfmoban
		}
	}
</script>

<style lang="scss">
.yfmoban{background-color: #F3F4F6;min-height: 100vh;padding-top: 1px;font-size: 16px;
	.list{background-color: #fff;margin: 20px 0 5px;padding:19px 15px;display: flex;justify-content: space-between;border-bottom: 1px solid #EDEDEF;
	border-top: 1px solid #EDEDEF;
		.list_r{color: #777777;}
	}
	.fan{margin: 15px 0 10px;background-color: #fff;padding: 0 15px;line-height: 40px;
		.fan_01{}
		.fan_02{border-top: 1px solid #F5F5F5;color: #9A9A9A;}
		.fan_03{display: flex;flex-wrap: wrap;justify-content: space-between;
			.fan_01_01{width: 50%;display: flex;justify-content: space-between;box-sizing: border-box;
			border-top: 1px solid #F5F5F5;
			 span{color: #9C9C9C;padding:0 10px 0 5px;}
			}
		}
	}
}
</style>
