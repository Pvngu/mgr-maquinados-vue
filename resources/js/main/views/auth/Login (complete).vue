<template>
    <div class="login-main-container">
        <a-row class="main-container-div">
            <a-col :xs="0" :sm="0" :md="0" :lg="12">
                <div class="left-login-div">
                    <img class="left-image" src="/images/login_background.png" />
                </div>
            </a-col>
            <a-col :xs="24" :sm="24" :md="24" :lg="12">
                <a-row class="login-left-div">
                    <a-col
                        :xs="{ span: 20, offset: 2 }"
                        :sm="{ span: 20, offset: 2 }"
                        :md="{ span: 16, offset: 4 }"
                        :lg="{ span: 16, offset: 4 }"
                    >
                        <a-card
                            :title="null"
                            class="login-div"
                            :bordered="innerWidth <= 768 ? true : false"
                        >
                            <a-form layout="vertical">
                                <div class="login-logo mb-30">
                                    <img
                                        class="login-img-logo"
                                        :src="globalSetting.light_logo_url"
                                    />
                                </div>
                                <a-row>
                                    <a-col class="text-center" :span="24">
                                        <a-typography-title :level="3">
                                            Welcome Back!
                                        </a-typography-title>
                                        <a-typography-paragraph>
                                            Please Enter your details.
                                        </a-typography-paragraph>
                                    </a-col>
                                </a-row>
                                <a-alert
                                    v-if="onRequestSend.error != ''"
                                    :message="onRequestSend.error"
                                    type="error"
                                    show-icon
                                    class="mb-20 mt-10"
                                />
                                <a-alert
                                    v-if="onRequestSend.success"
                                    :message="$t('messages.login_success')"
                                    type="success"
                                    show-icon
                                    class="mb-20 mt-10"
                                />
                                <a-form-item
                                    :label="$t('user.email_phone')"
                                    name="email"
                                    :help="rules.email ? rules.email.message : null"
                                    :validateStatus="rules.email ? 'error' : null"
                                >
                                    <a-input
                                        v-model:value="credentials.email"
                                        @pressEnter="onSubmit"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('user.email_phone'),
                                            ])
                                        "
                                    />
                                </a-form-item>

                                <a-form-item
                                    :label="$t('user.password')"
                                    name="password"
                                    :help="rules.password ? rules.password.message : null"
                                    :validateStatus="rules.password ? 'error' : null"
                                >
                                    <a-input-password
                                        v-model:value="credentials.password"
                                        @pressEnter="onSubmit"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('user.password'),
                                            ])
                                        "
                                    />
                                </a-form-item>

                                <a-form-item class="mt-30">
                                    <a-button
                                        :loading="loading"
                                        @click="onSubmit"
                                        class="login-btn"
                                        type="primary"
                                        block
                                    >
                                        {{ $t("menu.login") }}
                                    </a-button>
                                </a-form-item>
                            </a-form>
                        </a-card>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { defineComponent, reactive, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";

export default defineComponent({
    setup() {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { globalSetting, appType } = common();
        const loginBackground = globalSetting.value.login_image_url;
        const store = useStore();
        const router = useRouter();
        const credentials = reactive({
            email: null,
            password: null,
        });
        const onRequestSend = ref({
            error: "",
            success: "",
        });

        const onSubmit = () => {
            onRequestSend.value = {
                error: "",
                success: false,
            };

            addEditRequestAdmin({
                url: "auth/login",
                data: credentials,
                success: (response) => {
                    if (response && response.status == "success") {
                        const user = response.user;
                        store.commit("auth/updateUser", user);
                        store.commit("auth/updateToken", response.token);
                        store.commit("auth/updateExpires", response.expires_in);
                        store.commit(
                            "auth/updateVisibleSubscriptionModules",
                            response.visible_subscription_modules
                        );

                        router.push({
                            name: "admin.dashboard.index",
                            params: { success: true },
                        });
                    } else {
                        onRequestSend.value = {
                            error: response.message ? response.message : "",
                            success: false,
                        };
                    }
                },
                error: (err) => {},
            });
        };

        return {
            loading,
            rules,
            credentials,
            onSubmit,
            onRequestSend,
            globalSetting,
            loginBackground,

            innerWidth: window.innerWidth,
        };
    },
});
</script>

<style lang="less">
.login-main-container {
    background: #fff;
    height: 100vh;
}

.main-container-div {
    height: 100%;
}

.login-left-div {
    height: 100%;
    align-items: center;
}

.login-logo {
    text-align: center;
}

.login-img-logo {
    width: 150px;
}

.container-content {
    margin-top: 100px;
}

.login-div {
    border-radius: 10px;
}

.outer-div {
    margin: 0;
}

.left-login-div {
    background: #f8f8ff;
    height: 100%;
    display: flex;
    align-items: center;
}

.left-image {
    width: 100%;
    height: 100%;
    display: block;
    margin: 0 auto;
    max-height: 100svh;
    object-fit: cover;
}
</style>
