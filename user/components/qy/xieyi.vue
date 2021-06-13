<template>
	<view class="tan" v-if="xy">
		<div class="black" @click="xy=false">
			<div class="container"></div>
		</div>
		<view class="t_con">
			<view class="t_c_tit">《服务协议和隐私政策》</view>
			<view class="t_c_con"> 欢迎您访问如花商城！为了更好地为您提供服务与保障您的消费者权益，请使用前务必仔细阅读以下协议内容。如您选择同意，则视为您已充分阅读并理解本服务协议的内容，自愿接受本协议所约定的权利义务约束。</view> 
			<view class="t_c_more" >
				<span @click="jump_xieyi(1)">《服务协议》</span> 和
				<span @click="jump_xieyi(2)">《隐私政策》</span>
			</view>
			<view class="t_c_btn">
				<view class="t_c_btn_01 bg_red" @click="sapprove(true)">同意</view>
				<view class="t_c_btn_01 bg_gery" @click="sapprove(false)">不同意</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				xy:false
			};
		},
		props:{
			name:String
		},
		components: {
		},
		created() {
			if(!uni.getStorageSync('xyfile')){
				this.xy=true
			}
		},
		methods:{
			sapprove(e){
				uni.setStorageSync('xyfile',e)
				this.xy=false
			},
			jump_xieyi(e){
				uni.navigateTo({
					url:'/pages/xieyi/xieyi?type='+e
				})
			}
		}
	}
</script>

<style lang="less">
	.black{
		.container {
		    background-color: #000000;
		    position: fixed;
		    top: 0;
		    opacity: 0.6;
		    width: 100%;
		    height: 100%;
		    z-index: 999;
		} 
	
	}
.t_con{background-color: #fff;position:fixed;top: 120px;left: 10%;width: 80%;padding: 20px;border-radius: 5px;
		z-index: 1999;min-height: 370px;overflow: hidden;
		.t_c_tit{font-size: 16px;text-align: center;font-weight: 600;padding-bottom: 10px;}
		.t_c_con{font-size: 14px;color: #868686;}
		.t_c_more{text-align: center;color: #868686;padding: 15px 0 10px;font-size: 14px;
			span{color: #4B73CE;}
		}
		.t_c_btn{
			.t_c_btn_01{height: 35px;line-height: 35px;color: #fff;border-radius: 20px;text-align: center;
			font-size: 14px;margin-top: 10px;}
			.bg_red{background-color: #F00D3B;}
			.bg_gery{background-color: #CCCCCC;}
		}
	}
</style>
