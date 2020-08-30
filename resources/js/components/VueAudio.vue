<template>
  <div :class="`${classes}__player`">
    <audio
      v-bind:id="playerId"
      :loop="innerLoop"
      ref="audiofile"
      :src="file"
      preload="auto"
      style="display:none;"
    ></audio>
    <!-- <a @click="stop()" title="Stop" class="icon-stop"></a> -->
    <div class="flex-align-center" style="width: 100%;">
      <div class="play_toggle">
        <vs-icon
          @click="pause()"
          class="play_btn_audio"
          :icon="paused ? 'icon-play-circle' : 'icon-pause' "
          icon-pack="feather"
        />
      </div>
      <!-- <a
        @click="pause()"
        title="Play"
        class="feather"
        :class="[ paused ? 'icon-play-circle' : 'icon-pause' ]"
      ></a>-->
      <div class="flex flex-column mt-4" style="width:100%;">
        <div
          v-on:click="setPosition"
          :class="`${classes}__playback-time-wrapper`"
          :title="currentTime"
        >
          <div v-bind:style="progressStyle" :class="`${classes}__playback-time-indicator`"></div>
        </div>
        <div class="time_audio mt-2 ml-3">
          <span :class="`${classes}__playback-time-current`">{{currentTime}}</span>
          <span class="mx-2">-</span>
          <span :class="`${classes}__playback-time-total`">{{duration}}</span>
        </div>
      </div>
    </div>

    <div class="flex-align-center">
      <vs-button
        radius
        size="35px"
        color="primary"
        class="Audio_download"
        style="margin-right:0px !important;"
        type="flat"
        @click="download()"
        icon="icon-download"
        icon-pack="feather"
      ></vs-button>
      <vs-button
        color="warning"
        type="flat"
        size="35px"
        style="margin-right:0px !important;"
        radius
        class="volume_audio ml-2"
        @click="mute()"
        icon-pack="feather"
        :icon="isMuted ? 'icon-volume-x': 'icon-volume-2' "
      ></vs-button>
      <vs-button
        color="danger"
        type="flat"
        size="35px"
        style="margin-right:0px !important;"
        radius
        class="ml-2 clearFile"
        @click="clearFile()"
        icon-pack="feather"
        icon="icon-x"
      ></vs-button>
    </div>

    <!-- <div :class="`${classes}__extern-wrapper`"> -->
    <!-- <a
          @click="changeLoop()"
          class="feather"
          :class="[ innerLoop ? 'icon-rotate-cw' : 'icon-rotate-cw']"
    ></a>-->

    <!-- <a
          class="feather"
          @click="mute()"
          :class="[isMuted ? 'icon-volume-x': 'icon-volume-2' ]"
          title="Mute"
    ></a>-->
    <!-- <a
          v-on:mouseover="toggleVolume()"
          class="feather volume-toggle icon-sliders"
          title="Volume"
    >-->
    <!-- <input
            orient="vertical"
            v-model.lazy="volumeValue"
            v-on:change="updateVolume()"
            v-show="hideVolumeSlider"
            type="range"
            min="0"
            max="100"
            class="volume-slider"
    />-->
    <!-- </a>
    <vs-slider :min="0" max="100" @change="updateVolume()" v-model.lazy="volumeValue" />-->
    <!-- </div> -->
  </div>
</template>

<script>
export const baseVolumeValue = 7.5;
let audio, uuid;

export const prefixCls = "vue-sound";

export const generateUUID = () => {
  return "xxxxxxxx-xxxx-4xxx".replace(/[xy]/g, function(c) {
    let v, r;
    r = (Math.random() * 16) | 0;
    v = c === "x" ? r : (r & 0x3) | 0x8;
    return v.toString(16);
  });
};

export const convertTimeHHMMSS = val => {
  let hhmmss = new Date(val * 1000).toISOString().substr(11, 8);
  return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
};

