<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { useToast } from "vue-toastification";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const handleLogin = () => {
    form.post(route('admin.login'), {
        onSuccess: () => {
            useToast().success('Login successful');
        },
        onError: (errors) => {
            useToast().error('Login failed. Please check your credentials.');
        },
    })
}
const currentYear = new Date().getFullYear();
</script>

<template>

    <Head title="Login" />

    <!-- <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
</div> -->

    <div class="screen">
        <div class="screen2">
            <div class="card-bg">
                <div class="register-page " style="min-height: 542px;">
                    <div class="register-box" style="width: 400px;">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-center align-items-center">
                                        <img src="/images/Aqua-mission/Login page logo-2.png" alt="logo" class=""
                                            style="opacity: 1" width="100px">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body mt-5">
                                <form @submit.prevent="handleLogin">
                                    <div class="form-group mb-3 px-3">

                                        <label for="" class="form-label p-1"><span class="fas fa-user"></span> User name
                                            /
                                            User
                                            mail</label>
                                        <input type="email" class="form-control" placeholder="Email"
                                            v-model="form.email">
                                        <!-- <div class="input-group-append">
                                <div class="input-group-text">
                                </div>
                            </div> -->
                                    </div>
                                    <div class="form-group px-3 mb-3">
                                        <label for="" class="form-label p-1"><span class="fas fa-lock"></span>
                                            Password</label>
                                        <input type="password" class="form-control" placeholder="Password"
                                            v-model="form.password">
                                        <!-- <div class="input-group-append">
                                <div class="input-group-text">

                                </div>
                            </div> -->
                                    </div>
                                    <div class="row d-flex justify-content-end px-3 mt-4">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer mt-5 d-flex justify-content-center align-items-center bg-light">
                                <small class="text-muted text-center py-2">
                                    &copy; <span id="currentYear">{{ currentYear }}</span> Aqua Mission. All rights
                                    reserved.
                                </small>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.company-name {
    font-weight: bolder;
}

.card-bg {
    /* background: linear-gradient(to right, #000000, #1e3a8a, #000000); */

}
.card-header,.card-footer{
    background: #e9f1fe !important;
}
.register-page {
    background: transparent;
}

.register-box {
    position: relative;
    z-index: 99;
}
/* --bg-- */
.screen2 {
    position: relative;
    background: radial-gradient(ellipse at center,
            rgba(0, 33, 82, 1) 0%,
            rgba(0, 0, 0, 1) 100%);

}

.card-bg:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    background: repeating-linear-gradient(45deg,
            transparent,
            transparent 1px,
            rgba(0, 0, 0, .8) 10px,
            rgba(255, 255, 255, 1) 10px);
    z-index: 1;
}

.screens {
    margin: 0;
    padding: 0;
}

.screens>.screen {
    position: relative;
    border-bottom: 1px solid black;
    width: 100vw;
    height: 100vh;
    list-style-type: none;
    display: block;
}
</style>
