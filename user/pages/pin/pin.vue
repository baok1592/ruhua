<template>
	<view class="pin">
		<view class="navbar">
			<view v-for="(item, index) in navList" :key="index" class="nav-item" :class="{current: tabCurrentIndex === index}"
			 @click="tabClick(index)">
				{{item.text}}
			</view>
		</view>
		<view v-if="tabCurrentIndex == 0 ">
			<None @emits="jump_all_pro" v-if="navList[0].orderList=='' && list_empty" guang="去逛逛"></None>
			<view class='product' v-for="(item,index) of navList[0].orderList" :key="index" v-else>
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
		<view v-if="tabCurrentIndex == 1">
			<None @emits="jump_all_pro" v-if="navList[1].orderList=='' && list_empty" guang="去逛逛"></None>
			<view class='product' v-for="(item,index) of navList[1].orderList" :key="index" v-else>
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
</template>

<script>
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				list_empty: false,
				// time:new Date().getTime(),
				getimg:this.$getimg,
				list:[],
				tabCurrentIndex: 0,
				navList: [{
						state: 0,
						text: '今日拼团',
						loadingType: 'more',
						orderList: []
					},
					{
						state: 1,
						text: '拼团抢先知',
						loadingType: 'more',
						orderList: []
					}
				],
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
				this.$api.http.get('pt/get_pt_goods?type=1').then(res=>{
					this.list=res.data
					this.qufen(res.data)
				})
			},
			jump_all_pro(){ 
				uni.navigateTo({
					url:'/pages/extend-view/productList/productList?cid=0'
				})
			},
			//活动商品分类 是否今日拼团
			qufen(data){
				const that = this
				let now_time = new Date().getTime()
				let start_time = ''
				for (let k in data) {
					start_time = new Date(data[k].pt.start_time).getTime()
					if(start_time > now_time ){
						that.navList[1].orderList.push(data[k])
						this.list_empty=true
					}else{
						that.navList[0].orderList.push(data[k])
						this.list_empty=true
					}
				}
			},
			jump_pro(id){
				uni.navigateTo({
					url: '/pages/extend-view/productDetail/productDetail?id='+id
				});
			},
			//顶部tab点击
			tabClick(index) {
				console.log(index)
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
.pin{font-size: 14px;font-weight: 500;background-color: #fff;min-height: 100vh;
	.navbar {
		display: flex;
		height: 40px;
		padding: 0 5px;
		background: #fff;
		box-shadow: 0 1px 5px rgba(0, 0, 0, .06);
		position: relative;
		z-index: 10;
	
		.nav-item {
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 15px;
			color: $font-color-dark;
			position: relative;
	
			&.current {
				color: $base-color;
	
				&:after {
					content: '';
					position: absolute;
					left: 50%;
					bottom: 0;
					transform: translateX(-50%);
					width: 44px;
					height: 0;
					border-bottom: 2px solid $base-color;
				}
			}
		}
	}
	
	.product{display: flex;padding: 10px;border-bottom: 1px solid #F6F6F6;justify-content: space-between;
		.pro_l{width: 90px;position: relative;flex-shrink: 0;position: relative;}
		.pro_l img{width: 100%;height: 90px;}
		.xian{position: absolute;top: 0;left: 0;background-color: #FF4646;color: #fff;font-size: 12px;padding: 2px 9px 4px 3px;
		border-bottom-right-radius: 20px;}
		.pro_r{padding-left: 15px;box-sizing: border-box;flex-grow: 1;
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
</style>
