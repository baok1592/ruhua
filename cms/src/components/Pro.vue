<template>
	<div id="release">
		<transition appear appear-active-class="animated fadeInLeft">
			<div class="release">
				<div class="order-back" style="margin-bottom: 20px;">
					<el-button type="primary" size="small" @click="back">返回</el-button>
					<el-button type="primary" size="small" @click="caiji">采集商品</el-button>
				</div>

				<!-- <el-button type="primary" size="small" @click="caiji">采集商品</el-button> -->
				<el-form ref="forms" :model="forms" label-width="120px">
					<template v-if="show_input">
						<el-form-item label="商品名称">
							<el-input v-model="forms.goods_name" style="width:60%"></el-input>
						</el-form-item>
						<el-form-item label="商品类型">
							<el-radio v-model="forms.style" :label="0" border size="medium">实物商品(物流)</el-radio>
							<el-radio v-model="forms.style" :label="1" border size="medium">虚拟商品(验证码)</el-radio>
						</el-form-item>
						<el-form-item label="商品分类">
							<el-select v-model="forms.category_id" placeholder="请选择商品分类">
								<template v-for="item in category">
									<el-option v-if="item.level == 1" :value="item.category_id" :label="item.category_name" :key="item.category_id"></el-option>
									<el-option v-if="item.level == 2" :value="item.category_id" :label="'--' + item.category_name" :key="item.category_id"></el-option>
								</template>
							</el-select>
						</el-form-item>
						<el-form-item label="商品促销语">
							<el-input v-model="forms.description" style="width:60%"></el-input>
						</el-form-item>

						<el-row>
							<el-col :span="4">
								<el-form-item label="销售价格">
									<el-input v-model="forms.price" style="width: 100px"></el-input>
									<span>元</span>
								</el-form-item>
								
							</el-col>
							<el-col :span="4">
								<el-form-item label="市场价格">
									<el-input v-model="forms.market_price" style="width: 100px"></el-input>
									<span>元</span>
								</el-form-item>
							</el-col>
							<el-col :span="4">
								<el-form-item label="VIP优惠金额">
									<el-input v-model="forms.vip_price" style="width: 100px" @change="com_price"></el-input>
									<span>元</span>
								</el-form-item>
							</el-col>

							<el-col :span="4">
								<el-form-item label="运费">
									<el-select v-model="forms.delivery_id" placeholder="请选择运费模板">
										<el-option v-for="item in delivery" :value="item.id" :label="item.name" :key="item.id"></el-option>
									</el-select>
								</el-form-item>
							</el-col>
						</el-row>
						<el-row v-if="show_sku==0">
							<el-col :span="4">
								<el-form-item label="总库存">
									<el-input v-model="forms.stock" style="width: 100px"></el-input>
									<span>件</span>
								</el-form-item>
						
							</el-col>
							<el-col :span="4">
								<el-form-item label="基础销量">
									<el-input v-model="forms.sales" style="width: 100px"></el-input>
									<span>件</span>
								</el-form-item>
							</el-col>
						</el-row>
						<el-row>
							<el-col :span="4">
								<el-form-item label="商品大图" style="width:100%">
									<el-button size="small" style="margin-bottom: 20px;" type="primary" @click="choose_pic" v-if="img_list.length < 6">选择图片</el-button>
									<template>
										<template v-if="img_list.length > 0">
											<div style="display: flex; width:700px ; flex-wrap: wrap;">
												<template v-for="(item,index) of img_list">

													<div class="picA" v-if="img_list.length > 0">
														<template v-if="is_caiji == 1">
															<img class="img" :src="item">
														</template>
														<template v-else>
															<img class="img" :src="getimg + item.url">
														</template>
														<i class="el-icon-circle-close" @click="del_img(index)"></i>
													</div>
												</template>
											</div>
										</template>

										<!-- <div class="picA" style="margin-left: 19px;" @click="choose_pic" v-if="img_list.length < 6">
									<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
								</div> -->
									</template>

								</el-form-item>
							</el-col>

							<el-col :span="12">
								<el-col :span="7">
									<el-form-item label="商品视频">
										<el-button size="small" style="margin-bottom: 20px;" type="primary" @click="choose_video">选择视频</el-button>
									</el-form-item>
								</el-col>
								<el-col :span="4">
									<template v-if="video_data.description">{{video_data.description}}</template>
									<template v-else>{{video_data.url}}</template>
								</el-col>

							</el-col>

						</el-row>
						<el-form-item label="规格类型">
							<el-radio v-model="show_sku" :label="0" border size="medium" :checked="show_sku?'':'checked'">无</el-radio>
							<el-radio v-model="show_sku" :label="1" border size="medium" :checked="show_sku?'checked':''">有</el-radio>
						</el-form-item>
						

						<el-form-item>
							<skuv v-show="show_sku" :sub="sub" :del="sku_comfirm" :rdata="rdata" @pro_sku="pro_sku"></skuv>

						</el-form-item>
						<el-form-item label="商品详情" prop="desc">
							<vue-ueditor-wrap v-model="forms.content" :config="myConfig"></vue-ueditor-wrap>
						</el-form-item>
						<el-form-item>
						
							<el-button type="primary" v-if="sub_btn" @click="on_add">立即创建</el-button>
						
						
							<el-button v-if="!sub_btn" type="success" @click="on_xg">提交修改</el-button>
							<el-button @click="rest">取消</el-button>
						</el-form-item>
					</template>

					
				</el-form>
			</div>
		</transition>

		<el-dialog v-loading="loading" title="采集商品" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<span>京东或淘宝</span>
			<el-input v-model="caiji_url" placeholder='请输入采集地址'></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisible = false">取 消</el-button>
				<el-button type="primary" @click="sub_caiji" v-loading.fullscreen.lock="fullscreenLoading">确 定</el-button>
			</span>
		</el-dialog>
		<Pic ref="child" :drawer="drawer" :father_fun="get_img" :length="length" :iscg="is_cg"></Pic>
		<Video ref="child_video" :drawer="drawer_video" :father_fun="get_video" :length="length_video" :iscg="is_cg"></Video>
	</div>
