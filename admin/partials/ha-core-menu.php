<a-menu
	:default-selected-keys="HA.currentPage"
	:default-open-keys="HA.currentOpen"
	mode="inline"
	theme="dark"
	@click="switchMenu"
>
	<template v-for="item in HA.menu_items">
		<a-menu-item v-if="!item.children" :key="item.key">
			<a-icon :type="item.icon" v-if="item.icon"></a-icon>
			<span>{{item.title}}</span>
		</a-menu-item>
		<a-sub-menu v-else :key="item.key">
			<span slot="title">
				<a-icon :type="item.icon" v-if="item.icon"></a-icon>
				<span>{{item.title}}</span>
			</span>
			<a-menu-item v-for="sub in item.children" :key="sub.key">{{ sub.title }}</a-menu-item>
		</a-sub-menu>
	</template>
</a-menu>