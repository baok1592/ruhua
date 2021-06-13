<template>
	<div class="root">
		<el-menu :default-active="act" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" background-color="#545c64"
		text-color="#fff" active-text-color="#ffd04b" @select="handleSelect"> 
			<el-menu-item index="4">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="1" @click="jump_tohome">
				<span slot="title">首页</span>
			</el-menu-item>
			<el-submenu index="3">
				<template slot="title">
					<span>商品</span>
				</template>
				<el-menu-item-group>
					<el-menu-item unique-opened="true" index="3-1" @click="jump_toproduct">
						<span slot="title" style="margin-left: 40px;">商品列表</span>
					</el-menu-item>
					<el-menu-item index="3-2" @click="jump_cate">
						<span slot="title" style="margin-left: 40px;">商品分类</span>
					</el-menu-item>
					
				</el-menu-item-group>
			</el-submenu>

			<el-submenu index="4">
				<template slot="title">
					<span>订单</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="4-1" @click="jump_toorder">
						<span slot="title" style="margin-left: 40px;">订单</span>
					</el-menu-item>
					<el-menu-item index="4-2" @click="jump_evaluate">
						<span slot="title" style="margin-left: 40px;">评价</span>
					</el-menu-item>
					<el-menu-item index="4-3" @click="jump_reseller_order">
						<span slot="title" style="margin-left: 40px;">分销订单</span>
					</el-menu-item>
				</el-menu-item-group>
			</el-submenu>
			<!-- <el-menu-item index="9" @click="jump_touser">
				<span slot="title">用户</span>
			</el-menu-item> -->
			
			<el-submenu index="9">
				<template slot="title">
					<span>用户</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="9-1" @click="jump_touser">
						<span slot="title" style="margin-left: 40px;">用户列表</span>
					</el-menu-item>
					<!-- <el-menu-item index="9-2" @click="jump_topoint">
						<span slot="title" style="margin-left: 40px;">积分</span>
					</el-menu-item> -->
				</el-menu-item-group>
			</el-submenu>
			
			
			
			<el-submenu index="5">
				<template slot="title">
					<span>财务</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="5-1" @click="jump_tomoney">
						<span slot="title" style="margin-left: 40px;">退款</span>
					</el-menu-item>
					<el-menu-item index="5-2" @click="jump_totixian">
						<span slot="title" style="margin-left: 40px;">提现</span>
					</el-menu-item>
				</el-menu-item-group>
			</el-submenu>
			
			<el-submenu index="7">
				<template slot="title">
					<span>统计</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="7-1" @click="jump_data">
						<span slot="title" style="margin-left: 40px;">数据</span>
					</el-menu-item>
					<el-menu-item index="7-2" @click="jump_reseller">
						<span slot="title" style="margin-left: 40px;">分销商统计</span>
					</el-menu-item>
					<el-menu-item index="7-3" @click="jump_resellertj">
						<span slot="title" style="margin-left: 40px;">分销商</span>
					</el-menu-item>
					<el-menu-item index="7-4" @click="jump_yj">
						<span slot="title" style="margin-left: 40px;">佣金记录</span>
					</el-menu-item>
					
				</el-menu-item-group>
			</el-submenu>
			
			
			<!-- <el-menu-item index="7" @click="jump_todata">
				<span slot="title">统计</span>
			</el-menu-item> -->
			<el-submenu index="2">
				<template slot="title">
					<span>内容</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="2-1" @click="jump_toad">
						<span slot="title" style="margin-left: 40px;">广告</span>
					</el-menu-item>
					<el-menu-item index="2-2" @click="jump_toarticle">
						<span slot="title" style="margin-left: 40px;">文章/表单</span>
					</el-menu-item>
					<el-menu-item index="2-3" @click="jump_tonav">
						<span slot="title" style="margin-left: 40px;">导航设置</span>
					</el-menu-item>
					<el-menu-item index="2-4" @click="jump_tohot">
						<span slot="title" style="margin-left: 40px;">热门搜索</span>
					</el-menu-item>
				</el-menu-item-group>
			</el-submenu>
			<el-submenu index="8">
				<template slot="title">
					<span>应用</span>
				</template>
				<el-menu-item-group>

					<el-menu-item index="8-1" @click="jump_tocoupon">
						<span slot="title" style="margin-left: 40px;">优惠券</span>
					</el-menu-item>
					<el-menu-item index="8-2" @click="jump_tovip_tc">
						<span slot="title" style="margin-left: 40px;">会员套餐</span>
					</el-menu-item>
					<el-menu-item index="8-3" @click="jump_todiscount">
						<span slot="title" style="margin-left: 40px;">限时折扣</span>
					</el-menu-item>
					<el-menu-item index="8-4" @click="jump_toreseller">
						<span slot="title" style="margin-left: 40px;">分销商品</span>
					</el-menu-item>
					<el-menu-item index="8-5" @click="jump_template">
						<span slot="title" style="margin-left: 40px;">运费模板</span>
					</el-menu-item>
					<el-menu-item index="8-6" @click="jump_pt">
						<span slot="title" style="margin-left: 40px;">拼团活动</span>
					</el-menu-item>
				</el-menu-item-group>
			</el-submenu>
			<el-submenu index="10">
				<template slot="title">
					<span>设置</span>
				</template>
				<el-menu-item-group>
					<el-menu-item index="10-1" @click="jump_toset">
						<span slot="title" style="margin-left: 40px;">站点设置</span>
					</el-menu-item>
					<el-menu-item index="10-2" @click="jump_backup">
						<span slot="title" style="margin-left: 40px;">备份</span>
					</el-menu-item>
					<el-menu-item index="10-3" @click="jump_toadmin">
						<span slot="title" style="margin-left: 40px;">管理员</span>
					</el-menu-item>
					<el-menu-item index="10-4" @click="jump_upgrade">
						<span slot="title" style="margin-left: 40px;">检测版本</span>
					</el-menu-item> 
				</el-menu-item-group>
			</el-submenu>
			<!-- <el-menu-item index="6" @click="logout">
				<span slot="title">退出</span>
			</el-menu-item> -->
			<el-menu-item index="11" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="111" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="411" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="4111" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="42" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="43" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="44" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="45" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="46" disabled style="cursor:default">
				<span slot="title"></span>
			</el-menu-item>

		</el-menu>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				act:'',
				activeIndex: '1',
				activeIndex2: '1',
				status: 0,
				msg: '确定退出？'
			}
			props:['act']
		},
		mounted(){
			this.act=localStorage.getItem("act");
		},
		methods: {
			logout() {
				this.$confirm('此操作将退出管理系统, 是否继续?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					localStorage.clear(); //清除所有缓存
					this.$router.push({
						path: '/',
					})
					this.$message({
						type: 'success',
						message: '退出成功!'
					});
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消'
					});
				});
			},
			handleOpen(key, keyPath) {
				console.log('handleOpen:',key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			},
			handleSelect(key, keyPath) {
				console.log('handleSelect:',key, keyPath);
				this.act=key
				localStorage.setItem("act",key);
			},
			jump_totixian(){
				this.$router.push({
					path: '/tixian/tixian'
				})
			},
			jump_resellertj(){
				this.$router.push({
					path: '/resellertj/resellertj'
				})
			},
			jump_yj(){
				this.$router.push({
					path: '/yj/yj'
				})
			},
			jump_toproduct() {
				this.$router.push({
					path: '/product/new_product'
				})
			},
			jump_toadmin(){
				this.$router.push({
					path:'/user/admin'
				})
			},
			jump_template(){
				this.$router.push({
					path: '/product/template'
				})
			},
			jump_evaluate(){
				this.$router.push({
					path: '/order/evaluate'
				})
			},
			jump_tohot(){
				this.$router.push({
					path: '/ad/hot'
				})
			},
			jump_tonav(){
				this.$router.push({
					path: '/ad/nav'
				})
			},
			jump_backup(){
				this.$router.push({
					path: '/backup/backup'
				})
			},
			jump_upgrade(){
				this.$router.push({
					path: '/set/upgrade'
				})
			},
			jump_data(){
				this.$router.push({
					path: '/data/data'
				})
			},
			jump_reseller(){
				this.$router.push({
					path: '/data/reseller'
				})
			},
			jump_reseller_order(){
				this.$router.push({
					path: '/order/reseller_order'
				})
			},
			jump_good(){
				this.$router.push({
					path: '/good'
				})
			},
			jump_todiscount(){
				this.$router.push({
					path: '/extend/discount'
				})
			},
			jump_pt(){
				this.$router.push({
					path: '/extend/pt'
				})
			},
			jump_topoint(){
				this.$router.push({
					path: '/user/point'
				})
			},
			jump_toreseller(){
				this.$router.push({
					path: '/extend/reseller'
				})
			},
			jump_cate(){
				this.$router.push({
					path: '/product/category'
				})
			},
			jump_tohome() {
				this.$router.push({
					path: '/'
				})
			},
			jump_toorder(){
				this.$router.push({
					path: '/order/order'
				})
			},
			jump_touser(){
				this.$router.push({
					path: '/user'
				})
			},
			jump_tomoney(){
				this.$router.push({
					path: '/money'
				})
			},
			jump_todata(){
				this.$router.push({
					path: '/data'
				})
			},
			jump_toad(){
				this.$router.push({
					path: '/ad'
				})
			},
			jump_toarticle(){
				this.$router.push({
					path: '/article'
				})
			},
			jump_toextend(){
				this.$router.push({
					path: '/extend'
				})
			},
			jump_tocoupon(){
				this.$router.push({
					path: '/coupon'
				})
			},
			jump_tovip_tc(){
				this.$router.push({
					path: '/vip_tc/vip_tc'
				})
			},
			jump_toset(){
				this.$router.push({
					path: '/set'
				})
			}
		}
	}
</script>

<style>
</style>
