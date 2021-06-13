<template>
	<view class="loginA">
		<view class="head">
			<img src="@/imgs/detail.jpg"></img>
		</view>
		<view class="name">如花商城</view>
		<view class="btn-wx" @click="login('wx')">
			<img src="@/imgs/wx.png" ></img>微信一键登录
		</view>
		<view class="shou" @click="login('phone')">使用手机号登陆 ></view>
		<!-- <view class="des">新用户注册成功</view> -->
		<XcxAuth :auth="auth" @userinfo="get_userinfo"></XcxAuth>
	</view> 
</template>

<script>
	import XcxAuth from "@/components/wx_auth/xcx_auth.vue" 
	export default {
		data() {
			return {
				auth: {
					is_name: false,
					is_address: false,
					is_phone: false,
				},
			};
		},
		components:{
			XcxAuth
		},
		methods:{
			login(e){
				if(e == 'wx'){
					console.log('微信登录')
					this.auth.is_name = !this.auth.is_name
				}
				if(e == 'phone'){
					uni.navigateTo({
						url:'../loginB/loginB'
					})
				}
			},
			get_userinfo(e) {
				this.userinfo = e
				uni.navigateBack({})
			},
		}
	}
</script>

<style lang="scss">
.loginA{
	.head{padding: 80px 0 0px;text-align: center;
		img{width: 85px;height: 85px;border-radius: 10px;}
	}
	.name{text-align: center;padding: 20px 0 150px;font-size: 16px;font-weight: 600;}
	.btn-wx{margin: 10px;background-color: #1AAC19;border-radius: 5px;color: #fff;text-align: center;padding: 10px;
	display: flex;justify-content: center;
		img{width: 25px;height: 25px;padding-right: 5px;}
	}
	.shou{text-align: center;padding: 5px 10px 0;}
	.des{text-align: center;color: #9B9B9B;padding:40px 10px 0;font-size: 12px;}
}
</style>
