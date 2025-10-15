<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
  data: Object,
  regions: Array,
});
const toast = useToast();
const id = ref(props.data.id ? props.data.id : null);
const regions = ref(props.regions? props.regions: null);
const region_id = ref(props.data.region_id ? props.data.region_id : null);
const district_name = ref(props.data.district_name ? props.data.district_name : null);
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
    formData.append('region_id', region_id.value);
    formData.append('district_name', district_name.value);

    store('district.update', formData);
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
                        <h4 class="m-0">DISTRICT EDIT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('district.index')">District</a></li>
                            <li class="breadcrumb-item active">District Edit</li>
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
                                <h5 class="w-75 mb-3 text-bold">District Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_email">Region Name</label>
                                            <select class="form-control" v-model="region_id">
                                                <option value="" disabled selected>Select Region</option>
                                                <option v-for="region in regions"
                                                    :key="region.id" :value="region.id">
                                                    {{ region.region_code + ' ' + region.region_name }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="district_name">District Name</label>
                                            <input type="text" class="form-control" id="district_name" required placeholder="District Name" v-model="district_name">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary" type="submit">Update</button>
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
