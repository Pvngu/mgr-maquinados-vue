import { ref } from "vue";

const viewUserDrawer = () => {
    const viewUserDrawerData = ref({});
    const isViewUserDrawerVisible = ref(false);

    const showViewUserDrawer = (record) => {
        viewUserDrawerData.value = record;
        isViewUserDrawerVisible.value = true;
    }

    const hideViewUserDrawer = () => {
        viewUserDrawerData.value = {};
        isViewUserDrawerVisible.value = false;
    }

    return {
        viewUserDrawerData,
        isViewUserDrawerVisible,

        showViewUserDrawer,
        hideViewUserDrawer,
    }
}

export default viewUserDrawer;
