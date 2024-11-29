// resources/js/Pages/Roles/Index.vue
<template>
    <AuthenticatedLayout>

        <Head title="Roles" />
        <div class="p-6 bg-white rounded-lg shadow-lg max-w-3xl mx-auto">
            <Button label="Create Role" icon="pi pi-plus" @click="goToCreate"
                class="mb-4 px-5 py-2 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 rounded-lg transition-all duration-200" />

            <DataTable :value="roles" class="p-datatable-sm">
                <Column field="name" header="Role Name" class="font-semibold text-sm text-gray-700" />

                <Column header="Actions">
                    <template #body="slotProps">
                        <div class="flex items-center space-x-2">
                            <Button label="Edit" icon="pi pi-pencil"
                                class="p-button-sm p-button-info hover:bg-blue-500 focus:ring-2 focus:ring-blue-500 rounded-lg"
                                @click="editRole(slotProps.data)" />
                            <Button label="Delete" icon="pi pi-trash"
                                class="p-button-sm p-button-danger hover:bg-red-500 focus:ring-2 focus:ring-red-500 rounded-lg"
                                @click="confirmDelete(slotProps.data)" />
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
        roles: Array,
    },
    methods: {
        goToCreate() {
            this.$inertia.visit(route('roles.create'));
        },
        actionsTemplate(rowData) {
            return h(Button, {
                label: "Edit",
                icon: "pi pi-pencil",
                class: "mr-2",
                onClick: () => this.editRole(rowData),
            });
        },
        editRole(rowData) {
            // Using the named route for editing the role
            console.log(rowData);

            this.$inertia.visit(route('roles.edit', { role: rowData.id }));
        },
        confirmDelete(role) {
            this.$confirm.require({
                message: `Are you sure you want to delete the role "${role.name}"?`,
                header: 'Confirm Delete',
                icon: 'pi pi-exclamation-triangle',
                rejectProps: {
                    label: 'Cancel',
                    severity: 'success',

                },
                acceptProps: {
                    label: 'Yes',
                    severity: 'danger',
                    outlined: true
                },
                accept: () => {
                    this.deleteRole(role);
                },
                reject: () => {
                    console.log('Deletion canceled');
                }
            });
        },

        // Handle the deletion of the role
        deleteRole(role) {
            console.log(`Deleting role with ID: ${role.id}`);
            this.$inertia.delete(route('roles.destroy', { role: role.id }), {
                onSuccess: () => {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'Role Deleted',
                        detail: 'The role has been deleted successfully.',
                        life: 3000,
                    });
                },
                onError: () => {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'There was an error deleting the role.',
                        life: 3000,
                    });
                },
            });
        }
    },
};
</script>
