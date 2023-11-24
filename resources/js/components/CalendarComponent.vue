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
              displayEventTime: false,
              eventClick: this.handleEventClick,
            },
            eventColors:['#91DEFF', '#88C0F3', '#8DA1DE', '#9780C0', '#9D5F98', '#B04C7B'],
          };
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


              this.calendarOptions.events = this.usersEvents.map((event, index) => {
                // Check if start and end times are specified
                const startTime = event.event_start_time ? `T${event.event_start_time}` : '';
                const endTime = event.event_end_time ? `T${event.event_end_time}` : 'T23:59:59';

                return {
                  title: event.name,
                  start: `${event.event_start}${startTime}`,
                  end: `${event.event_end}${endTime}`,
                  color: this.eventColors[index % this.eventColors.length],
                  allDay: false,
                  url: `/event/${event.id}/detail`
                };
              });


            })
            .catch(error => {
              console.error('Error fetching events:', error);
            });
      },
      handleEventClick(info) {
        const eventUrl = info.event.url;
        if (eventUrl) {
          window.location.href = eventUrl;
        }
      },
      isRole,
      getAuthUser,
    },
    watch: {
    },
};
</script>

<style>
.fc {
    color: #07abd5;
}
:root {
    --fc-small-font-size: .85em;
    --fc-page-bg-color: #ffffff;
    --fc-neutral-bg-color: rgba(208, 208, 208, 0.3);
    --fc-neutral-text-color: #808080;
    --fc-border-color: #696969;

    --fc-button-text-color: #fff;
    --fc-button-bg-color: #07abd5;
    --fc-button-border-color: #07abd5;
    --fc-button-hover-bg-color: #1e2b37;
    --fc-button-hover-border-color: #1a252f;
    --fc-button-active-bg-color: #1a252f;
    --fc-button-active-border-color: #151e27;

    --fc-event-bg-color: #3788d8;
    --fc-event-border-color: #3788d8;
    --fc-event-text-color: #2f2f2f;
    --fc-event-selected-overlay-color: rgba(0, 0, 0, 0.25);

    --fc-more-link-bg-color: #d0d0d0;
    --fc-more-link-text-color: inherit;

    --fc-event-resizer-thickness: 10px;
    --fc-event-resizer-dot-total-width: 8px;
    --fc-event-resizer-dot-border-width: 1px;

    --fc-non-business-color: rgba(144, 136, 136, 0.3);
    --fc-bg-event-color: rgb(143, 223, 130);
    --fc-bg-event-opacity: 0.3;
    --fc-highlight-color: rgba(188, 232, 241, 0.3);
    --fc-today-bg-color: rgba(255, 220, 40, 0.15);
    --fc-now-indicator-color: red;
}

</style>


