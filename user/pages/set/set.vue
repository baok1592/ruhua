<template>
	<view class="container">
		<view class="list">
			<view class="list_l">个人资料</view>
			<view class="list_r">
				<view class="list_r_1" v-if="from.headpic"><img :src="from.headpic"></img></view>
				<view class="list_r_1" v-else><img src="../../imgs/8.jpg"></img></view>
				
			</view>
		</view> 
		<view class="list">
			<view class="list_l">昵称</view>
			<view class="list_r">
				<view class="list_r_1" ><input placeholder="请输入" v-model="from.nickname"></view>
				
			</view>
		</view> 
		<view class="list">
			<view class="list_l">生日</view>
			<view class="list_r">
				<view class="list_r_1"><input placeholder="请输入" v-model="from.birthday"></view>
				
			</view>
		</view> 
		<view class="list">
			<view class="list_l">性别</view>
			<view class="list_r">
				<view class="list_r_3"> 
					<radio-group @change="radioChange">
						<label class="uni-list-cell uni-list-cell-pd" >
							<view class="nan"><radio value="1" :checked="from.sex==1?true:false" style="transform:scale(0.7)"  />男</view>
							<view><radio value="0" :checked="from.sex==0?true:false"  style="transform:scale(0.7)" />女</view>
						</label>
					</radio-group>
				</view>
				
			</view>
		</view> 
		<view class="list-cell log-out-btn" @click="set()">
			<text class="cell-tit">提 交</text>
		</view>
		<view class="nicheng" v-if="xgnc">
			<view class="tan_nc">
				<view class="nc_in"><input placeholder="请输入" v-model="form.name"/></view>
				<view class="nc_btn">
					<view class="btn_l" @click="change_nc">取消</view>
					<view class="btn_r" @click="change_nc">确定</view>
				</view>
			</view>
			<div class="container"></div>
		</view>
		
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import {  
	    mapMutations  
	} from 'vuex';
	export default {
		components: {uniIcon},
		data(){
			return {
				xgnc:false,
				from:{
					headpic:0,
					nickname:'',
					sex:'',
					birthday:'',
				},
				current:1
			}
		},
		onLoad(options) {
			this._load()
		},
		methods:{
			set(){
				this.$api.http.post('user/edit_info',this.from).then(res=>{
					console.log(res)
					this.$api.msg('操作成功')
					setTimeout(()=>{
						uni.switchTab({
							url: '/pages/user/user',
						});
					},1500)
					
				})
				
			},
			_load() {
				// this.$api.loading()
				this.$api.http.get('user/info').then((res)=>{
					this.from = res	 
					uni.hideLoading()
				});
			},
			
			radioChange(e){
				this.from.sex=e.detail.value
			},
			change_nc(){
				this.xgnc=!this.xgnc
			},
			...mapMutations(['logout']),

			navTo(url){
				this.$api.msg(`跳转到${url}`);
			},
			//退出登录
			toLogout(){
				uni.showModal({
				    content: '确定要退出登录么',
				    success: (e)=>{
				    	if(e.confirm){
				    		this.logout();
				    		uni.clearStorageSync();
				    		uni.reLaunch({
				    			url:'/pages/public/login'
				    		})
				    	}
				    }
				});
			},
			//switch
			switchChange(e){
				let statusTip = e.detail.value ? '打开': '关闭';
				this.$api.msg(`${statusTip}消息推送`);
			},

		},
		onPullDownRefresh() {
			console.log('refresh');
			this._load();
			setTimeout(function () {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang='scss'>
	page{
		background: $page-color-base;padding-top: 10px;
	}
	.list{background-color: #fff;border-bottom: 1px solid #F8F8F8;padding: 10px;display: flex;justify-content: space-between;
		font-size:15px;
		.list_l{height: 40px;line-height: 40px;}
		.list_r{height: 40px;line-height: 40px;color: #999999;display: flex;
			.list_r_1{
				img{height: 40px;width: 40px;border-radius: 40px;}
				input{text-align: right;font-size: 13px;margin-top: 11px;}
			} 
			.list_r_2{padding-left: 5px;margin-top: -1px;font-size: 13px;}
			.uni-list-cell-pd{display: flex;}
			.nan{padding-right: 10px;}
		}
		
	}
	.nicheng{
		.tan_nc{background-color: #fff;z-index: 9999;position: fixed;top: 130px;left: 20%;width: 60%;
			.nc_in{padding: 30px 20px;}
			.nc_btn{display: flex;font-size:14px;border-top:1px solid #F4F4F4;
				.btn_l{width: 50%;text-align: center;padding: 15px 0;border-right: 1px solid #F4F4F4;}
				.btn_r{width: 50%;text-align: center;padding: 15px 0;}
			}
		}
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
	.list-cell{
		display:flex;
		align-items:baseline;
		padding: 20upx $page-row-spacing;
		line-height:60upx;
		position:relative;
		background: #fff;
		justify-content: center;
		&.log-out-btn{
			margin-top: 40upx;
			.cell-tit{
				color: $uni-color-primary;
				text-align: center;
				margin-right: 0;
			}
		}
		&.cell-hover{
			background:#fafafa;
		}
		&.b-b:after{
			left: 30upx;
		}
		&.m-t{
			margin-top: 16upx; 
		}
		.cell-more{
			align-self: baseline;
			font-size:$font-lg;
			color:$font-color-light;
			margin-left:10upx;
		}
		.cell-tit{
			flex: 1;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			margin-right:10upx;
		}
		.cell-tip{
			font-size: $font-base;
			color: $font-color-light;
		}
		switch{
			transform: translateX(16upx) scale(.84);
		}
	}
</style>
