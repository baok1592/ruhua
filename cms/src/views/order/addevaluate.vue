<template>
	<div class="list">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>

			<el-container style="" class="pro-list">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<div class="add">
						<div class="add_btn">
							<el-button type="primary" @click="jumpback">返回</el-button>
						</div>
						<el-form ref="forms" :model="forms" label-width="120px">
							<el-form-item label="商品选择">
								<el-select v-model="forms.goods_id" placeholder="请选择商品分类">
									<el-option v-for="(item,index) of goods" :value="index" :label="item" :key="index"></el-option>
								</el-select>
							</el-form-item>
							<el-form-item label="评价时间">
								<el-date-picker v-model="value1" type="date" placeholder="选择日期" @change="get_time" value-format="yyyy-MM-dd">
								</el-date-picker>
							</el-form-item>
							<el-form-item label="用户昵称">
								<el-input v-model="forms.nickname" style="width:35%"></el-input>
							</el-form-item>
							<el-row>
								<el-col :span="8">
									<el-form-item label="选择头像">
										<template v-if="headpic.length > 0">
											<div style="display: flex; width:530px ; flex-wrap: wrap;">
												<template v-for="(item,index) of headpic">
													<i class="el-icon-circle-close" @click="delheadpic(index)"></i>
													<div class="picA" v-if="headpic.length > 0">
														<img class="img" :src="getimg + item.url">
													</div>
												</template>
											</div>
										</template>
										<div class="picA" style="margin-left: 19px;" @click="to_choose_headpic" v-if="headpic.length < 1">
											<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
										</div>
									</el-form-item>
								</el-col>
							</el-row>
							<el-row>
								<el-form-item label="评价图片" style="width:100%">
									<el-button size="small" style="margin-bottom: 20px;" type="primary" @click="to_choose_img" v-if="img_list.length < 9">选择图片</el-button>
									<template v-if="img_list.length > 0">
										<div style="display: flex; width:530px ; flex-wrap: wrap;">
											<template v-for="(item,index) of img_list">
												<i class="el-icon-circle-close" @click="delimg(index)"></i>
												<div class="picA" v-if="img_list.length > 0">
													<img class="img" :src="getimg + item.url">
												</div>
											</template>
										</div>
									</template>
									<!-- <div class="picA" style="margin-left: 19px;" @click="to_choose_img" v-if="img_list.length < 9">
										<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
									</div> -->
								</el-form-item>
							</el-row>
							<el-form-item label="评分">
								<el-rate v-model="forms.rate" style="padding-top: 10px" @change="get_rate"></el-rate>
							</el-form-item>
							<el-form-item label="评价">
								<el-input type="textarea" v-model="forms.content" style="width:35%"></el-input>
							</el-form-item>
						</el-form>

						<span slot="footer" class="dialog-footer ">
							<el-button @click="jumpback">取 消</el-button>
							<el-button type="primary" @click="onSubmit">确 定</el-button>
						</span>
					</div>
				</el-main>
			</el-container>
		</el-container>
		<!-- <Pic :drawer="drawer" @drawer="is_show" @get_img="get_img" :length="length" :ishead="ishead"
		@get_headpic="get_headpic"></Pic> -->
		<Pic :drawer="drawer"  :father_fun="get_img" :length="length"></Pic>
	</div>
</template>

<script>
	import {
		Api_url
	} from "@/common/config";
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	import Pic from '../PicList.vue'

	export default {
		data() {
			return {
				get_img:Function,
				ishead: '',
				length: '',
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				headpic: [],
				value1: '',
				forms: {
					goods_id: '',
					rate: '',
					content: '',
					imgs: [],
					headpic: '',
					nickname: '',
					create_time: ''
				},
				goods:''
			}
		},
		components: {
			NavTo,
			Header,
			Pic
		},
		//vue生命函数
		mounted() {
			this.get_all_goods()
		},
		methods: {
			get_all_goods(){
				this.http.get('product/admin/all_goods_name').then((res) => {
					let goods = {}
					for (let v of res.data) {
						goods[v.goods_id] = v.goods_name;
					}
					this.goods = goods
					console.log(this.goods)
				});
			},
			onSubmit() {
				this.http.post_show('rate/admin/add_rate',this.forms).then(res=>{
					this.$message({
						showClose: true,
						message: '提交成功',
						type: 'success'
					});
					this.forms={
						goods_id: '',
						rate: '',
						content: '',
						imgs: [],
						headpic: '',
						nickname: '',
						create_time: ''
					}
					this.img_list= []
					this.headpic = []
					this.jumpback()
				})
			},
			get_rate(e){
				console.log(e)
				this.forms.rate = e
			},
			emit_one() {
				console.log(123)
			},
			jumpback() {
				this.$router.push({
					path: './evaluate'
				})
			},

			to_choose_img() {
				this.drawer = !this.drawer
				this.length = 9
				this.ishead = ''
				this.get_img = this.get_banner
			},
			to_choose_headpic() {
				this.drawer = !this.drawer
				this.length = 1
				this.ishead = 1
				this.get_img = this.get_headpic
			},
			is_show() {
				this.drawer = !this.drawer
			},
			// get_img(e) {
			// 	this.img_list = e
			// 	let arr =[]
			// 	for (let v in e) {
			// 		arr.push(e[v].id)
			// 	}
			// 	this.forms.imgs = arr
			// 	console.log(arr)
			// },
			get_headpic(e) {
				this.drawer = false
				for (let k in e) {
					const v=e[k]
					// this.img_list.push(v)
					this.headpic.push(v)
					this.forms.headpic = v.id
				}
				this.length=6-this.img_list.length
				console.log('get_img_end:',e,this.img_list)
			},
			get_banner(e){
				this.drawer = false
				for (let k in e) {
					const v=e[k]
					this.img_list.push(v)
					this.forms.imgs.push(v.id+'')
				}
				this.length=6-this.img_list.length
				console.log('get_img_end:',e,this.img_list)
			},
			delimg(index) {
				this.img_list.splice(index, 1)
			},
			delheadpic(index) {
				this.headpic.splice(index, 1)
			},
			get_time(e){
				console.log(e)
				this.forms.create_time = e
			},
			up_ok(res) {
				if (res.code == 201) {
					this.addform.category_pic = res.id;
				}
			},

		},

		
	}
</script>

<style lang="less" scoped="">
.add{
	line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
}
	.pro-list {
		line-height: 30px;
		text-align: left;
	}

	.picA {
		width: 148px;
		height: 148px;
		background-color: #FBFDFF;
		border: 1px dashed #C0CCDA;
		border-radius: 6px;
		box-sizing: border-box;
		vertical-align: top;
		text-align: center;
		margin: 6px;


		img {
			width: 144px;
			height: 144px;
			border: 1px solid #BFBFBF;
			border-radius: 3px;
		}
	}
</style>
