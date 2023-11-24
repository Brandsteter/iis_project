<template>
    <div  v-if="upcomingEvents.length > 0">
        <p class="header-text-format"><b>Upcoming events</b></p>
        <div class="list-container">
            <table >
                <thead>
                <tr >
                    <th>Name</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Place</th>
                    <th>Capacity</th>
                    <th>Creator</th>
                </tr>
                </thead>
                <tbody class="list-container">
                <tr v-for="(event, index) in upcomingEvents">
                        <td >{{ event.name }}</td>
                        <td>{{ event.event_start}}</td>
                        <td>{{ event.event_end}}</td>
                        <td>{{ event.place.name}}</td>
                        <td :class="{'red-text': event.capacity_current === checkCapacityValue(event)}" v-if="event.capacity_current === checkCapacityValue(event)">
                          {{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
                        <td v-else>{{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
                        <td>{{event.creator.name}}</td>
                        <td><v-btn variant="text" color="secondary" :href="`/event/${event.id}/detail`">Detail</v-btn></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="openEditModal(event)"
                                   v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                   Edit</v-btn></td>
                        <td><v-btn variant="text"
                                   color="secondary"
                                   @click="assignCategory(event)"
                                   v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                   Add category</v-btn></td>
                        <td><v-btn variant="text"
                                   color="red"
                                   @click="showConfirm(event)"
                                   v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
                                   Delete</v-btn></td>


                </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                :data="upcomingEvents"
                @pagination-change-page="fetchApprovedEvents"
            />
        </div>

    </div>
    <div  v-if="previousEvents.length > 0">
      <p class="header-text-format"><b>Previous events</b></p>
      <div class="list-container">
        <table >
          <thead>
          <tr >
            <th>Name</th>
            <th>Start date</th>
            <th>End date</th>
            <th>Place</th>
            <th>Capacity</th>
            <th>Creator</th>
            <th>Attending</th>
          </tr>
          </thead>
          <tbody class="list-container">
          <tr v-for="(event, index) in previousEvents">
            <td >{{ event.name }}</td>
            <td>{{ event.event_start}}</td>
            <td>{{ event.event_end}}</td>
            <td>{{ event.place.name}}</td>
            <td :class="{'red-text': event.capacity_current === checkCapacityValue(event)}" v-if="event.capacity_current === checkCapacityValue(event)">
              {{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
            <td v-else>{{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
            <td>{{event.creator.name}}</td>
            <td></td>
            <td><v-btn variant="text" color="secondary" :href="`/event/${event.id}/detail`">Detail</v-btn></td>
            <td><v-btn variant="text"
                       color="secondary"
                       @click="openEditModal(event)"
                       v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
              Edit</v-btn></td>
            <td><v-btn variant="text"
                       color="secondary"
                       @click="assignCategory(event)"
                       v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
              Add category</v-btn></td>
            <td><v-btn variant="text"
                       color="red"
                       @click="showConfirm(event)"
                       v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
              Delete</v-btn></td>

          </tr>
          </tbody>
        </table>
        <Bootstrap5Pagination
            :data="previousEvents"
            @pagination-change-page="fetchApprovedEvents"
        />
      </div>

    </div>
    <div v-else>
        <p style="font-size: 20px ; margin-left:10px"><b>No events yet</b></p>
    </div>

    <v-dialog v-model="showModal" max-width="400" :persistent="true">
        <v-card class="card" style="background-color: lightskyblue; border-radius: 10px;">
            <v-card-text>
                <form>
                    <label style="font-size: x-large" class="form-label">Edit event</label>
                    <div class="mb-3">
                        <label for="InputName" class="form-label">Name of event<span style="color: red;">*</span></label>
                        <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                        <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputEventStart" class="form-label">Start of event<span style="color: red;">*</span></label>
                        <input type="date" id="InputEventStart" class="form-control" v-model="fields.event_start" maxlength="255" required>
                        <span v-if="errorMessages.errors.event_start" style="color: red;">{{errorMessages.errors.event_start[0]}}</span>
                    </div>
                    <div class="mb-3">
                      <label for="InputEventStart" class="form-label">Start of event</label>
                      <input type="time" id="InputEventStart" class="form-control" v-model="fields.event_start_time" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="InputEventEnd" class="form-label">End of event<span style="color: red;">*</span></label>
                        <input type="date" id="InputEventEnd" class="form-control" v-model="fields.event_end" maxlength="255" required>
                        <span v-if="errorMessages.errors.event_end" style="color: red;">{{errorMessages.errors.event_end[0]}}</span>
                    </div>
                    <div class="mb-3">
                        <label for="InputEventEnd" class="form-label">End of event</label>
                        <input type="time" id="InputEventEnd" class="form-control" v-model="fields.event_end_time" maxlength="255">
                    </div>
                  <div class="mb-3">
                    <label for="InputCapacityMax" class="form-label">Maximal capacity (unlimited if not specified)</label>
                    <input class="form-control" id="InputCapacityMax" v-model="fields.capacity_max" maxlength="255" type="number">
                  </div>
                  <div class="mb-3">
                    <label for="InputPlace" class="form-label">Select place<span style="color: red;">*</span></label>
                    <select class="form-control" id="InputPlace" v-model="fields.place_id" required>
                        <option value="none" selected disabled hidden>Select an Option</option>
                        <option v-for="(place) in placesApproved.data" :value="place.id">{{place.name}}</option>
                    </select>
                    <span v-if="errorMessages.errors.place_id" style="color: red;">{{errorMessages.errors.place_id[0]}}</span>
                  </div>
                  <div class="mb-3">
                      <label for="InputDescription" class="form-label">Description<span style="color: red;">*</span></label>
                    <textarea class="form-control" id="InputDescription" v-model="fields.description" maxlength="255" type="text" required></textarea>
                  </div>
                  <span style="color: red;">* - the field is required</span>
                  <div style="margin-bottom: 10px;"></div>
                  <div class="d-flex justify-content-center">
                    <div class="button-container">
                      <v-btn @click="cancelSubmit" min-width="100" color="grey-darken-3" class="mr-15">Cancel</v-btn>
                      <v-btn @click="submit" color="grey-darken-3" min-width="100">Submit</v-btn>
                    </div>
                  </div>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>

    <!--Event delete confirmation window-->
    <v-dialog v-model="showConfirmation" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Do you want to delete this event?</v-card-title>
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

    <!--Select Category for event-->
    <v-dialog v-model="showCategoryAssignment" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Select a category for this event</v-card-title>
            <form>
                <div class="mb-3">
                    <label for="InputPlace" class="form-label">Select category</label>
                    <select class="form-control" id="InputPlace" v-model="eventCategory.category_id" required>
                        <option value="none" selected disabled hidden>Select an Option</option>
                        <option v-for="(category) in categoriesApproved" :value="category.id">{{category.name}}</option>
                    </select>
                    <span v-if="errorMessages.errors.category_id" style="color: red;">{{errorMessages.errors.category_id[0]}}</span>
                </div>
                <div class="d-flex justify-content-center">
                    <v-btn @click="addCategory()" color="grey-darken-3">
                        Submit
                    </v-btn>
                </div>
            </form>
        </v-card>
    </v-dialog>

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
            categoriesApproved: [],
            authUser: null,
            roleEnum: RoleEnum,
            showModal: false,
            modalMode: 'create',
            showConfirmation: false,
            eventManipulate: null,
            eventCategory: {
                category_id: "",
            },
            showCategoryAssignment : false,
            eventDeleteConfirm: null,
            fields: {
                id: "",
                name: "",
                event_start: "",
                event_start_time: "",
                event_end: "",
                capacity_max: "",
                place_id: "",
                description: "",
                creator_user_id: "",
            },
          errorMessages: {
            message: "",
            errors: {
              name: null,
              event_start: null,
              event_end: null,
              place_id: null,
              category_id: null,
            }
          },
        };
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
        this.fields.creator_user_id = this.authUser.id;
    },
    mounted() {
        this.fetchApprovedEvents();
        this.fetchUnapprovedEvents();
        this.fetchApprovedPlaces();
        this.fetchApprovedCategories();
    },
    computed: {
      previousEvents() {
        return (this.eventsApproved.data ?? []).filter(event => new Date(event.event_end) < new Date());
      },
      upcomingEvents() {
        return (this.eventsApproved.data ?? []).filter(event => new Date(event.event_end) >= new Date());
      }
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
        addCategory() {
            const url = `/event/${this.eventManipulate.id}/add-category`;
            axios.post(url, this.eventCategory)
                .then((response) => {
                    if (response) {
                        window.location.href = '/event'
                    }
                })
                .catch((error) => {
                  if (error.response && error.response.data.message) {
                    this.errorMessages = error.response.data;
                    console.log(this.errorMessages);
                  }
                });
        },
        openEditModal(event) {
            this.modalMode = 'edit';
            this.showModal = true;
            this.fields = { ...event };
        },
        submit() {
            axios.put(`/event/${this.fields.id}`, this.fields).then((response) => {
                if (response) {
                    window.location.href = '/event'
                }
            })
            .catch((error) => {
              if (error.response && error.response.data.message) {
                this.errorMessages = error.response.data;
              }
            });
        },
        cancelSubmit() {
          this.showModal = false;
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
        fetchApprovedCategories() {
            axios.get('/category/approved')
                .then(response => {
                    this.categoriesApproved = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories', error);
                })
        },
        showConfirm(event) {
            this.showConfirmation = true;
            this.eventDeleteConfirm = event;
        },
        confirmDelete() {
            this.showConfirmation = false;
            this.deleteEvent(this.eventDeleteConfirm);
            this.eventDeleteConfirm = null;
        },
        cancelDelete() {
            this.showConfirmation = false;
            this.eventDeleteConfirm = null;
        },
        assignCategory(event) {
            this.showCategoryAssignment = true;
            this.eventManipulate = event;
        },

        isRole,
        getAuthUser,
    }
};
</script>

<style>

.header-text-format {
    font-size: 30px;
    margin: 0;
    color: #07abd5;
}

.list-container{
    background-color: #91deff;
    width: 100%;
    border-radius: 10px;
    padding: 10px;
}



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

.red-text {
  color: red;
}

.button-container {
  margin-top: 10px;
  margin-bottom: 10px;
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
</style>
