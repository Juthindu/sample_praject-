<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
// Define product table columns
const employee_table_columns = [
    { field: 'id', title: 'Id', isUnique: true },
    { field: 'consumer.first_name', title: 'First Name', isUnique: true },
    { field: 'consumer.last_name', title: 'Last Name' },
    { field: 'consumer.contact_number', title: 'Contact Number' },
    { field: 'sample_count', title: 'Sample Count' },
    { field: 'total_payment_amount', title: 'Total Payment Amount' },
    { field: 'paid_amount', title: 'Paid Amount' },
    {
        field: 'payment_status',
        title: 'Payment Status',
        cellRenderer: (row) => {
            const map = {
                'Failed': 'badge badge-danger',
                'Unpaid': 'badge badge-warning',
                'Paid': 'badge badge-success',
            };
            return `<span class="${map[row.payment_status] || 'badge badge-light'}">
                ${row.payment_status}
              </span>`;
        }
    },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];
onMounted(() => {

});
</script>

<template>
    <AdminLayout>

        <Head title="Payment-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">PAYMENT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('dashboard')">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payment Management</li>
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
                        <!-- <a href=""><button class="btn btn-primary mr-2">Export Payment</button></a> -->
                        <!-- <button class="btn btn-primary mr-2">Import Payment</button> -->
                        <a :href="route('payment.create')"><button class="btn btn-primary mr-2">Create Payment</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable 
                                    title="Oic TABLE" 
                                    fetch_url="/api/consumer-sample-payments"
                                    :columns="employee_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="Employee" 
                                    edit_route_name='payment.edit' 
                                    use_delete_button=true 
                                    use_payment_button=true 
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
