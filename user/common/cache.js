var time = Date.parse(new Date()) / 1000

export default {
	
	_set_home_cache(res){  
		console.log('11')
		console.log(res[0])
		
		let arr={}
		arr['data'] = res
		arr['cache_time']=time
		uni.setStorageSync('home', arr);//放入缓存
		console.log('set')
	}
}  