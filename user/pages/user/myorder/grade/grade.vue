<template>
	<view class="grade">
		<view class="product">
			<view class="pic"><img :src="getimg + order.imgs.url"></img></view>
			<view class="title">
				<view class="tit_01">{{order.goods_name}}</view>
				<view class="tit_02">
					<view class="tit_02_l">x{{order.num}}</view>
					<view class="tit_02_r">共 {{order.total_price}}元</view>
				</view>
			</view>
		</view>
		<view class="BH10"></view>
		<view class="pingj">
			<view class="pj_c">
				<textarea style="height: 100px;" v-model="form.content" placeholder="请输入..."></textarea>
			</view>

			<view class="cu-form-group" style="width: 75%;">
				<view class="grid col-4 grid-square flex-sub" style="display: flex;flex-wrap: wrap;">
					<view  class="bg-img" v-for="(x,y) in ImgBox" :key="y" @tap="ViewImage($event,index)" :data-url="x">
						<view class="tu" style="margin:10px;">
							<img :src="x"></img>
						</view>
						
						<view class="cu-tag bg-red" @tap.stop="DelImg($event,index)" :data-index="y">
							<text class='cuIcon-close'></text>
						</view>
					</view>
					<view class="solids"  v-if="ImgBox.length<9">
						<view @tap="ChooseImage(index)" style="width: 50px;height: 50px;border: 1px solid #000000; display: flex;line-height: 50px;justify-content: center;">+</view>
					</view>
					<!-- <view class="solids" @tap="ChooseImage(index)" v-if="ImgBox.length<20">
							<text class='cuIcon-cameraadd'>+</text>
						</view> -->
				</view>
			</view>
			
		</view>
		<view class="BH10"></view>
		<view class="star">
			<view class="star_01">物流服务：<uni-rate value="0" @change="get_rate" :index="index"></uni-rate>
			</view>
		</view>
		<view class="BH10"></view>
		<view class="BH10"></view>
		<view class="BH10"></view>
		<view class="BH10"></view>
		<view class="BH10"></view>

		<view class="btn" @click="sub_grade">发布</view>
		<view style="height: 50px;"></view>
	</view>
</template>

