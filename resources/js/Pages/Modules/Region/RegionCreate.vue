<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const toast = useToast();
const region_code = ref('');
const region_name = ref('');
const formRef = ref(null);

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    formData.append('region_code', region_code.value);
    formData.append('region_name', region_name.value);

    store('region.store', formData);
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
                        <h4 class="m-0">REGION CREATE</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('region.index')">Region</a></li>
                            <li class="breadcrumb-item active">Region Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body">
                                <h5 class="w-75 mb-3 text-bold">Region Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="region_code">Region Code</label>
                                            <input type="text" class="form-control" id="region_code" required placeholder="Region Code" v-model="region_code">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="region_name">Region Name</label>
                                            <input type="text" class="form-control" id="region_name" required placeholder="Region Name" v-model="region_name">
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
