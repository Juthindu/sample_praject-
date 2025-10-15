<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import { Head } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import { useToast } from 'vue-toastification'
import { store } from '../../../main';

const props = defineProps({
    consumers: Array,
});
const consumers = ref(props.consumers ? props.consumers : null);

const toast = useToast();
const date = ref('');
const selected_consumer_id = ref('');
const transport = ref(0);
const vat = ref(0);
const laboratory_no = ref('Region LAB');
const paid_amount = ref(0);
const payment_status = ref("payment_status");

// const total_payment_amount = ref(0);
const formRef = ref(null);

const prices = {
    physical: 200,
    coliform: 150,
    coli: 150,
    chemical: 11850,
    colour: 100,
    turbidity: 100,
    bacteriological: 300,
    ph: 100,
    electrical: 100,
    chloride: 550,
    alkalinity: 400,
    nitrate: 1150,
    nitrite: 1000,
    fluoride: 350,
    phosphate: 950,
    dissolvedSolid: 550,
    hardness: 500,
    iron: 600,
    sulphate: 900,
    calcium: 500,
    manganese: 2600,
};
const samples = ref([
    {
        id: 1,
        reference_number: '',
        source: '',
        sample_locations: '',
        quantity: '',
        temperature: '',
        collected: '',
        weatherCondition: '',
        physical: false,
        colour: false,
        turbidity: false,
        bacteriological: false,
        coliform: false,
        coli: false,
        chemical: false,
        ph: false,
        electrical: false,
        chloride: false,
        alkalinity: false,
        nitrate: false,
        nitrite: false,
        fluoride: false,
        phosphate: false,
        dissolvedSolid: false,
        hardness: false,
        iron: false,
        sulphate: false,
        calcium: false,
        manganese: false,
    },
]);

const sample_numbers = computed(() => samples.value.length);

let nextSampleId = 2;

function addSample() {
    samples.value.push({
        id: nextSampleId++,
        reference_number: '',
        sample_locations: ''
    });
}

function removeSample(idx) {
    if (samples.value.length === 1) {
        toast.warning('At least one sample is required');
        return;
    }
    samples.value.splice(idx, 1);
}

const subtotal = computed(() => {
    let sum = 0;

    samples.value.forEach(s => {
        // ✅ Physical Quality
        if (s.physical) {
            sum += prices.physical;
        } else {
            if (s.colour) sum += prices.colour;
            if (s.turbidity) sum += prices.turbidity;
        }

        // ✅ Bacteriological Quality
        if (s.bacteriological) {
            sum += prices.bacteriological;
        } else {
            if (s.coliform) sum += prices.coliform;
            if (s.coli) sum += prices.coli;
        }

        // ✅ Chemical Quality
        if (s.chemical) {
            sum += prices.chemical;
        } else {
            if (s.ph) sum += prices.ph;
            if (s.electrical) sum += prices.electrical;
            if (s.chloride) sum += prices.chloride;
            if (s.alkalinity) sum += prices.alkalinity;
            if (s.nitrate) sum += prices.nitrate;
            if (s.nitrite) sum += prices.nitrite;
            if (s.fluoride) sum += prices.fluoride;
            if (s.phosphate) sum += prices.phosphate;
            if (s.dissolvedSolid) sum += prices.dissolvedSolid;
            if (s.hardness) sum += prices.hardness;
            if (s.iron) sum += prices.iron;
            if (s.sulphate) sum += prices.sulphate;
            if (s.calcium) sum += prices.calcium;
            if (s.manganese) sum += prices.manganese;
        }
    });

    return sum + Number(transport.value || 0);
});


const total_payment_amount = computed(() => {
    const sub = subtotal.value || 0;
    return sub + (sub * Number(vat.value || 0)) / 100;
});

const balance = computed(() => {
    return (total_payment_amount.value || 0) - (paid_amount.value || 0);
});

const selected_consumer = computed(() => {
    return consumers.value.find(c => c.id === selected_consumer_id.value) || null;
});

