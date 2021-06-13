<template>
	 <view class="imageUploadContainer">
		<view class="imageUploadList"> 
			<view class="imageItem" v-bind:key="index" v-for="(path,index) in value">   
				<image :src="path.url" :class="{'dragging':isDragging(index)}" draggable="true" @tap="previewImage" :data-index="index" @touchstart="start" @touchmove="move" @touchend="stop"></image>
				<view v-if="isShowDel" class="imageDel" @tap="deleteImage" :data-index="index">x</view> 						
			</view>
			<view v-if="isShowAdd" class="imageUpload" @tap="selectImage">+</view>
		</view>
		<image v-if="showMoveImage" class="moveImage" :style="{left:posMoveImageLeft, top:posMoveImageTop}" :src="moveImagePath"></image> 
	</view> 
</template>

<script>
	var _self;	
	export default {
		name:'robby-image-upload',
		props: ['value','enableDel','enableAdd','enableDrag','serverUrl','formData','limit','fileKeyName','showUploadProgress','serverUrlDeleteImage'],
		data() {
			return {
				role_id:'',
				edit_box:false,
				e_data:'',
				imageBasePos:{
					x0: -1,
					y0: -1,
					w:-1,
					h:-1,
				},
				showMoveImage: false,
				moveImagePath: '',
				moveLeft: 0,
				moveTop: 0,
				deltaLeft: 0,
				deltaTop: 0,
				dragIndex: null,
				targetImageIndex: null
			}
		},  
		computed:{
			posMoveImageLeft: function(){ 
				return this.moveLeft + 'px'
			},
			posMoveImageTop: function(){
				return this.moveTop + 'px'
			},
			isShowDel: function(){
				if(this.enableDel === false){
					return false
				}else{
					return true
				}
			},
			isShowAdd: function(){
				if(this.enableAdd === false){
					return false
				}
				
				if(this.limit && this.value.length >= this.limit){
					return false
				}
				
				return true
			},
			isDragable: function(){
				if(this.enableDrag === false){
					return false
				}else{
					return true
				}
			}
		}, 
		methods:{
			sub(){ 
				this.$emit('sub')				
			}, 
			selectImage: function(){
				_self = this
				if(!_self.value){
					_self.value = []
				} 
				
				uni.chooseImage({
					count: _self.limit ? (_self.limit - _self.value.length) : 999,
					success: function(e){
						var imagePathArr = e.tempFilePaths
						
						//如果设置了limit限制，在web上count参数无效，这里做判断控制选择的数量是否合要求
						//在非微信小程序里，虽然可以选多张，但选择的结果会被截掉
						//在app里，会自动做选择数量的限制
						if(_self.limit){
							var availableImageNumber = _self.limit - _self.value.length
							if(availableImageNumber < imagePathArr.length){
								uni.showToast({
									title: '图片总数限制为'+_self.limit+'张，当前还可以选'+availableImageNumber+'张',
									icon:'none',
									mask: false,
									duration: 2000
								});
								return
							}
						}
						
						console.log('s1:',imagePathArr)
						let arrs=[]
						for(let i=0; i<imagePathArr.length;i++){
							arrs.push(imagePathArr[i])
						}
						 
						//检查服务器地址是否设置，设置即表示图片要上传到服务器
						if(_self.serverUrl){
							
							var remoteIndexStart = _self.value.length - imagePathArr.length
							var promiseWorkList = []
							var keyname = (_self.fileKeyName ? _self.fileKeyName : 'upload-images')
							var completeImages = 0
							
							for(let i=0; i<imagePathArr.length;i++){
								promiseWorkList.push(new Promise((resolve, reject)=>{
									let remoteUrlIndex = remoteIndexStart + i
									uni.uploadFile({
										url:_self.serverUrl,
										fileType: 'image',
										formData:_self.formData,
										filePath: imagePathArr[i],
										header:{
											token:uni.getStorageSync("token")
										},
										name: keyname,
										success: function(res){
											if(res.statusCode === 200){
												//_self.value[remoteUrlIndex] = JSON.parse(res.data)  
												_self.value.push(JSON.parse(res.data))
												  
												 
												console.log('s3:',_self.value)
												completeImages ++
												
												if(_self.showUploadProgress){
													uni.showToast({
														title: '上传进度：' + completeImages + '/' + imagePathArr.length,
														icon: 'none',
														mask: false,
														duration: 1000
													});
												}
												console.log('success to upload image: ' + res.data)
												resolve('success to upload image:' + remoteUrlIndex)
											}else{
												console.log('fail to upload image:'+res.data)
												reject('failt to upload image:' + remoteUrlIndex)
											}
										},
										fail: function(res){
											console.log('fail to upload image:'+res)
											reject('failt to upload image:' + remoteUrlIndex)
										}
									})
								}))
							}
							Promise.all(promiseWorkList).then((result)=>{
								console.log('s4:',_self.value)
								_self.$emit('add', {
									currentImages: imagePathArr,
									allImages: _self.value	//id+url
								})
								_self.$emit('input', _self.value)
							})
						}else{
							_self.$emit('add', {
								currentImages: imagePathArr,
								allImages: _self.value	//id+url
							})
							_self.$emit('input', _self.value)
						}
					}
				})
			},
			deleteImage: function(e){ 
				const that=this
				uni.showModal({
					title: '提示',
					content: '确定删除？',
					success: function (res) {
						if (res.confirm) {
							var imageIndex = e.currentTarget.dataset.index
							var deletedImagePath = that.value[imageIndex]
							that.value.splice(imageIndex, 1) 
							that.del(deletedImagePath)	
						}  
					}
				});							
			}, 
			del(deletedImagePath){ 				
				let id=deletedImagePath.id
				//检查删除图片的服务器地址是否设置，如果设置则调用API，在服务器端删除该图片
				if(this.serverUrlDeleteImage){
					uni.request({
						url: this.serverUrlDeleteImage,
						method: 'POST',
						header:{
							token:uni.getStorageSync("token")
						},
						data: {
							id
						},
						success: res => {
							console.log(res.data)
						}
					});
				}				
				this.$emit('delete',{
					currentImage: deletedImagePath,
					allImages: this.value
				})
				this.$emit('input', this.value)
			}, 
			previewImage: function(e){
				var imageIndex = e.currentTarget.dataset.index
				uni.previewImage({
					current: this.value[imageIndex],
					indicator: "number",
					loop: "true",
					urls:this.value
				})
			},
			initImageBasePos: function(){
				let paddingRate = 0.024
				_self = this
				//计算图片基准位置
				uni.getSystemInfo({
					success: function(obj) {
						let screenWidth = obj.screenWidth
						let leftPadding = Math.ceil(paddingRate * screenWidth)
						let imageWidth = Math.ceil((screenWidth - 2*leftPadding)/4)
						
						_self.imageBasePos.x0 = leftPadding
						_self.imageBasePos.w = imageWidth
						_self.imageBasePos.h = imageWidth
					}
				})
			},
			findOverlapImage: function(posX, posY){
				let rows = Math.floor((posX-this.imageBasePos.x0)/this.imageBasePos.w)
				let cols = Math.floor((posY-this.imageBasePos.y0)/this.imageBasePos.h)
				let indx = cols*4 + rows
				return indx
			},
			isDragging: function(indx){
				return this.dragIndex === indx
			},
			start: function(e){
				if(!this.isDragable){
					return
				}
				this.dragIndex = e.currentTarget.dataset.index
				this.moveImagePath = this.value[this.dragIndex]
				this.showMoveImage = true
				
				//计算纵向图片基准位置
				if(this.imageBasePos.y0 === -1){
					this.initImageBasePos()
					
					let basePosY = Math.floor(this.dragIndex / 4) * this.imageBasePos.h
					let currentImageOffsetTop = e.currentTarget.offsetTop
					this.imageBasePos.y0 = currentImageOffsetTop - basePosY
				}
				
				//设置选中图片当前左上角的坐标
				this.moveLeft = e.target.offsetLeft
				this.moveTop = e.target.offsetTop
			},
			move: function(e){ 
				if(!this.isDragable){
					return
				}
				const touch = e.touches[0]
				this.targetImageIndex = this.findOverlapImage(touch.clientX, touch.clientY)
				
				//初始化deltaLeft/deltaTop
				if(this.deltaLeft === 0){
					this.deltaLeft = touch.clientX - this.moveLeft
					this.deltaTop = touch.clientY - this.moveTop 
				}
				
				//设置移动图片位置
				this.moveLeft = touch.clientX - this.deltaLeft
				this.moveTop = touch.clientY - this.deltaTop
			},
			stop: function(e){
				if(!this.isDragable){
					return
				}
				if(this.dragIndex !== null && this.targetImageIndex !== null){
					if(this.targetImageIndex<0){
						this.targetImageIndex = 0
					}
				
					if(this.targetImageIndex>=this.value.length){
						this.targetImageIndex = this.value.length-1
					}
					//交换图片
					if(this.dragIndex !== this.targetImageIndex){
						this.value[this.dragIndex] = this.value[this.targetImageIndex]
						this.value[this.targetImageIndex] = this.moveImagePath
					}
				}
				
				this.dragIndex = null
				this.targetImageIndex = null
				this.deltaLeft = 0
				this.deltaTop = 0
				this.showMoveImage = false 
				this.$emit('move', this.value)
			}
		}
	}
