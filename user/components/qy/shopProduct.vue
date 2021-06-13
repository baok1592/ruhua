<template>
	<div class="shop">
		<view class="shop_01" @click="jump">
			<view class="shop_01_l">
				<img src="@/imgs/1.jpg"></img>
			</view>
			<view class="shop_01_m">
				<view class="shop_01_m_tit">名字</view>
				<view class="shop_01_m_ying">在销商品30件</view>
			</view>
			<view class="shop_01_r">
				进入店铺<span>></span>
			</view>
		</view>
		<view class="shop_02"></view>
		<view class="product">
			<view class="pro-tit">猜你喜欢</view>
			
			<scroll-view class="scroll-view_x" scroll-x style="width: auto;overflow:hidden;">
				<view class="pro">
					<view class="pro-01" v-for="(item,index) of pro" :key="index">
						<img src="@/imgs/1.jpg"></img>
						<p>这里是很长的商品描述再来几个字阿萨德哈市</p>
						<view  class="pro-01-p">¥78</view>
					</view>
				</view>
			</scroll-view>
			
		</view>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				pro:[1,2,3,4,5]
			};
		},
		props:{
			info:String
		},
		components: {
		},
		methods:{
			jump(){
				this.$emit("jump");
			}
		},
		mounted() {
			
		}
	}
</script>

<style lang="less">
.shop{background-color: #fff;font-size: 14px;margin-top: 10px;
	.shop_01{display: flex;padding: 10px;
		.shop_01_l img{width: 40px;height: 40px;border-radius: 5px;box-shadow: 4px 4px 5px #F0F0F1;}
		.shop_01_m{flex-grow: 1;padding: 0 20px 0 10px;
			.shop_01_m_tit{font-weight: 600;margin:0px 0 6px;height: 20px;line-height: 20px;overflow: hidden;font-size: 16px;}
			.shop_01_m_ying{color: #989897;}
		}
		.shop_01_r{height: 40px;line-height: 40px;color: #E15253;display: flex;
		justify-content: space-between;width: 80px;flex-shrink: 0;
			span{color: #D6D6D6;}
		}
	}
	.shop_02{background-color: #F5F5F5;height: 8px;}
	.product{
		.pro-tit{padding: 15px 10px;font-weight: 600;font-size: 15px;}
		.pro{display: flex;padding: 0 0 10px 10px;width: 100%;
			.pro-01{width:28%;padding-right: 1%;flex-shrink: 0;
				img{width: 100%;height: 105px;}
				p{margin: 5px 0;max-height: 36px;line-height: 18px;font-size: 12px;overflow: hidden;
				  text-overflow: ellipsis;
				  display: -webkit-box;
				  -webkit-line-clamp: 2;
				  -webkit-box-orient: vertical;}
				.pro-01-p{color: #E31436;text-align: center;}
			}
		}
	}
}
</style>
