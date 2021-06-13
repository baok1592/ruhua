<template>
	<view class="like">
		<!-- <uni-segmented-control :current="current" :values="items" @clickItem="onClickItem" style-type="text" active-color="#4cd964"></uni-segmented-control> -->
		<view class="content">
			<view v-show="current === 0">
				<view>
					<None v-if="list_empty"></None>
					<view v-for="(item,index) of likeList" :key="index" v-else>
						<view class="des">
							<view class="des_1" @click="jump_toporduct(item.fav_id)">
								<img :src="getimg + item.imgs" />
							</view>
							<view class="des_2">
								<view class="msg">
									<view class="name">{{item.product.goods_name}}</view>
									<view class="int">{{item.int}}</view>
									<view class="price">¥{{item.price}}</view>
								</view>
								<view class="btn1">
									<view class="btn" @click="del(item.fav_id)">取消收藏</view>
									<view class="btn" @click="jump_toporduct(item.fav_id)">去购买</view>
								</view>
							</view>
						</view>
						<view class="kong"></view>
					</view>
				</view>
			</view>
			<view v-show="current === 1">
				<None v-if="list_empty"></None>
			</view>
		</view>
	</view>
</template>

<script>
	import uniSegmentedControl from "@/components/uni/uni-segmented-control/uni-segmented-control.vue"
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				getimg:this.$getimg,
				likeList: '',
				id: '',
				list_empty: false,
				items: ['商品', '商店'],
				current: 0
			}
		},
		components: {
			None,
			uniSegmentedControl
		},
		onLoad(option) {
			this._load()
			this.id = option.id
		},
		onShow(option) {
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('favorite/get_all_fav').then(res=>{
					// this.likeList = res.data.goods
					if(!res.data.goods){
						this.list_empty=true
					}else{
						this.likeList = res.data.goods
					}
					console.log(this.likeList)
				})
			},
			del(id) {
				
				const that = this
				uni.showModal({
					title: '提示',
					content: '确定取消？',
					success: function(res) {
						if (res.confirm) {
							that.$api.http.put('/favorite/del_fav', {
								id:id
							}).then(res => {
								that.$api.msg('取消成功')
								that._load()
								
							})
							console.log('用户点击确定');
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});


			},
			jump_toporduct(id) {
				uni.navigateTo({
					url: '/pages/extend-view/productDetail/productDetail?id=' + id,
				})
			},
			onClickItem(index) {
				if (this.current !== index) {
					this.current = index;
				}
			}
		},
		onPullDownRefresh() {
			console.log('refresh');
			this._load();
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
	.like{background-color: #F0F0F0;min-height: 100vh;}
	.des {
		display: flex;
		padding-top: 10px;
		background-color: #fff;
		padding-bottom: 5px;
		justify-content: space-between
	}

	.des_1 {
		padding: 10px 5px
	}

	.des_1 img {
		width: 30vw;
		height: 30vw;
	}

	.des_2 {
		display: flex;
		flex-direction: column;
		padding-top: 10px;
		width: 63%
	}

	.box {
		display: flex;
		justify-content: space-around;
		background-color: #F8F8FF;
		height: 200px;
		width: 100%
	}

	.msg {
		height: 80px
	}

	.productImg img {
		width: 120px;
		height: 120px;
		padding-left: 10px;
		padding-right: 10px;
	}

	.btn1 {
		height: 30px;
		display: flex;
		justify-content: flex-end;
		padding-right: 10px;
	}

	.btn {
		display: flex;
		font-size: 14px;
		border: 1px solid #C0C0C0;
		height: 25px;
		width: 70px;
		border-radius: 15px;
		justify-content: center;
		align-items: center;
		margin: 5px
	}

	.name {
		font-size: 15px;
	}

	.int {
		font-size: 15px;
		color: #A8A8A8
	}

	.price {
		font-size: 15px;
		color: #FF6600
	}

	.kong {
		height: 20upx
	}
</style>
