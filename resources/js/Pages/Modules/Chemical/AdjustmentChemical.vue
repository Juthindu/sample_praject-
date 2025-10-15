<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification'
import { store, searchChemical } from '../../../main';

const toast = useToast();
const search = ref('');
const formRef = ref(null);
const chemicals = ref([])
const selectedChemicals = ref([])

watch(search, async (newValue) => {
    if (!newValue) {
        chemicals.value = [];
        return;
    }
    try {
        const response = await searchChemical(route('chemical.search', { search: newValue }));
        chemicals.value = response.original;
        console.log(response.original);
    } catch (e) {
        console.error('search failed:', e);
        chemicals.value = [];
    }
});

const addChemicalToTable = (chemical) => {
    const exists = selectedChemicals.value.find(i => i.id === chemical.id);
    if (!exists) {
        selectedChemicals.value.push({
            ...chemical,
            quantity: 1,
            chemical_code: chemical.chemical_code || 0,
            chemical_name: chemical.chemical_name || 0,
            scal_metionment: chemical.scal_metionment || 0,
            process: 0,
        });
    }
    search.value = '';
    chemicals.value = [];
};

const remove = (id) => {
    selectedChemicals.value = selectedChemicals.value.filter(
        (chemical) => chemical.id !== id
    );
};
async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    selectedChemicals.value.forEach((c, index) => {
        formData.append(`chemicals[${index}][id]`, parseInt(c.id)); 
        formData.append(`chemicals[${index}][quantity]`, parseInt(c.quantity)); 
        formData.append(`chemicals[${index}][chemical_code]`, c.chemical_code); 
        formData.append(`chemicals[${index}][chemical_name]`, c.chemical_name); 
        formData.append(`chemicals[${index}][scal_metionment]`, c.scal_metionment); 
        formData.append(`chemicals[${index}][process]`, c.process);
    });
    store('adjustment.store', formData);
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
                        <h4 class="m-0 text-uppercase">Adjustments Chemical</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('con.sample.index')">Chemical</a></li>
                            <li class="breadcrumb-item active ">Adjustments Chemical</li>
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
                                <h5 class="w-75 mb-3 text-bold text-uppercase">Adjustment</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-12 mb-4">
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="search-key-icon"><svg
                                                                xmlns="http://www.w3.org/2000/svg" height="24px"
                                                                viewBox="0 -960 960 960" width="24px" fill="#e3e3e3">
                                                                <path
                                                                    d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                                                            </svg></span>
                                                    </div>
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Chemical" aria-label="search-key"
                                                        v-model="search" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 search-box" v-if="search && chemicals.length > 0">
                                                    <ul class="search-list">
                                                        <li v-for="chemical in chemicals" :key="chemical.id"
                                                            @click="addChemicalToTable(chemical)" class="search-item">
                                                            {{ chemical.chemical_code }} {{ chemical.chemical_name }}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div v-else-if="search && chemicals.length === 0" class="search-result">
                                                    <p>No chemicals found...</p>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Chemical Code</th>
                                                            <th scope="col">Chemical Name</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">scale</th>
                                                            <th scope="col">Process</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="c in selectedChemicals" :key="c.id">
                                                            <th scope="row">{{ c.chemical_code }}</th>
                                                            <td>{{ c.chemical_name }}</td>
                                                            <td>
                                                                <input type="number" min="0"
                                                                    class="form-control tbl-quantity"
                                                                    v-model="c.quantity">
                                                            </td>
                                                            <td>{{ c.scal_metionment }}</td>
                                                            <td>
                                                                <select id="selectedWarehouse" v-model="c.process"
                                                                    class="form-control form-select d-block">
                                                                    <option value='addition'>Addition</option>
                                                                    <option value='subtraction'>Subtraction</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-12 d-flex  align-items-center">
                                                                        <a @click="remove(c.id)">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                height="24px" viewBox="0 -960 960 960"
                                                                                width="24px" fill="#D16D6A">
                                                                                <path
                                                                                    d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                                            </svg>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
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

.tbl-quantity {
    border-color: none !important;
    padding-right: calc(1.5em + .75rem) !important;
    background-image: none !important;
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
}
</style>