export default {
  name: "vue-audio",
  props: {
    file: {
      type: String,
      default: null
    },
    autoPlay: {
      type: Boolean,
      default: false
    },
    loop: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    duration: function() {
      return this.audio ? convertTimeHHMMSS(this.totalDuration) : "";
    },
    playerId: function() {
      return "player-" + this.uuid;
    },
    classes: function() {
      return prefixCls;
    }
  },
  data() {
    return {
      isMuted: false,
      loaded: false,
      playing: false,
      paused: true,
      progressStyle: "",
      currentTime: "00:00",
      uuid: "0",
      innerLoop: undefined,
      audio: undefined,
      totalDuration: 0,
      hideVolumeSlider: false,
      volumeValue: baseVolumeValue
    };
  },
  methods: {
    clearFile() {
      this.$emit("ClearFile");
    },
    setPosition: function name(e) {
      let tag = e.target;
      if (this.paused) return;

      if (e.target.tagName === "SPAN") {
        return;
      }
      const pos = tag.getBoundingClientRect();
      const seekPos = (e.clientX - pos.left) / pos.width;
      this.audio.currentTime = parseInt(this.audio.duration * seekPos);
    },
    updateVolume: function() {
      this.hideVolumeSlider = false;
      this.audio.volume = this.volumeValue / 100;
      if (this.volumeValue / 100 > 0) {
        this.isMuted = this.audio.muted = false;
      }

      if (this.volumeValue / 100 === 0) {
        this.isMuted = this.audio.muted = true;
      }
    },
    toggleVolume: function() {
      this.hideVolumeSlider = true;
    },
    stop: function() {
      this.playing = false;
      this.paused = true;
      this.audio.pause();
      this.audio.currentTime = 0;
    },
    play: function() {
      if (this.playing && !this.paused) return;
      this.paused = false;
      this.audio.play();
      this.playing = true;
    },
    pause: function() {
      this.paused = !this.paused;
      this.paused ? this.audio.pause() : this.audio.play();
    },
    changeLoop: function() {
      this.innerLoop = !this.innerLoop;
    },
    download: function() {
      this.stop();
      window.open(this.file, "download");
    },
    mute: function() {
      this.isMuted = !this.isMuted;
      this.audio.muted = this.isMuted;
      this.volumeValue = this.isMuted ? 0 : 75;
    },
    _handleLoaded: function() {
      if (this.audio.readyState >= 2) {
        if (this.autoPlay) this.play();

        this.loaded = true;
        this.totalDuration = parseInt(this.audio.duration);
      } else {
        throw new Error("Failed to load sound file");
      }
    },
    _handlePlayingUI: function(e) {
      let currTime = parseInt(this.audio.currentTime);
      let percentage = parseInt((currTime / this.totalDuration) * 100);
      this.progressStyle = `width:${percentage}%;`;
      this.currentTime = convertTimeHHMMSS(currTime);
    },
    _handlePlayPause: function(e) {
      if (e.type === "pause" && this.playing === false) {
        this.progressStyle = `width:0%;`;
        this.currentTime = "00:00";
        this.paused = true;
      }
    },
    init: function() {
      this.audio.addEventListener("timeupdate", this._handlePlayingUI);
      this.audio.addEventListener("loadeddata", this._handleLoaded);
      this.audio.addEventListener("pause", this._handlePlayPause);
      this.audio.addEventListener("play", this._handlePlayPause);
    },
    getAudio: function() {
      return this.$el.querySelectorAll("audio")[0];
    }
  },
  mounted: function() {
    this.uuid = generateUUID();
    this.audio = this.getAudio();
    this.innerLoop = this.loop;
    this.init();
  },
  beforeDestroy: function() {
    this.audio.removeEventListener("timeupdate", this._handlePlayingUI);
    this.audio.removeEventListener("loadeddata", this._handleLoaded);
    this.audio.removeEventListener("pause", this._handlePlayPause);
    this.audio.removeEventListener("play", this._handlePlayPause);
  }
};
</script>
