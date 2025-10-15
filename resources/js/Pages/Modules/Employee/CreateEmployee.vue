<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
    data: Object,
});
const roles = ref(props.data ? props.data : null);
const selectedRoleId = ref('')
const toast = useToast();
const employee_first_name = ref('');
const employee_last_name = ref('');
const employee_nic = ref('');
const employee_contact_number = ref('');
const employee_address = ref('');
const employee_email = ref('');
const formRef = ref(null);

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();

    formData.append('first_name', employee_first_name.value);
    formData.append('last_name', employee_last_name.value);
    formData.append('nic', employee_nic.value);
    formData.append('contact_number', employee_contact_number.value);
    formData.append('address', employee_address.value);
    formData.append('email', employee_email.value);
    formData.append('role', selectedRoleId.value);

    store('employee.store', formData);
}

</script>

<template>
    <AdminLayout>

        <Head title="employee-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">EMPLOYEE CREATE</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('employee.index')">Employee</a></li>
                            <li class="breadcrumb-item active">Employee Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <section class="content">
            <div class="container-fluid">
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body">
                                <h5 class="w-75 mb-3 text-bold">Employee Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_first_name">First Name</label>
                                            <input type="text" class="form-control" id="employee_first_name" required
                                                placeholder="First Name" v-model="employee_first_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_last_name">Last Name</label>
                                            <input type="text" class="form-control" id="employee_last_name" required
                                                placeholder="Last Name" v-model="employee_last_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_nic">NIC</label>
                                            <input type="text" class="form-control" id="employee_nic_name" required
                                                placeholder="NIC" v-model="employee_nic">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_contact_number">Contact number</label>
                                            <input type="number" class="form-control" id="employee_contact_number"
                                                required placeholder="Contact Number" v-model="employee_contact_number">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_contact_number">Address</label>
                                            <input type="text" class="form-control" id="employee_address" required
                                                placeholder="Address" v-model="employee_address">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_email">Email</label>
                                            <input type="email" class="form-control" id="employee_address" required
                                                placeholder="Email address" v-model="employee_email">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_email">Role Name</label>
                                            <select class="form-control" v-model="selectedRoleId">
                                                <option value="" disabled selected>Select Role</option>
                                                <option v-for="role in roles.filter(r => r.name !== 'Super Admin')"
                                                    :key="role.id" :value="role.name">
                                                    {{ role.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary" type="submit">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AdminLayout>
</template>

<style scoped>
canvas {
    height: 400px !important;
}
</style>
