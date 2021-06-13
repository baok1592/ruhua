<template>
	<view class="container">

		<view class="user-section">
			<image class="bg" src="/static/user-bg1.jpg"></image>
			<view class="user-info-box">
				<view class="portrait-box">
					<image class="portrait" :src="userinfo.avatarUrl || '/static/missing-face.png'"></image>
				</view>
				<view class="info-box">
					<text class="username">{{userinfo.nickName || '游客'}}</text>
				</view>
			</view>
			<view class="vip-card-box">
				<image class="card-bg" src="/static/vip-card-bg.png" mode=""></image>
				<navigator url="/pages/user/member/member">
					<view class="b-btn" v-if="get_user_vip_status">
						立即开通
					</view>
				</navigator>
				<view class="tit">
					<text class="yticon icon-iLinkapp-"></text>
					普通用户
				</view>
				<text class="e-m">如花商城</text>
				<text class="e-b">欢迎使用开源商城系统</text>
			</view>
		</view>

		<view class="cover-container" :style="[{
				transform: coverTransform,
				transition: coverTransition
			}]">
			<!-- 下拉方法 -->
			<!-- @touchstart="coverTouchstart" @touchmove="coverTouchmove" @touchend="coverTouchend" -->
			<image class="arc" src="/static/arc.png"></image>

			<!-- <view class="tj-sction">
				<view class="tj-item">
					<text class="num">128.8</text>
					<text>余额</text>
				</view>
				<view class="tj-item">
					<text class="num">0</text>
					<text>优惠券</text>
				</view>
				<view class="tj-item">
					<text class="num">20</text>
					<text>积分</text>
				</view>
			</view> -->
			<!-- 订单 -->
			<view class="order-section">
				<view class="order-item" @click="jump_toorder(0)" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-shouye"></text>
					<text>全部订单</text>
				</view>
				<view class="order-item" @click="jump_toorder(1)" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-daifukuan"></text>
					<text>待付款</text>
					<view class="number" v-if="list.no_payment > 0">
						<uni-badge :text="list.no_payment" type="error"></uni-badge>
					</view>
				</view>
				<view class="order-item" @click="jump_toorder(2)" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-yishouhuo"></text>
					<text>待收货</text>
					<view class="number" v-if="list.no_shipment > 0">
						<uni-badge :text="list.no_shipment" type="error"></uni-badge>
					</view>
				</view>
				<view class="order-item" @click="jump_toorder(4)" hover-class="common-hover" :hover-stay-time="50">
					<text class="yticon icon-shouhoutuikuan"></text>
					<text>退款/售后</text>
				</view>
				
				<view class="bangding" v-if="bang">
					<view class="bd-l">找不到订单和优惠券？绑定手机试试</view>
					<!-- <view class="bd-r" @click="bind">立即绑定</view> -->
					<button class="bd-r" size="mini" open-type="getPhoneNumber" @getphonenumber="bind">授权登录</button>
				</view>
			</view>

			<!-- 浏览历史 -->
			<view class="history-section icon">
				<view @click="jump_toaddress">
					<list-cell icon="icon-dizhi" iconColor="#5fcda2" title="地址管理"></list-cell>
				</view>
				<view @click="jump_tolike">
					<list-cell icon="icon-shoucang" iconColor="#DF2073" title="我的收藏"></list-cell>
				</view>
				<view @click="jump_tocms">
					<list-cell icon="icon-shoucang_xuanzhongzhuangtai" iconColor="#54b4ef" title="后台(演示-无需权限)"></list-cell>
				</view>
				<view @click="jump_toreseller" v-if="fx_switch">
					<list-cell icon="icon-iconfontweixin" iconColor="#54b4ef" title="分销商(演示-无需权限)"></list-cell>
				</view>
				<view @click="jump_tomycoupon">
					<list-cell icon="icon-tuijian" iconColor="#54b4ef" title="我的优惠券"></list-cell>
				</view>
				<!-- #ifdef APP-PLUS -->
				<view @click="jump_toset">
					<list-cell icon="icon-shezhi1" iconColor="#e07472" title="设置" border=""></list-cell>
				</view>
				<view @click="clear_cache">
					<list-cell icon="icon-shezhi1" iconColor="#e07472" title="清空缓存" border=""></list-cell>
				</view>
				<!-- #endif -->
				<view @click="jump_tohelp">
					<list-cell icon="icon-bangzhu" iconColor="#FF0000" title="关于如花"></list-cell>
				</view>
			</view>
			<view class="cpy">
				<view>如花商城系统</view>
				<view>Copyringht@2020</view>
				<!-- <view>www.ruhuashop.com</view> -->
			</view>
		</view>

		<XcxAuth :auth="auth" @userinfo="get_userinfo"></XcxAuth>
	</view>
