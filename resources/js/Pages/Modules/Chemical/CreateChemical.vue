
<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const toast = useToast();
const chemical_name = ref('');
const chemical_code = ref('');
const quantity = ref('');
const scal_metionment = ref('');
const formRef = ref(null);

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    
    formData.append('chemical_name', chemical_name.value);
    formData.append('chemical_code', chemical_code.value);
    formData.append('quantity', quantity.value);
    formData.append('scal_metionment', scal_metionment.value);

    store('chemical.store', formData);
}

</script>

<template>
    <AdminLayout>

        <Head title="consumer-sample-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">CHEMICAL CREATE</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('con.sample.index')">Chemical</a></li>
                            <li class="breadcrumb-item active">Chemical Create</li>
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
                                <h5 class="w-75 mb-3 text-bold">Chemical Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="chemical_name">Chemical Name </label>
                                            <input type="text" class="form-control" id="chemical_name" required
                                                placeholder="Chemical Name" v-model="chemical_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="chemical_code">Chemical Code</label>
                                            <input type="text" class="form-control" id="chemical_code" required
                                                placeholder="Chemical Code" v-model="chemical_code">
                                        </div>
                                           <div class="col-6 mb-3">
                                            <label for="total_payment_amount">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" required
                                                placeholder="Quantity" v-model="quantity">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="scal_metionment">Scal Metionment</label>
                                            <input type="text" class="form-control" id="scal_metionment" required
                                                placeholder="Scal Metionment" v-model="scal_metionment">
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
