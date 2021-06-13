<template>
	<div>
		<div style="margin-top: 100px; margin-bottom: 50px;">当前时间：{{now_time}}</div>
		<!-- <el-button type="primary" plain @click="quanbu">全部</el-button> -->
		<template v-for="(item,index) of time">
			<!-- <div class="sea_02_01_r_2" @click="choose_time(item.ti)">
			{{item.ti}}
		</div> -->
			<el-button type="primary" plain size="mini" @click="choose_time(item.value)">{{item.ti}}</el-button>
		</template>
		<div style="margin: 50px;">时间戳范围:{{time_range}}</div>
		<div>时间范围:{{time_range2}}</div>
	</div>

</template>

<script>
	export default {
		data() {
			return {
				time_range: '',
				time_range2: '',
				now_time: '',
				week_start: '',
				week_end: '',
				month_start: '',
				month_end: '',
				now_year_firstDay: '',
				now_year_lastDay: '',
				Quarter_start: '',
				Quarter_end: '',
				yestoday_start: '',
				yestoday_end: '',
				range_start: '',
				range_end: '',
				time: [{
					"ti": "昨天",
					value: '0'
				}, {
					"ti": "今天",
					value: '1'
				}, {
					"ti": "本周",
					value: '2'
				}, {
					"ti": "本月",
					value: '3'
				}, {
					"ti": "本季度",
					value: '4'
				}, {
					"ti": "今年",
					value: '5'
				}],
			}
		},
		props:[],
		mounted() {
			this.get_now_time()
			this.get_week();
			this.get_month()
			this.get_year()
			this.get_Quarter()
			this.getDate()
			this.get_today()
		},
		methods: {
			formatNumber(n) {
				n = n.toString()
				return n[1] ? n : '0' + n;
			},
			formatTime(number, format) {
				let time = new Date(number)
				let newArr = []
				let formatArr = ['Y', 'M', 'D', 'h', 'm', 's']
				newArr.push(time.getFullYear())
				newArr.push(this.formatNumber(time.getMonth() + 1))
				newArr.push(this.formatNumber(time.getDate()))

				newArr.push(this.formatNumber(time.getHours()))
				newArr.push(this.formatNumber(time.getMinutes()))
				newArr.push(this.formatNumber(time.getSeconds()))

				for (let i in newArr) {
					format = format.replace(formatArr[i], newArr[i])
				}
				return format;
			},
			get_now_time() {
				this.now_time = new Date().getTime()
			},
			choose_time(value) {
				if (value == 0) {
					this.time_range = this.yestoday_start + '-' + this.yestoday_end

					this.time_range2 = this.formatTime(this.yestoday_start, 'Y年-M月-D日') + '---' + this.formatTime(this.yestoday_end,
						'Y年-M月-D日')
						// this.$emit("父组件接收名称(xxx)","传给父组件的值(data)")
						//<time_range v-on:xxx = "show"><time_range/>
				}
				if (value == 1) {
					this.time_range = this.day_start + '-' + this.day_end
					this.time_range2 = this.formatTime(this.day_start, 'Y年-M月-D日') + '---' + this.formatTime(this.day_end,
						'Y年-M月-D日')
				}
				if (value == 2) {
					this.time_range = this.week_start + '-' + this.week_end
					this.time_range2 = this.formatTime(this.week_start, 'Y年-M月-D日') + '---' + this.formatTime(this.week_end,
						'Y年-M月-D日')
				}
				if (value == 3) {
					this.time_range = this.month_start + '-' + this.month_end
					this.time_range2 = this.formatTime(this.month_start, 'Y年-M月-D日') + '---' + this.formatTime(this.month_end,
						'Y年-M月-D日')
				}
				if (value == 4) {
					this.time_range = this.Quarter_start + '-' + this.Quarter_end
					this.time_range2 = this.formatTime(this.Quarter_start, 'Y年-M月-D日') + '---' + this.formatTime(this.Quarter_end,
						'Y年-M月-D日')
				}
				if (value == 5) {
					this.time_range = this.now_year_firstDay + '-' + this.now_year_lastDay
					this.time_range2 = this.formatTime(this.now_year_firstDay, 'Y年-M月-D日') + '---' + this.formatTime(this.now_year_lastDay,
						'Y年-M月-D日')
				}

			},
			get_week() {
				//按周日为一周的最后一天计算  
				var date = new Date();

				//今天是这周的第几天  
				var today = date.getDay();

				//上周日距离今天的天数（负数表示）  
				var stepSunDay = -today + 1;

				// 如果今天是周日  
				if (today == 0) {

					stepSunDay = -7;
				}

				// 周一距离今天的天数（负数表示）  
				var stepMonday = 7 - today;

				var time = date.getTime();

				var monday = new Date(time + stepSunDay * 24 * 3600 * 1000);
				var sunday = new Date(time + stepMonday * 24 * 3600 * 1000);

				//本周一的日期 （起始日期）  
				var startDate = monday.getTime()

				//本周日的日期 （结束日期）  
				var endDate = sunday.getTime() // 日期变换  
				this.week_start = startDate
				this.week_end = endDate
				// console.log(startDate + ' - ' + endDate)
				// return startDate + ' - ' + endDate;
			},
			get_month() {
				// 获取当前月的第一天  
				var start = new Date();
				start.setDate(1);
				// 获取当前月的最后一天  
				var date = new Date();
				var currentMonth = date.getMonth();
				var nextMonth = ++currentMonth;
				var nextMonthFirstDay = new Date(date.getFullYear(), nextMonth, 1);
				var oneDay = 1000 * 60 * 60 * 24;
				var end = new Date(nextMonthFirstDay - oneDay);

				// var startDate = transferDate(start); // 日期变换  
				var startDate = start.getTime() // 日期变换  
				// var endDate = transferDate(end); // 日期变换  
				var endDate = end.getTime() // 日期变换  
				this.month_start = startDate
				this.month_end = endDate
				// return startDate + ' - ' + endDate;  
			},
			get_year() { //获取当前年开始时间-结束时间
				var now = new Date()
				var now_year = now.getFullYear()
				var now_year_firstDay = new Date(now_year, 0, 1).getTime()
				var now_year_lastDay = new Date(now_year, 11, 31).getTime()
				this.now_year_firstDay = now_year_firstDay
				this.now_year_lastDay = now_year_lastDay
			},
			get_Quarter_date() { //获得本季度的开始月份
				var now = new Date()
				var nowMonth = now.getMonth();
				var quarterStartMonth = 0;
				if (nowMonth < 3) {
					quarterStartMonth = 0;
				}
				if (2 < nowMonth && nowMonth < 6) {
					quarterStartMonth = 3;
				}
				if (5 < nowMonth && nowMonth < 9) {
					quarterStartMonth = 6;
				}
				if (nowMonth > 8) {
					quarterStartMonth = 9;
				}
				return quarterStartMonth;
			},
			get_Quarter() { //获取当前季度
				var now = new Date()
				var nowYear = now.getYear()
				nowYear += (nowYear < 2000) ? 1900 : 0;
				var Quarter_start = new Date(nowYear, this.get_Quarter_date(), 1).getTime()
				var Quarter_end_month = this.get_Quarter_date() + 2
				var Quarter_end = new Date(nowYear, Quarter_end_month, this.get_month_days(Quarter_end_month)).getTime()
				this.Quarter_start = Quarter_start
				this.Quarter_end = Quarter_end
			},
			get_month_days(myMonth) { //获取当前月天数
				var now = new Date()
				var nowYear = now.getYear();
				var monthStartDate = new Date(nowYear, myMonth, 1);
				var monthEndDate = new Date(nowYear, myMonth + 1, 1);
				var days = (monthEndDate - monthStartDate) / (1000 * 60 * 60 * 24);
				return days;
			},
			getDate() {
				var dd = new Date();
				var n = -1
				dd.setDate(dd.getDate() + n);
				var y = dd.getFullYear();
				var m = dd.getMonth() + 1;
				var d = dd.getDate();
				m = m < 10 ? "0" + m : m;
				d = d < 10 ? "0" + d : d;
				var day = y + "-" + m + "-" + d;
				this.yestoday_start = new Date(day).getTime() - 8 * 60 * 60 //昨天开始时间
				this.yestoday_end = new Date(day).getTime() + 16 * 60 * 60
			},
			get_today() {
				var dd = new Date();
				var n = 0
				dd.setDate(dd.getDate() + n);
				var y = dd.getFullYear();
				var m = dd.getMonth() + 1;
				var d = dd.getDate();
				m = m < 10 ? "0" + m : m;
				d = d < 10 ? "0" + d : d;
				var day = y + "-" + m + "-" + d;
				this.day_start = new Date(day).getTime() - 8 * 60 * 60
				this.day_end = new Date(day).getTime() + 16 * 60 * 60
			}
		}
	}
</script>

<style>
</style>
