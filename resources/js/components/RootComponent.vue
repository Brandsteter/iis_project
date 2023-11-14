


<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer  class="bg-light-blue-accent-1" theme="dark" permanent>

        <v-list>
          <v-list-item
              prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
              title="Guest"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item prepend-icon="mdi-folder" title="Events" value="myfiles"></v-list-item>
          <v-list-item prepend-icon="mdi-account-multiple" title="Event Categories" value="shared"></v-list-item>
        </v-list>
        <template v-slot:append>
          <div class="pa-2">
            <a href="auth/login"><v-btn block> Login </v-btn></a>
            <br>
            <a href="auth/register"><v-btn block> Register </v-btn></a>
          </div>
        </template>
      </v-navigation-drawer>
    </v-layout>

    <!-- End Calendar -->

  </v-card>
</template>

<script>
export default {
  data() {
    return {
    };
  },
  methods: {

  },
};
</script>

<style scoped>
.calendar-container {
  text-align: center;
  margin-top: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: center;
  font-size: 14px;
}

th {
  background-color: #4285f4;
  color: #ffffff;
}

td {
  position: relative;
  cursor: pointer;
}

td:hover {
  background-color: #f1f1f1;
}

.today {
  background-color: #e0e0e0;
}

.weekend {
  color: #ff5722;
}

/* Your custom styles here */
</style>
