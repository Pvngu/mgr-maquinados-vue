<!--
     MARK: Model
-->

When user wanst a "Full backend" it means you need to generate model, controller, migration, request and route

For .php files don't forget to add `<?php` at the beginning 

# Instructions for Creating a New Model

When creating a new model, ensure the following:

1. **Extend BaseModel**:

    ```php
    class ModelName extends BaseModel
    {
        // ...existing code...
    }
    ```

2. **Include `$table` property**:

    ```php
    protected $table = 'table_name';
    ```

3. **Hide ID and foreign key columns in `$hidden` property**:

    ```php
    protected $hidden = ['id', 'foreign_key_column'];
    ```

4. **Append hashid columns for foreign keys in `$appends` property**:

    ```php
    protected $appends = ['xid', 'x_foreign_key_column'];
    ```

5. **Include `$filterable` property and leave the array empty**:

    ```php
    protected $filterable = [];
    ```

6. **Include `$hashableGetterFunctions` for foreign key columns**:

    ```php
    protected $hashableGetterFunctions = [];
    ```

7. **Include `$casts` property**:
    ```php
    protected $casts = [];
    ```
8. **Include `boot` method**:

    ```php
    protected static function boot()
    {
        parent::boot();
    }
    ```

9. **Include relations if needed**

Example:

```php
namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExampleModel extends BaseModel
{
    use HasFactory;

    protected $table = 'example_table';

    protected $hidden = ['id', 'category_id'];

    protected $appends = ['xid', 'x_category_id'];

    protected $filterable = [];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
```

<!--
     MARK: Controller
-->

# Instructions for Creating a New Controller

When creating a new controller, ensure the following:

1. **Extend ApiBaseController**:

    ```php
    class ControllerName extends ApiBaseController
    {
        // ...existing code...
    }
    ```

2. **Create `modifyIndex` function**:

    ```php
    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
    ```

3. **Include the controller file in the `Api` folder within `Controllers`**:
    ```php
    // Example path: app/Http/Controllers/Api/ExampleModelController.php
    ```

Example:

```php
namespace App\Http\Controllers\Api;

use App\Models\ExampleModel;
use App\Http\Controllers\ApiBaseController;

class ExampleModelController extends ApiBaseController
{
    protected $model = ExampleModel::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
```

<!--
     MARK: Request
-->

# Instructions for Creating a New Request

When creating a new request, ensure the following:

1. **Extend BaseRequest**:

    ```php
    class RequestName extends BaseRequest
    {
        // ...existing code...
    }
    ```

2. **Include `authorize` method**:

    ```php
    public function authorize()
    {
        return true;
    }
    ```

3. **Include `rules` method**:

    ```php
    public function rules()
    {
        return [
            // ...validation rules...
        ];
    }
    ```

4. **Create request files in the appropriate folder**:

    - `Requests/Api/ModelName/IndexRequest.php`
    - `Requests/Api/ModelName/StoreRequest.php`
    - `Requests/Api/ModelName/UpdateRequest.php`
    - `Requests/Api/ModelName/DeleteRequest.php`

5. **Define properties for the model and request classes in modelController**:
    ```php
    protected $model = ModelName::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;
    ```

Example:

```php
namespace App\Http\Requests\Api\ModelName;

use App\Http\Requests\Api\BaseRequest;

class ExampleRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }
}
```

```php
namespace App\Http\Controllers\Api;

//existing imports
use App\Http\Requests\Api\ExampleModel\StoreRequest;
use App\Http\Requests\Api\ExampleModel\UpdateRequest;
use App\Http\Requests\Api\ExampleModel\DeleteRequest;
use App\Http\Requests\Api\ExampleModel\IndexRequest;

class ExampleModelController extends ApiBaseController
{
protected $model = ExampleModel::class;
protected $indexRequest = IndexRequest::class;
protected $storeRequest = StoreRequest::class;
protected $updateRequest = UpdateRequest::class;
protected $deleteRequest = DeleteRequest::class;

//existing code
}
``` 

<!--
    MARK: Route
-->

# Instructions for Adding a Resource Route in web.php

When adding a resource route in `routes/web.php`, ensure the following:

1. **Use the `ApiRoute::resource` method**:

    ```php
    ApiRoute::resource('resource_name', 'ResourceController', $options);
    ```

2. **Replace `resource_name` with the desired resource name**.
3. **Replace `ResourceController` with the appropriate controller name**.
3. **Do not forget to add `$options`**

Example:

```php
ApiRoute::group(['middleware' => ['api.permission.check', 'api.auth.check', 'license-expire']], function () {
    //...existing routes...
    ApiRoute::resource('forms', 'FormController', $options);
});
```

# When creating a new view do the following in `resources/js/main/views`

when user wants a "Full View" it means you also going to generate the route, perms, leftsidebar menu-item and translations  

<!--
     MARK: Fields.js
-->

## Instructions for Creating a New Fields File

When creating a new fields file, ensure the following:

1. **Import necessary modules**:

    ```javascript
    import { useI18n } from "vue-i18n";
    import { ref } from "vue";
    ```

2. **Define the `fields` function**:

    ```javascript
    const fields = () => {
        // ...existing code...
    };
    ```

3. **Define the `url` and `addEditUrl` constants**:

    model_name must be in plural

    ```javascript
    const url = "model_name?fields=id,xid,other_fields";
    const addEditUrl = "model_name";
    ```

4. **Use the `useI18n` hook to get the translation function**:

    ```javascript
    const { t } = useI18n();
    ```

5. **Define the `hashableColumns` array**:

    ```javascript
    const hashableColumns = ["foreign_key_column"];
    ```

6. **Define the `initData` object**:

    ```javascript
    const initData = {
        other_fields: "",
        foreign_key_column: undefined,
    };
    ```

7. **Define the `columns` array**:

    ```javascript
    const columns = [
        {
            title: t("common.other_field"),
            dataIndex: "other_field",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];
    ```

8. **Define the `filterableColumns` array**:

    ```javascript
    const filterableColumns = [
        {
            key: "other_field",
            value: t("common.other_field"),
        },
    ];
    ```

9. **Return the defined variables and functions**:
    ```javascript
    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
    };
    ```

Example:

```javascript
import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "example_model?fields=id,xid,name,other_fields";
    const addEditUrl = "example_model";
    const { t } = useI18n();
    const hashableColumns = ["foreign_key_column"];

    const initData = {
        name: "",
        other_fields: "",
        foreign_key_column: undefined,
    };

    const columns = [
        {
            title: t("common.name"),
            dataIndex: "name",
        },
        {
            title: t("common.other_field"),
            dataIndex: "other_field",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("common.name"),
        },
        {
            key: "other_field",
            value: t("common.other_field"),
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
    };
};

export default fields;
```

