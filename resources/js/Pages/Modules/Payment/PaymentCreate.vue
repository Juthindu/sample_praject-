<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
    samples: Array,
    data: Object,
});
const toast = useToast();

const samples = ref(props.samples?.length ? props.samples : []);
const consumer_sample_id = ref(props.data?.id ?? null);
const sample = ref(null);
const paid_amount = ref(props.data?.paid_amount ?? '');
const payment_status = ref(props.data?.payment_status ?? '');
const formRef = ref(null);

if (props.data) {
    sample.value = {
        ...props.data,
        consumer: props.data.consumer ?? {},
        sample_data: props.data.sample_data ?? [],
    };
}

watch(consumer_sample_id, (newId) => {
    if (!newId) {
        sample.value = null;
        paid_amount.value = '';
        payment_status.value = '';
        return;
    }

    const found = samples.value.find(p => p.id === newId);
    if (found) {
        sample.value = {
            ...found,
            consumer: found.consumer ?? {},
            sample_data: found.sample_data ?? [],
        };
        paid_amount.value = found.paid_amount ?? '';
        payment_status.value = found.payment_status ?? '';
    }
});

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }

    const totalPaid = Number(sample.value?.paid_amount || 0) + Number(paid_amount.value || 0);
    const totalAmount = Number(sample.value?.total_payment_amount || 0);

    if (totalPaid < totalAmount && payment_status.value == 'Paid') {
        toast.error("please check the payment status.");
        return;
    }
    if (totalPaid > totalAmount && payment_status.value == 'Unpaid') {
        toast.error("please check the payment status.");
        return;
    }

    const formData = new FormData();
    formData.append('consumer_sample_id', sample.value.id);
    formData.append('paid_amount', paid_amount.value);
    formData.append('payment_status', payment_status.value);

    store('consumer.samples.payment', formData);
}

</script>

<template>
    <AdminLayout>

        <Head title="Make-Payment" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">MAKE PAYMENT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('payment.index')">Payment Management</a></li>
                            <li class="breadcrumb-item active">Make Payment</li>
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
                                <h5 class="w-75 mb-3 text-bold">Consumer & Payment Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-12 mb-3">
                                            <label for="consumer_sample_id">Select Sample Reference</label>
                                            <select class="form-control" id="consumer_sample_id" required
                                                v-model="consumer_sample_id">
                                                <option :value=0 selected>Select Consumers</option>
                                                <option v-for="sample in samples" :value="sample.id" :key="sample.id">
                                                    Name :
                                                    {{ sample.consumer.first_name }} {{ sample.consumer.last_name }}
                                                    /NIC: {{ sample.consumer.nic }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="sample">
                                        <div class="card card-default">
                                            <div class="card-body mb-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p><strong>Full Name:</strong> {{ sample.consumer.first_name }} {{sample.consumer.last_name }}</p>
                                                        <p><strong>NIC:</strong> {{ sample.consumer.nic }}</p>
                                                        <p><strong>Address:</strong> {{ sample.consumer.address }}</p>
                                                        <p><strong>Email:</strong> {{ sample.consumer.email }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><strong>Sample Count:</strong> {{ sample.sample_count }}</p>
                                                        <p><strong>Total Amount:</strong> Rs. {{ sample.total_payment_amount}}</p>
                                                        <p><strong>Paid Amount:</strong> Rs. {{ Number(sample.paid_amount) + Number(paid_amount)}}</p>
                                                        <p><strong>Balance:</strong> Rs. {{ Number(sample.total_payment_amount) - (Number(sample.paid_amount) + Number(paid_amount)) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                                <label for="paid_amount">Payment Amount</label>
                                                <input type="text" class="form-control" id="paid_amount" required
                                                    placeholder="Payment Amount" v-model="paid_amount">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                                <label for="payment_status">Payment Status</label>
                                                <select class="form-control" id="payment_status" required
                                                    v-model="payment_status">
                                                    <option value="" selected>Select Payment Status</option>
                                                    <option value="Paid">Paid</option>
                                                    <option value="Unpaid">Unpaid</option>
                                                </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-warning" type="submit">Confirm Payment</button>
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
