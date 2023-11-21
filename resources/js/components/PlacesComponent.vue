<template>
    <div>
        <h1>Places</h1>
        <div>
            <v-btn @click="openCreateModal" prepend-icon="mdi-plus">Create a new place</v-btn>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(place, index) in placesApproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                      <td>{{ place.name }}</td>
                      <td>{{ place.address}}</td>
                      <td><v-btn variant="text"
                                 color="secondary"
                                 @click="openEditModal(place)"
                                 v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                 Edit</v-btn></td>
                      <td><v-btn variant="text"
                                 color="red"
                                 @click="showConfirm(place)"
                                 v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                 Delete</v-btn></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
            <h1>Unapproved places</h1>
            <div>
                <table>
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Adress</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(place, index) in placesUnapproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                        <td>{{ place.name }}</td>
                        <td>{{ place.address}}</td>
                        <td><v-btn variant="text"
                                   color="green"
                                   @click="approvePlace(place)">Approve</v-btn></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="openEditModal(place)">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="showConfirm(place)">Delete</v-btn></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!--Create/Edit modal-->
    <v-dialog v-model="showModal" max-width="600">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title v-if="modalMode === 'create'">Create new place</v-card-title>
            <h4 v-if="modalMode === 'create'"> Place will be sent to a page moderator for confirmation</h4>
            <v-card-title v-else-if="modalMode === 'edit'">Edit place</v-card-title>
            <v-card-text>
                <form>
                    <div class="mb-3">
                        <label for="InputName" class="form-label">Name</label>
                        <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="InputAddress" class="form-label">Address</label>
                        <input class="form-control" id="InputAddress" v-model="fields.address" maxlength="255" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label for="InputDescription" class="form-label">Description</label>
                        <input class="form-control" id="InputDescription" v-model="fields.description" maxlength="255" type="text" required>
                    </div>

                    <div class="d-flex justify-content-center">
                        <v-btn @click="submit" color="grey-darken-3">
                            Submit</v-btn>
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!--Delete confirmation window-->
    <v-dialog v-model="showConfirmation" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Do you want to delete this place?</v-card-title>
            <div class="button-container">
                <v-btn @click="confirmDelete()"
                       min-width="80"
                       color="green">Yes</v-btn>
                <v-btn @click="cancelDelete()"
                       min-width="80"
                       color="red">No</v-btn>
            </div>
        </v-card>
    </v-dialog>
</template>

<script>
import {isRole, getAuthUser} from "../app";
import {RoleEnum} from "../enums/RoleEnum";

export default {
    data() {
        return {
            placesApproved: [],
            placesUnapproved: [],
            authUser: null,
            roleEnum: RoleEnum,
            showModal: false,
            modalMode: 'create',
            showConfirmation: false,
            placeDeleteConfirm: null,
            fields: {
                id: "",
                name: "",
                address: "",
                description: "",
            },
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedPlaces();
        this.fetchUnapprovedPlaces();
    },
    methods: {
        fetchApprovedPlaces() {
            axios.get('/place/approved')
                .then(response => {
                    this.placesApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                })
        },
        fetchUnapprovedPlaces() {
            axios.get('/place/unapproved')
                .then(response => {
                    this.placesUnapproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                })
        },
        approvePlace(place) {
            const url = `/place/${place.id}`;
            axios.patch(url)
                .then(response => {
                    this.fetchApprovedPlaces();
                    this.fetchUnapprovedPlaces();
                })
                .catch(error => {
                    console.error('Error approving place:', error);
                });
        },
        deletePlace(place) {
            const url = `/place/${place.id}`;
            axios.delete(url)
                .then(() => {
                    this.fetchApprovedPlaces();
                    this.fetchUnapprovedPlaces();
                })
                .catch(error => {
                    console.error('Error deleting event:', error);
                });
        },
        openCreateModal() {
            this.modalMode = 'create';
            this.showModal = true;
            this.fields = {
                name: "",
                address: "",
                description: ""
            }
        },
        openEditModal(place) {
            this.modalMode = 'edit';
            this.showModal = true;
            this.fields = { ...place };
        },
        submit() {
            if (this.modalMode === 'create') {
                axios.post('/place', this.fields).then((response) => {
                    if (response) {
                        window.location.href = '/place'
                    }
                })
            } else if (this.modalMode === 'edit') {
                const url = `/place/${this.fields.id}`;
                axios.put(url, this.fields).then((response) => {
                    if (response) {
                        window.location.href = '/place'
                    }
                })
            }
        },
        showConfirm(place) {
            this.showConfirmation = true;
            this.placeDeleteConfirm = place;
        },
        confirmDelete() {
            this.showConfirmation = false;
            this.deletePlace(this.placeDeleteConfirm);
            this.placeDeleteConfirm = null;
        },
        cancelDelete() {
            this.showConfirmation = false;
            this.placeDeleteConfirm = null;
        },

        isRole,
        getAuthUser,
    }
};
</script>

<style>
/* Your custom styles here */
.user-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.user-card {
    border: 1px solid #ccc;
    padding: 20px;
    /* Adjust width or other styles as needed */
}

.user-info p {
    margin: 5px 0;
    /* Adjust spacing or other styles as needed */
}

.user-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
    /* Additional styles for action buttons */
}

.button-container {
    display: flex;
    justify-content: space-evenly; /* Adds space between buttons */
}

.confirm-title {
    text-align: center;
}

th, td {
    padding: 5px;
}
</style>
