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
                                :label="$t('creditor.name')"
                                name="name"
                                :help="rules.name ? rules.name.message : null"
                                :validateStatus="rules.name ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.email')"
                                name="email"
                                :help="rules.email ? rules.email.message : null"
                                :validateStatus="rules.email ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.email"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.email'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.first_name')"
                                name="first_name"
                                :help="rules.first_name ? rules.first_name.message : null"
                                :validateStatus="rules.first_name ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.first_name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.first_name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.last_name')"
                                name="last_name"
                                :help="rules.last_name ? rules.last_name.message : null"
                                :validateStatus="rules.last_name ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.last_name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.last_name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.phone_number')"
                                name="phone_number"
                                :help="rules.phone_number ? rules.phone_number.message : null"
                                :validateStatus="rules.phone_number ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.phone_number"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.phone_number'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.address_line1')"
                                name="address_line1"
                                :help="rules.address_line1 ? rules.address_line1.message : null"
                                :validateStatus="rules.address_line1 ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.address_line1"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.address_line1'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.address_line2')"
                                name="address_line2"
                                :help="rules.address_line2 ? rules.address_line2.message : null"
                                :validateStatus="rules.address_line2 ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.address_line2"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.address_line2'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.city')"
                                name="city"
                                :help="rules.city ? rules.city.message : null"
                                :validateStatus="rules.city ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.city"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.city'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.state_id')"
                                name="state_id"
                                :help="rules.state_id ? rules.state_id.message : null"
                                :validateStatus="rules.state_id ? 'error' : null"
                            >
                                <a-select
                                    v-model:value="formData.state_id"
                                    :placeholder="
                                        $t('common.select_default_text', [
                                            $t('creditor.state_id'),
                                        ])
                                    "
                                    :allowClear="true"
                                    optionFilterProp="title"
                                    show-search
                                >
                                    <a-select-option 
                                        v-for="state in states" 
                                        :key="state.id" 
                                        :value="state.id"
                                        :title="state.name"
                                    >
                                        {{ state.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('creditor.zip_code')"
                                name="zip_code"
                                :help="rules.zip_code ? rules.zip_code.message : null"
                                :validateStatus="rules.zip_code ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.zip_code"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('creditor.zip_code'),
                                        ])
                                    "
                                />
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
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
            </a-button>
            <a-button @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "states"
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined
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