<template>
	<div id="sku_tag">
		<div class="form-group">
			<div class="form-h">商品规格</div>
			<div class="form-item" v-for="(attr,index) in attrs" :key="index">
				<div class="form-title">
					<el-input class="sku-name" v-model="attr.pName" placeholder="规格名" />
					<el-checkbox v-model="checked" v-if="index==0">添加规格图片</el-checkbox>
					<span class="delete" @click="toDelete(index)">×</span>
				</div>
				<ul class="form-list">
					<li v-for="(item,index2) in attr.spec" :key="index2">
						<div class="spgg_r_02_2">
							<el-input v-model="item.cName"></el-input>
							<div class="in_clo" @click="del_canshu(index,index2)">
								<i class="el-icon-error" style="color:#cecece;"></i>
							</div>
						</div>
						<div v-if="checked && index==0">
							
							<div class="picA" @click="choose_pic(index2)" v-if="checked"> 
								<img v-if="item.pic" :src="getimg + item.pic" />
								<i v-else class="el-icon-plus sku-img"></i> 			
							</div> 
						</div>
					</li>
					<li>
						&emsp;
						<button class="btn" type="button" name @click="add_canshu(index)">添加</button>
					</li>
				</ul>
				<div class="spgg_r_04" v-if="checked && index==0">
					仅支持为第一组规格设置规格图片，建议尺寸：160*160像素
				</div>
			</div>
			<div class="form-btn-group">
				<el-button size="mini" :disabled="attrs.length==3?true:false" type="button" name @click="addItem">添加规格项目</el-button>
			</div>
			<div class="form-table" v-show="tableData">
				<div class="stock-title">商品库存</div>
				<table class="table-sku" border="1px solid #ccc">
					<thead>
						<tr>
							<th v-for="(list,index) in tableData" :key="index">{{list['pName']}}</th>
							<th width="200">价格</th>
							<th width="200">库存</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="row in rows" :key="row">
							<td v-for="(item,index2) in tableData" v-if="!((row-1)%item['rowspan'])" :rowspan="item['rowspan']" :key="index2">{{item|getName(row)}}</td>
							<td>
								<input v-model="tableList[row-1]['price']" placeholder="单价" />
							</td>
							<td>
								<input v-model="tableList[row-1]['stock_num']" placeholder="库存" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div> 
		<Pic :drawer="drawer"  :father_fun="get_img" :length="length"></Pic>
	</div>
</template>

