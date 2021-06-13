<template>
	<None v-if="list_empty" guang="去逛逛"></None>
	<view v-else >
		<view class="onebuy" v-if="is_con">
			<view class="head">
				<img :src="web_url+'/static/web/26.jpg'"></img>
			</view>
			<view class="ob">
				<view class='product' v-for="(item,index) of list" :key="index" >
					<view class='pro_l'>
					  <view class='pro_l_pic'  @click="jump_pro(item.goods_id)">
						  <img :src="getimg+item.goods.imgs"></img>
					  </view>
					  
					</view>
					<view class='pro_r'>
					  <view class='pro_r_01'>{{item.goods.goods_name}}</view>
					  <view class='pro_r_02'>
						<view class='pro_r_02_l'>
						    <view class='pro_r_02_l_02'> 
							   拼团价: <span>¥{{(item.goods.price*10000 - item.price*10000)/10000}}</span>
							   <!--  -->
							</view>
							<view>单买价：{{item.goods.market_price}}</view>
						</view>
						<view class='pro_r_02_r' @click="jump_pro(item.goods_id)">
							<view class="pro_qcj">去拼单</view>
						</view>
					  </view>
					</view>
				</view>
			</view>
		</view>
	</view>
	
</template>

<script>
	import {
		Api_url
	} from '@/common/config'
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				web_url:Api_url,
				is_con:false,
				list_empty: false,
				getimg:this.$getimg,
				list:''
			};
		},
		components:{
			None
		},
		onLoad(){
			this._load() 
		},
		methods:{
			_load(){
				this.$api.http.get('pt/get_pt_goods?type=3').then(res=>{
					if (res.data=='') {
						this.list_empty = true
					} else {
						this.list = res.data
						this.is_con=true
					}			
				})
			},
			jump_pro(id){
				uni.navigateTo({
					url: '/pages/extend-view/productDetail/productDetail?id='+id
				});
			},
			//顶部tab点击
			tabClick(index) {
				this.tabCurrentIndex = index;
			},
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
.onebuy{font-size: 14px;font-weight: 500;background-color: #F7801C;min-height: 100vh;
	.head img{width: 100%;height: 200px;}
	.ob{padding: 10px;
		.product{display: flex;padding: 10px 0;justify-content: space-between;background-color: #ffffff;margin-bottom: 10px;
			.pro_l{width: 90px;position: relative;flex-shrink: 0;position: relative;}
			.pro_l img{width: 100%;height: 90px;}
			.xian{position: absolute;top: 0;left: 0;background-color: #FF4646;color: #fff;font-size: 12px;padding: 2px 9px 4px 3px;
			border-bottom-right-radius: 20px;}
			.pro_r{padding:0 10px 0 15px;box-sizing: border-box;flex-grow: 1;
			display: flex;justify-content: space-between;flex-direction: column;}
			.pro_r_01{height: 40px;overflow: hidden;line-height: 20px;margin: 5px 0;overflow: hidden;}
			.pro_r_02{display: flex;justify-content: space-between;}
			.pro_r_02_l{color: #ABABAB;font-size: 12px;
				.pro_r_02_l_02{color: #E76C52;font-size: 14px;}
				.pro_r_02_l_02 span{padding-right: 5px;font-weight: 600;}
			}
			
			.pro_qcj{background-color: #FF4646;color: #fff;height: 30px;line-height: 30px;border-radius: 20px;text-align: center;padding: 0 10px;}
		}
	}
}
</style>
