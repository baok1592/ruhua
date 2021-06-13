<template>
	<view class="Tan">
		<view class='' v-if="coupon">
		    <view class="coupon" :style="'background: url('+web_url+'/static/web/51.jpg) no-repeat;'">
				<view class="cou_01" v-for="(item,index) of coulist" :key="index" v-if="index<3">
					<view class="cou_01_01">
						<view class="cou_01_01_l"><span>10</span>元</view>
						<view class="cou_01_01_2">
							<view class="cou_btn">立即领取</view>
						</view>
						<view class="radiue_left"></view>
						<view class="radiue_right"></view>
					</view>
					<view class="cou_01_02">全场商品满120元可用</view>
				</view>
		        <view class='two_close' @click="close_add"><uni-icon type="close" size="40" color="#fff"></uni-icon></view>
		    </view>
		    <Black></Black>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import Black from './Black'
	export default {
		data() {
			return {
				
			};
		},
		props:{
			coupon:'',
			coulist:String,
		},
		components: {
			Black,
			uniIcon
		},
		watch:{
			coupon(){
				console.log(this.coupon)
			}
		},
		methods:{
			close_add(){
				this.$emit("close_add");
			}
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.Tan{
	.coupon{position: fixed;top: 60px;left: 8%;width: 84%;height: 500px;background-color: #fff;box-sizing: border-box;color: #BE3A48;
	z-index: 10001;padding: 180px 30px 35px;background-size: 100% 100%;
		.cou_01{background-color:#FFE8B2;border-radius: 3px;margin-bottom: 10px;
			.cou_01_01{display: flex;padding: 5px 0;border-bottom: 1px dashed #F5E2AA;position: relative;
				.cou_01_01_l{width: 40%;text-align: center;font-weight: 600;font-size: 16px;
					span{font-size: 40px;}
				}
				.cou_01_01_2{
					text-align: center;width: 60%;
					.cou_btn{font-weight: 600;background: linear-gradient(to right, #BC4146, #EA5963);color: #FEBB98;display: inline-block;
					border: 1px solid #BD725B;border-radius: 20px;height: 26px;line-height: 26px;width: 80px;margin: 10px 0 0 0px;
					font-size: 13px;}
				}
				.radiue_left{position:absolute;bottom:-5px;left:0;width:5px;height:10px;border-radius:0 100px 100px 0;background-color: #F7C0AD;}
				.radiue_right{position: absolute;bottom: -5px;right: 0;width: 5px; height: 10px;border-radius:100px 0 0 100px   ;background-color: #F7C0AD;}
			}
			.cou_01_02{font-size: 12px;text-align: center;height: 30px;line-height: 30px;}
		}
	}
	
	.two_close{position: absolute;bottom: -60px;left: 42%;color: #BDB4AD;}

}
</style>
