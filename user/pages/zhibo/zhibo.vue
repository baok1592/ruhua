<template>
	<view class="zhibo">
		<view class="head"></view> 
		<view class="zbo">
			<view class="zb" @click="jump_detail(item.id)" v-for="(item,index) of list" :key="index">
				<view class="zb_l">
					<img :src="web_url+'/static/web/pkq.gif'"></img>
					<view class="zbz">
						<view class="zbz_01">视频购</view>
					</view>
					<view class="guank">122人观看</view>
					<view class="rhm">
						<view class="rhm_l"><img src="@/imgs/8.jpg"></img></view>
						<view class="rhm_r">名字</view>
					</view>
				</view>
				<view class="zb_r">
					<view class="zb_r_tit">生活不将就 护肤品用对不用贵</view>
					<view class="zb_r_des">超多好货，买就送现金抵用券</view>
					<view class="zb_r_more">
						<view class="zb_r_more_l" >
							<img src="@/imgs/9.jpg"></img>
							<view class="zb_r_more_l_pri">¥29.9</view>
						</view>
						<view class="zb_r_more_l" >
							<img src="@/imgs/zhi.jpg"></img>
							<view class="zb_r_more_l_pri">¥29.9</view>
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- <view class="title">
			<view class=""><img src="@/imgs/yu.png"></img></view>直播预告
		</view>
		<view class="yugao">
			<scroll-view class="scroll-view_x" scroll-x style="">
				<block  v-for="(item,index) of list" :key="index">
					
					<view class="yg">
						<view class="yg_01">
							<view :class="index!=0?'yg_01_1':'kong'" v-if=""></view>
							<view class="yg_01_2">今天 13:30</view>
							<view class="yg_01_1"></view>
						</view>
						<view class="yg_02">
							<view class="yg_02_01"><img src="@/imgs/zhi.jpg"></img></view>
							<view class="yg_02_02">生活不将就</view>
							<view class="yg_02_03">生活不将就</view>
							<view class="yg_02_04">开播提醒</view>
						</view>
					</view>
				</block>
			</scroll-view>
		</view>	
		<view class="title">
			<view class=""><img src="@/imgs/hui.png"></img></view>精彩回放
		</view>
		<view class="playback">
			<view class="pb" v-for="(item,index) of list" :key="index">
				<img src="@/imgs/zhi.jpg"></img>
				<view class="zbz">
					<view class="zbz_01">回放</view>
				</view>
				<view class="guank">122人观看</view>
				<view class="rhm">
					<view class="rhm_l"><img src="@/imgs/8.jpg"></img></view>
					<view class="rhm_r">名字</view>
				</view>
				<view class="pb_tit">工厂直销，888</view>
			</view>
		</view>
		 -->
	</view>
</template>

