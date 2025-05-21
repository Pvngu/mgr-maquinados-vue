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

        <!-- Stats Cards -->
        <div class="mt-30 mb-20">
            <a-row :gutter="[15, 15]">
                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#1890ff">
                        <template #image>
                            <ShoppingCartOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2>
                                {{ responseData.totalOrders || 0 }}
                            </h2>
                            <p>{{ $t("dashboard.total_orders") }}</p>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#52c41a">
                        <template #image>
                            <DollarOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2>
                                {{ formatCurrency(responseData.totalRevenue || 0) }}
                            </h2>
                            <p>{{ $t("dashboard.total_revenue") }}</p>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#722ed1">
                        <template #image>
                            <TeamOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2>
                                {{ responseData.totalClients || 0 }}
                            </h2>
                            <p>{{ $t("dashboard.total_clients") }}</p>
                        </template>
                    </StateWidget>
                </a-col>
                <a-col :xs="24" :sm="24" :md="6" :lg="6" :xl="6">
                    <StateWidget bgColor="#fa8c16">
                        <template #image>
                            <AppstoreOutlined
                                style="color: #fff; font-size: 24px"
                            />
                        </template>
                        <template #description>
                            <h2>
                                {{ responseData.totalProducts || 0 }}
                            </h2>
                            <p>{{ $t("dashboard.total_products") }}</p>
                        </template>
                    </StateWidget>
                </a-col>
            </a-row>
        </div>

        <!-- Charts and Tables -->
        <!-- <a-row :gutter="[18, 18]" class="mt-30 mb-20">
            <a-col :xs="24" :sm="24" :md="12" :lg="16" :xl="16">
                <a-card :title="$t('dashboard.sales_chart')" :bordered="false">
                    <div style="height: 350px">
                        <Line 
                            :data="chartData" 
                            :options="chartOptions" 
                        />
                    </div>
                </a-card>
            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                <a-card :title="$t('dashboard.stock_alert')" :bordered="false">
                    <div class="stock-alert-container">
                        <a-alert
                            v-if="responseData.lowStockProducts > 0"
                            message=""
                            :description="`${responseData.lowStockProducts} ${$t('dashboard.products_low_stock')}`"
                            type="warning"
                            show-icon
                        />
                        <a-empty v-else :description="$t('dashboard.no_stock_alerts')" />
                    </div>
                </a-card>
            </a-col>
        </a-row> -->

        <!-- Recent Orders -->
        <a-row :gutter="[18, 18]" class="mt-20 mb-20">
            <a-col :span="24">
                <a-card :title="$t('dashboard.recent_orders')" :bordered="false">
                    <a-table
                        :columns="orderColumns"
                        :data-source="responseData.recentOrders || []"
                        :pagination="false"
                        :loading="loading"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.key === 'client'">
                                {{ record.client ? record.client.name : '' }}
                            </template>
                            <template v-else-if="column.key === 'amount'">
                                {{ formatCurrency(record.total_amount) }}
                            </template>
                            <template v-else-if="column.key === 'date'">
                                {{ formatDate(record.order_date) }}
                            </template>
                            <template v-else-if="column.key === 'action'">
                                <a-button
                                    type="primary"
                                    @click="exportPdfOrder(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><FilePdfOutlined /></template>
                                </a-button>
                            </template>
                        </template>
                    </a-table>
                </a-card>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { onMounted, reactive, ref, watch, nextTick } from "vue";
import {
    ShoppingCartOutlined,
    DollarOutlined,
    TeamOutlined,
    AppstoreOutlined,
    EyeOutlined,
    AlertOutlined,
    FilePdfOutlined
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useRouter } from 'vue-router';
import common from "../../common/composable/common";
import AdminPageHeader from "../../common/layouts/AdminPageHeader.vue";
import StateWidget from "../../common/components/common/card/StateWidget.vue";
import DateRangePicker from "../../common/components/common/calendar/DateRangePicker.vue";
import { Line } from 'vue-chart-3';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

export default {
    components: {
        AdminPageHeader,
        StateWidget,
        DateRangePicker,
        ShoppingCartOutlined,
        DollarOutlined,
        TeamOutlined,
        AppstoreOutlined,
        EyeOutlined,
        AlertOutlined,
        FilePdfOutlined,
        Line,
    },
    setup() {
        const { formatTimeDuration } = common();
        const { t } = useI18n();
        const router = useRouter();
        const responseData = ref({});
        const filters = reactive({
            dates: [],
        });
        const loading = ref(false);
        const chartData = ref({
            labels: [],
            datasets: [
                {
                    label: 'Sales',
                    data: [],
                    backgroundColor: 'rgba(24, 144, 255, 0.2)',
                    borderColor: '#1890ff',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                }
            ]
        });
        const chartOptions = ref({
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value, true);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return formatCurrency(context.parsed.y);
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        });

        // Order table columns
        const orderColumns = [
            {
                title: t('dashboard.order_id'),
                dataIndex: 'id',
                key: 'id',
                sorter: true,
            },
            {
                title: t('dashboard.client'),
                dataIndex: 'client',
                key: 'client',
            },
            {
                title: t('dashboard.amount'),
                dataIndex: 'total_amount',
                key: 'amount',
                sorter: true,
            },
            {
                title: t('dashboard.date'),
                dataIndex: 'order_date',
                key: 'date',
                sorter: true,
            },
            {
                title: t('common.action'),
                key: 'action',
            },
        ];

        onMounted(() => {
            getInitData();
        });

        const getInitData = () => {
            loading.value = true;
            const dashboardPromise = axiosAdmin.post("dashboard", filters);

            Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
                responseData.value = dashboardResponse.data;
                loading.value = false;
                nextTick(() => {
                    initSalesChart();
                });
            }).catch(() => {
                loading.value = false;
            });
        };

        const initSalesChart = () => {
            if (!responseData.value.salesByDay) return;
            
            const dates = [];
            const amounts = [];
            
            responseData.value.salesByDay.forEach(item => {
                dates.push(formatDate(item.date));
                amounts.push(item.amount);
            });
            
            // Update chart data
            chartData.value.labels = dates;
            chartData.value.datasets[0].data = amounts;
            chartData.value.datasets[0].label = t('dashboard.sales');
        };

        const formatCurrency = (amount, abbreviated = false) => {
            if (amount === null || amount === undefined) return '-';
            
            // You might want to adjust this based on your locale and currency
            if (abbreviated && amount >= 1000) {
                return '$' + (amount / 1000).toFixed(1) + 'k';
            }
            
            return '$' + amount.toLocaleString();
        };

        const formatDate = (dateString) => {
            if (!dateString) return '-';
            
            const date = new Date(dateString);
            return date.toLocaleDateString();
        };

        const viewOrder = (record) => {
            if (record && record.xid) {
                router.push({ name: 'admin.orders.view', params: { id: record.xid } });
            }
        };

        // PDF export function
        const exportPdfOrder = async (orderXid) => {
            try {
            const response = await axiosAdmin.get(`orders/${orderXid}/export-pdf`, {
                responseType: 'blob'
            });
            const url = window.URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            window.open(url, '_blank');
            } catch (error) {
            // handle error if needed
            }
        };

        watch([filters], (newVal, oldVal) => {
            getInitData();
        });

        return {
            formatTimeDuration,
            formatCurrency,
            formatDate,
            filters,
            responseData,
            loading,
            orderColumns,
            viewOrder,
            chartData,
            chartOptions,
            exportPdfOrder,
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

/* Make sure the chart container works properly with Chart.js */
canvas {
    max-width: 100%;
    height: 100% !important;
}
</style>
