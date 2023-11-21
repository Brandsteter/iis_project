<template>



<!--    <div class="calendar-container" >-->
<!--        <v-toolbar height="50px" flat class="custom-toolbar">-->
<!--            <v-btn outlined class="mr-3" color="white" elevation="2" @click="setToday">-->
<!--                Today-->
<!--            </v-btn>-->
<!--            <v-btn icon @click="prevMonth">-->
<!--                <v-icon color="white">mdi-chevron-left</v-icon>-->
<!--            </v-btn>-->
<!--            <v-btn icon @click="nextMonth">-->
<!--                <v-icon color="white">mdi-chevron-right</v-icon>-->
<!--            </v-btn>-->
<!--            <h3 >-->
<!--                {{ monthName }} {{ currentYear }}-->
<!--            </h3>-->
<!--        </v-toolbar>-->

<!--        <table>-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th>Mon</th>-->
<!--                <th>Tue</th>-->
<!--                <th>Wed</th>-->
<!--                <th>Thu</th>-->
<!--                <th>Fri</th>-->
<!--                <th>Sat</th>-->
<!--                <th>Sun</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody id="calendarBody"></tbody>-->
<!--        </table>-->

<!--    </div>-->

      <FullCalendar :options='calendarOptions' />
      <div v-if="isRole(roleEnum.User , authUser)">
        <h1>My events</h1>
        <div>
          <table>
            <thead>
            <tr>
              <th>Name</th>
              <th>Start date</th>
              <th>End date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(event, index) in usersEvents" :style="{ background: index % 2 === 0 ? 'white' : 'lightgrey' }">
              <td>{{ event.name }}</td>
              <td>{{ event.event_start}}</td>
              <td>{{ event.event_end}}</td>
            </tr>
            </tbody>
          </table>
      </div>
    </div>

</template>

<script>
import {isRole, getAuthUser} from "../app";
import {RoleEnum} from "../enums/RoleEnum";
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'

export default {
    components: {
      FullCalendar,
    },
    data() {
        const today = new Date();
        return {
            today,
            currentMonth: today.getMonth(),
            currentYear: today.getFullYear(),
            usersEvents: [],
            authUser: null,
            roleEnum: RoleEnum,
            calendarOptions: {
              plugins: [ dayGridPlugin ],
              initialView: 'dayGridMonth',
              events: [
                {
                  title: "vianocne trhy",
                  start: "2023-11-19T12:00:00",
                  end: "2023-11-20T14:00:00",
                  color: "#aaf6f0",
                }
              ],
              allDayText: 'Celý deň',
            },
          };
    },
    computed: {
        monthName() {
            const monthNames = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ];
            return monthNames[this.currentMonth];
        },
    },
    created: async function(){
      this.authUser = await this.getAuthUser()
    },
    mounted() {
        this.updateCalendar();
        this.getUsersEvents();
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
                    cell.height="100";
                    cell.width="100";
                    cell.classList.add('today');


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
                            cell.bgColor="aaf6f0";
                        }

                        // Highlight weekends
                        if (j === 0 || j === 6) {
                            cell.classList.add('weekend');
                        }
                        cell.style.border = '1px solid #2f2f2f';

                        dayCounter++;
                    }

                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);
            }
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
        setToday() {
            this.currentMonth = this.today.getMonth();
            this.currentYear = this.today.getFullYear();
            this.updateCalendar();
        },
        getUsersEvents(){
          axios.get('/event/attended')
              .then(response => {
                this.usersEvents = response.data;
                console.log(this.usersEvents);
              })
              .catch(error => {
                console.error('Error fetching events:', error);
              })
        },
        isRole,
        getAuthUser,


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
    width: max-content;
}

table {
    width: 100%;
    height: 100%;

}

th, td {
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

td {
    position: relative;
    cursor: pointer;
    height: 40px; /* Adjust the height as needed */
    width: 40px; /* Adjust the width as needed */
    transition: background-color 0.3s ease; /* Smooth transition for color change */
}

td:hover {
    background-color: #aaf6f0; /* Change the color to your desired hover color */
}


th {
    background-color: #2f2f2f;
    color: #ffffff;
}


.custom-toolbar {
    width: 100%;
    background-color: #2f2f2f;
}

td:hover {
    background-color: #aaf6f0;
}

h3 {
    margin: 10px;
    color: #e0e0e0;
}


.weekend {
    color: #ff5722;
}



/* Your custom styles here */
</style>

