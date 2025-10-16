<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a :href="route('admin.dashboard.login')" class="brand-link">
            <img src="/images/Aqua-mission/Navigation Logo 2.png" alt="AdminLTE Logo" class="brand-image " style="opacity: 1">
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a :href="route('admin.dashboard.login')" class="nav-link"  :class="{ active: route().current('admin.dashboard.login') }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2Zm0 2a8 8 0 1 0 0 16a8 8 0 0 0 0-16Zm0 1c1.018 0 1.985.217 2.858.608L13.295 7.17a5 5 0 0 0-4.83 8.366L7.05 16.95l-.156-.161A7 7 0 0 1 12 5Zm6.392 4.143c.39.872.608 1.84.608 2.857a6.978 6.978 0 0 1-2.05 4.95l-1.414-1.414a5.008 5.008 0 0 0 1.295-4.83l1.561-1.563Zm-2.15-2.8l1.415 1.414l-3.725 3.726A2.003 2.003 0 0 1 12 14a2 2 0 1 1 .517-3.932l3.726-3.725Z" />
                            </svg>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Region Management')">
                        <a :href="route('region.index')" class="nav-link"  :class="{ active: route().current('region.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#c2c7d0" >
                                <path
                                    d="M160-240h640v-480H160v480Zm0 80q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm0-80v-480 480Zm320-240q-17 0-28.5-11.5T440-520q0-17 11.5-28.5T480-560q17 0 28.5 11.5T520-520q0 17-11.5 28.5T480-480Zm0 200q81-69 120.5-127.5T640-516q0-75-48.5-119.5T480-680q-63 0-111.5 44.5T320-516q0 50 39.5 108.5T480-280Z" />
                            </svg>
                            <p>
                                Region
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('District Management')">
                        <a :href="route('district.index')" class="nav-link" :class="{ active: route().current('district.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#c2c7d0">
                                <path
                                    d="M440-40v-80q-125-14-214.5-103.5T122-438H42v-80h80q14-125 103.5-214.5T440-836v-80h80v80q125 14 214.5 103.5T838-518h80v80h-80q-14 125-103.5 214.5T520-120v80h-80Zm40-158q116 0 198-82t82-198q0-116-82-198t-198-82q-116 0-198 82t-82 198q0 116 82 198t198 82Z" />
                            </svg>
                            <p>
                                District
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('OIC Management')">
                        <a :href="route('oic.index')" class="nav-link" :class="{ active: route().current('oic.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h133v-133H200v133Zm213 0h134v-133H413v133Zm214 0h133v-133H627v133ZM200-413h133v-134H200v134Zm213 0h134v-134H413v134Zm214 0h133v-134H627v134ZM200-627h133v-133H200v133Zm213 0h134v-133H413v133Zm214 0h133v-133H627v133Z" />
                            </svg>
                            <p>
                                OIC
                            </p>
                        </a>
                    </li>

                    <!-- <li>
                        <hr style="background-color: whitesmoke;">
                    </li> -->

                    <li class="nav-item border-top" v-if="userPermissions.includes('Own Resource Water Sample Management')">
                        <a :href="route('own.resource.sample.index')" class="nav-link" :class="{ active: route().current('own.resource.sample.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960"
                                width="20" fill="#e3e3e3">
                                <path
                                    d="M480-100q-133 0-226.5-92T160-416q0-63 24.5-120.5T254-638l226-222 226 222q45 44 69.5 101.5T800-416q0 132-93.5 224T480-100ZM240-416h480q0-47-18-89.5T650-580L480-748 310-580q-34 32-52 74.5T240-416Z" />
                            </svg>
                            <p>
                                NWSDB WRS
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Test Result Management')">
                        <a :href="route('own.sample.data.index')" class="nav-link" :class="{ active: route().current('own.sample.data.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M320-480v-80h320v80H320Zm0-160v-80h320v80H320Zm-80 240h300q29 0 54 12.5t42 35.5l84 110v-558H240v400Zm0 240h442L573-303q-6-8-14.5-12.5T540-320H240v160Zm480 80H240q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80Zm-480-80v-640 640Zm0-160v-80 80Z" />
                            </svg>
                            <p>
                               NWSDB WRS Test
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Test Result Management')">
                        <a :href="route('release.own.sample.data.index')" class="nav-link" :class="{ active: route().current('release.own.sample.data.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q32 0 62-6t58-17l60 61q-41 20-86 31t-94 11Zm280-80v-120H640v-80h120v-120h80v120h120v80H840v120h-80ZM424-296 254-466l56-56 114 114 400-401 56 56-456 457Z"/>
                            </svg>
                            <p>
                               NWSDB WRS Confirm
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Send Email Management')">
                        <a :href="route('final.own.sample.data.index')" class="nav-link" :class="{ active: route().current('final.own.sample.data.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/>
                            </svg>
                            <p>
                                Complete NWSDB WRS
                            </p>
                        </a>
                    </li>
                    <!-- <li>
                        <hr style="background-color: whitesmoke;">
                    </li> -->
                    <li class="nav-item border-top" v-if="userPermissions.includes('Consumer Water Sample Management')">
                        <a :href="route('con.sample.index')" class="nav-link"  :class="{ active: route().current('con.sample.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M280-80q-31 0-54-20t-26-51l-80-729h720l-80 729q-3 31-26 51t-54 20H280Zm-53-560h147q21-19 48-29.5t58-10.5q31 0 58 10.5t48 29.5h147l17-160H209l18 160Zm253 200q33 0 56.5-23.5T560-520q0-33-23.5-56.5T480-600q-33 0-56.5 23.5T400-520q0 33 23.5 56.5T480-440ZM236-560l44 400h400l44-400h-89q2 10 3.5 19.5T640-520q0 66-47 113t-113 47q-66 0-113-47t-47-113q0-11 1.5-20.5T325-560h-89Zm44 400h45-5 320-5 45-400Z" />
                            </svg>
                            <p>
                                Consumer WRS
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Consumer Water Sample Management')">
                        <a :href="route('payment.index')" class="nav-link"  :class="{ active: route().current('payment.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M560-440q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM280-320q-33 0-56.5-23.5T200-400v-320q0-33 23.5-56.5T280-800h560q33 0 56.5 23.5T920-720v320q0 33-23.5 56.5T840-320H280Zm80-80h400q0-33 23.5-56.5T840-480v-160q-33 0-56.5-23.5T760-720H360q0 33-23.5 56.5T280-640v160q33 0 56.5 23.5T360-400Zm440 240H120q-33 0-56.5-23.5T40-240v-440h80v440h680v80ZM280-400v-320 320Z" />
                            </svg>
                            <p>
                                C WRS Payment Section
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Test Result Management')">
                        <a :href="route('consumer.sample.data.test.index')" class="nav-link" :class="{ active: route().current('consumer.sample.data.test.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M320-480v-80h320v80H320Zm0-160v-80h320v80H320Zm-80 240h300q29 0 54 12.5t42 35.5l84 110v-558H240v400Zm0 240h442L573-303q-6-8-14.5-12.5T540-320H240v160Zm480 80H240q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h480q33 0 56.5 23.5T800-800v640q0 33-23.5 56.5T720-80Zm-480-80v-640 640Zm0-160v-80 80Z" />
                            </svg>
                            <p>
                                C WRS Test
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Consumer Water Sample Management')">
                        <a :href="route('consumer.sample.data.release.index')" class="nav-link" :class="{ active: route().current('consumer.sample.data.release.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q65 0 123 19t107 53l-58 59q-38-24-81-37.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160q32 0 62-6t58-17l60 61q-41 20-86 31t-94 11Zm280-80v-120H640v-80h120v-120h80v120h120v80H840v120h-80ZM424-296 254-466l56-56 114 114 400-401 56 56-456 457Z"/>
                            </svg>
                            <p>
                               C WRS Confirm
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Send Email Management')">
                        <a :href="route('consumer.sample.data.final.index')" class="nav-link" :class="{ active: route().current('consumer.sample.data.final.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z"/>
                            </svg>
                            <p>
                                Send Email
                            </p>
                        </a>
                    </li>
                    <!-- <li>
                        <hr style="background-color: whitesmoke;">
                    </li> -->
                    <li class="nav-item border-top" v-if="userPermissions.includes('Consumer Management')">
                        <a :href="route('new.consumer.index')" class="nav-link"  :class="{ active: route().current('new.consumer.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                            </svg>
                            <p>
                                Consumer
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Employee Management')">
                        <a :href="route('employee.index')" class="nav-link"  :class="{ active: route().current('employee.*') }">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960"
                                width="20" fill="#e3e3e3">
                                <path
                                    d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17-62.5t47-43.5q60-30 124.5-46T480-440q67 0 131.5 16T736-378q30 15 47 43.5t17 62.5v112H160Zm320-400q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm160 228v92h80v-32q0-11-5-20t-15-14q-14-8-29.5-14.5T640-332Zm-240-21v53h160v-53q-20-4-40-5.5t-40-1.5q-20 0-40 1.5t-40 5.5ZM240-240h80v-92q-15 5-30.5 11.5T260-306q-10 5-15 14t-5 20v32Zm400 0H320h320ZM480-640Z" />
                            </svg>
                            <p>
                                Employee
                            </p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Chemical Inventory Management')">
                        <a href="#" class="nav-link" :class="{ active: route().current('chemical.*')}">
                            <svg class="svg mr-2" xmlns="http://www.w3.org/2000/svg" height="20"
                                viewBox="0 -960 960 960" width="20" fill="#e3e3e3">
                                <path
                                    d="M200-120q-51 0-72.5-45.5T138-250l222-270v-240h-40q-17 0-28.5-11.5T280-800q0-17 11.5-28.5T320-840h320q17 0 28.5 11.5T680-800q0 17-11.5 28.5T640-760h-40v240l222 270q32 39 10.5 84.5T760-120H200Zm80-120h400L544-400H416L280-240Zm-80 40h560L520-492v-268h-80v268L200-200Zm280-280Z" />
                            </svg>
                            <p>
                                Chemical
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('chemical.index')" class="nav-link">
                                    <p class="ml-4">Chemical Management</p>
                                </a>
                            </li>
                             <li class="nav-item">
                                <a :href="route('chemical.stock.index')" class="nav-link">
                                    <p class="ml-4">Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('chemical.adjustment.index')" class="nav-link">
                                    <p class="ml-4">Adjustment Chemical</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item" v-if="userPermissions.includes('Permission Management')">
                        <a href="#" class="nav-link" :class="{ active: route().current('role.*')}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="25" height="25"
                                viewBox="0 0 32 32">
                                <path fill="currentColor"
                                    d="M21.066 20.667c1.227-.682 1.068-3.31-.354-5.874c-.61-1.104-1.36-1.998-2.11-2.623a5.229 5.229 0 0 1-3.1 1.03a5.23 5.23 0 0 1-3.105-1.03c-.75.625-1.498 1.52-2.11 2.623c-1.423 2.563-1.58 5.192-.35 5.874c.548.312 1.126.078 1.722-.496a10.51 10.51 0 0 0-.167 1.874c0 2.938 1.14 5.312 2.543 5.312c.846 0 1.265-.865 1.466-2.188c.2 1.314.62 2.188 1.46 2.188c1.397 0 2.546-2.375 2.546-5.312c0-.66-.062-1.29-.168-1.873c.6.575 1.176.813 1.726.497zM15.5 12.2a4.279 4.279 0 1 0-.003-8.557A4.279 4.279 0 0 0 15.5 12.2zm8.594 2.714a3.514 3.514 0 0 0 0-7.025a3.513 3.513 0 1 0 .001 7.027zm4.28 2.13c-.502-.908-1.116-1.642-1.732-2.155a4.3 4.3 0 0 1-2.546.845c-.756 0-1.46-.207-2.076-.55c.496 1.093.803 2.2.86 3.19c.094 1.516-.38 2.64-1.328 3.165a2.017 2.017 0 0 1-.653.224c-.057.392-.096.8-.096 1.23c0 2.413.935 4.362 2.088 4.362c.694 0 1.04-.71 1.204-1.796c.163 1.08.508 1.796 1.2 1.796c1.145 0 2.09-1.95 2.09-4.36c0-.543-.053-1.06-.14-1.54c.492.473.966.668 1.418.408c1.007-.56.877-2.718-.29-4.82zm-21.468-2.13a3.512 3.512 0 1 0-3.514-3.512a3.515 3.515 0 0 0 3.514 3.514zm2.535 6.622c-1.592-.885-1.738-3.524-.456-6.354a4.242 4.242 0 0 1-2.078.553c-.956 0-1.832-.32-2.55-.846c-.615.512-1.228 1.246-1.732 2.153c-1.167 2.104-1.295 4.262-.287 4.82c.45.258.925.065 1.414-.406a8.83 8.83 0 0 0-.135 1.538c0 2.412.935 4.36 2.088 4.36c.694 0 1.04-.71 1.204-1.795c.165 1.08.51 1.796 1.2 1.796c1.147 0 2.09-1.95 2.09-4.36c0-.433-.04-.842-.097-1.234a2.02 2.02 0 0 1-.66-.226z" />
                            </svg>
                            <p>
                                Permission
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a :href="route('role.management')" class="nav-link">
                                    <p class="ml-4">Role</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a :href="route('role.permission')" class="nav-link">
                                    <p class="ml-4">Permission</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a :href="route('logout')" method="post" class="nav-link">
                            <!-- <li class="nav-item" v-if="userPermissions.includes('Order Management')" > -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="25" height="20"
                                viewBox="0 0 512 512">
                                <path fill="currentColor"
                                    d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32v224c0 17.7 14.3 32 32 32s32-14.3 32-32V32zm-144.5 88.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z" />
                            </svg>
                            <p class="ms-3">
                                Log out
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>
<script>
export default {
    name: 'MainSideBar',
    props: {
        userPermissions: {
            type: Array,
            required: true,
        },
    },
    mounted() {
        console.log('userPermissions:', this.userPermissions);
    }
};
</script>
<style>
.sidebar-color {}

.nav-link {
    color: #c2c7d0;
}

.nav-link:hover {
    color: #ffffff !important;
}

.nav-link:hover .svg {
    fill: #ffffff !important;
}

.nav-label {
    display: inline-flex;
    gap: .5rem;
    align-items: center;
}

.nav-link svg {
    transition: color .2s ease;
}

.brand-link{
display: flex;
justify-content: center;
}
.brand-link img{
    max-height: 40px !important;
}

</style>
