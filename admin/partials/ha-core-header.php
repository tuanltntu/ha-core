<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://tuanltntu.com
 * @since      1.0.0
 *
 * @package    Ha_Core
 * @subpackage Ha_Core/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="ha-core" :class="'ha-core ha-fixed ' + HA.currentPage">
	<div v-if="!init" class="ha-plugins-init">
		<div class="lds-ripple-wrap">
			<div class="lds-ripple"><div></div><div></div></div>
		</div>
	</div>
	<a-layout>
		<template v-if="winWidth > 768">
			<a-layout-sider
			  :trigger="null"
			  collapsible
			  v-model="collapsed"
			>			
				<div class="logo" @click="()=> {collapsed = !collapsed; localStorage.ha_collapsed = collapsed}">
					<a-icon	
					  class="trigger i-menu"
					  :type="collapsed ? 'menu-unfold' : 'menu-fold'"
					></a-icon>
					HA PLUGINS
				</div>
				<?php include 'ha-core-menu.php'; ?>
			</a-layout-sider>
		</template>
		<template v-else>
			<a-drawer
				title="<?php _e('Menu', 'ha-core'); ?>"
				placement="right"
				:closable="true"
				@close="onMenuMobile"
				:visible="collapsed"
			>
				<?php include 'ha-core-menu.php'; ?>
			</a-drawer>
		</template>
		<a-layout :style="{minHeight: contentHeight + 'px'}">
			<a-layout-header class="ha-header" style="background: #fff; padding: 10px 16px;">
				<h3>{{ HA.title }}</h3>
				<div class="ha-controls">
					<?php do_action('ha-core-header-controls'); ?>
                    <template v-if="winWidth < 768">
                        <a-icon
                            @click="()=> {collapsed = !collapsed; localStorage.ha_collapsed = collapsed}"
                            class="trigger i-menu ha-mobile-menu-icon"
                            :type="collapsed ? 'menu-unfold' : 'menu-fold'"
                        ></a-icon>
                    </template>
				</div>
			</a-layout-header>
			<a-layout-content :style="{ margin: '12px', padding: '24px', background: '#fff', minHeight: '280px' }">