<template>
	<view class="kedetail">
		<view class="head">
			<view class="head_l">
				<img src="@/imgs/8.jpg"></img>
				<view class="sex" v-if="sex"><img src="@/imgs/nan.png"></img></view>
				<view class="sex" v-if="!sex"><img src="@/imgs/nv.png"></img></view>
			</view>
			<view class="head_r">
				<view class="head_r_01">名字<span>VIP</span></view>
				<view class="head_r_02"><span>微信粉丝</span><span>购买用户</span></view>
			</view>
		</view>
		<view class="BH6"></view>
		<view class="jytj">
			<view class="jytj_tit">交易统计</view>
			<view class="con">
				<view class="con_01">
					<view class="con_01_1">累计消费金额（元）</view>
					<view class="con_01_2">0</view>
				</view>
				<view class="con_01">
					<view class="con_01_1">可用优惠券/码（张）</view>
					<view class="con_01_2">0<span><uni-icon type="arrowright" size="15" color="#A5A5A5"></uni-icon></span></view>
				</view>
				<view class="con_01">
					<view class="con_01_1">累计下单数</view>
					<view class="con_01_2">0<span><uni-icon type="arrowright" size="15" color="#A5A5A5"></uni-icon></span></view>
				</view>
				<view class="con_01">
					<view class="con_01_1">权益卡（张）</view>
					<view class="con_01_2">0<span><uni-icon type="arrowright" size="18" color="#A5A5A5"></uni-icon></span></view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"  
	export default {
		data() {
			return {
				sex:false
			};
		},
		components: {
			uniIcon 
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
.kedetail{font-size: 14px;
	.head{display: flex;margin: 10px;padding:10px 5px;
		.head_l{position: relative;
			img{width: 55px;height:55px;border-radius: 50%;}
			.sex{position: absolute;right: 0;bottom: 0;
				img{width: 20px;height: 20px;}
			}
		}
		.head_r{padding-left: 10px;
			.head_r_01{font-size: 16px;font-weight: 600;padding:5px 0 15px;
				span{background-color: #FAAB0C;color: #fff;font-weight: 100;font-size: 14px;padding:2px 3px;border-radius: 3px;
				margin-left: 5px;}
			}
			.head_r_02{
				span{background-color: #F2F3F5;border-radius: 3px;color: #D0D0D3;font-size: 12px;margin-right: 5px;padding: 2px 3px;}
			}
		}
	}
	.BH6{height: 6px;background-color: #FAFAFA;}
	.jytj{padding: 30px 10px 15px;
		.jytj_tit{font-size: 18px;font-weight: 600;padding-bottom: 20px;}
		.con{display: flex;flex-wrap: wrap;justify-content: space-between;;
			.con_01{width: 48%;padding: 10px 0 20px;box-sizing: border-box;
				.con_01_1{color: #A4A5A7;}
				.con_01_2{font-size: 18px;font-weight: 600;padding-top: 20px;line-height: 20px;
					span{padding-left: 10px;}
				}
			}
		}
	}
}
</style>
