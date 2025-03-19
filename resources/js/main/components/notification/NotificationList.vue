<template>
    <a-list item-layout="horizontal" :data-source="notifications" style="width: 100%;">
        <template #renderItem="{ item }">
            <a-list-item
                @click="{
                    if (item.type == 'signed_document') {
                        router.push({ name: 'admin.sales.details', params: { id: item.individual.sale.xid }, query: { tab: 'docs', doc_tab: 'esign' } });
                    }
                }"
                :class="{
                    'list-hover': item.type == 'signed_document',
                    'unread-item': item.pivot.read_at == null,
                    'no-flex': item.pivot.read_at !== null
                }"
            >
                <a-list-item-meta
                    :description="formatRelativeTime(item.created_at)"
                >
                    <template #title>
                        <span v-if="item.type == 'sale_transfer'">
                            {{ $t('notification.sale_transfer', [
                                item.sender.name,
                                item.extra
                            ]) }}
                        </span>
                        <span v-if="item.type == 'signed_document'">
                            {{ $t('notification.signed_document', [
                                item.individual.first_name + ' ' + item.individual.last_name,
                                item.document.title
                            ]) }}
                        </span>
                    </template>
                    <template #avatar>
                        <a-avatar v-if="item.sender" :src="item.sender.profile_image_url" />
                        <a-avatar v-else>
                            <BellOutlined />
                        </a-avatar>
                    </template>
                </a-list-item-meta>
                <template #actions>
                    <a-radio @click.stop="markAsRead(item)" 
                    v-if="item.pivot.read_at == null" />
                </template>
            </a-list-item>
        </template>
        <slot></slot>
    </a-list>
</template>

<script>
import common from '../../../common/composable/common';
import { BellOutlined } from '@ant-design/icons-vue';
import { useRouter } from 'vue-router';

export default {
    props: {
        notifications: {
            default: {}
        },
        unreadNotifications: {
            default: 0
        }
    },
    components: {
        BellOutlined
    },
    setup(props, { emit }) {
        const { formatRelativeTime } = common();

        const markAsRead = (item) => {
            axiosAdmin.post(`app-notifications/${item.pivot.id}/mark-as-read`).then((res) => {
                if(res.data.success) {
                    item.pivot.read_at = new Date();
                    emit('markAsRead');
                }
            });
        }

        const markAllAsRead = () => {
            if(props.unreadNotifications.value !== 0)
                axiosAdmin.post(`app-notifications/mark-all-as-read`).then((res) => {
                    if(res.data.success) {
                        props.notifications.forEach((item) => {
                            item.pivot.read_at = new Date();
                        });
                        emit('markAllAsRead');
                    }
            });
        }

        const router = useRouter();
        
        return {
            formatRelativeTime,
            markAsRead,
            markAllAsRead,
            router
        };
    },
};
</script>