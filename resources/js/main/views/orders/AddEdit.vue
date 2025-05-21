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
                                :help="rules.clienft_id ? rules.client_id.message : null"
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
                                        v-for="client in customers"
                                        :key="client.xid"
                                        :title="client.name"
                                        :value="client.xid"
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
                    
                    <!-- Contact Information -->
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.contact_name')"
                                name="contact_name"
                                :help="rules.contact_name ? rules.contact_name.message : null"
                                :validateStatus="rules.contact_name ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.contact_name"
                                    :placeholder="$t('common.placeholder_default_text', [$t('orders.contact_name')])"
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.contact_phone')"
                                name="contact_phone"
                                :help="rules.contact_phone ? rules.contact_phone.message : null"
                                :validateStatus="rules.contact_phone ? 'error' : null"
                            >
                                <PhoneInput
                                    v-model="formData.contact_phone"
                                    :disabled="false"
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    
                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('orders.contact_email')"
                                name="contact_email"
                                :help="rules.contact_email ? rules.contact_email.message : null"
                                :validateStatus="rules.contact_email ? 'error' : null"
                            >
                                <a-input
                                    v-model:value="formData.contact_email"
                                    :placeholder="$t('common.placeholder_default_text', [$t('orders.contact_email')])"
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
                                        :key="user.xid"
                                        :title="user.name"
                                        :value="user.xid"
                                    >
                                        {{ user.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                    </a-row>
                    
                    <!-- Order Items Section -->
                    <a-divider orientation="left">{{ $t('orders.order_items') }}</a-divider>
                    
                    <a-table
                        :dataSource="formData.order_items"
                        :pagination="false"
                        :bordered="true"
                        size="middle"
                    >
                        <a-table-column key="no" :title="$t('common.no')" align="center" width="60px">
                            <template #default="{ index }">
                                {{ index + 1 }}
                            </template>
                        </a-table-column>
                        
                        <a-table-column key="product_id" :title="$t('orders.product')">
                            <template #default="{ record, index }">
                                <a-select
                                    v-model:value="record.product_id"
                                    :placeholder="$t('common.select_default_text', [$t('orders.product')])"
                                    style="width: 100%"
                                    show-search
                                    optionFilterProp="title"
                                    @change="(value) => productSelected(value, index)"
                                >
                                    <a-select-option
                                        v-for="product in products"
                                        :key="product.id"
                                        :title="product.name"
                                        :value="product.id"
                                    >
                                        {{ product.name }}
                                    </a-select-option>
                                </a-select>
                            </template>
                        </a-table-column>
                        
                        <a-table-column key="quantity" :title="$t('orders.quantity')" width="120px">
                            <template #default="{ record }">
                                <a-input-number
                                    v-model:value="record.quantity"
                                    :min="1"
                                    style="width: 100%"
                                    @change="calculateItemTotal(record)"
                                />
                            </template>
                        </a-table-column>
                        
                        <a-table-column key="price" :title="$t('orders.price')" width="120px">
                            <template #default="{ record }">
                                <a-input-number
                                    v-model:value="record.price"
                                    :min="0"
                                    :precision="2"
                                    style="width: 100%"
                                    @change="calculateItemTotal(record)"
                                />
                            </template>
                        </a-table-column>
                        
                        <a-table-column key="subtotal" :title="$t('orders.subtotal')" align="right" width="120px">
                            <template #default="{ record }">
                                {{ formatPrice(record.quantity * record.price) }}
                            </template>
                        </a-table-column>
                        
                        <a-table-column key="action" :title="$t('common.action')" align="center" width="70px">
                            <template #default="{ index }">
                                <a-button
                                    type="primary"
                                    danger
                                    size="small"
                                    @click="removeOrderItem(index)"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button>
                            </template>
                        </a-table-column>
                    </a-table>
                    
                    <a-row class="mt-20">
                        <a-col :span="24">
                            <a-button type="dashed" block @click="addOrderItem">
                                <template #icon><PlusOutlined /></template>
                                {{ $t('orders.add_order_item') }}
                            </a-button>
                        </a-col>
                    </a-row>
                    
                    <!-- Order Total -->
                    <a-row :gutter="16" class="mt-20" justify="end">
                        <a-col :xs="24" :sm="24" :md="8" :lg="6">
                            <a-statistic
                                :title="$t('orders.total_amount')"
                                :value="formatPrice(formData.total_amount)"
                                style="text-align: right;"
                            />
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
import { defineComponent, watch } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";
import PhoneInput from "../../../common/components/common/input/PhoneInput.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "customers",
        "users",
        "products"
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        DeleteOutlined,
        DateTimePicker,
        PhoneInput,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        // Format price with 2 decimal places
        const formatPrice = (price) => {
            return parseFloat(price).toFixed(2);
        };

        // Add a new order item
        const addOrderItem = () => {
            props.formData.order_items.push({
                product_id: undefined,
                quantity: 1,
                price: 0
            });
        };

        // Remove an order item
        const removeOrderItem = (index) => {
            props.formData.order_items.splice(index, 1);
            calculateOrderTotal();
        };

        // When a product is selected, set its price
        const productSelected = (productId, index) => {
            const selectedProduct = props.products.find(product => product.id === productId);
            if (selectedProduct) {
                props.formData.order_items[index].price = selectedProduct.price;
                calculateItemTotal(props.formData.order_items[index]);
            }
        };

        // Calculate the total for an individual item
        const calculateItemTotal = (item) => {
            calculateOrderTotal();
        };

        // Calculate the total amount for the entire order
        const calculateOrderTotal = () => {
            let total = 0;
            props.formData.order_items.forEach(item => {
                total += item.quantity * item.price;
            });
            props.formData.total_amount = total;
        };

        // Watch for changes to order items and recalculate total
        watch(() => props.formData.order_items, () => {
            calculateOrderTotal();
        }, { deep: true });

        // Handle form submission
        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url + (props.addEditType === "add" ? "" : `/${props.formData.xid}`),
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
            addOrderItem,
            removeOrderItem,
            productSelected,
            calculateItemTotal,
            calculateOrderTotal,
            formatPrice,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
        };
    },
});
</script>

<style scoped>
.mt-20 {
    margin-top: 20px;
}
</style>
