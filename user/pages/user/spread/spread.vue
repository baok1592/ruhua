<template>
	<view class="spread">
		<view class="head">
			<img class="img" :src="userinfo.avatarUrl"></img>
		</view>
		<view class="title">{{userinfo.nickName}}</view>
		<view class="er">
			<img :src="getimg + code_url"></img>
		</view>
		<view class="btn">
			<view>
				<button class="butt" @click="save" :disabled="is_click">保存二维码</button>
			</view>
		</view>
		<view class="gui">
			<view>1.将此页面二维码保存并发送给您的好友</view>
			<view>2.好友识别二维码通过手机号进行注册</view>
			<view>3.好友进行实名认证后返推荐奖励给您账户</view>
		</view>
	</view>
</template>

<script>
	import {
		CUser
	} from '@/common/cache/user.js'
	var cache_user = new CUser();
	export default {
		data() {
			return {
				getimg: this.$getimg,
				code_url: '',
				is_click: false,
				userinfo: {}
			};
		},
		onLoad() {
			const my = cache_user.info()
			this.userinfo = {
				avatarUrl: my.headpic,
				nickName: my.nickname
			}
			this._load()
		},
		methods: {

			_load() {
				this.$api.http.post('user/get_web_code',{
					path:'',
				}).then(res => {
					this.code_url = res.data
				})
			},
			save() {
				// #ifdef H5
				uni.showModal({
					title: '请截图保存',
				})
				// #endif

				// #ifdef MP-WEIXIN
				const that = this
				this.is_click = true
				uni.showLoading({
					title: '加载中'
				});
				console.log(1)
				uni.authorize({
					scope: 'scope.writePhotosAlbum',
					success(res) {
						uni.downloadFile({
							url: 'https://www.baidu.com/img/bd_logo1.png', //仅为示例，并非真实的资源
							success: (res) => {
								console.log(res)
								uni.saveImageToPhotosAlbum({
									filePath: res.tempFilePath,
									success: function(res) {
										console.log('save success', res);
										uni.showToast({
											title: '图片保存成功',
											duration: 2000
										})
										that.is_click = false
										uni.hideLoading();
									}
								});
							}
						});

					}
				})

				// #endif



			},
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
	.spread {
		.head {
			background-color: #FA436A;
			height: 100px;
			position: relative;

			.img {
				width: 65px;
				height: 65px;
				border-radius: 50px;
				border: 1px solid #C896A6;
				position: absolute;
				left: 50%;
				bottom: -30px;
				margin-left: -32.5px;
			}
		}

		.title {
			text-align: center;
			padding: 40px 0 30px;
			font-size: 18px;
			font-weight: 600;
		}

		.er {
			text-align: center;

			img {
				width: 180px;
				height: 180px;
				border: 2px solid #F6D7B1;
				padding: 2px;
				margin: 0px 0 25px;
			}
		}

		.btn {
			display: flex;
			font-size: 13px;
			justify-content: center;

			.butt {
				background-color: #FB6471;
				height: 25px;
				line-height: 25px;
				border-radius: 20px;
				width: 140px;
				text-align: center;
				color: #fff;
			}
		}

		.gui {
			color: #9E9E9E;
			font-size: 12px;
			padding: 40px 0 0 75px;
			line-height: 20px;
		}
	}
</style>
