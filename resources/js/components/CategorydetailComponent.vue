<template>
    <div>
        <h1>Category Details</h1>
        <h2>{{category.name}}</h2>
    </div>
    <v-btn @click="newSubcategory" prepend-icon="mdi-plus">Add new subcategory</v-btn>
    <h3>Events</h3>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Start date</th>
                <th>End date</th>
<!--                <th>Place</th>-->
                <th>Capacity</th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="(event, index) in category.events" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
            <td>{{ event.name }}</td>
            <td>{{ event.event_start}}</td>
            <td>{{ event.event_end}}</td>
<!--            <td>{{ event.place.name}}</td>-->
            <td :class="{'red-text': event.capacity_current === checkCapacityValue(event)}" v-if="event.capacity_current === checkCapacityValue(event)">
              {{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
            <td v-else>{{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
            <td><v-btn variant="text" color="secondary" :href="`/event/${event.id}/detail`">Detail</v-btn></td>

          <!--            <td><v-btn variant="text"-->
<!--                       color="secondary"-->
<!--                       @click="openEditModal(event)">Edit</v-btn></td>-->
<!--            <td><v-btn variant="text"-->
<!--                       color="red"-->
<!--                       @click="showConfirm(event)">Delete</v-btn></td>-->
<!--            <td><v-btn variant="text"-->
<!--                       color="secondary"-->
<!--                       @click="assignCategory(event)"-->
<!--                       v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)">-->
<!--                Add category</v-btn></td>-->
        </tr>
        </tbody>
    </table>

    <!--Create new subcategory-->
    <v-dialog v-model="showNewSubcategoryModal" max-width="400" max-height="250">
        <v-card class="card" style=" border-radius: 10px;">
            <v-card-title class="confirm-title">Create a new sub-category for {{category.name}}</v-card-title>
            <form>
                <div class="mb-3">
                    <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                    <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
                </div>
                <span style="color: red;">* - the field is required</span>
                <div style="margin-bottom: 10px;"></div>
                <div class="d-flex justify-content-center">
                    <v-btn @click="submit()" color="grey-darken-3">
                        Submit
                    </v-btn>
                </div>
            </form>
        </v-card>
    </v-dialog>
</template>

<script>
import {isRole, getAuthUser} from "../app";
export default {
    props: {
        category: {
            required: true,
            type: Object,
        }
    },
    data() {
       return {
           userId: null,
           showNewSubcategoryModal: false,
           fields: {
             name: null,
             parent_category_id: this.category.id
           },
       }
    },
    methods: {
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
      newSubcategory() {
        this.showNewSubcategoryModal = true;
      },
      submit() {
        axios.post('/category', this.fields).then((response) => {
            if (response) {
                window.location.href = `/category/${this.category.id}/detail`
            }
        })
      },
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
    },

}
</script>

<style>

.red-text {
  color: red;
}
</style>
