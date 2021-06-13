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
							<AddCategory @up_parent="up_list" :list="category_top"></AddCategory>

							<!-- 弹窗--修改分类 -->
							<el-dialog title="" :visible.sync="editbox" width="35%" center>
								<el-form :model="editform">
									<el-form-item label="商品分类名称" :label-width="formLabelWidth" style='width: 80%'>
										<el-input v-model="editform.category_name" auto-complete="off"></el-input>
									</el-form-item>
									<el-form-item label="商品分类简称" :label-width="formLabelWidth" style='width: 80%'>
										<el-input v-model="editform.short_name" auto-complete="off"></el-input>
									</el-form-item>
									<el-form-item label="上级分类" :label-width="formLabelWidth">
										<el-select v-model="editform.pid" placeholder="请选择分类">
											<el-option label="顶级分类" value="0"></el-option>
											<el-option v-for="item in category_top" :value="item.category_id" :label="item.category_name"></el-option>
										</el-select>
									</el-form-item>
									<el-form-item label="选择图片" :label-width="formLabelWidth">
										<div style="display: flex; width:530px ; flex-wrap: wrap;" v-if="editform.imgs">
											<i class="el-icon-circle-close" @click="delimg"></i>
											<div class="picA" @click="to_choose_img">
												<img class="img" :src="getimg + editform.imgs">
											</div>
										</div>
										<div v-else class="picA" style="margin-left: 19px;" @click="to_choose_img">
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
								<el-table :data="category" border style="width: 100%" :default-sort="{prop: 'sort', order: 'descending'}">
									<!-- <el-table-column prop="sort" label="排序" width="100" sortable>
										<template slot-scope="scope">
											<el-input v-model="scope.row.sort"></el-input>
										</template>
									</el-table-column> -->
									<el-table-column type="index" label="序号" width="80">
									</el-table-column>
									<el-table-column prop="category_name" label="分类名字">
										<template slot-scope="scope">
											<span v-if="scope.row.line">&emsp;&emsp;|——</span>{{scope.row.category_name}}
										</template>
									</el-table-column>
									<el-table-column prop="short_name" label="商品分类简称">
									</el-table-column>
									<el-table-column prop="imgs" label="缩略图">
										<template slot-scope="scope">
											<img :src="$getimg+scope.row.imgs" width="40" height="40" />
										</template>
									</el-table-column>
									<el-table-column prop="pid" label="Pid">
									</el-table-column>
									<el-table-column prop="category_id" label="分类id">
									</el-table-column>
									<el-table-column prop="is_visible" label="是否显示">
										<template slot-scope="scope">
											<el-switch @change="onswitch(scope.row.category_id)" v-model="scope.row.is_visible" active-color="#409EFF"
											 inactive-color="#DCDFE6">
											</el-switch>
										</template>
									</el-table-column>
									<el-table-column prop="level" label="操作">
										<template slot-scope="scope">
											<el-button type="success" size="small" @click="onedit(scope.row.category_id)">修改</el-button>
											<el-button type="danger" size="small" @click="del(scope.row.category_id,scope.$index)">删除</el-button>
										</template>
									</el-table-column>
								</el-table>
							</template>
							<!-- <div class="sort_btn">
								<el-button type="success" size="small" @click="sort_sub">提交排序</el-button>
							</div> -->
						</div>

					</el-main>

				</transition>
			</el-container>
		</el-container>
		<!-- <Pic :drawer="drawer" @drawer="is_show" @get_img="get_img" :length="length"></Pic> -->
		<Pic ref="child" :drawer="drawer" :father_fun="get_img" :length="length" :iscg="is_cg"></Pic>
	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import AddCategory from '../../components/AddCategory.vue'
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
				is_cg: 0,
				length: 1,
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				input: '',
				category: [],
				category_top: [],
				dialogTableVisible: false,
				editbox: false, //修改的box
				editform: {
					category_name: '',
					short_name: '',
					pid: '',
					category_pic: [],
					imgs: []
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

			getCategory() {
				var that = this;
				// 				let loadingInstance = Loading.service({
				// 					fullscreen: true
				// 				}); //显示加载
				var arr = [];
				//获取所有分类	
				this.http.get('category/admin/all_category')
					.then((res) => {
						// loadingInstance.close(); //关闭加载 
						console.log(that.category);
						for (var i = 0; i < res.data.length; i++) {
							if (res.data[i].level == 1) {
								arr.push(res.data[i]);
							}
							if (res.data[i].level == 2) {
								res.data[i].line = true;
							} else {
								res.data[i].line = false;
							}
						}
						that.category = res.data;
						that.category_top = arr;
					});
			},


			to_choose_img() {
				this.drawer = !this.drawer
			},
			is_show() {
				this.drawer = !this.drawer
			},
			get_img(e) {
				// this.img_list = e
				// for (let v in e) {
				// 	this.editform.category_pic = e[v].id
				// 	this.editform.imgs = e[v].url
				// }
				// console.log(this.form.img_id)



				this.drawer = false
				for (let k in e) {
					const v = e[k]
					this.img_list.push(v)
					this.editform.category_pic = v.id
					this.editform.imgs = v.url
				}
				this.length = 6 - this.img_list.length
				console.log('get_img_end:', e, this.img_list)



			},
			delimg(index) {
				// this.img_list.splice(index, 1)
				this.editform.imgs = ''
			},


			//删除分类
			del(id, index) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('category/admin/del_category', {
						id: id
					}).then((res) => {
						if (res.data == 1) {
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'success'
							});
						}else{
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'warning'
							});
						}

						this.getCategory()
					})
				})
			},
			//修改分类
			onedit(id) {
				console.log(id)

				// this.editform = this.category[index];
				const that = this
				for (let k in that.category) {
					let v = that.category[k]
					if (v.category_id == id) {
						that.editform = v
					}
				}
				console.log(this.editform)
				// this.editindex = index;
				this.editbox = true;
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
					obj[value.category_id] = value.sort
				}
				this.http.post_show('category/admin/set_sort', obj)
					.then((res) => {
						this.$message({
							message: '操作成功',
							type: 'success'
						})
					})
			},
			//更新分类
			onSubmit(index) {
				const that = this;
				this.http.post_show('category/admin/edit_category', {
					form: that.editform
				}).then((res) => {
					this.$message({
						message: '修改成功',
						type: 'success'
					})
					// console.log(res.data);
					that.editbox = false;
					// this.category.splice(index, 1, res.data);
					this.getCategory()
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
		.category {
			line-height: 30px;
			text-align: left;
			background-color: #fff;
			padding: 15px;
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
