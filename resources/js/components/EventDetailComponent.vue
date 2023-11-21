
<template>
<h1 class="header-size">{{eventMutable.name}}</h1>
    <div class="inline-components">
        <v-icon color="black">mdi-map-marker</v-icon>
        <p class="text-format">{{ eventMutable.place.name }}</p>
        <p class="text-format">{{ eventMutable.place.address }}</p>
    </div>
    <br>
    <div class="inline-components">
        <v-icon color="black">mdi-calendar</v-icon>
        <p class="text-format">{{eventMutable.event_start }}</p>
        <p class="text-format"> - </p>
        <p class="text-format">{{eventMutable.event_end }}</p>
    </div>


    <p class="text-description">Description:</p>
    <div class="description-box">
        <p class="text-description">{{ eventMutable.description }}</p>
    </div>

    <v-btn v-if="!checkIfUserIsInArrayOfUsers(eventMutable.users, authUser)" class="attend-button" @click="attendEvent(eventMutable)">
        Attend
    </v-btn>
    <v-btn v-else class="attend-button" color="red" @click="">
      Leave event
    </v-btn>

    <br>
    <br>
    <div>
        <h2>Rate this event</h2>

    </div>

    <div class="users-comment-input">
        <h2>Comments:</h2>
        <div class="users-comment-input">
            <p>tu budu zobrazene komentare</p>
        </div>
        <div class="users-comment-input">
            <label for="InputComment" class="form-label">Comment on this event:</label>
            <br>
            <div class="inline-components users-comment-input">
                <input id="InputComment" class="form-control" v-model="commentBody" type="text" maxlength="255"  required>
                <v-btn  @click="createComment(eventMutable)">
                    Post
                </v-btn>
            </div>
        </div>
    </div>


</template>

<script>
import {isRole, getAuthUser, checkIfUserIsInArrayOfUsers} from "../app";
import {RoleEnum} from "../enums/RoleEnum";

export default {
    props: {
        event: {
            required: true,
            type: Object,
        }
    },
    created: async function(){
      this.authUser = await this.getAuthUser()
    },
    data() {
        return {
            value: null,
            isAttending: true,
            authUser: null,
            roleEnum: RoleEnum,
            commentBody: "",
            userEvents: [],
            eventMutable: this.event,
        };
    },
    methods: {
        attendEvent(eventMutable){
            const url = `/event/${eventMutable.id}`;
            axios.post(url, eventMutable)
                .then(response => {
                    this.eventMutable = response.data;
                    window.location.href = `${url}/detail`;
                })
                .catch(error => {
                    console.error('Error attending event:', error);
                })
        },
        createComment(eventMutable){
          const url = `/event/${eventMutable.id}/comments`;
          const requestData = {
            body: this.commentBody,
            event_id: eventMutable.id,
            user_id: this.authUser.id,
          };
          axios.post(url, requestData)
              .then(response => {
                console.log("Comment successfully created?");
              })
              .catch(error => {
                console.error('Error attending event:', error);
              })
        },
        isRole,
        getAuthUser,
        checkIfUserIsInArrayOfUsers,

    },


}
</script>

<style>
.header-size{
    font-size: 50px;
}
.inline-components {
    display: inline-flex;
    align-items: center;
}
.text-format {
    font-size: 30px;
    margin-left: 10px;
}

.text-description{
    font-size: 20px;
}
.description-box{
    padding: 10px;
    border-radius: 10px; /* Adjust the value to change the roundness of the corners */
    width: 75%; /* Adjust the value to set the width as a percentage of the page */
    margin-right: auto; /* Center the box horizontally */
    box-sizing: border-box; /* Include padding and border in the box's total width and height */
    border: 1px solid black;

}
.attend-button {
    margin: 10px;
    background-color: #05c76c;
    color: white;
}
.users-comment-input {
    padding: 10px;
    background-color: #bbbbbb;
    border-radius: 10px; /* Adjust the value to change the roundness of the corners */
    width: 75%; /* Adjust the value to set the width as a percentage of the page */
    margin-right: auto; /* Center the box horizontally */
    box-sizing: border-box; /* Include padding and border in the box's total width and height */
}
</style>
