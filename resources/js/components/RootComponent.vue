


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
    <div>
      <h2>Simple Calendar</h2>
      <table>
        <thead>
        <tr>
          <th>Sun</th>
          <th>Mon</th>
          <th>Tue</th>
          <th>Wed</th>
          <th>Thu</th>
          <th>Fri</th>
          <th>Sat</th>
        </tr>
        </thead>
        <tbody id="calendarBody"></tbody>
      </table>
    </div>
    <!-- End Calendar -->
  </v-card>
  <!-- Calendar -->

  <div>

  </div>


</template>


<script>
  export default {
    data() {
      return {
        today: new Date(),
        currentMonth: null,
        currentYear: null
      };
    },
    mounted() {
      this.updateCalendar();
    },

    // async created() {
    //   // Call the async function in a lifecycle hook or a method
    //   await this.fetchAuthUser();
    // },
    methods: {
      updateCalendar() {
        const calendarBody = document.getElementById('calendarBody');
        calendarBody.innerHTML = '';

        const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1);
        const lastDayOfMonth = new Date(this.currentYear, this.currentMonth + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();

        let dayCounter = 1;

        for (let i = 0; i < 6; i++) {
          const row = document.createElement('tr');

          for (let j = 0; j < 7; j++) {
            const cell = document.createElement('td');

            if ((i === 0 && j < firstDayOfMonth.getDay()) || dayCounter > daysInMonth) {
              // Empty cells before the first day of the month or after the last day of the month
              cell.textContent = '';
            } else {
              cell.textContent = dayCounter;

              // Highlight today's date
              if (
                  this.currentYear === this.today.getFullYear() &&
                  this.currentMonth === this.today.getMonth() &&
                  dayCounter === this.today.getDate()
              ) {
                cell.style.backgroundColor = '#99ccff';
              }

              dayCounter++;
            }

            row.appendChild(cell);
          }

          calendarBody.appendChild(row);
        }
      },
      // Add navigation methods if needed
      // nextMonth() { /* ... */ },
      // prevMonth() { /* ... */ },
    },
    watch: {
      currentMonth: 'updateCalendar',
      currentYear: 'updateCalendar',
    },
  };

</script>

