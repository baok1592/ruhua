<template>
	<div class="root">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<transition appear appear-active-class="animated fadeInLeft">
						<div class="article">
							<el-button type="primary" size="small" v-if="!is_form" @click="ontab_nav">{{btn_title}}</el-button>
							<el-button type="primary" size="small" @click="show_form">{{form_btn}}</el-button>
							<div style="height:20px;">&nbsp;</div>
							<template v-if="is_form">
								<Form :edit="is_form_edit"></Form>
							</template>
							<template v-if="tab_nav == 2">
								<el-row>
									<el-col :span="16">
										<el-form :model="form">
											<el-form-item label="类型" :label-width="formLabelWidth">
												<el-select v-model="form.type" placeholder="请选择类型">
													<el-option v-for="item in options" :key="item.value" :value="item.value" :label="item.label"></el-option>
												</el-select>
											</el-form-item>
											<el-form-item label="文章标题" :label-width="formLabelWidth">
												<el-input v-model="form.title" auto-complete="off"></el-input>
											</el-form-item>
											<el-form-item label="文章缩略图" :label-width="formLabelWidth">
												<el-button size="small" style="margin-bottom: 20px;" type="primary" @click="choose_pic" v-if="img_list.length < 1">选择图片</el-button>
												<template>
													<template v-if="img_list.length > 0">
														<div style="display: flex; width:700px ; flex-wrap: wrap;">
															<template v-for="(item,index) of img_list">
																<div class="picA" v-if="img_list.length > 0">
																	<img class="img" :src="$getimg + item.url">
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
											<template v-if="form.type == 2">
												<el-form-item label="Appid" :label-width="formLabelWidth">
													<el-input v-model="form.appid" placeholder="必填" auto-complete="off"></el-input>
												</el-form-item>
												<el-form-item label="路径" :label-width="formLabelWidth">
													<el-input v-model="form.path" placeholder="选填" auto-complete="off"></el-input>
												</el-form-item>
											</template>

											<el-form-item label="文章内容" :label-width="formLabelWidth">
												<vue-ueditor-wrap v-model="form.content" :config="myConfig"></vue-ueditor-wrap>
											</el-form-item>
										</el-form>
										<div class="dialog-footer">
											<el-button type="primary" @click="sub('form')" v-if="!isedit">确 定</el-button>
											<el-button type="primary" @click="sub_edit('form')" v-else>修 改</el-button>
										</div>
									</el-col>
								</el-row>
							</template>
							<template v-if="tab_nav == 1">
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="60">
										<el-input v-model="index"></el-input>
									</el-table-column>
									<el-table-column prop="title" label="文章标题" width="380">

									</el-table-column>
									<el-table-column label="文章图片" width="200%">
										<!-- <template slot-scope="scope">
											<template v-if="scope.row.img.url">
												<img :src="$getimg + scope.row.img.url" />
											</template>
											
										</template> -->
									</el-table-column>
									<el-table-column label="类型" width="180"></el-table-column>
									<el-table-column label="类别" width="100%">
										<template slot-scope="scope">
											<template v-for="item of options">
												<p v-if="scope.row.type == item.value">{{item.label}}</p>
											</template>
										</template>
									</el-table-column>
									<el-table-column prop="create_time" label="创建时间" width="100%"></el-table-column>
									<el-table-column prop="is_hidden" label="隐藏" width="100%">
										<template slot-scope="scope">
											<el-switch @change="onswitch(scope.row.id)" v-model="scope.row.is_hidden" :active-value="1" active-color="#409EFF"
											 inactive-color="#DCDFE6"></el-switch>
										</template>
									</el-table-column>

									<el-table-column prop="operation" label="操作" width="150%">
										<template slot-scope="scope">
											<el-button @click="edit_get(scope.row.id)" type="success" size="small">修改</el-button>
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id,scope.$index)">删除</el-button>
										</template>
									</el-table-column>
									<strong></strong>
								</el-table>
							</template>
							<el-button style="margin-top: 10px" type="success" size="small" slot="reference" >提交排序</el-button>
						</div>
					</transition>
				</el-main>
			</el-container>
		</el-container>
		<Pic ref="child" :drawer="drawer" :father_fun="get_img" :length="length" :iscg="is_cg"></Pic>
	</div>
</template>

