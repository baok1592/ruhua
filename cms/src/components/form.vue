<template>
	<el-row>
		<el-form :model="form">
			<el-form-item label="类型" :label-width="formLabelWidth">
				<el-select v-model="form.type" placeholder="请选择类型">
					<el-option v-for="item in options" :key="item.value" :value="item.value" :label="item.label"></el-option>
				</el-select>
			</el-form-item> 
			<el-form-item label="标题" :label-width="formLabelWidth" v-if="form.type == 3">
				<el-input v-model="sub_data.title" auto-complete="off" style="width: 15%;"></el-input>
			</el-form-item>
			<template v-for="(item,index) of form">
				<el-row>
					<el-col :span="4">
						<el-form-item label="名称" :label-width="formLabelWidth">
							<el-input v-model="form[index].desc" auto-complete="off"></el-input>
						</el-form-item>
					</el-col>
					<el-col :span="5">
						<el-form-item label="样式" :label-width="formLabelWidth">
							<el-select v-model="form[index].type" placeholder="请选择类型" @change="select_type(index)">
								<el-option v-for="item in cate" :key="item.value" :value="item.value" :label="item.label"></el-option>
							</el-select>
						</el-form-item>
					</el-col>
					<el-col :span="4">
						<el-form-item label="标识" :label-width="formLabelWidth">
							<el-input v-model="form[index].name" auto-complete="off"></el-input>
						</el-form-item>
					</el-col>
					<el-col :span="5">
						<el-form-item label="提示" :label-width="formLabelWidth" v-if="form[index].show">
							<el-input v-model="form[index].default" auto-complete="off"></el-input>
						</el-form-item>
						<el-form-item label="选项" :label-width="formLabelWidth" v-if="!form[index].show">
							<el-input v-model="form[index].options" auto-complete="off" placeholder="用@@隔开,例:男@@女"></el-input>
						</el-form-item>
					</el-col>
					<el-col :span="5">
						<el-button v-if="index != 0" type="danger" size="small" @click="del(index)" style="margin-left: 30px;margin-top: 5px;">删除</el-button>
					</el-col>
				</el-row>
			</template>
			<el-button type="success" size="small" @click="add">新增</el-button>
		</el-form>
		<div style="display: flex; justify-content: center;">
			<el-button type="primary" size="small" v-if="edit == 1" @click="is_edit">提交修改</el-button>
			<el-button type="primary" size="small" v-else @click="sub">确 定</el-button>
		</div>
	</el-row>
</template>

<script>
	export default {
		data() {
			return {
				sub_data:{
					type:'',
					form:[],
					title:''
				},
				show:true,
				form: [{
						type: '',
						desc: '',
						default: '',
						show:true,
						options:'',
						name:''
					},
					{
						type: '',
						desc: '',
						default: '',
						show:true,
						options:'',
						name:''
					},
					{
						type: '',
						desc: '',
						default: '',
						show:true,
						options:'',
						name:''
					},
				],

				formLabelWidth: "120px",
				options: [{
						value: 1,
						label: "独立"
					},
					{
						value: 2,
						label: "下单购物"
					},
					{
						value: 3,
						label: "个人中心"
					},
				],
				cate: [{
						value: 'input',
						label: "输入框"
					},
					{
						value: 'radio',
						label: "单选"
					},
					{
						value: 'check',
						label: "多选"
					},
					{
						value: 'date',
						label: "日期"
					},
					{
						value: 'time',
						label: "时间"
					},
					{
						value: 'city',
						label: "城市"
					},
					{
						value: 'select',
						label: "下拉"
					},
				]
			}
		},
		props:['edit'],
		components: {

		},
		mounted() {
			if(this.edit == 1){
				const data=JSON.parse(localStorage.getItem("form_data"))
				console.log(data)
				this.form = data.form
				this.form.type = data.type
			}
		},
		methods: {
			is_edit(){
				this.sub_data.form = this.form
				this.sub_data.type = this.form.type
				console.log(this.sub_data)
			},
			sub(){
				this.sub_data.type = this.form.type
				this.sub_data.form = this.form
				console.log(this.form)
				localStorage.setItem("form_data",JSON.stringify(this.sub_data))
				this.http.post_show("article/admin/add_article").then(res => {    //演示用虚拟接口
					
				});
			},
			select_type(index){
				console.log(index)
				console.log(this.form[index])
				if(this.form[index].type == 'radio' || this.form[index].type == 'check' || this.form[index].type == 'select'){
					this.show = false
					this.form[index].show = false
				}
				else{
					this.show = true
					this.form[index].show = true
				}
			},
			add() {
				this.form.push({
					type: '',
					desc: '',
					default: '',
					show:true,
					options:'',
					name:''
				})
			},
			del(index) {
				this.form.splice(index, 1)
			}
		}

	}
</script>

<style>
</style>
