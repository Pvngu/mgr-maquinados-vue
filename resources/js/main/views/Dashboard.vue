<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.dashboard`)" style="padding: 0px" />
        </template>
    </AdminPageHeader>

    <div class="dashboard-page-content-container">
        <a-row :gutter="[8, 8]" class="mt-30 mb-10">
            <a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
                <DateRangePicker
                    ref="serachDateRangePicker"
                    @dateTimeChanged="
                        (changedDateTime) => (filters.dates = changedDateTime)
                    "
                />
            </a-col>
        </a-row>

        <div class="mt-30 mb-20">
            <a-row :gutter="[15, 15]">
                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#08979c">
                        <template #image>
                            <CopyrightCircleOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2>

                            </h2>
                            <p>{{ $t("dashboard.") }}</p>
                        </template>
                    </StateWidget>
                </a-col>
            </a-row>
        </div>

        <a-row :gutter="[18, 18]" class="mt-30 mb-20">
        </a-row>

        <a-row :gutter="[18, 18]" class="mt-30 mb-20">
        </a-row>
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch } from "vue";
import {
    CopyrightCircleOutlined,
    MobileOutlined,
    ScheduleOutlined,
    ClockCircleOutlined,
    DoubleRightOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import common from "../../common/composable/common";
import AdminPageHeader from "../../common/layouts/AdminPageHeader.vue";
import StateWidget from "../../common/components/common/card/StateWidget.vue";
import DateRangePicker from "../../common/components/common/calendar/DateRangePicker.vue";

export default {
    components: {
        AdminPageHeader,
        StateWidget,
        DateRangePicker,

        MobileOutlined,
        CopyrightCircleOutlined,
        ClockCircleOutlined,
        ScheduleOutlined,
        DoubleRightOutlined,
    },
    setup() {
        const { formatTimeDuration } = common();
        const { t } = useI18n();
        const responseData = ref([]);
        const filters = reactive({
            dates: [],
        });

        onMounted(() => {
            getInitData();
        });

        const getInitData = () => {
            const dashboardPromise = axiosAdmin.post("dashboard", filters);

            Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
                responseData.value = dashboardResponse.data;
            });
        };

        watch([filters], (newVal, oldVal) => {
            getInitData();
        });

        return {
            formatTimeDuration,
            filters,
            responseData,
        };
    },
};
</script>

<style lang="less">
.ant-card-extra,
.ant-card-head-title {
    padding: 0px;
}

.ant-card-head-title {
    margin-top: 10px;
}
</style>