<!--
     MARK: AddEdit.vue
-->

## Instructions for Creating a New AddEdit.vue File

When creating a new AddEdit.vue file, ensure the following:

1. **Use the `a-drawer` component for the drawer layout**:

    ```vue
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
    >
         <!-- Form content goes here -->
    </a-drawer>
    ```

2. **Use the `a-form` component for the form layout**:

    ```vue
    <a-form layout="vertical">
         <!-- Form items go here -->
    </a-form>
    ```

3. **Define form items using `a-form-item` and `a-input` components. Add up to two `a-form-item` per row using `a-col` within `a-row`**:

    ```vue
    <a-row :gutter="16">
          <a-col :xs="24" :sm="24" :md="12" :lg="12">
               <a-form-item
                    :label="$t('model.field_name')"
                    name="field_name"
                    :help="rules.field_name ? rules.field_name.message : null"
                    :validateStatus="rules.field_name ? 'error' : null"
                    class="required"
               >
                    <a-input
                         v-model:value="formData.field_name"
                         :placeholder="$t('common.placeholder_default_text', [$t('model.field_name')])"
                    />
               </a-form-item>
          </a-col>
          <a-col :xs="24" :sm="24" :md="12" :lg="12">
               <a-form-item
                    :label="$t('model.field_name')"
                    name="field_name"
                    :help="rules.field_name ? rules.field_name.message : null"
                    :validateStatus="rules.field_name ? 'error' : null"
                    class="required"
               >
                    <a-input
                         v-model:value="formData.field_name"
                         :placeholder="$t('common.placeholder_default_text', [$t('model.field_name')])"
                    />
               </a-form-item>
          </a-col>
     </a-row>
    ```

4. **Include footer buttons for submit and cancel actions**:

    ```vue
    <template #footer>
        <a-button
            type="primary"
            @click="onSubmit"
            style="margin-right: 8px"
            :loading="loading"
        >
            <template #icon> <SaveOutlined /> </template>
            {{
                addEditType == "add" ? $t("common.create") : $t("common.update")
            }}
        </a-button>
        <a-button @click="onClose">
            {{ $t("common.cancel") }}
        </a-button>
    </template>
    ```

5. **Import necessary modules and components**:

    ```javascript
    import { defineComponent } from "vue";
    import {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
    } from "@ant-design/icons-vue";
    import apiAdmin from "../../../common/composable/apiAdmin";
    ```

6. **Define props and components in the `defineComponent` function**:

    ```javascript
    export default defineComponent({
        props: [
            "formData",
            "data",
            "visible",
            "url",
            "addEditType",
            "pageTitle",
            "successMessage",
        ],
        components: {
            PlusOutlined,
            LoadingOutlined,
            SaveOutlined,
        },
        setup(props, { emit }) {
            // Setup code goes here
        },
    });
    ```

7. **Define the `setup` function and necessary methods**:

    ```javascript
    setup(props, { emit }) {
         const { addEditRequestAdmin, loading, rules } = apiAdmin();

         const onSubmit = () => {
              addEditRequestAdmin({
                    url: props.url,
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
              drawerWidth: window.innerWidth <= 991 ? "90%" : "40%",
         };
    }
    ```

Example:

```vue
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
                                :label="$t('model.field_name')"
                                name="field_name"
                                :help="
                                    rules.field_name
                                        ? rules.field_name.message
                                        : null
                                "
                                :validateStatus="
                                    rules.field_name ? 'error' : null
                                "
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.field_name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('model.field_name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                        <a-col :xs="24" :sm="24" :md="12" :lg="12">
                            <a-form-item
                                :label="$t('model.field_name')"
                                name="field_name"
                                :help="
                                    rules.field_name
                                        ? rules.field_name.message
                                        : null
                                "
                                :validateStatus="
                                    rules.field_name ? 'error' : null
                                "
                                class="required"
                            >
                                <a-input
                                    v-model:value="formData.field_name"
                                    :placeholder="
                                        $t('common.placeholder_default_text', [
                                            $t('model.field_name'),
                                        ])
                                    "
                                />
                            </a-form-item>
                        </a-col>
                    </a-row>
                    // Add as many rows as necessary
                    <a-row :gutter="16"> </a-row>
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
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
} from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
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
            drawerWidth: window.innerWidth <= 991 ? "90%" : "40%",
        };
    },
});
</script>
```

## Available Form Items

### a-select

```vue
<a-select
    style="width: 100%"
    v-model:value="formData.select_field"
    show-search
    optionFilterProp="title"
    :placeholder="$t('common.select_default_text', [$t('model.select_field')])"
>
    <a-select-option
        v-for="option in options"
        :key="option.id"
        :title="option.name"
        :value="option.id"
    >
        {{ option.name }}
    </a-select-option>
</a-select>
```

### a-input

```vue
<a-input
    v-model:value="formData.input_field"
    :placeholder="$t('common.placeholder_default_text', [$t('model.input_field')])"
/>
```

### a-textarea

```vue
<a-textarea
    v-model:value="formData.input_field"
    :placeholder="$t('common.placeholder_default_text', [$t('model.input_field')])"
    :auto-size="{ minRows: 4, maxRows: 6 }"
/>
```

### a-select

```vue
<a-select
    v-model:value="extraFilters.model_id" 
    :placeholder="$t('common.select_default_text', [$t('model.select_field')])"
    :allowClear="true"
    style="width: 100%"
    optionFilterProp="title"
    show-search
    @change="setUrlData"
>
    <a-select-option
        v-for="model in modelPlural"
        :key="model.xid"
        :title="model.name"
        :value="model.xid"
    >
        {{ model.name }}
    </a-select-option>
</a-select>
```

### UserSelect

```vue
<UserSelect
    @onChange="(id) => { formData.user_id = id; }"
    :value="formData.user_id"
    mode="multiple"
/>
```

### DateTimePicker

```vue
<DateTimePicker
    :dateTime="formData.date_time"
    :isFutureDateDisabled="false"
    :showTime="false"
    :onlyDate="true"
    @dateTimeChanged="(changedDateTime) => { formData.date_time = changedDateTime; }"
/>
```

### Year picker
```vue
<YearPicker
    :disabled="loading"
    :year="formData.year"
    @yearChanged="
        (changedYear) =>
            (formData.year = changedYear)
    "
/>
```

### UploadFileBig

Do not forget to import it and register the component

```vue
<UploadFileBig
    :acceptFormat="'image/*,.pdf'"
    :formData="formData"
    folder="models"
    uploadField="file"
    @onFileUploaded="
        (file) => {
            formData.file = file.file;
            formData.file_url = file.file_url;
        }
    "
/>
```

