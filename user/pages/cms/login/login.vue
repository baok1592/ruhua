<template>
	<view class="login">
		<view class="head"> </view>
		<view class="tou"><img src="@/imgs/logo.png"/></img></view> 
		<view class="con" v-if="status == 0">
			<view class="con_01">
				<view class="con_01_l"><uni-icon type="contact" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_01_r"><input v-model="mobile" class="uni-input" focus placeholder="请输入用户名" style="height: 35px;background-color: #fff;"/></view>
			</view> 
			<view class="con_02">
				<view class="con_02_l"><uni-icon type="locked" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_02_r"><input v-model="psd" class="uni-input" placeholder="请输入密码" @confirm="login()" style="height: 35px;background-color: #fff;"/></view>
				<!-- <button class="con_02_t" type="default" @click="send" v-if="!stop">验证码</button> -->
				<button class="con_02_t" type="default" v-if="stop" :disabled="true">{{miao}}秒</button>
			</view>
			<view class="con_03" style="display: flex;justify-content: flex-end; margin-top: 10px;" >
				<view @click="change_sta">忘记密码？</view>
			</view>
			<view class="con_04" style="margin-top: 30px;">
				<div class="con_04_1 " @click="login()">登录</div>
			</view> 
		</view>
		
		<view class="con" v-if="status == 1">
			<view class="con_01">
				<view class="con_01_l"><uni-icon type="contact" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_01_r"><input v-model="mobile" class="uni-input" focus placeholder="请输入用户名" style="height: 35px;background-color: #fff;" /></view>
			</view> 
			<view class="con_02">
				<view class="con_02_l"><uni-icon type="locked" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_02_r"><input v-model="new_psd" class="uni-input" placeholder="请输入新密码" style="height: 35px;background-color: #fff;" /></view>
				
			</view>
			<view class="con_01">
				<view class="con_01_l"><uni-icon type="locked" size="25" color="#E5E5E5"></uni-icon></view>
				<view class="con_01_r"><input v-model="code" class="uni-input" focus placeholder="请输入验证码" style="height: 35px;background-color: #fff;" /></view>
				<button class="con_02_t" type="default" @click="send" v-if="!stop">验证码</button>
				<button class="con_02_t" type="default" v-if="stop" :disabled="true">{{miao}}秒</button>
			</view> 
			<view class="con_04" style="margin-top: 50px;display: flex;justify-content: space-between;">
				<div class="con_04_1 con_04_3" @click="back">返回登录</div>
				<div class="con_04_1 con_04_2" @click="edit_psd">提交</div>
			</view> 
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return { 
				stop: false,
				miao: 0,
				mobile:'',
				code:'',
				status:'0',
				psd:'',
				new_psd:''
			};
		},
		components: {uniIcon},
		onLoad() {
			
		},
		methods: { 
			back(){
				uni.navigateBack()
			},
			change_sta(){
				this.status = 1
			},
			edit_psd(){
				const mobile=this.mobile
				const code=this.code
				const new_psd = this.new_psd
				if(mobile.length!=11){
					this.$api.msg('手机号或验证码错误')
					return;
				}
				uni.showToast({
				    title: '修改成功',
				    duration: 2000
				});
				setTimeout(function(){
					uni.navigateBack()
				},2000)
			},
			
			login(){
				const mobile=this.mobile
				const code=this.code
				const psd = this.psd
				if(mobile.length!=11){
					this.$api.msg('手机号或验证码错误')
					return;
				}
				// uni.setStorageSync('token',res.token)
				uni.switchTab({
					url:'/pages/index/index'
				}) 
			},
			send(){
				const mobile=this.mobile
				if(mobile.length!=11){
					this.$api.msg('手机号错误')
					return; 
				}
			},
			settime(smiao) {
				const that = this
				const miao = that.miao
				if (miao == 0) {
					that.miao=0
					this.stop = false
				} else {
					setTimeout(() => {
						smiao--
						this.miao = smiao
						that.settime(smiao)
					}, 1000)
				}
			
			}
		}
	}
</script>

<style lang="less">
.login{
	padding-top:100px;
	.head{font-size: 22px;padding: 20px;}
	.tou{text-align: center;}
	.tou img{width: 64px;height: 64px;}
	.con{padding: 20px 30px;}
	.con_01{border-bottom: 2px solid #F7F7F7;padding-top: 20px;display: flex;}
	.con_01_l{width: 40px;text-align: center;padding: 0px 0 5px;}
	.con_01_r{flex-grow: 1;padding-right: 10px;}
	input::-webkit-input-placeholder { color: #D2D2D2;     }
	.con_03{font-size: 12px;color: #F78674;padding: 15px 0 33px 8px;display: flex;justify-content: space-between;}
	.con_04_1{background-color: #E61874;color: #ffffff;border-radius: 25px;height: 35px;line-height: 35px;font-size: 14px;text-align: center;
	border: 1px solid #F0F0F0;width: 100%;}
	.con_04_2{background-color: #E61874;color: #ffffff;width: 40%;}
	.con_04_3{background-color: #E9E8E5;color: #81817E;width: 40%;}
	.con_05{padding: 20px 0 ;text-align: center;color: #F78674;}
	.con_05 span{padding-left: 20px;}
	.con_02{border-bottom: 2px solid #F7F7F7;padding-top: 15px;display: flex;justify-content: space-between;}
	.con_02_t{background-color: #E0441D;color: #fff;height: 30px;line-height: 30px;border-radius: 20px;padding: 0 20px;margin-bottom: 5px;font-size:28upx;}
	.con_02_r{flex-grow: 1;padding-right: 10px;margin-top: 5px;}
	.con_02_l{width: 40px;text-align: center;padding: 5px 0 5px;}
}
</style>
