<template>
  <div class="dark-mode-toggle">
    <base-button
      @click="toggleDarkMode"
      type="neutral"
      size="sm"
      class="btn-icon-only"
    >
      <span class="btn-inner--icon">
        <i :class="darkModeIcon"></i>
      </span>
    </base-button>
  </div>
</template>

<script>
import BaseButton from "@/components/argon-core/BaseButton.vue";

export default {
  name: "DarkModeToggle",
  components: {
    BaseButton
  },
  data() {
    return {
      isDarkMode: false
    };
  },
  computed: {
    darkModeIcon() {
      return this.isDarkMode ? "ni ni-sun" : "ni ni-moon";
    }
  },
  mounted() {
    // Check for saved theme preference or respect OS preference
    const savedTheme = localStorage.getItem("theme");
    const osPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    
    if (savedTheme === "dark" || (!savedTheme && osPrefersDark)) {
      this.enableDarkMode();
    }
  },
  methods: {
    toggleDarkMode() {
      if (this.isDarkMode) {
        this.disableDarkMode();
      } else {
        this.enableDarkMode();
      }
    },
    enableDarkMode() {
      document.body.classList.add("dark-mode");
      localStorage.setItem("theme", "dark");
      this.isDarkMode = true;
      
      // Update chart configurations for dark mode
      this.$emit("dark-mode-changed", true);
    },
    disableDarkMode() {
      document.body.classList.remove("dark-mode");
      localStorage.setItem("theme", "light");
      this.isDarkMode = false;
      
      // Update chart configurations for light mode
      this.$emit("dark-mode-changed", false);
    }
  }
};
</script>

<style scoped>
.dark-mode-toggle {
  display: inline-block;
  margin-left: 10px;
}
</style>