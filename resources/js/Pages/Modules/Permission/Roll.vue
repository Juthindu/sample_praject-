<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
import { store } from '../../../main';
import { useToast } from 'vue-toastification'

const toast = useToast();
const name = ref('');
const formRef = ref(null);
const showModal = ref(false)
const roleId = ref(null);

// Define product table columns
const role_table_columns = [
    { field: 'id', title: 'ID', isUnique: true },
    { field: 'name', title: 'Name' },
    { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];

const openCreateRoles = () =>{
    name.value = '';
    roleId.value = null;
    showModal.value = true
}

const openEditRole = (row) => {
    name.value = row.name;
    roleId.value = row.id;
    showModal.value = true;
};

async function SubmitCreateRoles(){
    const form = formRef.value;
    if (form.checkValidity() === false) {
        toast.error("Please fill out all required fields.");
        form.classList.add('was-validated');
        return;
    }
    const formData = new FormData();
    formData.append('name', name.value);

      if (roleId.value) {
        formData.append('id', roleId.value);
        await store('role.update', formData);
    } else {
        await store('role.store', formData);
        // toast.success("Role created successfully");
    }

    showModal.value = false;
    refreshKey.value++;

}

onMounted(() => {

});
</script>

<template>
    <AdminLayout>

        <Head title="Roles-Management" />

        <!-- Dashboard Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0">ROLES</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a :href="route('admin.dashboard.login')">Dashboard</a></li>
                            <li class="breadcrumb-item active">Roles</li>
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
                        <button class="btn btn-primary mr-2">Export Roles</button>
                        <button class="btn btn-primary mr-2">Import Roles</button>
                        <a href="#" @click.prevent="openCreateRoles()"><button class="btn btn-primary mr-2">Create Roles</button></a>
                    </div>
                </div>
                <!-- DataTable Row -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-default">
                            <div class="card-body" style="padding: 0px;">
                                <DataTable
                                    title="ROLES TABLE"
                                    fetch_url="/api/roles"
                                    :columns="role_table_columns"
                                    table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>'
                                    modal_title="Role"
                                    edit_route_name = 'role.edit'
                                    delete_route_name= 'role.delete'
                                    edit_modal=true
                                    @edit-item="openEditRole"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
          <div v-if="showModal" class="modal-backdrop-custom">
                <div class="modal-container-custom">
                    <button @click="showModal = false" class="modal-close-button">
                    <i class="fas fa-times"></i>
                    </button>
                    <h2>{{roleId ? 'Edit Roles' : 'Create Roles'}}</h2>
                    <label for="">Name :</label>
                    <input
                    v-model="name"
                    id="name" name="name"
                    type="text"
                    placeholder="Enter Name"
                    class="form-control mb-3"
                    required
                    />
                    <div class="text-end">
                    <button @click="SubmitCreateRoles"  ref="formRef" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </div>
    </AdminLayout>
</template>

<style scoped>
canvas {
    height: 400px !important;
}

.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1050; /* Must be above Bootstrap's base z-index */
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-container-custom {
  background-color: white;
  border-radius: 8px;
  padding: 24px;
  width: 450px;
  position: relative;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.modal-close-button {
  position: absolute;
  top: 12px;
  right: 12px;
  border: none;
  background: transparent;
  font-size: 1.25rem;
  cursor: pointer;
}
</style>
