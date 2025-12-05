<template>
  <div class="card">
    <div class="card-header">
      <h1>Edit Profile</h1>
    </div>
    <div class="card-body">
      <form ref="profile_form" @submit.prevent="handleProfileUpdate">
        <base-input
          label="Name"
          prepend-icon="fas fa-user"
          placeholder="Your name"
          v-model="user.name"
        />
        <validation-error :errors="apiValidationErrors.name" />
        <base-input
          label="Email"
          prepend-icon="fas fa-envelope"
          placeholder="Email"
          v-model="user.email"
        />
        <validation-error :errors="apiValidationErrors.email" />
        
        <!-- Profile Picture Upload -->
        <div class="form-group">
          <label class="form-control-label">Profile Picture</label>
          <div class="d-flex align-items-center">
            <div class="avatar avatar-xl rounded-circle mr-3">
              <img :src="previewImage || userProfileImage || '/img/theme/team-4.jpg'" alt="Profile Image" class="rounded-circle">
            </div>
            <div>
              <base-button type="secondary" size="sm" @click="triggerFileInput">
                <i class="fas fa-upload"></i> Choose File
              </base-button>
              <input 
                ref="fileInput" 
                type="file" 
                accept="image/*" 
                style="display: none;" 
                @change="handleFileChange"
              >
              <p v-if="selectedFile" class="text-muted text-xs mt-1 mb-0">{{ selectedFile.name }}</p>
            </div>
          </div>
        </div>
        
        <div class="my-4">
          <base-button
            type="button"
            class="btn btn-sm btn-primary"
            native-type="submit"
          >
            Submit
          </base-button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import BaseInput from "~/components/argon-core/Inputs/BaseInput.vue";
import BaseButton from "~/components/argon-core/BaseButton.vue";
import formMixin from "@/mixins/form-mixin";
import ValidationError from "~/components/ValidationError.vue";

export default {
  name: "UserEditCard",

  components: {
    BaseInput,
    BaseButton,
    ValidationError,
  },

  mixins: [formMixin],

  props: {
    user: Object,
  },

  data() {
    return {
      selectedFile: null,
      previewImage: null
    };
  },
  
  computed: {
    userProfileImage() {
      if (this.user && this.user.profile_image) {
        // If profile_image is a full URL, use it directly
        if (this.user.profile_image.startsWith('http')) {
          return this.user.profile_image;
        }
        // Otherwise, construct the full URL using the API base URL
        const baseUrl = process.env.apiUrl.replace('/api/v2', '');
        return `${baseUrl}/storage/${this.user.profile_image}`;
      }
      return null;
    }
  },

  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },

    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedFile = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },

    async handleProfileUpdate() {
      if (["1"].includes(this.user.id)) {
        await this.$notify({
          type: "danger",
          message: "You are not allowed not change data of default users.",
        });
        return;
      }
      
      try {
        // First update the user profile data
        await this.$store.dispatch("profile/update", this.user);
        
        // Then upload the profile image if selected
        if (this.selectedFile) {
          await this.$store.dispatch("users/upload", {
            user: this.user,
            image: this.selectedFile
          });
          
          // Refresh the profile to get updated data
          await this.$store.dispatch("profile/me");
        }
        
        this.unsetApiValidationErrors();

        this.$notify({
          type: "success",
          message: "Profile updated successfully.",
        });
      } catch (error) {
        this.$notify({
          type: "danger",
          message: "Oops, something went wrong!",
        });
        this.setApiValidation(error.response.data.errors);
      }
    },
  },
};
</script>
<style scoped>
.avatar img {
  width: 64px;
  height: 64px;
  object-fit: cover;
}
</style>