</template>
<script>
	import Check from '@/common/check.js'
	import uniBadge from "@/components/uni/uni-badge/uni-badge.vue"
	import XcxAuth from "@/components/wx_auth/xcx_auth.vue"
	import listCell from '@/components/mix-list-cell';
	import {
		WxToken
	} from '@/common/wx_token.js'
	import {
		Api_url
	} from '@/common/config.js'
	var wxtoken = new WxToken();
	import {
		CUser
	} from '@/common/cache/user.js'
	var cache_user = new CUser();

	let startY = 0,
		moveY = 0,
		pageAtTop = true;
	export default {
		components: {
			listCell,
			XcxAuth,
			uniBadge
		},
		data() {
			return {
				gl:1, 
				list: '',
				auth: {
					is_name: false,
					is_address: false,
					is_phone: false,
				},
				coverTransform: 'translateY(0px)',
				coverTransition: '0s',
				moving: false,
				userinfo: '',
				switch_list:'',
				fx_switch:false
			}
		},
		onLoad() { 
			this.switch_list = uni.getStorageSync('switch')
			this.check_switch()
			// #ifdef H5 || APP-PLUS
			//wxtoken.verify('userinfo'); //手动授权获取openid和头像昵称	 			
			// #endif
			
			
			
		},
		onShow() {
			const that=this
			const my = cache_user.info_wait()
			my.then(res=>{  
				if(res && res.headpic && res.nickname){ 
					that.userinfo = {
						avatarUrl: res.headpic,
						nickName: res.nickname
					}
				}else{		 
					//auth传到组件，组件监听auth是否有变动
					that.auth.is_name = !that.auth.is_name
				}
			}) 
			this.get_number()
			
		},
		computed:{
			get_user_vip_status(){
				let vip_status = uni.getStorageSync('my')
				console.log(vip_status)
				if(vip_status.data && vip_status.data.vip && vip_status.data.vip.status == 1){
					return false
				}
				return true
			},
			bang(){
				let x = true
				let cache = uni.getStorageSync('my')
				let switch_cache = uni.getStorageSync('switch')
				console.log('ss',x)
				if(switch_cache.merge_mode!=1){
					if(cache && cache.data.mobile){
						 x = false
					}else{
						x = true
					}
					return x
				}else{
					x = false
					return x
				}
				
			}
		}, 
		methods: {
			check_switch(){
				const that = this
				console.log(this.switch_list.fx_status)
				that.fx_switch = this.switch_list.fx_status
			}, 
			bind(e) {
				// #ifdef MP-WEIXIN
				let obj = {}
				obj.iv = e.detail.iv
				obj.encryptedData = e.detail.encryptedData
				console.log(obj)
				//然后在第三方服务端结合 session_key 以及 app_id 进行解密获取手机号

				this.$api.http.post('auth/bind_wx_mobile', obj).then(res => {
					console.log(res)
					if(res.data == 1){
						cache_user.reset_storage()
					}
					
				})
				// #endif


			},
			get_number() { 
				let my = uni.getStorageSync('my')
				if(!my){ 
					return;
				} 
				this.$api.http.post('order/user/order_date', {}, false).then(res => {
					this.list = res.data
				})
			},
			get_userinfo(e) {
				this.userinfo = e
			},
			clear_cache() {
				uni.clearStorageSync()
				this.$api.msg('已清空')
			},
			jump_tohelp() {
				uni.navigateTo({
					url: '/pages/list/list'
				})
			},
			jump_toorder(e) {
				console.log(Check.a())
				if (!Check.a()) {
					return
				} else {
					uni.navigateTo({
						url: '/pages/order/order?state=' + e
					})
				}

			},
			jump_toset() {
				if (!Check.a()) {
					return
				}
				uni.navigateTo({
					url: '/pages/set/set'
				})
			},
			jump_toaddress() {
				if (!Check.a()) {
					return
				} else {
					uni.navigateTo({
						url: '/pages/address/address'
					})
				}

			},
			jump_tolike() {
				if (!Check.a()) {
					return
				}
				uni.navigateTo({
					url: '/pages/user/like/like'
				})
			},
			jump_tomycoupon() {
				if (!Check.a()) {
					return
				}
				uni.navigateTo({
					url: '/pages/user/mycoupon/mycoupon'
				})
			},
			jump_toreseller() {
				if (!Check.a()) {
					return
				}
				uni.navigateTo({
					url: '/pages/user/reseller/reseller'
				})
			},
			jump_tocms() {
				if (!Check.a()) {
					return
				}
				uni.navigateTo({
					url: '/pages/cms/index/index'
				})
			},
			/**
			 * 统一跳转接口,拦截未登录路由
			 * navigator标签现在默认没有转场动画，所以用view
			 */
			navTo(url) {
				if (!this.hasLogin) {
					// url = '/pages/public/login';
					uni.navigateTo({
						url
					})
				}
				uni.navigateTo({
					url
				})
			},

			/**
			 *  会员卡下拉和回弹
			 *  1.关闭bounce避免ios端下拉冲突
			 *  2.由于touchmove事件的缺陷（以前做小程序就遇到，比如20跳到40，h5反而好很多），下拉的时候会有掉帧的感觉
			 *    transition设置0.1秒延迟，让css来过渡这段空窗期
			 *  3.回弹效果可修改曲线值来调整效果，推荐一个好用的bezier生成工具 http://cubic-bezier.com/
			 */
			coverTouchstart(e) {
				if (pageAtTop === false) {
					return;
				}
				this.coverTransition = 'transform .1s linear';
				startY = e.touches[0].clientY;
			},
			coverTouchmove(e) {
				moveY = e.touches[0].clientY;
				let moveDistance = moveY - startY;
				if (moveDistance < 0) {
					this.moving = false;
					return;
				}
				this.moving = true;
				if (moveDistance >= 80 && moveDistance < 100) {
					moveDistance = 80;
				}

				if (moveDistance > 0 && moveDistance <= 80) {
					this.coverTransform = `translateY(${moveDistance}px)`;
				}
			},
			coverTouchend() {
				if (this.moving === false) {
					return;
				}
				this.moving = false;
				this.coverTransition = 'transform 0.3s cubic-bezier(.21,1.93,.53,.64)';
				this.coverTransform = 'translateY(0px)';
			}
		},
		// #ifndef MP
		onNavigationBarButtonTap(e) {
			const index = e.index;
			if (index === 0) {
				this.navTo('/pages/set/set');
			} else if (index === 1) {
				// #ifdef APP-PLUS
				const pages = getCurrentPages();
				const page = pages[pages.length - 1];
				const currentWebview = page.$getAppWebview();
				currentWebview.hideTitleNViewButtonRedDot({
					index
				});
				// #endif
				uni.navigateTo({
					url: '/pages/notice/notice'
				})
			}
		},
		// #endif
		onPullDownRefresh() { 
			this.get_number()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>
<style lang='scss'>
	.container {
		background-color: #F5F5F5;
		min-height: 100vh;
	}

	.cpy {
		padding: 20px 0;
		text-align: center;
		font-size: 12px;
		color: #666;
	}

	%flex-center {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	%section {
		display: flex;
		justify-content: space-around;
		align-content: center;
		background: #fff;
		border-radius: 10upx;
	}

	.user-section {
		height: 520upx;
		padding: 100upx 30upx 0;
		position: relative;

		.bg {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			filter: blur(1px);
			opacity: .7;
		}
	}

	.user-info-box {
		height: 180upx;
		display: flex;
		align-items: center;
		position: relative;
		z-index: 1;

		.portrait {
			width: 130upx;
			height: 130upx;
			border: 5upx solid #fff;
			border-radius: 50%;
		}

		.username {
			font-size: $font-lg + 6upx;
			color: $font-color-dark;
			margin-left: 20upx;
		}
	}

	.vip-card-box {
		display: flex;
		flex-direction: column;
		color: #f7d680;
		height: 240upx;
		background: linear-gradient(left, #333333, #000000);
		border-radius: 16upx 16upx 0 0;
		overflow: hidden;
		position: relative;
		padding: 20upx 24upx;

		.card-bg {
			position: absolute;
			top: 20upx;
			right: 0;
			width: 380upx;
			height: 260upx;
		}

		.b-btn {
			position: absolute;
			right: 20upx;
			top: 16upx;
			width: 132upx;
			height: 40upx;
			text-align: center;
			line-height: 40upx;
			font-size: 22upx;
			color: #36343c;
			border-radius: 20px;
			background: linear-gradient(left, #f9e6af, #ffd465);
			z-index: 1;
		}

		.tit {
			font-size: $font-base+2upx;
			color: #f7d680;
			margin-bottom: 28upx;

			.yticon {
				color: #f6e5a3;
				margin-right: 16upx;
			}
		}

		.e-b {
			font-size: $font-sm;
			color: #d8cba9;
			margin-top: 10upx;
		}
	}

	.cover-container {
		background: $page-color-base;
		margin-top: -160upx;
		padding: 0 30upx;
		position: relative;
		background: #f5f5f5;
		padding-bottom: 20upx;

		.arc {
			position: absolute;
			left: 0;
			top: -34upx;
			width: 100%;
			height: 36upx;
		}
	}

	.tj-sction {
		@extend %section;

		.tj-item {
			@extend %flex-center;
			flex-direction: column;
			height: 140upx;
			font-size: $font-sm;
			color: #75787d;
		}

		.num {
			font-size: $font-lg;
			color: $font-color-dark;
			margin-bottom: 8upx;
		}
	}

	.order-section {
		@extend %section;
		padding: 28upx 0;
		margin-top: 20upx;
		flex-wrap: wrap;

		.bangding {
			width: 100%;
			display: flex;
			justify-content: space-between;
			font-size: 12px;
			border-top: 1px solid #F1F3F6;
			padding: 10px 10px 0;
			margin-top: 15px;

			.bd-l {
				height: 27px;
				line-height: 27px;
			}

			.bd-r {
				color: #FB526A;
				border: 1px solid #FB526A;
				border-radius: 3px;
				height: 24px;
				line-height: 24px;
				text-align: center;
				padding: 0 10px;
			}
		}

		.order-item {
			@extend %flex-center;
			width: 120upx;
			height: 120upx;
			border-radius: 10upx;
			font-size: $font-sm;
			color: $font-color-dark;
			position: relative;

			.number {
				position: absolute;
				top: -3px;
				right: 10px;
			}
		}

		.yticon {
			font-size: 48upx;
			margin-bottom: 18upx;
			color: #fa436a;
		}

		.icon-shouhoutuikuan {
			font-size: 44upx;
		}
	}

	.history-section {
		padding: 30upx 0 0;
		margin-top: 20upx;
		background: #fff;
		border-radius: 10upx;

		.sec-header {
			display: flex;
			align-items: center;
			font-size: $font-base;
			color: $font-color-dark;
			line-height: 40upx;
			margin-left: 30upx;

			.yticon {
				font-size: 44upx;
				color: #5eba8f;
				margin-right: 16upx;
				line-height: 40upx;
			}
		}

	}
</style>
