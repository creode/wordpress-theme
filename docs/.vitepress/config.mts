import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "WordPress Theme Framework - Documentation",
  description: "Documentation for Creode's WordPress Theme Framework.",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Installation', link: '/installation' },
    ],

    sidebar: [
      {
        text: 'Setup',
        items: [
          { text: 'Introduction', link: '/' },
          { text: 'Installation', link: '/installation' },
          { text: 'Migrating from Theme Core', link: '/migrating-from-theme-core' }
        ]
      },
      {
        text: 'Commands',
        items: [
          { text: 'Commands', link: '/commands' }
        ]
      }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/vuejs/vitepress' }
    ]
  }
})
