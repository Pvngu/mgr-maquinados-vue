<template>
    <a-drawer
        :title="$t('debt.creditor_details')"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :maskClosable="false"
        @close="onClose"
    >
        <a-typography-title :level="4">
            {{ creditor.name }}
        </a-typography-title>
        <a-typography-title :level="5">
            {{ $t("common.details") }}
        </a-typography-title>
        <a-descriptions :column="2">
            <a-descriptions-item :label="$t('lead.first_name')">
            {{ creditor.first_name }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.last_name')">
            {{ creditor.last_name }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.phone_number')">
            {{ creditor.phone_number }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.email')">
            {{ creditor.email }}
            </a-descriptions-item>
        </a-descriptions>

        <a-typography-title :level="5" class="mt-20">
            {{ $t("common.address") }}
        </a-typography-title>
        <a-descriptions :column="2">
            <a-descriptions-item :label="$t('common.address')">
            {{ creditor.address_line1 }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.address_line_2')">
            {{ creditor.address_line2 }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.city')">
            {{ creditor.city }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.state')">
            {{ creditor.state ? creditor.state.name : '-' }}
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.zip_code')">
            {{ creditor.zip_code }}
            </a-descriptions-item>
        </a-descriptions>
    </a-drawer>
</template>
<script>
import { defineComponent } from "vue";
import ActivityLogTable from "../../components/activity-log/index.vue";

export default defineComponent({
    props: ["creditor", "visible"],
    emits: ["closed"],
    components: {
        ActivityLogTable,
    },
    setup(props, { emit }) {
        
        const onClose = () => {
            emit("closed");
        };

        return {
            onClose,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "40%",
        };
    },
});
</script>

<style lang="less">
.user-details {
    .ant-descriptions-item {
        padding-bottom: 5px;
    }
}
</style>
