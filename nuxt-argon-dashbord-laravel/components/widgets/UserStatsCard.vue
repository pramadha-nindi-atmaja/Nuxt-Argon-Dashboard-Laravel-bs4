<template>
  <card class="card-stats" :show-footer-line="true">
    <div class="row">
      <div class="col">
        <slot>
          <h5 class="card-title text-uppercase text-muted mb-0" v-if="title">{{title}}</h5>
          <span class="h2 font-weight-bold mb-0" v-if="subTitle">{{subTitle}}</span>
        </slot>
      </div>

      <div class="col-auto" v-if="$slots.icon || icon">
        <slot name="icon">
          <div class="icon icon-shape text-white rounded-circle shadow"
               :class="[`bg-${type}`, iconClasses]">
            <i :class="icon"></i>
          </div>
        </slot>
      </div>
    </div>

    <p class="mt-3 mb-0 text-sm">
      <slot name="footer">
        <span class="text-success mr-2" v-if="percentage">
          <i class="fa" :class="percentageIcon"></i> {{ percentage }}%
        </span>
        <span class="text-nowrap" v-if="since">{{ since }}</span>
      </slot>
    </p>
  </card>
</template>
<script>
  import Card from '@/components/argon-core/Cards/Card.vue';

  export default {
    name: 'user-stats-card',
    components: {
      Card
    },
    props: {
      type: {
        type: String,
        default: 'primary'
      },
      icon: String,
      title: String,
      subTitle: String,
      iconClasses: [String, Array],
      percentage: String,
      percentageIcon: {
        type: String,
        default: 'fa-arrow-up'
      },
      since: String
    }
  };
</script>
<style></style>