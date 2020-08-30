<template>
  <div>
    <vs-prompt title="Upload Image" id="upload_modal" @close="close" :active.sync="active_upload">
      <div class="row mx-0">
        <div class="col-lg-12 flex-center">
          <div class="cut">
            <vue-cropper
              v-if="option.img != ''"
              ref="cropper"
              :img="option.img"
              :output-size="option.size"
              :output-type="option.outputType"
              :info="true"
              :full="option.full"
              :fixed="fixed"
              :fixed-number="fixedNumber"
              :can-move="option.canMove"
              :can-move-box="option.canMoveBox"
              :fixed-box="option.fixedBox"
              :original="option.original"
              :auto-crop="option.autoCrop"
              :auto-crop-width="option.autoCropWidth"
              :auto-crop-height="option.autoCropHeight"
              :center-box="option.centerBox"
              :high="option.high"
              @img-load="imgLoad"
              @real-time="realTime"
              mode="cover"
            ></vue-cropper>
            <div class="empty_image" v-if="option.img === ''">
              <input
                type="file"
                id="uploads"
                ref="fileInput"
                style="position:absolute;"
                accept="image/png, image/jpeg, image/gif, image/jpg"
                @change="uploadImg($event, 1)"
              />
              <span id="upload_text">Upload</span>
              <button @click="trigger" id="upload-Btn">
                <i class="feather icon-upload mx-0"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 flex-center mt-4 mb-1 upload_btns">
        <vs-button
          color="primary"
          radius
          type="flat"
          icon-pack="feather"
          @click="changeScale(1)"
          icon="icon-zoom-in"
        ></vs-button>
        <vs-button
          color="primary"
          radius
          type="flat"
          @click="changeScale(-1)"
          icon-pack="feather"
          icon="icon-zoom-out"
        ></vs-button>
        <vs-button
          color="primary"
          radius
          type="flat"
          icon-pack="feather"
          @click="rotateLeft"
          icon="icon-rotate-ccw"
        ></vs-button>
        <vs-button
          color="primary"
          radius
          type="flat"
          icon-pack="feather"
          @click="rotateRight"
          icon="icon-rotate-cw"
        ></vs-button>
        <vs-button
          color="danger"
          radius
          type="flat"
          @click="clearImage"
          icon-pack="feather"
          icon="icon-trash-2"
        ></vs-button>
        <vs-button
          color="warning"
          radius
          type="flat"
          @click="save()"
          icon-pack="feather"
          icon="icon-save"
        ></vs-button>
      </div>
      <!-- <div class="test-button">
                <label class="btn" for="uploads">upload</label>

                <button @click="startCrop" v-if="!crap" class="btn">
                    start
                </button>
                <button @click="stopCrop" v-else class="btn">stop</button>
                <button @click="clearCrop" class="btn">clear</button>
                <button @click="refreshCrop" class="btn">refresh</button>
                <button class="btn">+</button>
                <button @click="changeScale(-1)" class="btn">-</button>
                <button class="btn">rotateLeft</button>
                <button class="btn">rotateRight</button>
                <button class="btn">
                    Save
                </button>
      </div>-->
    </vs-prompt>
  </div>
</template>

<script>
export default {
  props: {
    type: null,
    active: false
  },
  data() {
    return {
      crap: false,
      previews: {},
      active_upload: false,
      option: {
        img: "",
        size: 1,
        full: true,
        outputType: "png",
        canMove: true,
        fixedBox: false,
        original: false,
        canMoveBox: true,
        autoCrop: true,
        autoCropWidth: 512,
        autoCropHeight: 512,
        centerBox: false,
        high: true
      },
      show: true,
      fixed: true,
      fixedNumber: [1, 1]
    };
  },
  watch: {
    active(newVal, oldVal) {
      this.active_upload = newVal;
    }
  },
  components: {},
  methods: {
    close() {
      this.$emit("close");
    },
    clearImage() {
      this.option.img = "";
      this.previews = {};
    },
    trigger() {
      this.$refs.fileInput.click();
    },
    realTime(data) {
      this.previews = data;
    },
    startCrop() {
      // start
      this.crap = true;
      this.$refs.cropper.startCrop();
    },
    stopCrop() {
      //  stop
      this.crap = false;
      this.$refs.cropper.stopCrop();
    },
    clearCrop() {
      // clear
      this.$refs.cropper.clearCrop();
    },
    refreshCrop() {
      // clear
      this.$refs.cropper.refresh();
    },
    changeScale(num) {
      num = num || 1;
      this.$refs.cropper.changeScale(num);
    },
    rotateLeft() {
      this.$refs.cropper.rotateLeft();
    },
    rotateRight() {
      this.$refs.cropper.rotateRight();
    },
    save() {
      this.$refs.cropper.getCropData(data => {
        var file = {};
        var data = data;
        var url = data;
        if (this.type) {
          file.type = this.type;
        }
        file.data = data;
        file.url = url;
        // this.active = false;
        this.$emit("saved_file", file);
      });
    },
    uploadImg(e, num) {
      if (num > 1) {
        alert("Please, upload only one image!");
        return false;
      }
      var file = e.target.files[0];
      if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
        alert("The file uploaded need to be - gif,jpeg,jpg,png,bmp");
        return false;
      }
      var reader = new FileReader();
      reader.onload = e => {
        let data;
        if (typeof e.target.result === "object") {
          data = window.URL.createObjectURL(new Blob([e.target.result]));
        } else {
          data = e.target.result;
        }
        if (num === 1) {
          this.option.img = data;
        }
      };
      reader.readAsArrayBuffer(file);
    },
    imgLoad(msg) {
      // ;
    }
  },
  mounted: function() {}
};
</script>
<style>
.empty_image:hover > #upload_text {
  color: rgb(var(--vs-primary));
}
span#upload_text {
  font-weight: 600;
  font-size: 14px;
  color: rgb(var(--vs-dark2));
  margin-bottom: 50px;
}
.con-title-after > h3 {
  animation: open 0.3s;
}
#uploads {
  cursor: pointer;
  opacity: 0;
  top: 0px;
  width: 100%;
  height: 100%;
}
#upload_modal > .vs-dialog > footer {
  display: none !important;
}
#upload_modal > .vs-dialog {
  max-width: 540px !important;
}
.empty_image:hover > #upload-Btn {
  background: rgb(var(--vs-primary));
  color: rgb(var(--vs-light));
}
button#upload-Btn {
  transition: 0.3s all;
  border: 0px;
  width: 102%;
  margin: 0px -2px -2px -2px;
  background: rgb(var(--vs-gray2));
  color: rgb(var(--vs-dark2));
  height: 40px;
  font-size: 20px;
}
.empty_image {
  animation: rebound 0.3s;
  overflow: hidden;
  transition: 0.3s all;
  width: 200px;
  height: 200px;
  background: rgb(var(--vs-theme)) !important;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  flex-direction: column;
  border-radius: 0.5rem;
  border: 0.07rem dashed rgb(var(--vs-dark), 0.2);
}

.empty_image:hover > i {
  color: rgb(var(--vs-primary));
}
.empty_image:hover {
  border-color: rgb(var(--vs-primary)) !important;
  box-shadow: 0 5px 10px 0 rgba(var(--vs-primary), 0.2);
}
.cut {
  background: linear-gradient(
    to right,
    rgba(var(--vs-primary), 0.2) 0%,
    rgba(var(--vs-light_primary), 0.2) 100%
  ) !important;
  border-radius: 0.3rem;
  width: 535px;
  height: 380px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 0.3rem;
  overflow: hidden;
}
</style>
