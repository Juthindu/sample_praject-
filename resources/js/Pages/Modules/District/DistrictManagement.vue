<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
// Define product table columns
const employee_table_columns = [
    { field: 'id', title: 'ID', isUnique: true },
    { field: 'region.region_code', title: 'Region Code' },
    { field: 'region.region_name', title: 'Region Name' },
    { field: 'district_name', title: 'District Name' },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];
</script>

<template>
    <AdminLayout>

        <Head title="District-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">DISTRICT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('dashboard')">Dashboard</a></li>
                            <li class="breadcrumb-item active">District Management</li>
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
                        <!-- <a href=""><button class="btn btn-primary mr-2">Export District</button></a> -->
                        <!-- <button class="btn btn-primary mr-2">Import District</button> -->
                        <a :href="route('district.create')"><button class="btn btn-primary mr-2">Create District</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable
                                    title="District TABLE"
                                    fetch_url="/api/districts"
                                    :columns="employee_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="District"
                                    edit_route_name = 'district.edit'
                                    delete_route_name= 'district.delete'
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