</script>

<style>
	.imageUploadContainer{
		padding: 10upx 5upx;
		margin: 10upx 5upx;
	}
	.name{
		text-align: center;
		margin-top:-30upx;
	}
	.dragging{
		transform: scale(1.2)
	}
	
	.imageUploadList{
		display: flex;
		flex-wrap: wrap;
	}
	
	.imageItem, .imageUpload{
		width: 200upx;
		height: 200upx;
		margin: 30upx 15upx 30upx;
	}
	
	.imageDel{
		position: relative;
		left: 180upx;
		bottom: 225upx;
		background-color: rgba(0,0,0,0.5);
		width: 36upx;
		text-align: center;
		line-height: 35upx;
		border-radius: 17upx;
		color: white;
		font-size: 30upx;
		padding-bottom: 2upx;
	}
	
	.imageItem image, .moveImage{
		width: 200upx;
		height: 200upx;
		border-radius: 8upx;
	}
	
	.imageUpload{
		line-height: 200upx;
		text-align: center;
		font-size: 150upx;
		color: #D9D9D9;
		border: 1px solid #D9D9D9;
		border-radius: 8upx;
	}
	
	.moveImage{
		position: absolute;
	}
	.uni-list {
		width: 260px;
	}	
	.popup_foot {
		display: flex;
		border-top: 1px #F9F9F9 solid;
		line-height: 40px;
		height: 40px;
		margin-top: 30px;
	}
	.build_01{
		line-height: 35px;
	}
	.build_01 input{
		background: #ededed;
	}
	.popup_foot_l {
		width: 50%;
		text-align: center;
	}
	
	.popup_foot_r {
		width: 50%;
		text-align: center;
		border-left: 1px #F9F9F9 solid;
		color: #5095D3;
	}
</style>