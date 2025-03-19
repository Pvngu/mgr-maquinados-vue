<template>
    <a-select
        v-model:value="searchValue"
        :placeholder="
            $t('common.select_default_text', [
            $t('debt.original_creditor'),
            ])
        "
        :filter-option="false"
        showSearch
        :not-found-content="null"
        @search="fetchCreditor"
        style="max-width: 84%;"
        @select="$emit('optionChanged', $event)"
    >
        <template v-if="table.loading" #notFoundContent>
            <a-spin size="small" />
        </template>
        <a-select-option
            v-for="item in table.data"
            :key="item.id"
        >
            {{ item.name }}
        </a-select-option>
    </a-select>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import crud from '../../../common/composable/crud';
import { useI18n } from 'vue-i18n';

export default {
    props: {
        selectedCreditor: {
            default: {
                id: undefined,
                name: undefined,
            },
        }
    },
    emits: ['optionChanged'],
    setup(props) {
        const searchValue = ref(undefined);
        const { t } = useI18n();
        const crudVariables = crud();
        const url = 'creditors?fields=id,name';

        const filterableColumns = [
            {
                key: 'name',
                value: t('common.name')
            },
        ];

        onMounted(() => {
            crudVariables.tableUrl.value = {
                url,
            };

            crudVariables.table.filterableColumns = filterableColumns;
        });

        const fetchInitData = (id) => {
            const filters = {
                id: id
            }

            crudVariables.tableUrl.value = {
                url,
                filters
            };

            crudVariables.fetch({
                page: 1,
            });
        }

        const fetchCreditor = (value) => {
            crudVariables.table.searchString = value;
            crudVariables.onTableSearch();
        }

        watch(() => props.selectedCreditor, (newValue) => {
            if(newValue.id !== undefined) {
                crudVariables.table.data = [
                    {
                        id: newValue.id,
                        name: newValue.name,
                    }
                ]
                searchValue.value = newValue.id;
            } else {
                searchValue.value = undefined;
            }
        }, { immediate: true });

        return {
            ...crudVariables,
            fetchCreditor,
            searchValue,
        }
    }
}
</script>