<template>
    <p class="header-text-format"><b>{{place.name}}</b></p>
    <div class="inline-components">
      <v-icon color="dimgrey">mdi-map-marker</v-icon>
      <p class="text-format"><b>Adress: {{place.address}}</b></p>
    </div>

    <hr>
      <div class="description-box">
          <p class="text-description">{{ place.description }}</p>
      </div>
    <hr>

</template>

<script>
import {isRole, getAuthUser} from "../../app";
import {RoleEnum} from "../../enums/RoleEnum";

export default {
    props: {
        place: {
            required: true,
            type: Object,
        }
    },
  data() {
    return {

    };
  },
  created: async function(){
    this.authUser = await this.getAuthUser()
  },
  mounted() {
  },
  methods: {
    isRole,
    getAuthUser,
  }
};
</script>

<style>

.header-text-format {
    font-size: 40px;
    margin-bottom: 10px;
    color: #07abd5;
}

.text-format {
  font-size: 20px;
  margin-left: 10px;
  color: #5b5b5b;
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

.inline-components {
  margin-top: -10px;
  display: inline-flex;
  align-items: center;
}

</style>
