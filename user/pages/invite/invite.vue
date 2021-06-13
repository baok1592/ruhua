<template>
	<view class="invite">
		<view class="head">
			<view class="top">
				<view class="mess">
					<view class="mess_l">
						<img :src="getimg +img"></img>
					</view>
					<view class="mess_r">
						<view class="mess_tit">{{name}}</view>
						<view class="mess_price">
							<!-- ¥<span>{{buy_data[0].price}}</span> -->
							<xianshi  title="拼团" :price="price"></xianshi>
						</view>
					</view>
				</view>
				<view class="tishi">七天退货·自提·发货&售后</view>
			</view>
		</view>
		<view class="H100"></view>
	
		<view class="jr">
			<view class="jr_tit">
				还差<span>{{tuan_data.num}}人</span>拼购成功，时间仅剩
				<!-- <uni-countdown :show-day="false" :hour="remain.h" :minute="remain.m" border-color="#fff"
				:second="remain.s" color="#fff" background-color="#FE201F" splitor-color="#FE201F"></uni-countdown> -->
				<uni-countdown :show-day="false" :hour="remain.h" :minute="remain.m" :second="remain.s"></uni-countdown>
				
			</view>
			<view class="jr_img">
				<view class="img_01 img_01_border">
					<img :src="tuan_data.c_pic"></img>
					<view class="zhicheng">团长</view>
				</view>
				<view class="img_01" v-for="(item,index) of tuan_data.item_pic" :key="index">
					<img :src="item"></img>
				</view>
				<view class="img_01" v-for="(item,index) of tuan_data.num" :key="index">
					?
				</view>
			</view>
		</view>
		<view class="btn">邀请好友参团</view>
	</view>
</template>

<script>
	import uniCountdown from "@/components/uni/uni-countdown/uni-countdown.vue"
	import xianshi from "@/components/qy/xianshi"
	export default {
		data() {
			return {
				name:"",
				price:'',
				img:'',				
				remain:'',
				id:'',
				buy_data:'',
				getimg:this.$getimg,
				tuan_data:''
			};
		},
		components:{
			xianshi,
			uniCountdown
		},
		onLoad(option) {
			this.buy_data = uni.getStorageSync('buy')
			this.id = option.id
			this.img = option.img
			this.price = option.price
			this.name = option.name
			this.get_one_tuan()
			console.log(this.buy_data)
		},
		methods:{
			get_one_tuan(){
				this.$api.http.get('pt/get_one_item',{id:this.id}).then(res=>{
					this.tuan_data = res.data
					this.remain = this.zhuan_time(res.data.item_time)
					console.log(this.remain)
				})
			},
			zhuan_time(end) {
				let obj = {
					h: '',
					m: '',
					s: ''
				}
				let start = new Date().getTime()
				let remain = end * 1000 - start
				let h = parseInt(remain / 1000 / 60 / 60 % 24)
				let m = parseInt(remain / 1000 / 60 % 60)
				let s = parseInt(remain / 1000 % 60)
				console.log(h + '-' + m + '-' + s)
				obj.h = h
				obj.m = m
				obj.s = s
				return obj
			},
		}
	}
</script>

<style lang="scss">
.invite{background-color: #fff;min-height: 100vh;font-size: 14px;
	.head{background-color: #FB6129;height: 100px;border-radius: 0 0 5px 5px;position: relative;
		.top{position: absolute;top: 20px;left: 2%;width: 96%;background-color: #fff;padding: 10px;border-radius: 3px;
		box-shadow: 0 1px 5px #E5E5E5;
			.mess{display: flex;	
				.mess_l img{width: 120px;height: 120px;border-radius: 3px;}
				.mess_r{flex-grow: 1;padding-left: 10px;display: flex;flex-direction: column;justify-content: space-between;
					.mess_tit{font-size: 14px;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;
					-webkit-line-clamp: 2; -webkit-box-orient: vertical;}
					.mess_price{display: flex;color: #FE201F;align-items:baseline ;
						span{font-size: 30px;padding-right: 8px;}
					}
				}
			}
			.tishi{padding: 20px 0 0px;color: #B5B5B5;font-size: 12px;}
		}
	}
	.H100{height: 110px;}
	.jr{padding:0 10px 10px;font-size: 14px;
		.jr_tit{font-weight: 600;text-align: center;padding-bottom: 10px;
			span{color: #FE201F;}
		}
		.jr_img{display: flex;justify-content: center;
			.img_01{position: relative;border-radius: 50%;margin: 0 7px;width: 44px;height: 44px;
			border: 2px dashed #E6E6E6;text-align: center;line-height: 44px;color:#E6E6E6;
				img{width: 40px;height: 40px;border-radius: 50%;}
				.zhicheng{position: absolute;top: -8px;left: 0;background-color:#E93B3D;color: #fff;
				font-size: 12px;border-radius: 10px;width: 40px;text-align: center;line-height: 16px;}
			}
			.img_01_border{border: 2px solid #E93B3D;}
		}
	}
	.btn{background-color: #ED3F14;color: #fff;height: 40px;line-height: 40px;padding: 0 12px;
	border-radius: 3px;margin: 10px;text-align: center;}
}
</style>
