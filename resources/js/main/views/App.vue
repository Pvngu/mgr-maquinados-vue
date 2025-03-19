<template>
    <router-view v-slot="{ Component, route }">
        <suspense>
            <template #default>
                <a-config-provider
                    :theme="{
                        token: {
                            colorPrimary: appSetting.primary_color,
                            fontFamily: 'Nunito,sans-serif',
                            borderRadius: 4,
                        },
                    }"
                    :direction="appSetting.rtl ? 'rtl' : 'ltr'"
                >
                    <div class="theme-container">
                        <ThemeProvider :theme="{ ...themeVars }">
                            <component :is="Component" />
                        </ThemeProvider>
                    </div>
                </a-config-provider>
            </template>
            <template #fallback> Loading... </template>
        </suspense>
    </router-view>
</template>

<script>
import { watch, onMounted, computed } from "vue";
import { theme } from "ant-design-vue";
import { ThemeProvider } from "vue3-styled-components";
import { themeVars } from "../config/theme/themeVariables";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import common from "../../common/composable/common";
import LoadingApp from "./LoadingApp.vue";

export default {
    name: "App",
    components: {
        ThemeProvider,
        LoadingApp,
    },
    setup() {
        const route = useRoute();
        const store = useStore();
        const darkTheme = "dark";
        const { updatePageTitle, appSetting, appType } = common();
        const appChecking = computed(() => store.state.auth.appChecking);

        onMounted(() => {
            // if (
            //     router.currentRoute &&
            //     router.currentRoute.value &&
            //     router.currentRoute.value.meta.isFrontStore == undefined
            // ) {
            //     setInterval(() => {
            //         store.dispatch("auth/refreshToken");
            //     }, 10 * 60 * 1000);
            // }
        });

        watch(route, (newVal, oldVal) => {
            // router.push({
            //     name: "admin.setup_app.index",
            // });

            const menuKey =
                typeof newVal.meta.menuKey == "function"
                    ? newVal.meta.menuKey(newVal)
                    : newVal.meta.menuKey;

            updatePageTitle(menuKey.replace("-", "_"));

        });

        return {
            theme,
            themeVars,
            darkTheme,
            appChecking,
            appSetting,
        };
    },
};
</script>

<style>
body {
    background: #f0f2f5 !important;
}
</style>
