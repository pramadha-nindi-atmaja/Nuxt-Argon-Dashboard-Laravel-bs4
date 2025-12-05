<template>
  <div class="card card-profile">
    <img src="/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">

    <div class="row justify-content-center">
      <div class="col-lg-3 order-lg-2">
        <div class="card-profile-image">
          <a href="#">
            <img :src="profileImageUrl" class="rounded-circle">
          </a>
        </div>
      </div>
    </div>

    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
      <div class="d-flex justify-content-between">
        <a href="#" class="btn btn-sm btn-info mr-4">Connect</a>
        <a href="#" class="btn btn-sm btn-default float-right">Message</a>
      </div>
    </div>

    <div class="card-body pt-0">
      <div class="row">
        <div class="col">
          <div class="card-profile-stats d-flex justify-content-center">
            <div>
              <span class="heading">22</span>
              <span class="description">Friends</span>
            </div>
            <div>
              <span class="heading">10</span>
              <span class="description">Photos</span>
            </div>
            <div>
              <span class="heading">89</span>
              <span class="description">Comments</span>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center">
        <h5 class="h3">
          {{ userName }}<span class="font-weight-light">, 27</span>
        </h5>
        <div class="h5 font-weight-300">
          <i class="ni location_pin mr-2"></i>Bucharest, Romania
        </div>
        <div class="h5 mt-4">
          <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
        </div>
        <div>
          <i class="ni education_hat mr-2"></i>University of Computer Science
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    user: {
      type: Object,
      default: () => ({
        name: 'Jessica Jones',
        profile_image: null
      })
    }
  },
  
  computed: {
    profileImageUrl() {
      if (this.user && this.user.profile_image) {
        // If profile_image is a full URL, use it directly
        if (this.user.profile_image.startsWith('http')) {
          return this.user.profile_image;
        }
        // Otherwise, assume it's a relative path from storage
        return `${process.env.apiUrl.replace('/api/v2', '')}/storage/${this.user.profile_image}`;
      }
      // Fallback to default image
      return '/img/theme/team-4.jpg';
    },
    
    userName() {
      return this.user && this.user.name ? this.user.name : 'Jessica Jones';
    }
  }
};
</script>
<style></style>