<template>
	<div id="category">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main style="background-color: #F3F3F3;">
						<div class="category">
							<!-- 弹窗--新增分类 -->
							<AddCategory @up_parent="up_list" :nav_list="list"></AddCategory>

							<!-- 弹窗--修改分类 -->
							<el-dialog title="" :visible.sync="editbox" width="35%" center @close="close">
								<el-form :model="editform">
									<el-form-item label="导航名称" :label-width="formLabelWidth" style='width: 80%'>
										<el-input v-model="editform.nav_name" auto-complete="off"></el-input>
									</el-form-item>
									<el-form-item label="跳转到" :label-width="formLabelWidth">
										<el-select v-model="editform.url_name" placeholder="请选择跳转地址" @change="get_jump_url">
											<el-option v-for="item in list" :value="item" :label="item.name"></el-option>
										</el-select>
									</el-form-item>
									<el-form-item label="选择分类" :label-width="formLabelWidth" v-if="editform.url_name == '分类'">
										<el-select v-model="editform.category" placeholder="请选择跳转的分类" @change="creat_url">
											<!-- <el-option label="顶级分类" value="0"></el-option> -->
											<el-option v-for="item in category_nav" :value="item.category_id" :label="item.category_name"><span v-if="item.pid != 0">&nbsp;&nbsp;|--{{item.category_name}}</span></el-option>
										</el-select>
									</el-form-item>
									<template v-if="editform.url_name == '其他小程序'">
										<el-form-item label="appID" :label-width="formLabelWidth" style='width: 80%'>
											<el-input v-model="editform.other" auto-complete="off"></el-input>
										</el-form-item>
										<el-form-item label="路径" :label-width="formLabelWidth" style='width: 80%'>
											<el-input v-model="editform.url" auto-complete="off"></el-input>
										</el-form-item>
									</template>
									<el-form-item label="选择图片" :label-width="formLabelWidth">
										<div style="display: flex; width:530px ; flex-wrap: wrap;" v-if="editform.img_id">
											<i class="el-icon-circle-close" @click="delimg"></i>
											<div class="picA" @click="choose_pic">
												<img class="img" :src="getimg + editform.img_id">
											</div>
										</div>
										<div v-else class="picA" style="margin-left: 19px;" @click="choose_pic">
											<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
										</div>
									</el-form-item>
								</el-form>
								<div slot="footer" class="dialog-footer" style='text-align: center'>
									<el-button @click="editbox = false">取 消</el-button>
									<el-button type="primary" @click="onSubmit(editindex)">确 定</el-button>
								</div>
							</el-dialog>

							<template>
								<el-table :data="category" border style="width: 100%">
									<el-table-column prop="" label="排序" width="70">
										<template slot-scope="scope">
											<el-input v-model="scope.row.sort"></el-input>
										</template>
									</el-table-column>
									<el-table-column type="index" label="序号" width="80"></el-table-column>
									<el-table-column prop="nav_name" label="导航名称"></el-table-column>
									<el-table-column prop="img_id" label="导航图标" width="100">
										<template slot-scope="scope">
											<img class="navimg" :src="getimg + scope.row.img_id" />
										</template>
									</el-table-column>
									<el-table-column prop="url" label="跳转路径"></el-table-column>
									<el-table-column prop="level" label="操作">
										<template slot-scope="scope">
											<el-button type="success" size="small" @click="onedit(scope.$index)">修改</el-button>
											<el-button type="danger" size="small" @click="del(scope.row.id,scope.$index)">删除</el-button>
										</template>
									</el-table-column>
								</el-table>
							</template>
							<div class="sort_btn">
								<el-button type="success" size="small" @click="sort_sub">提交排序</el-button>
							</div>
						</div>

					</el-main>

				</transition>
			</el-container>
		</el-container>
		<!-- <Pic :drawer="drawer" @drawer="is_show" @get_img="get_img" :length="length"></Pic> -->
		<Pic :drawer="drawer" :father_fun="get_img" :length="length"></Pic>
	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import AddCategory from '../../components/addnav.vue'
	import {
		Api_url
	} from '@/common/config.js'
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	import Pic from '../PicList.vue'
	export default {
		name: 'Category',
		components: {
			AddCategory,
			NavTo,
			Header,
			Pic
		},
		data() {
			return {
				show_appid_input: 0,
				list: [{
						id: 1,
						name: '签到',
						url: '/pages/qiandao/qiandao'
					},
					{
						id: 2,
						name: '积分',
						url: '/pages/point/point'
					},
					{
						id: 3,
						name: '秒杀',
						url: '/pages/miaosha/miaosha'
					},
					{
						id: 4,
						name: '限时折扣',
						url: '/pages/discount/discount'
					},
					{
						id: 16,
						name: '一元团购',
						url: '/pages/one/one'
					},
					{
						id: 5,
						name: '拼团',
						url: '/pages/pin/pin'
					},
					{
						id: 6,
						name: '直播',
						url: '/pages/zhibo/zhibo'
					},
					{
						id: 7,
						name: '活动',
						url: '/pages/notice/notice'
					},
					{
						id: 8,
						name: '优惠券',
						url: '/pages/coupon/coupon'
					},
					{
						id: 9,
						name: '分销商品',
						url: '/pages/extend-view/productList/productList?type=fx'
					},
					{
						id: 10,
						name: '视频购',
						url: '/pages/zhibo/zhibo'
					},
					{
						id: 11,
						name: '大转盘',
						url: '/pages/activity/draw'
					},
					{
						id: 12,
						name: '九宫格',
						url: '/pages/sherpa-jiugongge/sherpa-jiugongge'
					},
					{
						id: 13,
						name: '种树',
						url: '/pages/watering/watering'
					},
					{
						id: 15,
						name: '分类',
						url: '/pages/extend-view/productList/productList'
					},
					{
						id: 14,
						name: '其他小程序',
						url: '',
						other: ''
					},
				],
				length: 1,
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				input: '',
				category: [],
				category_nav: [],
				category_top: [],
				dialogTableVisible: false,
				editbox: false, //修改的box
				editform: {
					nav_name: '',
					url_name: '',
					img_id: '',
					url: '',
				},
				formLabelWidth: '120px',
				editindex: 0,
				upfile_url: Api_url + '/admin/upload/img',
				upfile_data: {
					use: 'category'
				},
				upfile_head: {
					token: localStorage.getItem("token")
				}
			};
		},
		methods: {

			creat_url(e) {
				console.log(e)
				this.editform.url = '/pages/extend-view/productList/productList?cid=' + e + '&sid=0'
			},
			close() {
				this.editform = {
						nav_name: '',
						url_name: '',
						img_id: '',
						url: '',
					},
					this.img_list = []
			},
			getCategory() {
				var that = this;
				//获取所有导航	
				this.http.get('nav/admin/all_nav')
					.then((res) => {
						that.category = res.data;
					});

				this.http.get("category/admin/all_category").then(res => {

					if (res.data.length > 0) {
						that.category_nav = res.data;
						console.log(that.category_nav)
					} else {
						this.$message({
							showClose: true,
							message: "请先创建商品分类",
							type: "warning"
						});
					}
				});
			},

			to_choose_img() {
				this.drawer = !this.drawer
			},
			is_show() {
				this.drawer = !this.drawer
			},
			get_img(e) {
				this.drawer = false
				for (let k in e) {
					const v = e[k]
					this.img_list.push(v)
					this.editform.img_id = v.url
				}
				this.length = 6 - this.img_list.length
				console.log('get_img_end:', e, this.img_list)
			},
			//打开图库
			choose_pic() {
				this.length = 6 - this.img_list.length
				this.drawer = true
			},
			// get_img(e) {
			// 	this.img_list = e
			// 	for (let v in e) {
			// 		this.editform.img_id = e[v].url
			// 	}
			// 	console.log(this.form.img_id)
			// },
			delimg() {
				this.editform.img_id = ''
			},

			get_jump_url(e) {
				console.log(e)
				this.editform.url = e.url
				this.editform.url_name = e.name
			},

			//删除导航
			del(id, index) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('nav/admin/del_nav', {
						id: id
					}).then((res) => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						that.category.splice(index, 1);
						return false;
					})
				})
			},
			//修改导航
			onedit(index) {
				this.editform = this.category[index];
				if (this.category[index].other) {
					this.editform.other = this.category[index].other
				}
				this.editindex = index;
				this.editbox = true;
				console.log(this.editform)
			},
			//新增分类成功后
			up_list() {
				this.getCategory();
			},
			//提交排序
			sort_sub() {
				let obj = {}
				const that = this
				for (let value of that.category) {
					obj[value.id] = value.sort
				}
				this.http.post_show('nav/admin/set_sort', obj)
					.then((res) => {
						this.$message({
							message: '操作成功',
							type: 'success'
						})
					})
			},
			//更新分类
			onSubmit() {
				var that = this;
				this.http.post_show('nav/admin/edit_nav', that.editform)
					.then((res) => {
						this.$message({
							message: '修改成功',
							type: 'success'
						})
						that.editbox = false;
						that.getCategory()
					});
			},
			//是否隐藏
			onswitch(id) {
				var that = this;
				this.http.put_show('/cms/update', {
						id: id,
						db: 'category',
						field: 'is_visible'
					})
					.then((res) => {
						console.log(res);
					});
			},
			up_ok(res) {
				if (res.code == 201) {
					this.editform.category_pic = res.id;
				}
			},

		},
		mounted() {
			this.getCategory(); //获取分类  
		}
	}
</script>

<style lang="less" scoped="">
	#category {
		.navimg {
			height: 60px;
			width: 60px;
		}

		.category {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
			text-align: left;
		}

		.sort_btn {
			margin-top: 20px;
			;
		}

		.btn {
			margin-bottom: 6px;
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
	}
</style>
