<template>
	<view class="agent">
		<view class="head">
			共 <span>{{agentList.length}}</span> 人
		</view>
		<view class="t_tou">
			<view class="tou_1">头像</view>
			<view class="tou_1">昵称</view>
			<view class="tou_1">时间</view>
		</view>
		<view class="t">
			<None v-if="list_empty"></None>
			<view class="agent" v-else v-for="(item,index) of agentList" :key="index">
				<li class="t_01" v-if="item.user">
					<view class="t_01_1"> <img :src="item.user.headpic"></img> </view>
					<view class="t_01_1">{{item.user.nickname}}</view>
					<view class="t_01_1">{{item.create_time}}</view>
				</li>
			</view>
		</view>
	</view>
</template>

<script>
	import None from "@/components/qy/none.vue" 
	export default {
		data() {
			return {
				agentList:[],
				
				length:0,
				list_empty:false
			};
		},
		components: {None},
		onLoad() {
			this._load()
		},
		methods:{
			_load(){
				this.$api.http.get('fx/user/get_bind_user').then(res=>{
					this.agentList = res.data
				})
			}
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

<style lang="less">
.agent{
	.none{padding: 150px 0;text-align: center;color:#00A9F4;line-height:50px;
		img{width: 60px;height: 60px;}
	}
	.head{background-color: #E0451D;color: #fff;text-align: center;padding: 40px 10px 30px;}
	.head span{font-size: 30px;padding: 0 5px;font-size: 18px}
	.t_tou{display: flex;padding: 10px;height: 40px;line-height: 30px;}
	.tou_1{width: 34%;text-align: center;font-size: 14px;font-weight: bold;}
	.t_01{display: flex;height: 50px;line-height: 50px; 
	}
	.t_01_1{width: 34%;text-align: center;font-size: 14px}
	.t_01_1 img{ margin-top:8px;width: 35px;height: 35px;border-radius: 35px;}
	.t li:nth-of-type(odd){ background-color: #EEEEEE;} 
	.t li:nth-of-type(even){background-color: #fff;} 

}
</style>
