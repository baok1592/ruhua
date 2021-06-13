<template>
	<view class="zhibo-detail">
		<live-player src="https://domain/pull_stream" autoplay @statechange="statechange" 
		@error="error" style="width: 300px; height: 225px;" />
		
		<navigator url="plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=2'">Go to Live Player page</navigator>
	</view>
</template>

<script>
export default {
	data() {
		return {};
	},
	methods: {
		statechange(e) {
			console.log('live-player code:', e.detail.code);
		},
		error(e) {
			console.error('live-player error:', e.detail.errMsg);
		}
	}
};
</script>

<style lang="scss"></style>
