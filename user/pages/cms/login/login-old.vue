<template>
	<view class="login">
		<view class="head">登录</view>
		<view class="tou"><img src="@/imgs/user.png"/></img></view>
		<view class="con">
			<view class="con_01">
				<view class="con_01_l"><uni-icon type="contact" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_01_r"><input class="uni-input" focus placeholder="请输入用户名" /></view>
			</view>
			<view class="con_01" v-if="mm">
				<view class="con_01_l"><uni-icon type="locked" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_01_r"><input class="uni-input" focus placeholder="请输入密码" /></view>
			</view>
			<view class="con_02" v-if="yzm">
				<view class="con_02_l"><uni-icon type="locked" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_02_r"><input class="uni-input" focus placeholder="请输入验证码" /></view>
				<view class="con_02_t">验证码</view>
			</view>
			<view class="con_03">
				<view>忘记密码？</view> 
				<view>立即注册</view> 
			</view>
			<view class="con_04">
				<div class="con_04_1 con_04_2">登录</div>
			</view>
			<view class="con_05">
				<text @click="mmdl">密码登陆</text> <uni-icon type="forward" size="16" color="#F78674"></uni-icon> 
				<span @click="yzmdl">验证码登录</span> <uni-icon type="forward" size="16" color="#F78674"></uni-icon>
			</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				mm:true,
				yzm:false
			};
		},
		components: {uniIcon},
		methods: {
			mmdl(){
				this.mm=true
				this.yzm=false
			},
			yzmdl(){
				this.yzm=true
				this.mm=false
			}
		}
	}
</script>

<style lang="less">
.login{
	.head{font-size: 22px;padding: 20px;}
	.tou{text-align: center;}
	.tou img{width: 64px;height: 64px;}
	.con{padding: 20px 30px;}
	.con_01{border-bottom: 2px solid #F7F7F7;padding-top: 20px;display: flex;}
	.con_01_l{width: 40px;text-align: center;padding: 0px 0 5px;}
	.con_01_r{flex-grow: 1;padding-right: 10px;}
	input::-webkit-input-placeholder { color: #D2D2D2;     }
	.con_03{font-size: 12px;color: #F78674;padding: 15px 0 33px 8px;display: flex;justify-content: space-between;}
	.con_04_1{background-color: #F3F3F3;color: #E0E0E0;border-radius: 25px;height: 35px;line-height: 35px;font-size: 14px;text-align: center;
	border: 1px solid #F0F0F0;width: 100%;}
	.con_04_2{background-color: #E0441D;color: #ffffff;}
	.con_05{padding: 20px 0 ;text-align: center;color: #F78674;}
	.con_05 span{padding-left: 20px;}
	.con_02{border-bottom: 2px solid #F7F7F7;padding-top: 15px;display: flex;justify-content: space-between;}
	.con_02_t{background-color: #E0441D;color: #fff;height: 30px;line-height: 30px;border-radius: 20px;padding: 0 10px;margin-bottom: 5px;}
	.con_02_r{flex-grow: 1;padding-right: 10px;margin-top: 5px;}
	.con_02_l{width: 40px;text-align: center;padding: 5px 0 5px;}
}
</style>
