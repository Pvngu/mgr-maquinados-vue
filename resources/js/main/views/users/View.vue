<template>
    <a-drawer
        :title="user.name"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :maskClosable="false"
        @close="onClose"
    >
        <div class="user-details">
            <a-row class="mb-40" :gutter="[16, 16]" v-if="timeTaken.total > 0 || timeTaken.last_month > 0 || timeTaken.this_month > 0 || timeTaken.last_week > 0 || timeTaken.this_week > 0">
                <a-col :span="24">
                    <a-typography-title :level="5">
                        {{ $t("user.call_log") }}
                    </a-typography-title>
                    <a-space
                        :wrap="true"
                        align="center"
                    >
                        <a-card v-if="timeTaken.total">
                            <a-statistic
                                title="Total"
                                :value="formatSeconds(timeTaken.total)"
                            />
                        </a-card>
                        <a-card v-if="timeTaken.today > 0">
                            <a-statistic
                                title="Today"
                                :value="formatSeconds(timeTaken.today)"
                            />
                        </a-card>
                        <a-card v-if="timeTaken.this_month > 0">
                            <a-statistic
                                title="This Month"
                                :value="formatSeconds(timeTaken.this_month)"
                            />
                        </a-card>
                        <a-card v-if="timeTaken.this_week > 0">
                            <a-statistic
                                title="This Week"
                                :value="formatSeconds(timeTaken.this_week)"
                            />
                        </a-card>
                    </a-space>
                </a-col>
            </a-row>
            <a-row :gutter="[16, 16]">
                <a-col :xs="24" :sm="24" :md="4" :lg="4">
                    <a-image :src="user.profile_image_url" />
                </a-col>
                <a-col :xs="24" :sm="24" :md="20" :lg="20">
                    <a-descriptions
                        :title="$t(`user.staff_members_details`)"
                        layout="vertical"
                        :contentStyle="{ fontWeight: 500, marginBottom: '20px' }"
                    >
                        <a-descriptions-item :label="$t('user.name')">
                            {{ user.name }}
                        </a-descriptions-item>
                        <a-descriptions-item :label="$t('user.email')">
                            {{ user.email }}
                        </a-descriptions-item>
                        <a-descriptions-item :label="$t('user.phone')">
                            {{ user.phone ? user.phone : "-" }}
                        </a-descriptions-item>
                        <a-descriptions-item
                            :label="$t('user.address')"
                            :span="2"
                        >
                            {{ user.address ? user.address : "-" }}
                        </a-descriptions-item>
                        <a-descriptions-item
                            :label="$t('user.credit_period')"
                            v-if="user.details"
                        >
                            {{
                                user.details.credit_period
                                    ? `${user.details.credit_period} day(s)`
                                    : "-"
                            }}
                        </a-descriptions-item>
                        <a-descriptions-item
                            :label="$t('user.credit_limit')"
                            v-if="user.details"
                        >
                            {{
                                user.details.credit_limit
                                    ? formatAmountCurrency(user.details.credit_limit)
                                    : "-"
                            }}
                        </a-descriptions-item>
                    </a-descriptions>
                </a-col>
            </a-row>
        </div>
    </a-drawer>
</template>
<script>
import { defineComponent, ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import common from "../../../common/composable/common";

export default defineComponent({
    props: ["user", "visible"],
    emits: ["closed"],
    setup(props, { emit }) {
        const { formatAmountCurrency, formatSeconds } = common();
        const { t } = useI18n();
        const timeTaken = ref({
            total: 0,
            today: 0,
            this_month: 0,
            this_week: 0,
        });

        const onClose = () => {
            emit("closed");
        };

        const fetchData = () => {
            axiosAdmin.get(`user/${props.user.xid}/call-log`).then((response) => {
                timeTaken.value.total = response.total_time_taken;
                timeTaken.value.today = response.time_taken_today;
                timeTaken.value.this_week = response.time_taken_this_week;
                timeTaken.value.this_month = response.time_taken_this_month;
            });
        };

        watch(() => props.user, (value) => {
            if (props.user && props.user.xid) {
                fetchData();
            }
        });

        return {
            formatAmountCurrency,
            formatSeconds,
            onClose,
            timeTaken,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
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
