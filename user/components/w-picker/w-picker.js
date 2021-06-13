const forMatNum=(num)=>{
	return num<10?'0'+num:num+'';
}
const initPicker={
	//日期
	date:{
		init(start,end,mode="date",step,value,flag,disabled){
			let aToday=new Date();
			let tYear,tMonth,tDay,tHours,tMinutes,tSeconds,defaultVal=[];
			let initstartDate=new Date(start.toString());
			let endDate=new Date(end.toString());
			if(start>end){
				initstartDate=new Date(end.toString());
				endDate=new Date(start.toString());
			};
			let startYear=initstartDate.getFullYear();
			let startMonth=initstartDate.getMonth()+1;
			let endYear=endDate.getFullYear();
			let years=[],months=[],days=[],hours=[],minutes=[],seconds=[],areas=[],returnArr=[];
			let curMonth=flag?value[1]*1:(value[1]+1);
			let dYear=aToday.getFullYear();
			let dMonth=aToday.getMonth()+1;
			let dDate=aToday.getDate();
			let totalDays=new Date(startYear,curMonth,0).getDate();
			for(let s=startYear;s<=endYear;s++){
				years.push(s+'');
			};
			let curYear=years[value[0]];
			switch(mode){
				case "half":
				case "date":
				case "yearMonth":
					if(disabled&&curYear==dYear){
						for(let m=1;m<=dMonth;m++){
							months.push(forMatNum(m));
						};
						for(let d=1;d<=dDate;d++){
							days.push(forMatNum(d));
						}
					}else{
						for(let m=1;m<=12;m++){
							months.push(forMatNum(m));
						};
						for(let d=1;d<=totalDays;d++){
							days.push(forMatNum(d));
						}
					};
					break;
				default:
					for(let m=1;m<=12;m++){
						months.push(forMatNum(m));
					};
					for(let d=1;d<=totalDays;d++){
						days.push(forMatNum(d));
					}
					break;
			}
			for(let h=0;h<24;h++){
				hours.push(forMatNum(h));
			}
			for(let m=0;m<60;m+=step*1){
				minutes.push(forMatNum(m));
			}
			for(let s=0;s<60;s++){
				seconds.push(forMatNum(s));
			}
			if(flag){
				returnArr=[
					years.indexOf(value[0]),
					months.indexOf(value[1]),
					days.indexOf(value[2]),
					hours.indexOf(value[3]),
					minutes.indexOf(value[4])==-1?0:minutes.indexOf(value[4]),
					seconds.indexOf(value[5])
				]
			};
			switch(mode){
				case "range":
					if(flag){
						defaultVal=[returnArr[0],returnArr[1],returnArr[2],0,returnArr[0],returnArr[1],returnArr[2]];
						return {years,months,days,defaultVal}
					}else{
						return {years,months,days}
					}
					break;
				case "date":
					if(flag){
						defaultVal=[returnArr[0],returnArr[1],returnArr[2]];
						return {years,months,days,defaultVal}
					}else{
						return {years,months,days}
					}
					break;
				case "half":
					areas=[{
						label:"上午",
						value:0
					},{
						label:"下午",
						value:1
					}];
					if(flag){
						defaultVal=[returnArr[0],returnArr[1],returnArr[2],returnArr[3]];
						return {years,months,days,areas,defaultVal}
					}else{
						return {years,months,days,areas}
					}
					break;	
				case "yearMonth":
					if(flag){
						defaultVal=[returnArr[0],returnArr[1]];
						return {years,months,defaultVal}
					}else{
						return {years,months}
					}
					break;
				case "dateTime":
					if(flag){
						defaultVal=returnArr;
						return {years,months,days,hours,minutes,seconds,defaultVal}
					}else{
						return {years,months,days,hours,minutes,seconds}
					}
					break;
				case "time":
					if(flag){
						defaultVal=[returnArr[3],returnArr[4],returnArr[5]];
						return {hours,minutes,seconds,defaultVal}
					}else{
						return {hours,minutes,seconds}
					}
					break;			
			}
		},
		initMonths:(year,disabled)=>{
			let aDate=new Date();
			let dYear=aDate.getFullYear();
			let dMonth=aDate.getMonth()+1;
			let dDate=aDate.getDate();
			let flag=dYear==year?true:false;
			let months=[];
			if(flag&&disabled){
				for(let m=1;m<=dMonth;m++){
					months.push(forMatNum(m));
				};			
			}else{
				for(let m=1;m<=12;m++){
					months.push(forMatNum(m));
				};
			};
			return months;
		},
		initDays:(year,month,disabled)=>{
			let aDate=new Date();
			let dYear=aDate.getFullYear();
			let dMonth=aDate.getMonth()+1;
			let dDate=aDate.getDate();
			let flag=(dYear==year&&dMonth==month)?true:false;
			let totalDays=new Date(year,month,0).getDate();
			let dates=[];
			if(flag&&disabled){
				for(let d=1;d<=dDate;d++){
					dates.push(forMatNum(d));
				};			
			}else{
				for(let d=1;d<=totalDays;d++){
					dates.push(forMatNum(d));
				};
			};
			return dates;
		},
	},
	//短期日期上下午
	limitHour:{
		init(dayStep=7){
			let startDate=new Date();
			let date=[],areas=[],hours=[];
			let hour=new Date().getHours();
			let weeks=["周日","周一","周二","周三","周四","周五","周六"];
			let arrs=[];
			for(let i=0;i<dayStep;i++){
				let year,month,day,weekday;
				year=startDate.getFullYear();
				month=forMatNum(startDate.getMonth()+1);
				day=forMatNum(startDate.getDate());
				weekday=weeks[startDate.getDay()];
				let label="";
				switch(i){
					case 0:
						label="今天";
						break;
					case 1:
						label="明天"
						break;
					case 2:
						label="后天"
						break;
					default:
						label=month+"月"+day+"日"+" "+weekday;
						break;
				}
				date.push({
					label:label,
					value:year+"-"+month+"-"+day,
					today:i==0?true:false
				})
				startDate.setDate(startDate.getDate()+1);
			}
			if(hour>12){
				areas=[{
					label:"下午",
					value:1
				}]
			}else{
				areas=[{
					label:"上午",
					value:0
				},{
					label:"下午",
					value:1
				}]
			};
			for(let k=hour>12?hour-12:hour;k<=12;k++){
				hours.push({
					label:forMatNum(k),
					value:forMatNum(hour>12?k+12:k)
				})
			};
			return {date,areas,hours};
		},
		initAreas(date){
			let areas=[];
			let hour=new Date().getHours();
			if(date.today){
				if(hour>12){
					areas=[{
						label:"下午",
						value:1
					}]
				}else{
					areas=[{
						label:"上午",
						value:0
					},{
						label:"下午",
						value:1
					}]
				};
			}else{
				areas=[{
					label:"上午",
					value:0
				},{
					label:"下午",
					value:1
				}]
			}
			return areas;areas=[{
					label:"上午",
					value:0
				},{
					label:"下午",
					value:1
				}]
		},
		initHours(dateCol,hourCol){
			let hours=[];
			let hour=new Date().getHours();
			if(dateCol.today){
				if(hourCol.value==1&&hour<=12){
					for(let k=1;k<=12;k++){
						hours.push({
							label:forMatNum(k),
							value:forMatNum(hourCol.value==1?k+12:k)
						})
					};
				}else{
					for(let k=hour>12?hour-12:hour;k<=12;k++){
						hours.push({
							label:forMatNum(k),
							value:forMatNum(hourCol.value==1?k+12:k)
						})
					};
				}
				
			}else{
				for(let k=1;k<=12;k++){
					hours.push({
						label:forMatNum(k),
						value:forMatNum(hourCol.value==1?k+12:k)
					})
				};
			};
			return hours
		}
	},
	//短期日期时间初始化
	limit:{
		init(dayStep=7,startHour=8,endHour=20,minuteStep=1,afterStep=30){
			let startDate=new Date();
			let bsDate=new Date(new Date().getTime()+afterStep*60*1000);
			let date=[],hours=[],minutes=[];
			let hour=bsDate.getHours();
			let minute=Math.floor(bsDate.getMinutes()/minuteStep)*minuteStep;
			let weeks=["周日","周一","周二","周三","周四","周五","周六"];
			for(let i=0;i<dayStep;i++){
				let year,month,day,weekday;
				year=startDate.getFullYear();
				month=forMatNum(startDate.getMonth()+1);
				day=forMatNum(startDate.getDate());
				weekday=weeks[startDate.getDay()];
				let label="";
				switch(i){
					case 0:
						label="今天";
						break;
					case 1:
						label="明天"
						break;
					case 2:
						label="后天"
						break;
					default:
						label=month+"月"+day+"日"+" "+weekday;
						break;
				}
				date.push({
					label:label,
					value:year+"-"+month+"-"+day,
					flag:i==0?true:false
				})
				startDate.setDate(startDate.getDate()+1);
			}
			if(hour<startHour){
				hour=startHour;
			};
			if(hour>endHour){
				hour=endHour;
			};
			for(let k=hour*1;k<=endHour*1;k++){
				hours.push({
					label:forMatNum(k),
					value:forMatNum(k),
					flag:k==hour?true:false
				})
			};
			for(let j=minute;j<60;j+=minuteStep*1){
				minutes.push({
					label:forMatNum(j),
					value:forMatNum(j)
				});
			}
			return {date,hours,minutes};
		},
		initHours(startHour=8,endHour=20,minuteStep=1,afterStep=30,date){
			let hours=[];
			let arr=date.split("-");
			let aDate=new Date();
			let dYear=aDate.getFullYear();
			let dMonth=aDate.getMonth()+1;
			let dDate=aDate.getDate();
			let bsDate=new Date(new Date().getTime()+afterStep*60*1000);
			let hour=bsDate.getHours();
			let flag=(dYear==arr[0]&&dMonth==arr[1]&&dDate==arr[2])?true:false;
			if(hour>endHour){
				hour=endHour;
			};
			if(flag){
				for(let k=hour*1;k<=endHour*1;k++){
					hours.push({
						label:forMatNum(k),
						value:forMatNum(k),
						flag:k==hour?true:false
					})
				};			
			}else{
				for(let k=startHour*1;k<=endHour*1;k++){
					hours.push({
						label:forMatNum(k),
						value:forMatNum(k),
						flag:false
					})
				}			
			};
			return hours;
		},
		initMinutes(startHour=8,endHour=20,minuteStep=1,afterStep=30,date,hour){
			let minutes=[];
			let bsDate=new Date(new Date().getTime()+afterStep*60*1000);
			let arr=date.split("-");
			let aDate=new Date();
			let dYear=aDate.getFullYear();
			let dMonth=aDate.getMonth()+1;
			let dDate=aDate.getDate();
			let dHour=bsDate.getHours();;
			let minute=Math.floor(bsDate.getMinutes()/minuteStep)*minuteStep;
			let flag=(dYear==arr[0]&&dMonth==arr[1]&&dDate==arr[2])?true:false;
			if(flag){
				if(hour==dHour){
					for(let j=minute;j<60;j+=minuteStep*1){
						minutes.push({
							label:forMatNum(j),
							value:forMatNum(j)
						});
					}	
				}else{
					for(let j=0;j<60;j+=minuteStep*1){
						minutes.push({
							label:forMatNum(j),
							value:forMatNum(j)
						})
					}
				}
						
			}else{
				for(let j=0;j<60;j+=minuteStep*1){
					minutes.push({
						label:forMatNum(j),
						value:forMatNum(j)
					})
				}			
			}
			return minutes;
		}
	},
	//选择区间初始化
	range:{
		init(start,end,value,flag){
			let aToday=new Date();
			let tYear,tMonth,tDay,tHours,tMinutes,tSeconds,defaultVal=[];
			let initstartDate=new Date(start.toString());
			let endDate=new Date(end.toString());
			if(start>end){
				initstartDate=new Date(end.toString());
				endDate=new Date(start.toString());
			};
			let startYear=initstartDate.getFullYear();
			let startMonth=initstartDate.getMonth()+1;
			let endYear=endDate.getFullYear();
			let fyears=[],fmonths=[],fdays=[],tyears=[],tmonths=[],tdays=[],returnArr=[];
			let curMonth=flag?value[1]*1:(value[1]+1);
			let totalDays=new Date(startYear,curMonth,0).getDate();
			for(let s=startYear;s<=endYear;s++){
				fyears.push(s+'');
			};
			for(let m=1;m<=12;m++){
				fmonths.push(forMatNum(m));
			};
			for(let d=1;d<=totalDays;d++){
				fdays.push(forMatNum(d));
			};
			for(let s=startYear;s<=endYear;s++){
				tyears.push(s+'');
			};
			for(let m=1;m<=12;m++){
				tmonths.push(forMatNum(m));
			};
			for(let d=1;d<=totalDays;d++){
				tdays.push(forMatNum(d));
			};
			if(flag){
				defaultVal=[
					fyears.indexOf(value[0]),
					fmonths.indexOf(value[1]),
					fdays.indexOf(value[2]),
					0,
					tyears.indexOf(value[0]),
					tmonths.indexOf(value[1]),
					tdays.indexOf(value[2])
				];
				return {
					fyears,
					fmonths,
					fdays,
					tyears,
					tmonths,
					tdays,
					defaultVal
				}
			}else{
				return {
					fyears,
					fmonths,
					fdays,
					tyears,
					tmonths,
					tdays,
				}
			}
		},
		initDays(year,month){
			let totalDays=new Date(year,month,0).getDate();
			let dates=[];
			for(let d=1;d<=totalDays;d++){
				dates.push(forMatNum(d));
			};
			return dates;
		}
	}
}

export default initPicker