<template>
	<div id="login" v-if="show">
		<div class="login_wrap">

			<div class="wenzi">商城管理后台</div>
			<div id="darkbannerwrap"></div>
			<form method="post">
				<div class="put"><i class="el-icon-news"></i><input v-model="user" placeholder="用户名"></div>
				<div class="put"><i class="el-icon-edit"></i><input v-model="password" placeholder="密码" type="password"
					v-on:keyup.enter="sub(password)"></div>
				<div>
					<el-row>
						<el-button type="primary" class="btn" @click="sub(password)">登录</el-button>
					</el-row>
				</div>
			</form>

		</div>
	</div>
</template>

<script>
	export default {
		name: 'Login',
		data() {
			return {
				user: "",
				password: "",
				show: true
			}
		},
		methods: {
			sub(psw) {
				console.log(psw)
				var that = this;
				const username = this.user
				this.http.post_show('index/admin/login', {
						username: username,
						psw: psw
					})
					.then((res) => {
						if (res.status == 200) {
							localStorage.setItem("token", res.data.token);
							localStorage.setItem("oauth", res.data.oauth);
							localStorage.setItem("admin_name", that.user);
							that.$message({
								showClose: true,
								message: '登陆成功',
								type: 'success'
							});
							this.$router.push({
								path: '/'
							});
						}
						if (res.status == 400) {
							this.$message.error(res.msg);
							return
						}


					});
			},
			login() {
				const token = localStorage.getItem("token");
				if (token) {
					this.$router.push({
						path: '/'
					});
				} else {
					return
					this.$router.push({
						path: '/login'
					});
				}
			}
		},
		mounted() {
			this.login()
			const login = true;
			const psw = '3310e6953f221320ec02c5f8b17092df';
			//const login=sessionStorage.getItem('qy_login')       
			//const psw= sessionStorage.getItem('qy_psw')   
			if (!login || !psw) {
				let ht = document.location.protocol;
				let host = document.location.host
				let ww = ht + '//' + host;
				window.location.href = ww;
			}
			// if(!this.$route.query.ucid){ 
			//   alert('缺id')
			//   return;
			// }
			// this.sub(psw) 
		}

	}
</script>

<style lang="less">
	#login {
		padding: 0px;
		margin: 0px;
		position: absolute;
		top: 0px;
		left: 0px;
		width: 100%;
		height: 100%;
		border: hidden;
	}

	#login {
		background: url(../../../public/image/login.jpg) repeat left center;
		background-size: cover;
	}

	.login_wrap {
		margin: 180px auto 0 auto;
		min-height: 420px;
		max-width: 420px;
		padding: 40px;
		margin-left: auto;
		margin-right: auto;
		border-radius: 4px;
		box-sizing: border-box;
		text-align: center;

		.wenzi {
			width: 400px;
			text-align: center;
			font-size: 32px;
			color: #fff;
			letter-spacing: 3px;
		}

		.put {
			color: #fff;
			padding: 5px 0;
			width: 280px;
			margin-top: 30px;
			border: 2px solid #e0e0e0;
			border-radius: 20px;
			margin: 30px 0 20px 50px;
			font-size: 24px;
		}

		.put input {
			background: none;
			text-indent: 20px;
			outline: none;
			border: 0px;
			color: #fff;
			line-height: 40px;
			width: 80%;
			font-size: 16px;
		}

		.btn {
			width: 200px;
			margin-left: 40px;
		}
	}
</style>