<script>
	import {
		Api_url
	} from "@/common/config";
	import Pic from '../views/PicList.vue'
	export default {
		data() {
			return {
				oncli:"",
				img_list: [],
				drawer:false,
				length:1,
				getimg: this.$getimg,
				checked: false,
				sku_img_index: '', //规格图片下标
				attrs: [],
				dynamicTags: [],
				inputVisible: false,
				inputValue: "",
				edit_data: {}, //原修改数据      
				upfile_url: Api_url + "/admin/upload/img",
				upfile_head: {
					token: localStorage.getItem("token")
				},
				get_img:Function,
				imageUrl: [],
				img_ids: []
			};
		},
		components:{
			Pic
		},
		props: {
			sub: Number, //获取最终数据用于提交
			rdata: Object, //修改时的原数据
			del: Number //情况数据
		},
		mounted() {
			//console.log("atr:", this.attrs);
			console.log("data:", this.tableData);
			// console.log("rows", this.rows);
			//console.log(this.tableList);
		},
		watch: {
			//父组件点击提交按钮，本组件传递数据出去
			sub() {
				console.log('传递规格-最终参数')
				const data = {}
				data['list'] = this.tableList
				if(this.checked){
					data['sku_img_id'] = this.img_ids
				}else{
					data['sku_img_id'] = []
				}
				this.$emit('pro_sku', data)
			},
			//父组件提交成功后，清空本组件数据
			del() {
				console.log('sku-clear')
				this.attrs = []
			},
			//修改时的原数据
			rdata(e) {
				this.edit_data = e
				this.edit_old(e);
			}
		},
		filters: {
			getName: function(obj, index) {
				if (obj) {
					let r = parseInt((index - 1) / obj["rowspan"]);
					let l = obj["specLen"] || 1;
					let key = r % l;
					return obj["spec"] && obj["spec"][key] && obj["spec"][key]["cName"];
				}
			}
		},
		computed: {
			//规格table
			tableData: function() {
				let attrs = this.attrs;
				let len = attrs.length;
				if (len == 0) {
					return;
				}
				let tData = [];
				//初始化tableData
				for (let i = 0; i < len; i++) {
					let row = {};
					row["pName"] = attrs[i]["pName"];
					row["spec"] = [];
					row["price"] = {};
					row["stock_num"] = {};
					let len2 = attrs[i]["spec"].length;
					let specLen = 0;
					for (let j = 0; j < len2; j++) {
						let spe = {};
						let cName = attrs[i]["spec"][j]["cName"];
						if (!cName) {
							continue;
						}
						++specLen;
						spe["cName"] = cName;
						row["spec"].push(spe);
					}
					row["specLen"] = specLen;
					tData.push(row);
				}
				//获取rowspan
				for (let k = 0, len3 = tData.length; k < len3; k++) {
					let rowspan = 1;
					for (let k1 = k + 1; k1 < len3; k1++) {
						let kSpecLen = tData[k1]["specLen"] || 1;
						rowspan *= kSpecLen;
					}
					tData[k]["rowspan"] = rowspan;
				}
				return tData;
			},
			//规格条数
			rows: function() {
				if (!this.tableData) {
					return;
				}
				let rows = 1;
				let tableData = this.tableData;
				let len = tableData.length;
				for (let i = 0; i < len; i++) {
					let specLen = tableData[i]["specLen"] || 1;
					rows *= specLen;
				}
				//每条rowspan都为1情况
				if (rows == 1) {
					return tableData[0]["spec"].length; //2
				}
				return rows;
			},
			tableList: function() {
				if (!this.tableData) {
					return;
				}
				let rows = this.rows;
				let tList = [];
				let srcData = this.tableData;
				for (let i = 0; i < rows; i++) {
					let listItem = {};
					//构建动态项
					for (let j = 0; j < srcData.length; j++) {
						//构造第一类目
						let key = srcData[j]["pName"];
						let rowspan = srcData[j]["rowspan"];
						let len = srcData[j]["specLen"];
						if (!len) {
							continue;
						}
						let spec = srcData[j]["spec"];
						let index = parseInt(i / rowspan) % len;
						listItem[key] = spec[index]["cName"];
					}
					listItem["price"] = "";
					listItem["stock_num"] = "";
					if (this.edit_data.list) {
						let e = this.edit_data.list
						//判断 e[i]['price']，如果 e[i]不存在就会报错
						if (e[i]) {
							listItem["price"] = e[i]['price'];
							listItem["stock_num"] = e[i]['stock_num'];
						}
					}
					tList.push(listItem);
				}
				return tList;
			}
		},
		methods: { 
			delA(index){
				this.img_list.splice(index,1)
			},
			get_delivery() {
				this.http.get('delivery /admin/get_delivery').then(res => {
					this.delivery = res.data
				})
			},
			//规格
			choose_pic(index) {
				this.drawer = true
				this.xb = index 
				this.get_img = this.setlist			
			}, 
			setlist(e){
				console.log('执行setlist',e)
				console.log(this.xb)  
				this.drawer = false
				this.$set(this.attrs[0].spec[this.xb],'pic',e[0].url)
				this.img_ids[this.xb]=e[0].id
				console.log('spec',this.attrs[0].spec)
			},
			
			
			//规格图片下标
			sku_img_idfun(index) {
				this.sku_img_index = index
				this.imageUrl.splice(index, 1, '');
				this.img_ids.splice(index, 1, 0);
			},
			//规格图片上传成功
			handleAvatarSuccess(res, file) {
				let index = this.sku_img_index
				console.log('sku_index', index)
				let img = URL.createObjectURL(file.raw);
				let id = res.id
				this.imageUrl.splice(index, 1, img);
				this.img_ids.splice(index, 1, id);
			},
			//获取修改数据
			edit_old(res) {
				console.log('tree:',res.tree[0])
				for (let [k, v] of Object.entries(res.tree)) {
					let arr = [];
					for (let [x, y] of Object.entries(v.v)) { 
						arr[x] = {};
						arr[x]["cName"] = y.name;
						if (k == 0) {
							if (y.img_id) {
								arr[x]["pic"] = y.imgUrl;
								this.imageUrl.splice(x, 1, y.imgUrl);
								this.img_ids.splice(x, 1, y.img_id);
								this.checked = true
							} else { 
								this.checked = false
							}
						}
					}
					let obj = {
						pName: v.k,
						rowspan: 1,
						spec: arr
					};
					console.log('sku_img',arr)
					if (this.attrs.length >= 3) {
						return;
					}
					this.attrs.push(obj);
				}
			},
			//新增规格名称
			addItem: function() {
				let obj = {
					pName: "",
					rowspan: 1,
					spec: [{
						cName: ""
					}]
				};
				if (this.attrs.length >= 3) {
					return;
				}
				this.attrs.push(obj);
			},
			toDelete: function(index) {
				this.img_list = []
				this.attrs.splice(index, 1);
			},
			//新增规格参数
			add_canshu(index) {
				this.attrs[index].spec.push({
					cName: ""
				});
			},
			//删除规格参数
			del_canshu(index, index2) {
				this.attrs[index].spec.splice(index2, 1);
			},
			handleClose(tag) {
				this.dynamicTags.splice(this.dynamicTags.indexOf(tag), 1);
			},
		}
	};
