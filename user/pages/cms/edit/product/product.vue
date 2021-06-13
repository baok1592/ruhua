<template>
	<view class="product">
		<view class="pic">
			商品幻灯图(可拖动)
			<view>
				<robby-image-upload :value="pics" :server-url="serverUrl" :form-data="formData" :server-url-delete-image="DelServelUrl"
				 @delete="delImage" @add="onImg" @move="onMove" :limit="3"></robby-image-upload>
			</view>
		</view>
		<view class="top">
		</view>
		<view class="biao">
			<view class="biao_01">
				<view class="biao_01_l"> 商品名称：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="goods.goods_name" />
				</view>
			</view>
			<view class="biao_02">
				<view class="biao_01_1"> 商品类型：</view>
				<view class="biao_01_2">
					<radio-group class="uni-list" @change="leixin_chage">
						<label class="uni-list-cell uni-list-cell-pd" v-for="(item,index) in radioItems" :key="index"> 
							<radio :id="item.value" style="transform:scale(0.7)" :value="item.value"
							 :checked="item.value == goods.style"></radio>
							<label class="label-2-text" :for="item.value">
								<text>{{item.name}}</text>
							</label>
						</label>
					</radio-group>
				</view>
			</view>
			<view class="biao_02" v-if="mail == 0">
				<view class="biao_02_l"> 配送方式： </view>
				<view class="biao_02_r" @click="show_mail_list=true">
					<block v-for="(item,index) of peisong" :key="index">
						{{peisong[index]}}
					</block>
					<uni-icon type="arrowdown" size="20" color="#A7A7A7"></uni-icon>
				</view>
			</view>
			<view class="biao_02" @click="is_set_category">
				<view class="biao_02_l"> 商品分类：</view>
				<view class="biao_02_r" >
					{{category_name}}
					<uni-icon type="arrowdown" size="20" color="#A7A7A7"></uni-icon>
				</view>
			</view> 
			<view class="biao_01">
				<view class="biao_01_l">商品促销语：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="goods.description" />
				</view>
			</view>
			<view class="biao_02" style="border: none;">
				<view class="biao_01_1"> 是否允许分销：</view>
				<view class="biao_01_2">
					<radio-group class="uni-list" @change="fx_chage">
						<label class="uni-list-cell uni-list-cell-pd" v-for="(item,index) in fxItems" :key="index"> 
							<radio :id="item.value" style="transform:scale(0.7)" :value="item.value"
							 :checked="item.value == goods.fx_status"></radio>
							<label class="label-2-text" :for="item.value">
								<text>{{item.name}}</text>
							</label>
						</label>
					</radio-group>
				</view>
			</view>
			
			
			<view class="biao_01" v-if="goods.fx_status>0">
				<view class="biao_01_l">分销佣金：</view>
				<view class="biao_01_r">
					<input class="uni-input" v-model="goods.fx_tc" />
				</view>
			</view>
		</view>
		<view class="biao" v-if="is_sku">
			<view class="guige">
				<view class="biao_01">
					<view class="biao_01_l">原价：</view>
					<view class="biao_01_r">
						<input class="uni-input" type="digit" v-model="goods.market_price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">普通用户价：</view>
					<view class="biao_01_r">
						<input class="uni-input" type="digit" v-model="goods.price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">VIP价：</view>
					<view class="biao_01_r">
						<input class="uni-input" type="digit" v-model="goods.vip_price" />
					</view>
				</view>
			</view>
		</view>
		<view class="biao">
			<view class="guige" v-for="(item,index) of pro_num" :key="index">
				<view class="biao_01">
					<view class="biao_01_l">规格名称：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="sku[index].name" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">市场价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="sku[index].market_price" @input="get_market_price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">普通用户价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="sku[index].price" @input="get_price($event,index)" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">VIP价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="sku[index].vip_price" @input="get_vip_price($event,index)" />
					</view>
				</view>
				<view class="biao_01"  v-if="goods.fx_status>0">
					<view class="biao_01_l">分销提成：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="sku[index].fx_tc" @input="get_fx($event,index)" />
					</view>
				</view>
				<view class="stop" @click="close(index)"><img src="@/imgs/stop.png" /></view>
			</view>
			<view class="jgkc" @click="tjge()">
				<uni-icon type="plus" size="18" color="red"></uni-icon><span></span>添加商品规格
			</view>
		</view>
		<view class="biao">
			<view class="biao_04">
				<view class="biao_04_l">
					<view class="biao_04_l_1"> 总库存：</view>
					<view class="biao_04_l_i">
						<input class="uni-input" type="number" v-model="goods.stock" />
					</view>
					<view class="biao_01_m"></view>
				</view> 
				<view class="biao_04_l" v-if="goods.leixin==0">
					<view class="biao_04_l_1"> 运费：</view>
					<view class="biao_04_l_i"  >
						<input type="digit" class="uni-input" v-model="goods.shipping_money" /> 
					</view>
				</view>
			</view>

			<view class="biao_05">
				商品详情介绍：
			</view>
			<textarea v-model="goods.content"></textarea>
		</view>
 

		<view class="H50"></view>
		<view class="p_btn">
			<!-- <cover-view class="btn-save">    -->
			<button class="pro_btn" @click="sub()" v-if="!is_edit">提交新增</button>
			<button class="pro_btn" @click="sub_edit()" v-else>提交修改</button>
			<!-- </cover-view> -->
		</view>
		<!-- 商品类型弹窗 -->
		<uni-popup :show="show_cate_list" type="top" mode="fixed" @hidePopup="show_cate_list=false">
			<view class="uni-list leixin">
				<radio-group @change="cate_change">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in category_list" :key="index">
						<view class="xuan">
							<radio :value="item.category_id" :checked="item.category_id == goods.category_id"/>
							{{item.category_name}}
						</view>
					</label>
				</radio-group>
			</view>
		</uni-popup>


		<!-- 配送方式弹窗 -->
		<uni-popup :show="show_mail_list" type="top" mode="fixed" @hidePopup="show_mail_list=false" v-model="goods.peisong">
			<view class="uni-list leixin">
				<checkbox-group @change="mail_change">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in mail_list" :key="index">
						<view class="xuan">
							<checkbox :value="index+''" />
							{{item.name}}
						</view>
					</label>
				</checkbox-group>
			</view>
		</uni-popup>
		<view style="height:100upx"></view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	import robbyImageUpload from '@/components/plan-image-upload/up_img'
	import {
		Api_url
	} from '@/common/config'
	export default {
		data() {
			return {
				groupList: '',
				group_id: '',

				pics: [],
				photos: [],
				c_pics: [],
				c_photos: [],
				serverUrl: Api_url + 'up_img',
				DelServelUrl: Api_url + 'del_img',
				formData: {
					use: 'pro_banner',
					back: 'idurl'
				},
				c_formData: {
					use: 'pro_img',
					back: 'idurl'
				},
				sku: [],
				show_cate_list: false,
				show_mail_list: false,
				category_name: '',
				category_list: [],
				id: 0,
				is_edit: false,
				goods: {
					banner_imgs: [],
					goods_name: '',
					category_id: '',
					description: '',
					price: '',
					vip_price: '',
					market_price: '',
					shipping_money:'',
					leixin: -1, //类型0快递 1核销
					fx_status:0, //是否允许分销
					fx_tc: '', //分销提成
					sales: '',
					stock: '',
					content: '',
					goods_name: '',
					shop_category_id: [],
					courier_type:[],
					c_imgs:[]
				},
				pro_num: [],
				guige: false,
				radioItems: [{
						name: '快递邮寄',
						value: '0'
					},
					{
						name: '到店核销',
						value: '1'
					}
				],
				fxItems: [{
						name: '否',
						value: '0'
					},
					{
						name: '是',
						value: '1'
					}
				],
				market_price: '',
				price: '',
				vip_price: '',
				edit_group_id: '',
				fx_tc: '',
				mail: 1,
				mail_list: [{
						id: '1',
						name: '快递发货'
					},
					{
						id: '2',
						name: '同城配送'
					},
					{
						id: '3',
						name: '到店自提'
					}
				],
				peisong: '',
				pro_group_name:[],
				group_list: [{
						id: '1',
						name: '未分组'
					},
					{
						id: '2',
						name: 'A组'
					},
					{
						id: '3',
						name: 'B组'
					}
				],
				group: '未分组',


			};
		},
		components: {
			uniIcon,
			uniPopup,
			robbyImageUpload
		},
		computed: {
			is_sku() {
				if (this.pro_num.length > 0) {
					return false
				} else {
					return true
				}
			}
		},
		watch: {
			'goods.price'(news, old) {
				if (news * 1 >= this.goods.market_price * 1) {
					this.$api.msg('用户价需小于原价')
					this.goods.price = this.goods.market_price * 1 - 1
				}
			},
			'goods.vip_price'(news, old) {
				console.log(news)
				console.log(old)

				if (news * 1 > this.goods.price * 0.95) {
					this.$api.msg('易卡价至少优惠5%')
					let vip_price = this.goods.price * 950/1000
					this.goods.vip_price = vip_price.toFixed(2)
				} 
			},
			'goods.fx_tc'(news, old) {
				if (news * 1 > this.goods.vip_price * 0.99) {
					this.$api.msg('提成不能大于VIP价')
					this.goods.fx_tc = this.goods.vip_price * 1 - 1
				}
			},

		},
		onLoad(options) {
			this._request()
			this.id = options.id
			this.get_groupList()


		},
		onShow() {
			const group_data = uni.getStorageSync('groupList')
			this.groupList = group_data.groupList
			this.group_id = group_data.group_id
			if(group_data){
				this.goods.shop_category_id = group_data.group_id
			}
			if (!group_data.group_name) {
				this.group = '未分组'
			} else {
				this.group = group_data.group_name
			}
			uni.removeStorageSync('groupList');

		},
		methods: {
			is_set_category(){ 
				this.show_cate_list=true
			},
			get_market_price(event) {
				this.market_price = event.target.value
				console.log(this.market_price)
			},
			get_price(event, index) {
				const that = this
				this.price = event.target.value
				if (this.price * 1 > this.market_price * 1) {
					this.$api.msg('用户价需小于原价')
					that.sku[index].price = this.market_price * 1 - 1
					that.price = this.market_price * 1 - 1
				}
			},
			get_vip_price(event, index) {
				const that = this
				this.vip_price = event.target.value
				console.log(this.price)
				if (this.vip_price * 1 > this.price * 0.95) {
					this.$api.msg('易卡价至少优惠5%')
					that.sku[index].vip_price = this.price * 950/1000
					that.vip_price = that.sku[index].vip_price.toFixed(2) 
				}

			},
			get_fx(event, index) {
				const that = this
				this.fx_tc = event.target.value
				console.log(this.vip_price)
				if (this.fx_tc * 1 > this.vip_price * 0.99) {
					this.$api.msg('提成不能大于VIP价')
					that.sku[index].fx_tc = this.vip_price * 1 - 1
					that.fx_tc = that.vip_price * 1 - 1
				}
			}, 
			//----------------------------------------------------------------------------------获取分类
			_request() {
				this.$api.http.get('category/get_category',{id:1}).then(res => {
					this.category_list = res.data
					if (this.id > 0) {
						this.get_pro(this.id)
					}
				})
			},
			//---------------------------------------------------------------------------获取修改商品的数据
			get_pro(id) {
				let cate = this.category_list
				this.is_edit = true
				const that = this
				this.$api.http.get('/product/get_product',{id}).then(res2 => {
					const res=res2.data
					console.log(res)
					this.goods = res
					this.pics = res.banner_imgs_list
					this.photos = res.banner_imgs
					this.c_pics = res.banner_imgs_list
					this.c_photos = res.banner_imgs
					console.log(this.c_photos)
					if(res.shop_category_id && res.shop_category_id[0]){
						this.edit_group_id = res.shop_category_id
					}else{
						this.edit_group_id = ''
					}
					console.log('aa:',this.edit_group_id)
					if (res.sku.json) {
						this.sku = res.sku.json
					}
					if(res.style==0){
						this.mail=0
					}
					for (let k in res.sku.json) {
						that.pro_num.push(1)
					}
					for (let k in cate) {
						if (cate[k]['category_id'] == res.category_id) {
							that.category_name = cate[k].category_name
						}
					}
					let group_name = []
					for (let k in that.groupList) {
						let v = that.groupList[k].id
						group_name[v] = that.groupList[k].name
					}
					
					for (var i = 0; i < that.edit_group_id.length; i++) {
						let num = that.edit_group_id[i]
						this.pro_group_name[i] = group_name[num]
					} 				
				})
			}, 
			
			onImg(e) {
				this.pics = e.allImages
				const obj = e.allImages
				for (let index in obj) {
					let id = obj[index].id
					this.photos[index] = id
				}
			},
			onMove(e) {
				this.pics = e
				const obj = e
				for (let index in obj) {
					let id = obj[index].id
					this.photos[index] = id
				}
				console.log('move:', this.photos)
			},
			onMove_c(e) {
				this.c_pics = e
				const obj = e
				for (let index in obj) {
					let id = obj[index].id
					this.c_photos[index] = id
				}
				console.log('c_move:', this.c_photos)
			},
			delImage(e) {
				let arr = []
				for (let k in e.allImages) {
					arr[k] = e.allImages[k].id
				}
				this.photos = arr
			},
			c_onImg(e) {
				const that = this
				let arr = []
				this.c_pics = e.allImages
				const obj = e.allImages
				for (let index in obj) {
					let id = obj[index].id
					arr.push(id)
					// that.c_photos[index] = id
				}
				this.c_photos = arr
				console.log(arr)
			},
			c_delImage(e) {
				let arr = []
				console.log(e.allImages)
				for (let k in e.allImages) {
					arr[k] = e.allImages[k].id
				}
				this.c_photos = arr
			},
			sub() {				
				const that = this
				that.$api.msg('演示版-无权限')
				return; 
				if (this.sku.length != 0) {
					that.goods.sku = that.sku
				} else {
					that.goods.sku = []
				}
				if (this.goods.leixin<0) {
					that.$api.msg('未选择物流类型')
					return;
				}
				if(this.goods.leixin==0 && this.goods.shipping_money==''){
					that.$api.msg('未填写运费')
					return;
				}
				
				if (this.mail == 0 && !this.peisong) {
						that.$api.msg('未选择配送方式')
						return; 
				}
				if (!this.goods.category_id) {
					that.$api.msg('未选择分类')
					return;
				}
				if (this.goods.goods_name=='' || this.goods.stock=='') {
						that.$api.msg('未填写商品名称或库存')
						return; 
				}
				if (this.goods.market_price=='') {
						that.$api.msg('未填写商品原价')
						return; 
				}
				if (this.goods.price=='') {
						that.$api.msg('未填写普通用户价')
						return; 
				}
				if (this.goods.vip_price=='') {
						that.$api.msg('未填写VIP价')
						return; 
				}
				if(this.goods.fx_status == "1"){
					if(this.goods.fx_tc == ''){
						that.$api.msg('未填写分销提成')
						return;
					}
				}
				if(this.photos.length<1){
					that.$api.msg('未上传商品幻灯图')
					return;
				}
				
				this.goods.banner_imgs = this.photos
				this.goods.c_imgs = this.c_photos
				that.$api.http.post('shop/add_product', that.goods).then(res => {
					that.$api.msg('操作成功')
					setTimeout(() => {
						uni.redirectTo({
							url: '/pages/edit/pro_manage/pro_manage'
						})
						uni.setStorageSync('goods',that.goods)
					}, 1000);
				})

			},
			sub_edit() {
				console.log('点击了修改')
				const that = this
				console.log('sku:', this.sku.length)
				if (that.sku.length > 0 || that.sku.length < 0) {
					that.goods.sku = that.sku
				} else {
					that.goods.sku = []
				}
				that.goods.goods_id = this.id
				this.goods.banner_imgs = this.photos
				console.log(this.c_photos)
				// -----------------------------------------------------------------修改商品报错点
				if (that.c_photos.length > 0) {
					that.goods.banner_imgs = that.c_photos
				} else {
					that.goods.banner_imgs = [] //避免空让c_imgs由[]变成""
				}
				// -------------------------------------------------------------------修改商品报错点结束
				that.$api.http.post('shop/edit_product', that.goods).then(res => {
					that.$api.msg('操作成功')
					setTimeout(() => {
						uni.redirectTo({
							url: '/pages/edit/pro_manage/pro_manage'
						})
					}, 1000);
				})
			},
			leixin_chage(e) {
				this.goods.leixin = e.detail.value
				console.log(e)
				this.mail = e.detail.value
				if (e.detail.value == 1) {
					this.peisong = ''
				}
			},
			fx_chage(e) {
				console.log('xx:',e.detail.value)
				this.goods.fx_status = e.detail.value  
			},
			//规格出现
			tjge() {
				this.pro_num.push('1');
				console.log('pri_num:', this.pro_num)
				this.sku.push({
					name: '',
					price: '',
					vip_price: '',
					fx_tc: '',
					market_price: ''
				})
				console.log('sku:', this.sku)
			},
			//规格消失
			close(index) {
				this.pro_num.splice(index, 1)
				this.sku.splice(index, 1)
				if (this.sku.length == 0 || this.pro_num.length == 0) {
					this.sku = []
					this.pro_num = []
				}
			},
			compressImg(e) {
				console.log(e)
			},
			changeIndicatorDots(e) {
				this.isYasuo = !this.isYasuo
			},
			//选择分类
			cate_change(event) {
				console.log(event)
				const value = event.detail.value
				for (let k in this.category_list) {
					if(value == this.category_list[k].category_id){
						this.category_name = this.category_list[k].category_name
						this.goods.category_id = this.category_list[k].category_id
					}
				}
				// const index = event.detail.value
				// this.category_name = this.category_list[index].category_name
				// this.goods.category_id = this.category_list[index].category_id
				this.show_cate_list = false
			},
			//选择配送方式
			mail_change(event) {
				const that = this
				const li = event.target.value.map(String)
				console.log(li)
				let arr = []
				let arr2 = []
				for (let x in li) {
					let v = li[x]
					arr2[x] = that.mail_list[v].name + ''
				}
				this.peisong = arr2.join(" ")
				this.goods.courier_type = arr2
				console.log(this.peisong)
			},


			previewImage() { //预览图片
				uni.previewImage({
					urls: this.imgList
				});
			},
			buy(type) {
				this.$api.http.post('user/pay_vip', {
					type
				}).then(res => {
					this.$api.msg('购买成功')
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/user/user'
						})
					}, 1500)
				})
			}
		},
		onPullDownRefresh() {
			this._request()
			this.get_groupList()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
	.pic {
		padding: 20upx 10upx;
	}

	.input-view {
		font-size: 28upx;
	}

	.leixin {
		height: 300px;
		width: 50vw;
		overflow: hidden;
		overflow-x: hidden;
		overflow-y: scroll;
	}

	.xuan {
		padding-bottom: 10px;
	}

	.close-view {
		text-align: center;
		line-height: 14px;
		height: 16px;
		width: 16px;
		border-radius: 50%;
		background: #FF5053;
		color: #FFFFFF;
		position: absolute;
		top: -6px;
		right: -4px;
		font-size: 12px;
	}

	.product {
		background-color: #F7F6FB;
		height: 100%;

		.head {
			position: relative;
			padding: 0 5px;
		}

		.head img {
			width: 100%;
			height: 150px;
		}

		.btn {
			position: absolute;
			top: 10px;
			right: 5px;
		}

		.btn button {
			font-size: 10px;
			color: red;
			border: none;
			border-radius: 20px;
			background-color: #CBCBCB;
			padding: 5px;
		}

		.top {
			box-shadow: 0 0 8px 5px #EDEDED;
			height: 1px;
			background-color: #EDEDED;
		}

		.pro_tit {
			padding: 15px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.BH {
			height: 5px;
			background-color: #F2F2F2;
		}

		.biao {
			background-color: #fff;
			margin: 10px;
			border-radius: 5px;
			border: 1px solid #EAEAEA;
		}

		.biao span {
			color: red;
			padding-right: 5px;
		}

		.biao textarea {
			width: 100%;
			border-bottom: 1px solid #EDEDED;
			padding: 10px;
			height: 100px;
		}

		.biao_01 {
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_01_l {
			padding-top: 7px;
			flex-shrink: 0;
		}

		.biao_01_1 {
			padding-top: 5px;
		}

		.biao_01_r {
			flex-grow: 1;
		}

		.biao_01_r text {
			padding-right: 10px;
		}

		.biao_02_r select {
			padding: 0 10px;
			line-height: 25px;
			min-width: 80px;
			text-align: center;
		}
		.fxsm{padding: 0 10px 10px;font-size: 13px;color: #909090;}
		.biao_01_m {
			border-right: 1px solid #EDEDED;
		}

		.biao_02 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
			justify-content: space-between;
		}

		.biao_03 {
			padding: 13px 10px 10px;
			border-bottom: 1px solid #EDEDED;
			display: flex;
		}

		.biao_04 {
			display: flex;
			padding: 10px 10px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.biao_04_l {
			display: flex;
			width: 50%;
			line-height: 30px;
			padding-right: 10px;
		}

		.biao_04_l_i {
			flex-shrink: 1;
		}

		.biao_04_l_1 {
			flex-shrink: 0;
		}

		.biao_05 {
			padding: 10px 10px 0;
		}

		.jgkc {
			padding: 15px 10px;
			border-bottom: 1px solid #EDEDED;
		}

		.p_btn {
			background: #F7F6FB;
			padding: 0 10px 10px;
			position: fixed;
			bottom: 0;
			width: 100%;
			z-index: 9999;
		}

		.pro_btn {
			background-color: #E5E5E5;
			text-align: center;
			border-radius: 20px;
			background-color: #DF421D;
			color: #fff;
			width: 94%;
		}

		.guige {
			border: 1px solid #EDEDED;
			margin: 10px 20px 5px 10px;
			position: relative;
		}

		.stop {
			position: absolute;
			right: -15px;
			top: 60px;
		}

		.stop img {
			width: 30px;
			height: 30px;
		}

		.uni-input-input,
		.uni-input {
			height: 30px;
			line-height: 30px;
		}
	}
</style>
