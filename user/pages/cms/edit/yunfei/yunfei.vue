<template>
	<view class="yunfei">
		<view class="con">
			<view class="set">
				<view class="set_l">
					<radio :class="radio=='A'?'checked':''" :checked="radio=='A'?true:false" value="A"></radio>
				</view>
				<view class="set_m">
					<view class="set_m_01">统一运费（0元）</view>
					<view class="set_m_02">未有商品使用</view>
				</view>
				<view class="set_r">
					<navigator url="/pages/edit/yfset/yfset">
						<uni-icon type="compose" size="30" color="#669CE7"></uni-icon>						
					</navigator>

				</view>
			</view>
			<view class="set" v-for="(item,index) of list">
				<view class="set_l">
					<radio :class="radio=='A'?'checked':''" :checked="radio=='A'?true:false" value="A"></radio>
				</view>
				<view class="set_m">
					<view class="set_m_01">{{item.num}}</view>
					<view class="set_m_02">{{item.mess}}</view>
				</view>
				<view class="set_r">
					<navigator url="/pages/edit/yfmoban/yfmoban">
						<uni-icon type="info" size="25" color="#1A6AE2"></uni-icon>
					</navigator>
				</view>
			</view>
		</view>
		<view class="ti">需要创建更多运费模板，请登录“youzan.com”通过“订单--->物流工具”新建</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				list:''
			};
		},
		components: {uniIcon},
		onLoad() {  
			this.list=this.$api.json.yunfei
		}
	}
</script>

<style lang="scss">
.yunfei{background-color: #FAFAFA;min-height: 100vh;font-size: 15px;padding-top: 1px;
	.con{margin: 10px 0;border-top: 1px solid #F0F0F0;
		.set{background-color: #fff;padding: 15px 10px;display: flex;border-bottom: 1px solid #EEEEEE;
			.set_l{padding-right: 10px;display: flex;flex-direction: column;justify-content: center;}
			.set_m{flex-grow: 1;
				.set_m_01{padding-bottom: 10px;}
				.set_m_02{color: #A1A1A1;}
			}
			.set_r{padding-left: 10px;display: flex;flex-direction: column;justify-content: center;}
		}
	}
	.ti{padding: 10px;line-height: 18px;}
}
</style>
