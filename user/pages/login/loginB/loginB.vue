<template>
	<view class="loginB">

		<view class='name'>
			<input v-model="phone_number" placeholder="请输入手机号码" />
		</view>
		<view class='yzm'>
			<view class="name_r"><input v-model="code" placeholder="请输入验证码" /></view>
			<view class="yzm_r" @click="get_code" v-if="is_code">获取验证码</view>
			<view style="color: #6D6D72;font-size: 15px; padding-top: 15px;margin-right: 20px;" v-if="!is_code">{{btn_name}}</view>
		</view>
		<view class='btn' @click="login">立即登录</view>
		<view class='wj'>
			<checkbox-group @change="read">
				<checkbox value="1" style="transform:scale(0.7)" />已阅读并同意<span @click="jump(1)">《用户协议》</span>和<span @click="jump(2)">《用户隐私政策》</span></checkbox-group>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				cutdown: 60,
				is_read: '',
				phone_number: '',
				code: '',
				yz_code: '',
				xieyi: '',
				yinsi: '',
				btn_name: '',
				is_code: true
			};
		},
		components: {},
		onLoad() {

		},
		methods: {
			settime() {
				if (this.cutdown == 0) {
					this.btn_name = '获取验证码'
					this.is_code = true
					this.cutdown = 60
					return
				} else {
					this.cutdown--
					this.btn_name = '(' + this.cutdown + ')'
				}
				setTimeout(()=>{
					this.settime()
				},1000)
			},
			read(e) {
				this.is_read = e.detail.value[0]
				console.log(this.is_read)
			},
			jump(e) {
				uni.navigateTo({
					url: '../../xieyi/xieyi?type=' + e
				})
			},
			get_code() {

				if (!this.phone_number || this.phone_number.length != 11) {
					this.$api.msg('请输入正确的手机号码')
					return
				}
				
				this.$api.http.post('auth/get_login_code', {
					mobile: this.phone_number
				}).then(res => {
					console.log(res)
					this.is_code = false
					this.yz_code = res.data
					this.settime()
				})
			},
			login() {
				if (!this.is_read) {
					this.$api.msg('请阅读并同意《用户协议》以及《用户隐私政策》')
					return
				}
				if (!this.phone_number || !this.code) {
					this.$api.msg('未填写手机号或验证码')
					return
				}
				if (this.phone_number.length != 11) {
					this.$api.msg('请输入正确的手机号')
					return
				}
				this.$api.http.post('auth/get_mobile_token', {
					mobile: this.phone_number,
					code: this.code
				}).then(res => {
					if (res.status == 400) {
						this.$api.msg(res.msg)
						return;
					}
					let token = res.msg
					uni.setStorageSync('token', token)
					this.$api.msg('登录成功')
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/index/index'
						});
					}, 2000);
				})
			}
		},
		mounted() {

		}
	}
</script>

<style lang="less">
	.loginB {
		padding-top: 100px;

		.xieyi {
			background-color: #FFFFFF;
			padding-left: 0;
			padding-right: 0;
			border-radius: 0;
		}

		.xieyi::after {

			border: none;

		}

		.name {
			padding: 20px 0px 10px;
			margin: 15px 20px 0px;
			border-bottom: 1px solid #F4F4F4;
			display: flex;
		}

		.yzm {
			padding: 6px 0px;
			margin: 0px 20px;
			display: flex;
			justify-content: space-between;
		}

		.name_r {
			width: 100%;
			border-bottom: 1px solid #F4F4F4;
			padding-top: 15px;
		}

		.yzm_r {
			background-color: #E21534;
			color: #fff;
			line-height: 50px;
			padding: 0 15px;
			font-size: 14px;
			margin-left: 15px;
			border-radius: 3px;
			flex-shrink: 0;
		}

		.btn {
			margin: 30px 20px 10px;
			background-color: #E21534;
			color: #fff;
			height: 45px;
			text-align: center;
			line-height: 45px;
			border-radius: 3px;
		}

		.wj {
			padding: 0 20px;
			color: #AAAAAA;
			font-size: 12px;

			span {
				color: #78B0E5;
			}
		}
	}
</style>
