<template>
	<view class="shop">
<!-- 		<view class="head">
			<view class='head_l'><img src='../../static/8.jpg'></img></view>
			<view class='head_m'>
			  <view class='head_m_01'>旗舰店</view>
			  <view class='head_m_02'>2.7万人关注</view>
			</view>
			<view class='head_r'>关注</view>
		</view> -->
		<view class='shopp'>
			<view class="shopp_i"><img src="../../static/home.png"></img> </view>
			<view class="shop_t">
				<view class='address'>
					<view class='address_01' bindtap='jump_shop'>
						<view>{{shop.shop_name}}</view>
						<view class="address_info">{{shop.shop_address}}</view>
					</view>
					<view class='address_02'>
						<uni-icon type="location-filled" size="23" color="#E02E24"></uni-icon>
					</view>
					<view class='address_02'>
						<uni-icon type="phone-filled" size="23" color="#E02E24"></uni-icon>
					</view>
				</view>
			</view>
		</view>
		<view class='tab'>
			<view :class="xuan==0?'xz':'bb' " @click="num" id=0>首页</view>
			<view :class="xuan==1?'xz':'bb' "   @click="num" id=1>新品</view>
			<view :class="xuan==2?'xz':'bb' "   @click="num" id=2>热门</view>
			<view :class="xuan==3?'xz':'bb' "   @click="num" id=3>活动</view>
		</view>
		<view class="scroll_box" > 
		  <scroll-view class="scroll-view_x" scroll-x style="width: auto;overflow:hidden;" >
		   <view class='sw'>
			<template  v-for="(item, index) in list"> 
			  <view class="item_list" > 
				<view class='coupon_01'>
				  <view class='coupon_01_l'>¥<span> 5</span></view>
				  <view class='coupon_01_r'>
					<view class='coupon_01_r_01'>领取薯券</view>
					<view class='coupon_01_r_02'>满100使用</view>
				  </view>
				</view>
			  </view>
			</template>
		   </view>
		  </scroll-view>
		</view>
		<view class='pro'>
			<view class="list-f">
			  
				<view class='f_01' >
				  <view class='f_01_1'>
					<!-- <img :src='info.imgs' />  -->
					<img />
					<view class='f_01_1_f'>积分抵扣20元</view>
				  </view>
				  <view class='f_01_2'><!-- {{info.goods_name}} -->1123</view>
				  <view class='f_01_3'><!-- {{info.description}} -->2</view>
				  <view class='f_01_4'>¥<!-- {{info.price}} -->3</view>
				</view>
			  
			</view>
		</view>
		<view class='back'><router-link to='/' >
			<view class='back_01'><uni-icon type="arrowleft" size="18" color="#fff"></uni-icon></view>
			<view class='back_02'>返回首页</view></router-link>
		</view>
	</view>
	