```javascript
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";
```

### a-radio-group

```vue
<a-radio-group
    button-style="solid"
    size="small"
    v-model:value="formData.input_field"
>
    <a-radio value="check">
        {{ $t("model.option-1") }}
    </a-radio>
    <a-radio value="debit">
        {{ $t("model.option-2") }}
    </a-radio>
</a-radio-group>
```

<!--
     MARK: Index.vue
-->

## Instructions for Creating a New Index File

When creating a new index file, ensure the following:

1. **Use the `AdminPageHeader` component**:

    ```vue
    <AdminPageHeader>
         <template #header>
              <a-page-header :title="$t(`menu.model_name`)" class="p-0" />
         </template>
         <template #actions>
              <a-space>
                    <template v-if="permsArray.includes('model_name_create') || permsArray.includes('admin')">
                         <a-button type="primary" @click="addItem">
                              <PlusOutlined />
                              {{ $t("model_name.add") }}
                         </a-button>
                    </template>
                    <a-button v-if="table.selectedRowKeys.length > 0 && (permsArray.includes('model_name_delete') || permsArray.includes('admin'))" type="primary" @click="showSelectedDeleteConfirm" danger>
                         <template #icon><DeleteOutlined /></template>
                         {{ $t("common.delete") }}
                    </a-button>
              </a-space>
         </template>
         <template #breadcrumb>
              <a-breadcrumb separator="-" style="font-size: 12px">
                    <a-breadcrumb-item>
                         <router-link :to="{ name: 'admin.dashboard.index' }">
                              {{ $t(`menu.dashboard`) }}
                         </router-link>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>
                         {{ $t(`menu.model_name`) }}
                    </a-breadcrumb-item>
              </a-breadcrumb>
         </template>
    </AdminPageHeader>
    ```

2. **Use the `admin-page-table-content` component**:

    ```vue
    <admin-page-table-content>
         <AddEdit
              :addEditType="addEditType"
              :visible="addEditVisible"
              :url="addEditUrl"
              @addEditSuccess="addEditSuccess"
              @closed="onCloseAddEdit"
              :formData="formData"
              :data="viewData"
              :pageTitle="pageTitle"
              :successMessage="successMessage"
         />
         <!-- Add table, filters and other components here -->
    </admin-page-table-content>
    ```

3. **Include necessary imports and setup**:

    ```vue
    <script>
    import { ref, onMounted } from "vue";
    import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
    import { PlusOutlined, DeleteOutlined } from "@ant-design/icons-vue";
    import crud from "../../../common/composable/crud";
    import common from "../../../common/composable/common";
    import fields from "./fields";
    import AddEdit from "./AddEdit.vue";

    export default {
        components: {
            PlusOutlined,
            DeleteOutlined,
            AdminPageHeader,
            AddEdit,
        },
        setup() {
            const {
                url,
                addEditUrl,
                initData,
                columns,
                filterableColumns,
                hashableColumns,
            } = fields();
            const { permsArray } = common();
            const crudVariables = crud();

            onMounted(() => {
                crudVariables.table.filterableColumns = filterableColumns;

                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "model_name";
                crudVariables.initData.value = { ...initData };
                crudVariables.formData.value = { ...initData };

                setUrlData();
            });

            const setUrlData = () => {
                crudVariables.tableUrl.value = {
                    url,
                };

                crudVariables.hashableColumns.value = [...hashableColumns];

                crudVariables.fetch({
                    page: 1,
                });
            };

            return {
                ...crudVariables,
                permsArray,
                columns,
                filterableColumns,
                setUrlData,
            };
        },
    };
    </script>
    ```

4. **Define filters structure if it is required**:

    ```vue
    <a-row class="mt-20">
            <a-col :span="24">
                <a-tabs
                    v-model:activeKey="filters.status" 
                    @change="setUrlData" 
                    centered
                    type="card"
                    class="table-tab-filters"
                >
                    <a-tab-pane key="">
                            <template #tab>
                                <span>
                                    <FileOutlined />
                                    {{ $t("common.all") }}
                                </span>
                            </template>
                    </a-tab-pane>
                    <a-tab-pane key="individual">
                            <template #tab>
                                <span>
                                    <UserOutlined />
                                    {{ $t("model_name.individual") }}
                                </span>
                            </template>
                    </a-tab-pane>
                </a-tabs>
            </a-col>
    </a-row>
    ```

5. **Define the table structure**:

    ```vue
    <a-row class="mt-5">
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                            :columns="columns"
                            :row-key="(record) => record.xid"
                            :data-source="table.data"
                            :pagination="table.pagination"
                            :loading="table.loading"
                            @change="handleTableChange"
                            bordered
                            size="middle"
                    >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.dataIndex === 'state_id'">
                                    {{ record.state.code }}
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button
                                            v-if="permsArray.includes('model_name_edit') || permsArray.includes('admin')"
                                            type="primary"
                                            @click="editItem(record)"
                                            style="margin-left: 4px"
                                    >
                                            <template #icon><EditOutlined /></template>
                                    </a-button>
                                    <a-button
                                            v-if="(permsArray.includes('model_name_delete') || permsArray.includes('admin')) && (!record.children || record.children.length == 0)"
                                            type="primary"
                                            @click="showDeleteConfirm(record.xid)"
                                            style="margin-left: 4px"
                                    >
                                            <template #icon><DeleteOutlined /></template>
                                    </a-button>
                                </template>
                            </template>
                    </a-table>
                </div>
            </a-col>
    </a-row>
    ```

