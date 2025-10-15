<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const toast = useToast();
const search_key = ref('');


async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    formData.append('chemical_name', chemical_name.value);

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
                        <h4 class="m-0">Add Chemical Create</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('con.sample.index')">Chemical</a></li>
                            <li class="breadcrumb-item active">Add Chemical Create</li>
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
                                <h5 class="w-75 mb-3 text-bold">Create</h5>
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
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 search-box">
                                                    <ul class="search-list">
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                        <li class="search-item">abc</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <table class="table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Chemical Code</th>
                                                            <th scope="col">Chemical Name</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">CH-0001</th>
                                                            <td>Acetone</td>
                                                            <td>
                                                                <input type="number" class="form-control tbl-quantity">
                                                            </td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-12 d-flex  align-items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            height="24px" viewBox="0 -960 960 960"
                                                                            width="24px" fill="#D16D6A">
                                                                            <path
                                                                                d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                                        </svg>
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
.tbl-quantity{
    border-color: none !important;
    padding-right: calc(1.5em + .75rem) !important;
    background-image:none !important;
    background-repeat: no-repeat;
    background-position: right calc(.375em + .1875rem) center;
    background-size: calc(.75em + .375rem) calc(.75em + .375rem);
}
</style>
