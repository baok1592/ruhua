<template>
	<view class="shop_login" :style="'height:'+body_height+'px'">
		<view class="shop_tit" v-if="!edit">添加银行卡</view>
		<view class="shop_tit" v-else>修改银行卡</view>
		<view class="s_login"> 
			<view class="biao_01">
				<view class="biao_01_l">姓名：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="from.username" placeholder="请输入" />
				</view>
			</view>
			<view class="biao_01">
				<view class="biao_01_l" style="white-space:nowrap;">银行卡号：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="from.bank_num" placeholder="请输入" />
				</view>
			</view>
			<view class="biao_01">
				<view class="biao_01_l">开户行：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="from.bank_name" placeholder="请输入" />
				</view>
			</view>
		</view>
		<view class="btn" @click="tijiao" v-if="!edit">提交</view>
		<view class="add_btn" v-else>
			<view class="add_btn_l" @click="delet">删除</view>
			<view class="add_btn_r" @click="edits">修改</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				id:'',
				edit:true,
				body_height: 640,
				from:{username:'',bank_num:'',bank_name:''}
			};
		},
		onLoad(option) {  
			if(option.type=='add'){
				this.edit=false
				uni.removeStorageSync('bank')
			}
			const form=uni.getStorageSync('bank')
			if(form){
				this.from=form
			}
			this.id=form.id
		},
		components: {
			uniIcon
		},
		methods:{
			tijiao(){ 
				// this.$api.http.post('shop_cms/add_card',this.from).then(res=>{
					this.$api.msg('操作成功')
					setTimeout(()=>{
						uni.navigateTo({
							url:'/pages/cms/user/fenxiao/tixian/tixian'
						});
					},1000)
				// })
			},
			delet(){
			
				const that=this
				uni.showModal({
					title:'是否删除？',
					success(res) { 
						if(res.confirm){
							// that.$api.http.get('shop_cms/del_card?id='+that.id).then(res=>{
								that.$api.msg('操作成功')
								setTimeout(()=>{
									uni.navigateTo({
										url:'/pages/cms/user/fenxiao/tixian/tixian'
									});
								},1000)
							// })
						}
					}
				})
			},
			edits(){
				// this.$api.http.post('shop_cms/edit_card',this.from).then(res=>{
					this.$api.msg('操作成功')
					setTimeout(()=>{
						uni.navigateTo({
							url:'/pages/cms/user/fenxiao/tixian/tixian'
						});
					},1000)
				// })
			}
		}
	}
</script>

<style lang="less">
	.shop_login {
		background: url(../../../../../imgs/4.jpg) repeat-x #F6F6F6;
		padding-bottom: 1px;

		.shop_tit {
			font-size: 22px;
			color: #fff;
			padding: 20px 20px;
		}
		.uni-input{background-color: #fff;padding-top: 5px;}
		.s_login {
			margin: 0 10px;
			border-radius: 5px; 
			padding:40px 10px;
			background-color: #fff;
		}

		.l_01 {
			display: flex;
			justify-content: space-between;
			border-bottom: 1px solid #FBFBFB;
			height: 40px;
			line-height: 40px;
		}

		.l_02 {
			border-bottom: 1px solid #FBFBFB;
			height: 40px;
			line-height: 40px;
			color: #C2C2C2;
		} 

		.biao_01 {
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			line-height: 30px;
			display: flex;
		}

		.biao_01_l {
			width: 25%;white-space: nowrap;
			text-align: right;
			padding-right:15px;
		}
		.btn{background-color: #DE411C;color: #fff;margin: 10px 20px;height: 40px;line-height: 40px;text-align: center;
			border-radius: 20px;position: fixed;left: 0;bottom: 20px;z-index: 99;width: 90%;
		}
		.add_btn{
			position: fixed;left: 0;bottom: 20px;z-index: 99;width: 90%;margin: 10px 20px;height: 40px;line-height: 40px;
			display: flex;justify-content: space-between;
			.add_btn_l{width: 47%;background-color: #DBD6D6;text-align: center;border-radius: 20px;}
			.add_btn_r{width: 47%;background-color: #DE411C;color: #fff;text-align: center;border-radius: 20px;}
		}

	}
</style>
