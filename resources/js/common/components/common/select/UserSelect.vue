<template>
    <a-select
        v-model:value="selectOption"
        :placeholder="$t('common.select_default_text', [$t('user.user')])"
        :allowClear="true"
        style="width: 100%"
        :dropdown-match-select-width="false"
        optionFilterProp="title"
        show-search
        @change="onChange"
        :disabled="disabled"
    >
        <a-select-opt-group v-for="( users, role ) in allUsers" :key="role" :label="role">
            <a-select-option
                v-for="user in users"
                :key="user.xid"
                :title="user.name"
                :value="user.xid"
                :disabled="disableDisabledUsers && user.status === 'disabled' || user.xid === currentUserId"
                :class="{ warningSelect: showDisabledUserWarning && user.assigned_sales_count > 0 && user.status === 'disabled' }"
            >
                <a-row>
                    <a-col>
                        <a-avatar :src="user.profile_image_url" :size="20" />
                    </a-col>
                    <a-col class="ml-5">
                        {{ user.name }} <span v-if="showAssignedSalesCount">({{ user.assigned_sales_count }})</span>
                    </a-col>
                </a-row>
            </a-select-option>
        </a-select-opt-group>
    </a-select>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from 'vue';

export default defineComponent({
    props: {
        value: {
            default: null
        },
        disabled: {
            default: false
        },
        showDisabledUserWarning: {
            default: false
        },
        showAssignedSalesCount: {
            default: false
        },
        disableDisabledUsers: {
            default: false
        },
        currentUserId: {
            default: undefined
        },
        fetchUserData: {
            default: true
        },
        data: {
            default: null
        }
    },
    setup(props, { emit }) {
        const usersUrl = 'all-users?log_type=staff_members';
        const allUsers = ref({});
        const selectOption = ref(null);

        const onChange = (id) => {
            emit('onChange', id);
        }

        onMounted(()=> {
            if(props.value) {
                selectOption.value = props.value;
            }

            if(props.fetchUserData) {
                axiosAdmin.get(usersUrl).then((res) => {
                    allUsers.value = res.data.users;
                })
            } else if (Object.keys(props.data).length > 0) {
                allUsers.value = props.data;
            }
        })

        // Reset select option when value is null
        watch(() => props.value, (newValue) => {
            if(newValue === null) {
                selectOption.value = newValue;
            }
        })

        watch(() => props.data, (newValue) => {
            if(newValue) {
                allUsers.value = newValue;
            }
        })

        return {
            allUsers,
            onChange,
            selectOption
        }
    }
});
</script>

<style>
    .warningSelect {
        background-color: #fffbe6;
        color: #faad14 !important
    }
</style>