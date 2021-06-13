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
					<el-tree :data="data" show-checkbox default-expand-all node-key="id" ref="tree" highlight-current :props="defaultProps">
					</el-tree>

					<div class="buttons">
						<el-button @click="getCheckedNodes">通过 node 获取</el-button>
						<el-button @click="getCheckedKeys">通过 key 获取</el-button>
						<el-button @click="setCheckedNodes">通过 node 设置</el-button>
						<el-button @click="setCheckedKeys">通过 key 设置</el-button>
						<el-button @click="resetChecked">清空</el-button>
					</div>
				</el-main>
			</el-container>
		</el-container>
		<!-- 添加弹出框 -->
		<el-dialog title="选择配送区域" :visible.sync="dialogVisibleadd" width="30%">
			<el-tree :data="data" show-checkbox node-key="id" ref="tree" highlight-current :props="defaultProps" @check-change="handleCheckChange"
			 :accordion="true" :check-on-click-node="true">
			</el-tree>

			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="getCheckedNodes">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
	import City from '@/common/city' //城市
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data() {
			return {
				data: City,
				defaultProps: {
					children: 'children',
					label: 'label'
				}
			}
		},
		components: {
			NavTo,
			Header
		},
		mounted() {
			console.log(this.$route.query.key)
			const site = JSON.parse(localStorage.getItem("edit_data"))
			this.form = site
			this.list_show = site.rule
			this.list = site.rule
			console.log(this.data)
		},
		methods: {
			getCheckedNodes() {
				console.log(this.$refs.tree.getCheckedNodes());
			},
			getCheckedKeys() {
				console.log(this.$refs.tree.getCheckedKeys());
			},
			setCheckedNodes() {
				this.$refs.tree.setCheckedNodes([{
					"id": 1,
					"label": "北京市",
					"children": [{
						"id": 2,
						"label": "北京市"
					}]
				}, {
					"id": 19,
					"label": "天津市",
					"children": [{
						"id": 20,
						"label": "天津市"
					}]
				}]);
			},
			setCheckedKeys() {
				this.$refs.tree.setCheckedKeys([3]);
			},
			resetChecked() {
				this.$refs.tree.setCheckedKeys([]);
			}
		}
	}
</script>

<style lang="less">
	.add {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;

		.add_btn {
			margin-bottom: 10px
		}

		.xiao {
			display: flex;
			width: 100%;

			.xiao_l {
				flex-shrink: 0;
				width: 110px;
				text-align: right;
				font-size: 13px;
				padding-right: 10px;
				color: #606266;
			}

			.zhi {
				margin: 10px 0 30px;
			}
		}
	}
</style>
