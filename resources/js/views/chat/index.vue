<template>
  <div class="row">
    <div class="col-12 px-0 chat_content">
      <vs-sidebar
        id="Chat_contacts"
        color="primary"
        class="sidebarx"
        spacer
        :click-not-close="true"
        hidden-background
        v-model="active"
      >
        <div class="header-sidebar flex-align-center" style=" width: 100%;" slot="header">
          <div class="dot" style="background:rgb(var(--vs-success))" v-if="auth.status == 'online'"></div>
          <div
            class="dot"
            style="background:rgb(var(--vs-warning))"
            v-if="auth.status == 'offline'"
          ></div>
          <vs-avatar class="chat_avatar" :src="auth.profile_image" size="38px" />
          <vs-input
            icon-no-border
            class="chat_search"
            id="contact_search"
            icon="icon-search"
            icon-pack="feather"
            v-model="query"
          />

          <vs-icon
            v-if="mobile"
            class="custom_icon mx-3"
            icon="icon-menu"
            @click="$emit('show_sidebar')"
            icon-pack="feather"
            size="21px"
            color="dark"
          />
        </div>

        <div v-if="!no_data" class="contact_list">
          <h4 class="text-bold mx-2 primary">Recent Chats</h4>
          <ul class="mb-base">
            <li
              v-for="(contact,n) in recentChat"
              :key="n"
              @click="startConversation(contact)"
              :class="{ selected: contact.id == selected_id }"
            >
              <div class="contact_person pointer">
                <div class="flex-align-center">
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-success))"
                    v-if="contact.status == 'online'"
                  ></div>
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-warning))"
                    v-if="contact.status == 'offline'"
                  ></div>
                  <vs-avatar size="38px" class="chat_avatar" :src="contact.profile_image" />
                  <div class="contact__info flex flex-column truncate ml-2">
                    <h5 class="text-bold">{{contact.name}}</h5>
                    <img v-if="contact.typing" src="/storage/image/dots.gif" style="width:30px" />
                    <span class="truncate" v-if="!contact.typing">{{contact.recent_message}}</span>
                  </div>
                </div>

                <vs-chip
                  v-if="contact.unread!==0"
                  class="sidebar_badge"
                  color="danger"
                >{{contact.unread}}</vs-chip>
              </div>
              <vs-divider class="my-0" color="rgb(var(--vs-dark),0.1)" />
            </li>
          </ul>

          <h4 class="text-bold mx-2 primary">Contacts</h4>
          <ul>
            <li
              v-for="(contact,n) in otherContacts"
              @click="startConversation(contact)"
              :class="{ selected: contact.id == selected_id }"
              :key="n"
            >
              <div class="contact_person pointer">
                <div class="flex-align-center">
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-success))"
                    v-if="contact.status == 'online'"
                  ></div>
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-warning))"
                    v-if="contact.status == 'offline'"
                  ></div>
                  <vs-avatar size="38px" class="chat_avatar" :src="contact.profile_image" />
                  <div class="contact__info flex flex-column truncate ml-2">
                    <h5 class="text-bold">{{contact.name}}</h5>
                    <img v-if="contact.typing" src="/storage/image/dots.gif" style="width:30px" />
                    <span class="truncate" v-if="!contact.typing">{{contact.email}}</span>
                  </div>
                </div>
                <vs-chip
                  v-if="contact.unread!==0"
                  class="sidebar_badge"
                  color="danger"
                >{{contact.unread}}</vs-chip>
              </div>
              <vs-divider class="my-0" color="rgb(var(--vs-dark),0.1)" />
            </li>
          </ul>
        </div>
        <div v-if="no_data" class="no_data">
          <div class="flex-align-center flex-column">
            <img src="/storage/image/empty.png" style="width:120px;" alt />
            <p class="text-sm text-bold mt-3">No Data available!</p>
          </div>
        </div>
      </vs-sidebar>
      <slide-y-down-transition>
        <div class="message_feed" v-if="!hide_message" :class="{FullWidth: !active}">
          <div class="chat_header" v-if="selectedContact">
            <div class="flex-align-center">
              <!-- <vs-button
                type="flat"
                color="dark"
                icon="icon-arrow-left"
                icon-pack="feather"
                v-if="mobile"
                class="back_to_mails ml-3"
                @click="active=!active"
              />-->
              <vs-icon
                color="primary"
                size="18px"
                icon="icon-arrow-left"
                icon-pack="feather"
                v-if="mobile"
                @click="active=!active;hide_message=!hide_message"
                class="back_to_mails ml-3 pointer custom_icon"
              />
              <div
                class="dot"
                style="background:rgb(var(--vs-success))"
                v-if="selectedContact.status == 'online'"
              ></div>
              <div
                class="dot"
                style="background:rgb(var(--vs-warning))"
                v-if="selectedContact.status == 'offline'"
              ></div>
              <vs-avatar size="38px" class="chat_avatar" :src="selectedContact.profile_image" />
              <div class="contact__info flex-start truncate ml-2 flex-column">
                <h5 class="text-bold mb-0">{{selectedContact.name}}</h5>
                <span
                  class="small-text"
                  style="color:rgb(var(--vs-dark),0.5)"
                >{{selectedContact.status}}</span>
              </div>
            </div>

            <!-- <vs-dropdown vs-trigger-click class="contact_details">
              <vs-icon icon="icon-more-vertical" size="18px" icon-pack="feather" />
              <vs-dropdown-menu class="nav_dropdown" id="chat_dropdown">
                <vs-dropdown-item class="nav_dropdown_item">
                  <vs-icon icon-pack="feather" icon="icon-user" />option 1
                </vs-dropdown-item>
                <vs-dropdown-item class="nav_dropdown_item">
                  <vs-icon icon-pack="feather" icon="icon-user" />option 2
                </vs-dropdown-item>
              </vs-dropdown-menu>
            </vs-dropdown>-->
          </div>
          <div
            @click="show_preview=false;show_upload_btns=false;show_emoji=false"
            class="chat_body"
            :class="{fullHeight: !selectedContact,show_preview:show_preview}"
            ref="feed"
          >
            <div class="before_chat flex-align-center" v-if="!selectedContact && !getting_message">
              <div class="flex-column flex-center">
                <vs-icon size="5rem" id="chat_logo" icon="icon-message-square" icon-pack="feather" />
                <h4 @click="active=!active" class="text-bold logo_text_chat">Start Conversation</h4>
              </div>
            </div>
            <div class="before_chat flex-align-center getting_message" v-if="getting_message">
              <div class="flex-column flex-center">
                <img src="/storage/image/dots.gif" style="width:100px" />
              </div>
            </div>
            <ul class="message_body" v-if="selectedContact && !getting_message">
              <li class="getMoreMessage" v-if="messages.length > 9">
                <vs-divider color="rgb(var(--vs-dark),0.2)">
                  <span @click="GetMoreMessage()" id="loadMore" class="loadmore_btn pointer">
                    <vs-icon v-if="loading" icon="el-icon-loading" size="16px" icon-pack="el-icon" />
                    <span v-if="!loading">Load More</span>
                  </span>
                </vs-divider>
              </li>
              <li
                class="flex-align-center"
                style="width:100%;justify-content: center; position:sticky;top:1.5rem;"
                v-if="loaded && messages.length > 9"
              >
                <vs-button
                  @click="loaded=!loaded"
                  href="#loadMore"
                  size="35px"
                  color="primary"
                  id="scroll_to_top_chat"
                  transparent
                  type="flat"
                  radius
                  icon="icon-chevron-up"
                  icon-pack="feather"
                ></vs-button>
              </li>
              <li
                v-for="(message,index) in messages"
                class="message"
                :class="{removed_: message.removed,sent:message.to == selectedContact.id,received:message.to !== selectedContact.id}"
                :key="message.id"
              >
                <div class="flex-align-center" v-if="message.to !== selectedContact.id">
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-success))"
                    v-if="selectedContact.status == 'online'"
                  ></div>
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-warning))"
                    v-if="selectedContact.status == 'offline'"
                  ></div>
                  <vs-avatar size="38px" class="chat_avatar" :src="selectedContact.profile_image" />
                </div>
                <div
                  class="message_group"
                  @click="show_option=index"
                  :class="{removed: message.removed}"
                >
                  <vs-chip color="gray" transparent v-if="message.removed">Removed</vs-chip>
                  <div
                    class="message_option"
                    v-if="message.removable && !message.removed && show_option===index"
                  >
                    <div class="option_content openicon">
                      <vs-icon
                        class="reomve_btn"
                        color="gray"
                        @click="removeMessage(message.id,index)"
                        icon="icon-trash"
                        icon-pack="feather"
                      ></vs-icon>
                    </div>
                  </div>
                  <div
                    class="image_chat mb-3 mx-0 row"
                    style="width: 65%;"
                    v-if="message.images && !message.removed"
                  >
                    <div
                      class="col-4 pl-0"
                      style="padding-right: 5px !important;"
                      v-for="(image,n) in message.images"
                      :key="n"
                    >
                      <el-image
                        class="chat_images openicon"
                        style="width: 100%;height: 120px;"
                        :preview-src-list="[ message.images[n].file_name]"
                        :src="image.file_name"
                      >
                        <div slot="placeholder" class="image-slot">
                          <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
                        </div>
                        <div slot="error" class="image-slot">
                          <i class="feather icon-image"></i>
                        </div>
                      </el-image>
                    </div>
                  </div>
                  <div class="files" v-if="message.files && !message.removed">
                    <div class="audio_message" v-if="message.files.file_type==='audio'">
                      <vue-audio
                        class="chat_audio_ flex-align-center"
                        :file="message.files.file_path"
                      />
                      <div class="file_details">
                        <p class="mt-3 mb-2 text-small truncate">
                          <span class="text-bold">Name :</span>
                          {{message.files.file_name}}
                        </p>
                        <p class="mt-0 mb-0 text-small">
                          <span class="text-bold">Size :</span>
                          {{message.files.size}} MB
                        </p>
                      </div>
                    </div>
                    <div class="video_message" v-if="message.files.file_type==='video'">
                      <Media
                        class="video_preview"
                        :kind="'video'"
                        :controls="true"
                        :src="[message.files.file_path]"
                      ></Media>
                      <div class="file_details">
                        <p class="mt-3 mb-2 truncate text-small">
                          <span class="text-bold">Name :</span>
                          {{message.files.file_name}}
                        </p>
                        <p class="mt-0 mb-0 text-small">
                          <span class="text-bold">Size :</span>
                          {{message.files.size}} MB
                        </p>
                      </div>
                    </div>
                    <div
                      class="other_message"
                      v-if="message.files.file_type!=='video' && message.files.file_type!=='audio' && message.files.file_type!=='image'"
                    >
                      <a :href="message.files.file_path" target="_blank">
                        <img
                          class="other_file_preview"
                          :src="`/storage/image/${message.files.file_type}.png`"
                          alt
                        />
                      </a>
                      <div class="file_details">
                        <p class="mt-3 mb-2 truncate text-small">
                          <span class="text-bold">Name :</span>
                          {{message.files.file_name}}
                        </p>
                        <p class="mt-0 mb-0 text-small">
                          <span class="text-bold">Size :</span>
                          {{message.files.size}} MB
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="text" v-if="message.text && !message.removed">
                    <p class="text-md message_body">{{ message.text }}</p>
                  </div>
                  <span class="text-small chat_time">{{ message.created_at | moment('calendar') }}</span>

                  <div class="chat_status">
                    <vs-avatar
                      class="read_avatar"
                      size="14px"
                      :src="selectedContact.profile_image"
                      v-if="messages.length!==0 && ReadIndex && message.id === messages[ReadIndex].id && message.read"
                    />
                    <vs-icon
                      :icon="message.delivered ? 'el-icon-success' : 'el-icon-circle-check' "
                      icon-pack="el-icon"
                      class="delivered_status"
                      size="14px"
                      v-if="message && message.to === selectedContact.id && !message.read"
                    />
                  </div>
                </div>
                <div class="flex-align-center" v-if="message.to === selectedContact.id">
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-success))"
                    v-if="auth.status == 'online'"
                  ></div>
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-warning))"
                    v-if="auth.status == 'offline'"
                  ></div>
                  <vs-avatar size="38px" class="chat_avatar" :src="auth.profile_image" />
                </div>
              </li>
              <li class="message received openicon" v-if="selectedContact.typing">
                <div class="flex-align-center" v-if="message.to !== selectedContact.id">
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-success))"
                    v-if="selectedContact.status == 'online'"
                  ></div>
                  <div
                    class="dot"
                    style="background:rgb(var(--vs-warning))"
                    v-if="selectedContact.status == 'offline'"
                  ></div>
                  <vs-avatar size="38px" class="chat_avatar" :src="selectedContact.profile_image" />
                </div>
                <div class="message_group">
                  <div class="text">
                    <p class="text-md message_body">
                      <img src="/storage/image/dots.gif" style="width:30px" />
                    </p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <!-- Preview -->
          <div class="file_preview_div open" :class="{block : show_emoji}" v-if="show_preview">
            <div class="emoji" v-if="show_emoji">
              <vs-tabs>
                <vs-tab :label="SimleEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in SimleEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                    <span
                      class="emoji pointer"
                      v-for="emoji in peopleEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="foodEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="(emoji,n) in foodEmoji"
                      :key="n"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="objectEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in objectEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="sybomlsEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in sybomlsEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="activitiesEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in activitiesEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="animalsEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in animalsEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <vs-tab :label="travelEmoji[0].unicode">
                  <div class="con-tab-ejemplo">
                    <span
                      class="emoji pointer"
                      v-for="emoji in travelEmoji"
                      :key="emoji.unicode"
                      @click="setEmoji(emoji.unicode)"
                    >{{emoji.unicode}}</span>
                  </div>
                </vs-tab>
                <!-- <vs-tab :label="flagsEmoji[0].unicode">
                <div class="con-tab-ejemplo">
                  <span
                    class="emoji pointer"
                    v-for="emoji in flagsEmoji"
                    :key="emoji.unicode"
                    @click="setEmoji(emoji.unicode)"
                  >{{emoji.unicode}}</span>
                </div>
                </vs-tab>-->
              </vs-tabs>
            </div>
            <div class="flex-between-center" v-if="show_upload_btns">
              <div class="text-center flex-align-center flex-column mr-3 mr-sm-4">
                <vs-icon
                  icon="icon-image"
                  icon-pack="feather"
                  @click="$refs.InputFile.click()"
                  class="upload_file_btns pointer"
                />
                <span class="mt-2">Images</span>
              </div>

              <input
                type="file"
                @change="onFileChange"
                name="chat_file"
                multiple
                ref="InputFile"
                hidden
              />
              <input type="file" @change="onFileChange2" name="chat_file2" ref="InputFile2" hidden />

              <div class="text-center flex-align-center flex-column mr-3">
                <vs-icon
                  @click="clickFile2('audio')"
                  icon="icon-music"
                  icon-pack="feather"
                  class="upload_file_btns pointer"
                />
                <span class="mt-2">Audio</span>
              </div>
              <div class="text-center flex-align-center flex-column mr-3">
                <vs-icon
                  @click="clickFile2('video')"
                  icon="icon-film"
                  icon-pack="feather"
                  class="upload_file_btns pointer"
                />
                <span class="mt-2">Video</span>
              </div>
              <div class="text-center flex-align-center flex-column mr-3">
                <vs-icon
                  @click="clickFile2('Other')"
                  icon="icon-file-plus"
                  icon-pack="feather"
                  class="upload_file_btns pointer"
                />
                <span class="mt-2">Other Files</span>
              </div>
            </div>
            <div class="preview_content" v-for="(file,n) in images" :key="n">
              <vs-icon
                @click="deletePreview(n)"
                icon="icon-x"
                icon-pack="feather"
                class="delete_preview pointer"
              />
              <el-image
                class="employee_profile_image preview_images openicon mr-3"
                :preview-src-list="file.srcList"
                :src="file.url"
              >
                <div slot="placeholder" class="image-slot">
                  <i class="el-icon-loading" style="font-size:18px !important; color:#fff;"></i>
                </div>
                <div slot="error" class="image-slot">
                  <i class="feather icon-image"></i>
                </div>
              </el-image>
            </div>
            <div
              class="audio_preview_div"
              v-if="FileDetails.file_path && FileDetails.file_type==='audio'"
            >
              <vue-audio @ClearFile="ClearFile" class="review_audio" :file="FileDetails.file_path" />
              <p class="mt-3 mb-2 text-small">
                <span class="text-bold">Name :</span>
                {{FileDetails.file_name}}
              </p>
              <p class="mt-0 mb-0 text-small">
                <span class="text-bold">Size :</span>
                {{FileDetails.size}} MB
              </p>
            </div>
            <div
              style="width: 100%;"
              class="video_preview_div flex"
              v-if="FileDetails.file_path && FileDetails.file_type==='video'"
            >
              <Media
                class="video_preview"
                :kind="'video'"
                :controls="true"
                :src="[FileDetails.file_path]"
              ></Media>
              <div class="video_details ml-3">
                <p class="mb-2 text-small">
                  <span class="text-bold">Name :</span>
                  {{FileDetails.file_name}}
                </p>
                <p class="mt-0 mb-0 text-small">
                  <span class="text-bold">Size :</span>
                  {{FileDetails.size}} MB
                </p>
              </div>
              <vs-button
                size="35px"
                color="danger"
                type="flat"
                radius
                class="clearFile ml-3"
                @click="ClearFile()"
                icon-pack="feather"
                icon="icon-x"
              ></vs-button>
            </div>
            <div
              style="width: 100%;"
              class="video_preview_div flex flex-between"
              v-if="FileDetails.file_path && FileDetails.file_type!=='video' && FileDetails.file_type!=='audio' && FileDetails.file_type!=='image'"
            >
              <div class="flex-align-center">
                <a :href="FileDetails.file_path" target="_blank">
                  <img
                    class="other_file_preview"
                    :src="`/storage/image/${FileDetails.file_type}.png`"
                    alt
                  />
                </a>
                <div class="video_details ml-3">
                  <p class="mb-2 text-small">
                    <span class="text-bold">Name :</span>
                    {{FileDetails.file_name}}
                  </p>
                  <p class="mt-0 mb-0 text-small">
                    <span class="text-bold">Size :</span>
                    {{FileDetails.size}} MB
                  </p>
                </div>
              </div>

              <vs-button
                size="35px"
                color="danger"
                type="flat"
                radius
                class="clearFile ml-3"
                @click="ClearFile()"
                icon-pack="feather"
                icon="icon-x"
              ></vs-button>
            </div>
          </div>
          <!-- Message Composer -->
          <div class="chat_composer" v-if="selectedContact">
            <div class="ml-3 flex-align-center">
              <vs-icon
                @click="show_preview=!show_preview;show_upload_btns=!show_upload_btns"
                :class="{rotate_btn: show_upload_btns}"
                icon="icon-plus-circle"
                icon-pack="feather"
                class="more_btns_chat pointer"
                style="font-size:22px !important;"
              />
              <vs-icon
                @click="show_preview=!show_preview;show_emoji=!show_emoji"
                icon="mood"
                style="font-size:24px !important;"
                class="more_btns_chat ml-2 pointer"
              />
            </div>
            <vs-input
              class="typing"
              @focus="sendTypingEvent"
              color="primary"
              placeholder="Type Here..."
              @keydown.enter="send"
              v-model="message"
            />
            <vs-icon icon="icon-send" icon-pack="feather" @click="send" v-if="mobile" />
            <vs-button
              color="primary"
              class="chat_btn"
              @click="send"
              v-if="!mobile"
              type="filled"
            >Send</vs-button>
          </div>
        </div>
      </slide-y-down-transition>
    </div>
  </div>
