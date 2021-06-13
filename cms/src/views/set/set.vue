<template>
	<div>
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<el-row >
						<transition appear appear-active-class="animated fadeInLeft">
							<div class="set">
								<el-tabs v-model="activeName">
									<el-tab-pane label="基础设置" name="first">
										<set-a :datas="abc"></set-a>
									</el-tab-pane>
									<el-tab-pane label="支付设置" name="second">
										<set-b></set-b>
									</el-tab-pane>
								</el-tabs>
							</div>
						</transition>
					</el-row>
				</el-main>
			</el-container>
		</el-container>

	</div>

</template>

<script>
	import SetA from './Set-a.vue'
	import SetB from './Set-b.vue'
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		name: 'Set',
		data() {
			return {
				activeName: 'first',
				abc: [],
				auth: false
			}
		},
		components: {
			SetA,
			SetB,
			NavTo,
			Header
		},
		methods: {
			check_auth(name) {
				const auth = localStorage.getItem("oauth");
				if (auth.indexOf(name) < 0) {
					this.$message({
						message: '无权限',
						type: 'none'
					});
				} else {
					this.auth = true
				}
			}
		},
		mounted() {
			// this.check_auth('site_set')
		}
	}
</script>

<style lang="less" scoped="">
	.set {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}
</style>
