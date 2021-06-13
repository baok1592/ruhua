<template>
	<view class="form">
		<view v-for="(item,index) of data" :key="index">
			<view class="biao" v-if="item.type=='radio'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r">
					<radio-group class="radio-group" name="sex" @change="radioChange">
						<label class="tui-radio">
							<radio value="1" :checked="item.value==1?true:false" style="zoom: 90%;" /> 男<span></span>
						</label>
						<label class="tui-radio">
							<radio value="0" :checked="item.value==0?true:false" style="zoom: 90%;" /> 女
						</label>
					</radio-group>
				</view>
			</view>
			<view class="biao" v-if="item.type=='input'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r">
					<input placeholder-class="phcolor" v-model="arr.name" class="tui-input" placeholder="请输入.." />
				</view>
			</view>
			<view class="biao" v-if="item.type=='textarea'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r">
					<textarea name="msg" v-model="arr.msg" placeholder="请输入.."></textarea>
				</view>
			</view>
			<view class="biao" v-if="item.type=='switch'" style="padding: 3px 10px;">
				<view class="biao-l" style="padding-top: 3px;">{{item.desc}}:</view>
				<view class="biao-r">
					<switch name="switch" checked @change="switch1Change" style="transform:scale(0.7)" />
				</view>
			</view>
			<view class="biao" v-if="item.type=='check'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r">
					<checkbox-group @change="checkboxChange">
						<label class="uni-list-cell uni-list-cell-pd" v-for="it of item.option" :key="it.value">
							<checkbox :value="it.value+''" :checked="it.checked" />{{it.name}}<span></span>
						</label>
					</checkbox-group>
				</view>
			</view>
			<view class="biao" v-if="item.type=='date'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r" @click="show(1)">
					{{result}}
				</view>
			</view>
			<view class="biao" v-if="item.type=='time'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r" @click="show3(3)">
					{{time}}
				</view>
			</view>
			<view class="biao" v-if="item.type=='city'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r" @click="toggleTab">
					{{resultInfo.result}}

				</view>
			</view>
			<view class="biao" v-if="item.type=='country'">
				<view class="biao-l">{{item.desc}}:</view>
				<view class="biao-r">
					<picker @change="bindPickerChange" :value="ind" :range="item.option">
						<view class="uni-input">{{item.option[ind]}}</view>
					</picker>
				</view>
			</view>
		</view>
		<view class="btn">
			<button class="tui-btn tui-btn-block tui-danger tui-fillet" hover-class="tui-danger-hover" @click="submit">
				提交</button>
		</view>
		<tui-datetime ref="dateData" :type="type" :startYear="startYear" :endYear="endYear" :cancelColor="cancelColor" :color="color"
		 :setDateTime="setDateTime" @confirm="change"></tui-datetime>
		<tui-datetime ref="dateTime" :type="type" :startYear="startYear" :endYear="endYear" :cancelColor="cancelColor" :color="color"
		 :setDateTime="setDateTime" @confirm="change3"></tui-datetime>

		<w-picker mode="region" :defaultVal="['北京市','市辖区','东城区']" @confirm="onConfirm" ref="region"></w-picker>
	</view>

</template>

