
<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { update } from '../../../main';

const props = defineProps({
  data: Object,
});

const toast = useToast();
const id = ref(props.data.id ? props.data.id : null);
const consumer_first_name = ref(props.data.first_name ? props.data.first_name : null);
const consumer_last_name = ref(props.data.last_name ? props.data.last_name : null);
const consumer_nic = ref(props.data.nic ? props.data.nic : null);
const consumer_contact_number = ref(props.data.contact_number ? props.data.contact_number : null);
const consumer_address = ref(props.data.address ? props.data.address : null);
const consumer_email = ref(props.data.email ? props.data.email : null);
const formRef = ref(null);

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    formData.append('id', id.value);
    formData.append('first_name', consumer_first_name.value);
    formData.append('last_name', consumer_last_name.value);
    formData.append('nic', consumer_nic.value);
    formData.append('contact_number', consumer_contact_number.value);
    formData.append('address', consumer_address.value);
    formData.append('email', consumer_email.value);
    update('new.consumer.update', formData);
}

</script>

<template>
    <AdminLayout>

        <Head title="Consumer-Edit" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">CONSUMER EDIT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('new.consumer.index')">Consumer</a></li>
                            <li class="breadcrumb-item active">Consumer Edit</li>
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
                                <h5 class="w-75 mb-3 text-bold">Consumer Edit</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="consumer_first_name">First Name</label>
                                            <input type="text" class="form-control" id="consumer_first_name" required
                                                placeholder="First Name" v-model="consumer_first_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="consumer_last_name">Last Name</label>
                                            <input type="text" class="form-control" id="consumer_last_name" required
                                                placeholder="Last Name" v-model="consumer_last_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="consumer_nic">NIC</label>
                                            <input type="text" class="form-control" id="consumer_nic_name" required
                                                placeholder="NIC" v-model="consumer_nic">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="consumer_contact_number">Contact number</label>
                                            <input type="number" class="form-control" id="consumer_contact_number" required
                                                placeholder="Contact Number" v-model="consumer_contact_number">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="consumer_contact_number">NIC</label>
                                            <input type="text" class="form-control" id="consumer_address" required
                                                placeholder="Address" v-model="consumer_address">
                                        </div>
                                         <div class="col-md-6 mb-3">
                                            <label for="consumer_email">Email</label>
                                            <input type="email" class="form-control" id="consumer_address" required
                                                placeholder="Email address" v-model="consumer_email">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary" type="submit">UPDATE</button>
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
