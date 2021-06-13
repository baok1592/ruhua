<template>
	<div class="about">
		<el-drawer title="视频管理(视频上传（10M）以内：)" :visible.sync="drawer" direction="rtl" size="35%" :before-close="close_box">
			<span>
				<div class="rltan">
					<div class="btn">
						<a href="https://alibabawood.aliyun.com/web/Home" target="_blank">
							<el-button>在线制作视频</el-button>
						</a>
					</div>

					<video v-if="show" controls="controls" width="300" height="200" autoplay="autoplay">
						<source :src="play_url" type="video/mp4">
					</video>


					<!-- <div class="btn">
						<el-button @click="dialogVisibleadd = true">添加</el-button>
					</div>
					<el-tabs v-model="activeName" type="card" @tab-click="category_nav">
						<el-tab-pane label="全部" :name="0 + ''"></el-tab-pane>
						<template v-for="item of img_category">
							<el-tab-pane :label="item.category_name" :name="item.id + ''"></el-tab-pane>
						</template>
					</el-tabs> -->
					<div class="con" v-loading.fullscreen.lock="fullscreenLoading">
						<!-- 上传图片 -->
						<el-row>
							<el-input v-model="des" placeholder="请输入视频描述" style="width: 30%;margin-right: 10px;"></el-input>
							<input type="file" id="fileExport" @change="upload" ref="inputer" >
							<el-button @click="handleFileChange" type="primary" size="mini">确定上传</el-button>
						</el-row>

						<!-- 上传图片 -->
						<el-dialog :visible.sync="dialogVisiblea"> </el-dialog>
						<div style="height: 25px"></div>
						<el-row :gutter="18">
							<template v-if="cate_id == 0">
								<!-- 全部图片 -->
								<el-col :span="6" v-for="(item, index) of img_list" :key="index">
									<div :class="tab_css(index)" @click="choose_img(index)" style="word-wrap: normal; word-break: break-all; padding: 5px; text-align: center;line-height: 30px;overflow: hidden;">
										<template>
											<template v-if="item.description">{{item.description}}</template>
											<template v-else>{{item.url}}</template>
										</template>
										<!-- <img class="img" :src="getimg + item.url" @click="choose_img(index)" /> -->

									</div>
									<el-button v-if="play_index!=index" style="margin-left: 25%" type="primary" size="mini" @click="play(item.url,index)">播放</el-button>
									<el-button v-else style="margin-left: 25%" type="danger" size="mini" @click="cancel_play(index)">取消</el-button>
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
		<el-dialog title="添加视频分类" :visible.sync="dialogVisibleadd" width="30%">
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
				up_video:'',
				des:'',
				show: false,
				play_url: '',
				fullscreenLoading: false,
				activeName: 'first',
				form: {
					name: '',
					banner_imgs: ''
				},
				play_index: -1,
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
				upfile_url: Api_url + 'video/admin/add_video',
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
		created() {
			this._load();
			this.checkList = [];
		},
		methods: {
			_load() {
				//获取所有视频
				this.http.get('video/admin/get_all_video').then(res => {

					for (let k in res.data) {
						res.data[k].is_play = 0
					}
					this.img_list = res.data;
				});
				// this.http.get('img_category/admin/get_category').then(res => {
				// 	this.img_category = res.data;
				// });
			},
			play(url, index) {
				this.play_index = index
				this.show = false
				this.play_url = ''
				setTimeout(() => {
					this.show = true
					this.play_url = Api_url + url
				}, 100)

				console.log(this.play_url)
				console.log(this.show)
			},
			cancel_play(index) {
				if (this.play_index == index) {
					this.show = false
					this.play_index = -1
				}
			},
			handleFileChange() {
				
				let inputDOM = this.$refs.inputer;
				let file = inputDOM.files[0]; // 通过DOM取文件数据
				let size = Math.floor(file.size / 1024); //计算文件的大小　
				console.log(size / 1024)
				let formData = new FormData(); //new一个formData事件
				formData.append("file", file); //将file属性添加到formData里
				formData.append("description", this.des); //将file属性添加到formData里
				console.log(file)
				if ((size / 1024) > 10) {
					this.$message.error('上传文件大小不能超过10M');
				} else { 
					
					this.http.file_post('video/admin/add_video', formData).then(res => {
						this.fullscreenLoading = false
						this.$message({
							message: res.msg,
							type: 'success'
						});
						this.des = ''
						this._load()
						
					})
				}
				//此时formData就是我们要向后台传的参数了
			},
			upload(){
				
			},
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
								url: this.img_list[xb].url,
								description: this.img_list[xb].description,
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
						message: '最多选择' + this.length + '个视频',
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
							message: '最多选择' + this.length + '个视频',
							type: 'warning'
						});
						return;
					}
				}
			},
			//删除图片
			del_img() {
				let ids = [];
				for (let k in this.checkList) {
					const index = this.checkList[k]
					for (let xb in this.img_list) {
						if (index == xb) {
							ids.push(this.img_list[xb].id);
						}
					}
				}
				this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('video/admin/edit_video', {
						ids: ids
					}).then(res => {
						this.$message({
							type: 'success',
							message: res.msg
						});
						this.checkList = [];
						this._load();
					});
				})
			},
			//图片上传回调
			upimg_back_fun(res) {
				this.$message({
					type: 'success',
					message: '上传成功'
				});
				console.log(res)
				console.log(this.upfile_banner_list)
				this._load();
			},
			//切换分类
			category_nav(event) {
				this.cate_id = event.name;
				let arr = [];
				for (let k in this.img_list) {
					let v = this.img_list[k];
					if (v.category_id == event.name) {
						arr.push(v);
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


			width: 110px;
			height: 110px;
			border: 1px solid #bfbfbf;
			border-radius: 3px;

		}

		.pic1 {
			display: flex;
			flex-wrap: wrap;
			justify-content: space-around;
			margin: 10px 0;
			position: relative;



			width: 104px;
			height: 104px;
			border: 4px solid #fa2736;
			border-radius: 3px;


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
			height: 700px;
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
