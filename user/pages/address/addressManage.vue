<template>
	<view class="content">
		<view class="row b-b">
			<text class="tit">联系人</text>
			<input class="input" type="text" v-model="form.name" placeholder="收货人姓名" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">手机号</text>
			<input class="input" type="number" v-model="form.mobile" placeholder="收货人手机号码" placeholder-class="placeholder" />
		</view>
		<view class="row b-b">
			<text class="tit">地区</text>
			<view class="result" @click="toggleTab">{{resultInfo.result}}</view>
			<!-- <input class="input" type="text" v-model="form.city" placeholder="楼号、门牌" placeholder-class="placeholder" @tap="choose_region" /> -->
			<w-picker mode="region" :defaultVal="['北京市','市辖区','东城区']" @confirm="onConfirm" ref="region"></w-picker>

		</view>
		<view class="row b-b">
			<text class="tit">详细</text>
			<input class="input" type="text" v-model="form.detail" placeholder="楼号、门牌" placeholder-class="placeholder" />
		</view>

		<!-- <view class="row default-row">
			<text class="tit">设为默认</text>
			<switch :checked="addressData.defaule" color="#fa436a" @change="switchChange" />
		</view> -->
		<view class="btn"> 
			<button class="add-btn" v-if="type == 'edit'" @click="del">删除</button>
			<button class="add-btn" v-if="type == 'add'" @click="sub_add">提交</button>
			<button class="add-btn" v-if="type == 'edit'" @click="sub_edit">提交</button>
		</view>

	</view>
</template>

<script>
	import wPicker from "@/components/w-picker/w-picker.vue";
	export default {
		data() {
			return {
				type: '',
				del_id:'',
				form: {
					name: '',
					mobile: '',
					city: '',
					detail: '',
					province: '',
					city: '',
					county: '',
					detail: '',
					areacode:''
				},
				tabIndex: 0,
				mode: "range",
				tabList: [{
					mode: "region",
					name: ""
				}],
				resultInfo: {
					result: '请选择地区'
				}
			}
		},
		onLoad(option) {
			console.log(option)
			if (option.type == 'edit') {
				let cache = uni.getStorageSync('edit_data')
				this.form = cache
				this.type = 'edit'
				this.del_id = cache.id
				this.resultInfo.result = cache.province + cache.city +cache.county
			}
			else if (option.type == 'add') {
				this.type = 'add'
			}
			console.log(this.form);
		},
		components: {
			wPicker
		},
		onShow() {

		},
		methods: {
			del(){
				this.$api.http.put('address/del_address',{id:this.del_id}).then(res=>{
					this.$api.msg('删除成功');
					setTimeout(() => {
						uni.navigateBack()
					}, 1000)
				})
			},
			toggleTab() {
				this.$refs['region'].show();
			},
			onConfirm(val) {
				console.log(val);
				//如果页面需要调用多个mode类型，可以根据mode处理结果渲染到哪里;
				// switch(this.mode){
				// 	case "date":
				// 		break;
				// }
				this.resultInfo = val;
				// this.form.city = this.resultInfo.result
				this.form.province = val.checkArr[0]
				this.form.city = val.checkArr[1]
				this.form.county = val.checkArr[2]
				console.log(this.form)
			},

			sub_edit(id) {
				const that = this
				const data = this.form
				this.$api.http.post('address/edit_address', data).then(res => {
					this.$api.msg('修改成功');
					setTimeout(() => {
						uni.navigateBack()
					}, 1000)
				})

			},
			sub_add() {
				const data = this.form
				if(data.mobile.length != 11){
					this.$api.msg('请输入正确的的手机号')
					return
				}
				this.$api.http.post('address/add_address', data).then(res => {
					this.$api.msg('添加成功');
					setTimeout(() => {
						uni.navigateBack()
					}, 1000)
				})
			}

		}
	}
</script>

<style lang="scss">
	page {
		background: $page-color-base;
		padding-top: 16upx;
	}

	.row {
		display: flex;
		align-items: center;
		position: relative;
		padding: 0 30upx;
		height: 110upx;
		background: #fff;

		.tit {
			flex-shrink: 0;
			width: 120upx;
			font-size: 30upx;
			color: $font-color-dark;
		}

		.input {
			flex: 1;
			font-size: 30upx;
			color: $font-color-dark;
		}

		.icon-shouhuodizhi {
			font-size: 36upx;
			color: $font-color-light;
		}
	}

	.default-row {
		margin-top: 16upx;

		.tit {
			flex: 1;
		}

		switch {
			transform: translateX(16upx) scale(.9);
		}
	}

	.btn {

		display: flex;

		.add-btn {
			width: 47%;
			height: 80upx;
			margin: 60upx auto;
			font-size: $font-lg;
			color: #fff;
			background-color: $base-color;
			border-radius: 10upx;
			box-shadow: 1px 2px 5px rgba(219, 63, 96, 0.4);
		}
	}



	.tab {
		color: #f00;
		padding: 20upx 0;
		font-size: 32upx;
	}

	.tab.active {
		color: #999999;
	}

	.result {
		color: #999999;
		margin-right: 5%;width: 100%;
		font-size: 32upx;
	}
</style>
