<template>
    <AuthenticatedLayout>

        <Head title="User Management" />
        <div class="p-6 bg-gray-100 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">User Management</h3>

            <!-- Create User Button -->
            <Button label="Create User" icon="pi pi-plus" @click="createUser"
                class="p-button-success p-mb-4 bg-green-500 text-white hover:bg-green-600 rounded-lg py-2 px-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-300" />

            <!-- DataTable to display users -->
            <DataTable :value="users" :rows="10" paginator class="rounded-lg overflow-hidden shadow-md bg-white">
                <Column field="name" header="Name" class="text-gray-600 font-medium" />
                <Column field="email" header="Email" class="text-gray-600 font-medium" />
                <Column header="Roles">
                    <template #body="slotProps">
                        <!-- Display roles as comma-separated list -->
                        <span class="text-gray-600">
                            {{ slotProps.data.roles.map(role => role.name).join(', ') }}
                        </span>
                    </template>
                </Column>
                <Column header="Actions">
                    <template #body="slotProps">
                        <div class="p-d-flex p-ai-center space-x-3">
                            <!-- Edit Button -->
                            <Button label="Edit" icon="pi pi-pencil"
                                class="p-button-warning p-button-sm p-mr-2 bg-yellow-500 text-white hover:bg-yellow-600 rounded-lg py-1.5 px-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                                @click="editUser(slotProps.data)" />
                            <!-- Delete Button -->
                            <Button label="Delete" icon="pi pi-trash"
                                class="p-button-danger p-button-sm bg-red-500 text-white hover:bg-red-600 rounded-lg py-1.5 px-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-300"
                                @click="confirmDelete(slotProps.data.id)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

    </AuthenticatedLayout>
</template>

<script>


export default {
    props: {
        users: Array,
    },
    methods: {
        createUser() {
            this.$inertia.visit(route('users.create'));
        },
        editUser(user) {
            this.$inertia.visit(route('users.edit', user.id));
        },
        confirmDelete(userId) {
            // Show the confirmation dialog
            this.$confirm.require({
                message: 'Are you sure you want to delete this user?',
                header: 'Confirm Deletion',
                icon: 'pi pi-exclamation-triangle',
                rejectProps: {
                    label: 'No',
                    severity: 'success',

                },
                acceptProps: {
                    label: 'Yes',
                    severity: 'danger',
                    outlined: true
                },
                accept: () => {
                    this.deleteUser(userId);
                },
            });
        },

        deleteUser(userId) {
            this.$inertia.delete(route('users.destroy', userId), {
                onSuccess: () => {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'User Deleted',
                        detail: 'The user has been deleted successfully.',
                        life: 3000,
                    });
                },
                onError: () => {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'There was an error deleting the user.',
                        life: 3000,
                    });
                },
            });
        },
    },
};
</script>
