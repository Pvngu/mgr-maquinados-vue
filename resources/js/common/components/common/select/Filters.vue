<template>
    <a-dropdown v-model:open="visible" :trigger="['click']">
        <a-button type="link">
            <template #icon>
                <FilterFilled class="filter-icon" />
            </template>
        </a-button>
        <template #overlay>
            <a-row class="filter-dropdown">
                <a-col :span="24">
                    <a-row justify="space-between" align="center">
                        <a-col>
                            <a-typography-title :level="5">Filters</a-typography-title>
                        </a-col>
                        <a-col @click="$emit('onReset')">
                            <a-button type="text" danger>
                                Reset
                            </a-button>
                        </a-col>
                    </a-row>
                </a-col>
                <a-col :span="24">
                    <a-form layout="vertical" class="mt-20">
                        <slot />
                    </a-form>
                </a-col>
            </a-row>
        </template>
    </a-dropdown>
</template>

<script>
import { FilterFilled } from '@ant-design/icons-vue';
import { ref } from 'vue';

export default {
    components: {
        FilterFilled
    },
    emits: ['onReset'],
    setup() {
        const visible = ref(false);
        return {
            visible
        }
    }
}
</script>

<style scoped>
.filter-icon {
    font-size: 1.1rem;
    color: #94a3b8;
}

.filter-icon:hover {
    color: #6b7280;
}

.filter-dropdown {
    padding: 1.5rem;
    width: 400px;
    border-radius: .5rem;
}
</style>