<script>
	import {
		Api_url
	} from '@/common/config'
	import uniRate from "@/components/uni/uni-rate/uni-rate.vue"
	export default {
		data() {
			return {
				imgList: [],
				order: '',
				order_id: '',
				goods_id:'',
				getimg: this.$getimg,
				form: {
					id: '',
					goods_id: '',
					rate: '',
					content: '',
				},
				grade_list: '',
				ImgBox: []
			};
		},
		components: {
			uniRate
		},
		onLoad(option) {
			let cache = uni.getStorageSync('grade_pro')
			this.order = cache.data
			this.order_id = option.order_id
			this.goods_id = option.goods_id
			
			this.creat_obj()
		},
		onShow() {
			console.log('触发了onshow')
		},
		methods: {
			creat_obj() {
				let num = this.order.length
				console.log(num)
				let arr = []
				let ImgBox = []
				let obj = {
					goods_id: '',
					content: '',
					imgs: [],
					rate: ''
				}
				const that = this
				for (var i = 0; i < num; i++) {
					arr[i] = obj
					ImgBox[i] = []
				}
				this.grade_list = arr
				this.ImgBox = ImgBox
				console.log('ImgBox:', this.ImgBox)
			},
			async sub_grade() {
				uni.showLoading({
					title: '提交中'
				});
				uni.hideLoading();
				// for (let s in this.order) {
				// 	let v = this.order[s]
				// 	//				    this.grade_list[s].imgs = await this.uploads(s) 
				// 	await this.uploads(s)
				// 	this.grade_list[s].content = v.content
				// 	this.grade_list[s].goods_id = v.goods_id
				// 	this.grade_list[s].rate = v.rate
				// 	// this.grade_list[s].imgs = []
				// }
				// let obj = {
				// 	id: this.order_id,
				// 	json: this.grade_list
				// }
				
				this.form.imgs = await this.uploads()
				// console.log(obj)
				this.form.id = this.order_id
				this.form.goods_id = this.goods_id
				console.log(this.form);
				this.$api.http.post('order/user/set_pj', this.form).then(res => {
					this.$api.msg('发布成功')
					uni.hideLoading();
					setTimeout(() => {
						uni.navigateBack()
					}, 1000);
				})
			},
			async uploads() {
				const that = this
				let arr = []
				console.log(that.ImgBox)
				for (let k in that.ImgBox) {
					const v = that.ImgBox[k]
					let a = await that.up_img(v)
					let b = JSON.parse(a)
					arr.push(b.data[0]) 
				}
				console.log('img:', arr)
				// this.grade_list[s].imgs.push(arr)
				// this.grade_list[s].imgs=arr[]
				return arr;
			},
			up_img(url) {
				console.log(url);
				// this.$api.http.post('index/upload/img',url).then(res=>{
				// 	console.log(res);
				// })
				return new Promise((resolve, reject) => {
					uni.uploadFile({
						url: Api_url +'index/upload/img', //仅为示例，非真实的接口地址
						filePath: url,
						name: 'img',
						header: {
							token: uni.getStorageSync("token")
						},
						formData: {
							'user': 'test'
						},
						success: (res) => {
							resolve(res.data); 
						}
					});
				})
			},
			ChooseImage(index) {
				const that = this
				uni.chooseImage({
					count: 9, //默认9
					sizeType: ['original', 'compressed'], //可以指定是原图还是压缩图，默认二者都有
					sourceType: ['album'], //从相册选择
					success: (res) => {
						const num = that.ImgBox.length
						if (num != 0) {
							console.log('add', res.tempFilePaths)
							//that.ImgBox[index].push(res.tempFilePaths)
							// that.ImgBox[index].splice(num - 1, 0, res.tempFilePaths)
							that.ImgBox.push(res.tempFilePaths[0])
						} else {
							console.log('create', res.tempFilePaths)
							// that.$set(that.ImgBox,index,res.tempFilePaths)
							that.ImgBox.push(res.tempFilePaths[0])
						}
						console.log(that.ImgBox)
					}
				});
			},
			ViewImage(e, index) {
				const that = this
				let box = that.ImgBox[index]
				console.log(box, index)
				uni.previewImage({
					urls: box,
					current: e.currentTarget.dataset.url
				});
			},
			DelImg(e, index) {
				const that = this
				console.log('del:', e.currentTarget.dataset.index, index)
				uni.showModal({
					title: '删除',
					content: '确定要删除吗？',
					cancelText: '否',
					confirmText: '是',
					success: res => {
						if (res.confirm) {
							that.ImgBox[index].splice(e.currentTarget.dataset.index, 1)
						}
					}
				})
			},
			get_rate(e) {
				console.log(e)
				this.form.rate = e.value
				this.order[e.index]['rate'] = e.value
			},
		}
	}
</script>

<style lang="less">
	.grade {
		font-size: 14px;

		.product {
			padding: 10px;
			display: flex;

			.pic {
				padding-right: 20px;

				img {
					width: 90px;
					height: 90px;
				}
			}

			.title {
				font-size: 16px;
				width: 80%;
				display: flex;
				flex-direction: column;
				justify-content: space-between;

				.tit_01 {
					overflow: hidden;
					line-height: 20px;
					height: 60px;
				}

				.tit_02 {
					display: flex;
					justify-content: space-between;
				}
			}
		}

		.BH10 {
			height: 10px;
			background-color: #F4F4F6;
		}

		.pingj {
			padding: 15px 10px;

			.pj_c {
				border: 1px solid #EFEFEF;
				padding: 5px;
				margin-bottom: 18px;
				height: 100px;
			}

			.tu {
				img {
					width: 50px;
					height: 50px;
					margin-right: 15px;
				}
			}
		}

		.star {
			padding: 15px 10px;

			.star_01 {
				display: flex;
				line-height: 25px;
				padding-bottom: 5px;
			}
		}

		.btn {
			margin: 25px 10px 0;
			background-color: #57406E;
			border-radius: 20px;
			text-align: center;
			color: #fff;
			line-height: 40px;
		}
	}
</style>
