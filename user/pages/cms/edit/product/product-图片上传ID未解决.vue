<template>
	<view class="product"> 
		<choose :count="count" :imgList="goods.banner_imgs" @changes="fileChange"></choose>
		<compress ref="compress" :maxwh="maxwh" :quality="quality"> </compress> 
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
							<radio :id="item.value" style="transform:scale(0.7)"
							 :value="item.value+''" :checked="item.checked"></radio>
							<label class="label-2-text" :for="item.value">
								<text>{{item.name}}</text>
							</label>
						</label>
					</radio-group>  
				</view>
			</view>
			<view class="biao_02">
				<view class="biao_02_l"> 商品分类：</view>
				<view class="biao_02_r" @click="show_cate_list=true">
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
		</view>
		<view class="biao">
			<view class="guige" v-for="(item,index) of pro_num" :key="index">
				<view class="biao_01">
					<view class="biao_01_l">市场价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="goods.market_price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">普通用户价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="goods.price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">VIP价：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="goods.vip_price" />
					</view>
				</view>
				<view class="biao_01">
					<view class="biao_01_l">分销提成：</view>
					<view class="biao_01_r">
						<input class="uni-input" v-model="goods.fx_tc" />
					</view>
				</view>
				<!-- <view class="stop" @click="close(index)"><img src="../../../imgs/stop.png" /></view> -->
			</view>
			<!-- <view class="jgkc" @click="tjge()">
				<uni-icon type="plus" size="18" color="red"></uni-icon><span></span>添加商品规格
			</view> -->
		</view>
		<view class="biao">
			<view class="biao_04">
				<view class="biao_04_l">
					<view class="biao_04_l_1"> 总库存：</view>
					<view class="biao_04_l_i">
						<input class="uni-input" v-model="goods.stock" />
					</view>
					<view class="biao_01_m"></view>
				</view>
				<view class="biao_04_l" v-if="goods.leixin==0">
					<view class="biao_04_l_1"> 运费：</view>
					<view class="biao_04_l_i">
						<input class="uni-input" v-model="goods.shipping_money" />
					</view>
				</view>
			</view>

			<view class="biao_05">
				商品详情：
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
		
		<uni-popup :show="show_cate_list" type="top" mode="fixed" 
		@hidePopup="show_cate_list=false">
			<view class="uni-list leixin">
				<radio-group @change="cate_change">
					<label class="uni-list-cell uni-list-cell-pd" v-for="(item, index) in category_list" :key="index">
						<view class="xuan">
							<radio :value="index+''" />
							{{item.category_name}}  
						</view>
		
					</label>
				</radio-group>
			</view>
		</uni-popup>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	import uniPopup from "@/components/uni/uni-popup/uni-popup.vue"
	import choose from "@/components/img/choose.vue"
	import compress from "@/components/img/compress.vue"
	export default {
		data() {
			return {
				show_cate_list:false,
				category_name:'',
				category_list:[],
				id: 0,
				isYasuo: true,
				count: 4,
				maxwh: 210,
				quality: 1,
				imgList: [],
				is_edit: false,
				goods: {
					banner_imgs:[],
					goods_name: '',
					category_id: '',
					description: '',
					price: '',
					vip_price: '',
					market_price: '',
					leixin:'1',	//类型0快递 1核销
					fx_tc:'',	//分销提成
					sales: '',
					stock: '',
					content: ''
				},
				pro_num: [1],
				guige: false,
				radioItems: [{
						name: '快递邮寄',
						value: '0'
					},
					{
						name: '到店核销',
						value: '1',
						checked: 'true'
					}
				]
			};
		},
		components: {
			uniIcon,
			choose,
			compress,
			uniPopup
		},
		watch: {
			'goods.price'(news,old){
				if(news*1>this.goods.market_price*0.9){
					this.$api.msg('用户价至少优惠10%')
					this.goods.price=this.goods.market_price*0.9
				}
			},
			'goods.vip_price'(news,old){
				console.log(news)
				if(news*1>this.goods.price*1){
					this.$api.msg('VIP价需小于用户价')
					this.goods.vip_price=this.goods.price*1-1
				}
			},
			'goods.fx_tc'(news,old){
				if(news*1>this.goods.vip_price*1){
					this.$api.msg('提成不能大于VIP价')
					this.goods.fx_tc=this.goods.vip_price*1
				}
			}
		},
		onLoad(options) {
			this._request() 
			this.id = options.id
			
		},
		methods: {
			//获取分类
			_request(id) {
				this.$api.http.get('category/1').then(res => {
					this.category_list = res
					if (this.id > 0) {
						this.get_pro(this.id) 
					}
				}) 
			}, 
			//获取修改商品的数据
			get_pro(id) {
				let cate=this.category_list
				this.is_edit=true
				const that=this
				this.$api.http.get('tgpro/' + id).then(res => {
					this.goods = res 
					for (let k in cate) { 
						if(cate[k]['category_id']==res.category_id){
							that.category_name=cate[k].category_name
						}
					}
				})								
			}, 
			//大图
			fileChange(e) { 
				this.goods.banner_imgs = e; 
			},
			sub(){
				const that=this 
				this.$refs.compress.yasuoImg(this.goods.banner_imgs).then(e => {
					let imgs = e.map((value, index) => {
						return value.tempFilePath
					})
					that.goods.banner_imgs=imgs
					that.$api.http.post('shop/add_product',that.goods).then(res=>{
						
					})
				})  			
			}, 
			sub_edit(){
				const that=this 
				that.goods.goods_id=this.id
				that.$api.http.post('shop/edit_product',that.goods).then(res=>{ 
					that.$api.msg('操作成功')
					setTimeout(()=>{
						uni.redirectTo({
							url:'/pages/edit/pro_manage/pro_manage'
						})
					},1000);
					
				})
				return;
				this.$refs.compress.yasuoImg(this.goods.banner_imgs).then(e => {
					let imgs = e.map((value, index) => {
						return value.tempFilePath
					})
					that.goods.banner_imgs=imgs
					that.goods.goods_id=this.id
					that.$api.http.post('shop/edit_product',that.goods).then(res=>{
						
					})
				})  	
			},
			leixin_chage(e){
				this.goods.leixin=e.detail.value
			},
			//规格出现
			tjge() {
				this.pro_num.push('1');
			},
			//规格消失
			close(index) {
				this.pro_num.splice(index, 1)
			},
			compressImg(e) {
				console.log(e)
			},
			changeIndicatorDots(e) {
				this.isYasuo = !this.isYasuo
			},
			//选择分类
			cate_change(event) {
				const index = event.detail.value 
				this.category_name = this.category_list[index].category_name
				this.goods.category_id = this.category_list[index].category_id
				this.show_cate_list = false				
			},
			previewImage() { //预览图片
				uni.previewImage({
					urls: this.imgList
				});
			}, 
		}
	}
</script>

<style lang="less">
	.input-view {
		font-size: 28upx;
	}
	.leixin{
			height:300px;
			width:50vw;
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
			padding: 10px;
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