async function handleSubmit() {
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData();

    // 🔹 Global fields
    formData.append('date', date.value);
    formData.append('consumer_id', selected_consumer_id.value);
    formData.append('laboratory_no', laboratory_no.value);
    formData.append('transport', transport.value);
    formData.append('vat', vat.value);
    formData.append('paid_amount', paid_amount.value);
    formData.append('payment_status', payment_status.value);
    formData.append('subtotal', subtotal.value);
    formData.append('total_payment_amount', total_payment_amount.value);
    formData.append('balance', balance.value);
    formData.append('sample_count', sample_numbers.value);

    // 🔹 Loop over each sample
    samples.value.forEach((s, index) => {
        formData.append(`samples[${index}][id]`, s.id);
        formData.append(`samples[${index}][reference_number]`, s.reference_number);
        formData.append(`samples[${index}][source]`, s.source || "");
        formData.append(`samples[${index}][sample_locations]`, s.sample_locations);
        formData.append(`samples[${index}][quantity]`, s.quantity || "");
        formData.append(`samples[${index}][temperature]`, s.temperature || "");
        formData.append(`samples[${index}][collected]`, s.collected || "");
        formData.append(`samples[${index}][weatherCondition]`, s.weatherCondition || "");

        // ✅ Add main + sub tests (booleans converted to 1/0 for backend)
        Object.keys(prices).forEach(testKey => {
            formData.append(`samples[${index}][${testKey}]`, s[testKey] ? 1 : 0);
        });
    });

    store('consumer.samples.store', formData);
}

</script>

