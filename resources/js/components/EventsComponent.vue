<template>
    <div>
        <h1>Events</h1>
        <div>
            <h4>Halo</h4>
            <h4></h4>
        </div>
        <div>
            <v-btn @click="toggleForm" prepend-icon="mdi-plus">Create a new place</v-btn>
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
    <div v-if="showForm" class="card" style="width: 400px;  background-color: lightskyblue; padding: 20px; border-radius: 10px;">
        <form>
            <label style="font-size: x-large" class="form-label">Create a new event</label>
            <div class="mb-3">
                <label for="InputName" class="form-label">Name of event</label>
                <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <label for="InputEventStart" class="form-label">Start of event</label>
                <input type="date" id="InputEventStart" class="form-control" v-model="fields.event_start" maxlength="255" required>
            </div>
            <div class="mb-3">
                <label for="InputEventEnd" class="form-label">End of event</label>
                <input type="date" id="InputEventEnd" class="form-control" v-model="fields.event_end" maxlength="255" required>
            </div>
          <div class="mb-3">
            <label for="InputCapacityMax" class="form-label">Maximal capacity (unlimited if not specified)</label>
            <input class="form-control" id="InputCapacityMax" v-model="fields.capacity_max" maxlength="255" type="number">
          </div>
          <div class="mb-3">
            <label for="InputPlace" class="form-label">Select place</label>
            <select class="form-control" id="InputPlace" v-model="fields.place_id" required>
                <option value="none" selected disabled hidden>Select an Option</option>
<!--                <option v-for="(place) in placesApproved.data" value="{{place.place_id}}">{{place.name}}</option>-->
                <option value="{{placesApproved.data[0].place_id}}">{{placesApproved.data[0].name}}</option>
            </select>
          </div>
          <div class="mb-3">
              <label for="InputDescription" class="form-label">Description</label>
            <textarea class="form-control" id="InputDescription" v-model="fields.description" maxlength="255" type="text" required></textarea>
          </div>
          <div class="d-flex justify-content-center">
              <v-btn @click="submit" color="grey-darken-3">
                  Submit
              </v-btn>
          </div>
        </form>

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
            placesApproved: [],
            authUser: null,
            roleEnum: RoleEnum,
            showForm: false,
            fields: {
                name: "",
                event_start: "",
                event_end: "",
                capacity_max: "",
                place_id: "",
                description: "",
            },
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.fetchApprovedEvents();
        this.fetchUnapprovedEvents();
        this.fetchApprovedPlaces();
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
        submit() {
          axios.post('/event', this.fields).then((response) => {
            if (response) {
              window.location.href = '/event'
            }
          })
        },
        toggleForm() {
            this.showForm = !this.showForm;
        },
        fetchApprovedPlaces() {
            axios.get('/place/approved')
                .then(response => {
                    this.placesApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
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