<script>
	import wPicker from "@/components/w-picker/w-picker.vue";
	import tuiButton from "@/components/button/button"
	import tuiDatetime from "@/components/dateTime/dateTime"
	export default {
		components: {
			tuiButton,
			tuiDatetime,
			wPicker
		},
		data() {
			return {
				ind: 0,
				resultInfo: {
					result: '请选择地区'
				},
				text: "选择城市",
				type: 1,
				startYear: 1980,
				endYear: 2030,
				cancelColor: "#888",
				color: "#5677fc",
				setDateTime: "",
				result: "选择日期",
				time: "选择时间",
				arr: {},
				data: [{
						type: "radio",
						name: "sex",
						desc: "性别",
						value: "",
						option: [{
							value: 0,
							name: "女",
							default: 1
						}, {
							value: 1,
							name: "男"
						}]
					},
					{
						type: "input",
						name: "name",
						desc: "姓名",
						value: ""
					},
					{
						type: "textarea",
						name: "msg",
						desc: "留言",
						value: ""
					},
					{
						type: "date",
						name: "date",
						desc: "日期",
						value: ""
					},
					{
						type: "switch",
						name: "switch",
						desc: "开关",
						value: ""
					},
					{
						type: "check",
						name: "check",
						desc: "套餐",
						value: "",
						option: [{
							value: 0,
							name: "A"
						}, {
							value: 1,
							name: "B"
						}],
					},
					{
						type: "city",
						name: "city",
						desc: "城市",
						value: ""
					},
					{
						type: "time",
						name: "time",
						desc: "时间",
						value: ""
					},
					{
						type: "country",
						name: "country",
						desc: "国家",
						value: "",
						option: ["中国",

							"美国",


							"巴西"
						]
					}
				],
			}
		},
		onLoad() {
			const data = this.data
			let arr = {}
			for (let k in data) {
				const v = data[k]
				arr[v.name] = v.value + ''
			}
			this.arr = arr
			console.log("arr:", arr)
		},
		methods: {
			bindPickerChange: function(e) {
				console.log('picker发送选择改变，携带值为', e.target.value)
				this.ind = e.target.value
				this.arr.country = e.target.value
			},
			toggleTab() {
				this.$refs['region'].show();
			},
			onConfirm(val) {
				console.log(val);
				//如果页面需要调用多个mode类型，可以根据mode处理结果渲染到哪里;
				// switch(this.mode){
				// 	case "date":
				// 		break;
				// }
				this.resultInfo = val;
				this.arr.city = this.resultInfo.result
			},

			checkboxChange: function(e) {
				let values = e.detail.value;
				this.arr.check = values
				console.log("e:", values)

			},
			switch1Change: function(e) {
				this.arr.switch = e.target.value
			},
			submit() {
				console.log(this.arr)
			},

			radioChange(e) {
				this.arr.sex = e.detail.value
			},
			show: function(type) {
				this.cancelColor = "#888";
				this.color = "#5677fc";
				this.setDateTime = "";
				this.startYear = 1980;
				this.endYear = 2030;
				switch (type) {
					case 1:
						//this.setDateTime = "2019-10-12";
						this.type = 2;
						break;
					case 3:
						// this.setDateTime = "18:01";
						this.type = 4;
						break;
					default:
						break;
				}
				this.$refs.dateData.show()
			},
			change: function(e) {
				console.log(e)
				this.result = e.result
				this.arr.date = e.result
			},
			show3: function(type) {
				this.cancelColor = "#888";
				this.color = "#5677fc";
				this.setDateTime = "";
				this.startYear = 1980;
				this.endYear = 2030;
				switch (type) {
					case 1:
						//this.setDateTime = "2019-10-12";
						this.type = 2;
						break;
					case 3:
						// this.setDateTime = "18:01";
						this.type = 4;
						break;
					default:
						break;
				}
				this.$refs.dateTime.show()
			},
			change3: function(e) {
				console.log(e)
				this.time = e.result
				this.arr.time = e.result
			}
		}
	}
</script>

<style lang="scss">
	@import '../../static/style/thorui.css';

	.form {
		font-size: 16px;

		.biao {
			display: flex;
			border-bottom: 1px solid #EDECE8;
			padding: 10px;

			.biao-l {
				width: 80px;
				text-align: right;
				flex-shrink: 0;
			}

			.biao-r {
				padding-left: 25px;
				flex-grow: 1;

				textarea {
					width: 90%;
					height: 80px;
				}

				span {
					padding-left: 10px;
				}
			}
		}

		.btn {
			padding: 50px 10px 0;
		}

		.uni-input {
			background-color: #fff;
		}
	}
</style>
