<template>
 <div>
<!--   Unapproved events-->
   <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
     <p class="header-text-format"><b>Unapproved events</b></p>
     <div class="list-container">
       <table>
         <thead>
         <tr>
           <th>Name</th>
           <th>Start date</th>
           <th>End date</th>
           <th>Place</th>
           <th>Capacity</th>
           <th>Creator</th>
         </tr>
         </thead>
         <tbody>
         <tr v-for="(event, index) in eventsUnapproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
           <td>{{ event.name }}</td>
           <td>{{ event.event_start}}</td>
           <td>{{ event.event_end}}</td>
           <td>{{ event.place.name}}</td>
           <td :class="{'red-text': event.capacity_current === checkCapacityValue(event)}" v-if="event.capacity_current === checkCapacityValue(event)">
             {{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
           <td v-else>{{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
           <td v-if="event.creator === null">Deleted User</td>
           <td v-else>{{event.creator.name}}</td>
           <td><v-btn variant="text"
                      color="green"
                      @click="approveEvent(event)">Approve</v-btn></td>
           <td><v-btn variant="text"
                      color="secondary"
                      @click="eventOpenEditModal(event)">Edit</v-btn></td>
           <td><v-btn variant="text"
                      color="red"
                      @click="eventShowConfirm(event)">Delete</v-btn></td>
         </tr>
         </tbody>
       </table>
       <Bootstrap5Pagination
         :data="eventsUnapproved"
         @pagination-change-page="fetchUnapprovedEvents"
       />
     </div>
   </div>

<!--Unapproved places-->
   <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
     <p class="header-text-format"><b>Unapproved places</b></p>
     <div class="list-container">
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
           <td></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>

           <td><v-btn variant="text"
                      color="green"
                      @click="approvePlace(place)">Approve</v-btn></td>
           <td><v-btn variant="text"
                      color="secondary"
                      @click="placeOpenEditModal(place)">Edit</v-btn></td>
           <td><v-btn variant="text"
                      color="red"
                      @click="placeShowConfirm(place)">Delete</v-btn></td>

         </tr>
         </tbody>
       </table>
         <Bootstrap5Pagination
             :data="placesUnapproved"
             @pagination-change-page="fetchUnapprovedPlaces"
         />
     </div>
   </div>

<!--   Unapproved categories-->
   <div v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">
     <p class="header-text-format"><b>Unapproved categories</b></p>
     <div class="list-container">
       <table>
         <thead>
         <tr>
           <th>Name</th>
           <th>Parent</th>
         </tr>
         </thead>
         <tbody>
         <tr v-for="(category, index) in categoriesUnapproved.data" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
           <td>{{category.name}}</td>
           <td v-if="category.category !== null">{{category.category.name}}</td>
           <td v-else>--</td>
           <td><v-btn variant="text"
                      color="green"
                      @click="approveCategory(category)">Approve</v-btn></td>
           <td><v-btn variant="text"
                      color="secondary"
                      @click="categoryOpenEditModal(category)">Edit</v-btn></td>
           <td><v-btn variant="text"
                      color="red"
                      @click="categoryShowConfirm(category)">Delete</v-btn></td>
         </tr>
         </tbody>
           <Bootstrap5Pagination
               :data="categoriesUnapproved"
               @pagination-change-page="fetchUnapprovedCategories"
           />
       </table>
     </div>
   </div>

<!--   Event edit modal-->
   <v-dialog v-model="eventShowModal" max-width="400" :persistent="true">
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
               <v-btn @click="editEvent" color="grey-darken-3" min-width="100">Submit</v-btn>
             </div>
           </div>
         </form>
       </v-card-text>
     </v-card>
   </v-dialog>

   <!--Event delete confirmation window-->
   <v-dialog v-model="eventShowConfirmation" max-width="400" max-height="250">
     <v-card class="card" style="border-radius: 10px;">
       <v-card-title class="confirm-title">Do you want to delete this event?</v-card-title>
       <div class="button-container">
         <v-btn @click="eventConfirmDelete()"
                min-width="80"
                color="green">Yes</v-btn>
         <v-btn @click="cancelDelete()"
                min-width="80"
                color="red">No</v-btn>
       </div>
     </v-card>
   </v-dialog>

   <!--Place Edit modal-->
   <v-dialog v-model="placeShowModal" max-width="600" :persistent="true">
     <v-card class="card" style="background-color: lightskyblue; border-radius: 10px;">
       <v-card-title>Edit place</v-card-title>
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
             <div class="button-container">
               <v-btn @click="cancelSubmit" min-width="100" color="grey-darken-3" class="mr-15">Cancel</v-btn>
               <v-btn @click="editPlace" color="grey-darken-3" min-width="100">Submit</v-btn>
             </div>
           </div>
         </form>
       </v-card-text>
     </v-card>
   </v-dialog>

   <!--Place delete confirmation window-->
   <v-dialog v-model="placeShowConfirmation" max-width="400" max-height="250">
     <v-card class="card" style=" border-radius: 10px;">
       <v-card-title class="confirm-title">Do you want to delete this place?</v-card-title>
       <div class="button-container">
         <v-btn @click="placeConfirmDelete()"
                min-width="80"
                color="green">Yes</v-btn>
         <v-btn @click="cancelDelete()"
                min-width="80"
                color="red">No</v-btn>
       </div>
     </v-card>
   </v-dialog>

   <!--Category edit modal-->
   <v-dialog v-model="categoryShowModal" max-width="400" max-height="250" :persistent="true">
     <v-card class="card" style="background-color: lightskyblue; border-radius: 10px;">
       <v-card-title class="confirm-title">Edit category</v-card-title>
       <form>
         <div class="mb-3">
           <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
           <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
           <span v-if="errorMessages.errors.name" style="color: red;">{{errorMessages.errors.name[0]}}</span>
         </div>
         <span style="color: red;">* - the field is required</span>
         <div style="margin-bottom: 10px;"></div>
         <div class="d-flex justify-content-center">
           <div class="button-container">
             <v-btn @click="cancelSubmit" min-width="100" color="grey-darken-3" class="mr-15">Cancel</v-btn>
             <v-btn @click="editCategory" color="grey-darken-3" min-width="100">Submit</v-btn>
           </div>
         </div>
       </form>
     </v-card>
   </v-dialog>

   <!--Category delete confirmation window-->
   <v-dialog v-model="categoryShowConfirmation" max-width="600" max-height="250">
     <v-card class="card" style=" border-radius: 10px;">
       <v-card-title class="confirm-title">Do you want to delete this category? </v-card-title>
       <div class="text-center"> All its subcategories will also be removed and events will lose their category</div>
       <div class="button-container">
         <v-btn @click="categoryConfirmDelete()"
                min-width="80"
                color="green">Yes</v-btn>
         <v-btn @click="cancelDelete()"
                min-width="80"
                color="red">No</v-btn>
       </div>
     </v-card>
   </v-dialog>
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
      eventsUnapproved: [],
      placesUnapproved: [],
      placesApproved: [],
      categoriesUnapproved: [],
      categoriesApproved: [],
      authUser: null,
      roleEnum: RoleEnum,
      eventShowModal: false,
      placeShowModal: false,
      categoryShowModal: false,
      eventShowConfirmation: false,
      placeShowConfirmation: false,
      categoryShowConfirmation: false,
      eventDeleteConfirm: null,
      placeDeleteConfirm: null,
      categoryDeleteConfirm: null,
      fields: {
        id: "",
        name: "",
        address: "",
        description: "",
        parent_category_id: null,
        event_start: "",
        event_start_time: "",
        event_end: "",
        capacity_max: "",
        place_id: "",
        creator_user_id: "",
      },
      errorMessages: {
        message: "",
        errors: {
          name: null,
          address: null,
          description: null,
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
  },
  mounted() {
    this.fetchUnapprovedPlaces();
    this.fetchUnapprovedCategories();
    this.fetchUnapprovedEvents();
    this.fetchApprovedPlaces();
    this.fetchApprovedCategories();
  },
  methods: {
    fetchUnapprovedEvents(page=1) {
      axios.get('/event/unapproved?page=' + page)
        .then(response => {
          this.eventsUnapproved = response.data;
        })
        .catch(error => {
          console.error('Error fetching events:', error);
        })
    },

    fetchUnapprovedPlaces(page=1) {
      axios.get('/place/unapproved?page=' + page)
        .then(response => {
          this.placesUnapproved = response.data;
        })
        .catch(error => {
          console.error('Error fetching events:', error);
        })
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
    approveEvent(event) {
      const url = `/event/${event.id}`;
      axios.patch(url)
        .then(() => {
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
          this.fetchUnapprovedEvents();
        })
        .catch(error => {
          console.error('Error deleting event:', error);
        });
    },
    approvePlace(place) {
      const url = `/place/${place.id}`;
      axios.patch(url)
        .then(() => {
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
          this.fetchUnapprovedPlaces();
        })
        .catch(error => {
          console.error('Error deleting event:', error);
        });
    },
    fetchUnapprovedCategories(page=1) {
      axios.get('/category/unapproved?page=' + page)
        .then(response => {
          this.categoriesUnapproved = response.data;
        })
        .catch(error => {
          console.error('Error fetching unapproved categories:', error);
        })
    },
    approveCategory(category) {
      const url = `/category/${category.id}`;
      axios.patch(url)
        .then(() => {
          this.fetchUnapprovedCategories();
        })
        .catch(error => {
          console.error('Error approving category:', error);
        });
    },
    deleteCategory(category) {
      const url = `/category/${category.id}`;
      axios.delete(url)
        .then(() => {
          this.fetchUnapprovedCategories();
        })
        .catch(error => {
          console.error('Error deleting category:', error);
        });
    },
    eventOpenEditModal(event) {
      this.eventShowModal = true;
      this.fields = { ...event };
    },
    placeOpenEditModal(place) {
      this.placeShowModal = true;
      this.fields = { ...place };
    },
    categoryOpenEditModal(category) {
      this.categoryShowModal = true;
      this.fields = { ...category };
    },
    editPlace() {
        const url = `/place/${this.fields.id}`;
        axios.put(url, this.fields).then((response) => {
          if (response) {
            window.location.href = '/moderator'
          }
        })
        .catch((error) => {
          if (error.response && error.response.data.message) {
            this.errorMessages = error.response.data;
          }
        });
    },
    editEvent() {
      axios.put(`/event/${this.fields.id}`, this.fields).then((response) => {
        if (response) {
          window.location.href = '/moderator'
        }
      })
        .catch((error) => {
          if (error.response && error.response.data.message) {
            this.errorMessages = error.response.data;
          }
        });
    },
    editCategory() {
      const url = `/category/${this.fields.id}`
      axios.put(url, this.fields).then((response) => {
        if (response) {
          window.location.href = '/moderator'
        }
      })
        .catch((error) => {
          if (error.response && error.response.data.message) {
            this.errorMessages = error.response.data;
          }
        });
    },
    cancelSubmit() {
      this.categoryShowModal = false;
      this.placeShowModal = false;
      this.eventShowModal = false;
      this.resetErrors();
    },
    resetErrors() {
      this.errorMessages.message = "";
      this.errorMessages.errors.name = null;
      this.errorMessages.errors.address = null;
      this.errorMessages.errors.event_start = null;
      this.errorMessages.errors.event_end = null;
      this.errorMessages.errors.place_id = null;
      this.errorMessages.errors.category_id = null;
      this.errorMessages.errors.description = null;
    },
    eventShowConfirm(event) {
      this.eventShowConfirmation = true;
      this.eventDeleteConfirm = event;
    },
    placeShowConfirm(place) {
      this.placeShowConfirmation = true;
      this.placeDeleteConfirm = place;
    },
    categoryShowConfirm(category) {
      this.categoryShowConfirmation = true;
      this.categoryDeleteConfirm = category;
    },
    eventConfirmDelete() {
      this.eventShowConfirmation = false;
      this.deleteEvent(this.eventDeleteConfirm);
      this.eventDeleteConfirm = null;
    },
    placeConfirmDelete() {
      this.placeShowConfirmation = false;
      this.deletePlace(this.placeDeleteConfirm);
      this.placeDeleteConfirm = null;
    },
    categoryConfirmDelete() {
      this.categoryShowConfirmation = false;
      this.deleteCategory(this.categoryDeleteConfirm);
      this.categoryDeleteConfirm = null;
    },
    cancelDelete() {
      this.eventShowConfirmation = false;
      this.placeShowConfirmation = false;
      this.categoryShowConfirmation = false;
      this.eventDeleteConfirm = null;
      this.placeDeleteConfirm = null;
      this.categoryDeleteConfirm = null;
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

  .text-center {
    align-items: center;
  }
</style>
