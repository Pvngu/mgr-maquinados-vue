<template>
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
    >
        <a-form layout="vertical">
            <a-row>
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.client_id')"
                                name="client_id"
                                :help="rules.client_id ? rules.client_id.message : null"
                                :validateStatus="rules.client_id ? 'error' : null"
                                class="required"
                            >
                                <a-select
                                    v-model:value="formData.client_id"
                                    :placeholder="$t('common.select_default_text', [$t('orders.client_id')])"
                                    show-search
                                    optionFilterProp="title"
                                >
                                    <a-select-option
                                        v-for="client in clients"
                                        :key="client.id"
                                        :title="client.name"
                                        :value="client.id"
                                    >
                                        {{ client.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.order_date')"
                                name="order_date"
                                :help="rules.order_date ? rules.order_date.message : null"
                                :validateStatus="rules.order_date ? 'error' : null"
                                class="required"
                            >
                                <DateTimePicker
                                    :dateTime="formData.order_date"
                                    :isFutureDateDisabled="false"
                                    :showTime="false"
                                    :onlyDate="true"
                                    @dateTimeChanged="(changedDateTime) => { formData.order_date = changedDateTime; }"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.total_amount')"
                                name="total_amount"
                                :help="rules.total_amount ? rules.total_amount.message : null"
                                :validateStatus="rules.total_amount ? 'error' : null"
                                class="required"
                            >
                                <a-input-number
                                    v-model:value="formData.total_amount"
                                    :placeholder="$t('common.placeholder_default_text', [$t('orders.total_amount')])"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.user_id')"
                                name="user_id"
                                :help="rules.user_id ? rules.user_id.message : null"
                                :validateStatus="rules.user_id ? 'error' : null"
                                class="required"
                            >
                                <a-select
                                    v-model:value="formData.user_id"
                                    :placeholder="$t('common.select_default_text', [$t('orders.user_id')])"
                                    show-search
                                    optionFilterProp="title"
                                >
                                    <a-select-option
                                        v-for="user in users"
                                        :key="user.id"
                                        :title="user.name"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button
                type="primary"
                @click="onSubmit"
                style="margin-right: 8px"
                :loading="loading"
            >
                <template #icon> <SaveOutlined /> </template>
                {{
                    addEditType == "add"
                        ? $t("common.create")
                        : $t("common.update")
                }}
            </a-button>
            <a-button @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        DateTimePicker,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "40%",
        };
    },
});
</script>
