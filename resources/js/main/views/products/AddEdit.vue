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
                                :label="$t('common.name')"
                                name="name"
                                :help="rules.name ? rules.name.message : null"
                                :validateStatus="rules.name ? 'error' : null"
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.name"
                                    :placeholder="$t('common.placeholder_default_text', [$t('common.name')])"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('common.description')"
                                name="description"
                                :help="rules.description ? rules.description.message : null"
                                :validateStatus="rules.description ? 'error' : null"
                            >
                                <a-textarea
                                    v-model:value="formData.description"
                                    :placeholder="$t('common.placeholder_default_text', [$t('common.description')])"
                                    :auto-size="{ minRows: 4, maxRows: 6 }"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('products.price')"
                                name="price"
                                :help="rules.price ? rules.price.message : null"
                                :validateStatus="rules.price ? 'error' : null"
                                class="required"
                            >
                                <a-input-number
                                    v-model:value="formData.price"
                                    :placeholder="$t('common.placeholder_default_text', [$t('products.price')])"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('products.stock_quantity')"
                                name="stock_quantity"
                                :help="rules.stock_quantity ? rules.stock_quantity.message : null"
                                :validateStatus="rules.stock_quantity ? 'error' : null"
                                class="required"
                            >
                                <a-input-number
                                    v-model:value="formData.stock_quantity"
                                    :placeholder="$t('common.placeholder_default_text', [$t('products.stock_quantity')])"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('products.initial_stock_quantity')"
                                name="initial_stock_quantity"
                                :help="rules.initial_stock_quantity ? rules.initial_stock_quantity.message : null"
                                :validateStatus="rules.initial_stock_quantity ? 'error' : null"
                                class="required"
                            >
                                <a-input-number
                                    v-model:value="formData.initial_stock_quantity"
                                    :placeholder="$t('common.placeholder_default_text', [$t('products.initial_stock_quantity')])"
                                    style="width: 100%"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('products.category')"
                                name="category_id"
                                :help="rules.category_id ? rules.category_id.message : null"
                                :validateStatus="rules.category_id ? 'error' : null"
                                class="required"
                            >
                                <a-select
                                    v-model:value="formData.category_id"
                                    :placeholder="$t('common.select_default_text', [$t('products.category')])"
                                    show-search
                                    optionFilterProp="title"
                                    style="width: 100%"
                                >
                                    <a-select-option
                                        v-for="category in categories"
                                        :key="category.xid"
                                        :title="category.name"
                                        :value="category.xid"
                                    >
                                        {{ category.name }}
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

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "categories"
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
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
