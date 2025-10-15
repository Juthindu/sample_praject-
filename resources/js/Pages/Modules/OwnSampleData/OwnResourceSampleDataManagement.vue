<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
// Define product table columns
const consumer_sample_table_columns = [
    { field: 'id', title: 'Id', isUnique: true },
    { field: 'reference_number', title: 'Reference Number'},
    { field: 'quantity', title: 'Quantity' },
    { field: 'collected', title: 'Collected' },
    { field: 'temperature', title: 'Temperature' },
    { field: 'weather_condition', title: 'Weather Condition' },
    // {
    //     field: 'payment_status',
    //     title: 'Payment Status',
    //     cellRenderer: (row) => {
    //         const map = {
    //             'Failed': 'badge badge-danger',
    //             'Unpaid': 'badge badge-warning',
    //             'Paid': 'badge badge-success',
    //         };
    //         return `<span class="${map[row.payment_status] || 'badge badge-light'}">
    //             ${row.payment_status}
    //           </span>`;
    //     }
    // },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];
onMounted(() => {

});
</script>

<template>
    <AdminLayout>
        <Head title="NWSDB-Water-Sample-Data-Management" />
        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">NWSDB WATER RESOURCE SAMPLE DATA</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('dashboard')">Dashboard</a></li>
                            <li class="breadcrumb-item active">NWSDB Water Resource Sample Data Management</li>
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
                        <!-- <a href=""><button class="btn btn-primary mr-2">Export Consumer Sample</button></a> -->
                        <!-- <button class="btn btn-primary mr-2">Import Consumer Sample</button> -->
                        <a :href="route('own.sample.data.create')"><button class="btn btn-primary mr-2">Add NWSDB Water Resource Sample Test Result</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable
                                    title="NWSDB  WATER  SAMPLE TABLE"
                                    fetch_url="/api/own-sample-data"
                                    :columns="consumer_sample_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="NWSDB Water Resource Sample"
                                    edit_route_name= 'own.sample.data.edit'
                                    use_delete_button=true 
                                    use_test_button=true 
                                    use_edit_button=true 
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
