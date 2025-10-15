<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
  districts: Array,
});

const toast = useToast();

const districts = ref(props.districts? props.districts: null);
const district_id = ref('');
const oic_name = ref('');
const formRef = ref(null);

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    formData.append('district_id', district_id.value);
    formData.append('oic_name', oic_name.value);

    store('oic.store', formData);
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
                        <h4 class="m-0">OIC CREATE</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('oic.index')">Oic</a></li>
                            <li class="breadcrumb-item active">Oic Create</li>
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
                                <h5 class="w-75 mb-3 text-bold">OIC Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="employee_email">District Name</label>
                                            <select class="form-control" v-model="district_id">
                                                <option value="" disabled selected>Select District</option>
                                                <option v-for="district in districts"
                                                    :key="district.id" :value="district.id">
                                                    {{ district.district_name}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="oic_name">OIC Name</label>
                                            <input type="text" class="form-control" id="oic_name" required placeholder="Oic Name" v-model="oic_name">
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
