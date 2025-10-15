<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
// Define product table columns
const oic_table_columns = [
    { field: 'id', title: 'ID', isUnique: true },
    { field: 'district.region.region_name', title: 'District Name' },
    { field: 'district.district_name', title: 'District Name' },
    { field: 'oic_name', title: 'oic Name' },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];
onMounted(() => {

});
</script>

<template>
    <AdminLayout>

        <Head title="Oic-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">OIC</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('dashboard')">Dashboard</a></li>
                            <li class="breadcrumb-item active">Oic Management</li>
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
                        <!-- <a href=""><button class="btn btn-primary mr-2">Export Oic</button></a> -->
                        <!-- <button class="btn btn-primary mr-2">Import Oic</button> -->
                        <a :href="route('oic.create')"><button class="btn btn-primary mr-2">Create oic</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable
                                    title="Oic TABLE"
                                    fetch_url="/api/oics"
                                    :columns="oic_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="Oic"
                                    edit_route_name = 'oic.edit'
                                    delete_route_name= 'oic.delete'
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
