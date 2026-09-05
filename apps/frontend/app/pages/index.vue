<template>
  <div class="mx-auto w-full max-w-7xl py-20">
    <div class="grid gap-x-32 lg:grid-cols-2 lg:items-start">
      <section class="mb-28 lg:mb-0">
        <TheHero
          title="I built yet another"
          title-accent="URL shortener"
          subtitle="Shawdy is my learning project for exploring Git, Docker, CI/CD, Symfony, API Platform, Vue.js and modern web development with a hint of Python."
          :primary-button="{
            text: 'See how it works',
            href: '/about',
            variant: 'primary',
          }"
          :secondary-button="{
            text: 'View on GitHub',
            href: 'https://github.com/fkdiy/shawdy',
            variant: 'secondary',
          }"
        />
      </section>

      <aside class="mb-8 lg:mb-0">
        <UrlShortener />
      </aside>
    </div>
  </div>
</template>
