<template>
    <AuthenticatedLayout>

        <Head title="Edit Role" />
        <div class="p-6 bg-white rounded-lg shadow-lg max-w-2xl mx-auto">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Edit Role</h3>

            <form @submit.prevent="updateRole" class="space-y-6">
                <!-- Role Name -->
                <div class="p-field mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <InputText v-model="newrole.name" id="name" required
                        class="mt-2 block w-full p-inputtext-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <!-- Permissions -->
                <div class="p-field mb-5">
                    <label for="permissions" class="block text-sm font-medium text-gray-700">Permissions</label>
                    <MultiSelect v-model="newrole.permissionss" :options="permissions" optionLabel="name"
                        optionValue="name" placeholder="Select Permissions"
                        class="mt-2 block w-full p-multiselect-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <Button label="Save Changes" type="submit" icon="pi pi-check"
                        class="w-full py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
            </form>
        </div>

    </AuthenticatedLayout>
</template>

<script>

export default {
    props: {
        role: Object,
        permissions: Array,
    },
    data() {
        return {
            newrole: { ...this.role },
        };
    },
    methods: {
        updateRole() {
            const url = route('roles.update', { role: this.newrole.id });

            this.$inertia.put(url, this.newrole);
        },
    },
};
</script>
