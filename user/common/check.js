import {
	Api_url
} from '@/common/config.js'
var x = 0
export default {
	GetUrlParame(parameName) {
		/// 获取地址栏指定参数的值
		/// <param name="parameName">参数名</param>
		// 获取url中跟在问号后面的部分
		var parames = window.location.search
		// 检测参数是否存在
		if (parames.indexOf(parameName) > -1) {
			var parameValue = ''
			parameValue = parames.substring(parames.indexOf(parameName), parames.length)
			// 检测后面是否还有参数
			if (parameValue.indexOf('&') > -1) {
				// 去除后面多余的参数, 得到最终 parameName=parameValue 形式的值
				parameValue = parameValue.substring(0, parameValue.indexOf('&'))
				// 去掉参数名, 得到最终纯值字符串
				parameValue = parameValue.replace(parameName + '=', '')
				return parameValue
			}
			return ''
		}
	},
	check_login_xcx() {
		let token = uni.getStorageSync('token')
		var that = this; 
		return uni.request({
			url: Api_url + 'auth/token_verify',
			method: 'POST',
			data: {
				token: token
			},
			success: function(res) {
				var valid = res.data.isValid;
				console.log(res)
				if (!valid || res.data.statusCode == 401) {
					console.log('token不存在')
					x = 0
				} else {
					x = 1
				}
				console.log(x)
				return x
				
			}
		})
		

	},
	
	check_login_h5(e=''){
			let type=''
			if(e=='userinfo'){ 
				type='userinfo'
			}  
			const code = this.GetUrlParame('code') // 截取code  
			var domine = window.location.href.split("#")[0]; // 微信会自动识别#    并且清除#后面的内容 
			var domine = domine.split("?code")[0]; 
			if (!code) {
				console.log('获取code')		
				var token = uni.getStorageSync('token'); //获取缓存
				// var token = 'db6e572e7ac70c20b4ec91cabaaa' //获取缓存
				if (!token) { 
					console.log('token不存在')
					return x = 0
				}else{
					return x = 1
				}
				
			} 
	},
	
	judge_gl() {
		//1 微信    2 微信+手机    3 手机
		const swtich=uni.getStorageSync('switch')
		console.log("xxa:",swtich)
		let gl = swtich.merge_mode
		if (gl == 1) {
			uni.navigateTo({
				url: '/pages/login/login'
			})
		} else if (gl == 2) {
			uni.navigateTo({
				url: '/pages/login/loginA/loginA'
			})
		} else if (gl == 3) {
			uni.navigateTo({
				url: '/pages/login/loginB/loginB'
			}) 
		}

	},

	async a() {
		const that = this
		
		// #ifdef MP-WEIXIN
			let is_login =await that.check_login_xcx()
			console.log('xcx', is_login)
		// #endif
		
		// #ifdef H5
			let is_login =await that.check_login_h5()
			console.log('h5', is_login)
		// #endif
		
		if (is_login == 0) {
			that.judge_gl()
			return false
		}
		if (is_login == 1) {
			return true
		}
	}
}