</template>

<script>
import VueAudio from "../../components/VueAudio.vue";
import Media from "@dongido/vue-viaudio";
import SimleEmoji from "../../assets/Json/emoji/emoji-group-smileys-emotion.json";
import peopleEmoji from "../../assets/Json/emoji/emoji-group-people-body.json";
import foodEmoji from "../../assets/Json/emoji/emoji-group-food-drink.json";
import objectEmoji from "../../assets/Json/emoji/emoji-group-objects.json";
import sybomlsEmoji from "../../assets/Json/emoji/emoji-group-symbols.json";
import activitiesEmoji from "../../assets/Json/emoji/emoji-group-activities.json";
import animalsEmoji from "../../assets/Json/emoji/emoji-group-animals-nature.json";
import travelEmoji from "../../assets/Json/emoji/emoji-group-travel-places.json";
// import flagsEmoji from "../../assets/Json/emoji/emoji-group-flags.json";

export default {
  props: {
    auth: {},
    mobile: false
  },
  data() {
    return {
      getting_message: false,
      hide_message: false,
      show_option: 0,
      activitiesEmoji: activitiesEmoji.emojiList,
      // flagsEmoji: flagsEmoji.emojiList,
      animalsEmoji: animalsEmoji.emojiList,
      travelEmoji: travelEmoji.emojiList,
      sybomlsEmoji: sybomlsEmoji.emojiList,
      foodEmoji: foodEmoji.emojiList,
      objectEmoji: objectEmoji.emojiList,
      peopleEmoji: peopleEmoji.emojiList,
      SimleEmoji: SimleEmoji.emojiList,
      show_emoji: false,
      ReadIndex: 0,
      show_upload_btns: false,
      loaded: false,
      loading: false,
      getMessage: 10,
      show_preview: false,
      visible: false,
      active: true,
      user: [],
      selectedContact: null,
      messages: [],
      contacts: [],
      filterData: [],
      message: "",
      recentChat: [],
      otherContacts: [],
      query: null,
      selected_id: null,
      no_data: false,
      images: [],
      otherFile: null,
      FileDetails: {},
      fileLimit: "",
      LimitWithExtension: false,
      AllowExtensions: [
        "pdf",
        "docx",
        "xlsx",
        "ods",
        "csv",
        "zip",
        "rar",
        "apk"
      ]
    };
  },
  components: {
    "vue-audio": VueAudio,
    Media
  },
  created() {
    if (this.mobile) {
      this.hide_message = true;
    } else {
      this.hide_message = false;
    }
    var index = this.$route.meta.index;
    this.$emit("index", index);
    this.getcontact();
    this.getRecent();
  },
  mounted() {
    this.echoMessage();

    this.listen();
  },
  watch: {
    query: function(newVal, oldVal) {
      this.search(newVal);
    }
  },
  methods: {
    startConversation(data) {
      this.getting_message = true;
      this.startConversationWith(data);
    },
    back_() {
      this.hide_message = !this.hide_message;
      this.selectedContact = [];
      this.messages = [];
    },
    removeMessage(id, index) {
      axios
        .post(`web/removeMessage`, { id: id })
        .then(response => {
          this.messages[index].removable = false;
          this.messages[index].removed = true;
          this.selectedContact.recent_message = "Removed";
        })
        .catch(error => {});
    },
    setEmoji(data) {
      this.message += data;
    },
    ClearFile(e) {
      this.show_preview = false;
      this.otherFile = null;
      this.FileDetails = {};
    },
    listen() {
      Echo.join("App")
        .listen("UserOnline", e => {
          var id = e.user.id;
          if (id !== this.auth.id) {
            var index = this.contacts
              .map(x => {
                return x.id;
              })
              .indexOf(id);

            this.contacts[index].status = "online";
          }
        })
        .listen("UserOffline", e => {
          var id = e.user.id;
          if (id !== this.auth.id) {
            var index = this.contacts
              .map(x => {
                return x.id;
              })
              .indexOf(id);
            this.contacts[index].status = "offline";
          }
        });
    },
    GetMoreMessage() {
      this.getMessage = parseInt(this.getMessage) + 10;
      this.loading = true;
      this.startConversationWith(this.selectedContact);
    },
    search(query) {
      var array = this.filterData.filter(data =>
        data.name.toLowerCase().includes(query.toLowerCase())
      );
      if (array.length != 0) {
        this.no_data = false;
        this.contacts = array;
        this.separateContact();
      } else {
        this.no_data = true;
      }
    },
    getcontact() {
      axios.get("web/contacts").then(response => {
        this.contacts = response.data;
      });
    },
    echoMessage() {
      Echo.private(`messages.${this.auth.id}`)
        .listen("NewMessage", e => {
          var id = e.message.from;
          var index = this.contacts
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.contacts[index].recent_message = e.message.text; //Update recent_message when new message arrive
          this.hanleIncoming(e.message);
        })
        .listen("TypingEvent", e => {
          var id = e.data.from;
          var index = this.contacts
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          this.contacts[index].typing = e.data.typing;
          setTimeout(() => {
            this.contacts[index].typing = false;
          }, 3000);
          this.scrollToBottom();
        })
        .listen("ReadAllMessage", e => {
          var id = e.id.to;
          var data = this.messages.filter(function(data) {
            return data.read === 0;
          });
          if (data) {
            data.forEach(message => {
              var message_id = message.id;
              var index = this.messages
                .map(x => {
                  return x.id;
                })
                .indexOf(message_id);
              this.messages[index].read = 1;
            });
          }
          this.filterRead();
        })
        .listen("DeliveredMessage", e => {
          var id = e.data.to;
          var data = this.messages.filter(function(data) {
            return data.delivered === 0;
          });
          if (data) {
            data.forEach(message => {
              var message_id = message.id;
              var index = this.messages
                .map(x => {
                  return x.id;
                })
                .indexOf(message_id);
              this.messages[index].delivered = true;
            });
          }
        })
        .listen("RemoveMessage", e => {
          var id = e.message.id;
          var index = this.messages
            .map(x => {
              return x.id;
            })
            .indexOf(id);
          if (this.messages[index]) {
            this.messages[index].removed = true;
          }
          var contact_id = e.message.from;
          var contact_index = this.contacts
            .map(x => {
              return x.id;
            })
            .indexOf(contact_id);
          this.contacts[contact_index].recent_message = "Removed";
        });
    },

    getRecent() {
      axios
        .get(`web/getRecent`)
        .then(response => {
          response.data.forEach(data => {
            var send_messages = data.send__messages.filter(function(message) {
              return (
                message.from === data.auth_id && message.to === data.user_id
              );
            });
            var receive_messages = data.receive__messages.filter(function(
              message
            ) {
              return (
                message.from === data.user_id && message.to === data.auth_id
              );
            });
            var messages = send_messages.concat(receive_messages); //Complie two arrays into one
            messages = messages.sort(function(a, b) {
              return b.id - a.id || a.created_at.localeCompare(b.created_at);
            }); // reorder messages from backend

            //put messages to contacts
            var recentChat_contacts = this.contacts.filter(function(contact) {
              return contact.id === data.user_id;
            });
            if (recentChat_contacts.length !== 0 && messages.length !== 0) {
              if (messages[0].removed) {
                recentChat_contacts[0].recent_message = "Removed";
              } else {
                recentChat_contacts[0].recent_message = messages[0].text;
              }
              var id = recentChat_contacts[0].id;
              var index = this.contacts
                .map(x => {
                  return x.id;
                })
                .indexOf(id);
              this.contacts[index] = recentChat_contacts[0];
            }
          });
          this.filterData = this.contacts;
          this.separateContact();
        })
        .catch(error => {});
    },
    separateContact() {
      this.recentChat = this.contacts.filter(function(data) {
        return data.recent_message !== "";
      });

      this.otherContacts = this.contacts.filter(function(data) {
        return data.recent_message === "";
      });
    },
    startConversationWith(contact) {
      this.selected_id = contact.id;
      this.updateUnreadCount(contact, true);
      this.updateRead();
      this.hide_message = false;
      if (this.mobile) {
        this.active = false;
      }
      axios
        .post(`web/conversation/`, {
          contact_id: contact.id,
          getMessage: this.getMessage
        })
        .then(response => {
          var messages = response.data;
          this.messages = messages.sort(function(a, b) {
            return a.id - b.id || a.created_at.localeCompare(b.created_at);
          });
          this.filterRead();
          this.selectedContact = contact;

          this.scrollToBottom();
          this.loading = false;
          this.loaded = true;
          this.$emit("read");
          this.getting_message = false;
        });
    },
    saveNewMessage(message) {
      message.read = 0;
      message.delivered = 0;
      this.messages.push(message);

      this.scrollToBottom();
    },
    filterRead() {
      var filter = this.messages.filter(function(data) {
        return data.read === 1;
      });
      if (filter.length !== 0) {
        var id = filter[parseInt(filter.length) - 1].id;
        var index = this.messages
          .map(x => {
            return x.id;
          })
          .indexOf(id);
        this.ReadIndex = index;
      } else {
        this.ReadIndex = 0;
      }
    },
    hanleIncoming(message) {
      if (this.selectedContact && message.from == this.selectedContact.id) {
        this.saveNewMessage(message);
        this.updateUnreadCount(message.from_contact, true);
        this.updateRead();
        return;
      } else {
        this.updateUnreadCount(message.from_contact, false);
      }
    },
    updateRead() {
      axios
        .post(`web/readAllMessage/`, {
          from: this.selected_id,
          to: this.auth.id
        })
        .then(response => {})
        .catch(error => {});
    },
    updateUnreadCount(contact, reset) {
      this.contacts = this.contacts.map(single => {
        if (single.id !== contact.id) {
          return single;
        }
        if (reset) single.unread = 0;
        else single.unread += 1;

        return single;
      });
    },
    scrollToBottom() {
      setTimeout(() => {
        this.$refs.feed.scrollTop =
          this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
      }, 50);
    },
    sendTypingEvent() {
      this.scrollToBottom();
      axios
        .post(`web/typing`, { contact_id: this.selectedContact.id })
        .then(response => {})
        .catch(error => {});
    },
    send(e) {
      e.preventDefault();

      if (this.message == "" && !this.otherFile && this.images.length === 0) {
        return;
      }

      if (!this.selectedContact) {
        return;
      }
      this.show_emoji = false;
      this.show_preview = false;

      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("contact_id", this.selectedContact.id);
      formData.append("text", this.message);

      if (this.images[0]) {
        formData.append("file1", this.images[0].file);
      }
      if (this.images[1]) {
        formData.append("file2", this.images[1].file);
      }
      if (this.images[2]) {
        formData.append("file3", this.images[2].file);
      }
      if (this.images[3]) {
        formData.append("file4", this.images[3].file);
      }
      if (this.images[4]) {
        formData.append("file5", this.images[4].file);
      }
      if (this.images[5]) {
        formData.append("file6", this.images[5].file);
      }
      if (this.otherFile) {
        formData.append("otherFile", this.otherFile);
        formData.append("otherFileDetails", JSON.stringify(this.FileDetails));
      }

      this.message = "";
      axios
        .post("web/conversation/send", formData, config)
        .then(response => {
          var Newmessage = response.data;
          Newmessage.to = parseInt(Newmessage.to);
          var images = [];
          if (this.images.length !== 0) {
            this.images.forEach(image => {
              var array = {};
              array.file_name = image.url;
              images.push(array);
              array = {};
            });
            this.images = [];
            Newmessage.images = images;
          }
          if (this.otherFile) {
            Newmessage.files = this.FileDetails;
            this.otherFile = null;
            this.FileDetails = {};
          }
          Newmessage.removable = true;
          this.saveNewMessage(Newmessage);
          this.removableTimeout(Newmessage.id);
        })
        .catch(error => {});
      this.getRecent();
    },
    removableTimeout(id) {
      var index = this.messages
        .map(x => {
          return x.id;
        })
        .indexOf(id);
      // setTimeout(() => {
      //   this.messages[index].removable = false;
      // }, 60000);
    },
    onFileChange(e) {
      for (let i = 0; i < e.target.files.length; i++) {
        const element = e.target.files[i];
        if (element.type.split("/")[0] !== "image") {
          alert("You can only upload image file!");
          return false;
        }
      }

      if (parseInt(e.target.files.length) > 6) {
        alert("You can only upload a maximum of 9 files");
        return false;
      }
      var data = {};
      var srcList = [];
      for (let i = 0; i < 6; i++) {
        if (e.target.files[i]) {
          const file = e.target.files[i];
          var url = URL.createObjectURL(file);
          data.file = file;
          data.url = url;
          srcList.push(url);
          data.srcList = srcList;
          this.images.push(data);
          data = {};
          srcList = [];
        }
      }
      this.show_upload_btns = false;
      this.show_preview = true;
    },
    onFileChange2(e) {
      if (this.LimitWithExtension) {
        var getExtension = e.target.files[0].name.split(".").pop();
        var check = this.AllowExtensions.filter(function(data) {
          return data === getExtension;
        });
        if (check.length === 0) {
          alert("This file type is not permitted!");
          return false;
        }
        var getType = getExtension;
      } else {
        var getType = e.target.files[0].type.split("/")[0];
        if (getType !== this.fileLimit) {
          alert(`Only ${this.fileLimit} type can be uploaded!`);
          return false;
        }
      }

      this.show_upload_btns = false;
      this.otherFile = e.target.files[0];
      this.FileDetails.size = (this.otherFile.size / 1000000).toFixed(2); //in MB
      this.FileDetails.file_path = URL.createObjectURL(this.otherFile);
      this.FileDetails.file_name = this.otherFile.name;
      this.FileDetails.file_type = getType;
      this.show_preview = true;
    },
    clickFile2(data) {
      if (data === "Other") {
        this.LimitWithExtension = true;
      } else {
        this.LimitWithExtension = false;
        this.fileLimit = data;
      }
      this.$refs.InputFile2.click();
    },
    deletePreview(index) {
      this.images.splice(index, 1);
      if (this.images.length === 0) {
        this.show_preview = false;
      }
    }
  }
};
</script>

