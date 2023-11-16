<template>
    <div>
        <h1>Places</h1>
        <div>
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
        // approvePlace(event) {
        //     const url = `/place/${place.id}`;
        //     axios.patch(url)
        //         .then(response => {
        //             this.fetchApprovedPlaces();
        //             this.fetchUnapprovedPlaces();
        //         })
        //         .catch(error => {
        //             console.error('Error approving event:', error);
        //         });
        // },
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
