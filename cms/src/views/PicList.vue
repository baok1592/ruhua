<template>
	<div class="about">
		<el-drawer title="图库管理" :visible.sync="drawer" direction="rtl" size="35%" :before-close="close_box">
			<span>
				<div class="rltan">
					<div class="btn">
						<el-button @click="dialogVisibleadd = true">添加分类</el-button>&emsp;
						<a href="https://luban.aliyun.com/" target="_blank">
							<el-button>在线制作图片</el-button>
						</a>
					</div>
					<el-tabs v-model="activeName" type="card" @tab-click="category_nav" closable @tab-remove="removeTab">
						<el-tab-pane label="全部" :name="0 + ''"></el-tab-pane>
						<template v-for="item of img_category">
							<el-tab-pane :label="item.category_name" :name="item.id + ''"></el-tab-pane>
						</template>
					</el-tabs>
					<div class="con">
						<el-upload :action="upfile_url" :data="{ cid: cate_id, back: 'idurl' }" :on-success="upimg_back_fun" :headers="upfile_head"
						 :limit="9" multiple :show-file-list="false" :file-list="upfile_banner_list" name="img" list-type="picture-card">
							<i class="el-icon-plus"></i>
						</el-upload>
						<el-dialog :visible.sync="dialogVisiblea"> </el-dialog>
						<div style="height: 25px"></div>
						<el-row :gutter="18">
							<template v-if="cate_id == 0">
								<!-- 全部图片 -->
								<el-col :span="6" v-for="(item, index) of img_list" :key="index">
									<div :class="tab_css(index)">
										<img class="img" :src="getimg + item.url" @click="choose_img(index)" />

									</div>
								</el-col>
							</template>
							<template v-else>
								<!-- 分类图片 -->
								<el-col class="abox" :span="6" v-for="(item, index) of imgB" :key="index">
									<div :class="tab_css(index)">
										<img class="img" :src="getimg + item.url" @click="choose_img(index)" />
									</div>
								</el-col>
							</template>
						</el-row>

						<div style="height:300px;"></div>
					</div>
					<div class="footbtn">
						<el-button @click="close_box">取消</el-button>
						<el-button @click="del_img" type="warning">删除</el-button>
						<el-button @click="add_img" type="primary">确定</el-button>
					</div>
				</div>
			</span>
		</el-drawer>

		<!-- 添加弹出框 -->
		<el-dialog title="添加图片分类" :visible.sync="dialogVisibleadd" width="30%">
			<el-form ref="cate_form" :model="cate_form" label-width="80px">
				<el-form-item label="分类名称">
					<el-input v-model="cate_form.category_name"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="dialogVisibleadd = false">取 消</el-button>
				<el-button type="primary" @click="onSubmit">确 定</el-button>
			</span>
		</el-dialog>

	</div>
