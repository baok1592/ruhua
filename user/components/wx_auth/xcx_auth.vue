<template>
	<view class="auth">  
		<view class="login-box" v-if="login">
			<view class="t1">授权登陆</view>
			<view class="t2">
				<img src="./wx_logo.png" />
				<view>申请获取以下权限</view>
			</view>
			<view class="t3">获得你的公开信息(名称、头像)</view>
			<view class="t4"><button size="mini" open-type="getUserInfo" @getuserinfo="updateUserInfo">授权登陆</button></view>
		</view> 
	</view>
</template>

<script>	
	import {Api_url} from '@/common/config'
	export default {
		data() {
			return {  
				login:false
			};
		}, 
		props:[
			'auth'
		],  
		watch:{
			'auth.is_name'(news){  
				this.check_userAuth()
			},
			'auth.is_address'(news){
				console.log('address:',news)
			},
			'auth.is_phone'(news){
				console.log('phone:',news)
			}
		},
		methods: {
			check_userAuth() {
				console.log('授权开始')
				const that = this;
				uni.getSetting({
					success(data) { 
						if (data.authSetting['scope.userInfo']) {
							console.log('已授权过了')
							 
							uni.getUserInfo({
								success: data => {  
									let user=data.userInfo 
									that.UpUser(user,data.encryptedData,data.iv)
								}
							})
							uni.navigateBack({
								
							})
						} else { 
							that.login = true
							
						}
					}
				})
			},
			updateUserInfo(res) { 
				this.login = false
				const that = this;
				if (res.detail.userInfo) {
					const user = res.detail.userInfo		 
					that.UpUser(user,res.detail.encryptedData,res.detail.iv) 
				}
			},
			UpUser(user,keys,iv){		 
				const that = this
				uni.request({
				  	url: Api_url+'auth/upinfo',
				  	method: 'POST',  
					data:{
						nickname: user.nickName,
						headpic: user.avatarUrl,
						keys,
						iv
					},
					header: {
						token:uni.getStorageSync("token")
					},
				  	success: function (res) {
						that.$emit('userinfo',user)
					},
				}); 
			}
		}
	}
</script>

<style lang="less">
	.auth {
		.login-box {
			position: absolute;
			left: 15%;
			top: 25%;
			z-index: 99999;
			border-radius: 20rpx;
			box-shadow: 1px 5px 3px #ccc;
			width: 70%;
			height: 439rpx;
			background: #FAF9FD;
			text-align: center;
		}

		.login-box .t1 {
			font-size: 32rpx;
			border-bottom: 1px solid #DEDEDE;
			padding: 7px 0;
		}

		.login-box .t2 {
			padding: 10rpx 0 10rpx;
			font-size: 30rpx;
		}

		.login-box .t2 image {
			width: 120rpx;
			height: 120rpx;
		}

		.login-box .t3 {
			font-size: 28rpx;
			color: #A8A7AB;
			padding-bottom: 20rpx;
		}

		.login-box .t4 {
			border-top: 1px solid #DEDEDE;
			padding: 10px 0 0;
		}

		.login-box .t4 button {
			font-size: 32rpx;
			color: #51C332;
			font-weight: 500;
		}
	}
</style>
