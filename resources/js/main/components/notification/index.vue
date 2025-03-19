<template>
    <a-dropdown :trigger="['click']" placement="bottomRight">
        <template #overlay>
            <a-row class="notification-content">
                <a-row justify="space-between" class="pt-10 pb-10 px-8" style="width: 100%;">
                    <a-col>
                        <a-typography-title :level="5">
                            {{ $t('notification.notifications') }}
                        </a-typography-title>
                    </a-col>
                    <a-col>
                        <a-button
                            type="text"
                            v-if="unreadNotifications !== 0" @click.stop="$refs.notificationList.markAllAsRead()"
                        >
                            {{ $t('notification.mark_all_as_read') }}
                        </a-button>
                        <a-divider class="mb-0 mt-10" />
                    </a-col>
                </a-row>
                <NotificationList 
                    :notifications="notifications.slice(0, 10)"
                    @onClose = "visible = false"
                    :unreadNotifications = "unreadNotifications"
                    @markAllAsRead = "unreadNotifications = 0"
                    @markAsRead = "unreadNotifications--"
                    ref="notificationList"
                >
                    <a-row v-if="notifications.length !== 0" style="width: 100%;" justify="center" class="pt-10 pb-10">
                        <a-divider class="mt-0" />
                        <a-col>
                            <a-button
                                @click="visible = true"
                            >
                            {{ $t('notification.view_all_notifications') }}
                            </a-button>
                        </a-col>
                    </a-row>
                </NotificationList>
            </a-row>
        </template>
        <a-badge :count="unreadNotifications">
            <a-button type="text">
                <template #icon>
                    <BellOutlined style="font-size: 1.1rem;"/>
                </template>
            </a-button>
        </a-badge>
    </a-dropdown>
    <ShowNotifications
        :notifications="notifications"
        :unreadNotifications="unreadNotifications"
        :currentPage="currentPage"
        :visible="visible"
        :loadMoreVisible="loadMoreVisible"
        @close="visible = false"
        @markAllAsRead = "unreadNotifications = 0"
        @markAsRead = "unreadNotifications--"
        @fetchMoreNotifications = "fetchMoreNotifications"
    />
</template>

<script>
import { ref, onMounted } from 'vue';
import { BellOutlined } from '@ant-design/icons-vue';
import ShowNotifications from './showNotifications.vue';
import NotificationList from './NotificationList.vue';
import common from '../../../common/composable/common';

export default {
    components: {
        BellOutlined,
        ShowNotifications,
        NotificationList
    },
    setup() {
        const visible = ref(false);
        const { user} = common();
        const notifications = ref([]);
        const unreadNotifications = ref(0);
        const currentPage = ref(1);
        const loadMoreVisible = ref(false);

        onMounted(() => {
            fetchInitData();
        });

        const fetchInitData = () => {
            // const notificationUrl = `users/notifications`;
            // const notificationPromise = axiosAdmin.get(notificationUrl);
            // const unreadNotificationsPromise = axiosAdmin.get(`app-notifications/unread`);
            // Promise.all([notificationPromise, unreadNotificationsPromise]).then(([res, unread]) => {
                // notifications.value = res.data.data;
                // currentPage.value = res.data.current_page;
                // unreadNotifications.value = unread.data;

                // if(res.data.current_page < res.data.last_page) {
                    // loadMoreVisible.value = true;
                // }
            // });
        }

        const fetchMoreNotifications = () => {
            const page = currentPage.value + 1;
            axiosAdmin.get(`users/notifications?page=${page}`).then((res) => {
                if(res.data) {
                    currentPage.value = res.data.current_page;
                    notifications.value.push(...res.data.data);
                }

                if(currentPage.value === res.data.last_page) {
                    loadMoreVisible.value = false;
                }
            });
        }

        return {
            visible,
            notifications,
            unreadNotifications,
            currentPage,
            fetchMoreNotifications,
            loadMoreVisible
        }
    }
}
</script>

<style>
.notification-content {
    width: 400px;
    max-height: 600px; 
    overflow-y: scroll;
}

.list-hover {
    transition: all 400ms ease;
}

.list-hover:hover {
    cursor: pointer;
    background: #f0f0f0;
}

.unread-item {
    background: #e6f7ff;
    padding-right: 0 !important;
}

.unread-item .ant-list-item-action {
    margin: 0 !important;
}

.no-flex {
    display: list-item !important;
}
</style>