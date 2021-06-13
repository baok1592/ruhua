<template>
	<view class="cart">
		<!-- 列表 -->
		<view class="cart-list">
			<block v-for="(item, k) in cartList" :key="item.id">
				<view class="cart-item">
					<view class="image-wrappers">
						<img :src="getimg+item.imgs" @click="jump_detail(item.goods_id)" ></img>
						<view class="yticon icon-xuanzhong2 checkbox" :class="{checked: item.radio}" @click="_radio('item', k)"></view>
					</view>
					<view class="item-right">
						<text class="clamp title" @click="jump_detail(item.goods_id)">{{item.goods_name}}</text>
						<text class="attr">{{item.attr_val}}</text>
						<text class="price">¥{{item.price}}</text>
						<view class="item-right-num">
							<tui-numberbox :max="item.stock" :min="1" :value="item.num" @change="numberChange($event,k)"></tui-numberbox> 
						</view>
					</view>
					<text class="del-btn yticon icon-fork" @click="deleteCartItem(k)"></text>
				</view>
			</block>
		</view> 
		<!-- 底部菜单栏 -->
		<view class="action-section">
			<view class="checkbox">

				<!-- <image :src="allChecked?'/static/selected.png':'/static/select.png'" mode="aspectFit" @click="check('all')"></image> -->
				<view class="clear-btn" v-if="cartList" @click="clearCart">
					清空
				</view>
			</view>
			<view class="total-box">
				<text class="price">¥{{total}}</text>
				<!-- <text class="coupon">
					已优惠
					<text>1</text>
					元
				</text> -->
			</view>
			<button type="primary" class="no-border confirm-btn" @click="createOrder">去结算</button>
		</view>

	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex';
	//import uniNumberBox from '@/components/uni-number-box.vue'
	import tuiNumberbox from "@/components/numberbox/numberbox"
	export default {
		components: {
			//uniNumberBox,
			tuiNumberbox
		},
		data() {
			return {
				getimg:this.$getimg,
				allChecked: false, //全选状态  true|false
				empty: false, //空白页现实  true|false
				cartList: [],
			};
		},
		onLoad() {  
		},
		onShow() {
			this._load()
		},
		watch: { 
		},
		computed: { 
			//计算总价
			total() {
				let total = 0; 
				let list = Object.values(this.cartList);
				if (list.length === 0) {
					this.empty = true;
					return total;
				}
				let checked = true;
				list.forEach(item => {
					if (item.radio === true) {
						total += item.price * item.num;
					}  
				}) 
				total = Number(total.toFixed(2));
				return total;
			}
		},
		methods: {		
			_load() {
				let cache = uni.getStorageSync('cart')
				console.log('cache',cache)
				if (!cache) {
					return;
				} else { 
					this.cartList = cache;
					console.log(this.cartList)
				}
			},
			jump_detail(id){
				uni.navigateTo({
					url:'../extend-view/productDetail/productDetail?id='+id
				})
			},
			//数量
			numberChange(e,index) {
				console.log('num:', e.value,index)
				this.$set(this.cartList[index],'num',e.value)
				uni.removeStorageSync('cart')
				this.cartList
				uni.setStorageSync('cart',this.cartList) 
			}, 		
			// 监听image加载完成
			onImageLoad(key, index) {
				this.$set(this[key][index], 'loaded', 'loaded');
			},
			//监听image加载失败
			onImageError(key, index) {
				this[key][index].image = '/static/errorImage.jpg';
			},
			navToLogin() {
				uni.navigateTo({
					url: '/pages/public/login'
				})
			},
			//选中状态处理
			_radio(type, index) {
				if (type === 'item') {
					//单选 
					this.cartList[index].radio = !this.cartList[index].radio;
					uni.removeStorageSync('cart')
					uni.setStorageSync('cart',this.cartList)
				} else {
					//全选
					const checked = !this.allChecked
				    let list = this.cartList;
					list.forEach(item => {
						item.radio = checked;   
					})
					this.allChecked = checked;
				} 
			},
			
			//删除
			deleteCartItem(index) {  
				let cartList=this.cartList 
				delete cartList[index]; 
				this.cartList=[]
				this.cartList=cartList 
				uni.setStorageSync('cart',cartList)  
			},
			//清空
			clearCart() {
				uni.showModal({
					content: '清空购物车？',
					success: (e) => {
						if (e.confirm) {
							this.cartList = '';
							uni.removeStorageSync('cart')
						}
					}
				})
			},
			
			//创建订单
			createOrder() { 				
				let buy_data = uni.getStorageSync('cart') 
				let x=0 
				for(let k in buy_data){
					const v=buy_data[k] 
					if(v.radio){ 
						x++
					} 
				}
				if(x>0){
					uni.navigateTo({
						url: '/pages/order/createOrder?state=car'
					})
				}				
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

<style lang='scss'>
	.cart {
		padding-bottom: 134upx;

		/* 空白页 */
		.empty {
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 100vh;
			padding-bottom: 100upx;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
			background: #fff;

			image {
				width: 240upx;
				height: 160upx;
				margin-bottom: 30upx;
			}

			.empty-tips {
				display: flex;
				font-size: $font-sm+2upx;
				color: $font-color-disabled;

				.navigator {
					color: $uni-color-primary;
					margin-left: 16upx;
				}
			}
		}

	/* 购物车列表项 */
	.cart-item {
		display: flex;
		position: relative;
		padding: 30upx 40upx;

		.image-wrappers { 
			width: 230upx !important;
			height: 230upx !important;
			flex-shrink: 0;
			position: relative;

			img { 
				width: 230upx !important;
				height: 230upx !important;
				border-radius: 8upx;
			}
		} 

		.checkbox {
			position: absolute;
			left: -16upx;
			top: -16upx;
			z-index: 8;
			font-size: 44upx;
			line-height: 1;
			padding: 14upx;
			color: $font-color-disabled;
			background: #fff;
			border-radius: 50px;
		}

		.item-right {
			display: flex;
			flex-direction: column;
			flex: 1;
			overflow: hidden;
			position: relative;
			padding-left: 30upx;

			.title,
			.price {
				font-size: $font-base + 2upx;
				color: $font-color-dark;
				height: 40upx;
				line-height: 40upx;
			}

			.attr {
				font-size: $font-sm + 2upx;
				color: $font-color-light;
				height: 50upx;
				line-height: 50upx;
			}

			.price {
				height: 50upx;
				line-height: 50upx;
			}
			.item-right-num{
				text-align: right;
			}
		}

		.del-btn {
			padding: 4upx 10upx;
			font-size: 34upx;
			height: 50upx;
			color: $font-color-light;
		}
	}

	/* 底部栏 */
	.action-section {
		/* #ifdef H5 */
		margin-bottom: 100upx;
		/* #endif */
		position: fixed;
		left: 30upx;
		bottom: 30upx;
		z-index: 95;
		display: flex;
		align-items: center;
		width: 690upx;
		height: 100upx;
		padding: 0 30upx;
		background: rgba(255, 255, 255, .9);
		box-shadow: 0 0 20upx 0 rgba(0, 0, 0, .5);
		border-radius: 16upx;

		.checkbox {
			height: 52upx;
			position: relative;

			image {
				width: 52upx;
				height: 100%;
				position: relative;
				z-index: 5;
			}
		}

		.clear-btn {
			position: absolute;
			left: 26upx;
			top: 0;
			z-index: 4;
			width: 110upx;
			height: 52upx;
			line-height: 52upx;
			padding-left: 20upx;
			font-size: $font-base;
			color: #fff;
			background: $font-color-disabled;
			border-radius: 0 50px 50px 0; 
 
		}

		.total-box {
			flex: 1;
			display: flex;
			flex-direction: column;
			text-align: right;
			padding-right: 40upx;

			.price {
				font-size: $font-lg;
				color: $font-color-dark;
			}

			.coupon {
				font-size: $font-sm;
				color: $font-color-light;

				text {
					color: $font-color-dark;
				}
			}
		}

		.confirm-btn {
			padding: 0 38upx;
			margin: 0;
			border-radius: 100px;
			height: 76upx;
			line-height: 76upx;
			font-size: $font-base + 2upx;
			background: $uni-color-primary;
			box-shadow: 1px 2px 5px rgba(217, 60, 93, 0.72)
		}
	}

	/* 复选框选中状态 */
	.action-section .checkbox.checked,
	.cart-item .checkbox.checked {
		color: $uni-color-primary;
	}

}	
</style>