</template>

<script>
	import {
		Loading
	} from "element-ui";
	import VueUeditorWrap from "vue-ueditor-wrap";
	import Pic from '../views/PicList.vue'
	import Video from './video.vue'
	import skuv from './sku.vue'
	import {
		Api_url
	} from "@/common/config";
	export default {
		name: "Pro",
		props: ["eid"],
		data() {
			return {
				show_input: false,
				video_id: 0,
				video_data: '',
				fullscreenLoading: false,
				is_cg: 0,
				is_edit: false,
				sub: 0,
				loading: false,
				dialogVisible: false,
				caiji_url: '',
				length: 6,
				length_video: 1,
				getimg: this.$getimg,
				show_sku: 0,
				sku_sub: 0, //提交时获取规格参数
				sku_comfirm: 0,
				rdata: {},
				myConfig: {
					autoHeightEnabled: false,
					initialFrameHeight: 500,
					initialFrameWidth: "60%",
					serverUrl: '/index.php/index/admin/ue_uploads',
					UEDITOR_HOME_URL: this.$ue + '/static/UEditor/',
					toolbars: [
						[
							"justifyleft",
							"justifycenter",
							"justifyright",
							"justifyjustify",
							"bold",
							"forecolor",
							"fontsize",
							"source",
							"insertimage"
						]
					]
				},
				category: [],
				forms: {
					goods_name: "",
					category_id: "",
					people: "",
					shipping_fee: "",
					market_price: "",
					price: "",
					vip_price: 0,
					stock: "",
					max_buy: "",
					// people: "",
					img_id: "", //主图
					banner_imgs: [], //banner多图
					description: "",
					content: "",
					sales: "",
					style: 0,
					delivery_id: '',
					guige: 0,
					video_id: 0
				},
				upfile_url: Api_url + 'img_category/admin/upload/img',
				upfile_head: {
					token: localStorage.getItem("token")
				},
				upfile_list: [], //上传文件列表
				upfile_list_sku: [], //上传文件列表--规格
				upfile_banner_list: [], //上传幻灯片列表
				sku_table: false,
				sub_btn: true,
				cate_id: '0',
				delivery: '',
				drawer: false,
				drawer_video: false,
				img_list: [],
				is_caiji: 0
			};
		},
		components: {
			VueUeditorWrap,
			Pic,
			skuv,
			Video
		},
		mounted() {
			// this.get_all_img()
			this.getCategory(); //获取分类
			this.get_delivery()
			if (this.eid > 0) {
				//大于0为编辑模式
				this.editProduct(this.eid);
			}
			console.log(this.img_list)
		},
		methods: {
			//监听会员优惠金额----不能大于销售价格
			com_price(){
				if(this.forms.vip_price*1 >= this.forms.price*1){
					this.$message({
						message: '会员优惠金额不能大于商品销售价格',
						type: 'warning'
					}) 
					this.forms.vip_price = this.forms.price - 1
				}
			},
			back() {
				this.$emit('back')
			},
			caiji() {
				this.dialogVisible = true
			},
			//采集
			sub_caiji() {
				this.fullscreenLoading = true;
				this.is_caiji = 1
				//this.loading = true
				this.http.post('product/copy/get_info', {
					url: this.caiji_url
				}).then(res => {
					//this.loading = false
					this.$message({
						message: '采集成功',
						type: 'success'
					})
					this.fullscreenLoading = false;
					this.caiji_url = ''
					this.dialogVisible = false
					this.forms.goods_name = res.data.goods_name
					let arr = []
					for (let v in res.data.banner_imgs_url) {
						let k = res.data.banner_imgs_url[v]
						if (arr.length < 6) {
							arr.push(k)
						}
					}
					this.img_list = arr
				})
			},


			handleClose() {
				this.dialogVisible = false
			},

			get_img(e) {
				this.drawer = false
				for (let k in e) {
					const v = e[k]
					this.img_list.push(v)
					this.forms.banner_imgs.push(v.id + '')
				}
				this.length = 6 - this.img_list.length
				console.log('get_img_end:', e, this.img_list)
			},
			get_video(e) {
				this.drawer_video = false
				let video_data = {
					id: e[0].id,
					description: e[0].description,
					url: e[0].url
				}
				this.video_data = video_data
				this.forms.video_id = e[0].id
				this.video_id = e[0].id
				console.log('get_img_end:', e)
				console.log(this.video_id)
			},
			del_img(index) {
				this.img_list.splice(index, 1)
				this.forms.banner_imgs.splice(index, 1)
			},
			get_delivery() {
				this.http.get('delivery/admin/get_delivery').then(res => {
					this.delivery = res.data
				})
			},
			//打开图库
			choose_pic() {
				this.length = 6 - this.img_list.length
				this.drawer = true
			},
			//打开视频库
			choose_video() {
				this.length = 1 - this.img_list.length
				this.drawer_video = true
			},
			//新增商品
			on_add() {
				console.log('提交on_add')
				this.is_edit = false
				this.sub++
			},
			on_xg() {
				console.log('修改on_xg')
				this.is_edit = true
				this.sub++
			},
			pro_sku(e) {
				console.log('pro_sku:', e, this.show_sku)
				if (this.show_sku == 1) {
					console.log('ssku:', this.forms['sku'])
					this.forms['sku'] = e['list']
					this.forms['sku_img_ids'] = e['sku_img_id']
				} else {
					console.log('end--sku:', this.forms['sku'])
					this.forms['sku'] = []
					this.forms['sku_img_ids'] = []
				}
				if (this.is_edit) {
					this.onEdit()
				} else {
					this.onSubmit()
				}
			},
			upload_img(url) {
				return this.http.post_show('index/upload/down_img', {
					url: url
				}).then(res => {
					return res
				})
			},
			async get_banner_id() {
				console.log(this.img_list)
				const that = this
				let arr = []
				for (let k in this.img_list) {
					let v = that.img_list[k]
					let s = await that.upload_img(v)
					arr.push(s)
				}
				return arr;

			},
			//新增商品
			async onSubmit() {
				var that = this;
				if (that.is_caiji == 1) {
					that.forms.banner_imgs = await that.get_banner_id()
				}
				console.log('sku:', this.forms['sku'])
				if (this.forms['sku'][0]) {
					this.forms['price'] = this.forms['sku'][0]['price']
					this.forms['stock'] = this.forms['sku'][0]['stock_num']
				}
				this.forms.video_id = this.video_id
				this.http.post_show("product/admin/add_product", that.forms).then(res => {
					var res_code = res.status.toString(); //返回结果状态码转字符串取第一位数
					if (res_code.charAt(0) == 2) {
						this.$message({
							showClose: true,
							message: "新增成功",
							type: "success"
						});
						this._clsForm(); //清空form数据
						this.upfile_list = []; //清空上传列表
						this.upfile_list_sku = []; //清空上传列表
						this.upfile_banner_list = []; //清空上传列表
						this.$emit('back')
					} else {
						that.$message({
							showClose: true,
							message: res.data.msg,
							type: "error"
						});
					}
				});
			},
			//清空form表单数据
			_clsForm() {
				console.log('clear')
				var that = this;
				for (var x in that.forms) {
					that.forms[x] = "";
				}
				that.sku_comfirm++ //清除sku数据
				this.video_data = {
					id: '',
					description: '',
					url: '',

				}
			},

			//获取分类
			getCategory() {
				var that = this;
				// var arr = [];
				//获取所有分类
				this.http.get("category/admin/all_category").then(res => {
					that.category = res.data;
					if (this.category.length > 0) {
						this.show_input = true
					}else{
						this.$message({
							showClose: true,
							message: "请先创建商品分类",
							type: "warning"
						});
					}
				});
			},

			remove(file) {
				let list = this.forms.banner_imgs;
				let arr = list.split(","); //字符串转数组
				let id = String(file.id); //数字转字符串
				let index = arr.indexOf(id);
				if (index > -1) {
					arr.splice(index, 1); //删除指定下标的元素
				}
				let str = arr.join(","); //数组转字符串
				this.forms.banner_imgs = str;
			},

			//获取修改商品信息
			editProduct(id) {
				let loadingInstance = Loading.service({
					fullscreen: true
				});
				var that = this;

				this.http.get('product/get_product?id=' + id).then(res => {

					for (var k in res.data.banner_imgs_list) {
						const v = res.data.banner_imgs_list[k]
						that.img_list[k] = {}
						that.img_list[k]['id'] = v.id
						that.img_list[k]['url'] = v.url
					}
					console.log('edit_img_list', that.img_list)
					console.log('sku', res.data.sku)
					for (var x in that.forms) {
						that.forms[x] = res.data[x];
					}

					console.log('num:', res.data.sku_arr['tree'].length)
					if (res.data.sku_arr['tree'].length > 0) {
						console.log('有sku', res.data.sku_arr['tree'])
						that.show_sku = 1
						that.rdata = res.data.sku_arr
					}
					that.forms.banner_imgs = res.data.banner_imgs
					this.forms.video_id = res.data.video_id
					that.forms.goods_id = res.data.goods_id;
					that.forms.delivery_id = res.data.delivery_id
					that.sub_btn = false;
					if (res.data.video && res.data.video.description) {
						that.video_data = {
							url: res.data.video.url,
							id: res.data.video_id,
							description: res.data.video.description
						}
					} else {
						that.video_data = {
							url: res.data.video.url,
							id: res.data.video_id,
						}
					}
				});
				loadingInstance.close();
			},
			//提交 修改商品
			onEdit() {
				var that = this;
				console.log(this.forms)
				this.http.post_show("product/admin/edit_product", that.forms).then(() => {
					this.$message({
						showClose: true,
						message: "修改成功",
						type: "success"
					});
					that.rest();
				});
			},
			rest() {
				this.$emit("emit_tangou");
			}
		},

	};
