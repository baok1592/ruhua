<template>
	<view class="discount">
		<None v-if="list_empty" guang="去逛逛"></None>
		<view class='concent' v-else>
			<view class='product' v-for="(item,index) of list" :key="index" >
				<view class='pro_l'>
				  <view class='pro_l_pic'  @click="jump_pro(item.goods_id)">
					  <img :src="getimg+item.goods.imgs"></img>
				  </view>
				  <view class="xian">限时折扣</view>
				</view>
				<view class='pro_r'>
				  <view class='pro_r_01'>{{item.goods.goods_name}}</view>
				  <view class='pro_r_02'>
					<view class='pro_r_02_l'>
					  <view class='pro_r_02_l_02'>
						  限时价: <span>¥{{item.goods.price-item.reduce_price}}</span>
						   <!-- 原价：{{item.goods.price}} -->
						   </view>
					</view>
					<view class='pro_r_02_r' @click="jump_pro(item.goods_id)">
						<view class="pro_qcj">去抢购</view>
					</view>
				  </view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				getimg:this.$getimg,
				list:[],
				list_empty: false,
			};
		},
		onLoad(){
			this._load()
		},
		components:{
			None
		},
		methods:{
			_load(){
				this.$api.http.get('discount/get_discount_goods').then(res=>{
					if (res.data=='') {
						this.list_empty = true
					} else {
						this.list = res.data
					}				
				})
			},
			jump_pro(id){
				uni.navigateTo({
					url: '/pages/extend-view/productDetail/productDetail?id='+id
				});
			}
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
.discount{
	background-color:#f7f7f7;min-height: 100vh;font-size: 14px;
	.concent{padding: 10px;background-color: #fff;}
	.product{display: flex;padding: 10px 0 ;border-bottom: 1px solid #F6F6F6;justify-content: space-between;}
	.pro_l{width: 90px;position: relative;flex-shrink: 0;position: relative;}
	.pro_l img{width: 100%;height: 90px;}
	.xian{position: absolute;top: 0;left: 0;background-color: #FF4646;color: #fff;font-size: 12px;padding: 2px 9px 4px 3px;
	border-bottom-right-radius: 20px;}
	.pro_r{padding-left: 15px;box-sizing: border-box;flex-grow: 1;
	display: flex;justify-content: space-between;flex-direction: column;}
	.pro_r_01{height: 40px;overflow: hidden;line-height: 20px;margin: 5px 0;overflow: hidden;}
	.pro_r_02{display: flex;justify-content: space-between;}
	.pro_r_02_l_02{color: #ABABAB;padding-top: 5px;font-size: 12px;}
	.pro_r_02_l_02 span{color: #E76C52;font-size: 22px;padding-right: 5px;}
	.pro_qcj{background-color: #FF4646;color: #fff;height: 30px;line-height: 30px;border-radius: 20px;text-align: center;padding: 0 10px;}
}
</style>
