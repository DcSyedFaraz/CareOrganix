<template>
    <AuthenticatedLayout>

        <Head :title="user ? 'Edit User' : 'Create User'" />
        <div class="p-6 bg-white rounded-lg shadow-lg max-w-2xl mx-auto mt-5">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">{{ user ? 'Edit' : 'Create' }} User</h3>

            <form @submit.prevent="submitForm">
                <!-- Name -->
                <div class="p-field mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <InputText id="name" v-model="userForm.name"
                        :class="['mt-2 block w-full p-inputtext-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500', { 'p-invalid': errors.name }]" />
                    <small v-if="errors.name" class="text-xs text-red-500">{{ errors.name }}</small>
                </div>

                <!-- Email -->
                <div class="p-field mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <InputText id="email" v-model="userForm.email"
                        :class="['mt-2 block w-full p-inputtext-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500', { 'p-invalid': errors.email }]" />
                    <small v-if="errors.email" class="text-xs text-red-500">{{ errors.email }}</small>
                </div>

                <!-- Password (only for create or password update) -->
                <div class="p-field mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <Password toggleMask :invalid="errors.password" id="password" v-model="userForm.password" fluid
                        :class="['mt-2 block w-full p-password-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500', { 'p-invalid': errors.password }]" />
                    <small v-if="errors.password" class="text-xs text-red-500">{{ errors.password }}</small>
                </div>

                <div class="p-field mb-5">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <Password toggleMask :invalid="errors.password" id="password_confirmation"
                        v-model="userForm.password_confirmation" fluid
                        :class="['mt-2 block w-full p-password-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500', { 'p-invalid': errors.password_confirmation }]" />
                    <small v-if="errors.password_confirmation" class="text-xs text-red-500">{{
                        errors.password_confirmation }}</small>
                </div>

                <!-- Roles -->
                <div class="p-field mb-5">
                    <label for="roles" class="block text-sm font-medium text-gray-700">Roles</label>
                    <MultiSelect id="roles" v-model="userForm.roles" :options="roles" optionLabel="name"
                        optionValue="name" multiple
                        :class="['mt-2 block w-full p-multiselect-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500', { 'p-invalid': errors.roles }]" />
                    <small v-if="errors.roles" class="text-xs text-red-500">{{ errors.roles }}</small>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <Button label="Save" icon="pi pi-check" type="submit"
                        class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>

<script>


export default {
    props: {
        user: Object,
        roles: Array,
    },
    data() {
        return {
            userForm: {
                name: this.user?.name || '',
                email: this.user?.email || '',
                password: '',
                password_confirmation: '',
                roles: this.user?.roles?.map(role => role.name) || [],
            },
            errors: {},
        };
    },
    methods: {
        submitForm() {
            const url = this.user
                ? route('users.update', this.user.id)
                : route('users.store');

            const method = this.user ? 'put' : 'post';

            this.$inertia[method](url, this.userForm, {
                onError: (errors) => {
                    this.errors = errors;
                    Object.values(errors).forEach(error => {
                        this.$toast.add({
                            severity: 'error',
                            summary: 'Validation Error',
                            detail: error,
                            life: 3000,
                        });
                    });
                },
                onSuccess: () => {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: 'User saved successfully.',
                        life: 3000,
                    });
                },
            });
        },
    },
};
</script>
