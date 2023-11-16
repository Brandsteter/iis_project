<template>
    <div>
        <h1>Events</h1>
        <div>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Place</th>
                    <th>Capacity</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(event, index) in eventsApproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                        <td>{{ event.name }}</td>
                        <td>{{ event.event_start}}</td>
                        <td>{{ event.event_end}}</td>
                        <td>{{ event.place.name}}</td>
                        <td>{{ event.capacity_current}}/{{checkCapacityValue(event)}}</td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="selectedOpen = false">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="deleteEvent(event)">Delete</v-btn></td>
                </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                :data="eventsApproved"
                @pagination-change-page="fetchApprovedEvents"
            />
        </div>

        <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
            <h1>Unapproved events</h1>
            <div>
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Place</th>
                        <th>Capacity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(event, index) in eventsUnapproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
                        <td>{{ event.name }}</td>
                        <td>{{ event.event_start}}</td>
                        <td>{{ event.event_end}}</td>
                        <td>{{ event.place.name}}</td>
                        <td>{{ event.capacity_current}}/{{checkCapacityValue(event)}}</td>
                        <td><v-btn variant="text"
                                   color="green"
                                   @click="approveEvent(event)">Approve</v-btn></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="selectedOpen = false">Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="deleteEvent(event)">Delete</v-btn></td>
                    </tr>
                    </tbody>
                </table>
                <Bootstrap5Pagination
                    :data="eventsUnapproved"
                    @pagination-change-page="fetchUnapprovedEvents"
                />
            </div>
        </div>
    </div>
</template>

<script>
import {isRole, getAuthUser} from "../app";
import {RoleEnum} from "../enums/RoleEnum";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    components: {
      Bootstrap5Pagination
    },
    data() {
        return {
            eventsApproved: [],
            eventsUnapproved: [],
            authUser: null,
            roleEnum: RoleEnum,
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedEvents();
        this.fetchUnapprovedEvents();
    },
    methods: {
        fetchApprovedEvents(page=1) {
            axios.get('/event/approved?page=' + page)
                .then(response => {
                    this.eventsApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                })
        },
        fetchUnapprovedEvents(page=1) {
            console.log(page)
            axios.get('/event/unapproved?page=' + page)
                .then(response => {
                    this.eventsUnapproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                })
        },
        approveEvent(event) {
            const url = `/event/${event.id}`;
            axios.patch(url)
                .then(() => {
                    this.fetchApprovedEvents();
                    this.fetchUnapprovedEvents();
                })
                .catch(error => {
                    console.error('Error approving event:', error);
                });
        },
        deleteEvent(event) {
            const url = `/event/${event.id}`;
            axios.delete(url)
                .then(() => {
                    this.fetchApprovedEvents();
                    this.fetchUnapprovedEvents();
                })
                .catch(error => {
                    console.error('Error deleting event:', error);
                });
        },
        checkCapacityValue(event) {
            if (event.capacity_max == null){
                return "∞";
            }
            else {
                return event.capacity_max;
            }
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
