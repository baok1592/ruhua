import {Api_url} from './config'
import http from './axios.js'

class AppToken {
	constructor() {
		this.tokenUrl = Api_url + 'auth/get_app_token';
		this.verifyUrl = Api_url + 'auth/token_verify'; 
	}

	//初始化登陆
	async verify() { 
		const ms=await http.get("index/user/sys_config").then(res=>{
			let arr = {}
			for (let k in res.data) { 
				let v = res.data[k]
				arr[v.key] = v.value
			} 
			return arr.merge_mode
		})
		if(ms==3){
			console.log("手机登陆模式-暂不登陆")
			return;	//手机登陆不属于初始化登陆
		} 
		
		var that = this;
		var token = uni.getStorageSync('token'); //获取缓存
		if (!token) {
			console.log('APP获取token:')
			//向微信api拿openid和unionid,并返回Token
			this.getTokenFromServer();
		} else {
			console.log('APP缓存token')
			this._veirfyFromServer(token); //验证token是否过期，过期调用.getTokenFromServer函数获取
		}
	}
	//验证token
	_veirfyFromServer(token) {
		var that = this;
		uni.request({
			url: that.verifyUrl,
			method: 'POST',
			data: {
				token: token
			},
			success: function(res) {
				var valid = res.data.isValid;
				if (!valid) {
					that.getTokenFromServer();
				}
			}
		})
	}
	////向微信api拿openid和unionid,并返回Token
	getTokenFromServer() {
		var that = this; 
		uni.login({
			provider: 'weixin',
			success: function(res) {
				console.log('app_wx::',res)
				uni.request({
					url: that.tokenUrl,
					method: 'POST',
					data: res,
					success: function(rest) { 
						console.log('token::',rest)
						uni.setStorageSync('token', rest.data.token);
					}
				})
			}
		})
	}
}
export {
	AppToken
};


