<template>
	<view class="content b-t">
		<None v-if="list_empty"></None>
		<view class="list b-b" v-for="(item, index) in addressList" :key="index" @click="checkAddress(item)" v-else>
			<view class="wrapper">
				<view class="address-box">
					<text v-if="item.is_default" class="tag">默认</text>
					<text v-if="!item.is_default" class="tag" @click="set_default(item.id)">设默认</text>
					<text class="address">{{item.province}} {{item.city}}{{item.county}}</text>
				</view>
				<view class="u-box">
					<text class="name">{{item.name}}</text>
					<text class="mobile">{{item.mobile}}</text>
				</view>
			</view>
			<text class="yticon icon-bianji" @click.stop="addAddress('edit', item)"></text>
		</view>
		<!-- <text style="display:block;padding: 16upx 30upx 10upx;lihe-height: 1.6;color: #fa436a;font-size: 24upx;">
			重要：添加和修改地址回调仅增加了一条数据做演示，实际开发中将回调改为请求后端接口刷新一下列表即可
		</text> -->
		<view class="add-btn">
			<button type="warn" style="width: 45%;" @click="addAddressA('add')">&emsp;新增地址</button>
			<!-- #ifdef MP-WEIXIN -->
			<button type="" style="width: 45%;background-color: #16AB60;color: #fff;" @click="get_address">微信地址</button>
			<!-- #endif -->
		</view>


	</view>
</template>

<script>
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				list_empty: false,
				form: {
					name: '',
					mobile: '',
					city: '',
					detail: '',
					province: '',
					county: '',
					detail: '',
					areacode:''
				},
				wx_address: '',
				source: 0,
				addressList: [],
				type_add: 'add'
			}
		},
		components:{
			None
		},
		onLoad(option) {
			this.source = option.source;
			this._load()
		},
		onShow() {
			this._load()
		},
		methods: {
			get_address() {
				const that = this
				let address = ''
				uni.chooseAddress({
					success(res) {
						address = res
						that.sub_address(res)
					},
				})
				console.log(address)
			},
			sub_address(res){
				this.form.name = res.userName
				this.form.mobile = res.telNumber
				this.form.city = res.cityName
				this.form.county = res.countyName
				this.form.detail = res.detailInfo
				this.form.province = res.provinceName
				this.form.areacode = res.postalCode
				console.log(this.form)
				this.$api.http.post('address/add_address', this.form).then(res => {
					this.$api.msg('添加成功');
					setTimeout(() => {
						uni.navigateBack()
					}, 1000)
				})
			},
			_load() {
				this.$api.http.get('address/get_all_address').then(res => {
					if (res.data=='') {
						this.list_empty = true
					} else {
						this.addressList = res.data
					}
				})
			},
			//设置默认地址
			set_default(id) {
				this.$api.http.post('address/set_default_address', {
					id: id
				}).then(res => {
					this.$api.msg('成功更改默认地址')
					this._load()
				})
			},
			//选择地址
			checkAddress(item) {
				if (this.source == 1) {
					//this.$api.prePage()获取上一页实例，在App.vue定义
					this.$api.prePage().addressData = item;
					uni.navigateBack()
				}
			},
			addAddress(type, item) {
				uni.setStorageSync('edit_data', item)
				uni.navigateTo({
					url: `/pages/address/addressManage?type=` + type
				})
			},
			addAddressA() {
				uni.navigateTo({
					url: `/pages/address/addressManage?type=` + this.type_add
				})
			},
			//添加或修改成功之后回调
			refreshList(data, type) {
				//添加或修改后事件，这里直接在最前面添加了一条数据，实际应用中直接刷新地址列表即可
				this.addressList.unshift(data);
				console.log(data, type);
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
	page {
		padding-bottom: 120upx;
	}

	.content {
		position: relative;
	}

	.list {
		display: flex;
		align-items: center;
		padding: 20upx 30upx;
		;
		background: #fff;
		position: relative;
	}

	.wrapper {
		display: flex;
		flex-direction: column;
		flex: 1;
	}

	.address-box {
		display: flex;
		align-items: center;

		.tag {
			font-size: 24upx;
			width:100upx;
			color: $base-color;
			margin-right: 10upx;
			background: #fffafb;
			border: 1px solid #ffb4c7;
			border-radius: 4upx;
			padding: 4upx 10upx;
			line-height: 1;
		}

		.address {
			font-size: 30upx;
			color: $font-color-dark;
		}
	}

	.u-box {
		font-size: 28upx;
		color: $font-color-light;
		margin-top: 16upx;

		.name {
			margin-right: 30upx;
		}
	}

	.icon-bianji {
		display: flex;
		align-items: center;
		height: 80upx;
		font-size: 40upx;
		color: $font-color-light;
		padding-left: 30upx;
	}

	.add-btn {
		position: fixed;
		left: 30upx;
		right: 30upx;
		bottom: 16upx;
		z-index: 95;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 690upx;
		height: 80upx;
		font-size: 32upx;
		color: #fff;

		border-radius: 10upx;

	}
</style>