</script>

<style lang="less">
	#release {
		.el-date-editor--time-select {
			margin: 0 10px;
		}

		.el-form-item__content {
			line-height: 25px;
		}

		.release {
			width: 100%;
			text-align: left;
		}

		.release-buy {
			font-weight: bold;
			line-height: 50px;
		}

		table.gridtable {
			font-family: verdana, arial, sans-serif;
			font-size: 11px;
			color: #333333;
			border-width: 1px;
			border-color: #ccc;
			border-collapse: collapse;
		}

		table.gridtable th {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #ccc;
			background-color: #f2f2f2;
		}

		table.gridtable td {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #ccc;
			background-color: #ffffff;
		}

		.upload-demo {
			width: 50% !important;
		}

		.picA {
			width: 148px;
			height: 148px;
			position: relative;
			background-color: #FBFDFF;
			border: 1px dashed #C0CCDA;
			border-radius: 6px;
			box-sizing: border-box;
			vertical-align: top;
			text-align: center;
			margin: 6px 6px 6px 10px;


			img {
				width: 144px;
				height: 144px;
				border: 1px solid #BFBFBF;
				border-radius: 3px;
			}

			.el-icon-circle-close {
				position: absolute;
				top: -13px;
				right: -10px;
				color: #7C7C7C;
				font-size: 25px;
				cursor: pointer;
			}
		}
	}
</style>