Example:

    ```vue
    <template>
        <AdminPageHeader>
            <template #header>
                <a-page-header :title="$t(`menu.example_model`)" class="p-0" />
            </template>
            <template #actions>
                <a-space>
                    <template
                        v-if="
                            permsArray.includes('example_model_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-button type="primary" @click="addItem">
                            <PlusOutlined />
                            {{ $t("example_model.add") }}
                        </a-button>
                    </template>
                    <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('example_model_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </template>
            <template #breadcrumb>
                <a-breadcrumb separator="-" style="font-size: 12px">
                    <a-breadcrumb-item>
                        <router-link :to="{ name: 'admin.dashboard.index' }">
                            {{ $t(`menu.dashboard`) }}
                        </router-link>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>
                        {{ $t(`menu.example_model`) }}
                    </a-breadcrumb-item>
                </a-breadcrumb>
            </template>
        </AdminPageHeader>

        <admin-page-table-content>
            <AddEdit
                :addEditType="addEditType"
                :visible="addEditVisible"
                :url="addEditUrl"
                @addEditSuccess="addEditSuccess"
                @closed="onCloseAddEdit"
                :formData="formData"
                :data="viewData"
                :pageTitle="pageTitle"
                :successMessage="successMessage"
            />
            <a-row class="mt-5">
                <a-col :span="24">
                    <div class="table-responsive">
                        <a-table
                            :columns="columns"
                            :row-key="(record) => record.xid"
                            :data-source="table.data"
                            :pagination="table.pagination"
                            :loading="table.loading"
                            @change="handleTableChange"
                            bordered
                            size="middle"
                        >
                            <template #bodyCell="{ column, record }">
                                <template v-if="column.dataIndex === 'state_id'">
                                    {{ record.state.code }}
                                </template>
                                <template v-if="column.dataIndex === 'action'">
                                    <a-button
                                        v-if="
                                            permsArray.includes(
                                                'example_model_edit'
                                            ) || permsArray.includes('admin')
                                        "
                                        type="primary"
                                        @click="editItem(record)"
                                        style="margin-left: 4px"
                                    >
                                        <template #icon><EditOutlined /></template>
                                    </a-button>
                                    <a-button
                                        v-if="
                                            (permsArray.includes(
                                                'example_model_delete'
                                            ) ||
                                                permsArray.includes('admin')) &&
                                            (!record.children ||
                                                record.children.length == 0)
                                        "
                                        type="primary"
                                        @click="showDeleteConfirm(record.xid)"
                                        style="margin-left: 4px"
                                    >
                                        <template #icon
                                            ><DeleteOutlined
                                        /></template>
                                    </a-button>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </a-col>
            </a-row>
        </admin-page-table-content>
    </template>

    <script>
    import { ref, onMounted } from "vue";
    import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
    import {
        PlusOutlined,
        DeleteOutlined,
        FileOutlined,
        UserOutlined,
        TeamOutlined,
        EditOutlined,
    } from "@ant-design/icons-vue";
    import crud from "../../../common/composable/crud";
    import common from "../../../common/composable/common";
    import fields from "./fields";
    import AddEdit from "./AddEdit.vue";

    export default {
        components: {
            PlusOutlined,
            DeleteOutlined,
            FileOutlined,
            UserOutlined,
            TeamOutlined,
            EditOutlined,
            AdminPageHeader,
            AddEdit,
        },
        setup() {
            const {
                url,
                addEditUrl,
                initData,
                columns,
                filterableColumns,
                states,
                getPrefetchData,
                hashableColumns,
            } = fields();
            const { permsArray } = common();
            const crudVariables = crud();

            onMounted(() => {
                getPrefetchData().then(() => {
                    crudVariables.table.filterableColumns = filterableColumns;

                    crudVariables.crudUrl.value = addEditUrl;
                    crudVariables.langKey.value = "example_model";
                    crudVariables.initData.value = { ...initData };
                    crudVariables.formData.value = { ...initData };

                    setUrlData();
                });
            });

            const setUrlData = () => {
                crudVariables.tableUrl.value = {
                    url,
                };

                crudVariables.hashableColumns.value = [...hashableColumns];

                crudVariables.fetch({
                    page: 1,
                });
            };

            return {
                ...crudVariables,
                permsArray,
                columns,
                filterableColumns,
                setUrlData,
            };
        },
    };
    </script>
    ```

<!--
    MARK: Add Search Input
-->

# Instructions for Adding a Search Input

When adding a search input in a view make sure to place it in a-table, ensure the following:

## Normal Search Input

1. **Add the search input in the `#title` slot of the `a-table` component**:

    ```vue
    <template #title>
        <a-row justify="end" align="center" class="table-header">
            <a-col 
                :xs="21"
                :sm="16"
                :md="16"
                :lg="12"
                :xl="8"
            >
                <a-input-search
                    style="width: 100%"
                    v-model:value="table.searchString"
                    :placeholder="$t('common.search')"
                    show-search
                    @search="onTableSearch"
                    @change="onTableSearch"
                    :loading="table.loading"
                />
            </a-col>
        </a-row>
    </template>
    ```

Example:

```vue
<a-table
    :columns="columns"
    :row-key="(record) => record.xid"
    :data-source="table.data"
    :pagination="table.pagination"
    :loading="table.loading"
    @change="handleTableChange"
    bordered
    size="middle"
>
    <template #title>
        <a-row justify="end" align="center" class="table-header">
            <a-col 
                :xs="21"
                :sm="16"
                :md="16"
                :lg="12"
                :xl="8"
            >
                <a-input-search
                    style="width: 100%"
                    v-model:value="table.searchString"
                    :placeholder="$t('common.search')"
                    show-search
                    @search="onTableSearch"
                    @change="onTableSearch"
                    :loading="table.loading"
                />
            </a-col>
        </a-row>
    </template>
    // Existing slots...
</a-table>
```

## Search Input with Options

1. **Add the search input with options in the `#title` slot of the `a-table` component**:

    ```vue
    <template #title>
        <a-row justify="end" align="center" class="table-header">
            <a-col 
                :xs="21"
                :sm="16"
                :md="16"
                :lg="12"
                :xl="8"
            >
                <a-input-group compact>
                    <a-select
                        style="width: 25%"
                        v-model:value="table.searchColumn"
                        :placeholder="$t('common.select_default_text', [''])"
                    >
                        <a-select-option
                            v-for="filterableColumn in filterableColumns"
                            :key="filterableColumn.key"
                        >
                            {{ filterableColumn.value }}
                        </a-select-option>
                    </a-select>
                    <a-input-search
                        style="width: 75%"
                        v-model:value="table.searchString"
                        :placeholder="$t('common.search')"
                        show-search
                        @search="onTableSearch"
                        @change="onTableSearch"
                        :loading="table.loading"
                    />
                </a-input-group>
            </a-col>
        </a-row>
    </template>
    ```

Example:

```vue
<a-table
    :columns="columns"
    :row-key="(record) => record.xid"
    :data-source="table.data"
    :pagination="table.pagination"
    :loading="table.loading"
    @change="handleTableChange"
    bordered
    size="middle"
>
    <template #title>
        <a-row justify="end" align="center" class="table-header">
            <a-col 
                :xs="21"
                :sm="16"
                :md="16"
                :lg="12"
                :xl="8"
            >
                <a-input-group compact>
                    <a-select
                        style="width: 25%"
                        v-model:value="table.searchColumn"
                        :placeholder="$t('common.select_default_text', [''])"
                    >
                        <a-select-option
                            v-for="filterableColumn in filterableColumns"
                            :key="filterableColumn.key"
                        >
                            {{ filterableColumn.value }}
                        </a-select-option>
                    </a-select>
                    <a-input-search
                        style="width: 75%"
                        v-model:value="table.searchString"
                        :placeholder="$t('common.search')"
                        show-search
                        @search="onTableSearch"
                        @change="onTableSearch"
                        :loading="table.loading"
                    />
                </a-input-group>
            </a-col>
        </a-row>
    </template>
    // Existing slots...
</a-table>
```

