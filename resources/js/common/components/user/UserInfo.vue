<template>
    <a-space>
        <a-avatar :size="30" :src="user.profile_image_url" />
        <span>
            <a-typography-link class="link">{{ user.name }}</a-typography-link>
             <br v-if="user && user.role && user.role.name" />
            <small v-if="user && user.role && user.role.display_name">{{
                user && user.role.display_name
            }}</small>
        </span>
    </a-space>
</template>

<script>
export default {
    props: ["user", "size"],
};
</script>