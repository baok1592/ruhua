<template>
	<view class="pindetail">
		<view class="head">
			<img src="../../imgs/1.jpg"></img>
			<view class="pin">
				<view class="jr">
					<view class="jr_tit">
						<img src="@/imgs/12.png"></img>
						恭喜您拼购成功！
					</view>
					<view class="jr_img">
						<view class="img_01 img_01_border">
							<img :src="item.c_pic"></img>
							<view class="zhicheng">团长</view>
						</view>
						<block v-for="imgs of item.item_pic">
							<view class="img_01 img_01_border">
							<img :src="imgs"></img>
						</view>
						</block>
						
						<view class="img_01" v-for="items of item.num">
							?
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="btn" @click="jump_detail">查看订单</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				item:'',
			};
		},
		onLoad(option) {
			this.get_pt_item(option.id)
		},
		methods:{
			get_pt_item(id){
				this.$api.http.get('pt/get_one_item?id='+id).then(res=>{
					this.item = res.data
				})
			},
			jump_detail(){
				uni.navigateTo({
					url:'/pages/user/myorder/myorder'
				})
			}
		}
	}
</script>

<style lang="scss">
.pindetail{
	.head{position: relative;margin-bottom: 50px;
		img{width: 100%;height: 170px;}
		.pin{background-color: #fff;position: absolute;top: 70px;left: 5%;width: 90%;border-radius: 5px;
		padding: 20px;box-shadow: 0 1px 5px #E5E5E5;}
		.jr{padding:0 10px 10px;font-size: 14px;
			.jr_tit{padding-bottom: 10px;display: flex;justify-content: center;padding-bottom: 20px;
				img{width: 20px;height: 20px;margin-right: 10px;}
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
	}
	.btn{border: 1px solid #DADADA;border-radius: 3px;height: 45px;line-height: 45px;text-align: center;margin: 10px;}
}
</style>