</script>

<style lang="css">
	#sku_tag {
		width: 60%;
	}
	.spgg_r_04 {
		padding-left: 20px;
	}

	.spgg_r_03 {
		display: flex;
	}

	.spgg_r_03_01 {
		padding: 10px 30px 0 0;
	}

	.avatar-uploader .el-upload {
		border: 1px dashed #d9d9d9;
		border-radius: 6px;
		cursor: pointer;
		position: relative;
		overflow: hidden;
	}

	.avatar-uploader .el-upload:hover {
		border-color: #409EFF;
	}

	.avatar-uploader-icon {
		font-size: 28px;
		color: #8c939d;
		width: 118px;
		height: 118px;
		line-height: 118px;
		text-align: center;
	}

	.avatar {
		width: 118px;
		height: 118px;
		display: block;
	}

	#sku_tag th {
		height: 40px;
		line-height: 40px;
		text-indent: 10px;
	}

	.el-tag+.el-tag {
		margin-left: 10px;
	}

	.button-new-tag {
		margin-left: 10px;
		height: 32px;
		line-height: 30px;
		padding-top: 0;
		padding-bottom: 0;
	}

	.input-new-tag {
		width: 90px;
		margin-left: 10px;
		vertical-align: bottom;
	}

	/**reset*/
	button,
	input {
		-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
		outline: none;
	}

	.btn {
		padding: 4px 12px;
		margin-bottom: 0;
		font-size: 14px;
		color: #333;
		vertical-align: middle;
		cursor: pointer;
		background-color: #f8f8f8;
		border: 1px solid #ddd;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
	}

	.btn.active,
	.btn:active,
	.btn:focus,
	.btn:hover {
		text-decoration: none;
		color: #333;
		background-color: #fcfcfc;
		border-color: #ccc;
	}

	/*table*/
	.form-item {
		padding: 5px 0;
	}

	table {
		border: 0;
	}

	table.table-sku {
		width: 100%;
		background-color: #fff;
		text-align: left;
	}

	table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	table.table-sku td {
		border: 1px solid #e5e5e5;
		padding: 3px 10px;
	}

	table.table-sku td input {
		padding: 3px 10px;
		border: 1px solid #ccc;
	}

	/**/
	.form-title {
		background: #f8f8f8;
		padding: 5px 10px;
		position: relative;
	}

	.form-title .label {
		color: #999;
	}

	.form-title .delete {
		width: 20px;
		height: 20px;
		line-height: 20px;
		border: 1px solid #ccc;
		border-radius: 50%;
		position: absolute;
		right: 15px;
		top: 50%;
		margin-top: -10px;
		text-align: center;
		color: #fff;
		background: #ccc;
		cursor: pointer;
	}

	.form-title input {
		background: #fff;
		border: 1px solid #ccc;
		padding: 10px;
	}

	.form-list {
		padding: 5px 10px;
		margin-top: 0;display: flex;
	}

	.form-list li {
		/*display: inline-block;*/
		margin-top: 10px;list-style:none
	}

	.spec-item {
		width: 100px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.form-list,
	.form-title {
		text-align: left;
	}

	.form-list input {
		background: #fff;
		margin-right: 10px;
		border: 1px solid #ccc;
		padding: 10px;
	}

	.form-group {
		border: 1px solid #ccc;
		padding: 10px;
	}

	.form-table {
		margin-top: 20px;
	}

	.form-table input{
		height:30px;
		line-height:30px;
	}
	.form-btn-group {
		margin-top: 10px;
		background: #f8f8f8;
		padding: 10px;
	}

	.stock-title,
	.form-h {
		height: 40px;
		line-height: 40px;
	}

	.spgg_r_02 {
		padding: 15px 10px;
		display: flex;
		line-height: 28px;
	}

	.spgg_r_02_2 {
		margin-left: 10px;
		position: relative;
		width: 100px;
	}

	.in_clo {
		position: absolute;
		top: -13px;
		right: -5px;
	}

	.sku-name {
		width: 200px;
		padding: 3px 10px;
	}

	.el-input,
	.el-input__inner {
		height: 34px;
		line-height: 34px;
	}
	
	.sku-img{
		margin-top: 45%; height: 28px; width: 28px;
	}
</style>