<!--
    MARK: Add filters
-->

# Instructions for Adding Filters in a View or Model

When adding filters in a view or model, ensure the following:

1. **Define the `filters` variable**:
    ```javascript
    const filters = ref({
        // Add filters here
    });
    ```

2. **Add `filters` to `crudVariables.tableUrl.value` parameters**:
    ```javascript
    crudVariables.tableUrl.value = {
         // ...existing code...
         filters,
    };
    ```


3. **Return the `filters` variable**:
    ```javascript
    return {
         // ...existing code...
         filters,
    };
    ```

4. **Add the desired filters to the `$filterable` array in the model**:
    ```php
    protected $filterable = ['desired_filters'];
    ```

Example:
```javascript
// ...existing code...

export default {
    // ...existing code...
     setup() {
          // ...existing variables...
          const filters = ref({
                status: "",
                state_id: undefined,
          });

          const setUrlData = () => {
                crudVariables.tableUrl.value = {
                     // ...existing parameters...
                     filters,
                };

                // ...existing code...
          };

          return {
                // ...existing variables/functions...
                filters,
          };
     },
}
</script>
````

```php
// In the model file

class ExampleModel extends BaseModel
{
    // ...existing protected variables...

    protected $filterable = ['status', 'state_id'];

    // ...existing code...
}
```

There can only be either 'normal filter' or a 'tab filter'

# Instructions for adding a `normal filter` or simply a `filter`

1. **Import Filters**

    ```javascript
    import Filters from "../../../common/components/common/select/Filters.vue";
    ```

2. **Register `Filters` component**

    ```javascript
    components: {
        //Existing components...
        Filters,
    }
    ```

3. **Add `resetFilters` function**

    ```javascript
    const resetFilters = () => {
        filters.value = {
            //Add filters here
        };
        setUrlData();
    };
    ```

4. **return the `resetFilters` function**

    ```javascript
    return {
        // ...existing variables/functions...
        resetFilters,
    };
    ```

5. **include the #title slot for filters as first child of `a-table`**:
    ```vue
    <a-table>
        <template #title>
            <a-row justify="end" align="center" class="table-header">
                <a-col class="ml-10">
                    <Filters 
                        @onReset="resetFilters"
                        :filters="filters"
                    >
                        <a-col :span="24">
                            <a-form-item :label="$t('model_name.filter_1')">
                                <a-select
                                    v-model:value="filters.filter_1"
                                    :placeholder="
                                        $t('common.select_default_text', [
                                            $t('model_name.filter_1'),
                                        ])
                                    "
                                    :allowClear="true"
                                    style="width: 100%"
                                    optionFilterProp="title"
                                    show-search
                                    @change="setUrlData"
                                >
                                    <a-select-option
                                        v-for="state in states"
                                        :key="filter_1.xid"
                                        :title="filter_1.name"
                                        :value="filter_1.xid"
                                    >
                                        {{ filter_1.name }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                            // Add as many forms items as neccesary
                        </a-col>
                    </Filters>
                </a-col>
            </a-row>
        </template>
        //...existing code...
    </a-table>
    ```

Example

```vue
<a-table
    :columns="columns"
    :row-key="(record) => record.xid"
    :data-source="table.data"
    :pagination="table.pagination"
    :loading="table.loading"
    @change="handleTableChange"
    bordered
    size="middle"
>
    <template #title>
        <a-row justify="end" align="center" class="table-header">
            // Existing a-cols...
            <a-col class="ml-10">
                <Filters 
                    @onReset="resetFilters"
                    :filters="filters"
                >
                    <a-col :span="24">
                        <a-form-item :label="$t('lead.state')">
                            <a-select
                                v-model:value="filters.state_id"
                                :placeholder="$t('common.select_default_text', [$t('lead.state')])"
                                :allowClear="true"
                                style="width: 100%"
                                optionFilterProp="title"
                                show-search
                                @change="setUrlData"
                            >
                                <a-select-option
                                    v-for="state in states"
                                    :key="state.xid"
                                    :title="state.name"
                                    :value="state.xid"
                                >
                                    {{ state.name }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </Filters>
            </a-col>
        </a-row>
    </template>
    // Existing slots...
</a-table>
```

# Instructions for adding a `tab filter`

1. **Add the following code above the row containing the table**

    ```vue
    <a-row class="mt-20">
         <a-col :span="24">
              <a-tabs
              v-model:activeKey="filters.filter_1" 
              @change="setUrlData" 
              centered
              type="card"
              class="table-tab-filters"
              >
              <a-tab-pane key="">
                   <template #tab>
                        <span>
                             <FileOutlined />
                             {{ $t("common.all") }}
                        </span>
                   </template>
              </a-tab-pane>
    
              </a-tabs>
         </a-col>
    </a-row>
    ```

Example
    `vue
        <admin-page-table-content>
            <AddEdit/>
            <a-row class="mt-20">
                <a-col :span="24">
                        <a-tabs
                        v-model:activeKey="filters.status" 
                        @change="setUrlData" 
                        centered
                        type="card"
                        class="table-tab-filters"
                        >
                        <a-tab-pane key="">
                            <template #tab>
                                <span>
                                    <FileOutlined />
                                    {{ $t("common.all") }}
                                </span>
                            </template>
                        </a-tab-pane>
                        <a-tab-pane key="individual">
                            <template #tab>
                                <span>
                                    <UserOutlined />
                                    {{ $t("creditor.individual") }}
                                </span>
                            </template>
                        </a-tab-pane>
                        <a-tab-pane key="business">
                            <template #tab>
                                <span>
                                    <TeamOutlined />
                                    {{ $t("creditor.business") }}
                                </span>
                            </template>
                        </a-tab-pane>
                        </a-tabs>
                </a-col>
            </a-row>
            <a-row class="mt-5">
            <a-col :span="24">
                <div class="table-responsive">
                        <a-table>
                        </a-table>
                </div>
            </a-col>
        </a-row>
        </admin-page-table-content>
    `

# Instruction for adding date filter

These change are applied on model controller and index view

1. **Add dates query in the `modifyIndex` function in model controller**
```php
    // Dates Filters
    if ($request->has('dates') && $request->dates != "") {
        $dates = explode(',', $request->dates);
        $startDate = $dates[0];
        $endDate = $dates[1];

        $query = $query->whereRaw('model_name.date_time >= ?', [$startDate])
            ->whereRaw('model_name.date_time <= ?', [$endDate]);
    }
```

2. **Add `extraFilter` ref const in script and return**
```javascript
        const extraFilters = ref({
            //...existing elements...
            dates: [],
        });

        return {
            //...existing variables/methods...
            extraFilters
        }
```

3. **Import `DateRangePicker`**
```javascript
    import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";
```

4. **Register `DateRangePicker` component **
```javascript
component {
    //...Existing components...
    DateRangePicker
}
```

5. **Add DateRangePicker item in `Filters` component**
```vue
    <Filters 
        @onReset="resetFilters"
        :filters="filters"
    >
        <a-col :span="24">
            //...existing items...
            <a-form-item :label="$t('common.date')">
                <DateRangePicker
                    @dateTimeChanged="
                        (changedDateTime) => {
                            extraFilters.dates = changedDateTime;
                            setUrlData();
                        }
                    "
                />
            </a-form-item>
        </a-col>
    </Filters>
```

<!--
    MARK: Route
-->

# Instructions for Creating a New Route

When creating a new route in `resources/js/main/router`, ensure the following:

1. **Create a new route file**:
     ```javascript
     import ViewName from '../views/view_name/index.vue';

     export default [
          {
               path: '/',
               component: () => import('../../common/layouts/Admin.vue'),
               children: [
               {
                    path: '/admin/view_name',
                    component: ViewName,
                    name: 'admin.view_name.index',
                    meta: {
                         requireAuth: true,
                         menuParent: "view_name",
                         menuKey: "view_name",
                         permission: "view_name_view"
                    }
               },
               ]
          }
     ]
     ```

2. **Assign the new route file in `index.js`**:
     ```javascript
     import ViewNameRoutes from './view_name';

     const router = createRouter({
          history: createWebHistory(),
          routes: [
               {
               path: '',
               redirect: '/admin/login'
               },
               ...AuthRoutes,
               ...DashboardRoutes,
               ...UserRoutes,
               ...SettingRoutes,
               ...MessagingRoutes,
               ...FormRoutes,
               ...ViewNameRoutes,
          ],
          scrollBehavior: () => ({ left: 0, top: 0 }),
     });
     ```

Example:
```javascript
// view_name.js
import ViewName from '../views/view_name/index.vue';

export default [
     {
          path: '/',
          component: () => import('../../common/layouts/Admin.vue'),
          children: [
               {
                    path: '/admin/view_name',
                    component: ViewName,
                    name: 'admin.view_name.index',
                    meta: {
                    requireAuth: true,
                    menuParent: "view_name",
                    menuKey: "view_name",
                    permission: "view_name_view"
                    }
               },
          ]
     }
]

// index.js
import ViewNameRoutes from './view_name';

const router = createRouter({
     history: createWebHistory(),
     routes: [
          {
               path: '',
               redirect: '/admin/login'
          },
          ...AuthRoutes,
          ...DashboardRoutes,
          ...UserRoutes,
          ...SettingRoutes,
          ...MessagingRoutes,
          ...FormxRoutes,
          ...ViewNameRoutes,
     ],
     scrollBehavior: () => ({ left: 0, top: 0 }),
});
```

<!--
     MARK: Translations
-->

# Instructions for Adding Translations

When adding translations to `app/Classes/LangTrans.php`, ensure the following:

1. **Add the translations inside the `$mainTranslations` array**:

    ```php
    public static $mainTranslations = [
         // ...existing translations...
         'new_translation_key' => [
              'add' => 'Add New Item',
              'edit' => 'Edit Item',
              'created' => 'Item Created Successfully',
              'updated' => 'Item Updated Successfully',
              'deleted' => 'Item Deleted Successfully',
              'delete_message' => 'Are you sure you want to delete this item?',
              'selected_delete_message' => 'Are you sure you want to delete selected item?',
              //...remaining translation...
         ],
    ];
    ```

3. **When left sidebar is created add a new translations to `main` Array**:
    ```php
    'main' => [
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'staff_members' => 'Staff Members',
        //...model name translation
    ]
    ```

2. **Ensure the translations follow the existing structure**:
    - Use descriptive keys for the translations.
    - Group related translations together.

Example:

```php
public static $mainTranslations = [
     'common' => [
          'enabled' => 'Enabled',
          'disabled' => 'Disabled',
          // ...other common translations...
     ],
     'menu' => [
          'dashboard' => 'Dashboard',
          'users' => 'Users',
          // ...other menu translations...
     ],
     'new_translation_key' => [
          'add' => 'Add New Item',
          'edit' => 'Edit Item',
          'created' => 'Item Created Successfully',
          'updated' => 'Item Updated Successfully',
          'deleted' => 'Item Deleted Successfully',
          'item_details' => 'Item Details',
          'item' => 'Item',
          'delete_message' => 'Are you sure you want to delete this item?',
          'selected_delete_message' => 'Are you sure you want to delete selected item?',
          'import_items' => 'Import Items',
     ],
];
```

<!--
    MARK: Permissions
-->

# Instructions for Adding New Permissions

When adding new permissions to `app/Classes/PermsSeed.php`, ensure the following steps are followed:

1. **Define the new permissions in the `$mainPermissionsArray`**:

    ```php
    public static $mainPermissionsArray = [
       // ...existing permissions...

       // New Permissions
       'new_permission_view' => [
          'name' => 'new_permission_view',
          'display_name' => 'New Permission View'
       ],
       'new_permission_create' => [
          'name' => 'new_permission_create',
          'display_name' => 'New Permission Create'
       ],
       'new_permission_edit' => [
          'name' => 'new_permission_edit',
          'display_name' => 'New Permission Edit'
       ],
       'new_permission_delete' => [
          'name' => 'new_permission_delete',
          'display_name' => 'New Permission Delete'
       ],
    ];
    ```

Example:

```php
public static $mainPermissionsArray = [
    // ...existing permissions...

    // Currency
    'currencies_view' => [
       'name' => 'currencies_view',
       'display_name' => 'Currency View'
    ],
    'currencies_create' => [
       'name' => 'currencies_create',
       'display_name' => 'Currency Create'
    ],
    'currencies_edit' => [
       'name' => 'currencies_edit',
       'display_name' => 'Currency Edit'
    ],
    'currencies_delete' => [
       'name' => 'currencies_delete',
       'display_name' => 'Currency Delete'
    ],
];
```

<!--
    MARK: LeftSidebar item
-->

# Instructions for Adding a New Menu Item in LeftSidebar

When adding a new menu item in `LeftSidebar` to `resources/js/common/layouts/LeftSidebar.vue`, ensure the following:

1. **Add the new menu item in the `a-menu` component**:

    ```vue
    <a-menu-item
       v-if="
          permsArray.includes('new_permission_view') ||
          permsArray.includes('admin')
       "
       @click="
          () => {
             menuSelected();
             $router.push({ name: 'admin.new_route.index' });
          }
       "
       key="new_route"
    >
       <NewIcon />
       <span>{{ $t("menu.new_item") }}</span>
    </a-menu-item>
    ```

2. **Import the necessary icon in the script section if necessary**:

    ```javascript
    import { NewIcon } from "@ant-design/icons-vue";
    ```

3. **Register the imported icon in the `components` object if necessary**:

    ```javascript
    components: {
       // ...existing components...
       NewIcon,
    },
    ```

Example:

```vue
<template>
    <a-menu
       :theme="appSetting.left_sidebar_theme"
       :openKeys="openKeys"
       v-model:selectedKeys="selectedKeys"
       :mode="mode"
       @openChange="onOpenChange"
       :style="{ borderRight: 'none' }"
    >
       <!-- Existing menu items -->

       <a-menu-item
          v-if="
             permsArray.includes('new_permission_view') ||
             permsArray.includes('admin')
          "
          @click="
             () => {
                menuSelected();
                $router.push({ name: 'admin.new_route.index' });
             }
          "
          key="new_route"
       >
          <NewIcon />
          <span>{{ $t("menu.new_item") }}</span>
       </a-menu-item>
    </a-menu>
</template>

<script>
import { defineComponent } from "vue";
import { NewIcon } from "@ant-design/icons-vue";

export default defineComponent({
    components: {
       // ...existing components...
       NewIcon,
    },
    setup() {
       // ...existing setup code...
    },
});
</script>
```

<!--
    MARK: Additional
-->

#Instruction to add a new column to a table

1. Add the following template to `a-table`, but make sure its not after the `action` column
```vue
<template v-if="column.dataIndex === 'column_name'">
    //content
</template>
```

If the column is "description", add the following:

```vue
<template v-if="column.dataIndex === 'description'">
    <p style="text-align: justify; white-space: wrap;" >
        <a-typography-paragraph
            :ellipsis="{
                rows: 2,
                expandable: true,
                symbol: $t('common.more'),
            }"
            :content="record.description"
        />
    </p>
</template>
```

#Instruction to add a getPrefetchData in fields.js

1.  Add `getPrefetchData` function in `fields.js`
```javascript
    const getPrefetchData = () => {
        const modelPromise = axiosAdmin.get("models?fields=id,xid,model_columns");
        return Promise.all([modelPromise]).then(([modelResponse]) => {
            models.value = modelResponse.data;
        });
    }
```

2.  Return the `getPrefetchData` function
```javascript
    return {
        //...existing code...
        getPrefetchData,
    }
```

3.  Call the `getPrefetchData` function in `index.vue` inside `onMounted`
```javascript
    onMounted(() => {
        getPrefetchData().then(() => {
            setUrlData();
        });
    });
```

<!--
    MARK: Ant Desing vue icons
-->

# Available icons for ant desing vue

StepBackwardOutlined, StepForwardOutlined, FastBackwardOutlined, FastForwardOutlined, ShrinkOutlined, ArrowsAltOutlined, DownOutlined, UpOutlined, LeftOutlined, RightOutlined, CaretUpOutlined, CaretDownOutlined, CaretLeftOutlined, CaretRightOutlined, UpCircleOutlined, DownCircleOutlined, LeftCircleOutlined, RightCircleOutlined, DoubleRightOutlined, DoubleLeftOutlined, VerticalLeftOutlined, VerticalRightOutlined, VerticalAlignTopOutlined, VerticalAlignMiddleOutlined, VerticalAlignBottomOutlined, ForwardOutlined, BackwardOutlined, RollbackOutlined, EnterOutlined, RetweetOutlined, SwapOutlined, SwapLeftOutlined, SwapRightOutlined, ArrowUpOutlined, ArrowDownOutlined, ArrowLeftOutlined, ArrowRightOutlined, LoginOutlined, PlayCircleOutlined, UpSquareOutlined, DownSquareOutlined, LeftSquareOutlined, RightSquareOutlined, LogoutOutlined, MenuFoldOutlined, MenuUnfoldOutlined, BorderBottomOutlined, BorderHorizontalOutlined, BorderInnerOutlined, BorderOuterOutlined, BorderLeftOutlined, BorderRightOutlined, BorderTopOutlined, BorderVerticleOutlined, PicCenterOutlined, PicLeftOutlined, PicRightOutlined, RadiusUprightOutlined, FullscreenOutlined, FullscreenExitOutlined, RadiusBottomLeftOutlined, RadiusBottomRightOutlined, RadiusUpleftOutlined, QuestionOutlined, QuestionCircleOutlined, PlusOutlined, PlusCircleOutlined, PauseOutlined, PauseCircleOutlined, MinusOutlined, MinusCircleOutlined, PlusSquareOutlined, MinusSquareOutlined, InfoOutlined, InfoCircleOutlined, ExclamationOutlined, ExclamationCircleOutlined, CloseOutlined, CloseCircleOutlined, CloseSquareOutlined, CheckOutlined, CheckCircleOutlined, CheckSquareOutlined, ClockCircleOutlined, WarningOutlined, IssuesCloseOutlined, StopOutlined, EditOutlined, FormOutlined, CopyOutlined, ScissorOutlined, DeleteOutlined, SnippetsOutlined, DiffOutlined, HighlightOutlined, AlignCenterOutlined, AlignLeftOutlined, AlignRightOutlined, BgColorsOutlined, BoldOutlined, ItalicOutlined, UnderlineOutlined, StrikethroughOutlined, RedoOutlined, UndoOutlined, ZoomInOutlined, ZoomOutOutlined, FontColorsOutlined, FontSizeOutlined, LineHeightOutlined, DashOutlined, SmallDashOutlined, SortAscendingOutlined, SortDescendingOutlined, DragOutlined, OrderedListOutlined, UnorderedListOutlined, RadiusSettingOutlined, ColumnWidthOutlined, ColumnHeightOutlined, AreaChartOutlined, PieChartOutlined, BarChartOutlined, DotChartOutlined, LineChartOutlined, RadarChartOutlined, HeatMapOutlined, FallOutlined, RiseOutlined, StockOutlined, BoxPlotOutlined, SlidersOutlined, AndroidOutlined, AppleOutlined, WindowsOutlined, IeOutlined, ChromeOutlined, GithubOutlined, AliwangwangOutlined, DingdingOutlined, WeiboSquareOutlined, WeiboCircleOutlined, TaobaoCircleOutlined, Html5Outlined, WeiboOutlined, TwitterOutlined, WechatOutlined, YoutubeOutlined, AlipayCircleOutlined, TaobaoOutlined, SkypeOutlined, GooglePlusOutlined, QqOutlined, MediumWorkmarkOutlined, GitlabOutlined, MediumOutlined, LinkedinOutlined, DropboxOutlined, FacebookOutlined, CodepenOutlined, CodeSandboxOutlined, AmazonOutlined, GoogleOutlined, CodepenCircleOutlined, AlipayOutlined, AntDesignOutlined, AntCloudOutlined, AliyunOutlined, ZhihuOutlined, SlackOutlined, SlackSquareOutlined, BehanceOutlined, BehanceSquareOutlined, DribbbleOutlined, DribbbleSquareOutlined, InstagramOutlined, YuqueOutlined, AlibabaOutlined, SketchOutlined, AccountBookOutlined, AimOutlined, AppstoreOutlined, YahooOutlined, RedditOutlined, AlertOutlined, ApartmentOutlined, ApiOutlined, AppstoreAddOutlined, AudioOutlined, AudioMutedOutlined, AuditOutlined, BankOutlined, BarcodeOutlined, BarsOutlined, BellOutlined, BlockOutlined, BookOutlined, BorderOutlined, BorderlessTableOutlined, BranchesOutlined, BugOutlined, BuildOutlined, BulbOutlined, CalculatorOutlined, CalendarOutlined, CameraOutlined, CarOutlined, CarryOutOutlined, CiCircleOutlined, CiOutlined, ClearOutlined, CloudDownloadOutlined, CloudOutlined, CloudServerOutlined, CloudSyncOutlined, CloudUploadOutlined, ClusterOutlined, CodeOutlined, CoffeeOutlined, CommentOutlined, CompassOutlined, CompressOutlined, ConsoleSqlOutlined, ContactsOutlined, ContainerOutlined, ControlOutlined, CopyrightCircleOutlined, CopyrightOutlined, CreditCardOutlined, CrownOutlined, CustomerServiceOutlined, DashboardOutlined, DatabaseOutlined, DeleteColumnOutlined, DeleteRowOutlined, DeliveredProcedureOutlined, DeploymentUnitOutlined, DesktopOutlined, DingtalkOutlined, DisconnectOutlined, DislikeOutlined, DollarCircleOutlined, DollarOutlined, DownloadOutlined, EllipsisOutlined, EnvironmentOutlined, EuroCircleOutlined, EuroOutlined, ExceptionOutlined, ExpandAltOutlined, ExpandOutlined, ExperimentOutlined, ExportOutlined, EyeOutlined, EyeInvisibleOutlined, FieldBinaryOutlined, FieldNumberOutlined, FieldStringOutlined, FieldTimeOutlined, FileAddOutlined, FileDoneOutlined, FileExcelOutlined, FileExclamationOutlined, FileOutlined, FileGifOutlined, FileImageOutlined, FileJpgOutlined, FileMarkdownOutlined, FilePdfOutlined, FilePptOutlined, FileProtectOutlined, FileSearchOutlined, FileSyncOutlined, FileTextOutlined, FileUnknownOutlined, FileWordOutlined, FireOutlined, FlagOutlined, FolderAddOutlined, FolderViewOutlined, ForkOutlined, FormatPainterOutlined, FileZipOutlined, FolderOutlined, FolderOpenOutlined, FrownOutlined, FunctionOutlined, FundViewOutlined, FunnelPlotOutlined, GatewayOutlined, GifOutlined, GoldOutlined, GroupOutlined, HddOutlined, HeartOutlined, FundProjectionScreenOutlined, GiftOutlined, GlobalOutlined, HistoryOutlined, HolderOutlined, HomeOutlined, HourglassOutlined, IdcardOutlined, ImportOutlined, InboxOutlined, InsertRowAboveOutlined, InsuranceOutlined, InteractionOutlined, KeyOutlined, InsertRowBelowOutlined, InsertRowLeftOutlined, InsertRowRightOutlined, LaptopOutlined, LayoutOutlined, LikeOutlined, LineOutlined, LinkOutlined, Loading3QuartersOutlined, LoadingOutlined, LockOutlined, MacCommandOutlined, MailOutlined, ManOutlined, MedicineBoxOutlined, MehOutlined, MenuOutlined, MergeCellsOutlined, MessageOutlined, MobileOutlined, MoneyCollectOutlined, MonitorOutlined, MoreOutlined, NodeCollapseOutlined, NodeExpandOutlined, NodeIndexOutlined, NotificationOutlined, NumberOutlined, OneToOneOutlined, PaperClipOutlined, PartitionOutlined, PayCircleOutlined, PercentageOutlined, PhoneOutlined, PictureOutlined, PoundOutlined, PoweroffOutlined, PlaySquareOutlined, PoundCircleOutlined, PrinterOutlined, ProfileOutlined, ProjectOutlined, PropertySafetyOutlined, PullRequestOutlined, PushpinOutlined, QrcodeOutlined, ReadOutlined, ReconciliationOutlined, RedEnvelopeOutlined, RobotOutlined, RocketOutlined, RotateLeftOutlined, SaveOutlined, SendOutlined, ScanOutlined, ScheduleOutlined, ReloadOutlined, RestOutlined, RotateRightOutlined, SafetyCertificateOutlined, SafetyOutlined, SearchOutlined, SecurityScanOutlined, SelectOutlined, SettingOutlined, ShakeOutlined, ShareAltOutlined, ShopOutlined, ShoppingCartOutlined, ShoppingOutlined, SisternodeOutlined, SkinOutlined, SmileOutlined, SolutionOutlined, SoundOutlined, SplitCellsOutlined, StarOutlined, SubnodeOutlined, SwitcherOutlined, SyncOutlined, TableOutlined, TabletOutlined, TagOutlined, TagsOutlined, TeamOutlined, ThunderboltOutlined, ToTopOutlined, ToolOutlined, TrademarkCircleOutlined, TrademarkOutlined, TransactionOutlined, TranslationOutlined, TrophyOutlined, UsbOutlined, UserAddOutlined, UserDeleteOutlined, UngroupOutlined, UnlockOutlined, UploadOutlined, UserOutlined, VideoCameraOutlined, UserSwitchOutlined, UsergroupAddOutlined, WalletOutlined, WhatsAppOutlined, UsergroupDeleteOutlined, VerifiedOutlined, VideoCameraAddOutlined, WifiOutlined, WomanOutlined