</template>
<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				shop: {
					"shop_id": 156,
					"shop_name": "大圣酸汤",
					"shop_description": "",
					"shop_type_id": "1",
					"aid": 18,
					"shop_group_id": 0,
					"shop_address": "兴义市桔山办万湖汇4栋一楼商铺",
					"position": ["25.110360785071236", "104.91775544276606"],
					"sort": 99,
					"img_id": 3041,
					"hj_imgs": "3042,3043,3044,3045,3046,3047,3048,3049,3050,3051,3052,3053,3054",
					"other_imgs": "",
					"shop_phone": "0859-3330300",
					"shop_collect": 0,
					"shop_sales": "0.00",
					"content": "<p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133858920.jpg\" style=\"\" title=\"1557818133858920.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133300938.jpg\" style=\"\" title=\"1557818133300938.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133278681.jpg\" style=\"\" title=\"1557818133278681.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133651548.jpg\" style=\"\" title=\"1557818133651548.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133943375.jpg\" style=\"\" title=\"1557818133943375.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133506942.jpg\" style=\"\" title=\"1557818133506942.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133540794.jpg\" style=\"\" title=\"1557818133540794.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133438109.jpg\" style=\"\" title=\"1557818133438109.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133176496.jpg\" style=\"\" title=\"1557818133176496.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133958474.jpg\" style=\"\" title=\"1557818133958474.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133244079.jpg\" style=\"\" title=\"1557818133244079.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133119964.jpg\" style=\"\" title=\"1557818133119964.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133113295.jpg\" style=\"\" title=\"1557818133113295.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818133551942.jpg\" style=\"\" title=\"1557818133551942.jpg\"\/><\/p><p><img src=\"https:\/\/api.laiyu.vip\/\/ue_upload\/img\/20190514\/1557818134437184.jpg\" style=\"\" title=\"1557818134437184.jpg\"\/><\/p><p><br\/><\/p>",
					"xinxin": 0,
					"chouyong": 5,
					"laiyu_tel": "15519997119",
					"first_mian": "0.00",
					"create_time": "2019-05-13",
					"update_time": "2019-05-18 17:09:25"
				},
				list:[1,2,3],
				xuan:0
			};
		},
		components: {uniIcon},
		methods: {
			num(event){
			  var i=event.currentTarget.id;
			  this.xuan=i 
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

<style lang="less">
.shop {font-size: 14px;
	/* 头部 */
	.shopp {
		font-size: 14px;
		background-color: #ffffff; margin-top: 8px; display: flex;
	  .shopp_i{padding: 17px 0 0 5px;
			img{width: 50px;height: 50px;}
		}
		.shop_m {
			padding: 10px 20rpx 10px;
			font-weight: 600;
			font-size: 16px;
			border-bottom: 2rpx solid #f2f2f2;
		}
	
		.shop_t {padding: 10px 0; flex-grow: 1;
			.address {
				display: flex;
				padding: 20rpx 5px 10rpx;
			}
	
			.address_01 {
				width: 76%;
				font-size: 16px;
				line-height: 40rpx;
				height: 80rpx;
				overflow: hidden;
			}
	
			.address_02 {
				width: 12%;
				color: #E02E24;
				display: flex;
				justify-content: center;
				align-items: center;
			}
	
			.address_info {
				color: #777;font-size: 14px;
			}
		}
	}
  .head{padding: 20px 10px;display: flex;}
  .head_l{padding: 13px 13px 13px;border: 1px solid #F6F6F6;border-radius: 45px;flex: 0 0 33px;box-sizing: border-box;}
  .head_l img{width: 18px;height: 18px;border-radius: 15px;}
  .head_m{width: 60%;padding: 8px 0 0 10px;flex-grow: 1;}
  .head_m_01{font-weight: bold;font-size: 16px;}
  .head_m_02{color: #B2B2B2;font-size: 11px;padding-top: 3px;}
  .head_r{width: 60px;background-color: #FF4444;margin-top: 10px;color: #fff;text-align: center;line-height: 30px;height: 30px;border-radius: 20px;}
  .tab{padding: 10px 20%;display: flex;justify-content: space-around;}
  .bb{padding-bottom: 5px;}
  .xz{border-bottom: 2px solid red;padding-bottom: 5px;}
  .pro{padding-top: 15px;}
  .coupon{padding: 10px;}
  .coupon_01{background-color: #f1f1f1;display: flex;width: 140px;height: 60px;margin:10px;flex: 0 0 140px;}
  .coupon_01_l{width: 40%;text-align: center;line-height: 60px;color: #FE475C;}
  .coupon_01_l span{font-size: 22px;}
  .coupon_01_r{width: 60%;text-align: center;padding-top: 10px;line-height: 20px;}
  .coupon_01_r_02{color: #9A9A9A;}
  .sw{display: flex;width: 100%;}
  .back a{position: fixed;bottom: 80px;left: 0;background-color: #FF5266;color: #FFDFE9;display: flex;text-decoration:none;
  padding: 3px 10px 3px 0px;border-top-right-radius: 18px;border-bottom-right-radius: 18px;}
  .back_01{padding-top: -1px;}
  .list-f{margin: 5px 2%;border-radius: 8px;padding-bottom: 10px;box-shadow: 4px 4px 5px #DEDEDE;display: inline-block;background-color: #fff;width: 46%;
	  .f_01{font-size: 12px;}
	  .f_01_1{position: relative;}
	  .f_01_1 img{width: 100%;height: 180px;border-top-left-radius: 8px;border-top-right-radius: 8px;}
	  .f_01_1_f{position: absolute;bottom: -5px;left: 14px;background-color: #FB5F52;color: #FBE7CF;font-size: 10px;padding: 3px 5px;border-top-right-radius: 8px;border-bottom-left-radius: 8px;}
	  .f_01_2{padding: 8px 10px 0px;height: 24px;overflow: hidden;line-height: 18px;}
	  .f_01_3{padding: 0 10px;height: 18px;color: #9D9B9B;overflow: hidden;line-height: 18px;}
	  .f_01_4{padding: 0 10px;color: #ED6657;}

	  .blue{
		.f_01_1_f{background-color: #2A82E4;}
		.f_01_4{color: #2A82E4 ;}
	  }
	  .green{
		.f_01_1_f{background-color: #34B07A ;}
		.f_01_4{color: #34B07A  ;}
	  }
	  .pink{
		.f_01_1_f{background-color: #FF729A  ;}
		.f_01_4{color: #FF729A;}
	  }
	  .orange{
		.f_01_1_f{background-color: #FF8D1B   ;}
		.f_01_4{color: #FF8D1B ;}
	  }
	  
	}
}
</style>
