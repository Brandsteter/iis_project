
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

    <div v-if="checkIfIsLoggedIn() && !checkIfEventEnded(event)" >
      <v-btn v-if="(!checkIfUserIsInArrayOfUsers(eventMutable.users, authUser)) && (eventMutable.capacity_current !== checkCapacityValue(eventMutable)) " class="attend-button" @click="attendEvent(eventMutable)">
          Attend
      </v-btn>
      <v-btn v-else-if="!checkIfUserIsInArrayOfUsers(eventMutable.users, authUser)" disabled class="attend-button-disabled">
          Attend
      </v-btn>
      <v-btn v-else class="attend-button" color="red" @click="unattendEvent(eventMutable)">
        Leave event
      </v-btn>
      </div>
      <div v-else>
        <br>
        <a v-if="!checkIfEventEnded(event)" href="/auth/login">Login to be able to attend in event</a>
      </div>

    <div v-if="checkIfEventEnded(event)" >
      <div v-if="(checkIfIsLoggedIn() && checkIfEventEnded(event)) && checkIfUserIsInArrayOfUsers(eventMutable.users, authUser)">
        <div class="users-comment-input">
          <h5>Rate this event</h5>
          <div class="star-rating">
                <span
                    v-for="index in 5"
                    :key="index"
                    @mouseover="hoverOver(index)"
                    @click="selectRating(index)"
                    @mouseleave="hoverOut">
                   <i :class="{ 'fas': index <= (hoverValue || selectedRating), 'far': index > (hoverValue || selectedRating) }" class="fa-star"></i>
                </span>
          </div>

          <br>
          <div class="inline-components comment-input-box" >
            <input id="InputComment" class="form-control" v-model="commentBody" type="text" maxlength="255"  required>
            <v-btn class="post-button" variant="text" @click="createComment(eventMutable)">
              Post
            </v-btn>
          </div>
        </div>
      </div>
      <h2>Comments:</h2>
      <div class="users-comment-input-all">
        <div class="comment-box" v-for="(comment, index) in comments">
            <v-btn  class="delete-button" density="compact" variant="text"  v-if="isRole(roleEnum.Moderator , authUser) || isRole(roleEnum.Admin , authUser)"
                   color="red" @click="deleteComment(eventMutable,comment)">
              Delete
            </v-btn>
            <h5><b>{{comment.user.name}}</b></h5>
            <span v-for="starIndex in 5" :key="starIndex">
              <i :class="{ 'fas': starIndex < comment.rating, 'far': starIndex >= comment.rating }" class="fa-star"></i>
            </span>
            <p class="comment-text">{{comment.body}}</p>
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
            hoverValue: 0,
            selectedRating: 0,
            isAttending: true,
            authUser: null,
            roleEnum: RoleEnum,
            commentBody: "",
            userEvents: [],
            eventMutable: this.event,
            comments: [],
        };
    },
    mounted() {
      this.fetchComments(this.eventMutable)
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
        unattendEvent(eventMutable){
          const url = `/event/${eventMutable.id}/unattend`;
          axios.post(url, eventMutable)
              .then(response => {
                window.location.href = `/event/${eventMutable.id}/detail`;
              })
              .catch(error => {
                console.error('Error unattending event:', error);
              })

        },
        createComment(eventMutable){
          const url = `/event/${eventMutable.id}/comments`;
          const requestData = {
            body: this.commentBody,
            event_id: eventMutable.id,
            user_id: this.authUser.id,
            rating: this.selectedRating,

          };
          axios.post(url, requestData)
              .then(response => {
                this.fetchComments(eventMutable);
              })
              .catch(error => {
                console.error('Error attending event:', error);
              })
        },
        fetchComments(eventMutable){
          const url = `/event/${eventMutable.id}/comments/`;
          axios.get(url)
              .then(response => {
                this.comments = response.data;
              })
              .catch(error => {
                console.error('Error fetching comments:', error);
              })
        },
        deleteComment(eventMutable, comment){
          const url = `/event/${eventMutable.id}/comments/${comment.id}`;
          axios.delete(url)
              .then(response => {
                this.fetchComments(eventMutable)
              })
              .catch(error => {
                console.error('Error deleting comment:', error);
              })
        },
        checkIfEventEnded(eventMutable) {
          const currentDate = new Date();
          const eventEndDate = new Date(eventMutable.event_end);

          return eventEndDate < currentDate;
        },
        checkIfIsLoggedIn(){
          return (isRole(this.roleEnum.User , this.authUser) || isRole(this.roleEnum.Moderator , this.authUser) || isRole(this.roleEnum.Admin , this.authUser));
        },
        checkCapacityValue(event) {
          if (event.capacity_max == null){
            return "∞";
          }
          else {
            return event.capacity_max;
          }
        },
        hoverOver(value) {
          this.hoverValue = value;
        },
        hoverOut() {
          this.hoverValue = 0;
        },
        selectRating(value) {
          this.selectedRating = value;
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
    border-radius: 10px;
    width: 75%;
    margin-right: auto;
    box-sizing: border-box;
    border: 1px solid black;

}
.attend-button {
    margin: 10px;
    background-color: #05c76c;
    color: white;
}

.attend-button-disabled {
  margin: 10px;
  background-color: #949494;
  color: black;
}

.comment-box {
  position: relative;
  margin: 10px;
  background-color: white;
  color: black;
  padding: 10px;
}

.delete-button {
  position: absolute;
  top: 0;
  right: 0;
}


.comment-text {
  color: #5c5c5c;
  padding: 10px;
}

.users-comment-input-all {
    position: relative;
    padding: 10px;
    background-color: #bbbbbb;
    width: 100%; /* Adjust the value to set the width as a percentage of the page */
    margin-right: auto; /* Center the box horizontally */
    box-sizing: border-box; /* Include padding and border in the box's total width and height */

}

.users-comment-input {
  position: relative;
  margin: 10px;
  background-color: white;
  color: black;
  padding: 20px;
  border: 1px solid black;
  border-radius: 10px;

}
.post-button{
  padding: 10px;
}

.comment-input-box {
  padding: 10px;
  width: 100%;
}


.star-rating {
  cursor: pointer;
}

.fa-star {
  transition: color 0.5s;
}
</style>