<script>
	import {
		Api_url
	} from '@/common/config'
	export default {
		data() {
			return {
				list:[{
					id:1
				},{
					id:3
				},{
					id:5
				}]
			};
		},
		onLoad(){
			this.web_url=Api_url 
		},
		methods:{
			jump_detail(id){
				// #ifdef MP-WEIXIN
					uni.navigateTo({
						url:"./detail"
					})
				// #endif
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
.zhibo{
	.head{position: absolute;top: 0;left: 0;background-color: #FD3830;height: 150px;border-radius: 0 0 10px 10px;width: 100%;z-index: -1;}
	.zbo{padding:30px 10px 10px;
		.zb{display: flex;background-color:#fff;border-radius:5px;margin-bottom: 15px;box-shadow: 2px 0px 6px #F0F0F0;height: 180px;
			.zb_l{position: relative;width: 180px;
				img{width: 180px;height: 180px;border-radius: 5px;}
				.guank{position: absolute;top: 0;left: 0;background-color: rgba($color: #000000, $alpha: 0.5);color: #EBDCD9;
				font-size: 12px;padding: 2px 4px 2px 55px;border-radius: 8px;}
				.zbz{position: absolute;top: 0;left: 0;background-color: #FF1B2C;color: #E8DBCE;font-size: 10px;z-index: 99;
				padding: 2px 8px 2px 2px;border-radius:5px 8px 8px 0;}
				.rhm{position: absolute;bottom: 5px;left: 10px;display: flex;color: #fff;line-height: 20px;font-size: 12px;
					img{width: 20px;height: 20px;border-radius: 50%;margin-right: 5px;}
					.rhm_r{line-height: 20px;height: 20px;overflow: hidden;padding-right: 15px;}
				}
			}
			.zb_r{padding: 6px 8px 6px 8px;flex-grow: 1;
				.zb_r_tit{font-size: 15px;font-weight: 600;line-height: 25px;max-height: 50px;overflow: hidden;
				text-overflow: ellipsis;width: 95%;}
				.zb_r_des{color: #A7A7A7;font-size: 12px;line-height: 20px;height: 20px;overflow: hidden;
				width: 95%;margin:5px 0 10px;}
				.zb_r_more{display: flex;justify-content: space-between;width: 95%;
					.zb_r_more_l{width: 44%;position:relative;padding-top: 5px;
						img{width: 70px;height: 70px;border-radius: 5px;}
						.zb_r_more_l_pri{position: absolute;bottom: 0;height: 20px;line-height: 20px;text-align: center;
						width: 70px;border-radius: 0 0 5px 5px;color: #fff;font-size: 12px;
						 background:linear-gradient(to bottom,rgba(248,247,247,0),rgba(129,129,129,1));}
					}
				}
			}
		}
	}
	
	.title{display: flex;justify-content: center;font-size: 16px;font-weight: 600;padding: 10px;
		img{width: 20px;height: 20px;margin-right: 10px;}
	}
	.yugao{width: 100%;overflow: hidden;white-space: nowrap;margin-left: 10px;
		.yg{width: 40vw;margin:0 0 30px 0px;display: inline-block;text-align: center;
			.yg_01{display: flex;margin-bottom: 10px;
				.yg_01_1{height: 12px;border-bottom: 2px dotted #BCBCBC;flex-grow: 1;}
				.kong{flex-grow: 1;}
				.yg_01_2{width: 100px;text-align: center;line-height: 20px;height: 20px;background-color: #F3F1F2;font-size: 12px;
				border-radius: 10px;}
			}
			.yg_02{background-color: #fff;box-shadow: 2px 0px 6px #F0F0F0;border-radius: 5px;padding: 0 0 20px 0px;
			position: relative;width: 36vw;
				.yg_02_01 img{width: 36vw;height: 36vw;border-radius: 5px;}
				.yg_02_02{text-align: center;font-weight: 600;height: 20px;line-height: 20px;overflow: hidden;margin-top: 5px;}
				.yg_02_03{text-align: center;font-size: 12px;color: #A9A9A9;height: 20px;line-height: 20px;overflow: hidden;}
				.yg_02_04{position: absolute;bottom: -10px;left: 50%;background: linear-gradient(to right, #FB9B0A, #FB7608);color: #FADDB0;
				padding: 4px 0;border-radius: 20px;font-size: 13px;width: 80px;text-align: center;margin-left: -40px;}
			}
		}
	}
	.playback{display: flex;flex-wrap: wrap;
		.pb{background-color:#fff;border-radius:5px;margin:0 0 15px 3%;box-shadow: 2px 0px 6px #F0F0F0;position: relative;
		width: 46vw;
			img{width:46vw ;height: 46vw;border-radius:5px;}
			.guank{position: absolute;top: 0;left: 0;background-color: rgba($color: #000000, $alpha: 0.5);color: #EBDCD9;
			font-size: 12px;padding: 2px 4px 2px 40px;border-radius: 8px;}
			.zbz{position: absolute;top: 0;left: 0;background-color: #FC466A;color: #E8DBCE;font-size: 10px;z-index: 99;
			padding: 2px 8px 2px 2px;border-radius:5px 8px 8px 0;}
			.rhm{position: absolute;bottom: 35px;left: 10px;display: flex;color: #F1EADE;line-height: 20px;font-size: 12px;
				img{width: 20px;height: 20px;border-radius: 50%;margin-right: 5px;}
				.rhm_r{line-height: 20px;height: 20px;overflow: hidden;padding-right: 15px;}
			}
			.pb_tit{font-weight: 600;text-align: center;height: 30px;line-height: 30px;padding: 0 10px;overflow: hidden;}
		}
	}
}
</style>
