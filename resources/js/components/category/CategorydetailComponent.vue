<template>
    <div>
        <p class="header-text-format"><b>Events in category {{category.name}}</b></p>
    </div>
    <div>
        <v-btn v-if="checkIfIsLoggedIn()" class="create-event-button" @click="newSubcategory()" prepend-icon="mdi-plus">Add new subcategory</v-btn>
    </div>

    <div v-if="category.events.length > 0" class="list-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Capacity</th>
                </tr>
            </thead>
            <tbody>
            <tr v-for="event in category.events">
                <td>{{ event.name }}</td>
                <td>{{ event.event_start}}</td>
                <td>{{ event.event_end}}</td>
                <td :class="{'red-text': event.capacity_current === checkCapacityValue(event)}" v-if="event.capacity_current === checkCapacityValue(event)">
                  {{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
                <td v-else>{{ event.capacity_current }}/{{ checkCapacityValue(event) }}</td>
                <td><v-btn variant="text" color="secondary" :href="`/event/${event.id}/detail`">Detail</v-btn></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div v-else>
        <p style="font-size: 20px ; margin-left:10px"><b>No events in this category</b></p>
    </div>




    <!--Create new subcategory-->
    <v-dialog v-model="showNewSubcategoryModal" max-width="400" max-height="250" :persistent="true">
        <v-card class="card" style=" background-color: lightskyblue; border-radius: 10px;">
            <v-card-title class="confirm-title">Create a new sub-category for {{category.name}}</v-card-title>
            <form>
                <div class="mb-3">
                    <label for="InputName" class="form-label">Name<span style="color: red;">*</span></label>
                    <input id="InputName" class="form-control" v-model="fields.name" type="text" maxlength="255" aria-describedby="emailHelp" required>
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
        </v-card>
    </v-dialog>

</template>

<script>
import {isRole, getAuthUser} from "../../app";
import {RoleEnum} from "../../enums/RoleEnum";
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
           authUser: null,
           roleEnum: RoleEnum,
           showNewSubcategoryModal: false,
           fields: {
             name: null,
             parent_category_id: this.category.id
           },
       }
    },
    created: async function(){
        this.authUser = await this.getAuthUser()
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
      cancelSubmit() {
        this.showNewSubcategoryModal = false;
      },
      checkIfIsLoggedIn(){
        return (isRole(this.roleEnum.User , this.authUser) || isRole(this.roleEnum.Moderator , this.authUser) || isRole(this.roleEnum.Admin , this.authUser));
      },

      isRole,
      getAuthUser,
    },

}
</script>

<style>
  .red-text {
    color: red;
  }

  .text-format {
      margin-left: 10px;
      color: #5b5b5b;
  }

  .create-event-button{
      margin:20px;
      background-color: #91deff;
  }

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
</style>
