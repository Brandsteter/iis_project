<template>
  <FullCalendar :options='calendarOptions' />
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
              events: [],
              allDayText: 'Celý deň',
            },
            eventColors: ['#aaf6f0', '#f0aaf6', '#aaf6aa', '#f6aaf0', '#aaf0f6'],
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
        this.getUsersEvents();
    },
    methods: {
      getUsersEvents() {
        axios.get('/event/attended')
            .then(response => {
              this.usersEvents = response.data;

              // Update the events property in calendarOptions with the fetched events
              this.calendarOptions.events = this.usersEvents.map((event, index) => ({
                title: event.name,
                start: event.event_start,
                end: event.event_end,
                color: this.eventColors[index % this.eventColors.length]
            }));

            })
            .catch(error => {
              console.error('Error fetching events:', error);
            });
      },
      isRole,
      getAuthUser,
    },
    watch: {
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

