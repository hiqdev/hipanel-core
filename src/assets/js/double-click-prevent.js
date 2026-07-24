$("form[method=\"post\"] :submit").click(function () {
  const btn = $(this);
  btn.attr("data-loading-text", this.innerHTML);
  btn.button("loading");
  setTimeout(function () {
    btn.button("reset");
  }, 1000);
});
