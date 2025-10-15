<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import $ from 'jquery';
import { useToast } from 'vue-toastification'
import axios from 'axios';

const toast = useToast();
const emit = defineEmits(['edit-item']);//new change
const props = defineProps<{
    title: string,
    fetch_url: string,
    columns: Array<any>,
    table_icon: string,
    modal_title: string,
    edit_route_name: string,
    delete_route_name: string,
    row_action_route_name?: string; // New: Optional route name for an additional row action
    row_action_button_text?: string; // New: Optional button text for the additional row action
    view_route_name?: string;         // New: Optional route name for viewing
    payment_route_name?: string; 
    use_view_modal?: boolean;         // New: Flag to use the modal for viewing
    view_button_text?: string;
    edit_modal?: boolean;//new chang
    use_view_button?: boolean;
    use_edit_button?: boolean;
    use_payment_button?: boolean;
    use_delete_button?: boolean;
    use_test_button?: boolean;
    use_mail_button?: boolean;
}>();
const products = ref([]);
const total = ref(0);
const page = ref(1);
const perPage = ref(10);
const search = ref('');
const loading = ref(false);
const cols = props.columns;
const modal_data = ref(<any | null>(null));
// const addRoute = ref(props.add_route_name || null);
const params = ref({
    sort_column: 'id', // default sort column
    sort_direction: 'asc' // default sort direction /api/products
});

axios.defaults.headers.common['X-CSRF-TOKEN'] = 
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


const handleSortChange = (sortData: { sortColumn: string, sortDirection: string }) => {
    params.value.sort_column = sortData.sortColumn;
    params.value.sort_direction = sortData.sortDirection;
    fetchProducts(); // Refetch data with new sorting
};

const fetchProducts = async () => {
    loading.value = true;
    try {
        const response = await axios.post(props.fetch_url, {
            page: page.value,
            per_page: perPage.value,
            search: search.value,
            sort_column: params.value.sort_column,
            sort_direction: params.value.sort_direction
        });

        products.value = response.data.data;
        total.value = response.data.total;
    } catch (error: any) {
        toast.error(error.response?.data?.message || error.message);
    } finally {
        loading.value = false;
    }
};

const handlePageChange = (newPage: number) => {
    page.value = newPage;
    fetchProducts();
};

const handlePageSizeChange = (newSize: number) => {
    perPage.value = newSize;
    page.value = 1;
    fetchProducts();
};

watch(search, () => {
    page.value = 1;
    fetchProducts();
});

const viewProduct = (row: any) => {
    if (props.use_view_modal) {
        modal_data.value = row;
        $('#viewModal').modal('show');
    } else if (props.view_route_name) {
        const url = route(props.view_route_name, { id: row.id });
        window.location.href = url;
    } else {
        modal_data.value = row;
        $('#editModal').modal('show');
    }
};

const editProduct = (row: any) => {//new changes
    if (props.edit_modal) {
        emit('edit-item', row);
    } else {
        const url = route(props.edit_route_name, { id: row.id });
        window.location.href = url;
    }
};

const sendMail = (row: any) => {
    if (!row || !row.id) {
        toast.error('Invalid record selected.');
        return;
    }

    const formData = new FormData();
    formData.append('id', row.id);

    // Make sure you have props.mail_route_name defined in your component props
    axios.post(route(props.edit_route_name), formData)
        .then((response) => {
            toast.success(response.data.message || 'Mail sent successfully!');
            // Optionally refresh data
            fetchProducts();
        })
        .catch((error) => {
            const message = error.response?.data?.message || 'Failed to send mail.';
            toast.error(message);
        });
};


const confirmDeleteProduct = (row: any) => {
    modal_data.value = row;
    $('#deleteModal').modal('show');
}


const deleteProduct = () => {
    if (modal_data.value != null) {
        const formData = new FormData();
        // formData.append('product_id', modal_data.value.id);
        formData.append('id', modal_data.value.id);

        axios.post(route(props.delete_route_name), formData)
            .then((response) => {
                fetchProducts();
                toast.success(response.data.message);
            }).catch((error => {
                toast.error(error);
            }));
    }
};

// New function for handling the general row action
const handleRowAction = (row: any) => {
    if (props.row_action_route_name) {
        const url = route(props.row_action_route_name, { id: row.id });
        window.location.href = url;
    }
};

// Global "Add New" button handler (if you decide to use it)
// const handleGlobalAdd = () => {
//     if (props.add_route_name) {
//         const url = route(props.add_route_name); // Assuming no ID for a global add route
//         window.location.href = url;
//     }
// };

// added by j. for refresh only page start
const fetchData = async () => {
  const response = await axios.get(props.fetch_url);
  products.value = response.data.data;
};
// added by j. for refresh only page end

onMounted(fetchProducts);

// added by j. for refresh only page start
defineExpose({
  reload: fetchProducts,
});
// added by j. for refresh only page end

</script>

<template>
    <div class="container-fluid">
        <div class="row justify-content-between mt-3">
            <div class="col-4">
                <span v-html="props.table_icon"></span>
                {{ props.title }}
            </div>
            <div class="col-2">
                <input v-model="search" type="text" placeholder="Search products..." class="form-control mb-3" />
            </div>
        </div>
        <vue3-datatable :rows="products" :columns="cols" :totalRows="total" :currentPage="page" :pageSize="perPage"
            :isServerMode="true" :loading="loading" :sortable="true" :sortColumn="params.sort_column"
            :sortDirection="params.sort_direction" @change="handlePageChange" @page-size-change="handlePageSizeChange"
            @sort="handleSortChange" skin="bh-table-hover">

            <template #[`actions`]="slotProps">
                <div class="btn-group" role="group">
                    <button class="btn btn-sm btn-primary mr-2" @click="viewProduct(slotProps.value)" v-if="use_view_button">View</button>
                    <button class="btn btn-sm btn-primary mr-2" @click="editProduct(slotProps.value)" v-if="use_payment_button">Payment</button>
                    <button class="btn btn-sm btn-warning mr-2" @click="editProduct(slotProps.value)" v-if="!use_edit_button">Edit</button>
                    <button class="btn btn-sm btn-warning mr-2" @click="editProduct(slotProps.value)" v-if="use_test_button">Result</button>
                    <button class="btn btn-sm btn-primary mr-2" @click="sendMail(slotProps.value)" v-if="use_mail_button">Send Mail</button>
                    <button class="btn btn-sm btn-danger mr-2"
                        @click="confirmDeleteProduct(slotProps.value)" v-if="!use_delete_button">Delete</button>
                    <!-- <button v-if="slotProps.value" class="btn btn-sm btn-danger mr-2" @click="confirmAddRoute(slotProps.value)">{{ x }}</button> -->
                    <button v-if="props.row_action_route_name && props.row_action_button_text"
                        class="btn btn-sm btn-info" @click="handleRowAction(slotProps.value)">
                        {{ props.row_action_button_text }}
                    </button>
                </div>
            </template>

            <template #loading>
                <div class="text-center py-4">Loading...</div>
            </template>
        </vue3-datatable>
    </div>

    <!-- View modal -->
    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ modal_title }} Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr v-for="(value, key) in modal_data" :key="key">
                                <th scope="row">{{ key }}</th>
                                <td>{{ value }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal -->
    <div class="modal fade" id="deleteModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ modal_title }} Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <P>Are you sure you want to delete this item? This action cannot be undone.</P>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click="deleteProduct()"
                        data-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 900px;
}
</style>
