<template>
    <div>
        <p class="header-text-format"><b>Places</b></p>
        <div v-if="placesApproved.data" class="list-container">
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
                      <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
        <div v-else>
            <p style="font-size: 20px ; margin-left:10px"><b>No places created yet</b></p>
        </div>
    </div>
    <v-btn class="create-event-button" @click="openCreateModal" prepend-icon="mdi-plus">Create a new place</v-btn>
<!--Create/Edit modal-->
    <v-dialog v-model="showModal" max-width="600">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title v-if="modalMode === 'create'">Create new place</v-card-title>
            <h4 v-if="modalMode === 'create'"> Place will be sent to a page moderator for confirmation</h4>
            <v-card-title v-else-if="modalMode === 'edit'">Edit place</v-card-title>
            <v-card-text>
                <form>
                    <div class="mb-3">
                        <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                        <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                        <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputAddress" class="form-label">Address<span style="color: red;">*</span></label>
                        <input class="form-control" id="InputAddress" v-model="fields.address" maxlength="255" type="text" required>
                        <span v-if="errorMessages.errors.address" style="color: red;">{{errorMessages.errors.address[0]}}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputDescription" class="form-label">Description<span style="color: red;">*</span></label>
                        <input class="form-control" id="InputDescription" v-model="fields.description" maxlength="255" type="text" required>
                        <span v-if="errorMessages.errors.description" style="color: red;">{{errorMessages.errors.description[0]}}</span>
                    </div>
                    <span style="color: red;">* - the field is required</span>
                    <div style="margin-bottom: 10px;"></div>
                    <div class="d-flex justify-content-center">
                        <v-btn @click="submit" color="grey-darken-3">
                            Submit</v-btn>
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!--Place delete confirmation window-->
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
            errorMessages: {
              message: "",
              errors: {
                name: null,
                address: null,
                description: null,
              }
            },
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedPlaces();
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
                .catch((error) => {
                  if (error.response && error.response.data.message) {
                    this.errorMessages = error.response.data;
                  }
                });
            } else if (this.modalMode === 'edit') {
                const url = `/place/${this.fields.id}`;
                axios.put(url, this.fields).then((response) => {
                    if (response) {
                        window.location.href = '/place'
                    }
                })
                .catch((error) => {
                  if (error.response && error.response.data.message) {
                    this.errorMessages = error.response.data;
                  }
                });
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

.user-info p {
    margin: 5px 0;
    /* Adjust spacing or other styles as needed */
}

.button-container {
    display: flex;
    justify-content: space-evenly; /* Adds space between buttons */
}

.confirm-title {
    text-align: center;
}

.header-text-format {
    font-size: 40px;
    margin-bottom: 10px;
    color: #07abd5;
}

.list-container{
    background-color: #91deff;
    width: 100%;
    border-radius: 10px;
    padding: 10px;
}

table {
    border-collapse: separate;
    border-spacing: 0 10px;
    margin-top: -10px;
    width: 100%;
}
td {
    padding: 10px;
    background-color: #ffffff;
}
td:first-child {
    border-left-style: solid;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

td:last-child {
    border-right-style: solid;
    border-bottom-right-radius: 10px;
    border-top-right-radius: 10px;
}

.button-container {
  margin-top: 10px;
  margin-bottom: 10px;
}

.create-event-button{
    margin:20px;
    background-color: #91deff;
}

</style>