</template>
<script>
	import {
		Api_url
	} from '@/common/config';
	export default {
		data() {
			return {
				activeName: 'first',
				form: {
					name: '',
					banner_imgs: ''
				},
				dialogVisibleadd: false,
				dialogVisiblea: false,
				getimg: this.$getimg,
				cate_id: '0',
				cate_form: {
					category_name: ''
				},
				img_category: '', //图片所有分类
				img_list: [], //图库所有图片
				checkList: [], //已选择的图片列表index
				add_img_list: [], //已选完传递到父组件的对象{id,url} 
				upfile_url: Api_url + 'img_category/admin/upload/img',
				upfile_head: {
					token: localStorage.getItem('token')
				},
				upfile_list: [], //上传文件列表
				upfile_list_sku: [], //上传文件列表--规格
				upfile_banner_list: [], //上传幻灯片列表
			};
		},
		props: {
			drawer: "",
			length: "",
			ishead: "",
			father_fun: Function,
			iscg: ''
		},
		components: {},
		mounted() {
			this._load();
			this.checkList = [];
			console.log(this.father_fun);
			console.log(123);
		},
		methods: {
			async _load() {
				await this.http.get('img_category/admin/get_all_img').then(res => {
					this.img_list = res.data;
				});
				await this.http.get('img_category/admin/get_category').then(res => {
					this.img_category = res.data;
				});
				console.log('获取数据结束')
			},
			//删除图片分类
			removeTab(targetName) {
				console.log(targetName)
				// let id = ''
				// for (let k in this.img_category) {
				// 	let v = this.img_category[k]
				// 	if(v.category_name == targetName){
				// 		id = v.id
				// 	}
				// }
				// console.log(id)
			},
			//新增
			onSubmit() {
				this.http.post_show('img_category/admin/add_category', this.cate_form).then(res => {
					this.$message({
						message: '添加成功',
						type: 'success'
					});
					this.dialogVisibleadd = false;
					this._load();
				});
			},
			//准备选中删除的图片
			change_del() {
				this.checkList = [];
				this.show_choose = '';
				this.length = this.img_list.length;
			},

			//选完图片-确定完成	
			add_img() {
				console.log('start:', this.checkList, this.img_list, this.add_img_list);
				const that = this;
				for (let k in this.checkList) {
					const index = this.checkList[k]
					for (let xb in this.img_list) {
						if (index == xb) {
							that.add_img_list.push({
								id: this.img_list[xb].id,
								url: this.img_list[xb].url
							});
						}
					}
				}
				if (this.add_img_list.length <= this.length) {
					const add_img_list = this.add_img_list
					this.add_img_list = [];
					this.checkList = [];
					this.father_fun(add_img_list);
					this.drawer = false;
				} else {
					this.$message({
						message: '最多选择' + this.length + '张图片',
						type: 'warning'
					});
					return;
				}
			},
			//选中图片时的样式
			tab_css(index) {
				if (this.checkList.indexOf(index) > -1) {
					return 'pic1';
				} else {
					return 'pic';
				}
			},
			//选择一张图片进入选择列表
			choose_img(inde) {
				console.log('choose_img', inde, this.length);
				const that = this;
				if (that.checkList.indexOf(inde) > -1) {
					let index = that.checkList.indexOf(inde);
					that.checkList.splice(index, 1);
				} else {
					if (that.checkList.length < that.length) {
						that.checkList.push(inde);
					} else {
						this.$message({
							message: '最多选择' + this.length + '张图片',
							type: 'warning'
						});
						return;
					}
				}
			},
			//删除图片
			del_img() {
				const that = this
				let ids = [];
				for (let k in this.checkList) {
					const index = this.checkList[k]
					for (let xb in this.img_list) {
						if (index == xb) {
							ids.push(this.img_list[xb].id);
						}
					}
				}
				console.log(ids)
				this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('img_category/admin/edit_image', {
						ids: ids
					}).then(res => {
						this.$message({
							type: 'success',
							message: res.msg
						});
						this.checkList = []
						if(this.cate_id != 0){
							for(let k in this.imgB){
								let v = this.imgB[k]
								for(let g of ids){
									if(v.id == g){
										that.imgB.splice(k,1)
									}
								}
							}
						}
						console.log(this.img_list)
						// this.category_nav(event, this.cate_id)
						this._load()
					});
				})
			},
			//图片上传回调
			async upimg_back_fun(res) {
				this.$message({
					type: 'success',
					message: '上传成功'
				});
				await this._load();
				this.category_nav(event, this.cate_id)
			},
			//切换分类
			category_nav(event, id) {
				console.log("event",event)
				console.log("id",id)
				const that = this
				let arr = [];
				if (event) {
					this.cate_id = event.name;
					for (let k in that.img_list) {
						let v = that.img_list[k];
						if (v.category_id == event.name) {
							arr.push(v);
						}
					}
				}
				if (id) {
					for (let k in that.img_list) {
						let v = that.img_list[k];
						if (v.category_id == id) {
							arr.push(v);
						}
					}
				}

				this.imgB = arr;
			},
			//取消并关闭 
			close_box() {
				console.log('xxx')
				this.add_img_list = [];
				this.checkList = [];
				this.add_img()
			}
		}
	};
</script>
<style lang="less">
	.about {
		text-align: left;
		padding: 20px;
	}

	.rltan {
		padding: 0 20px 0px;

		.el-upload--picture-card {
			width: 120px;
			height: 120px;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.btn {
			margin-bottom: 20px;
		}

		.pic {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			margin: 10px 0;

			img {
				width: 110px;
				height: 110px;
				border: 1px solid #bfbfbf;
				border-radius: 3px;
			}
		}

		.pic1 {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			margin: 10px 0;
			position: relative;


			img {
				width: 104px;
				height: 104px;
				border: 4px solid #fa2736;
				border-radius: 3px;
			}

			.black {
				background-color: #000000;
				position: absolute;
				top: 4px;
				left: 4px;
				opacity: 0.6;
				width: 104px;
				height: 104px;
				z-index: 999;
			}
		}

		.con {
			overflow-y: scroll;
			overflow-x: hidden;
			height: 600px;
			width: 100%;
		}

		.footbtn {
			text-align: right;
			position: fixed;
			bottom: 20px;
			right: 20px;
			background-color: #fff;
			z-index: 99;
		}
	}
</style>
