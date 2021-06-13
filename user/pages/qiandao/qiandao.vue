<template>
	<view class="three" > 
		<view class="top" :style="'width: 100%;height: 200px;background: url('+web_url+'/static/web/asd.jpg) no-repeat 100% 100%;background-size: 100% 100%;'">
			  <view class="top_01"><!-- <span>积分等级</span> --></view>  
			<view class="top_02">
				<view class="top_02_01">积分：</view>
				<view><span class="top_02_s">{{my_point?my_point:0}}</span> &nbsp;</view>
				<view class="top_02_03">兑换 <view class="top_02_03_i"></view></view> 
			</view>
			<view class="top_03"  @click="sign"><button>签到领积分</button></view>
		</view>
		<view class='qdjl'>
			<view class="qdjl_tit">已签到7天</view>
			<view class='qdjl_01'>
				<view class='qdjl_01_1' v-for="(item,index) of qiandao" :key="index">
				  <view class='qdjl_01_1_01' v-if="item.qd">{{item.num}}</view>
				  <view class='qdjl_01_1_03' v-else><uni-icon type="checkmarkempty" size="30" color="#FA7077" ></uni-icon></view>
				  <view class='qdjl_01_1_02'>{{index+1}}</view>
				</view>  
			</view>
		</view>
		<view class="jfdh" >
			<view class="jfdh_01"  @click="toast">
				<view><uni-icon type="star" size="35" ></uni-icon></view>
				积分兑换
				</view>
			<view class="jfdh_01"  @click="toast">
				<view><uni-icon type="star" size="35" ></uni-icon></view>
				积分抽奖
			</view> 
		</view>
		<view class="zjf">
			<view class="zjf_tit"><uni-icon type="star" ></uni-icon> <span>赚积分</span><uni-icon type="star" ></uni-icon></view>
			<view class='rw'>
			  <view class='rw_l'>
			    <view class='rw_l_01'><uni-icon type="chat" size="18" color="#fff"></uni-icon></view>
			    <view class='rw_l_02'>
			      <view class='rw_l_02_1'>分享商品 <span>+1积分</span></view>
			      <view class='rw_l_02_2'>每天可做5次</view>
			    </view>
			  </view>
			  <view class='rw_r'  @click="toast">做任务</view>
			</view>
			<view class='rw'>
			  <view class='rw_l'>
			    <view class='rw_l_01'><uni-icon type="chat" size="18" color="#fff"></uni-icon></view>
			    <view class='rw_l_02'>
			      <view class='rw_l_02_1'>绑定公众号 <span>+5积分</span></view>
			      <view class='rw_l_02_2'>一次性任务</view>
			    </view>
			  </view>
			  <view class='rw_r' @click="toast">做任务</view>
			</view>
		</view>
	</view>
</template>

<script>	 
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import { Api_url } from '@/common/config'
	export default {
		data() {
			return {
				list:[],
				my_point:0,
				qiandao:[1,2,3,4,5,6,7]
			};
		},
		components: {
			uniIcon,
		},
		onLoad(){
			this.web_url=Api_url 
			this.load()
		},
		methods:{
			_load(){  
				this.$api.http.get('points/user/get_points').then(res=>{
					this.my_point = res.data
				})
			},  
			toast(){
				this.$api.msg('完善中')
			},
			sign(){
				this.$api.http.get('points/sign').then(res=>{
					if(res.status == 400){
						this.$api.msg(res.msg)
					}else{
						this.$api.msg('签到成功')
						this._load()
					}
				})
			},
		},
		mounted() {
			this._load()
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">  
.three{
	background-color: #F4F4F4;height: 100vh;font-size: 14px;
	.top{width: 100%;height: 200px;margin-bottom: 110px;}
	.top_01{text-align: right;padding: 30px 25px 0 0 ;}
	.top_01 span{background-color: #FDD4B7;color: #FB392C;font-weight: bold;border-radius: 10px;padding: 3px 10px;}
	.top_02{text-align: center;padding: 10px 0 3px;color: #FC2318;display: flex;justify-content: center;}
	.top_02_01{padding-top: 20px;}
	.top_02_s{font-size:35px;padding-right: 5px;}
	.top_02_03{background-color: #FD2C23;color: #FDE7EA;padding: 0 10px;border-radius: 10px;font-size: 12px;height: 22px;line-height: 22px;margin-top: 16px;display: flex;}
	.top_02_03_i{padding: 1px 0 0 2px;}
	
	
	//微信小程序
	// #ifdef  MP-WEIXIN
	.top_03{display: flex;justify-content: center;margin-top: 32px;}
	// #endif
	//除了微信小程序
	// #ifndef MP-WEIXIN
	.top_03{display: flex;justify-content: center;margin-top: 32px;}
	// #endif
	
	button::after{border: none;}
	.top_03 button{border: none;width: 204px;height: 14px;line-height: 14px;border-radius: 20px;background: transparent;color: #FD4927;font-weight: bold;font-size: 15px;}
    .jfdh{background-color: #FFFFFF;width: 94%;box-sizing: border-box;padding: 20px 10px;border-radius: 8px;margin:0 10px 10px;display: flex;justify-content: space-around; }
    .jfdh_01{text-align: center;width: 30%;}
	.qdjl{background-color: #FFFFFF;position: absolute;top: 180px;left: 10px;width: 94%;box-sizing: border-box;padding: 20px 10px;border-radius: 8px;}
	.qdjl_tit{font-weight: bold;padding: 0 0 20px;}
	.qdjl_01{display: flex;}
	.qdjl_01_1{width: 14%;text-align: center;}
	.qdjl_01_1_01{font-size: 12px;height: 20px;margin:-5px 0 10px 10px;background-color: #D8A47D;color: #fff;
	width: 30px;height: 20px;line-height: 20px;border-radius: 20px;}
	.qdjl_01_1_03{margin-top: -10px;padding-bottom: 5px;}
	.qdjl_01_2{width: 14%;text-align: center;font-size: 12px;}
	.qdjl_01_2_01{padding-bottom: 6px;}
	.zjf{background-color: #FFFFFF;width: 94%;box-sizing: border-box;padding: 10px;border-radius: 8px;margin:0 10px 10px;}
	.zjf_tit{text-align: center;font-size: 16px;border-bottom: 1px solid #F4F5F5;padding-bottom: 10px}
	.zjf_tit span{padding: 0 20px 0;}
	.rw{margin-top: 5px;background-color: #FFFFFF;display: flex;justify-content: space-between;padding: 10px 0;}
	.rw_l{display: flex;}
	.rw_l_01{background-color: #D8A47D;width: 40px;height: 40px;border-radius: 40px;text-align: center;line-height: 45px;}
	.rw_l_02{padding-left: 10px;padding-top: 2px;}
	.rw_l_02_1 span{color: #D8A47D;font-size: 12px;}
	.rw_l_02_2{color: #A3A3A3;font-size: 12px;padding-top: 10px;}
	.rw_r{color: #E3B476;border-radius: 15px;border: 1px solid #E3B476;height: 20px;line-height: 20px;font-size: 12px;padding: 0 15px;margin-top: 10px;}
}	
</style>
