<template>
  <view class="one">
    <view class="hdgz" @click="show">活动规则</view>
    <view class='cy'>当前已经有6009人参与</view>
    <view class='prize'>
        <view class='prize_01'>
          <img src='../../imgs/head.jpg'></img>
          <view class='whd'>未获得</view>
        </view>
        <view class='prize_01'>
          <img src='../../imgs/head.jpg'></img>
          <view class='whd'>未获得</view>
        </view>
        <view class='prize_01'>
          <img src='../../imgs/head.jpg'></img>
          <view class='whd'>未获得</view>
        </view>
    </view>
    <view class='sm'>
        <view>付1元钱，获赠兑换权益1份，满3份可兑换</view>
        <view class='sm_02'>支付成功后获得5M流量，满100M可以提取</view>
      </view>
    <view class='btn'>支付一元，立即领取</view>
    <view class='action'>
        <view>更多一元活动</view>
        <view class='action_02'>一元好货，优惠享不停</view>
    </view>
    <view class='porduct'>
        <view class='product_01'>
          <view class='product_01_l'><img src='../../imgs/8.jpg'></img></view>
          <view class='product_01_r'>
            <view class='pro_tie'>好吃的大西瓜好吃的大西瓜好吃的大西瓜好吃的大西瓜</view>
            <view class='pro_int'>又甜又面又起沙</view>
            <view class='pro_btn'>立即查看</view>
          </view>
        </view>
        <view class='product_01'>
          <view class='product_01_l'><img src='../../imgs/8.jpg'></img></view>
          <view class='product_01_r'>
            <view class='pro_tie'>好吃的大西瓜</view>
            <view class='pro_int'>又甜又面又起沙又甜又面又起沙</view>
            <view class='pro_btn'>立即查看</view>
          </view>
        </view>
    </view>

    <view class='wz' v-if="geze">
        <view class="cccc">
          <view class='jtgz' >
            <view class='jtgz_tit'>活动规则</view>
            <view class='jtgz_con'>
                <view style='height:300px'>123</view>
                <view style='height:100px'>354</view>
                <view style='height:100px'>567</view>
                <view id="blue" class="scroll-view-item bc_blue">9</view>   
            </view>
            <view class='close' @click="close"><uni-icon type="close" size="30" color="#959595"></uni-icon></view>
          </view>
        </view>
        <Black></Black>
    </view>
  </view>
</template>

<script>
import Black from "../../components/qy/Black";
import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
export default {
  
	data() {
		return {
		  list:[1,2,3,4,5,6],
		  geze:false
		};
	},
	computed: {
		
	},
	components: {
		Black,uniIcon
	},
	methods: {
		show(){
		  this.geze=true
		},
		close(){
		  this.geze=false
		}
	 },
		onPullDownRefresh() {
			//this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
};
</script>

<style lang="less">
.one{
  background-color: #7ACCCC;min-height: 100vh;position:relative;
  .hdgz{position: absolute;top: 15px;right: 0;background-color: #000;padding: 5px 10px 5px 15px;border-top-left-radius: 18px;border-bottom-left-radius: 18px;color: #DBDCD7;font-size: 12px;}
  .cy{color: #AD8971;text-align: center;padding-top: 50px;}
  .prize{padding: 40px;display: flex;justify-content: space-around;}
  .prize_01{text-align: center;position: relative;}
  .prize_01 img{width:70px;height:70px;box-shadow: 1px 1px 5px #9B938B;}
  .whd{color: #DBDCD7;background-color:rgba(0,0,0,0.4);display: inline;padding: 2px 8px;position: absolute;bottom: -10px;left: 8px;border-radius: 5px;font-size: 12px;}
  .sm{text-align: center;color: #FF4444;line-height: 20px;font-size: 15px;}
  .sm_02{font-size: 12px;padding-top: 5px;}
  .btn{margin:20px 13%;background-color:#FF4444;color: #fff;text-align:center;height:30px;line-height:30px;border-radius:20px;font-size: 12px;}
  .action{padding: 10px;text-align: center;color: #FF4444;font-size: 18px;font-weight: bold;}
  .action_02{font-size: 12px;font-weight: 100;padding-top: 5px;}
  .porduct{padding: 10px;}
  .product_01{display: flex;margin-bottom: 10px;}
  .product_01_l{}
  .product_01_l img{width: 115px;height: 115px;}
  .product_01_r{width: 50%;flex-grow: 1;background-color: #fff;padding: 5px 20px 5px 15px;box-sizing: border-box;position: relative;height: 115px;}
  .pro_tie{font-size: 14px;font-weight: bold;margin-bottom: 8px;max-height: 36px;overflow: hidden;line-height: 18px;}
  .pro_int{font-size: 11px;height: 31px;overflow:hidden;}
  .pro_btn{position: absolute;bottom: 5px;width: 60%;background-color:#FF4444;color: #fff;text-align:center;height:23px;line-height:23px;border-radius:20px;font-size: 12px;}
  .wz{position: absolute;top: 110px;width: 100%;}
  .cccc{position: relative;z-index: 1999;}
  .jtgz{width: 80%;border: 6px solid #F43D3D;background-color: #F6F6F6;padding: 10px;box-sizing: border-box;margin-left: 10%;height: 400px; overflow-y:scroll;}
  .jtgz_tit{font-size: 16px;font-weight: bold;padding: 10px 0 10px;text-align: center;}
  .jtgz_con{color: #959595;}
  .close{position: absolute;bottom: -60px;left: 45%;}
}
</style>
