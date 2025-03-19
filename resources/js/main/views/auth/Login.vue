<template>
    <main class="login-container">
        <a-card style="max-width: 420px; margin: 0 auto;" :title="null">
            <div class="card-header">
                <img class="left-image" src="/images/login_background.png" />
            </div>
            <a-form layout="vertical">
                <a-row>
                    <a-col :span="24">
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
                    </a-col>
                    <a-col :span="24">
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
                    </a-col>
                    <a-col :span="24">
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
                    </a-col>
                    <a-col :span="24">
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
                    </a-col>
                </a-row>
            </a-form>
        </a-card>
    </main>
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

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

.login-container {
    padding-top: 4rem;
}

.card-header {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    gap: 8px;
}

.logo-title {
    font-family: "Roboto", sans-serif;
    font-weight: 800;
    font-size: 1.8rem;
    opacity: 0.9;
}
</style>
