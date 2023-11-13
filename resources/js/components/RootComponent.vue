


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
    <div class="calendar-container">
      <div class="calendar-header">
        <v-btn icon @click="prevMonth">
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-btn icon @click="nextMonth">
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </div>
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
</template>

<script>
export default {
  data() {
    const today = new Date();
    return {
      today,
      currentMonth: today.getMonth(),
      currentYear: today.getFullYear(),
    };
  },
  mounted() {
    this.updateCalendar();
  },
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
              cell.classList.add('today');
            }

            // Highlight weekends
            if (j === 0 || j === 6) {
              cell.classList.add('weekend');
            }

            dayCounter++;
          }

          row.appendChild(cell);
        }

        calendarBody.appendChild(row);
      }
    },
  },
  nextMonth() {
    this.currentMonth++;
    if (this.currentMonth > 11) {
      this.currentMonth = 0;
      this.currentYear++;
    }
    this.updateCalendar();
  },
  prevMonth() {
    this.currentMonth--;
    if (this.currentMonth < 0) {
      this.currentMonth = 11;
      this.currentYear--;
    }
    this.updateCalendar();
  },
  watch: {
    currentMonth: 'updateCalendar',
    currentYear: 'updateCalendar',
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
