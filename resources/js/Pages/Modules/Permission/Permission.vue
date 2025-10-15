<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref, computed,watch } from 'vue';
// import axios from 'axios';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const roles = ref([]);
const toast = useToast();
const permissions = ref([]);
const selectedPermissions = ref({});
const selectedRole = ref(null);


const formData = ref({
    selectedPermissions: [],
});

const fetchRoles = async () => {
    try {
        const response = await axios.get('/api/all/roles');
        roles.value = response.data;
    } catch (error) {
        console.error('Error fetching roles:', error);
    }
};

const isSuperAdmin = computed(() => {
    const role = roles.value.find((r) => r.id === selectedRole.value);
    return role?.name.toLowerCase() === 'super admin';
});

watch(selectedRole, (newRole) => {
    if (newRole) {
        fetchPermissions(newRole);
    }
});

const fetchRolePermissions = async (roleId) => {
    try {
        const response = await axios.get(`/roles/${roleId}/permissions`);
        const rolePermissions = response.data;

        selectedPermissions.value = {};

        permissions.value.forEach((perm) => {
            selectedPermissions.value[perm.id] = rolePermissions.includes(perm.name);
        });

    } catch (error) {
        toast.error('Failed to fetch role permissions.');
    }
};


const fetchPermissions = async (newRole) => {
    try {
        const response = await axios.get('/permissions');
        permissions.value = response.data;
        fetchRolePermissions(newRole);
    } catch (error) {
        console.error('Error fetching permissions:', error);
    }
};


const savePermissions = async () => {
    if (!selectedRole.value) {
        toast.error('No role selected.');
        return;
    }

    // Get only the selected permission names
    const selectedPermissionNames = permissions.value
        .filter(perm => selectedPermissions.value[perm.id]) // Only true values
        .map(perm => perm.name); // Extract permission names

    if (selectedPermissionNames.length === 0) {
        toast.warning("No permissions selected!");
        return;
    }

    const payload = {
        permissions: selectedPermissionNames // Send an array of permission names
    };

    const roleId = selectedRole.value;

    try {
        const response = await axios.put(`/roles/${roleId}/give-permission`, payload);
        console.log('Permissions saved:', response);
        toast.success('Permissions successfully assigned to the role!');
    } catch (error) {
        console.error('Error saving permissions:', error);
        toast.error(error.response?.data?.message || 'Failed to assign permissions.');
    }
};


onMounted(() => {
    fetchRoles();
});
</script>
<template>
    <AdminLayout>
        <Head title="Permission" />
<div class="ml-3 mr-3">
            <!-- Page Header -->
        <div class="row mb-2">
            <div class="col-sm-6 ">
                <h4 class="m-0 mt-2">ROLE PERMISSIONS MANAGEMENT</h4>
            </div>
            <div class="col-sm-6 ">
                <ol class="breadcrumb float-sm-right mt-2">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Permissions Management</li>
                </ol>
            </div>
        </div>

        <!-- Role Selection -->
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header ">
                        <h3 class="card-title text-bold"><i class="fas fa-solid fa-layer-group mr-2"></i>Select Role</h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label for="">Role Name :</label>
                                <select class="form-control" v-model="selectedRole">
                                    <option value="" disabled selected>Select Role</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.id">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Permissions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold"><i class="fas fa-solid fa-layer-group mr-2"></i>Set Permissions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex">
                            <div class="row d-flex">
                                <div class="col-md-6 col-sm-12 mb-3" v-for="permission in permissions"
                                    :key="permission.id">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox"
                                            :id="'permission' + permission.id"
                                            v-model="selectedPermissions[permission.id]"
                                            :disabled="permission.is_active === 0" />
                                        <label :for="'permission' + permission.id" class="custom-control-label">
                                            {{ permission.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <button type="submit" class="btn btn-primary mt-2 me-3 mb-2 mr-3 justify-content-end align-items-end"
                            style="width: 100px;" @click.prevent="savePermissions" :disabled="isSuperAdmin">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
    </AdminLayout>
</template>
