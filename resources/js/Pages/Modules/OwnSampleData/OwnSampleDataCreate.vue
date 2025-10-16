<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref, computed, watch } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
  sampleData: {
    type: Array,
    default: () => []
  },
  data: {
    type: Object,
    default: null
  }
});

const selectedSample = ref(props.data ?? null);
const sampleData = ref(props.sampleData ?? []);
const data_id = ref(props.data?.id?? '');
const testing_status = ref(props.data?.testing_status?? '');

const toast = useToast();
const formRef = ref(null);

watch(data_id, (newId) => {
    if (!newId) {
        selectedSample.value = null;
        return;
    }
    selectedSample.value = sampleData.value.find(d => d.id === newId) || null;
});

async function handleSubmit() {
    const form = formRef.value;
    if (!form.checkValidity()) {
        toast.error("Please fill out all required fields.");
        form.classList.add("was-validated");
        return;
    }

    const formData = new FormData();
    formData.append("sample_data_id", selectedSample.value.id);
    formData.append("testing_status", testing_status.value);

    selectedSample.value.tests.forEach(test => {
        const details = testDetails[test.test]; // lookup by test key (like "colour")

        formData.append(`tests[${test.id}][id]`, test.id);
        formData.append(`tests[${test.id}][name]`, test.test);
        formData.append(`tests[${test.id}][result]`, test.result);
        formData.append(`tests[${test.id}][times]`, test.times ?? 0);
        formData.append(`tests[${test.id}][quantity]`, details.quantity);
    });

    store("own.sample.data.update", formData);
}
const testDetails = {
  physical: { name: "Physical Quality" },
  colour: { name: "Colour", standard: "APHA 2120 C", limit: 15, unit: "Pt/Co unit" ,quantity:10},
  turbidity: { name: "Turbidity", standard: "APHA 2130 B", limit: 2, unit: "NTU" ,quantity:10},
  ph: { name: "pH @25°C", standard: "A 4500-H B", limit: "6.5 to 8.5", unit: "" ,quantity:10},
  electrical: { name: "Electrical Conductivity", standard: "APHA 2510 B", limit: "", unit: "µS/cm" ,quantity:10},
  chloride: { name: "Chloride (as CI)", standard: "APHA 4500-C1-B", limit: 250, unit: "mg/L" ,quantity:10},
  alkalinity: { name: "Total Alkalinity (as CaCO3)", standard: "APHA 2320", limit: 200, unit: "mg/L" ,quantity:10},
  nitrate: { name: "Nitrate (as NO3-)", standard: "APHA 4500- NO3- E, Adapted method", limit: 50, unit: "mg/L" ,quantity:10},
  nitrite: { name: "Nitrite (as NO2)", standard: "APHA 4500- NO₂- B, Adapted method", limit: 3, unit: "mg/L" ,quantity:10},
  fluoride: { name: "Fluoride (as F-)", standard: "APHA 4500-F-D, Adapted method", limit: 1, unit: "mg/L" ,quantity:10},
  phosphate: { name: "Total phosphate (as PO43-)", standard: "APHA 3500-P E, Adapted method", limit: 2, unit: "mg/L" ,quantity:10},
  dissolvedSolid: { name: "Total Dissolved Solid", standard: "APHA 2540 C", limit: 500, unit: "mg/L" ,quantity:10},
  hardness: { name: "Total Hardness (as CaCO3)", standard: "APHA 2340 C", limit: 250, unit: "mg/L" ,quantity:10},
  iron: { name: "Total Iron", standard: "APHA 4500- Fe B, Adapted method", limit: 0.3, unit: "mg/L" ,quantity:10},
  sulphate: { name: "Sulphate (as SO42-)", standard: "APHA 4500-SO42- E, Adapted method", limit: 250, unit: "mg/L" ,quantity:10},
  calcium: { name: "Calcium", standard: "APHA 3500-Ca B", limit: 100, unit: "mg/L" ,quantity:10},
  manganese: { name: "Manganese", standard: "APHA 3111 Mn B, Adapted method", limit: 0.1, unit: "mg/L" ,quantity:10},
  bacteriological: { name: "Bacteriological Quality" },
  coliform: { name: "Total Coliform", standard: "ISO 9308-1:2014", limit: 10, unit: "Nos/100mL" ,quantity:10},
  coli: { name: "E.Coli", standard: "ISO 9308-1:2014", limit: "Nil", unit: "Nos/100mL",quantity:10 }
};

</script>

<template>
    <AdminLayout>

        <Head title="consumer-sample-Result" />

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">NWSDB WATER RESOURCE SAMPLE RESULT</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('consumer.sample.data.test.index')">
                            <li class="breadcrumb-item active">Consumer Sample Data Management</li></a>
                            </li>
                            <li class="breadcrumb-item active">Consumer Sample Result</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body">
                                <h5 class="w-75 mb-3 text-bold">Sample Testing Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-6 mt-3 mb-3">
                                            <div class="form-group">
                                                <label for="consumer_id">Select Sample Reference</label>
                                                <select class="form-control" id="consumer_id" required
                                                    v-model="data_id">
                                                    <option value="" selected>Select Consumers</option>
                                                    <option v-for="data in sampleData" :value="data.id" :key="data.id">
                                                        {{ data.reference_number }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div v-if="selectedSample">
                                                <h5 class="mt-4 mb-3 text-bold">Tests & Results</h5>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>PARAMETERS</th>
                                                            <th>Results</th>
                                                            <th>Methods</th>
                                                            <th>SLS 614:2013</th>
                                                            <th>units</th>
                                                            <th>Times</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="t in selectedSample.tests" :key="t.id">
                                                            <td>{{ testDetails[t.test].name }}</td>
                                                            <td>
                                                                <input type="text" class="form-control" v-model="t.result" placeholder="Enter result">
                                                            </td>
                                                            <td>{{ testDetails[t.test].standard || '-' }}</td>
                                                            <td>{{ testDetails[t.test].limit || '-' }}</td>
                                                            <td>{{ testDetails[t.test].unit || '-' }}</td>
                                                            <td>
                                                                <input type="text" class="form-control" v-model="t.times" placeholder="How many time test">
                                                            </td>
                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-3 mb-3">
                                            <div class="form-group">
                                                <label for="testing_status">Sample Testing Status</label>
                                                <select class="form-control" id="testing_status" required
                                                    v-model="testing_status">
                                                    <option value="" selected>Select Testing Status</option>
                                                    <option value="Completed">Completed</option>
                                                    <option value="Ongoing">Ongoing</option>
                                                </select>
                                            </div>
                                        </div>
                                    <div class="col-md-6 mt-3 align-content-end text-right">
                                        <button class="btn btn-success" type="submit">Save Results</button>
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

.big-checkbox {
    transform: scale(1.5);
    /* make checkbox 1.5x bigger */
    margin-right: 10px;
}
</style>
