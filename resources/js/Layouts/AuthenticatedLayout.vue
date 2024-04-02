<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const web = 'https://teraminer.com.ve/';
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-[#84579f] border-b border-gray-100 shadow-lg">
                <!-- Primary Navigation Menu -->
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex items-center shrink-0">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block w-auto text-gray-800 fill-current h-9"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Inicio
                                </NavLink>
                                <NavLink :href="route('sellers.index')" :active="route().current('sellers.*')">
                                    Vendedores
                                </NavLink>
                                <NavLink :href="route('customers.index')" :active="route().current('customers.*')">
                                    Clientes
                                </NavLink>
                                <NavLink :href="route('products.index')" :active="route().current('products.*')">
                                    Productos
                                </NavLink>
                                <!-- //shipment link -->
                                <NavLink :href="route('shipments.index')" :active="route().current('shipments.*')">
                                    Envios
                                </NavLink>
                                <NavLink :href="route('invoices.index')" :active="route().current('invoices.*')">
                                    Ver Facturas
                                </NavLink>
                                <!-- garanty -->
                                <NavLink v-if="$page.props.auth.user.rol == 1" :href="route('garanty.index')" :active="route().current('garanty.*')">
                                    Garantias
                                </NavLink>
                                <!-- users -->
                                <NavLink v-if="$page.props.auth.user.rol == 1" :href="route('users.index')" :active="route().current('users.*')">
                                    Usuarios
                                </NavLink>

                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="relative ml-3 shadow-md">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                        <DropdownLink v-if="$page.props.auth.user.rol == 1" :href="route('data.index')"> Data </DropdownLink>
                                        <a
                                            :href="web"
                                            class="block w-full px-4 py-2 text-sm leading-5 text-left text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:bg-gray-100"
                                            target="_blank"
                                        >
                                        Ir a Teraminer.com.ve
                                        </a>
                                        <hr>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Salir
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center -mr-2 sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                            >
                                <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-white">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="text-base font-medium text-white">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-white">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')" class="text-white"> Perfil </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('data.index')" class="text-white"> Data </ResponsiveNavLink>
                            <hr class="border-t border-gray-700">
                            <ResponsiveNavLink :href="route('sellers.index')" class="text-white"> Vendedores </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('customers.index')" class="text-white"> Clientes </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('products.index')" class="text-white"> Productos </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('shipments.index')" class="text-white"> Envios </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('invoices.index')" class="text-white"> Ver Facturas </ResponsiveNavLink>
                            <ResponsiveNavLink v-if="$page.props.auth.user.rol == 1" :href="route('garanty.index')" class="text-white"> Garantias </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-white">
                                Salir
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
