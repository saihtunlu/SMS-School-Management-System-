<template>
  <div class="timetable vs-table--tbody" style="overflow-y:hidden;">
    <div class="time-ground" :style="TImeListSty2">
      <ul class="flex fullHeight">
        <li
          v-for="(time,i) in pageTimeGround"
          @click="clickTime(time,i)"
          :class="{borderRight: i== pageTimeGround.length-2, display_none: i== pageTimeGround.length-1}"
          :key="i"
        >
          <span>{{$moment(time, 'HH:mm').format('hh:mm a')}}</span>
          <p :style="timeListSty"></p>
        </li>
      </ul>
    </div>
    <div class="task-ground">
      <div v-for="(week, index) in weekGround" :style="TasKListSty2" :key="index" class="task-list">
        <p>{{week}}</p>
        <ul :style="taskListSty">
          <li
            class="task-list-item"
            v-for="(detail,n) in taskDetail[index]"
            :key="n"
            :style="detail.styleObj"
            @click="showDetail(detail, week)"
          >
            <p
              style="width:100% !important;"
            >{{$moment(detail.from, 'HH:mm').format('hh:mm a')}} - {{$moment(detail.to, 'HH:mm').format('hh:mm a')}}</p>
            <span class="text-small flex-align-center" style="font-size: 14px;color: #fff;">
              <vs-avatar size="20px" :src="detail.subject.teacher.profile_image" />
              {{detail.subject.teacher.first_name+' '+detail.subject.teacher.last_name}} |
              {{detail.title}}
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>
.timetable {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
}
.time-ground {
  display: block;
  position: absolute;
  left: 102px;
  top: 0;
  /* width: 100%; */
  height: 100% !important;
}
.time-ground ul li {
  font-size: 1rem;
  float: left;
  min-width: 100px;
  height: 100%;
  border-left: 1px solid rgb(var(--vs-dark), 0.2);
  border-top: 1px solid rgb(var(--vs-dark), 0.2);
  padding-top: 5px;
  padding-left: 3px;
}
/* .time-ground ul li span {
  float: right;
  margin-right: 0.5rem;
} */
.time-ground ul li p {
  position: absolute;
  left: 0;

  height: 1px;
  background-color: #eaeaea;
}
.task-ground {
  width: 100%;
  padding-top: 40px;
  border-bottom: 1px solid rgb(var(--vs-dark), 0.2);
}
.task-list {
  display: flex;
  height: 70px;
  width: 100%;
  box-sizing: border-box;
  border: 1px solid rgb(var(--vs-dark), 0.2);
  border-right: 0px !important;
  border-bottom: 0px !important;
}
.task-list p {
  text-align: center;
  font-size: 1rem;
  display: flex;
  margin-bottom: 0px !important;
  padding: 1rem;
  width: 102px;
  align-items: center;
}
.task-list-item {
  animation: 0.3s open ease-in-out;
  position: absolute;
  width: 14%;
  height: 60px;
  cursor: pointer;
  margin-top: 5px;
  border-radius: 0.5rem;
  padding: 1rem;
}
.task-list-item p {
  text-align: left;
  padding: 0;
  font-size: 0.8rem;
  color: #edf2f6;
}
li.display_none {
  display: none !important;
}
.task-list-item h3 {
  color: #e0e7e9;
  margin: 1rem 0 0 1rem;
}
.borderRight {
  border-right: 1px solid rgb(var(--vs-dark), 0.2);
}
.fullHeight {
  height: 100%;
}
</style>

<script>
export default {
  name: "Timetable",
  props: {
    timeGround: {
      type: Array,
      default: []
    },
    weekGround: {
      type: Array,
      default: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ]
    },
    taskDetail: {
      type: Array,
      default: []
    }
  },
  watch: {
    timeGround(value) {
      this.pageTimeGround = this.initTimeGround(value);
    },
    taskDetail: function(newVal, oldVal) {
      this.initTable();
    }
  },
  data() {
    return {
      pageTimeGround: [],
      showModal: false,
      taskListSty: {
        width: "900px"
      },
      TasKListSty2: {
        width: ""
      },
      TImeListSty2: {
        width: ""
      },
      timeListSty: {
        height: "100%"
      }
    };
  },
  created() {
    this.initTable();
  },

  mounted() {
    this.taskListSty.width = (this.pageTimeGround.length - 1) * 100 + "px";
    this.timeListSty.height = this.weekGround.length * 14 + "%";
    this.TasKListSty2.width = this.pageTimeGround.length * 100 + 2 + "px";
    this.TImeListSty2.width = this.pageTimeGround.length * 100 - 100 + "px";
  },
  methods: {
    initTable() {
      this.pageTimeGround = this.initTimeGround(this.timeGround);

      let maxTime = this.pageTimeGround[this.pageTimeGround.length - 1];
      let minTime = this.pageTimeGround[0];
      let maxMin = maxTime.split(":")[0] * 60 + maxTime.split(":")[1] * 1;
      let minMin = minTime.split(":")[0] * 60 + minTime.split(":")[1] * 1;

      for (let i = 0; i < this.taskDetail.length; i++) {
        for (let j = 0; j < this.taskDetail[i].length; j++) {
          let startMin =
            this.taskDetail[i][j].from.split(":")[0] * 60 +
            this.taskDetail[i][j].from.split(":")[1] * 1;
          let endMin =
            this.taskDetail[i][j].to.split(":")[0] * 60 +
            this.taskDetail[i][j].to.split(":")[1] * 1;
          if (startMin < minMin || endMin > maxMin) {
            this.taskDetail[i].splice(j, 1);
            j--;
            continue;
          }
          let difMin = endMin - startMin;
          this.taskDetail[i][j].styleObj = {
            width: (difMin * 100) / 60 + "px",
            left:
              ((startMin -
                (this.pageTimeGround[0].split(":")[0] * 60 +
                  this.pageTimeGround[0].split(":")[1] * 1)) *
                100) /
                60 +
              102 +
              "px",
            backgroundColor: this.taskDetail[i][j].color,
            boxShadow: "0px 10px 25px -10px " + this.taskDetail[i][j].color
          };
        }
      }
    },
    showDetail(obj, week) {
      obj.week = week;
      console.log("obj: ", obj);

      this.$emit("clickDetail", obj);
    },
    clickTime(data, i) {
      this.$emit("clickTime", data);
    },
    initTimeGround(value) {
      if (value && value.length == 2) {
        let startTime = value[0].split(":")[0] * 1;
        let endTime = value[1].split(":")[0] * 1;
        value = [];
        for (let i = startTime; i <= endTime; i++) {
          let hour = i < 10 ? "0" + i : "" + i;
          value.push(hour + ":00");
        }
      } else {
        value = [
          "08:00",
          "09:00",
          "10:00",
          "11:00",
          "12:00",
          "13:00",
          "14:00",
          "15:00",
          "16:00",
          "17:00",
          "18:00"
        ];
      }
      return value;
    }
  }
};
</script>
