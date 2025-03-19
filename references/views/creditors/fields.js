import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "creditors?fields=id,xid,name,first_name,last_name,email,phone_number,address_line1,address_line2,city,state_id,zip_code,state{code}";
    const addEditUrl = "creditors";
    const { t } = useI18n();
    const states = ref([]);
    // add hashable columns if needed
    const hashableColumns = ['state_id'];

    const initData = {
        name: "",
        first_name: "",
        last_name: "",
        email: "",
        phone_number: "",
        address_line1: "",
        address_line2: "",
        city: "",
        state_id: undefined,
        zip_code: ""
    };

    // getPrefetchData if needed
    const getPrefetchData = () => {
        const statesPromise = axiosAdmin.get('states/all');
        return Promise.all([statesPromise]).then(([statesReponse]) => {
            states.value = statesReponse.data;
        });
    }

    const columns = [
        {
            title: t("common.name"),
            dataIndex: "name",
        },
        {
            title: t("common.email"),
            dataIndex: "email",
        },
        {
            title: t("card.address"),
            dataIndex: "address_line1",
        },
        {
            title: t("card.address_city"),
            dataIndex: "city",
        },
        {
            title: t("card.address_state"),
            dataIndex: "state",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    // filters for input-search
    const filterableColumns = [
        {
            key: "name",
            value: t("common.name"),
        },
        {
            key: "address_line1",
            value: t("common.address"),
        },
        {
            key: "email",
            value: t("common.email"),
        },
        {
            key: "phone_number",
            value: t("common.phone"),
        },
        {
            key: "city",
            value: t("user.city"),
        },
        {
            key: "zip_code",
            value: t("user.zipcode"),
        }
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        states,
        getPrefetchData,
        hashableColumns
    }
}

export default fields;