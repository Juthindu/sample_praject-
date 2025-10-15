<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
// Define product table columns
const consumer_table_columns = [
    { field: 'id', title: 'ID', isUnique: true },
    { field: 'first_name', title: 'First Name' },
    { field: 'last_name', title: 'Last Name' },
    { field: 'nic', title: 'NIC' },
    { field: 'contact_number', title: 'Contact Number' },
    { field: 'address', title: 'Address' },
    { field: 'email', title: 'Email' },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];
onMounted(() => {

});
</script>

<template>
    <AdminLayout>

        <Head title="Consumer-Management" />
        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">CONSUMER</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('dashboard')">Dashboard</a></li>
                            <li class="breadcrumb-item active">Consumer Management</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- <a href=""><button class="btn btn-primary mr-2">Export Consumer</button></a> -->
                        <!-- <button class="btn btn-primary mr-2">Import Consumer</button> -->
                        <a :href="route('new.consumer.create')"><button class="btn btn-primary mr-2">Create Consumer</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable
                                    title="Consumer TABLE"
                                    fetch_url="/api/consumers"
                                    :columns="consumer_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="Consumer"
                                    edit_route_name = 'new.consumer.edit'
                                    delete_route_name= 'new.consumer.delete'
                                    view_button = false
                                />
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
