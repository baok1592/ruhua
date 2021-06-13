<template>
	<view class="xuan">
		<view class="product" v-for="(item,index) of my_product">
			<view class="pro_01"><uni-icon type="checkbox-filled" size="25" color="#D6D6D6" @click="choose(item.id)"></uni-icon></view>
			<view class="pro_02"><img :src="item.imgs"></img></view>
			<view class="pro_03">
				<view class="pro_03_tit">{{item.goods_name}}</view>
				<view class="pro_03_p">¥ {{item.price}}</view>
			</view>
		</view>
		<view class="foot">
			<view class="foot_l">
				<uni-icon type="checkbox-filled" size="25" color="#D6D6D6"></uni-icon>
				<span>全选</span>
			</view>  
			<view class="foot_r" @click="back">确定</view>
		</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				my_product:[],
				id:[]
			};
		},
		components: {uniIcon},
		onLoad() {
			this.get_all_product()
		},
		methods:{
			get_all_product(){
				this.my_product=this.$api.json.my_product
			},
			choose(id){
				this.id.push(id)
				
				console.log(this.id)
			},
			back(){
				uni.setStorageSync('pro_id_list',this.id)
				uni.navigateBack({
					
				})
			}
		}
	}
</script>

<style lang="scss">
.xuan{background-color: #F6F6F6;min-height: 100vh;
	.product{margin: 5px 0 10px;padding: 10px 0;display: flex;background-color:#fff;
		.pro_01{padding: 0 10px;display: flex;flex-direction: column;justify-content: center;}
		.pro_02 img{width: 30vw;height: 20vw;}
		.pro_03{padding-left: 10px;display: flex;flex-direction: column;justify-content: space-between;
			.pro_03_tit{font-size: 16px;height: 40px;line-height: 20px;overflow: hidden;}
		}
	}
	.foot{background-color: #fff;position: fixed;bottom: 0;left: 0;display: flex;justify-content: space-between;
	padding: 10px;box-sizing: border-box;width: 100%;
		.foot_l{display: flex;line-height: 27px;padding-top: 8px;
			span{padding-left: 10px;}
		} 
		.foot_r{background-color: #FA1919;color: #fff;border-radius: 5px;height: 40px;line-height: 40px;text-align: center;
		width: 100px;}
	}
}
</style>
