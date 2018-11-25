<style>
  .blur-up {
    filter: blur(5px);
    transition: filter 400ms;
  }

  .blur-up.lazyloaded {
    filter: blur(0);
  }
</style>

<img
  class="lazyload blur-up"
  src="$LQIP.URL"
  data-src="$URL.ATT"
  alt="$Title.ATT"
/>