<template>
    <AdminLayout>

        <Head title="consumer-sample-create" />

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">CONSUMER SAMPLE CREATE</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('con.sample.index')">Consumer Sample</a>
                            </li>
                            <li class="breadcrumb-item active">Consumer Sample Create</li>
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
                                <h5 class="w-75 mb-3 text-bold">Consumer Sample Details</h5>
                                <form class="needs-validation" novalidate @submit.prevent="handleSubmit" ref="formRef">
                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input type="date" class="form-control" id="date" required
                                                    placeholder="Date" v-model="date" name="date">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="nic">NIC</label>
                                                <input type="text" class="form-control" id="nic" placeholder="NIC"
                                                    :value="selected_consumer?.nic || ''" name="nic" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="consumer_id">Consumers</label>
                                                <select class="form-control" id="consumer_id" required
                                                    v-model="selected_consumer_id">
                                                    <option value="" selected>Select Consumers</option>
                                                    <option v-for="consumer in consumers" :value="consumer.id"
                                                        :key="consumer.id">
                                                        {{ consumer.first_name }} {{ consumer.last_name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" class="form-control" id="address"
                                                    placeholder="Address" :value="selected_consumer?.address || ''"
                                                    name="address" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-info card-outline mt-4" v-for="(s, i) in samples" :key="s.id">
                                        <div class="card-header">
                                            <h5 class="card-title">Create Sample #{{ i + 1 }}</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" @click="addSample"
                                                    aria-label="Add sample">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24"
                                                        viewBox="0 -960 960 960" width="24" fill="#0000F5">
                                                        <path
                                                            d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Z" />
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-tool"
                                                    :disabled="samples.length === 1" @click="removeSample(i)"
                                                    aria-label="Remove sample">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                        viewBox="0 -960 960 960" width="24px" fill="#EA3323">
                                                        <path
                                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md-3 mb-3">
                                                    <label :for="`reference_number_${s.id}`">Reference number</label>
                                                    <input type="text" class="form-control"
                                                        :id="`reference_number_${s.id}`" required
                                                        placeholder="Reference Number" v-model="s.reference_number"
                                                        name="reference_number[]">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label :for="`source${s.id}`">Source</label>
                                                    <input type="text" class="form-control" :id="`source${s.id}`"
                                                        required placeholder="Source" v-model="s.source"
                                                        name="source[]">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label :for="`sample_locations_${s.id}`">Sample Location</label>
                                                    <input type="text" class="form-control"
                                                        :id="`sample_locations_${s.id}`" required
                                                        placeholder="Sample Locations" v-model="s.sample_locations"
                                                        name="sample_locations[]">
                                                </div>
                                                <div class="col-3 mb-3">
                                                    <label :for="`quantity${s.id}`">Quantity</label>
                                                    <input type="text" class="form-control" :id="`quantity${s.id}`"
                                                        required placeholder="Sample Quantity" v-model="s.quantity"
                                                        name="quantity[]">
                                                </div>
                                                <div class="col-3 mb-3">
                                                    <label :for="`temperature${s.id}`">Temperature</label>
                                                    <input type="text" class="form-control" :id="`temperature${s.id}`"
                                                        required placeholder="Sample Temperature"
                                                        v-model="s.temperature" name="temperature[]">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label :for="`collected${s.id}`">Collected Date And Time</label>
                                                    <input type="datetime-local" class="form-control"
                                                        :id="`collected${s.id}`" required placeholder="Sample Collected"
                                                        v-model="s.collected" name="collected[]" />
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label :for="`weatherCondition${s.id}`">Weather Condition</label>
                                                    <input type="text" class="form-control"
                                                        :id="`weatherCondition${s.id}`" required
                                                        placeholder="Weather Condition" v-model="s.weatherCondition"
                                                        name="weatherCondition[]">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="col-12 mb-3">
                                                        <!-- Main checkbox -->
                                                        <div class="form-check mt-2">
                                                            <input class="form-check-input big-checkbox" type="checkbox"
                                                                :id="`physical${s.id}`" v-model="s.physical" />
                                                            <h5 class="form-check-label" :for="`physical${s.id}`">
                                                                Physical Quality : Rs : 200.00
                                                            </h5>
                                                        </div>
                                                        <!-- Sub-checkboxes (shown only if main is checked) -->
                                                        <div v-if="!s.physical" class="ms-3 mt-3 ml-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    :id="`colour${s.id}`" value="colour"
                                                                    v-model="s.colour" />
                                                                <label class="form-check-label"
                                                                    :for="`colour${s.id}`">Colour : Rs : 100.00</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    :id="`turbidity${s.id}`" value="turbidity"
                                                                    v-model="s.turbidity" />
                                                                <label class="form-check-label"
                                                                    :for="`turbidity${s.id}`">Turbidity : Rs :
                                                                    100.00</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <!-- Main checkbox -->
                                                        <div class="form-check mt-2">
                                                            <input class="form-check-input big-checkbox" type="checkbox"
                                                                :id="`bacteriological${s.id}`"
                                                                v-model="s.bacteriological" />
                                                            <h5 class="form-check-label"
                                                                :for="`bacteriological${s.id}`">
                                                                Bacteriological Quality : Rs : 300.00
                                                            </h5>
                                                        </div>

                                                        <!-- Sub-checkboxes (shown only if main is checked) -->
                                                        <div v-if="!s.bacteriological" class="ms-3 mt-3 ml-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    :id="`coliform${s.id}`" value="coliform"
                                                                    v-model="s.coliform" />
                                                                <label class="form-check-label"
                                                                    :for="`coliform${s.id}`">Total Coliform : Rs :
                                                                    150.00</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    :id="`coli${s.id}`" value="coli" v-model="s.coli" />
                                                                <label class="form-check-label"
                                                                    :for="`coli${s.id}`">E.Coli : Rs : 150.00</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3">
                                                    <!-- Main checkbox -->
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input big-checkbox" type="checkbox"
                                                            :id="`chemical${s.id}`" v-model="s.chemical" />
                                                        <h5 class="form-check-label" :for="`chemical${s.id}`">
                                                            Chemical Quality : Rs : 11,850.00
                                                        </h5>
                                                    </div>
                                                    <!-- Sub-checkboxes (shown only if main is checked) -->
                                                    <div v-if="!s.chemical" class="ms-3 mt-3 ml-4">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`ph${s.id}`" value="ph" v-model="s.ph" />
                                                            <label class="form-check-label" :for="`ph${s.id}`">Ph@25'C :
                                                                Rs : 100.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`electrical${s.id}`" value="electrical"
                                                                v-model="s.electrical" />
                                                            <label class="form-check-label"
                                                                :for="`electrical${s.id}`">Electrical
                                                                Conductivity : Rs : 100.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`chloride${s.id}`" value="chloride"
                                                                v-model="s.chloride" />
                                                            <label class="form-check-label"
                                                                :for="`chloride${s.id}`">Chloride (as CI) : Rs :
                                                                550.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`alkalinity${s.id}`" value="alkalinity"
                                                                v-model="s.alkalinity" />
                                                            <label class="form-check-label"
                                                                :for="`alkalinity${s.id}`">Total Alkalinity (as
                                                                CaCO3) : Rs : 400.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`nitrate${s.id}`" value="nitrate"
                                                                v-model="s.nitrate" />
                                                            <label class="form-check-label"
                                                                :for="`nitrate${s.id}`">Nitrate (as NO3-) : Rs :
                                                                1150.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`nitrite${s.id}`" value="nitrite"
                                                                v-model="s.nitrite" />
                                                            <label class="form-check-label"
                                                                :for="`nitrite${s.id}`">Nitrite (as NO2-) : Rs :
                                                                1000.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`fluoride${s.id}`" value="fluoride"
                                                                v-model="s.fluoride" />
                                                            <label class="form-check-label"
                                                                :for="`fluoride${s.id}`">Fluoride (as F-) : Rs :
                                                                350.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`phosphate${s.id}`" value="phosphate"
                                                                v-model="s.phosphate" />
                                                            <label class="form-check-label"
                                                                :for="`phosphate${s.id}`">Total phosphate (as
                                                                PO43-) : Rs : 950.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`dissolvedSolid${s.id}`" value="dissolvedSolid"
                                                                v-model="s.dissolvedSolid" />
                                                            <label class="form-check-label"
                                                                :for="`dissolvedSolid${s.id}`">Total Dissolved
                                                                Solid : Rs : 550.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`hardness${s.id}`" value="hardness"
                                                                v-model="s.hardness" />
                                                            <label class="form-check-label"
                                                                :for="`hardness${s.id}`">Total Hardness (as
                                                                CaCO3) : Rs : 500.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`iron${s.id}`" value="iron" v-model="s.iron" />
                                                            <label class="form-check-label" :for="`iron${s.id}`">Total
                                                                Iron : Rs : 600.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`sulphate${s.id}`" value="sulphate"
                                                                v-model="s.sulphate" />
                                                            <label class="form-check-label"
                                                                :for="`sulphate${s.id}`">Sulphate (as SO42-) : Rs :
                                                                900.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`calcium${s.id}`" value="calcium"
                                                                v-model="s.calcium" />
                                                            <label class="form-check-label"
                                                                :for="`calcium${s.id}`">Calcium : Rs : 500.00</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                :id="`manganese${s.id}`" value="manganese"
                                                                v-model="s.manganese" />
                                                            <label class="form-check-label"
                                                                :for="`manganese${s.id}`">Manganese : Rs :
                                                                2600.00</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <!-- <div class="col-md-3 mb-3">
                                            <label for="transport">Transport Cost</label>
                                            <input type="text" class="form-control" id="transport" required
                                                placeholder="Transport Cost" v-model="transport">
                                        </div> -->
                                        <div class="col-md-6 mb-3">
                                            <label for="vat">Vat %</label>
                                            <input type="number" class="form-control" id="vat" min="0" max="100"
                                                required placeholder="Vat %" v-model="vat" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="laboratory_no">Laboratory No</label>
                                            <input type="text" class="form-control" id="laboratory_no" required
                                                placeholder="Laboratory No" v-model="laboratory_no">
                                        </div>
                                        <!-- <div class="col-md-6 mb-3">
                                            <label for="paid_amount">Total Payment
                                                Amount</label>
                                            <input type="number" min="0" class="form-control" id="paid_amount" required
                                                placeholder="Total Payment Amount" v-model.number="paid_amount"
                                                name="paid_amount">
                                        </div> -->
                                        <!-- <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status</label>
                                                <select class="form-control" id="payment_status" disabled 
                                                    v-model="payment_status">
                                                    <option value="Unpaid" selected>Unpaid</option>
                                                    <option value="Paid">Paid</option>
                                                </select>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <table class="table table-sm table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Subtotal</th>
                                                        <td>Rs {{ subtotal.toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>VAT ({{ vat }}%)</th>
                                                        <td>Rs {{ (subtotal * vat / 100).toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Payment</th>
                                                        <td>Rs {{ total_payment_amount.toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Paid Amount</th>
                                                        <td>Rs {{ paid_amount.toFixed(2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Balance</th>
                                                        <td>Rs {{ balance.toFixed(2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between">

                                        <button type="button" class="btn btn-outline-primary" @click="addSample">+
                                            Add another sample</button>
                                        <button class="btn btn-primary" type="submit">Create</button>
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
