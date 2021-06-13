<template>
	<view class="user">
		<view class="cu-list menu" :class="[menuBorder?'sm-border':'',menuCard?                                                 'card-menu margin-top':'']">
			<view class="cu-item arrow" >
				<view class="content"  @click="change">
					<text class="text-grey">修改密码</text>
				</view>
			</view>
			<!-- #ifndef MP-WEIXIN -->
			<view class="cu-item arrow" >
				<view class="content">
					<text class="text-grey">更新版本</text>
				</view>
			</view>
			<!-- #endif -->
			<view class="cu-item arrow" :class="menuArrow?'arrow':''" @click="tel('0859-1234567')">
				<view class="content">
					<text class="text-grey">联系电话</text>
				</view>
				<view class="action">
					<text class="text-grey text-sm">0859-1234567</text>
				</view>
			</view>
			<view class="cu-item" :class="menuArrow?'arrow':''">
				<view class="content">
					<text class="text-grey" @click="lout()">退出</text>
				</view>
			</view>
		</view>
		
		<uni-popup :show="gaimi" type="bottom" mode="fixed"  @hidePopup="hidePopup">
			<view class="uni-list" >
				<view class="build_01">
					<view class="build_02_l">原密码：</view>
					<view class="build_02_r" ><input placeholder="请输入" v-model="old_psd" type="password"  style="margin-top: 7px;"/></view>
				</view>
				<view class="build_01">
					<view class="build_02_l">新密码：</view>
					<view class="build_02_r" ><input placeholder="请输入" v-model="new_psd_A" type="password" style="margin-top: 8px;"/></view>
				</view>
				<view class="build_01" style="border: none;">
					<view class="build_02_l">新密码：</view>
					<view class="build_02_r" ><input placeholder="请输入" v-model="new_psd_B" type="password" style="margin-top: 8px;"/></view>
				</view>
				<view class="foot" >
					<view class="foot_l" @click="change">取消</view>
					<view class="foot_r" @click="sub_edit">确认</view>
				</view>
			</view>
		</uni-popup>
	</view>

</template>

<script>
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import Titlec from "@/components/qy/Title-c.vue"
	import Listd from "@/components/qy/List-d.vue"
	export default {
		data() {
			return {
				list: [1, 2, 3],
				gaimi:false,
				old_psd:'',
				new_psd_A:'',
				new_psd_B:''
			};
		},
		components: {
			uniIcon,
			Titlec,
			Listd,uniPopup
		},
		methods: {
			tel(phone){
			    uni.makePhoneCall({
			     phoneNumber: phone
			    });
			}, 
			change(){
				this.gaimi=!this.gaimi
			},
			lout() {
				uni.showModal({
					title:'是否退出？',
					success(res) { 
						if(res.confirm){
							uni.removeStorageSync('token')
							uni.reLaunch({
								url: '/pages/login/login'
							})
						}
					}
				})
				
			}
		}
	}
</script>

<style lang="less">
	.user {
		padding: 20px;background-color: #fff;min-height: 100vh;
		.uni-list{width: 260px;font-size:16px;
			.build_01{display: flex;border-bottom: 1px solid #F8F8F8;line-height: 35px;padding: 5px 0;}
			.build_01_l{width: 80px;text-align: right;}
			.build_01_r{color: #959595;padding-left: 20px;}
			.foot{display: flex;border-top: 1px #F9F9F9 solid;line-height: 40px;height: 40px;margin-top: 30px;}
			.foot_l{width: 50%;text-align: center;}
			.foot_r{width: 50%;text-align: center;border-left: 1px #F9F9F9 solid ;color: #5095D3;}
		}
		.list {
			line-height: 35px;
		}

		.list_01 {
			padding: 15px 10px 8px;display: flex;justify-content: space-between;
			border-bottom: 1px solid #F4F4F4;
			font-size: 14px;
		}
	}
</style>
