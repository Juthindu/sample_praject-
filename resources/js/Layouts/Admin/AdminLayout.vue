<script setup>
import TopNav from '@/Components/Admin-Panel/TopNav.vue';
import AdminFooter from '@/Components/Admin-Panel/Footer.vue';
import SideBar from '@/Components/Admin-Panel/MainSideBar.vue';
import { ref, onMounted } from 'vue';

const userRoles = ref([]);
const userPermissions = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/user-permissions', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        userRoles.value = response.data.roles;
        userPermissions.value = response.data.permissions;
    } catch (error) {
        console.error('Error fetching permissions:', error);
    }
});

const can = (permission) => {
    return userPermissions.value.includes(permission);
};

const hasRole = (role) => {
    return userRoles.value.includes(role);
};
</script>
<template>
    <div class="wrapper">
        <TopNav />
        <SideBar :user-permissions="userPermissions"/>
        <main>
            <div class="content-wrapper">
                <!-- Main content -->
                 <slot />
            </div>
        </main>
        <AdminFooter />
    </div>
</template>

<script>
export default {
    name: 'AdminLayout',
    watch() {
        document.body.className = "hold-transition sidebar-mini";
    },
    data() {
        return {
            showModal: false,
            selectedStudentId: null,
            selectedRouteName: null,
        };
    },
};
</script>

<style>
.badge-success {
    color: #0AC074 !important;
    background-color: #CEF2E3 !important;
}

.badge-info {
    color: #0099FB !important;
    background-color: #CCEBFE !important;
}

.badge-danger {
    color: #F62947 !important;
    background-color: #FDD4DA !important;
}

.badge-warning {
    color: #92400e !important;
    background-color: #fef3c7 !important;
}
</style>