<script>
	import Pic from '../PicList.vue'
	import {
		Loading
	} from "element-ui";
	import VueUeditorWrap from "vue-ueditor-wrap";
	import {
		Api_url
	} from "@/common/config";
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	import Form from "@/components/form.vue";

	export default {
		name: "Article",
		data() {
			return {
				is_form_edit:0,
				img_list: [],
				drawer: false,
				length: 1,
				is_cg: 0,
				btn_title: "添加文章",
				form_btn: '添加表单',
				isedit: false,
				tab_nav: 1,
				dialogFormVisible: false,
				myConfig: {
					autoHeightEnabled: false,
					initialFrameHeight: 420,
					initialFrameWidth: "100%",
					serverUrl: Api_url + 'index/admin/ue_uploads',
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
				options: [
					{
						value: 1,
						label: "独立文章"
					},
					{
						value: 2,
						label: "小程序跳转"
					},
					{
						value: 3,
						label: "公告"
					}, {
						value: 4,
						label: "活动"
					},
					{
						value: 5,
						label: "个人中心"
					}
				],
				oid: 0,
				form: {
					title: "",
					content: "",
					type: "",
					appid: "",
					path: "",
					image: []
				},
				formLabelWidth: "120px",
				list: [],
				is_form: false
			};
		},
		components: {
			VueUeditorWrap,
			NavTo,
			Header,
			Pic,
			Form,
		},
		methods: {
			show_form() {
				if (this.form_btn == '添加表单') {
					this.form_btn = '返回'
					this.is_form = true
					this.tab_nav = 3
				}else{
					this.form_btn = '添加表单'
					this.tab_nav = 1
					this.is_form = false
				}

			},
			ontab_nav() {
				this.isedit = false;
				if (this.tab_nav == 2) {
					this.btn_title = "添加文章";
					this.tab_nav = 1;
					this.clear_data();
				} else {
					this.btn_title = "文章列表";
					this.tab_nav = 2;
					this.clear_data();
				}
			},
			sub(form) {
				var that = this;
				let data = that.form;
				if (data["type"] == 2) {
					data["summary"] = data.appid
					data["content"] = data.path
				}
				delete data.appid;
				delete data.path;
				this.http.post_show("article/admin/add_article", that.form).then(res => {
					that.$message({
						showClose: true,
						message: "添加成功",
						type: "success"
					});
					that.tab_nav = 1
					this.btn_title = "添加文章"
					that.clear_data();
					that.getArticles();
				});
			},
			sub_edit(form) {
				var that = this;
				const data = that.form;
				data["oid"] = that.oid;
				if (data["type"] == 2) {
					data["summary"] = data.appid
					data["content"] = data.path
				}
				delete data.appid;
				delete data.path;
				this.http.post_show("article/admin/edit_article", data).then(res => {
					that.$message({
						showClose: true,
						message: "修改成功",
						type: "success"
					});
					this.img_list = []
					this.btn_title = "添加文章"
					this.tab_nav = false
					that.clear_data();
					that.getArticles();
				});
			},
			//获取所有文章
			getArticles() {
				var that = this;
				let loadingInstance = Loading.service({
					fullscreen: true
				}); //显示加载
				this.http.get("article/admin/get_all_article").then(res => {
					console.log(res.data);
					loadingInstance.close(); //关闭加载
					var res_code = res.status.toString();
					if (res_code.charAt(0) == 2) {
						that.list = res.data;

					}
				});
			},
			//删除商品
			del(id, index) {
				var that = this;
				this.$confirm("是否删除?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning"
				}).then(() => {
					this.http
						.put_show("article/admin/del_article", {
							id: id
						})
						.then(res => {
							that.$message({
								showClose: true,
								message: "删除成功",
								type: "success"
							});
							that.list.splice(index, 1);
						});
				});
			},
			//是否隐藏
			onswitch(id) {
				var that = this;
				this.http.put_show("/cms/update", {
						id: id,
						db: "article",
						field: "is_hidden"
					})
					.then(res => {
						console.log(res);
					});
			},
			edit_get(id) {
				var that = this;
				// -------------------------------------------文章类型为表单时的修改
				// this.tab_nav = 3
				// this.is_form  = true
				// this.form_btn = '返回'
				// this.is_form_edit = 1
				// -----------------------------------------------end
				
				
				// 此时没有接口,需要从接口中判断文章列表中的文章类型为表单还是文章,根据值跳转不同界面
				
				// -------------------------------------------------------------类型为文章时取消注释，勿删
				this.http.get("article/get_one_article?id=" + id).then(res => {
					that.btn_title = "文章列表";
					that.form.title = res.data.title;
					that.form.content = res.data.content;
					that.form.type = res.data.type;
					if (res.data.type == 2) {
						that.form.appid = res.data.summary;
						that.form.path = res.data.content;
					}
					this.img_list.push(res.data.img)
					that.isedit = true;
					that.oid = res.data.id;
					that.tab_nav = 2;
				});
				// ------------------------------------------------------------------end
			},
			close_fun(done) {
				this.clear_data();
				done(); //官方实例用法
			},
			clear_data() {
				this.img_list = []
				this.dialogFormVisible = false;
				this.form.type = "";
				this.form.title = "";
				this.form.content = "";
				this.oid = 0;
			},
			get_img(e) {
				this.drawer = false
				for (let k in e) {
					const v = e[k]
					this.img_list.push(v)
					this.form.image = v.id
				}
				this.length = 1 - this.img_list.length
				console.log('get_img_end:', e, this.img_list)
			},
			//打开图库
			choose_pic() {
				this.length = 1 - this.img_list.length
				this.drawer = true
			},
			del_img(index) {
				this.img_list.splice(index, 1)
				this.form.image.splice(index, 1)
			},
		},
		mounted() {
			this.getArticles();
		}
	};
</script>

<style lang="less" scoped="">
	.article {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}

	.list-head {
		padding-bottom: 10px;
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
</style>
