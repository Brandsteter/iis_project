<template>
    <div>
        <h1>Places</h1>
        <div>
            <v-btn @click="toggleForm" prepend-icon="mdi-plus">Create a new place</v-btn>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Adress</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(place, index) in placesApproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                      <td>{{ place.name }}</td>
                      <td>{{ place.address}}</td>
                      <td><v-btn variant="text"
                                 color="secondary"
                                 @click="selectedOpen = false"
                                 v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                 Edit</v-btn></td>
                      <td><v-btn variant="text"
                                 color="red"
                                 @click="deleteCategory(category)"
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
                                   @click="selectedOpen = false">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="deletePlace(place)">Delete</v-btn></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showForm" class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
            <form>
                <label style="font-size: x-large" class="form-label">Create a new place for events</label>
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
                        Submit
                    </v-btn>

                </div>
            </form>
        </div>
    </div>
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
            showForm: false,
            fields: {
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
        toggleForm() {
            this.showForm = !this.showForm;
        },
        submit() {
            axios.post('/place', this.fields).then((response) => {
                if (response) {
                    window.location.href = '/place'
                }
            })
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

th, td {
    padding: 5px;
}
</style>
